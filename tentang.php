<?php
require_once 'includes/Session.php';

Session::start();

// Page configuration
$title = 'Tentang Kami - Kopi Cepoko';
$description = 'Pelajari lebih lanjut tentang Kopi Cepoko, UMKM kopi terpercaya yang menghadirkan kopi berkualitas tinggi dari Indonesia';
$currentPage = 'about';
$addTopMargin = true;

// Start output buffering for the layout
ob_start();
?>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; margin-bottom: 4rem;">
            <div>
                <h1 style="color: var(--primary-color); font-size: 3rem; margin-bottom: 1.5rem;">
                    Tentang Kopi Cepoko
                </h1>
                <p style="font-size: 1.2rem; margin-bottom: 1.5rem; color: var(--secondary-color);">
                    Perjalanan kami dimulai dari kecintaan terhadap kopi Indonesia dan komitmen untuk mendukung petani lokal.
                </p>
                <p style="margin-bottom: 1.5rem; line-height: 1.8;">
                    Kopi Cepoko adalah UMKM yang berdedikasi untuk menghadirkan cita rasa kopi terbaik dari berbagai daerah di Indonesia. 
                    Kami percaya bahwa setiap cangkir kopi menceritakan kisah tentang tanah, iklim, dan tangan-tangan terampil yang merawatnya.
                </p>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=600" 
                     alt="Kopi Cepoko" 
                     style="width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="section" style="background-color: var(--background-light);">
    <div class="container">
        <h2 class="section-title">Cerita Kami</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                Didirikan pada tahun 2020, Kopi Cepoko berawal dari mimpi sederhana untuk membagikan kelezatan kopi Indonesia 
                kepada lebih banyak orang. Kami memulai dengan mengunjungi kebun-kebun kopi di berbagai daerah, bertemu langsung 
                dengan para petani, dan belajar tentang proses dari biji hingga menjadi secangkir kopi yang sempurna.
            </p>
            <p style="font-size: 1.1rem; line-height: 1.8;">
                Hari ini, Kopi Cepoko telah melayani ribuan pelanggan di seluruh Indonesia, namun komitmen kami tetap sama: 
                menghadirkan kopi berkualitas tinggi sambil mendukung kesejahteraan petani lokal dan melestarikan kearifan lokal 
                dalam budidaya kopi.
            </p>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Nilai-Nilai Kami</h2>
        <div class="product-grid">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-leaf" style="font-size: 3rem; color: var(--success-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Sustainability</h3>
                    <p>Kami berkomitmen pada praktik berkelanjutan yang menjaga lingkungan dan mendukung komunitas petani lokal.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-gem" style="font-size: 3rem; color: var(--warning-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Kualitas</h3>
                    <p>Setiap biji kopi dipilih dengan cermat dan melalui proses quality control yang ketat untuk memastikan cita rasa terbaik.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-handshake" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Kemitraan</h3>
                    <p>Kami membangun hubungan jangka panjang dengan petani untuk memastikan keadilan dan keberlanjutan dalam rantai pasokan.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-heart" style="font-size: 3rem; color: var(--danger-color); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Passion</h3>
                    <p>Kecintaan kami terhadap kopi mendorong inovasi berkelanjutan dalam setiap aspek bisnis yang kami jalankan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Process -->
<section class="section" style="background-color: var(--background-light);">
    <div class="container">
        <h2 class="section-title">Proses Kami</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div class="text-center">
                <div style="background: var(--primary-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; font-weight: bold;">
                    1
                </div>
                <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Pemilihan Biji</h4>
                <p>Kami mengunjungi kebun kopi dan memilih biji kopi terbaik langsung dari petani lokal dengan standar kualitas tinggi.</p>
            </div>
            
            <div class="text-center">
                <div style="background: var(--secondary-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; font-weight: bold;">
                    2
                </div>
                <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Proses Roasting</h4>
                <p>Biji kopi dipanggang dengan teknik manual oleh roaster berpengalaman untuk menghasilkan profil rasa yang optimal.</p>
            </div>
            
            <div class="text-center">
                <div style="background: var(--accent-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; font-weight: bold;">
                    3
                </div>
                <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Quality Control</h4>
                <p>Setiap batch kopi melewati tes kualitas yang ketat untuk memastikan konsistensi rasa dan aroma yang sempurna.</p>
            </div>
            
            <div class="text-center">
                <div style="background: var(--success-color); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; font-weight: bold;">
                    4
                </div>
                <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Packaging & Delivery</h4>
                <p>Kopi dikemas dalam kemasan kedap udara untuk menjaga kesegaran dan dikirim langsung ke pelanggan di seluruh Indonesia.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Tim Kami</h2>
        <div class="product-grid">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300" 
                     class="card-img" alt="Founder">
                <div class="card-body text-center">
                    <h3 class="card-title">Budi Santoso</h3>
                    <p style="color: var(--secondary-color); font-weight: bold; margin-bottom: 0.5rem;">Founder & CEO</p>
                    <p style="font-size: 0.9rem;">Memiliki pengalaman 15 tahun di industri kopi dan passionate dalam mendukung petani lokal Indonesia.</p>
                </div>
            </div>
            
            <div class="card">
                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=300" 
                     class="card-img" alt="Head of Quality">
                <div class="card-body text-center">
                    <h3 class="card-title">Sari Dewi</h3>
                    <p style="color: var(--secondary-color); font-weight: bold; margin-bottom: 0.5rem;">Head of Quality</p>
                    <p style="font-size: 0.9rem;">Ahli cupping bersertifikat yang memastikan setiap biji kopi memenuhi standar kualitas tertinggi.</p>
                </div>
            </div>
            
            <div class="card">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300" 
                     class="card-img" alt="Head Roaster">
                <div class="card-body text-center">
                    <h3 class="card-title">Ahmad Rizki</h3>
                    <p style="color: var(--secondary-color); font-weight: bold; margin-bottom: 0.5rem;">Head Roaster</p>
                    <p style="font-size: 0.9rem;">Master roaster dengan keahlian khusus dalam mengembangkan profil rasa yang unik untuk setiap jenis kopi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
    <div class="container text-center">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Bergabunglah dengan Keluarga Kopi Cepoko</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">
            Rasakan pengalaman kopi Indonesia yang autentik dan dukung petani lokal bersama kami.
        </p>
        <div class="d-flex gap-2 justify-center" style="flex-wrap: wrap;">
            <a href="produk.php" class="btn btn-outline" style="border-color: white; color: white;">
                <i class="fas fa-shopping-cart"></i> Belanja Sekarang
            </a>
            <a href="kontak.php" class="btn" style="background: white; color: var(--primary-color);">
                <i class="fas fa-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .section div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    h1 {
        font-size: 2rem !important;
    }
    
    h2 {
        font-size: 2rem !important;
    }
    
    .d-flex.justify-center {
        justify-content: center !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>