<!DOCTYPE html>
<html>

<head>
    <title>Password Baru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
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
        }
    </style>
</head>

<body>

    <div class="card-reset">

        <h4 class="fw-bold text-center mb-3">Buat Password Baru</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf

            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Simpan Password</button>
        </form>

    </div>

</body>

</html>
