<?php
require_once 'app/Models/Product.php';
require_once 'includes/Session.php';

// Initialize database connection
try {
    require_once 'includes/Database.php';
    Database::getInstance();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get featured products
$productModel = new Product();
$featuredProducts = $productModel->getFeatured(6);

// Page configuration
$title = 'Kopi Cepoko - UMKM Kopi Terbaik';
$description = 'Kopi Cepoko menyediakan kopi berkualitas tinggi dari petani lokal Indonesia';
$currentPage = 'home';

// Start output buffering for the layout
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Selamat Datang di <span class="highlight">Kopi Cepoko</span></h1>
                <p>Nikmati cita rasa kopi terbaik dari petani lokal Indonesia. Kualitas premium dengan harga terjangkau.</p>
                <div class="hero-buttons">
                    <a href="produk.php" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Lihat Produk
                    </a>
                    <a href="tentang.php" class="btn btn-outline">
                        Tentang Kami
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=500" 
                     alt="Kopi Cepoko" style="max-width: 100%; height: auto; border-radius: 10px;">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="section" style="background-color: var(--background-light);">
    <div class="container">
        <h2 class="section-title">Produk Unggulan</h2>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="card">
                    <img src="<?= !empty($product['image']) ? 'uploads/products/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400' ?>" 
                         class="card-img" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                        <div class="card-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                        <div class="d-flex justify-between align-center">
                            <small>Stok: <?= $product['stock'] ?></small>
                            <a href="detail-produk.php?id=<?= $product['id'] ?>" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="produk.php" class="btn btn-secondary">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
            <div>
                <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=500" 
                     alt="Coffee Beans" style="width: 100%; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            </div>
            <div>
                <h2 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1.5rem;">Tentang Kopi Cepoko</h2>
                <p style="font-size: 1.2rem; margin-bottom: 1.5rem; color: var(--secondary-color);">
                    Kopi Cepoko adalah UMKM yang berdedikasi untuk menghadirkan kopi berkualitas tinggi 
                    dari berbagai daerah di Indonesia.
                </p>
                <p style="margin-bottom: 1.5rem;">
                    Kami bekerja sama langsung dengan petani kopi lokal untuk memastikan kualitas terbaik 
                    dan harga yang adil. Setiap biji kopi dipilih dengan teliti dan diproses dengan standar tinggi.
                </p>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i> 100% Kopi Asli Indonesia</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i> Bekerja sama dengan Petani Lokal</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i> Proses Quality Control Ketat</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i> Pengiriman ke Seluruh Indonesia</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section" style="background-color: var(--background-light);">
    <div class="container">
        <h2 class="section-title">Apa Kata Pelanggan Kami</h2>
        <div class="product-grid">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                    </div>
                    <p>"Pelayanan sangat memuaskan dan kualitas kopi konsisten. Highly recommended!"</p>
                    <h6 style="color: var(--primary-color); font-weight: bold;">- Sari Dewi</h6>
                    <small style="color: #666;">Bandung</small>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                    </div>
                    <p>"Arabica Gayonya mantap! Rasanya beda dari yang lain, pasti beli lagi."</p>
                    <h6 style="color: var(--primary-color); font-weight: bold;">- Budi Santoso</h6>
                    <small style="color: #666;">Jakarta</small>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                    </div>
                    <p>"Harga terjangkau tapi kualitas premium. Sudah langganan 2 tahun!"</p>
                    <h6 style="color: var(--primary-color); font-weight: bold;">- Ahmad Rizki</h6>
                    <small style="color: #666;">Surabaya</small>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .hero-content {
        grid-template-columns: 1fr !important;
        text-align: center;
    }
    
    .section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>