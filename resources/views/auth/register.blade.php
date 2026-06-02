<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Satria Training Camp</title>

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
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 40px 20px;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(79, 70, 229, 0.15);
            filter: blur(80px);
            border-radius: 50%;
            top: -100px;
            left: -100px;
            z-index: 0;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 80px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .form-control {
            border-radius: 12px;
            padding: 0 16px;
            height: 48px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .input-group-text {
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: none;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            border-radius: 12px;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--primary);
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            height: 48px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2);
            transition: all 0.2s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            background: var(--primary-dark);
        }

        .text-primary-custom {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .text-primary-custom:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('asset/images/logo-stc.png') }}" alt="STC Logo" class="brand-logo">
            </a>
            <h3 class="fw-bold text-dark mb-1">Daftar Akun Baru</h3>
            <p class="text-muted small mb-4">Bergabung dengan Satria Training Camp sekarang</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-4 small">
                <div class="d-flex align-items-center mb-2 fw-bold">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-6"></i> Terjadi Kesalahan
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control border-start-0 ps-0" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email') }}" placeholder="Gunakan Email Aktif" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-4">
                <i class="bi bi-person-plus-fill me-2"></i> Buat Akun
            </button>

            <div class="text-center small text-muted">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-primary-custom fw-bold">Login di sini</a>
            </div>
        </form>
    </div>

</body>
</html>