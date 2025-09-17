<?php
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../includes/Session.php';
require_once __DIR__ . '/includes/auth.php';

redirectIfLoggedIn();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if ($user && $user['role'] === 'admin') {
            Session::setUser($user);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah, atau Anda bukan admin';
        }
    }
}

// Page configuration
$title = 'Admin Login - Kopi Cepoko';
$description = 'Login admin untuk mengelola Kopi Cepoko';
$currentPage = 'admin';
$addTopMargin = true;
$baseUrl = '..';

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container" style="max-width: 500px;">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h2 style="color: var(--primary-color); margin-top: 1rem;">
                        Admin Login
                    </h2>
                    <p style="color: #666;">Masuk ke panel admin Kopi Cepoko</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Email Admin</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt"></i> Login Admin
                    </button>
                </form>
                
                <div class="text-center">
                    <a href="../index.php" style="color: var(--secondary-color);">
                        <i class="fas fa-arrow-left"></i> Kembali ke Homepage
                    </a>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <small style="color: #666;">
                        <strong>Demo Admin:</strong><br>
                        Email: admin@kopicepoko.com<br>
                        Password: admin123
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>