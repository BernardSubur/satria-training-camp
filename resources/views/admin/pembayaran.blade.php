@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Pembayaran</h3>
            <p class="text-muted mb-0">Kelola konfirmasi pembayaran member</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 p-3 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Member</th>
                        <th>Paket</th>
                        <th>Metode</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $key => $payment)
                    <tr id="payment-row-{{ $payment->id }}">
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($payment->user->name ?? '-') }}&background=e0e7ff&color=4f46e5&size=36" class="rounded-circle" width="36" height="36">
                                <span class="fw-semibold">{{ $payment->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $payment->paket->nama_paket ?? '-' }}</div>
                            <div class="text-muted small">Rp {{ number_format($payment->paket->harga ?? 0, 0, ',', '.') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-semibold">{{ strtoupper($payment->metode_pembayaran) }}</span>
                        </td>
                        <td class="text-center">
                            @if ($payment->status === 'pending')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3">Pending</span>
                            @elseif ($payment->status === 'success')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">Diterima</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="showBukti({{ $payment->id }})" title="Lihat Bukti" data-bs-toggle="tooltip">
                                    <i class="bi bi-image"></i> <span class="d-none d-sm-inline ms-1">Bukti</span>
                                </button>

                                @if ($payment->status === 'rejected')
                                    <button class="btn btn-sm btn-delete-outline rounded-pill px-3" onclick="confirmDelete({{ $payment->id }})" title="Hapus Data" data-bs-toggle="tooltip">
                                        <i class="bi bi-trash"></i> <span class="d-none d-sm-inline ms-1">Hapus</span>
                                    </button>
                                @endif

                                @if ($payment->status === 'pending')
                                    <form action="{{ route('admin.pembayaran.accept', $payment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Terima pembayaran ini?')">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pembayaran.reject', $payment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Tolak pembayaran ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if(count($payments) === 0)
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-muted fs-1 d-block mb-2 opacity-50"></i>
                            <span class="text-muted">Belum ada data pembayaran.</span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div id="customOverlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(6px);">
        <div style="background: #fff; border-radius: 20px; width: 90%; max-width: 450px; padding: 28px; position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.2); animation: popIn 0.25s ease;">
            <button type="button" onclick="closeOverlay()" class="btn-close position-absolute" style="top: 20px; right: 20px;"></button>
            <h5 class="fw-bold mb-4" id="overlayTitle">Bukti Pembayaran</h5>

            <div id="overlayLoading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <p class="text-muted fw-semibold">Mengambil data...</p>
            </div>

            <div id="overlayContent" class="d-none">
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">User</span>
                        <strong id="overlayUser" class="text-dark"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Paket</span>
                        <strong id="overlayPaket" class="text-primary"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode</span>
                        <strong id="overlayMetode" class="text-dark"></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span id="overlayStatus"></span>
                    </div>
                </div>

                <div id="overlayImageContainer" class="text-center bg-light rounded-3 p-3 border border-dashed">

                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes popIn {
            from { transform: translateY(20px) scale(0.96); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .btn-delete-outline {
            color: #ef4444;
            background-color: transparent;
            border: 1px solid #fecaca;
            transition: all 0.2s ease;
            font-size: 0.82rem;
            padding: 4px 12px;
        }

        .btn-delete-outline:hover {
            background-color: #ef4444;
            border-color: #ef4444;
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .modal-confirm {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
        }

        .modal-confirm .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        }

        .toast-container {
            z-index: 10000;
        }

        .custom-toast {
            background: #fff;
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid #10b981;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .custom-toast.show {
            transform: translateX(0);
        }

        .custom-toast .icon {
            width: 32px;
            height: 32px;
            background: #d1fae5;
            color: #10b981;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
    </style>

    <div class="modal fade modal-confirm" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content overflow-hidden">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle" style="width: 60px; height: 60px;">
                            <i class="bi bi-exclamation-triangle fs-2"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Hapus Data Pembayaran?</h5>
                    <p class="text-muted mb-4">Data pembayaran yang ditolak akan dihapus permanen dan tidak dapat dikembalikan.</p>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light border flex-grow-1 rounded-pill fw-semibold py-2" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="btnConfirmDelete" class="btn btn-danger flex-grow-1 rounded-pill fw-semibold py-2">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-4">
        <div id="successToast" class="custom-toast">
            <div class="icon">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <div class="fw-bold text-dark" style="font-size: 0.95rem;">Berhasil!</div>
                <div class="text-muted small">Data pembayaran berhasil dihapus</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function closeOverlay() {
        let overlay = document.getElementById('customOverlay');
        overlay.classList.add('d-none');
        overlay.classList.remove('d-flex');
    }

    function showBukti(paymentId) {
        let overlay = document.getElementById('customOverlay');
        overlay.classList.remove('d-none');
        overlay.classList.add('d-flex');

        document.getElementById('overlayLoading').classList.remove('d-none');
        document.getElementById('overlayContent').classList.add('d-none');
        document.getElementById('overlayTitle').innerText = 'Bukti Pembayaran';

        fetch(`/admin/pembayaran/${paymentId}/bukti`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('overlayLoading').classList.add('d-none');
                document.getElementById('overlayContent').classList.remove('d-none');

                document.getElementById('overlayTitle').innerText = 'Bukti - ' + data.nama_user;
                document.getElementById('overlayUser').innerText = data.nama_user;
                document.getElementById('overlayPaket').innerText = data.paket;
                document.getElementById('overlayMetode').innerText = data.metode;

                let statusHtml = '';
                if (data.status === 'pending') {
                    statusHtml = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Pending</span>';
                } else if (data.status === 'success') {
                    statusHtml = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Diterima</span>';
                } else {
                    statusHtml = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Ditolak</span>';
                }
                document.getElementById('overlayStatus').innerHTML = statusHtml;

                let imgContainer = document.getElementById('overlayImageContainer');
                if (data.bukti_url) {
                    imgContainer.innerHTML = `<img src="${data.bukti_url}" alt="Bukti Pembayaran" class="img-fluid rounded-3 shadow-sm" style="max-height: 400px; object-fit: contain; width: 100%;">`;
                } else {
                    imgContainer.innerHTML = '<p class="text-muted my-4"><i class="bi bi-image fs-2 d-block mb-2 opacity-50"></i>Gambar tidak tersedia</p>';
                }
            })
            .catch(error => {
                document.getElementById('overlayLoading').classList.add('d-none');
                document.getElementById('overlayContent').classList.remove('d-none');
                document.getElementById('overlayImageContainer').innerHTML = '<p class="text-danger my-4">Gagal memuat data.</p>';
            });
    }

    let deleteId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    function confirmDelete(id) {
        deleteId = id;
        deleteModal.show();
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        if (!deleteId) return;

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menghapus...';

        fetch(`/admin/pembayaran/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            deleteModal.hide();
            if (data.success) {
                const row = document.getElementById(`payment-row-${deleteId}`);
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                
                setTimeout(() => {
                    row.remove();
                    showToast();
                }, 500);
            } else {
                alert(data.message || 'Gagal menghapus data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Ya, Hapus';
            deleteId = null;
        });
    });

    function showToast() {
        const toast = document.getElementById('successToast');
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
