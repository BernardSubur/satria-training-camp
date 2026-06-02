<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satria Training Camp</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/favicon-stc.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --secondary: #10b981;
            --dark: #0f172a;
            --light: #f8fafc;
            --glass: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        .navbar {
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            border-radius: 50px;
            padding: 0.4rem 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            background: var(--primary-dark);
        }

        .hero {
            position: relative;
            padding: 160px 0 100px;
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(79, 70, 229, 0.1);
            filter: blur(80px);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--primary), #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: #475569;
            margin-bottom: 2rem;
            max-width: 500px;
        }

        .hero-gallery {
            position: relative;
            z-index: 1;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: perspective(1000px) rotateY(-5deg);
            transition: transform 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-gallery:hover {
            transform: perspective(1000px) rotateY(0deg);
        }

        .hero-gallery img {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
            background-color: rgba(0, 0, 0, 0.03);
            border-radius: 24px;
        }

        .features {
            padding: 100px 0;
            background: white;
            position: relative;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-weight: 800;
            font-size: 2.5rem;
            color: var(--dark);
        }

        .section-header p {
            color: #64748b;
            font-size: 1.1rem;
        }

        .pricing {
            padding: 100px 0;
            background: var(--light);
        }

        .price-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            height: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
        }

        .price-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--primary);
        }

        .price-card.popular {
            background: var(--primary);
            color: white;
            transform: scale(1.02);
            border: none;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.2);
            z-index: 1;
        }

        .price-card.popular:hover {
            transform: scale(1.04) translateY(-10px);
        }

        .price-card.popular .text-muted {
            color: #c7d2fe !important;
        }

        .price-amount {
            font-size: 3rem;
            font-weight: 800;
            margin: 1.5rem 0;
        }

        .price-card.popular .price-amount {
            color: white;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .feature-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-list li i {
            color: var(--secondary);
            font-size: 1.2rem;
        }

        .price-card.popular .feature-list li i {
            color: #34d399;
        }

        .location {
            padding: 100px 0;
            background: white;
        }

        .map-container {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        @media (max-width: 991px) {
            .hero h1 { font-size: 2.75rem; }
            .hero-gallery { margin-top: 2rem; transform: none; }
            .price-card { padding: 30px 24px; margin-bottom: 1rem; }
            .price-card.popular { transform: none; margin: 0; }
            .price-card.popular:hover { transform: translateY(-5px); }
            
            .navbar-nav .btn-primary, .navbar-nav .btn-outline-primary {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
                flex: 1;
                border-radius: 12px;
                width: 100%;
            }
            .nav-item.d-flex {
                flex-direction: row;
                gap: 8px !important;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('asset/images/logo-stc.png') }}" alt="STC Logo" height="45">
                SATRIA TRAINING CAMP
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1 text-primary"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#pricing">Paket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#jadwal">Jadwal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#lokasi">Lokasi</a>
                    </li>
                    
                    @php
                        $membership = Auth::check() ? \App\Models\Membership::where('user_id', Auth::id())->where('status', 'aktif')->first() : null;
                    @endphp

                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0 w-100 w-lg-auto d-flex gap-2">
                        @if ($membership)
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary w-100">Ke Dashboard</a>
                        @elseif (Auth::check())
                            <a href="{{ url('/paket') }}" class="btn btn-primary w-100">Beli Paket</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 w-lg-auto">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary w-100 w-lg-auto">Daftar Sekarang</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-bold">
                        <i class="bi bi-stars"></i> Muaythai & Boxing Camp
                    </div>
                    <h1>Train Hard.<br>Fight Smart.</h1>
                    <p>Tingkatkan kebugaran, bangun disiplin dan asah mental juara Anda bersama instruktur profesional di Satria Training Camp Purwokerto</p>
                    
                    <div class="d-flex gap-3">
                        <a href="#pricing" class="btn btn-primary btn-lg">Lihat Paket</a>
                        <a href="#lokasi" class="btn btn-outline-primary btn-lg">Lokasi Kami</a>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div id="heroCarousel" class="carousel slide hero-gallery" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('asset/images/gambar-1.jpeg') }}" class="d-block w-100" alt="Training">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('asset/images/gambar-2.jpeg') }}" class="d-block w-100" alt="Training">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('asset/images/gambar-3.jpeg') }}" class="d-block w-100" alt="Training">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('asset/images/gambar-4.jpeg') }}" class="d-block w-100" alt="Training">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('asset/images/gambar-5.jpeg') }}" class="d-block w-100" alt="Training">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <h2>Pilih Paket Latihan Anda</h2>
                <p>Harga terjangkau dan fasilitas lengkap. Mulai perjalanan Anda hari ini.</p>
            </div>

            <div class="row justify-content-center g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="price-card">
                        <h3 class="fw-bold">Student</h3>
                        <p class="text-muted">Khusus pelajar/mahasiswa (Max 18th)</p>
                        <div class="price-amount">Rp 200k<span class="fs-5 text-muted fw-normal">/bln</span></div>
                        
                        <ul class="feature-list">
                            <li><i class="bi bi-check-circle-fill"></i> 16 Sesi Latihan</li>
                            <li><i class="bi bi-check-circle-fill"></i> Regular Class</li>
                            <li><i class="bi bi-check-circle-fill"></i> Fasilitas Lengkap</li>
                        </ul>
                        
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-4">Pilih Paket</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="price-card popular">
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span class="badge bg-white text-primary rounded-pill px-3 py-2">Terpopuler</span>
                        </div>
                        <h3 class="fw-bold">Regular</h3>
                        <p class="text-muted">Untuk kebugaran harian</p>
                        <div class="price-amount">Rp 300k<span class="fs-5 text-white-50 fw-normal">/bln</span></div>
                        
                        <ul class="feature-list">
                            <li><i class="bi bi-check-circle-fill"></i> 16 Sesi Latihan</li>
                            <li><i class="bi bi-check-circle-fill"></i> Fleksibel Pilih Jadwal</li>
                            <li><i class="bi bi-check-circle-fill"></i> Akses Seluruh Fasilitas</li>
                            <li><i class="bi bi-check-circle-fill"></i> Komunitas Positif</li>
                        </ul>
                        
                        <a href="{{ route('register') }}" class="btn btn-light text-primary w-100 mt-4 fw-bold">Mulai Sekarang</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="price-card">
                        <h3 class="fw-bold">Private Class</h3>
                        <p class="text-muted">Fokus 1-on-1 dengan Coach</p>
                        <div class="price-amount">Rp 550k<span class="fs-5 text-muted fw-normal">/bln</span></div>
                        
                        <ul class="feature-list">
                            <li><i class="bi bi-check-circle-fill"></i> 8 Sesi Latihan Intensif</li>
                            <li><i class="bi bi-check-circle-fill"></i> Atur Jadwal Sendiri (Bebas)</li>
                            <li><i class="bi bi-check-circle-fill"></i> Pendampingan Khusus</li>
                            <li><i class="bi bi-check-circle-fill"></i> Hasil Lebih Cepat</li>
                        </ul>
                        
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-4">Hubungi Kami</a>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 text-center">
                <p class="text-muted mb-3">Juga tersedia paket Regular jangka panjang untuk harga lebih hemat:</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <div class="badge bg-white border text-dark p-3 rounded-3 shadow-sm">
                        <span class="fw-bold d-block fs-6">2 Bulan</span>
                        <span class="text-primary fw-bold">Rp 550.000</span> (32 Sesi)
                    </div>
                    <div class="badge bg-white border text-dark p-3 rounded-3 shadow-sm">
                        <span class="fw-bold d-block fs-6">3 Bulan</span>
                        <span class="text-primary fw-bold">Rp 800.000</span> (48 Sesi)
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features bg-white" id="jadwal">
        <div class="container">
            <div class="section-header">
                <h2>Jadwal Latihan</h2>
                <p>Pilih waktu yang paling sesuai dengan kesibukan Anda.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="p-4 border rounded-4 bg-light text-center h-100">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width:60px;height:60px;">
                            <i class="bi bi-calendar-week fs-3"></i>
                        </div>
                        <h4 class="fw-bold">Selasa & Kamis</h4>
                        <hr class="w-25 mx-auto border-primary opacity-50 border-2">
                        <p class="fs-5 mb-1"><strong>Sesi I:</strong> 16.30 - 18.00</p>
                        <p class="fs-5"><strong>Sesi II:</strong> 19.00 - 20.30</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="p-4 border rounded-4 bg-light text-center h-100">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width:60px;height:60px;">
                            <i class="bi bi-calendar-event fs-3"></i>
                        </div>
                        <h4 class="fw-bold">Sabtu & Minggu</h4>
                        <hr class="w-25 mx-auto border-primary opacity-50 border-2">
                        <p class="fs-5 mb-1"><strong>Sesi I:</strong> 15.30 - 17.00</p>
                        <p class="fs-5"><strong>Sesi II:</strong> 17.00 - 18.30</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="location" id="lokasi">
        <div class="container">
            <div class="section-header">
                <h2>Lokasi Kami</h2>
                <p>Kunjungi camp kami untuk melihat fasilitas secara langsung.</p>
            </div>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps?q=GOR+Satria+Purwokerto&output=embed"
                    width="100%" height="450" style="border:0;" loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @include('layouts.footer')
</body>
</html>