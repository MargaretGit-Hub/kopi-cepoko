<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Cepoko - UMKM Kopi Terbaik</title>
    <meta name="description" content="Kopi Cepoko menyediakan kopi berkualitas tinggi dari petani lokal">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-coffee"></i> Kopi Cepoko
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tentang.php">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kontak.php">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Selamat Datang di <span class="text-warning">Kopi Cepoko</span>
                    </h1>
                    <p class="lead text-white mb-4">
                        Nikmati cita rasa kopi terbaik dari petani lokal Indonesia. 
                        Kualitas premium dengan harga terjangkau.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="produk.php" class="btn btn-warning btn-lg px-4">
                            <i class="fas fa-shopping-cart"></i> Lihat Produk
                        </a>
                        <a href="tentang.php" class="btn btn-outline-light btn-lg px-4">
                            Tentang Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="images/hero-coffee.jpg" alt="Kopi Cepoko" class="img-fluid rounded-circle shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Produk Unggulan</h2>
                <p class="text-muted">Pilihan terbaik dari koleksi kopi kami</p>
            </div>
            <div class="row" id="featured-products">
                <!-- Products will be loaded here -->
            </div>
            <div class="text-center mt-4">
                <a href="produk.php" class="btn btn-dark btn-lg">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="images/about-us.jpg" alt="Tentang Kami" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3">Tentang Kopi Cepoko</h2>
                    <p class="mb-3">
                        Kopi Cepoko adalah UMKM yang berkomitmen menghadirkan kopi berkualitas tinggi 
                        langsung dari petani lokal Indonesia. Kami percaya bahwa setiap cangkir kopi 
                        memiliki cerita dan cita rasa yang unik.
                    </p>
                    <p class="mb-4">
                        Dengan pengalaman lebih dari 10 tahun, kami telah melayani ribuan pelanggan 
                        yang puas dengan kualitas dan pelayanan terbaik kami.
                    </p>
                    <a href="tentang.php" class="btn btn-warning">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Testimoni Pelanggan</h2>
                <p class="text-muted">Apa kata mereka tentang Kopi Cepoko</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">"Kopi terenak yang pernah saya coba! Aroma dan rasanya benar-benar autentik."</p>
                            <h6 class="fw-bold">- Budi Santoso</h6>
                            <small class="text-muted">Jakarta</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">"Pelayanan sangat memuaskan dan kualitas kopi konsisten. Highly recommended!"</p>
                            <h6 class="fw-bold">- Sari Dewi</h6>
                            <small class="text-muted">Bandung</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">"Harga terjangkau tapi kualitas premium. Sudah langganan 2 tahun!"</p>
                            <h6 class="fw-bold">- Ahmad Rizki</h6>
                            <small class="text-muted">Surabaya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-coffee"></i> Kopi Cepoko
                    </h5>
                    <p>UMKM kopi terpercaya yang menghadirkan cita rasa kopi terbaik Indonesia untuk Anda.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="fw-bold mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white-50">Beranda</a></li>
                        <li><a href="produk.php" class="text-white-50">Produk</a></li>
                        <li><a href="tentang.php" class="text-white-50">Tentang Kami</a></li>
                        <li><a href="kontak.php" class="text-white-50">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Kontak Info</h6>
                    <p class="text-white-50 mb-2">
                        <i class="fas fa-map-marker-alt"></i> 
                        Jl. Kopi No. 123, Jakarta
                    </p>
                    <p class="text-white-50 mb-2">
                        <i class="fas fa-phone"></i> 
                        +62 812-3456-7890
                    </p>
                    <p class="text-white-50 mb-2">
                        <i class="fas fa-envelope"></i> 
                        info@kopicepoko.com
                    </p>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Newsletter</h6>
                    <p class="text-white-50 mb-3">Dapatkan update produk terbaru dan promo menarik!</p>
                    <form>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Email Anda">
                            <button class="btn btn-warning" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Kopi Cepoko. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>