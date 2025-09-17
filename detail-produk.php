<?php
require_once 'app/Models/Product.php';
require_once 'includes/Session.php';

Session::start();

// Initialize database connection
try {
    require_once 'includes/Database.php';
    Database::getInstance();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

$productModel = new Product();

// Get product ID
$productId = intval($_GET['id'] ?? 0);

if (!$productId) {
    header('Location: produk.php');
    exit;
}

// Get product details
$product = $productModel->findById($productId);

if (!$product) {
    Session::setMessage('danger', 'Produk tidak ditemukan');
    header('Location: produk.php');
    exit;
}

// Get related products (same category)
$relatedProducts = $productModel->getAll(4, '', $product['category']);
$relatedProducts = array_filter($relatedProducts, function($p) use ($productId) {
    return $p['id'] != $productId;
});
$relatedProducts = array_slice($relatedProducts, 0, 3);

// Page configuration
$title = htmlspecialchars($product['name']) . ' - Kopi Cepoko';
$description = htmlspecialchars(substr($product['description'], 0, 160));
$currentPage = 'products';
$addTopMargin = true;

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem;">
            <span style="color: #666;">
                <a href="index.php" style="color: var(--secondary-color); text-decoration: none;">Beranda</a>
                <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.8rem;"></i>
                <a href="produk.php" style="color: var(--secondary-color); text-decoration: none;">Produk</a>
                <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.8rem;"></i>
                <span><?= htmlspecialchars($product['name']) ?></span>
            </span>
        </nav>
        
        <!-- Product Details -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <!-- Product Image -->
            <div>
                <img src="<?= !empty($product['image']) ? 'uploads/products/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600' ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     style="width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            </div>
            
            <!-- Product Info -->
            <div>
                <div style="background: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 25px; display: inline-block; margin-bottom: 1rem;">
                    <?= htmlspecialchars($product['category']) ?>
                </div>
                
                <h1 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <?= htmlspecialchars($product['name']) ?>
                </h1>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">
                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </span>
                    
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <?php if ($product['stock'] > 10): ?>
                            <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                            <span style="color: var(--success-color); font-weight: bold;">Stok Tersedia</span>
                        <?php elseif ($product['stock'] > 0): ?>
                            <i class="fas fa-exclamation-circle" style="color: var(--warning-color);"></i>
                            <span style="color: var(--warning-color); font-weight: bold;">Stok Terbatas (<?= $product['stock'] ?>)</span>
                        <?php else: ?>
                            <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                            <span style="color: var(--danger-color); font-weight: bold;">Stok Habis</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Deskripsi Produk</h3>
                    <p style="line-height: 1.8; color: #333;">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>
                </div>
                
                <!-- Product Features -->
                <div style="background: var(--background-light); padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Keunggulan Produk</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i>
                            100% Biji Kopi Asli Indonesia
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i>
                            Proses Roasting Manual dengan Kualitas Premium
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i>
                            Kemasan Kedap Udara untuk Menjaga Kesegaran
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <i class="fas fa-check" style="color: var(--success-color); margin-right: 0.5rem;"></i>
                            Gratis Ongkir untuk Pembelian di Atas Rp 100.000
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <?php if ($product['stock'] > 0): ?>
                        <?php if (Session::isLoggedIn()): ?>
                            <button onclick="addToCart(<?= $product['id'] ?>)" class="btn btn-primary" style="flex: 1; min-width: 200px;">
                                <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary" style="flex: 1; min-width: 200px;">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled style="flex: 1; min-width: 200px;">
                            <i class="fas fa-times"></i> Stok Habis
                        </button>
                    <?php endif; ?>
                    
                    <a href="https://wa.me/6281234567890?text=Saya tertarik dengan produk <?= urlencode($product['name']) ?>" 
                       target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                    </a>
                </div>
                
                <!-- Product Specifications -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #eee;">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Spesifikasi</h4>
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 0.5rem 2rem;">
                        <strong>Kategori:</strong>
                        <span><?= htmlspecialchars($product['category']) ?></span>
                        
                        <strong>Berat:</strong>
                        <span>250 gram</span>
                        
                        <strong>Kemasan:</strong>
                        <span>Standing Pouch dengan Valve</span>
                        
                        <strong>Masa Simpan:</strong>
                        <span>12 bulan</span>
                        
                        <strong>Asal Daerah:</strong>
                        <span>Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<section class="section" style="background-color: var(--background-light);">
    <div class="container">
        <h2 class="section-title">Produk Terkait</h2>
        <div class="product-grid">
            <?php foreach ($relatedProducts as $related): ?>
                <div class="card">
                    <img src="<?= !empty($related['image']) ? 'uploads/products/' . htmlspecialchars($related['image']) : 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400' ?>" 
                         class="card-img" alt="<?= htmlspecialchars($related['name']) ?>">
                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($related['name']) ?></h3>
                        <p class="card-text"><?= htmlspecialchars(substr($related['description'], 0, 100)) ?>...</p>
                        <div class="card-price">Rp <?= number_format($related['price'], 0, ',', '.') ?></div>
                        <div class="d-flex justify-between align-center">
                            <small>Stok: <?= $related['stock'] ?></small>
                            <a href="detail-produk.php?id=<?= $related['id'] ?>" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
@media (max-width: 768px) {
    .section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    .section div[style*="display: flex"] {
        flex-direction: column !important;
    }
    
    .section div[style*="display: grid"][style*="auto 1fr"] {
        grid-template-columns: auto 1fr !important;
    }
}
</style>

<script>
function addToCart(productId) {
    // This would typically make an AJAX call to add the product to cart
    // For now, we'll show a simple alert
    alert('Fitur keranjang belanja akan segera tersedia. Silakan hubungi kami via WhatsApp untuk pemesanan.');
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>