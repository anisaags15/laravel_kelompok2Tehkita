<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolahan Data Barang | Teh Kita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('templates/dist/css/landing.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <span class="teh">Teh</span><span class="kita">kita</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-lg-4">
                <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#welcome">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="#informasi">Informasi</a></li>
                <li class="nav-item"><a class="nav-link" href="#cabang">Cabang</a></li>
                <li class="nav-item"><a class="nav-link" href="#sop">SOP</a></li>
                <li class="nav-item ms-lg-3">
                    <a href="/register" class="btn btn-login rounded-pill px-4">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="hero-zoom-wrapper">
                <img src="{{ asset('images/carousel-1.png') }}" class="w-100 hero-img" alt="Slide 1">
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content container">
                <div class="badge-custom mb-3" data-aos="fade-down">Sistem Informasi Internal</div>
                <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">Pengelolaan Data <br><span class="text-highlight">Barang Terpadu</span></h1>
                <p class="hero-sub" data-aos="fade-up" data-aos-delay="400">Kantor Pusat Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat</p>
                <div class="hero-btn" data-aos="zoom-in" data-aos-delay="600">
                    <a href="/login" class="btn btn-main me-2">Mulai Sekarang</a>
                    <a href="#welcome" class="btn btn-outline-light px-4 py-2 rounded-pill">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
        
        <div class="carousel-item">
            <div class="hero-zoom-wrapper">
                <img src="{{ asset('images/carousel-1.png') }}" class="w-100 hero-img" alt="Slide 2">
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content container text-center">
                <h1 class="hero-title">Manajemen Stok <br>& Distribusi</h1>
                <p class="hero-sub">Membangun ekosistem kerja yang lebih tertib dan efisien bersama Teh Kita.</p>
            </div>
        </div>
    </div>
</div>

<section id="welcome" class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                <div class="welcome-image-wrapper">
                    <img src="{{ asset('images/produk-1.png') }}" alt="Teh Kita" class="img-fluid custom-about-img shadow-lg">
                    <div class="img-accent"></div>
                </div>
            </div>

            <div class="col-lg-7 ps-lg-5" data-aos="fade-left">
                <div class="about-content">
                    <h6 class="about-badge mb-3">Tentang Kami</h6>
                    <h2 class="welcome-title mb-4">Mendigitalkan Tradisi, <br><span class="text-green-gradient">Menjaga Kualitas</span></h2>
                    <p class="welcome-text">Website ini merupakan sistem internal yang dikembangkan khusus oleh <strong>Teh Kita</strong> untuk mengintegrasikan proses pengelolaan data barang, kontrol stok, dan alur distribusi secara terpusat.</p>
                    
                    <div class="features-mini mt-4">
                        <div class="feature-item-small">
                            <div class="f-icon-small"><i class="bi bi-shield-check"></i></div>
                            <div class="f-text">
                                <strong>Keamanan Data</strong>
                                <p>Proteksi data internal terenkripsi.</p>
                            </div>
                        </div>
                        <div class="feature-item-small">
                            <div class="f-icon-small"><i class="bi bi-lightning-charge"></i></div>
                            <div class="f-text">
                                <strong>Efisiensi Tinggi</strong>
                                <p>Monitoring stok real-time antar cabang.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="informasi" class="section section-dark">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Fitur & Informasi Sistem</h2>
            <div class="title-divider mx-auto"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="info-card-premium">
                    <div class="card-icon"><i class="bi bi-box-seam"></i></div>
                    <h5>Data Barang</h5>
                    <p>Manajemen katalog barang terlengkap dengan sistem klasifikasi yang rapi.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="info-card-premium">
                    <div class="card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h5>Stok Barang</h5>
                    <p>Pantau sisa stok setiap saat untuk memastikan ketersediaan bahan baku.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="info-card-premium">
                    <div class="card-icon"><i class="bi bi-truck"></i></div>
                    <h5>Distribusi</h5>
                    <p>Alur distribusi barang antar cabang terpantau dengan log yang detail.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="info-card-premium">
                    <div class="card-icon"><i class="bi bi-geo-alt"></i></div>
                    <h5>Data Cabang</h5>
                    <p>Integrasi database alamat dan kontak seluruh outlet Teh Kita.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="info-card-premium">
                    <div class="card-icon"><i class="bi bi-person-lock"></i></div>
                    <h5>Hak Akses</h5>
                    <p>Keamanan berlapis dengan pembagian peran admin dan manajemen.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="info-card-premium highlight">
                    <div class="card-icon"><i class="bi bi-alarm"></i></div>
                    <h5>Operasional</h5>
                    <p>Layanan support sistem internal setiap hari pukul 08.00 – 22.00 WIB.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="cabang" class="section">
    <div class="container text-center">
        <h2 class="section-title mb-5" data-aos="fade-up">Lokasi Cabang</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="cabang-grid">
                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=Teh+Kita+Arumsari+Jl.+Jembatan+Merah+No.18+Talun+Cirebon" class="cabang-item" data-aos="zoom-in">
                        <i class="bi bi-pin-map-fill me-2"></i> Teh Kita Perumnas
                    </a>
                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=Teh+Kita+Arumsari+Jl.+Jembatan+Merah+No.18+Talun+Cirebon" class="cabang-item" data-aos="zoom-in" data-aos-delay="100">
                        <i class="bi bi-pin-map-fill me-2"></i> Teh Kita Arumsari
                    </a>
                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=7G7M+VW6+Teh+Kita+Perjuangan+Sunyaragi+Cirebon" class="cabang-item" data-aos="zoom-in" data-aos-delay="200">
                        <i class="bi bi-pin-map-fill me-2"></i> Teh Kita Perjuangan
                    </a>
                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=Teh+Kita+Plumbon+Jl.+Mertabasah+Pamijahan+Plumbon+Cirebon" class="cabang-item" data-aos="zoom-in" data-aos-delay="300">
                        <i class="bi bi-pin-map-fill me-2"></i> Teh Kita Plumbon
                    </a>
                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=8C8X+WRG+Teh+Kita+Klangenan+Cirebon" class="cabang-item" data-aos="zoom-in" data-aos-delay="400">
                        <i class="bi bi-pin-map-fill me-2"></i> Teh Kita Klangenan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="sop" class="section bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 mb-4 mb-md-0" data-aos="fade-right">
                <h2 class="fw-bold display-6">SOP & <br>Peraturan Internal</h2>
                <p class="opacity-75">Kepatuhan terhadap prosedur adalah kunci kualitas layanan Teh Kita.</p>
            </div>
            <div class="col-md-7" data-aos="fade-left">
                <div class="sop-container p-4 rounded-4 shadow-lg">
                    <div class="sop-item"><i class="bi bi-1-circle-fill"></i> Hanya untuk Karyawan Resmi</div>
                    <div class="sop-item"><i class="bi bi-2-circle-fill"></i> Input Data Wajib Akurat & Real-time</div>
                    <div class="sop-item"><i class="bi bi-3-circle-fill"></i> Kerahasiaan Data Perusahaan Terjaga</div>
                    <div class="sop-item"><i class="bi bi-4-circle-fill"></i> Laporan Rutin Setiap Akhir Shift</div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container text-center">
        <h4 class="fw-bold mb-4"><span class="text-success">Teh</span><span class="text-warning">Kita</span></h4>
        <p class="mb-2">Jl. Ciremai Raya, Kota Cirebon.</p>
        <p class="mb-2">Email: <a href="mailto:tehkita@point.com" class="text-white text-decoration-none">tehkita@point.com</a></p>
        <p class="mb-4">Hubungi Kami: <a href="https://wa.me/62 896-3545-0705" target="_blank" class="text-white text-decoration-none"><i class="bi bi-whatsapp me-1"></i> +62 812-3456-7890</a></p>
        
        <div class="social-icons mb-4">
            <a href="https://www.instagram.com/tehkita.point?igsh=MTZobHh6OHo5NzcyNw==" target="_blank"><i class="bi bi-instagram"></i></a>
            <a href="https://www.tiktok.com/@teh.kita.perum2?_r=1&_t=ZS-94010UQVBtX" target="_blank"><i class="bi bi-tiktok"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
        </div>
        
        <div class="footer-bottom border-top pt-4">
            <p>© 2026 Es Teh Kita - Management System.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Inisialisasi Animasi AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Efek Navbar Saat Scroll
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('mainNav');
        if (window.scrollY > 50) {
            nav.classList.add('navbar-scrolled');
        } else {
            nav.classList.remove('navbar-scrolled');
        }
    });
</script>
</body>
</html>