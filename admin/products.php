<?php
require_once __DIR__ . '/../app/Models/Product.php';
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

$productModel = new Product();
$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        
        // Validation
        if (empty($name) || empty($description) || $price <= 0 || $stock < 0 || empty($category)) {
            $error = 'Semua field harus diisi dengan benar';
        } else {
            $productData = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'category' => $category
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = uniqid() . '.' . $fileExtension;
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        $productData['image'] = $fileName;
                    }
                }
            }
            
            if ($action === 'add') {
                if ($productModel->create($productData)) {
                    $success = 'Produk berhasil ditambahkan';
                    $_POST = []; // Clear form
                } else {
                    $error = 'Gagal menambahkan produk';
                }
            } elseif ($action === 'edit') {
                $id = intval($_POST['id']);
                if ($productModel->update($id, $productData)) {
                    $success = 'Produk berhasil diperbarui';
                } else {
                    $error = 'Gagal memperbarui produk';
                }
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($productModel->delete($id)) {
        Session::setMessage('success', 'Produk berhasil dihapus');
    } else {
        Session::setMessage('danger', 'Gagal menghapus produk');
    }
    header('Location: products.php');
    exit;
}

// Get product for editing
$editProduct = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editProduct = $productModel->findById(intval($_GET['id']));
    if (!$editProduct) {
        header('Location: products.php');
        exit;
    }
}

// Get all products for listing
$products = $productModel->getAll();
$categories = $productModel->getCategories();

// Page configuration
$title = 'Kelola Produk - Admin Kopi Cepoko';
$description = 'Mengelola produk di Kopi Cepoko';
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
                    <i class="fas fa-coffee"></i> Kelola Produk
                </h1>
                <nav style="color: #666;">
                    <a href="dashboard.php" style="color: var(--secondary-color); text-decoration: none;">Dashboard</a>
                    <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.8rem;"></i>
                    <span>Kelola Produk</span>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <?php if ($action !== 'add'): ?>
                    <a href="products.php?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                <?php endif; ?>
                <?php if ($action !== 'list'): ?>
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Daftar Produk
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if ($action === 'add' || $action === 'edit'): ?>
            <!-- Add/Edit Form -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                        <?= $action === 'add' ? 'Tambah Produk Baru' : 'Edit Produk' ?>
                    </h3>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                        <?php endif; ?>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Nama Produk *</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= htmlspecialchars($editProduct['name'] ?? $_POST['name'] ?? '') ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Kategori *</label>
                                <select name="category" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach (['Arabica', 'Robusta', 'Premium', 'Signature'] as $cat): ?>
                                        <option value="<?= $cat ?>" 
                                                <?= ($editProduct['category'] ?? $_POST['category'] ?? '') === $cat ? 'selected' : '' ?>>
                                            <?= $cat ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Deskripsi *</label>
                            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($editProduct['description'] ?? $_POST['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Harga (Rp) *</label>
                                <input type="number" name="price" class="form-control" step="1000" min="0"
                                       value="<?= $editProduct['price'] ?? $_POST['price'] ?? '' ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Stok *</label>
                                <input type="number" name="stock" class="form-control" min="0"
                                       value="<?= $editProduct['stock'] ?? $_POST['stock'] ?? '' ?>" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <?php if ($action === 'edit' && !empty($editProduct['image'])): ?>
                                <div class="mt-2">
                                    <img src="../uploads/products/<?= htmlspecialchars($editProduct['image']) ?>" 
                                         alt="Current image" style="max-width: 100px; height: auto; border-radius: 5px;">
                                    <small style="color: #666; display: block;">Gambar saat ini</small>
                                </div>
                            <?php endif; ?>
                            <small style="color: #666;">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= $action === 'add' ? 'Tambah Produk' : 'Perbarui Produk' ?>
                            </button>
                            <a href="products.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        
        <?php else: ?>
            <!-- Product List -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                        Daftar Semua Produk (<?= count($products) ?> produk)
                    </h3>
                    
                    <?php if (empty($products)): ?>
                        <div class="text-center p-4">
                            <i class="fas fa-coffee" style="font-size: 4rem; color: var(--secondary-color); opacity: 0.5;"></i>
                            <h4 style="color: var(--primary-color); margin-top: 1rem;">Belum Ada Produk</h4>
                            <p style="color: #666;">Tambahkan produk pertama Anda untuk memulai.</p>
                            <a href="products.php?action=add" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <img src="<?= !empty($product['image']) ? '../uploads/products/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=60' ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($product['name']) ?></strong>
                                                <br>
                                                <small style="color: #666;"><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</small>
                                            </td>
                                            <td>
                                                <span style="background: var(--secondary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 15px; font-size: 0.8rem;">
                                                    <?= htmlspecialchars($product['category']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong style="color: var(--secondary-color);">
                                                    Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <span style="color: <?= $product['stock'] > 10 ? 'var(--success-color)' : ($product['stock'] > 0 ? 'var(--warning-color)' : 'var(--danger-color)') ?>;">
                                                    <?= $product['stock'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($product['stock'] > 0): ?>
                                                    <span style="color: var(--success-color);">
                                                        <i class="fas fa-check-circle"></i> Aktif
                                                    </span>
                                                <?php else: ?>
                                                    <span style="color: var(--warning-color);">
                                                        <i class="fas fa-exclamation-circle"></i> Habis
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="../detail-produk.php?id=<?= $product['id'] ?>" 
                                                       class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                       target="_blank" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="products.php?action=edit&id=<?= $product['id'] ?>" 
                                                       class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="products.php?action=delete&id=<?= $product['id'] ?>" 
                                                       class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" 
                                                       title="Hapus"
                                                       onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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