@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Reservasi</h3>
            <p class="text-muted mb-0">Semua jadwal reservasi latihan member</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama Member</th>
                        <th>Tipe</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($r->user->name) }}&background=e0e7ff&color=4f46e5&size=36" class="rounded-circle" width="36" height="36">
                                <span class="fw-semibold">{{ $r->user->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($r->user->role == 'member')
                                <span class="badge bg-primary bg-opacity-10 text-primary">Regular</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning">Private</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $r->hari }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $r->sesi }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox text-muted fs-1 d-block mb-2 opacity-50"></i>
                            <span class="text-muted">Belum ada data reservasi</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection