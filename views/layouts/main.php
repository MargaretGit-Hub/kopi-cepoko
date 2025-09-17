<?php
require_once __DIR__ . '/../../includes/Session.php';
Session::start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kopi Cepoko - UMKM Kopi Terbaik' ?></title>
    <meta name="description" content="<?= $description ?? 'Kopi Cepoko menyediakan kopi berkualitas tinggi dari petani lokal Indonesia' ?>">
    
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= $baseUrl ?? '' ?>/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $baseUrl ?? '' ?>/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= $baseUrl ?? '' ?>/index.php">
                <i class="fas fa-coffee"></i> Kopi Cepoko
            </a>
            
            <button class="navbar-toggle" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <ul class="navbar-nav" id="navbarNav">
                <li><a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/index.php">Beranda</a></li>
                <li><a class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/produk.php">Produk</a></li>
                <li><a class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/tentang.php">Tentang Kami</a></li>
                <li><a class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/kontak.php">Kontak</a></li>
                
                <?php if (Session::isLoggedIn()): ?>
                    <li><a class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/profile.php">
                        <i class="fas fa-user"></i> <?= htmlspecialchars(Session::get('user_name')) ?>
                    </a></li>
                    <li><a class="nav-link" href="<?= $baseUrl ?? '' ?>/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
                    
                    <?php if (Session::isAdmin()): ?>
                        <li><a class="nav-link" href="<?= $baseUrl ?? '' ?>/admin/dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Admin
                        </a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a class="nav-link <?= $currentPage === 'login' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/login.php">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a></li>
                    <li><a class="nav-link <?= $currentPage === 'register' ? 'active' : '' ?>" href="<?= $baseUrl ?? '' ?>/register.php">
                        <i class="fas fa-user-plus"></i> Daftar
                    </a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php 
    $message = Session::getMessage();
    if ($message): 
    ?>
        <div style="margin-top: 80px; padding: 0 20px;">
            <div class="container">
                <div class="alert alert-<?= $message['type'] ?>">
                    <?= htmlspecialchars($message['message']) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main<?= isset($addTopMargin) && $addTopMargin ? ' style="margin-top: 80px;"' : '' ?>>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-coffee"></i> Kopi Cepoko</h3>
                    <p>UMKM kopi terpercaya yang menghadirkan cita rasa kopi terbaik Indonesia untuk Anda.</p>
                    <div class="d-flex gap-2 mt-2">
                        <a href="#" style="color: var(--text-light);"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" style="color: var(--text-light);"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" style="color: var(--text-light);"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="<?= $baseUrl ?? '' ?>/index.php">Beranda</a></li>
                        <li><a href="<?= $baseUrl ?? '' ?>/produk.php">Produk</a></li>
                        <li><a href="<?= $baseUrl ?? '' ?>/tentang.php">Tentang Kami</a></li>
                        <li><a href="<?= $baseUrl ?? '' ?>/kontak.php">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Kontak Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Kopi No. 123, Jakarta</p>
                    <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@kopicepoko.com</p>
                </div>
                
                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <p>Dapatkan update produk terbaru dan promo menarik!</p>
                    <form action="<?= $baseUrl ?? '' ?>/newsletter.php" method="POST" class="mt-2">
                        <div class="d-flex gap-1">
                            <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                            <button type="submit" class="btn btn-warning">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Kopi Cepoko. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        function toggleMenu() {
            const nav = document.getElementById('navbarNav');
            nav.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navbarNav');
            const toggle = document.querySelector('.navbar-toggle');
            
            if (!nav.contains(event.target) && !toggle.contains(event.target)) {
                nav.classList.remove('active');
            }
        });

        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>