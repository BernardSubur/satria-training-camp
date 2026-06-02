<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password</title>

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

        .card-reset {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body>

    <div class="card-reset">

        <h4 class="fw-bold mb-3 text-center">Forgot Password</h4>

        <p class="text-muted text-center mb-4">
            Masukkan email untuk menerima kode OTP
        </p>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.send.otp') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>

                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <button class="btn btn-primary w-100">
                Kirim OTP
            </button>

        </form>

        <div class="text-center mt-3">

            <a href="{{ route('login') }}" class="text-decoration-none">
                Kembali ke Login
            </a>

        </div>

    </div>

</body>

</html>
