<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Paket - Satria Training Camp</title>
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
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            min-height: 100vh;
            padding: 40px 0;
            color: var(--dark);
        }

        .page-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1.25rem 1.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-logout {
            color: #ef4444;
            background: #fef2f2;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }

        .section-title {
            text-align: center;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }

        .price-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            height: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
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
            font-size: 2.5rem;
            font-weight: 800;
            margin: 1.5rem 0;
        }

        .price-card.popular .price-amount {
            color: white;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem 0;
            flex-grow: 1;
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

        .btn-buy {
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-buy-outline {
            color: var(--primary);
            background: var(--primary-dark);
            background-color: transparent;
            border: 2px solid var(--primary);
        }
        
        .btn-buy-outline:hover {
            background: var(--primary);
            color: white;
        }

        .price-card.popular .btn-buy {
            background: white;
            color: var(--primary);
            border: none;
        }
        
        .price-card.popular .btn-buy:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 991px) {
            .section-title { font-size: 2rem; }
            .price-card { padding: 30px 24px; margin-bottom: 1.5rem; }
            .price-card.popular { margin: 0 0 1.5rem 0; transform: none; }
            .price-card.popular:hover { transform: translateY(-5px); }
            .page-header { padding: 1rem; border-radius: 16px; margin-bottom: 1.5rem; }
        }

    </style>
</head>
<body>

    <div class="container">
        
        <div class="page-header">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('asset/images/logo-stc.png') }}" alt="Logo" width="40">
                <h5 class="fw-bold mb-0 text-dark d-none d-sm-block">Satria Training Camp</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">Halo, <span class="fw-bold text-dark">{{ auth()->user()->name ?? 'Member' }}</span></span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-logout" style="text-decoration: none; border: none;">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info rounded-4 p-3 mb-4 d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        <h1 class="section-title">Pilih Paket Latihan</h1>
        <p class="text-center text-muted mb-5">Pilih paket yang paling sesuai dengan tujuan kebugaran Anda.</p>

        <div class="row g-5 justify-content-center">
            @foreach ($pakets as $paket)
                <div class="col-lg-4 col-md-6">
                    <div class="price-card {{ str_contains(strtolower($paket->nama_paket), 'regular') ? 'popular' : '' }}">
                        @if (str_contains(strtolower($paket->nama_paket), 'regular'))
                            <div class="position-absolute top-0 end-0 mt-3 me-3">
                                <span class="badge bg-white text-primary rounded-pill px-3 py-2">Rekomendasi</span>
                            </div>
                        @endif

                        <h3 class="fw-bold">{{ $paket->nama_paket }}</h3>
                        <p class="text-muted">{{ $paket->jumlah_sesi }} Sesi Latihan</p>
                        
                        <div class="price-amount">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}<span class="fs-6 fw-normal {{ str_contains(strtolower($paket->nama_paket), 'regular') ? 'text-white-50' : 'text-muted' }}">/paket</span>
                        </div>
                        
                        <ul class="feature-list">
                            <li><i class="bi bi-check-circle-fill"></i> Akses Fasilitas Lengkap</li>
                            <li><i class="bi bi-check-circle-fill"></i> Instruktur Profesional</li>
                            @if (str_contains(strtolower($paket->nama_paket), 'private'))
                                <li><i class="bi bi-check-circle-fill"></i> 1-on-1 Coaching</li>
                                <li><i class="bi bi-check-circle-fill"></i> Jadwal Fleksibel</li>
                            @else
                                <li><i class="bi bi-check-circle-fill"></i> Bergabung dengan Kelas</li>
                                <li><i class="bi bi-check-circle-fill"></i> Komunitas Positif</li>
                            @endif
                        </ul>
                        
                        <a href="{{ route('pembayaran.show', $paket->id) }}" class="btn-buy {{ str_contains(strtolower($paket->nama_paket), 'regular') ? '' : 'btn-buy-outline' }}">
                            Beli Paket Ini
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</body>
</html>
