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

// Get search and filter parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;

// Get products
$products = $productModel->getAll($limit * 10, $search, $category); // Get more than needed for pagination
$categories = $productModel->getCategories();

// Simple pagination
$totalProducts = count($products);
$startIndex = ($page - 1) * $limit;
$paginatedProducts = array_slice($products, $startIndex, $limit);
$totalPages = ceil($totalProducts / $limit);

// Page configuration
$title = 'Produk - Kopi Cepoko';
$description = 'Koleksi lengkap produk kopi premium dari Kopi Cepoko';
$currentPage = 'products';
$addTopMargin = true;

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Koleksi Produk Kopi</h1>
        
        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="d-flex gap-2" style="flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari produk..." 
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div style="min-width: 150px;">
                        <select name="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" 
                                        <?= $category === $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if ($search || $category): ?>
                        <a href="produk.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <!-- Search Results Info -->
        <?php if ($search || $category): ?>
            <div class="mb-4">
                <p style="color: var(--secondary-color);">
                    <?php if ($search && $category): ?>
                        Hasil pencarian "<strong><?= htmlspecialchars($search) ?></strong>" 
                        dalam kategori "<strong><?= htmlspecialchars($category) ?></strong>"
                    <?php elseif ($search): ?>
                        Hasil pencarian "<strong><?= htmlspecialchars($search) ?></strong>"
                    <?php else: ?>
                        Produk dalam kategori "<strong><?= htmlspecialchars($category) ?></strong>"
                    <?php endif; ?>
                    - <?= count($products) ?> produk ditemukan
                </p>
            </div>
        <?php endif; ?>
        
        <!-- Products Grid -->
        <?php if (empty($paginatedProducts)): ?>
            <div class="text-center p-4">
                <i class="fas fa-coffee" style="font-size: 4rem; color: var(--secondary-color); opacity: 0.5;"></i>
                <h3 style="color: var(--primary-color); margin-top: 1rem;">Produk Tidak Ditemukan</h3>
                <p style="color: #666;">Maaf, tidak ada produk yang sesuai dengan pencarian Anda.</p>
                <a href="produk.php" class="btn btn-primary">Lihat Semua Produk</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($paginatedProducts as $product): ?>
                    <div class="card">
                        <img src="<?= !empty($product['image']) ? 'uploads/products/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400' ?>" 
                             class="card-img" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <div class="d-flex justify-between align-center mb-2">
                                <span style="background: var(--secondary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem;">
                                    <?= htmlspecialchars($product['category']) ?>
                                </span>
                                <span style="color: var(--success-color); font-weight: bold;">
                                    <?php if ($product['stock'] > 10): ?>
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    <?php elseif ($product['stock'] > 0): ?>
                                        <i class="fas fa-exclamation-circle"></i> Terbatas
                                    <?php else: ?>
                                        <i class="fas fa-times-circle" style="color: var(--danger-color);"></i> Habis
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <h3 class="card-title"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                            <div class="card-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                            
                            <div class="d-flex justify-between align-center mt-3">
                                <small style="color: #666;">Stok: <?= $product['stock'] ?></small>
                                <a href="detail-produk.php?id=<?= $product['id'] ?>" class="btn btn-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="text-center mt-4">
                    <div class="d-flex justify-center gap-1" style="flex-wrap: wrap;">
                        <?php if ($page > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" 
                               class="btn btn-secondary">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                               class="btn <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" 
                               class="btn btn-secondary">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <p class="mt-2" style="color: #666;">
                        Halaman <?= $page ?> dari <?= $totalPages ?> 
                        (<?= count($paginatedProducts) ?> dari <?= $totalProducts ?> produk)
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.form-control:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(210, 105, 30, 0.1);
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column !important;
    }
    
    .d-flex.gap-2 > div {
        min-width: auto !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>