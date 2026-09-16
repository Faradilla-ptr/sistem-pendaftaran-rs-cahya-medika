<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RS Cahya Medika Bondowoso')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --sidebar-w: 230px;
            --border: #e5e7eb;
            --text-dark: #111827;
            --text-mid: #374151;
            --text-muted: #6b7280;
            --text-light: #9ca3af;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.07);
            --radius: 10px;
            --radius-lg: 14px;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; color: var(--text-dark); display: flex; min-height: 100vh; font-size: 14px; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-w); background: #fff; border-right: 1px solid var(--border); position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; display: flex; flex-direction: column; overflow-y: auto; }
        .sb-logo { padding: 16px 14px 12px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
        .sb-logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg,#1d4ed8,#0891b2); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
        .sb-logo-text .name { font-size: 13px; font-weight: 700; color: var(--text-dark); line-height: 1.2; }
        .sb-logo-text .sub { font-size: 10px; color: var(--text-muted); margin-top: 1px; }
        .sb-role-badge { margin: 10px 12px 6px; padding: 6px 11px; border-radius: 7px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 6px; }
        .sb-role-badge.admin, .sb-role-badge.pendaftaran { background: #dbeafe; color: #1e40af; }
        .sb-role-badge.rekam_medis { background: #e0f2fe; color: #0369a1; }
        .sb-role-badge.pasien { background: #d1fae5; color: #065f46; }
        .sb-nav { flex: 1; padding: 4px 10px; }
        .sb-section { margin-bottom: 4px; }
        .sb-section-title { font-size: 9.5px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 1.2px; padding: 10px 8px 4px; }
        .sb-nav-item { display: flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 8px; color: var(--text-mid); text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.15s; margin-bottom: 1px; }
        .sb-nav-item i { width: 15px; text-align: center; font-size: 12px; color: var(--text-muted); flex-shrink: 0; }
        .sb-nav-item:hover { background: #f3f4f6; color: var(--text-dark); }
        .sb-nav-item:hover i { color: var(--text-dark); }
        .sb-nav-item.active { background: #1e293b; color: #fff; font-weight: 600; }
        .sb-nav-item.active i { color: #fff; }
        .sb-footer { padding: 10px; border-top: 1px solid var(--border); }
        .sb-user { display: flex; align-items: center; gap: 8px; padding: 9px; border-radius: 9px; background: #f9fafb; border: 1px solid var(--border); }
        .sb-avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: white; flex-shrink: 0; }
        .sb-avatar.admin, .sb-avatar.pendaftaran { background: linear-gradient(135deg,#1d4ed8,#0891b2); }
        .sb-avatar.rekam_medis { background: linear-gradient(135deg,#0369a1,#06b6d4); }
        .sb-avatar.pasien { background: linear-gradient(135deg,#16a34a,#059669); }
        .sb-user-info .uname { font-size: 12px; font-weight: 600; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }
        .sb-user-info .uemail { font-size: 10px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }

        /* MAIN */
        .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: white; border-bottom: 1px solid var(--border); height: 52px; display: flex; align-items: center; justify-content: space-between; padding: 0 22px; position: sticky; top: 0; z-index: 100; }
        .topbar-title { font-size: 15px; font-weight: 700; color: var(--text-dark); }
        .topbar-right { display: flex; align-items: center; gap: 9px; }
        .topbar-date { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); background: #f3f4f6; padding: 5px 11px; border-radius: 7px; border: 1px solid var(--border); }
        .topbar-date i { font-size: 11px; }
        .btn-keluar { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 7px; font-size: 12px; font-weight: 600; text-decoration: none; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .btn-keluar:hover { background: #dc2626; color: white; border-color: #dc2626; }
        .page-wrap { padding: 22px; flex: 1; }
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
        .page-header-title { font-size: 19px; font-weight: 800; color: var(--text-dark); }
        .page-header-sub { font-size: 13px; color: var(--text-muted); margin-top: 3px; }

        /* CARDS */
        .card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; }
        .card-header { padding: 14px 18px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 700; color: var(--text-dark); }
        .card-body { padding: 18px; }
        .stat-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 16px 18px; display: flex; align-items: center; gap: 13px; }
        .stat-icon { width: 46px; height: 46px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .stat-value { font-size: 24px; font-weight: 800; color: var(--text-dark); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 3px; font-weight: 500; }

        /* TABLE */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { background: #f9fafb; padding: 10px 13px; text-align: left; font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        tbody td { padding: 11px 13px; border-bottom: 1px solid #f3f4f6; color: var(--text-dark); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafafa; }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .badge-success  { background: #d1fae5; color: #065f46; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }
        .badge-info     { background: #e0f2fe; color: #0c4a6e; }
        .badge-secondary{ background: #f3f4f6; color: #6b7280; }
        .badge-primary  { background: #dbeafe; color: #1e40af; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 15px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; font-family: inherit; transition: all 0.15s; white-space: nowrap; }
        .btn-primary   { background: #1d4ed8; color: white; }
        .btn-primary:hover { background: #1e40af; }
        .btn-success   { background: #16a34a; color: white; }
        .btn-success:hover { background: #15803d; }
        .btn-danger    { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-outline   { background: white; color: var(--text-mid); border: 1px solid var(--border); }
        .btn-outline:hover { background: #f9fafb; }
        .btn-info      { background: #0891b2; color: white; }
        .btn-info:hover { background: #0e7490; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-lg { padding: 11px 22px; font-size: 14px; }

        /* FORMS */
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-mid); margin-bottom: 5px; }
        .form-control, .form-select { width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: inherit; color: var(--text-dark); background: white; transition: border-color 0.15s; }
        .form-control:focus, .form-select:focus { outline: none; border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,0.08); }
        .form-control.is-invalid { border-color: var(--danger); }
        .invalid-feedback { color: var(--danger); font-size: 11px; margin-top: 3px; }
        textarea.form-control { resize: vertical; min-height: 80px; }

        /* ALERTS */
        .alert { padding: 11px 14px; border-radius: 9px; font-size: 13px; display: flex; align-items: flex-start; gap: 9px; margin-bottom: 16px; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .alert-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }

        /* GRID */
        .grid   { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }

        /* UTILS */
        .d-flex { display: flex; } .align-center { align-items: center; } .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; } .gap-3 { gap: 12px; }
        .mb-2 { margin-bottom: 8px; } .mb-4 { margin-bottom: 16px; } .mb-6 { margin-bottom: 24px; }
        .text-muted { color: var(--text-muted); } .text-sm { font-size: 12px; } .text-xs { font-size: 11px; }
        .fw-bold { font-weight: 700; } .text-center { text-align: center; } .text-right { text-align: right; }

        .ss-badge { display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:99px;font-size:11px;font-weight:600; }
        .ss-badge.success { background:#d1fae5;color:#065f46; }
        .ss-badge.pending { background:#fef3c7;color:#92400e; }
        .ss-badge.failed  { background:#fee2e2;color:#991b1b; }
        .pagination { display:flex;gap:4px;flex-wrap:wrap; }
        .page-item .page-link { padding:6px 11px;border-radius:7px;font-size:13px;color:var(--text-mid);background:white;border:1px solid var(--border);text-decoration:none; }
        .page-item.active .page-link { background:#1e293b;color:white;border-color:#1e293b; }

        @media (max-width:768px) { .sidebar { transform:translateX(-100%); } .main-wrap { margin-left:0; } .grid-2,.grid-3,.grid-4 { grid-template-columns:1fr; } .page-wrap { padding:14px; } }
    </style>
    @stack('styles')
</head>
<body>
@php $role = auth()->user()->role ?? 'pasien'; @endphp
<aside class="sidebar">
    <div class="sb-logo">
        <div class="sb-logo-icon">🏥</div>
        <div class="sb-logo-text">
            <div class="name">RS Cahya Medika</div>
            <div class="sub">Sistem Pendaftaran RM</div>
        </div>
    </div>
    <div class="sb-role-badge {{ $role }}">
        <i class="fas fa-{{ $role === 'pasien' ? 'user' : ($role === 'rekam_medis' ? 'file-medical' : 'desktop') }}"></i>
        @if($role==='admin') ADMIN (SUPER ADMIN)
        @elseif($role==='pendaftaran') PENDAFTARAN (SUPER ADMIN)
        @elseif($role==='rekam_medis') REKAM MEDIS
        @else PORTAL PASIEN
        @endif
    </div>
    <nav class="sb-nav">
        @if(auth()->user()->isAdmin())
        <div class="sb-section">
            <div class="sb-section-title">Pendaftaran & Loket</div>
            <a href="{{ route('admin.dashboard') }}" class="sb-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i> Dashboard Loket
            </a>
            <a href="{{ route('admin.pendaftaran.index') }}" class="sb-nav-item {{ request()->routeIs('admin.pendaftaran*') ? 'active' : '' }}">
                <i class="fas fa-list-check"></i> Antrean & Registrasi
            </a>
        </div>
        <div class="sb-section">
            <div class="sb-section-title">Master Data Hospital</div>
            <a href="{{ route('admin.pasien.index') }}" class="sb-nav-item {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Master Data Pasien
            </a>
            <a href="{{ route('admin.dokter.index') }}" class="sb-nav-item {{ request()->routeIs('admin.dokter*') ? 'active' : '' }}">
                <i class="fas fa-user-doctor"></i> Dokter Spesialis
            </a>
            <a href="{{ route('admin.poli.index') }}" class="sb-nav-item {{ request()->routeIs('admin.poli*') ? 'active' : '' }}">
                <i class="fas fa-hospital"></i> Poliklinik & Kuota
            </a>
        </div>
        <div class="sb-section">
            <div class="sb-section-title">Pelaporan Loket</div>
            <a href="{{ route('admin.laporan') }}" class="sb-nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Sensus Harian Loket
            </a>
            <a href="{{ route('admin.satusehat.status') }}" class="sb-nav-item {{ request()->routeIs('admin.satusehat*') ? 'active' : '' }}">
                <i class="fas fa-link"></i> SatuSehat API
            </a>
        </div>
        @else
        <div class="sb-section">
            <div class="sb-section-title">Menu Pasien</div>
            <a href="{{ route('pasien.dashboard') }}" class="sb-nav-item {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
                <i class="fas fa-house"></i> Beranda
            </a>
            <a href="{{ route('pasien.pendaftaran.create') }}" class="sb-nav-item {{ request()->routeIs('pasien.pendaftaran.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i> Daftar Berobat Baru
            </a>
            <a href="{{ route('pasien.pendaftaran.index') }}" class="sb-nav-item {{ request()->routeIs('pasien.pendaftaran.index') || request()->routeIs('pasien.pendaftaran.show') ? 'active' : '' }}">
                <i class="fas fa-ticket"></i> Tiket & Antrean Saya
            </a>
            <a href="{{ route('pasien.riwayat') }}" class="sb-nav-item {{ request()->routeIs('pasien.riwayat') ? 'active' : '' }}">
                <i class="fas fa-clock-rotate-left"></i> Riwayat Kunjungan
            </a>
        </div>
        <div class="sb-section">
            <div class="sb-section-title">Profil Pasien</div>
            <a href="{{ route('pasien.profil') }}" class="sb-nav-item {{ request()->routeIs('pasien.profil') ? 'active' : '' }}">
                <i class="fas fa-id-card"></i> Profil & Identitas
            </a>
        </div>
        @endif
    </nav>
    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar {{ $role }}">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div class="sb-user-info">
                <div class="uname">{{ Str::limit(auth()->user()->name, 22) }}</div>
                <div class="uemail">{{ Str::limit(auth()->user()->email, 26) }}</div>
            </div>
        </div>
    </div>
</aside>
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-right">
            <div class="topbar-date">
                <i class="fas fa-calendar-days"></i>
                {{ now()->locale('id')->isoFormat('ddd, D MMM Y') }}
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-keluar">
                    <i class="fas fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </header>
    <main class="page-wrap">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-circle-check" style="margin-top:1px;flex-shrink:0"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-circle-exclamation" style="margin-top:1px;flex-shrink:0"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-triangle-exclamation" style="margin-top:1px;flex-shrink:0"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin:4px 0 0 14px">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
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
