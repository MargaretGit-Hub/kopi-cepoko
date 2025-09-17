<?php
require_once 'app/Models/User.php';
require_once 'includes/Session.php';

Session::start();

// Redirect if already logged in
if (Session::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Semua field wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak sesuai';
    } else {
        $userModel = new User();
        
        // Check if email already exists
        if ($userModel->emailExists($email)) {
            $error = 'Email sudah terdaftar';
        } else {
            // Create user
            $userData = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address
            ];
            
            if ($userModel->create($userData)) {
                $success = 'Akun berhasil dibuat. Silakan login.';
                // Clear form data
                $_POST = [];
            } else {
                $error = 'Gagal membuat akun. Silakan coba lagi.';
            }
        }
    }
}

// Page configuration
$title = 'Daftar - Kopi Cepoko';
$description = 'Daftar akun baru di Kopi Cepoko';
$currentPage = 'register';
$addTopMargin = true;

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container" style="max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4" style="color: var(--primary-color);">
                    <i class="fas fa-user-plus"></i> Daftar Akun Baru
                </h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control" 
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                               required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" 
                                   minlength="6" required>
                            <small style="color: #666;">Minimal 6 karakter</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password *</label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   minlength="6" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" name="phone" class="form-control" 
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
                               placeholder="Contoh: 081234567890">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="3" 
                                  placeholder="Alamat lengkap untuk pengiriman"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus"></i> Daftar
                    </button>
                </form>
                
                <div class="text-center">
                    <p>Sudah punya akun? <a href="login.php" style="color: var(--secondary-color);">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>