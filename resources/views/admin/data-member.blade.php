@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Member</h3>
            <p class="text-muted mb-0">Kelola semua data keanggotaan member</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Paket</th>
                        <th class="text-center">Sisa Sesi</th>
                        <th>Expired</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $m)
                    @php $membership = $m->membership_aktif; @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=e0e7ff&color=4f46e5&size=40" class="rounded-circle shadow-sm" width="40" height="40">
                                <div>
                                    <div class="fw-bold">{{ $m->name }}</div>
                                    <div class="text-muted small">{{ $m->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($membership && $membership->paket)
                                <span class="fw-semibold text-primary">{{ $membership->paket->nama_paket }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($membership && $membership->sesi_tersisa !== null)
                                <span class="fw-bold {{ $membership->sesi_tersisa <= 2 ? 'text-danger' : 'text-dark' }}">{{ $membership->sesi_tersisa }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($membership && $membership->expired)
                                {{ \Carbon\Carbon::parse($membership->expired)->translatedFormat('d M Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($membership)
                                @if ($membership->status == 'aktif')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">Aktif</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3">{{ ucfirst($membership->status) }}</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted border">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.detail-member', $m->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection