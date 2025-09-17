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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if ($user) {
            Session::setUser($user);
            
            // Redirect to admin dashboard if admin, otherwise to home
            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error = 'Email atau password salah';
        }
    }
}

// Page configuration
$title = 'Login - Kopi Cepoko';
$description = 'Login ke akun Kopi Cepoko Anda';
$currentPage = 'login';
$addTopMargin = true;

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container" style="max-width: 500px;">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center mb-4" style="color: var(--primary-color);">
                    <i class="fas fa-sign-in-alt"></i> Login
                </h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
                
                <div class="text-center">
                    <p>Belum punya akun? <a href="register.php" style="color: var(--secondary-color);">Daftar di sini</a></p>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <small style="color: #666;">
                        <strong>Demo Accounts:</strong><br>
                        Admin: admin@kopicepoko.com / admin123<br>
                        User: user@example.com / user123
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>