<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pengelolaan Data Barang Teh Kita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .carousel-item {
            height: 100vh;
            min-height: 400px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.65);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 4rem;
            }
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 30px;
        }
    </style>
</head>
<body>

<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

    <!-- Indicator Bulat -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    </div>

    <div class="carousel-inner">

        <!-- SLIDE 1 -->
        <div class="carousel-item active"
             style="background-image: url('/images/carousel-1.png');">

            <div class="overlay"></div>

            <div class="d-flex h-100 align-items-center justify-content-center text-center text-white hero-content">
                <div>
                    <h1 class="hero-title mb-4">
                        Pengelolaan Data Barang Teh Kita
                    </h1>

                    <p class="lead mb-5">
                        Sistem Informasi Admin & Outlet Modern
                    </p>

                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg btn-custom me-3">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg btn-custom">
                        Registrasi
                    </a>
                </div>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="carousel-item"
             style="background-image: url('/images/carousel-1.png');">

            <div class="overlay"></div>

            <div class="d-flex h-100 align-items-center justify-content-center text-center text-white hero-content">
                <div>
                    <h1 class="hero-title mb-4">
                        Kelola Data Lebih Mudah & Cepat
                    </h1>

                    <p class="lead">
                        Dibuat dengan Laravel & Bootstrap
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
