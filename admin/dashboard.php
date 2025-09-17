<?php
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Product.php';
require_once __DIR__ . '/../app/Models/Order.php';
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

// Get dashboard statistics
$userModel = new User();
$productModel = new Product();
$orderModel = new Order();

$totalUsers = $userModel->getCount();
$totalProducts = $productModel->getCount();
$totalOrders = $orderModel->getCount();
$totalRevenue = $orderModel->getTotalRevenue();

$recentOrders = $orderModel->getRecentOrders(5);
$featuredProducts = $productModel->getFeatured(4);

// Page configuration
$title = 'Dashboard Admin - Kopi Cepoko';
$description = 'Panel admin untuk mengelola Kopi Cepoko';
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
                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                </h1>
                <p style="color: #666;">Selamat datang, <?= htmlspecialchars(Session::get('user_name')) ?></p>
            </div>
            <div class="d-flex gap-2">
                <a href="../index.php" class="btn btn-outline" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Lihat Website
                </a>
                <a href="../logout.php" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="product-grid mb-4" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;"><?= $totalUsers ?></h3>
                    <p style="margin: 0; color: #666;">Total Pengguna</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-coffee" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;"><?= $totalProducts ?></h3>
                    <p style="margin: 0; color: #666;">Total Produk</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;"><?= $totalOrders ?></h3>
                    <p style="margin: 0; color: #666;">Total Pesanan</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--secondary-color); margin-bottom: 0.5rem;">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
                    <p style="margin: 0; color: #666;">Total Pendapatan</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                    <i class="fas fa-bolt"></i> Aksi Cepat
                </h3>
                <div class="d-flex gap-2" style="flex-wrap: wrap;">
                    <a href="products.php?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-coffee"></i> Kelola Produk
                    </a>
                    <a href="users.php" class="btn btn-secondary">
                        <i class="fas fa-users"></i> Kelola Pengguna
                    </a>
                    <a href="orders.php" class="btn btn-secondary">
                        <i class="fas fa-shopping-cart"></i> Kelola Pesanan
                    </a>
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Recent Orders -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-shopping-cart"></i> Pesanan Terbaru
                    </h3>
                    
                    <?php if (empty($recentOrders)): ?>
                        <p style="color: #666; text-align: center; padding: 2rem;">
                            Belum ada pesanan
                        </p>
                    <?php else: ?>
                        <div style="max-height: 400px; overflow-y: auto;">
                            <?php foreach ($recentOrders as $order): ?>
                                <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                                    <div class="d-flex justify-between align-center mb-2">
                                        <strong style="color: var(--primary-color);">Order #<?= $order['id'] ?></strong>
                                        <span style="background: var(--warning-color); color: var(--text-dark); padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem;">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </div>
                                    <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">
                                        <?= htmlspecialchars($order['user_name']) ?> - <?= htmlspecialchars($order['user_email']) ?>
                                    </p>
                                    <p style="margin: 0.5rem 0; color: #666; font-size: 0.9rem;">
                                        <?= htmlspecialchars($order['items']) ?>
                                    </p>
                                    <div class="d-flex justify-between align-center">
                                        <strong style="color: var(--secondary-color);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                                        <small style="color: #666;"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="orders.php" class="btn btn-primary">Lihat Semua Pesanan</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Featured Products -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-star"></i> Produk Unggulan
                    </h3>
                    
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($featuredProducts as $product): ?>
                            <div style="border-bottom: 1px solid #eee; padding: 1rem 0; display: flex; gap: 1rem; align-items: center;">
                                <img src="<?= !empty($product['image']) ? '../uploads/products/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=80' ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-color); font-size: 1rem;">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </h4>
                                    <div class="d-flex justify-between align-center">
                                        <span style="color: var(--secondary-color); font-weight: bold;">
                                            Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                        </span>
                                        <small style="color: #666;">Stok: <?= $product['stock'] ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="products.php" class="btn btn-primary">Kelola Produk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .d-flex.justify-between {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .section div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    
    .d-flex.gap-2 {
        flex-direction: column !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>