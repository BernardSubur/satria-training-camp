<!DOCTYPE html>
<html>

<head>
    <title>Verifikasi OTP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e9d5ff, #c7d2fe, #a5b4fc);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .otp-input {
            font-size: 24px;
            letter-spacing: 10px;
            text-align: center;
        }
    </style>

</head>

<body>

    <div class="card-box">

        <h4 class="fw-bold text-center mb-3">Verifikasi OTP</h4>

        <p class="text-muted text-center mb-4">
            Masukkan kode OTP yang dikirim ke email Anda
        </p>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger text-center">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.otp.verify') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kode OTP</label>

                <input type="text" name="otp" class="form-control text-center" required maxlength="6"
                    inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            </div>

            <button class="btn btn-primary w-100">
                Verifikasi OTP
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}">
                Kirim ulang OTP
            </a>
        </div>

        <div class="text-center mt-2">
            <a href="{{ route('login') }}">
                Kembali ke Login
            </a>
        </div>

    </div>

</body>

</html>
