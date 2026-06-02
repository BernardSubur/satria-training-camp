@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Detail Member</h3>
            <p class="text-muted mb-0">Informasi lengkap profil dan keanggotaan member</p>
        </div>
        <a href="{{ route('admin.data-member') }}" class="btn btn-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="admin-card text-center mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=4f46e5&color=fff&size=80" class="rounded-circle shadow mb-3" width="80" height="80">
                <h4 class="fw-bold mb-1">{{ $member->name }}</h4>
                <p class="text-muted small mb-3">{{ $member->email }}</p>

                @if ($membership && $membership->status == 'aktif')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                    </span>
                @else
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                        <i class="bi bi-x-circle-fill me-1"></i> Nonaktif
                    </span>
                @endif
            </div>

            <div class="admin-card" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none; color: white;">
                <h6 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front me-2"></i> Info Keanggotaan</h6>

                <div class="bg-white bg-opacity-10 rounded-3 p-3">
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-white border-opacity-25">
                        <span style="opacity: 0.6;">Paket</span>
                        <span class="fw-bold">{{ $membership->paket->nama_paket ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-white border-opacity-25">
                        <span style="opacity: 0.6;">Sisa Sesi</span>
                        <span class="fw-bold">{{ $membership->sesi_tersisa ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="opacity: 0.6;">Expired</span>
                        <span class="fw-bold text-warning">{{ $membership->expired ? \Carbon\Carbon::parse($membership->expired)->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="admin-card mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2 text-primary"></i> Data Pribadi</h5>

                <div class="row g-3">
                    @php
                        $fields = [
                            ['Tempat Lahir', $member->tempat_lahir],
                            ['Tanggal Lahir', $member->tanggal_lahir],
                            ['Jenis Kelamin', $member->jenis_kelamin],
                            ['Pekerjaan / Sekolah', $member->pekerjaan],
                            ['No Telepon', $member->no_telp],
                            ['Agama', $member->agama],
                        ];
                    @endphp

                    @foreach($fields as $f)
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small fw-semibold mb-1">{{ $f[0] }}</div>
                            <div class="fw-bold">{{ $f[1] ?: '-' }}</div>
                        </div>
                    </div>
                    @endforeach

                    <div class="col-12">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small fw-semibold mb-1">Alamat</div>
                            <div class="fw-bold">{{ $member->alamat ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-heart-pulse-fill me-2 text-danger"></i> Data Fisik & Kesehatan</h5>

                <div class="row g-3">
                    @php
                        $healthFields = [
                            ['Golongan Darah', $member->golongan_darah],
                            ['Tinggi Badan', $member->tinggi_badan ? $member->tinggi_badan . ' cm' : '-'],
                            ['Berat Badan', $member->berat_badan ? $member->berat_badan . ' kg' : '-'],
                            ['Pengalaman Beladiri', $member->pernah_beladiri],
                        ];
                    @endphp

                    @foreach($healthFields as $f)
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="text-muted small fw-semibold mb-1">{{ $f[0] }}</div>
                            <div class="fw-bold">{{ $f[1] ?: '-' }}</div>
                        </div>
                    </div>
                    @endforeach

                    <div class="col-12">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3 border border-danger border-opacity-25">
                            <div class="text-danger small fw-semibold mb-1"><i class="bi bi-exclamation-triangle me-1"></i> Riwayat Penyakit Berat</div>
                            <div class="fw-bold">{{ $member->pernah_sakit ?: 'Tidak ada' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Reservasi</h5>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Sesi / Jam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservasis as $r)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</td>
                        <td class="fw-semibold">{{ $r->hari }}</td>
                        <td>
                            @if ($r->jam_mulai)
                                {{ \Carbon\Carbon::parse($r->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($r->jam_selesai)->format('H:i') }}
                            @else
                                {{ $r->sesi }}
                            @endif
                        </td>
                        <td>
                            @if($r->status == 'booked')
                                <span class="badge bg-primary bg-opacity-10 text-primary">Terjadwal</span>
                            @else
                                <span class="badge bg-light text-muted border">{{ ucfirst($r->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat reservasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
