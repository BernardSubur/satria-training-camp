@extends('layouts.member')

@section('content')
    <h4 class="fw-bold mb-4">Setting</h4>

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- VALIDATION ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-box">

        <h5 class="mb-3">Ganti Password</h5>

        <form method="POST" action="{{ route('setting.password') }}">
            @csrf

            <div class="mb-3">
                <label>Password Lama</label>
                <input type="password" name="password_lama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password" name="password_baru" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_baru_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-danger">
                Ganti Password
            </button>

        </form>

    </div>

@endsection
