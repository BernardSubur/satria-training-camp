@extends('layouts.member')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Reservasi Jadwal</h3>
            <p class="text-muted mb-0">Atur jadwal latihan Anda dengan mudah</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="content-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded">
                        <i class="bi bi-calendar-plus fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Buat Reservasi Baru</h5>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-4 d-flex align-items-center small">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 p-3 mb-4 d-flex align-items-center small">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('reservasi.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Pilih Hari</label>
                        <select name="hari" id="hari" class="form-select bg-light" required>
                            <option value="">-- Pilih Hari --</option>
                            @if (auth()->user()->role == 'member_private')
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            @else
                                <option value="Selasa">Selasa</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Pilih Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control bg-light" min="{{ date('Y-m-d') }}" required>
                    </div>

                    @if (auth()->user()->role == 'member_private')
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-muted small">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control bg-light" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-muted small">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control bg-light" required>
                            </div>
                        </div>
                        <div class="form-text text-warning small mb-4"><i class="bi bi-info-circle"></i> Durasi wajib tepat 1 jam.</div>
                    @else
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">Pilih Sesi</label>
                            <select name="sesi" id="sesi" class="form-select bg-light" required>
                                <option value="">-- Pilih Sesi --</option>
                            </select>
                            <div class="form-text small mt-2">Pilih hari dan tanggal terlebih dahulu untuk melihat slot.</div>
                        </div>
                    @endif

                    <button class="btn btn-primary w-100 fw-bold py-2 rounded-3">
                        <i class="bi bi-calendar-check me-2"></i> Konfirmasi Reservasi
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="content-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-success bg-opacity-10 text-success p-2 rounded">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Riwayat & Jadwal Saya</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted small fw-semibold border-0 py-3 rounded-start">Jadwal</th>
                                <th class="text-muted small fw-semibold border-0 py-3 text-center">Sesi</th>
                                <th class="text-muted small fw-semibold border-0 py-3 text-center">Waktu</th>
                                <th class="text-muted small fw-semibold border-0 py-3 rounded-end"></th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($reservasis as $item)
                                <tr>
                                    <td class="py-3 border-bottom">
                                        <div class="fw-bold text-dark">{{ $item->hari }}</div>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                                    </td>
                                    <td class="py-3 border-bottom text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $item->sesi ?? 'Private' }}</span>
                                    </td>
                                    <td class="py-3 border-bottom text-center">
                                        <div class="fw-semibold text-dark small">
                                            @if ($item->jam_mulai)
                                                {{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}
                                            @else
                                                @if ($item->hari == 'Selasa' || $item->hari == 'Kamis')
                                                    {{ $item->sesi == 'Sesi I' ? '16.30 - 18.00' : '19.00 - 20.30' }}
                                                @elseif ($item->hari == 'Sabtu' || $item->hari == 'Minggu')
                                                    {{ $item->sesi == 'Sesi I' ? '15.30 - 17.00' : '17.00 - 18.30' }}
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 border-bottom text-end">
                                        @if(\Carbon\Carbon::parse($item->tanggal)->isFuture() || \Carbon\Carbon::parse($item->tanggal)->isToday())
                                            <form method="POST" action="{{ route('reservasi.batal', $item->id) }}" onsubmit="return confirm('Yakin ingin membatalkan jadwal ini? Sesi akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">Batal</button>
                                            </form>
                                        @else
                                            <span class="badge bg-light text-muted border">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Belum ada riwayat reservasi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role != 'member_private')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const hari = document.getElementById('hari');
                const tanggal = document.getElementById('tanggal');
                const sesi = document.getElementById('sesi');

                function loadSesi() {
                    if (!hari.value || !tanggal.value) return;

                    sesi.innerHTML = '<option value="">Memuat Slot...</option>';

                    fetch("{{ route('get.slot') }}?tanggal=" + tanggal.value)
                        .then(res => res.json())
                        .then(data => {
                            let slot1 = 17 - data.slot1;
                            let slot2 = 17 - data.slot2;

                            sesi.innerHTML = '<option value="">-- Pilih Sesi --</option>';

                            if (hari.value == 'Selasa' || hari.value == 'Kamis') {
                                sesi.innerHTML += slot1 > 0 ? `<option value="Sesi I">Sesi I (16.30 - 18.00) - Sisa ${slot1} Slot</option>` : `<option disabled>Sesi I - Penuh</option>`;
                                sesi.innerHTML += slot2 > 0 ? `<option value="Sesi II">Sesi II (19.00 - 20.30) - Sisa ${slot2} Slot</option>` : `<option disabled>Sesi II - Penuh</option>`;
                            }

                            if (hari.value == 'Sabtu' || hari.value == 'Minggu') {
                                sesi.innerHTML += slot1 > 0 ? `<option value="Sesi I">Sesi I (15.30 - 17.00) - Sisa ${slot1} Slot</option>` : `<option disabled>Sesi I - Penuh</option>`;
                                sesi.innerHTML += slot2 > 0 ? `<option value="Sesi II">Sesi II (17.00 - 18.30) - Sisa ${slot2} Slot</option>` : `<option disabled>Sesi II - Penuh</option>`;
                            }
                        });
                }

                hari.addEventListener('change', loadSesi);
                tanggal.addEventListener('change', loadSesi);
            });
        </script>
    @endif

    @if (auth()->user()->role == 'member_private')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mulai = document.getElementById('jam_mulai');
                const selesai = document.getElementById('jam_selesai');

                function cekDurasi() {
                    if (mulai.value && selesai.value) {
                        let start = new Date("1970-01-01T" + mulai.value);
                        let end = new Date("1970-01-01T" + selesai.value);
                        let diff = (end - start) / 60000;

                        if (diff !== 60) {
                            alert("Durasi latihan wajib tepat 1 jam!");
                            selesai.value = "";
                        }
                    }
                }

                mulai.addEventListener('change', cekDurasi);
                selesai.addEventListener('change', cekDurasi);
            });
        </script>
    @endif

@endsection