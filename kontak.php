<?php
require_once 'includes/Session.php';

Session::start();

$success = '';
$error = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Mohon isi semua field yang wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        // In a real application, you would send email or save to database
        // For demo purposes, we'll just show success message
        $success = 'Terima kasih atas pesan Anda! Kami akan segera menghubungi Anda.';
        $_POST = []; // Clear form
    }
}

// Page configuration
$title = 'Kontak - Kopi Cepoko';
$description = 'Hubungi Kopi Cepoko untuk pertanyaan, saran, atau informasi lebih lanjut tentang produk kami';
$currentPage = 'contact';
$addTopMargin = true;

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container">
        <h1 class="section-title">Hubungi Kami</h1>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 4rem;">
            <!-- Contact Information -->
            <div>
                <h2 style="color: var(--primary-color); margin-bottom: 2rem;">Informasi Kontak</h2>
                
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Alamat</h4>
                            <p style="margin: 0; color: #666;">Jl. Kopi Raya No. 123<br>Jakarta Selatan 12560</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-phone" style="color: white; font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Telepon</h4>
                            <p style="margin: 0; color: #666;">+62 812-3456-7890</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-envelope" style="color: white; font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Email</h4>
                            <p style="margin: 0; color: #666;">info@kopicepoko.com</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock" style="color: white; font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Jam Operasional</h4>
                            <p style="margin: 0; color: #666;">
                                Senin - Jumat: 08:00 - 17:00<br>
                                Sabtu: 09:00 - 15:00<br>
                                Minggu: Libur
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Ikuti Kami</h4>
                    <div class="d-flex gap-2">
                        <a href="#" style="width: 40px; height: 40px; background: #3b5998; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" style="width: 40px; height: 40px; background: #e4405f; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" style="width: 40px; height: 40px; background: #25d366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" style="width: 40px; height: 40px; background: #1da1f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">Kirim Pesan</h3>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
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
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" name="phone" class="form-control" 
                                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
                                       placeholder="Contoh: 081234567890">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Subjek *</label>
                                <select name="subject" class="form-control" required>
                                    <option value="">Pilih Subjek</option>
                                    <option value="Pertanyaan Produk" <?= ($_POST['subject'] ?? '') === 'Pertanyaan Produk' ? 'selected' : '' ?>>Pertanyaan Produk</option>
                                    <option value="Pemesanan" <?= ($_POST['subject'] ?? '') === 'Pemesanan' ? 'selected' : '' ?>>Pemesanan</option>
                                    <option value="Kerjasama" <?= ($_POST['subject'] ?? '') === 'Kerjasama' ? 'selected' : '' ?>>Kerjasama</option>
                                    <option value="Keluhan" <?= ($_POST['subject'] ?? '') === 'Keluhan' ? 'selected' : '' ?>>Keluhan</option>
                                    <option value="Lainnya" <?= ($_POST['subject'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Pesan *</label>
                            <textarea name="message" class="form-control" rows="5" 
                                      placeholder="Tulis pesan Anda di sini..." 
                                      required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact Options -->
<section class="section" style="background-color: var(--background-light);">
    <div class="container">
        <h2 class="section-title">Kontak Cepat</h2>
        <div class="product-grid">
            <a href="tel:+6281234567890" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-body text-center">
                    <i class="fas fa-phone" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Telepon</h3>
                    <p style="margin: 0;">Hubungi langsung untuk konsultasi dan pemesanan</p>
                </div>
            </a>
            
            <a href="https://wa.me/6281234567890" target="_blank" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-body text-center">
                    <i class="fab fa-whatsapp" style="font-size: 3rem; color: var(--success-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">WhatsApp</h3>
                    <p style="margin: 0;">Chat langsung untuk pertanyaan cepat</p>
                </div>
            </a>
            
            <a href="mailto:info@kopicepoko.com" class="card" style="text-decoration: none; color: inherit;">
                <div class="card-body text-center">
                    <i class="fas fa-envelope" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Email</h3>
                    <p style="margin: 0;">Kirim email untuk komunikasi resmi</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Pertanyaan Sering Diajukan</h2>
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Bagaimana cara memesan produk?</h4>
                    <p style="margin: 0;">Anda dapat memesan melalui website kami dengan membuat akun terlebih dahulu, atau menghubungi kami langsung via WhatsApp untuk pemesanan yang lebih personal.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Berapa lama waktu pengiriman?</h4>
                    <p style="margin: 0;">Untuk wilayah Jabodetabek: 1-2 hari kerja. Untuk luar Jabodetabek: 3-5 hari kerja. Pengiriman gratis untuk pembelian di atas Rp 100.000.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Apakah produk masih fresh saat diterima?</h4>
                    <p style="margin: 0;">Ya, semua produk kami dikemas dalam kemasan kedap udara dengan valve untuk menjaga kesegaran. Kami juga mengirim produk yang baru di-roasting maksimal 3 hari sebelumnya.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Bagaimana cara menyimpan kopi yang baik?</h4>
                    <p style="margin: 0;">Simpan di tempat sejuk, kering, dan terhindar dari sinar matahari langsung. Jangan simpan di kulkas. Setelah dibuka, gunakan dalam waktu 2-3 minggu untuk kualitas rasa terbaik.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    .d-flex.gap-2 {
        flex-wrap: wrap !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>