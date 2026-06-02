@extends('layouts.member')

@section('content')
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard</h3>
            <p class="text-muted small mb-0">Ringkasan aktivitas latihan Anda</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="content-card h-100 d-flex align-items-center position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="bi bi-calendar-event fs-1 text-primary"></i>
                </div>
                <div class="d-flex gap-3 align-items-center z-1">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Latihan Hari Ini</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $latihanHariIni }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="content-card h-100 d-flex align-items-center position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="bi bi-lightning fs-1 text-warning"></i>
                </div>
                <div class="d-flex gap-3 align-items-center z-1">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                        <i class="bi bi-battery-charging fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Sisa Sesi Latihan</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $sisaSesi }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="content-card h-100 d-flex align-items-center position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="bi bi-trophy fs-1 text-success"></i>
                </div>
                <div class="d-flex gap-3 align-items-center z-1">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                        <i class="bi bi-check-all fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Sesi Selesai</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $latihanSelesai }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="content-card h-100 p-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: white; border: none; box-shadow: 0 15px 30px rgba(79, 70, 229, 0.2);">
                <h5 class="fw-bold mb-4"><i class="bi bi-credit-card-2-front me-2"></i> Keanggotaan Aktif</h5>
                
                @if ($membership)
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div>
                            <div class="text-white-50 small mb-1">Paket Saat Ini</div>
                            <h3 class="fw-bold mb-0 text-white">{{ $membership->paket->nama_paket }}</h3>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-award-fill"></i>
                        </div>
                    </div>
                    
                    <div class="bg-white bg-opacity-10 p-3 rounded-3">
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-white border-opacity-25">
                            <span class="text-white-50 small">Mulai</span>
                            <span class="fw-semibold small">{{ \Carbon\Carbon::parse($membership->mulai)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-white-50 small">Berakhir</span>
                            <span class="fw-semibold text-warning small">{{ \Carbon\Carbon::parse($membership->expired)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="bg-white bg-opacity-10 w-60 h-60 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:60px; height:60px;">
                            <i class="bi bi-emoji-frown fs-2 text-white-50"></i>
                        </div>
                        <p class="text-white-50 small">Belum ada paket aktif.</p>
                        <a href="{{ route('paket') }}" class="btn btn-light btn-sm fw-bold px-4 rounded-pill mt-2">Beli Paket</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7">
            <div class="content-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark">Jadwal Anda Mendatang</h5>
                    <a href="{{ route('reservasi') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Kelola Reservasi</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted small fw-semibold border-0 rounded-start py-3 px-3">Tanggal</th>
                                <th class="text-muted small fw-semibold border-0 py-3">Sesi / Jam</th>
                                <th class="text-muted small fw-semibold border-0 rounded-end py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($reservasis->take(4) as $item)
                                <tr>
                                    <td class="px-3 border-bottom py-3">
                                        <div class="fw-bold text-dark">{{ $item->hari }}</div>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                                    </td>
                                    <td class="border-bottom py-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $item->sesi ?? 'Private' }}</span>
                                        <div class="fw-semibold text-dark small">
                                            @if ($item->jam_mulai && $item->jam_selesai)
                                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                            @else
                                                @if ($item->hari == 'Selasa' || $item->hari == 'Kamis')
                                                    {{ $item->sesi == 'Sesi I' ? '16.30 - 18.00' : '19.00 - 20.30' }}
                                                @elseif ($item->hari == 'Sabtu' || $item->hari == 'Minggu')
                                                    {{ $item->sesi == 'Sesi I' ? '15.30 - 17.00' : '17.00 - 18.30' }}
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border-bottom py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-light text-danger fw-semibold px-3" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#modalBatal">
                                            Batal
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 border-bottom">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="60" class="mb-3 opacity-50">
                                        <p class="text-muted mb-0">Belum ada jadwal reservasi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBatal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-5 text-center">
                    <div class="d-inline-flex bg-danger bg-opacity-10 text-danger p-3 rounded-circle mb-4">
                        <i class="bi bi-x-circle fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Batalkan Reservasi?</h4>
                    <p class="text-muted mb-4">Apakah kamu yakin ingin membatalkan jadwal latihan ini? Sesi akan dikembalikan.</p>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Tutup</button>
                        <form id="formBatal" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger px-4 rounded-pill fw-bold">Ya, Batalkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalBatal = document.getElementById('modalBatal');
            if (modalBatal) {
                modalBatal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const form = document.getElementById('formBatal');
                    form.action = "{{ url('/reservasi') }}/" + id + "/batal";
                });
            }
        });
    </script>
@endsection
