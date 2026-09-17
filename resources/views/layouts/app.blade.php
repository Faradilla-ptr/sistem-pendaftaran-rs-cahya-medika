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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --sidebar-w: 230px;
            --border: #e2e8f0;
            --text-dark: #0f172a;
            --text-mid: #334155;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --shadow-sm: 0 1px 3px rgba(15,23,42,0.05);
            --shadow-md: 0 4px 12px rgba(15,23,42,0.06);
            --radius: 10px;
            --radius-lg: 14px;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: var(--text-dark); display: flex; min-height: 100vh; font-size: 14px; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-w); background: #ffffff; border-right: 1px solid #e2e8f0; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; display: flex; flex-direction: column; overflow-y: auto; box-shadow: 2px 0 10px rgba(15,23,42,0.02); }
        .sb-logo { padding: 18px 16px 14px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; }
        .sb-logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; color: #38bdf8; flex-shrink: 0; box-shadow: 0 4px 10px rgba(15,23,42,0.15); }
        .sb-logo-text .name { font-size: 13.5px; font-weight: 800; color: #0f172a; line-height: 1.2; letter-spacing: -0.2px; }
        .sb-logo-text .sub { font-size: 10px; color: #64748b; margin-top: 2px; font-weight: 500; }
        .sb-role-badge { margin: 12px 14px 6px; padding: 7px 12px; border-radius: 8px; font-size: 10.5px; font-weight: 700; display: flex; align-items: center; gap: 7px; letter-spacing: 0.3px; }
        .sb-role-badge.admin, .sb-role-badge.pendaftaran { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .sb-role-badge.rekam_medis { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
        .sb-role-badge.pasien { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .sb-nav { flex: 1; padding: 8px 12px; }
        .sb-section { margin-bottom: 8px; }
        .sb-section-title { font-size: 9.5px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.2px; padding: 12px 10px 6px; }
        .sb-nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 9px; color: #475569; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s ease; margin-bottom: 2px; }
        .sb-nav-item i { width: 18px; text-align: center; font-size: 13px; color: #64748b; flex-shrink: 0; transition: color 0.2s; }
        .sb-nav-item:hover { background: #f8fafc; color: #0f172a; }
        .sb-nav-item:hover i { color: #0891b2; }
        .sb-nav-item.active { background: linear-gradient(135deg, #0c4a6e, #0891b2); color: #ffffff; font-weight: 700; box-shadow: 0 4px 12px rgba(8,145,178,0.25); }
        .sb-nav-item.active i { color: #ffffff; }

        /* MAIN & TOPBAR DROPDOWN */
        .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; background: #f8fafc; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; height: 60px; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 8px rgba(15,23,42,0.03); }
        .topbar-title { font-size: 16px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-date { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #64748b; background: #f8fafc; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0; font-weight: 600; }
        .topbar-date i { font-size: 11px; color: #0891b2; }
        
        /* USER DROPDOWN WRAPPER */
        .user-dropdown-wrapper { position: relative; }
        .user-dropdown-btn { display: flex; align-items: center; gap: 10px; background: #ffffff; padding: 5px 12px 5px 6px; border-radius: 12px; border: 1.5px solid #cbd5e1; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(15,23,42,0.04); }
        .user-dropdown-btn:hover { border-color: #0891b2; background: #f0f9ff; }
        .sb-avatar { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: white; flex-shrink: 0; }
        .sb-avatar.admin, .sb-avatar.pendaftaran { background: linear-gradient(135deg,#1d4ed8,#0891b2); }
        .sb-avatar.rekam_medis { background: linear-gradient(135deg,#0369a1,#06b6d4); }
        .sb-avatar.pasien { background: linear-gradient(135deg,#16a34a,#059669); }
        .u-info-top { line-height: 1.2; text-align: left; }
        .u-info-top .uname { font-size: 12px; font-weight: 700; color: #0f172a; white-space: nowrap; max-width: 130px; overflow: hidden; text-overflow: ellipsis; }
        .u-info-top .uemail { font-size: 10px; color: #64748b; white-space: nowrap; max-width: 130px; overflow: hidden; text-overflow: ellipsis; }
        .u-arrow { font-size: 11px; color: #94a3b8; transition: transform 0.2s; margin-left: 2px; }

        .user-dropdown-menu {
            position: absolute; right: 0; top: calc(100% + 8px);
            width: 230px; background: #ffffff; border: 1px solid #e2e8f0;
            border-radius: 14px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12);
            padding: 8px; display: none; z-index: 500;
        }
        .user-dropdown-menu.show { display: block; animation: dropdownFade 0.2s ease; }
        @keyframes dropdownFade { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

        .udd-header { padding: 10px 12px; background: #f8fafc; border-radius: 10px; margin-bottom: 6px; }
        .udd-name { font-size: 13px; font-weight: 800; color: #0f172a; }
        .udd-email { font-size: 11px; color: #64748b; word-break: break-all; margin-top: 1px; }
        .udd-divider { height: 1px; background: #f1f5f9; margin: 6px 0; }
        .udd-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #334155; text-decoration: none; font-size: 12.5px; font-weight: 600; width: 100%; border: none; background: none; text-align: left; cursor: pointer; font-family: inherit; transition: all 0.15s; }
        .udd-item i { width: 16px; font-size: 13px; color: #0891b2; }
        .udd-item:hover { background: #f0f9ff; color: #0c4a6e; }
        .udd-item.logout-item { color: #dc2626; }
        .udd-item.logout-item i { color: #dc2626; }
        .udd-item.logout-item:hover { background: #fee2e2; color: #991b1b; }

        .page-wrap { padding: 24px; flex: 1; width: 100%; }

        /* CARDS */
        .card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; }
        .card-header { padding: 14px 18px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 700; color: var(--text-dark); }
        .card-body { padding: 18px; }
        .stat-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm); padding: 16px 18px; display: flex; align-items: center; gap: 13px; }
        .stat-icon { width: 46px; height: 46px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .stat-value { font-size: 24px; font-weight: 800; color: var(--text-dark); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 3px; font-weight: 500; }

        /* TABLE */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { background: #f8fafc; padding: 10px 13px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        tbody td { padding: 11px 13px; border-bottom: 1px solid #f1f5f9; color: var(--text-dark); vertical-align: middle; }
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
        .btn-outline:hover { background: #f8fafc; }
        .btn-info      { background: #0891b2; color: white; }
        .btn-info:hover { background: #0e7490; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-lg { padding: 11px 22px; font-size: 14px; }

        /* PROMINENT ELEVATED FORMS */
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--text-mid); margin-bottom: 5px; }
        .form-control, .form-select {
            width: 100%; padding: 9px 13px;
            border: 1.5px solid #cbd5e1; border-radius: 9px;
            font-size: 13px; font-weight: 500; font-family: inherit;
            color: var(--text-dark); background: #ffffff;
            box-shadow: 0 1px 3px rgba(15,23,42,0.04); transition: all 0.15s;
        }
        .form-control:focus, .form-select:focus { outline: none; border-color: #1d4ed8; box-shadow: 0 0 0 3.5px rgba(29,78,216,0.12); background: #fafcfe; }
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

        @media (max-width:768px) { .sidebar { transform:translateX(-100%); } .main-wrap { margin-left:0; } .grid-2,.grid-3,.grid-4 { grid-template-columns:1fr; } .page-wrap { padding:14px; } }
    </style>
    @stack('styles')
</head>
<body>
@php
    $userRole = auth()->user()->role ?? 'pasien';
    if (request()->is('pendaftaran*') || request()->is('admin*')) {
        $role = 'pendaftaran';
    } elseif (request()->is('rekam-medis*')) {
        $role = 'rekam_medis';
    } elseif (request()->is('pasien*')) {
        $role = 'pasien';
    } else {
        $role = ($userRole === 'pendaftaran' || $userRole === 'admin') ? 'pendaftaran' : ($userRole === 'rekam_medis' ? 'rekam_medis' : 'pasien');
    }
@endphp
<aside class="sidebar">
    <div class="sb-logo" style="padding:16px 20px;display:flex;align-items:center;justify-content:center">
        <img src="{{ asset('logo.png') }}" alt="Logo RS Cahya Medika" style="max-height:48px;width:auto;object-fit:contain">
    </div>
    <div class="sb-role-badge {{ $role }}">
        <i class="fas fa-{{ $role === 'pasien' ? 'user' : ($role === 'rekam_medis' ? 'file-medical' : 'desktop') }}"></i>
        @if($role==='admin') ADMIN (SUPER ADMIN)
        @elseif($role==='pendaftaran') PENDAFTARAN (LOKET)
        @elseif($role==='rekam_medis') REKAM MEDIS
        @else PORTAL PASIEN
        @endif
    </div>
    <nav class="sb-nav">
        @if($role === 'rekam_medis')
        <div class="sb-section">
            <div class="sb-section-title">Rekam Medis</div>
            <a href="{{ route('rekam_medis.dashboard') }}" class="sb-nav-item {{ request()->routeIs('rekam_medis.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i> Dashboard Rekam Medis
            </a>
            <a href="{{ route('rekam_medis.pendaftaran.index') }}" class="sb-nav-item {{ request()->routeIs('rekam_medis.pendaftaran*') ? 'active' : '' }}">
                <i class="fas fa-file-medical"></i> Data Berobat & Antrean
            </a>
            <a href="{{ route('rekam_medis.pasien.index') }}" class="sb-nav-item {{ request()->routeIs('rekam_medis.pasien*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Data Pasien & No. RM
            </a>
        </div>
        <div class="sb-section">
            <div class="sb-section-title">Integrasi & Pelaporan</div>
            <a href="{{ route('rekam_medis.satusehat.status') }}" class="sb-nav-item {{ request()->routeIs('rekam_medis.satusehat*') ? 'active' : '' }}">
                <i class="fas fa-link"></i> SatuSehat API
            </a>
            <a href="{{ route('rekam_medis.laporan') }}" class="sb-nav-item {{ request()->routeIs('rekam_medis.laporan*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Laporan Rekam Medis
            </a>
        </div>
        @elseif($role === 'pendaftaran' || $role === 'admin')
        <div class="sb-section">
            <div class="sb-section-title">Pendaftaran & Loket</div>
            <a href="{{ route('pendaftaran.dashboard') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i> Dashboard Loket
            </a>
            <a href="{{ route('pendaftaran.pendaftaran.index') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.pendaftaran*') || request()->routeIs('admin.pendaftaran*') ? 'active' : '' }}">
                <i class="fas fa-list-check"></i> Antrean & Registrasi
            </a>
        </div>
        <div class="sb-section">
            <div class="sb-section-title">Master Data Hospital</div>
            <a href="{{ route('pendaftaran.pasien.index') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.pasien*') || request()->routeIs('admin.pasien*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Master Data Pasien
            </a>
            <a href="{{ route('pendaftaran.dokter.index') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.dokter*') || request()->routeIs('admin.dokter*') ? 'active' : '' }}">
                <i class="fas fa-user-doctor"></i> Dokter Spesialis
            </a>
            <a href="{{ route('pendaftaran.poli.index') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.poli*') || request()->routeIs('admin.poli*') ? 'active' : '' }}">
                <i class="fas fa-hospital"></i> Poliklinik & Kuota
            </a>
        </div>
        <div class="sb-section">
            <div class="sb-section-title">Pelaporan Loket</div>
            <a href="{{ route('pendaftaran.laporan') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.laporan*') || request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Sensus Harian Loket
            </a>
            <a href="{{ route('pendaftaran.satusehat.status') }}" class="sb-nav-item {{ request()->routeIs('pendaftaran.satusehat*') || request()->routeIs('admin.satusehat*') ? 'active' : '' }}">
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
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-right">
            @php
                $notifCount = 0;
                $notifTitle = '';
                $notifMsg = '';
                if ($role === 'pendaftaran') {
                    $notifCount = \App\Models\Pendaftaran::where('status', 'terdaftar_online')->whereDate('tanggal_kunjungan', '>=', today())->count();
                    $notifTitle = 'Pendaftaran Online Baru';
                    $notifMsg = "$notifCount pasien mendaftar online & menunggu check-in di loket.";
                } elseif ($role === 'rekam_medis') {
                    $notifCount = \App\Models\Pendaftaran::whereIn('status', ['proses_rekam_medis', 'pemeriksaan_selesai'])->count();
                    $notifTitle = 'Berkas RM Siap Finalisasi';
                    $notifMsg = "$notifCount berkas pendaftaran siap diverifikasi ke SatuSehat.";
                }
            @endphp

            @if($notifCount > 0)
            <div class="notif-wrapper" style="position:relative">
                <button type="button" class="btn" style="background:#f1f5f9;border:1px solid #e2e8f0;padding:8px 12px;border-radius:10px;position:relative;cursor:pointer" onclick="Swal.fire('{{ $notifTitle }}', '{{ $notifMsg }}', 'info')">
                    <i class="fas fa-bell" style="color:#0284c7;font-size:15px"></i>
                    <span style="position:absolute;top:-5px;right:-5px;background:#dc2626;color:white;font-size:10px;font-weight:800;border-radius:99px;padding:2px 6px;line-height:1">{{ $notifCount }}</span>
                </button>
            </div>
            @endif

            <div class="topbar-date">
                <i class="fas fa-calendar-days"></i>
                {{ now()->locale('id')->isoFormat('ddd, D MMM Y') }}
            </div>
            
            {{-- USER DROPDOWN MENU AT TOPBAR --}}
            <div class="user-dropdown-wrapper">
                <button type="button" class="user-dropdown-btn" onclick="toggleUserDropdown(event)">
                    <div class="sb-avatar {{ $role }}">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div class="u-info-top">
                        <div class="uname">{{ auth()->user()->name }}</div>
                        <div class="uemail">{{ auth()->user()->email }}</div>
                    </div>
                    <i class="fas fa-chevron-down u-arrow" id="uDropdownArrow"></i>
                </button>
                
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    <div class="udd-header">
                        <div class="udd-name">{{ auth()->user()->name }}</div>
                        <div class="udd-email">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="udd-divider"></div>
                    @if(!auth()->user()->isAdmin())
                    <a href="{{ route('pasien.profil') }}" class="udd-item">
                        <i class="fas fa-user-gear"></i> Profil & Identitas
                    </a>
                    <div class="udd-divider"></div>
                    @endif
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none">
                        @csrf
                    </form>
                    <button type="button" class="udd-item logout-item" onclick="confirmLogout(event)">
                        <i class="fas fa-right-from-bracket"></i> Keluar / Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="page-wrap">
        @if(session('info'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle" style="margin-top:1px;flex-shrink:0"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif
        @yield('content')
    </main>
</div>

<script>
function toggleUserDropdown(e) {
    e.stopPropagation();
    const menu = document.getElementById('userDropdownMenu');
    const arrow = document.getElementById('uDropdownArrow');
    const isShowing = menu.classList.contains('show');
    
    if (isShowing) {
        menu.classList.remove('show');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    } else {
        menu.classList.add('show');
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    }
}

document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.user-dropdown-wrapper');
    const menu = document.getElementById('userDropdownMenu');
    const arrow = document.getElementById('uDropdownArrow');
    if (wrapper && !wrapper.contains(e.target) && menu && menu.classList.contains('show')) {
        menu.classList.remove('show');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
});

function confirmLogout(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: "Apakah Anda yakin ingin keluar dari akun ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmColor: '#dc2626',
        cancelColor: '#64748b',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutForm').submit();
        }
    });
}
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    confirmColor: '#1d4ed8',
    timer: 3000
});
@endif
@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: "{{ session('error') }}",
    confirmColor: '#dc2626'
});
@endif
</script>
@stack('scripts')
</body>
</html>
