@extends('layouts.admin')

@section('content')
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard</h3>
            <p class="text-muted mb-0">Selamat datang kembali, {{ auth()->user()->name ?? 'Admin' }}!</p>
        </div>
        <div class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="admin-card h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 5rem; margin-top: -10px; margin-right: -5px;">
                    <i class="bi bi-people-fill text-primary"></i>
                </div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Member Aktif</div>
                        <h2 class="fw-bold mb-0">{{ $totalMember }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="admin-card h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 5rem; margin-top: -10px; margin-right: -5px;">
                    <i class="bi bi-box-seam-fill text-info"></i>
                </div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Paket Terjual</div>
                        <h2 class="fw-bold mb-0">{{ $totalPaketTerjual }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="admin-card h-100 position-relative overflow-hidden" style="cursor: pointer;" onclick="window.location='{{ route('admin.pembayaran.index') }}'">
                <div class="position-absolute top-0 end-0 opacity-10" style="font-size: 5rem; margin-top: -10px; margin-right: -5px;">
                    <i class="bi bi-hourglass-split text-warning"></i>
                </div>
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending</div>
                        <h2 class="fw-bold mb-0">{{ $totalPending }}</h2>
                        <div class="text-warning small fw-semibold">Konfirmasi Bayar →</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="admin-card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: none;">
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-wallet2 fs-4 text-white"></i>
                    </div>
                    <div>
                        <div class="text-white small fw-semibold text-uppercase" style="opacity:0.7;">Total Pendapatan</div>
                        <h3 class="fw-bold mb-0 text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-calendar-range me-2 text-primary"></i> Jadwal Latihan 7 Hari ke Depan</h5>
        </div>

        <div class="row g-3">
            @foreach ($jadwal as $tanggal => $data)
                @php
                    $hasActivity = count($data['private']) > 0 || count($data['sesi1']) > 0 || count($data['sesi2']) > 0;
                    $isToday = \Carbon\Carbon::parse($tanggal)->isToday();
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="border rounded-4 h-100 overflow-hidden {{ $isToday ? 'border-primary border-2' : '' }}">

                        <div class="p-3 text-center {{ $isToday ? 'bg-primary text-white' : 'bg-light' }}">
                            <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l') }}</div>
                            <div class="{{ $isToday ? 'text-white-50' : 'text-muted' }} small">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</div>
                            @if($isToday)
                                <span class="badge bg-white text-primary mt-1 rounded-pill px-3">Hari Ini</span>
                            @endif
                        </div>

                        <div class="p-3">
                            {{-- Private --}}
                            @foreach ($data['private'] as $p)
                                <div class="d-flex align-items-start gap-2 mb-2 p-2 bg-primary bg-opacity-10 rounded-3">
                                    <span class="badge bg-primary mt-1">P</span>
                                    <div>
                                        <div class="fw-bold small text-dark">{{ $p['jam'] }}</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">{{ $p['nama'] }} <span class="text-primary">(Private)</span></div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Sesi I --}}
                            @if (count($data['sesi1']) > 0)
                                <div class="mb-2 p-2 bg-success bg-opacity-10 rounded-3">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-success">I</span>
                                        <span class="fw-bold small text-dark">Sesi I <span class="fw-normal text-muted">• 16:30 - 18:00</span></span>
                                    </div>
                                    <div class="ps-4">
                                        @foreach ($data['sesi1'] as $i => $nama)
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $i + 1 }}. {{ $nama }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Sesi II --}}
                            @if (count($data['sesi2']) > 0)
                                <div class="mb-2 p-2 bg-warning bg-opacity-10 rounded-3">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-warning text-dark">II</span>
                                        <span class="fw-bold small text-dark">Sesi II <span class="fw-normal text-muted">• 19:00 - 20:30</span></span>
                                    </div>
                                    <div class="ps-4">
                                        @foreach ($data['sesi2'] as $i => $nama)
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $i + 1 }}. {{ $nama }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Kosong --}}
                            @if (!$hasActivity)
                                <div class="text-center py-3">
                                    <i class="bi bi-moon-stars text-muted fs-4 d-block mb-1 opacity-50"></i>
                                    <span class="text-muted small">Tidak ada latihan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
