<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengelolahan Data Barang | Teh Kita</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('templates/dist/css/landing.css') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <span class="teh">Teh</span><span class="kita">kita</span>
        </a>

        <div class="collapse navbar-collapse justify-content-center">
            <ul class="navbar-nav gap-4">
                <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#welcome">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="#informasi">Informasi Sistem</a></li>
                <li class="nav-item"><a class="nav-link" href="#cabang">Cabang</a></li>
                <li class="nav-item"><a class="nav-link" href="#sop">SOP</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO CAROUSEL (2 SLIDE) -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">

        <!-- SLIDE 1 -->
        <div class="carousel-item active">
            <img src="{{ asset('images/carousel-1.png') }}" class="w-100 hero-img">
            <div class="hero-overlay"></div>

            <div class="hero-content">
                <h1 class="hero-title">Pengelolahan Data Barang</h1>
                <p class="hero-sub">Sistem Informasi Internal Es Teh Kita</p>

                <p class="hero-address">
                    Kantor Pusat<br>
                    Jl. Ciremai Raya, Kota Cirebon
                </p>

                <div class="hero-btn">
                    <a href="/login" class="btn btn-success rounded-pill px-4">Login</a>
                    <a href="/register" class="btn btn-warning rounded-pill px-4">Register</a>
                </div>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="carousel-item">
            <img src="{{ asset('images/carousel-1.png') }}" class="w-100 hero-img">
            <div class="hero-overlay"></div>

            <div class="hero-content">
                <h1 class="hero-title">Manajemen Stok & Distribusi</h1>
                <p class="hero-sub">Tertib • Terstruktur • Akurat</p>
            </div>
        </div>

    </div>
</div>

<!-- WELCOME -->
<section id="welcome" class="section welcome-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-md-9">

                <h2 class="welcome-title">
                    Selamat Datang di 
                    <span class="teh">Teh</span><span class="kita">Kita</span>
                </h2>

                <p class="welcome-subtitle">
                    Sistem Informasi Pengelolaan Data Barang
                </p>

                <p class="welcome-text">
                    Website ini merupakan sistem internal yang digunakan oleh
                    <strong>Teh Kita</strong> untuk mendukung proses pengelolaan
                    data barang, stok, distribusi, serta informasi cabang secara
                    terpusat dan terintegrasi.
                </p>

                <p class="welcome-text">
                    Dengan adanya sistem ini, diharapkan seluruh proses pencatatan
                    dapat berjalan lebih <strong>efisien, akurat, dan tertib</strong>,
                    sehingga operasional setiap cabang dapat terkontrol dengan baik.
                </p>

                <div class="welcome-divider"></div>

                <p class="welcome-note">
                    Sistem ini hanya diperuntukkan bagi karyawan dan pihak internal
                    Teh Kita yang memiliki akses resmi.
                </p>

            </div>
        </div>
    </div>
</section>
<!-- INFORMASI SISTEM -->
<section id="informasi" class="section">
    <h2 class="section-title">Informasi Sistem</h2>

    <div class="container mt-4">
        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="info-card">
                    <i class="bi bi-box-seam"></i>
                    <h6>Data Barang</h6>
                    <p>Pencatatan barang masuk dan keluar secara terstruktur.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <i class="bi bi-clipboard-data"></i>
                    <h6>Stok Barang</h6>
                    <p>Monitoring stok barang di setiap cabang.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <i class="bi bi-truck"></i>
                    <h6>Distribusi</h6>
                    <p>Pencatatan distribusi barang antar cabang.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <i class="bi bi-building"></i>
                    <h6>Data Cabang</h6>
                    <p>Manajemen data cabang dan gudang.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <i class="bi bi-people"></i>
                    <h6>Akses Pengguna</h6>
                    <p>Akses sistem terbatas untuk karyawan.</p>
                </div>
            </div>

            <!-- JAM KERJA (BARU) -->
            <div class="col-md-4">
                <div class="info-card highlight">
                    <i class="bi bi-clock-history"></i>
                    <h6>Jam Operasional</h6>
                    <p>
                        Senin – Minggu<br>
                        <strong>08.00 – 22.00 WIB</strong>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CABANG -->
<section id="cabang" class="section bg-light">
  <h2 class="section-title">Cabang Teh Kita</h2>

  <div class="container mt-4">
    <ul class="cabang-list">

      <li>
        <a target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=Teh+Kita+Perumnas+Jl.+Ciremai+Raya+Larangan+Harjamukti+Kota+Cirebon">
          📍 Teh Kita Perumnas
        </a>
      </li>

      <li>
        <a target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=Teh+Kita+Arumsari+Jl.+Jembatan+Merah+No.18+Talun+Cirebon">
          📍 Teh Kita Arumsari
        </a>
      </li>

      <li>
        <a target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=7G7M+VW6+Teh+Kita+Perjuangan+Sunyaragi+Cirebon">
          📍 Teh Kita Perjuangan
        </a>
      </li>

      <li>
        <a target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=Teh+Kita+Plumbon+Jl.+Mertabasah+Pamijahan+Plumbon+Cirebon">
          📍 Teh Kita Plumbon
        </a>
      </li>

      <li>
        <a target="_blank"
           href="https://www.google.com/maps/search/?api=1&query=8C8X+WRG+Teh+Kita+Klangenan+Cirebon">
          📍 Teh Kita Klangenan
        </a>
      </li>

    </ul>
  </div>
</section>

<!-- SOP -->
<section id="sop" class="section">
    <h2 class="section-title">SOP & Peraturan</h2>

    <div class="container mt-4">
        <ul class="sop-list">
            <li><i class="bi bi-check-circle"></i> Sistem hanya untuk karyawan resmi</li>
            <li><i class="bi bi-check-circle"></i> Data wajib diinput dengan benar</li>
            <li><i class="bi bi-check-circle"></i> Data bersifat rahasia</li>
            <li><i class="bi bi-check-circle"></i> Laporan dibuat berkala</li>
            <li><i class="bi bi-check-circle"></i> Pelanggaran dikenakan sanksi</li>
        </ul>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container text-center">
        <p class="mb-1">© 2026 Pengelolahan Data Barang Es Teh Kita</p>

        <!-- Email -->
        <p class="mb-2">
            📧 <a href="mailto:tehkita@point.com" class="footer-email">
                tehkita@point.com
            </a>
        </p>

        <!-- Social Media -->
        <div class="social">
            <a href="https://www.instagram.com/tehkita.point?igsh=MTZobHh6OHo5NzcyNw==" 
               class="me-3" target="_blank">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="https://www.tiktok.com/@teh.kita.perum2?_r=1&_t=ZS-94010UQVBtX" 
               target="_blank">
                <i class="bi bi-tiktok"></i>
            </a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>