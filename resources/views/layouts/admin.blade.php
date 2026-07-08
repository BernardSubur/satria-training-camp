<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - Satria Training Camp</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/favicon-stc.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #e0e7ff;
            --dark: #0f172a;
            --light: #f1f5f9;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --sidebar-width: 270px;
            --topbar-height: 70px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--dark);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-header img {
            width: 42px;
            border-radius: 12px;
            background: white;
            padding: 4px;
        }

        .sidebar-header .brand-name {
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            line-height: 1.2;
        }

        .sidebar-header .brand-sub {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            font-weight: 400;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 14px;
            overflow-y: auto;
        }

        .sidebar-menu .menu-label {
            color: rgba(255,255,255,0.3);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin-bottom: 12px;
            margin-top: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 11px 14px;
            text-decoration: none;
            color: rgba(255,255,255,0.75);
            border-radius: 12px;
            margin-bottom: 4px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .sidebar-menu a i {
            font-size: 1.15rem;
            margin-right: 12px;
            width: 24px;
            text-align: center;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.9);
        }

        .sidebar-menu a.active {
            background: var(--primary);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .btn-logout {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, 0.3);
            padding: 10px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .top-navbar {
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .toggle-sidebar-btn {
            background: rgba(79, 70, 229, 0.1);
            border: none;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s;
        }
        .toggle-sidebar-btn:hover {
            background: rgba(79, 70, 229, 0.2);
        }

        .content-area {
            padding: 28px;
            flex: 1;
        }

        .admin-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        .card-box {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .table {
            font-size: 0.92rem;
        }

        .table thead th {
            background: var(--light);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 14px 16px;
        }

        .table thead th:first-child { border-radius: 12px 0 0 12px; }
        .table thead th:last-child { border-radius: 0 12px 12px 0; }

        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr:last-child td { border-bottom: none; }

        .table-bordered { border: none; }
        .table-bordered td, .table-bordered th { border: none; border-bottom: 1px solid var(--border); }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 18px;
        }
        .btn-primary:hover { background: var(--primary-dark); }

        .btn-secondary {
            background: var(--light);
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-secondary:hover { background: #e2e8f0; color: var(--dark); }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .toggle-sidebar-btn { display: flex; }
            .content-area { padding: 20px 16px; }
            .sidebar-overlay.show { display: block; }
            
            .admin-card {
                padding: 20px;
                border-radius: 16px;
                margin-bottom: 16px;
            }
            .row.g-4 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }
        }

        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 1025; display: none;
            backdrop-filter: blur(3px);
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('asset/images/logo-stc.png') }}" alt="STC">
            <div>
                <div class="brand-name">STC Admin</div>
                <div class="brand-sub">Satria Training Camp</div>
            </div>
            <button class="btn-close btn-close-white ms-auto d-lg-none" id="closeSidebar" style="font-size: 0.7rem;"></button>
        </div>

        <div class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <a href="{{ route('admin.data-reservasi') }}" class="{{ request()->routeIs('admin.data-reservasi') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check"></i> Data Reservasi
            </a>

            <a href="{{ route('admin.data-member') }}" class="{{ request()->routeIs('admin.data-member', 'admin.detail-member') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Data Member
            </a>

            <div class="menu-label mt-3">Keuangan</div>

            <a href="{{ route('admin.pembayaran.index') }}" class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Data Pembayaran
            </a>

            <a href="{{ route('admin.laporan-transaksi') }}" class="{{ request()->routeIs('admin.laporan-transaksi') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill"></i> Laporan Transaksi
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </nav>

    <div class="main-wrapper">
        <header class="top-navbar">
            <button class="toggle-sidebar-btn" id="toggleSidebar">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold" style="font-size: 0.95rem;">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Administrator</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=4f46e5&color=fff&bold=true" alt="Admin" class="rounded-circle shadow-sm" width="40" height="40">
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggleBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
