<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Satria Training Camp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-soft: #eef2ff;
            --dark: #0f172a;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-600: #475569;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            min-height: 100vh;
        }

        .payment-header {
            background-color: white;
            border-bottom: 1px solid var(--slate-200);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .checkout-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid var(--slate-200);
        }

        .timer-count {
            color: #dc2626;
            font-family: monospace;
            font-size: 1.2rem;
        }

        .section-header {
            padding: 24px 24px 10px;
        }

        .summary-box {
            background: var(--slate-50);
            padding: 20px;
            margin: 0 24px 24px;
            border-radius: 16px;
            border: 1px solid var(--slate-100);
        }

        .total-amount {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
        }

        .method-tabs {
            padding: 0 24px;
        }

        .nav-pills .nav-link {
            padding: 12px;
            border-radius: 12px;
            color: var(--slate-600);
            font-weight: 600;
            border: 1px solid var(--slate-200);
            margin-bottom: 10px;
            transition: all 0.2s;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-soft);
            color: var(--primary);
            border-color: var(--primary);
        }

        .qris-container {
            padding: 30px;
            background: white;
            border: 1px solid var(--slate-200);
            border-radius: 16px;
            margin: 10px 0;
            position: relative;
        }

        .qris-image-wrapper {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            display: inline-block;
            transition: opacity 0.5s ease;
        }

        .qris-image-wrapper img {
            width: 280px;
            height: auto;
            display: block;
        }

        .qris-expired-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.95);
            z-index: 10;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
        }

        .bank-info-card {
            background: var(--slate-50);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--slate-200);
        }

        .copy-btn {
            cursor: pointer;
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            background: none;
            padding: 0;
        }

        .copy-btn:hover {
            text-decoration: underline;
        }

        .btn-submit {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 50px;
            padding: 16px;
            font-weight: 700;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-back {
            color: var(--slate-600);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .checkout-container {
                margin: 15px auto;
            }
            .total-amount {
                font-size: 1.5rem;
            }
            .qris-image-wrapper img {
                width: 100%;
                max-width: 240px;
            }
        }
    </style>
</head>
<body>

    <header class="payment-header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('asset/images/logo-stc.png') }}" width="40" alt="Logo">
                <span class="fw-bold text-dark">Checkout STC</span>
            </div>
        </div>
    </header>

    <div class="checkout-container">
        <a href="{{ route('paket') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Ganti Paket
        </a>

        <div class="payment-card">
            <div class="section-header">
                <h6 class="text-uppercase text-muted small fw-bold mb-1">Total Bayar</h6>
                <div class="total-amount">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
            </div>

            <div class="summary-box">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Paket</span>
                    <span class="fw-bold">{{ $paket->nama_paket }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Member</span>
                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                </div>
            </div>

            <div class="method-tabs">
                <h6 class="fw-bold mb-3">Pilih Metode Pembayaran</h6>
                
                <div class="nav flex-column nav-pills" id="pills-tab" role="tablist">
                    <button class="nav-link active text-start" id="pills-qris-tab" data-bs-toggle="pill" data-bs-target="#pills-qris" type="button" role="tab">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-qr-code-scan me-2"></i> QRIS / E-Wallet</span>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" height="20" alt="QRIS">
                        </div>
                    </button>
                    <button class="nav-link text-start" id="pills-transfer-tab" data-bs-toggle="pill" data-bs-target="#pills-transfer" type="button" role="tab">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-bank me-2"></i> Transfer Bank</span>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary px-2" style="font-size: 0.6rem">BCA</span>
                            </div>
                        </div>
                    </button>
                </div>

                <div class="tab-content mt-4" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-qris" role="tabpanel">
                        <div class="qris-container text-center">
                            <div class="qris-image-wrapper" id="qris-image">
                                <img src="{{ asset('asset/images/qris.jpg') }}" alt="QRIS STC">
                            </div>
                            <p class="text-muted small mt-3 mb-0">Scan menggunakan aplikasi bank atau e-wallet (GoPay, OVO, Dana, dll)</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-transfer" role="tabpanel">
                        <div class="bank-info-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary px-3 py-2">BANK BCA</span>
                                <span class="text-muted small">Dicek Manual</span>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Nomor Rekening</div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fs-4 fw-bold" id="norek">0462-123-122</span>
                                    <button class="copy-btn" onclick="copyText('0462123122', this)">Salin</button>
                                </div>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Nama Pemilik Rekening</div>
                                <div class="fw-bold">Dita Rizky Rindiani</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 pb-4">
                <hr class="my-4">
                <form action="{{ route('pembayaran.store', $paket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h6 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-2"></i> Konfirmasi Pembayaran</h6>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label small">Metode yang Digunakan</label>
                        <select name="metode_pembayaran" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS / E-Wallet</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small">Upload Bukti Transfer</label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        <div class="form-text" style="font-size: 0.75rem">Format: JPG, PNG. Ukuran maks 2MB.</div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyText(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.textContent;
                btn.textContent = 'Tersalin!';
                btn.style.color = '#10b981';
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.color = 'var(--primary)';
                }, 2000);
            });
        }
    </script>
</body>
</html>

