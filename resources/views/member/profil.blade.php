@extends('layouts.member')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Profil Member</h3>
            <p class="text-muted mb-0">Kelola informasi pribadi dan data diri Anda</p>
        </div>
    </div>

    <div class="content-card">

        @if (session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 p-3 mb-4 d-flex align-items-center small">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-4 small">
                <div class="d-flex align-items-center mb-2 fw-bold">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-6"></i> Terdapat Kesalahan
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4f46e5&color=fff&size=80" alt="Avatar" class="rounded-circle shadow-sm">
            <div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <div class="text-muted small">{{ $user->email }}</div>
            </div>
            
            <div class="ms-auto">
                <button type="button" id="btnEdit" class="btn btn-outline-primary fw-bold rounded-pill px-4">
                    <i class="bi bi-pencil-square me-2"></i> Edit Profil
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('profil.update') }}">
            @csrf
            
            <h5 class="fw-bold mb-3 fs-6 text-primary"><i class="bi bi-person-lines-fill me-2"></i> Informasi Dasar</h5>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-light form-profil" value="{{ $user->name }}" readonly required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-muted small">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control bg-light form-profil" value="{{ $user->tempat_lahir }}" readonly required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-muted small">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control bg-light form-profil" value="{{ $user->tanggal_lahir }}" readonly required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select bg-light form-profil" disabled required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ $user->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $user->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold text-muted small">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" class="form-control bg-light form-profil" rows="2" readonly required>{{ $user->alamat }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">Pekerjaan / Sekolah <span class="text-danger">*</span></label>
                    <input type="text" name="pekerjaan" class="form-control bg-light form-profil" value="{{ $user->pekerjaan }}" readonly required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">No WhatsApp <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">+62</span>
                        <input type="text" name="no_telp" class="form-control bg-light form-profil border-start-0" value="{{ str_replace('+62', '', $user->no_telp ?? '') }}" readonly required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Agama <span class="text-danger">*</span></label>
                    <input type="text" name="agama" class="form-control bg-light form-profil" value="{{ $user->agama }}" readonly required>
                </div>
            </div>

            <h5 class="fw-bold mb-3 fs-6 text-primary"><i class="bi bi-heart-pulse-fill me-2"></i> Data Fisik & Kesehatan</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Golongan Darah <span class="text-danger">*</span></label>
                    <input type="text" name="golongan_darah" class="form-control bg-light form-profil" value="{{ $user->golongan_darah }}" readonly required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                    <input type="number" name="tinggi_badan" class="form-control bg-light form-profil" value="{{ $user->tinggi_badan }}" readonly required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">Berat Badan (kg) <span class="text-danger">*</span></label>
                    <input type="number" name="berat_badan" class="form-control bg-light form-profil" value="{{ $user->berat_badan }}" readonly required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">Pengalaman Beladiri</label>
                    <input type="text" name="pernah_beladiri" class="form-control bg-light form-profil" value="{{ $user->pernah_beladiri }}" placeholder="Kosongkan jika belum pernah" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">Riwayat Penyakit Berat</label>
                    <input type="text" name="pernah_sakit" class="form-control bg-light form-profil border-danger" value="{{ $user->pernah_sakit }}" placeholder="Sebutkan jika ada riwayat asma, jantung, dll" readonly>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="submit" id="btnSimpan" class="btn btn-primary fw-bold rounded-pill px-5 d-none">
                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        const btnEdit = document.getElementById('btnEdit');
        const btnSimpan = document.getElementById('btnSimpan');

        btnEdit.addEventListener('click', function() {
            document.querySelectorAll('.form-profil').forEach(function(el) {
                el.removeAttribute('readonly');
                el.removeAttribute('disabled');
                el.classList.remove('bg-light');
                el.classList.add('bg-white');
            });

            btnEdit.classList.add('d-none');
            btnSimpan.classList.remove('d-none');
        });
    </script>
@endsection
