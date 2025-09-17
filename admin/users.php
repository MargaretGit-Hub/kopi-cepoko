<?php
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../includes/Session.php';
require_once __DIR__ . '/includes/auth.php';

requireAdmin();

// Initialize database connection
try {
    require_once __DIR__ . '/../includes/Database.php';
    Database::getInstance();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

$userModel = new User();
$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Handle role change
if ($action === 'change_role' && isset($_GET['id']) && isset($_GET['role'])) {
    $userId = intval($_GET['id']);
    $newRole = $_GET['role'];
    
    if (in_array($newRole, ['admin', 'user']) && $userId !== Session::getUserId()) {
        if ($userModel->update($userId, ['role' => $newRole])) {
            Session::setMessage('success', 'Role pengguna berhasil diubah');
        } else {
            Session::setMessage('danger', 'Gagal mengubah role pengguna');
        }
    } else {
        Session::setMessage('danger', 'Tidak dapat mengubah role Anda sendiri');
    }
    header('Location: users.php');
    exit;
}

// Handle delete user
if ($action === 'delete' && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    if ($userId !== Session::getUserId()) {
        if ($userModel->delete($userId)) {
            Session::setMessage('success', 'Pengguna berhasil dihapus');
        } else {
            Session::setMessage('danger', 'Gagal menghapus pengguna');
        }
    } else {
        Session::setMessage('danger', 'Tidak dapat menghapus akun Anda sendiri');
    }
    header('Location: users.php');
    exit;
}

// Get user details for view
$viewUser = null;
if ($action === 'view' && isset($_GET['id'])) {
    $viewUser = $userModel->findById(intval($_GET['id']));
    if (!$viewUser) {
        header('Location: users.php');
        exit;
    }
}

// Get all users
$users = $userModel->getAll();

// Page configuration
$title = 'Kelola Pengguna - Admin Kopi Cepoko';
$description = 'Mengelola pengguna di Kopi Cepoko';
$currentPage = 'admin';
$addTopMargin = true;
$baseUrl = '..';

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-4">
            <div>
                <h1 style="color: var(--primary-color);">
                    <i class="fas fa-users"></i> Kelola Pengguna
                </h1>
                <nav style="color: #666;">
                    <a href="dashboard.php" style="color: var(--secondary-color); text-decoration: none;">Dashboard</a>
                    <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.8rem;"></i>
                    <span>Kelola Pengguna</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <?php if ($action !== 'list'): ?>
                    <a href="users.php" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Daftar Pengguna
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action === 'view' && $viewUser): ?>
            <!-- User Detail View -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                        Detail Pengguna
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem 2rem; max-width: 600px;">
                        <strong>ID:</strong>
                        <span><?= $viewUser['id'] ?></span>
                        
                        <strong>Nama:</strong>
                        <span><?= htmlspecialchars($viewUser['name']) ?></span>
                        
                        <strong>Email:</strong>
                        <span><?= htmlspecialchars($viewUser['email']) ?></span>
                        
                        <strong>Role:</strong>
                        <span>
                            <span style="background: <?= $viewUser['role'] === 'admin' ? 'var(--danger-color)' : 'var(--success-color)' ?>; color: white; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem;">
                                <?= ucfirst($viewUser['role']) ?>
                            </span>
                        </span>
                        
                        <strong>Telepon:</strong>
                        <span><?= $viewUser['phone'] ? htmlspecialchars($viewUser['phone']) : '-' ?></span>
                        
                        <strong>Alamat:</strong>
                        <span><?= $viewUser['address'] ? nl2br(htmlspecialchars($viewUser['address'])) : '-' ?></span>
                        
                        <strong>Bergabung:</strong>
                        <span><?= date('d F Y H:i', strtotime($viewUser['created_at'])) ?></span>
                        
                        <strong>Terakhir Update:</strong>
                        <span><?= date('d F Y H:i', strtotime($viewUser['updated_at'])) ?></span>
                    </div>
                    
                    <div class="mt-4 d-flex gap-2">
                        <?php if ($viewUser['id'] !== Session::getUserId()): ?>
                            <?php if ($viewUser['role'] === 'user'): ?>
                                <a href="users.php?action=change_role&id=<?= $viewUser['id'] ?>&role=admin" 
                                   class="btn btn-warning"
                                   onclick="return confirm('Yakin ingin menjadikan pengguna ini sebagai admin?')">
                                    <i class="fas fa-user-shield"></i> Jadikan Admin
                                </a>
                            <?php else: ?>
                                <a href="users.php?action=change_role&id=<?= $viewUser['id'] ?>&role=user" 
                                   class="btn btn-secondary"
                                   onclick="return confirm('Yakin ingin mengubah admin ini menjadi user biasa?')">
                                    <i class="fas fa-user"></i> Jadikan User
                                </a>
                            <?php endif; ?>
                            
                            <a href="users.php?action=delete&id=<?= $viewUser['id'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="fas fa-trash"></i> Hapus Pengguna
                            </a>
                        <?php else: ?>
                            <span style="color: #666; font-style: italic;">Tidak dapat mengubah akun Anda sendiri</span>
                        <?php endif; ?>
                        
                        <a href="users.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        
        <?php else: ?>
            <!-- User List -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                        Daftar Semua Pengguna (<?= count($users) ?> pengguna)
                    </h3>
                    
                    <?php if (empty($users)): ?>
                        <div class="text-center p-4">
                            <i class="fas fa-users" style="font-size: 4rem; color: var(--secondary-color); opacity: 0.5;"></i>
                            <h4 style="color: var(--primary-color); margin-top: 1rem;">Belum Ada Pengguna</h4>
                            <p style="color: #666;">Belum ada pengguna yang terdaftar.</p>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Telepon</th>
                                        <th>Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr style="<?= $user['id'] === Session::getUserId() ? 'background-color: #f8f9fa;' : '' ?>">
                                            <td><?= $user['id'] ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($user['name']) ?></strong>
                                                <?php if ($user['id'] === Session::getUserId()): ?>
                                                    <br><small style="color: var(--secondary-color);">(Anda)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <span style="background: <?= $user['role'] === 'admin' ? 'var(--danger-color)' : 'var(--success-color)' ?>; color: white; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem;">
                                                    <?= ucfirst($user['role']) ?>
                                                </span>
                                            </td>
                                            <td><?= $user['phone'] ? htmlspecialchars($user['phone']) : '-' ?></td>
                                            <td>
                                                <small style="color: #666;">
                                                    <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="users.php?action=view&id=<?= $user['id'] ?>" 
                                                       class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                       title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($user['id'] !== Session::getUserId()): ?>
                                                        <?php if ($user['role'] === 'user'): ?>
                                                            <a href="users.php?action=change_role&id=<?= $user['id'] ?>&role=admin" 
                                                               class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                               title="Jadikan Admin"
                                                               onclick="return confirm('Yakin ingin menjadikan pengguna ini sebagai admin?')">
                                                                <i class="fas fa-user-shield"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="users.php?action=change_role&id=<?= $user['id'] ?>&role=user" 
                                                               class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                               title="Jadikan User"
                                                               onclick="return confirm('Yakin ingin mengubah admin ini menjadi user biasa?')">
                                                                <i class="fas fa-user"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <a href="users.php?action=delete&id=<?= $user['id'] ?>" 
                                                           class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                           title="Hapus"
                                                           onclick="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span style="color: #ccc; padding: 0.25rem 0.5rem; font-size: 0.8rem;" title="Tidak dapat mengubah akun sendiri">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- User Statistics -->
                        <div class="mt-4" style="background: var(--background-light); padding: 1.5rem; border-radius: 10px;">
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Statistik Pengguna</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                                <div class="text-center">
                                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;">
                                        <?= count(array_filter($users, function($u) { return $u['role'] === 'admin'; })) ?>
                                    </h3>
                                    <p style="margin: 0; color: #666;">Admin</p>
                                </div>
                                <div class="text-center">
                                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;">
                                        <?= count(array_filter($users, function($u) { return $u['role'] === 'user'; })) ?>
                                    </h3>
                                    <p style="margin: 0; color: #666;">User</p>
                                </div>
                                <div class="text-center">
                                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;">
                                        <?= count($users) ?>
                                    </h3>
                                    <p style="margin: 0; color: #666;">Total</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .d-flex.justify-between {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
    
    .d-flex.gap-1, .d-flex.gap-2 {
        flex-wrap: wrap !important;
    }
    
    .table {
        font-size: 0.9rem;
    }
    
    .table td, .table th {
        padding: 8px 12px;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>