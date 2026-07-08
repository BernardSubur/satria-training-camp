@extends('layouts.admin')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Laporan Transaksi</h3>
            <p class="text-muted mb-0">Rincian pembelian paket dan total pemasukan</p>
        </div>
    </div>

    <div class="admin-card mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" class="form-control bg-light" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" class="form-control bg-light" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button class="btn btn-primary rounded-pill px-4"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('admin.laporan-transaksi') }}" class="btn btn-secondary rounded-pill px-3">Reset</a>
            </div>
            <div class="col-md-auto ms-md-auto">
                <a href="{{ route('admin.export-pdf') }}" class="btn btn-outline-danger rounded-pill px-4">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="admin-card h-100" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: none; color: white;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-cash-stack fs-4 text-white"></i>
                    </div>
                    <div>
                        <div class="small text-white" style="opacity: 0.7;">Total Transaksi</div>
                        <h3 class="fw-bold mb-0 text-white">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="bi bi-receipt-cutoff fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Jumlah Transaksi</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $jumlahTransaksi }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Jenis Paket Terjual</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $perPaket->count() }} jenis</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart-line me-2 text-primary"></i> Ringkasan Per Paket</h5>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th class="text-center">Jumlah Terjual</th>
                        <th class="text-end">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perPaket as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item['nama_paket'] }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3">{{ $item['jumlah'] }} transaksi</span>
                        </td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @if(count($perPaket) === 0)
                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-list-ul me-2 text-primary"></i> Detail Transaksi</h5>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Member</th>
                        <th>Paket</th>
                        <th>Metode</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $key => $p)
                    <tr>
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td>{{ $p->created_at->translatedFormat('d M Y') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $p->user->name ?? '-' }}</div>
                            <div class="text-muted small">{{ $p->user->email ?? '-' }}</div>
                        </td>
                        <td class="fw-semibold text-primary">{{ $p->paket->nama_paket ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($p->metode_pembayaran) }}</span></td>
                        <td class="text-end fw-bold">Rp {{ number_format($p->paket->harga ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">Diterima</span>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($payments) === 0)
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data transaksi.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
