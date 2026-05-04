<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RS Cahya Medika Bondowoso')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #0c4a6e;
            --primary-light: #0369a1;
            --primary-soft: #e0f2fe;
            --accent: #06b6d4;
            --accent-light: #cffafe;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-400: #94a3b8;
            --gray-600: #475569;
            --gray-800: #1e293b;
            --sidebar-w: 260px;
            --header-h: 64px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--primary) 0%, #0a3a5c 100%);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo .logo-icon {
            width: 44px; height: 44px;
            background: var(--accent);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .sidebar-logo .rs-name {
            color: white;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.3;
        }

        .sidebar-logo .rs-sub {
            color: rgba(255,255,255,0.5);
            font-size: 10px;
            margin-top: 2px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 20px;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.35);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 8px;
            margin-bottom: 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-item.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 12px rgba(6,182,212,0.3);
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 14px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
        }

        .user-avatar {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-info .user-name {
            color: white;
            font-size: 12px;
            font-weight: 600;
        }

        .user-info .user-role {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            height: var(--header-h);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-800);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-date {
            font-size: 12px;
            color: var(--gray-400);
            background: var(--gray-100);
            padding: 6px 12px;
            border-radius: 8px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #fee2e2;
            color: var(--danger);
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
        }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ===== CARDS ===== */
        .card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--gray-800);
        }

        .card-body {
            padding: 22px;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--gray-800);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--gray-400);
            margin-top: 4px;
        }

        /* ===== TABLES ===== */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            background: var(--gray-50);
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
            color: var(--gray-600);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200);
        }

        tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-800);
            vertical-align: middle;
        }

        tbody tr:hover {
            background: var(--gray-50);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #e0f2fe; color: #0c4a6e; }
        .badge-secondary { background: var(--gray-100); color: var(--gray-600); }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover { background: var(--primary-light); }

        .btn-accent {
            background: var(--accent);
            color: white;
        }
        .btn-accent:hover { background: #0891b2; }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-outline {
            background: white;
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
        }
        .btn-outline:hover { background: var(--gray-50); }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: var(--gray-800);
            transition: border-color 0.2s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(6,182,212,0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 11px;
            margin-top: 4px;
        }

        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: var(--gray-800);
            background: white;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--accent);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 90px;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid var(--success); }
        .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid var(--danger); }
        .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid var(--warning); }
        .alert-info { background: var(--primary-soft); color: var(--primary); border-left: 4px solid var(--accent); }

        /* ===== GRID ===== */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }

        /* ===== UTILS ===== */
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .text-muted { color: var(--gray-400); }
        .fw-bold { font-weight: 700; }
        .text-sm { font-size: 12px; }
        .text-center { text-align: center; }

        /* ===== SATUSEHAT BADGE ===== */
        .ss-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
        }
        .ss-badge.success { background: #d1fae5; color: #065f46; }
        .ss-badge.pending { background: #fef3c7; color: #92400e; }
        .ss-badge.failed { background: #fee2e2; color: #991b1b; }

        /* ===== PAGINATION ===== */
        .pagination { display: flex; gap: 6px; }
        .page-item .page-link {
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--gray-600);
            background: white;
            border: 1px solid var(--gray-200);
            text-decoration: none;
        }
        .page-item.active .page-link {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">🏥</div>
            <div class="rs-name">RS Cahya Medika</div>
            <div class="rs-sub">Bondowoso · Non-BPJS</div>
        </div>

        <nav class="sidebar-nav">
            @if(auth()->user()->isAdmin())
                <div class="nav-section">
                    <div class="nav-section-title">Utama</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Manajemen</div>
                    <a href="{{ route('admin.pendaftaran.index') }}" class="nav-item {{ request()->routeIs('admin.pendaftaran*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Pendaftaran
                    </a>
                    <a href="{{ route('admin.pasien.index') }}" class="nav-item {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Data Pasien
                    </a>
                    <a href="{{ route('admin.dokter.index') }}" class="nav-item {{ request()->routeIs('admin.dokter*') ? 'active' : '' }}">
                        <i class="fas fa-user-md"></i> Dokter
                    </a>
                    <a href="{{ route('admin.poli.index') }}" class="nav-item {{ request()->routeIs('admin.poli*') ? 'active' : '' }}">
                        <i class="fas fa-door-open"></i> Poli / Klinik
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Integrasi</div>
                    <a href="{{ route('admin.satusehat.status') }}" class="nav-item {{ request()->routeIs('admin.satusehat*') ? 'active' : '' }}">
                        <i class="fas fa-link"></i> SatuSehat API
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <i class="fas fa-file-chart-line"></i> Laporan
                    </a>
                </div>
            @else
                <div class="nav-section">
                    <div class="nav-section-title">Menu</div>
                    <a href="{{ route('pasien.dashboard') }}" class="nav-item {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a href="{{ route('pasien.pendaftaran.create') }}" class="nav-item {{ request()->routeIs('pasien.pendaftaran.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i> Daftar Berobat
                    </a>
                    <a href="{{ route('pasien.pendaftaran.index') }}" class="nav-item {{ request()->routeIs('pasien.pendaftaran.index') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Pendaftaran Saya
                    </a>
                    <a href="{{ route('pasien.riwayat') }}" class="nav-item {{ request()->routeIs('pasien.riwayat') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Riwayat Kunjungan
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Akun</div>
                    <a href="{{ route('pasien.profil') }}" class="nav-item {{ request()->routeIs('pasien.profil') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i> Profil Saya
                    </a>
                </div>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main-wrapper">
        <header class="header">
            <div class="header-title">@yield('page-title', 'Dashboard')</div>
            <div class="header-right">
                <div class="header-date">
                    <i class="fas fa-calendar-alt" style="margin-right:5px; color:var(--accent)"></i>
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin: 4px 0 0 16px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
