<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RS Cahya Medika Bondowoso — Pendaftaran Online Non-BPJS</title>
<meta name="description" content="RS Cahya Medika Bondowoso — Rumah sakit swasta modern dengan layanan dokter spesialis. Daftar berobat online mudah, cepat, dan terhubung SatuSehat Kemenkes RI.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ─── RESET & BASE ─────────────────────────────────────── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
    --primary:       #0284c7;
    --primary-dark:  #0369a1;
    --navy:          #0f172a;
    --teal:          #0d9488;
    --emerald:       #059669;
    --slate:         #334155;
    --muted:         #64748b;
    --light-bg:      #f8fafc;
    --white:         #ffffff;
    --border:        #e2e8f0;
    --shadow-card:   0 10px 30px -5px rgba(15, 23, 42, 0.06), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
    --shadow-hover:  0 20px 40px -10px rgba(2, 132, 199, 0.18), 0 8px 16px -4px rgba(15, 23, 42, 0.06);
}
html { scroll-behavior: smooth; }
body { font-family:'Plus Jakarta Sans',sans-serif; color:var(--navy); background:#f8fafc; line-height:1.6; font-size:14px; overflow-x:hidden; width:100%; }
a { text-decoration:none; color:inherit; }

/* ─── FULL-WIDTH NAVBAR ──────────────────────────────────── */
#navbar {
    position:fixed; top:0; left:0; right:0; width:100%; z-index:1000;
    height:72px; padding:0 48px;
    background:rgba(255, 255, 255, 0.95);
    backdrop-filter:blur(16px);
    border-bottom:1px solid rgba(226, 232, 240, 0.9);
    display:flex; align-items:center; justify-content:space-between;
    box-shadow:0 4px 20px rgba(15, 23, 42, 0.04);
    transition:all 0.3s ease;
}
.nav-brand { display:flex; align-items:center; gap:12px; }
.nav-logo-box {
    width:42px; height:42px;
    background:linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
    border-radius:12px; display:flex; align-items:center; justify-content:center;
    color:white; font-size:19px; flex-shrink:0;
    box-shadow:0 4px 14px rgba(2, 132, 199, 0.3);
}
.nav-brand-text .n { font-size:16px; font-weight:800; color:var(--navy); line-height:1.2; }
.nav-brand-text .s { font-size:11px; color:var(--muted); font-weight:600; }
.nav-menu { display:flex; align-items:center; gap:6px; }
.nav-menu a {
    color:var(--slate); font-size:13.5px; font-weight:600;
    padding:8px 18px; border-radius:99px; transition:all 0.2s;
}
.nav-menu a:hover { color:var(--primary); background:#eff6ff; }
.nav-actions { display:flex; align-items:center; gap:10px; }
.btn-nav-ghost {
    padding:10px 22px; border-radius:99px; font-size:13px; font-weight:700;
    color:var(--slate); border:1px solid var(--border); background:var(--white);
    transition:all 0.2s;
}
.btn-nav-ghost:hover { background:var(--light-bg); color:var(--navy); border-color:#cbd5e1; }
.btn-nav-solid {
    padding:10px 24px; border-radius:99px; font-size:13px; font-weight:700;
    background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color:var(--white); transition:all 0.2s;
    box-shadow:0 4px 14px rgba(2, 132, 199, 0.35);
}
.btn-nav-solid:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(2, 132, 199, 0.45); }
.nav-toggle { display:none; color:var(--navy); font-size:22px; background:none; border:none; cursor:pointer; }

/* ─── FULL-WIDTH HERO SECTION ────────────────────────────── */
.hero-section {
    padding:124px 48px 72px; width:100%;
    background:radial-gradient(circle at 85% 15%, rgba(56, 189, 248, 0.09) 0%, transparent 45%),
               radial-gradient(circle at 15% 85%, rgba(13, 148, 136, 0.07) 0%, transparent 45%),
               #ffffff;
    border-bottom:1px solid var(--border);
}
.hero-container {
    width:100%; max-width:1440px; margin:0 auto;
    display:grid; grid-template-columns:1.15fr 0.85fr; gap:48px; align-items:center;
}
.hero-badge {
    display:inline-flex; align-items:center; gap:8px;
    background:#e0f2fe; border:1px solid #bae6fd;
    padding:6px 16px; border-radius:99px;
    font-size:12px; font-weight:700; color:#0369a1; margin-bottom:22px;
}
.hero-title {
    font-size:52px; font-weight:800; color:var(--navy);
    line-height:1.15; letter-spacing:-1px; margin-bottom:20px;
}
.gradient-text {
    background:linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.hero-desc {
    font-size:16px; color:var(--muted); line-height:1.7;
    margin-bottom:34px; max-width:560px; font-weight:400;
}
.hero-buttons { display:flex; gap:14px; flex-wrap:wrap; margin-bottom:44px; }
.btn-hero-primary {
    display:inline-flex; align-items:center; gap:10px;
    padding:16px 32px; border-radius:14px;
    background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color:var(--white); font-size:15px; font-weight:700;
    box-shadow:0 8px 24px rgba(2, 132, 199, 0.35); transition:all 0.25s;
}
.btn-hero-primary:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(2, 132, 199, 0.45); }
.btn-hero-secondary {
    display:inline-flex; align-items:center; gap:10px;
    padding:16px 28px; border-radius:14px; background:var(--white);
    color:var(--slate); font-size:15px; font-weight:700;
    border:1px solid var(--border); box-shadow:0 4px 12px rgba(15, 23, 42, 0.04);
    transition:all 0.2s;
}
.btn-hero-secondary:hover { background:#f8fafc; color:var(--navy); border-color:#cbd5e1; }

.hero-stats-row {
    display:grid; grid-template-columns:repeat(4,1fr); gap:16px;
    padding-top:28px; border-top:1px solid var(--border);
}
.hs-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:14px; padding:14px 16px;
    box-shadow:0 2px 8px rgba(15, 23, 42, 0.03);
}
.hs-val { font-size:24px; font-weight:800; color:var(--navy); line-height:1; }
.hs-lbl { font-size:11px; color:var(--muted); margin-top:4px; font-weight:600; }

/* ─── HERO RIGHT: MODERN DIGITAL PORTAL PREVIEW ─────────── */
.portal-preview { display:flex; flex-direction:column; gap:16px; }

.live-queue-card {
    background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius:20px; padding:24px; color:white;
    box-shadow:0 20px 40px -10px rgba(15, 23, 42, 0.25);
    position:relative; overflow:hidden;
}
.live-queue-card::before {
    content:''; position:absolute; top:-60px; right:-60px; width:180px; height:180px;
    background:radial-gradient(circle, rgba(2, 132, 199, 0.3) 0%, transparent 70%);
    border-radius:50%; pointer-events:none;
}
.lq-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
.lq-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(16, 185, 129, 0.2); border:1px solid rgba(16, 185, 129, 0.4); color:#34d399; font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; }
.lq-dot { width:7px; height:7px; border-radius:50%; background:#10b981; animation:pulse 1.8s infinite; }
@keyframes pulse { 0%{transform:scale(0.9);opacity:0.8} 50%{transform:scale(1.25);opacity:1} 100%{transform:scale(0.9);opacity:0.8} }
.lq-rm { font-size:11px; color:#94a3b8; font-family:monospace; }
.lq-main { display:flex; align-items:center; justify-content:space-between; gap:20px; }
.lq-number { font-size:42px; font-weight:900; line-height:1; letter-spacing:-1px; color:#38bdf8; }
.lq-poli { font-size:14px; font-weight:700; color:white; margin-top:4px; }
.lq-sub { font-size:11px; color:#94a3b8; }
.lq-btn { background:rgba(255,255,255,0.1); hover:background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.2); color:white; padding:10px 18px; border-radius:10px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:6px; transition:all 0.2s; white-space:nowrap; }

.quick-feature-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:14px; }
.qf-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:16px; padding:18px 16px; box-shadow:var(--shadow-card);
    display:flex; align-items:center; gap:14px; transition:all 0.25s ease;
}
.qf-card:hover { border-color:#7dd3fc; transform:translateY(-2px); box-shadow:var(--shadow-hover); }
.qf-icon {
    width:42px; height:42px; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;
}
.qf-icon.i-blue { background:#e0f2fe; color:#0284c7; }
.qf-icon.i-green { background:#dcfce7; color:#16a34a; }
.qf-icon.i-purple { background:#f3e8ff; color:#9333ea; }
.qf-icon.i-amber { background:#fef3c7; color:#d97706; }
.qf-title { font-size:13px; font-weight:800; color:var(--navy); line-height:1.2; }
.qf-sub { font-size:11px; color:var(--muted); margin-top:2px; }

/* ─── FULL-WIDTH SECTIONS ────────────────────────────────── */
.full-sec-wrap { width:100%; padding:84px 48px; border-bottom:1px solid var(--border); }
.full-sec-wrap.bg-light { background:#ffffff; }
.sec-inner-wide { width:100%; max-width:1440px; margin:0 auto; }
.sec-head { margin-bottom:48px; }
.sec-tag {
    display:inline-flex; align-items:center; gap:6px;
    padding:5px 14px; border-radius:99px;
    font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.8px;
    background:#e0f2fe; color:#0369a1; margin-bottom:12px; border:1px solid #bae6fd;
}
.sec-title { font-size:38px; font-weight:800; color:var(--navy); letter-spacing:-0.8px; line-height:1.2; }
.sec-sub { font-size:15px; color:var(--muted); margin-top:10px; max-width:620px; }

/* ─── KEUNGGULAN ─────────────────────────────────────────── */
.feat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.feat-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:18px; padding:32px 26px; box-shadow:var(--shadow-card);
    transition:all 0.3s ease; position:relative; overflow:hidden;
}
.feat-card:hover {
    transform:translateY(-5px); box-shadow:var(--shadow-hover);
    border-color:#7dd3fc;
}
.fc-icon-wrap {
    width:52px; height:52px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; margin-bottom:20px; flex-shrink:0;
}
.fc-icon-wrap.i1 { background:#e0f2fe; color:#0284c7; }
.fc-icon-wrap.i2 { background:#ccfbf1; color:#0d9488; }
.fc-icon-wrap.i3 { background:#dcfce7; color:#16a34a; }
.fc-icon-wrap.i4 { background:#fef3c7; color:#d97706; }
.fc-icon-wrap.i5 { background:#f3e8ff; color:#9333ea; }
.fc-icon-wrap.i6 { background:#e0e7ff; color:#4f46e5; }
.fc-title { font-size:17px; font-weight:800; color:var(--navy); margin-bottom:10px; }
.fc-body  { font-size:13.5px; color:var(--muted); line-height:1.65; }

/* ─── POLI SECTION ───────────────────────────────────────── */
.poli-grid-section { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.pc-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:18px; padding:26px 22px; text-align:center;
    transition:all 0.3s ease; box-shadow:var(--shadow-card);
}
.pc-card:hover {
    border-color:var(--primary); transform:translateY(-4px);
    box-shadow:var(--shadow-hover);
}
.pc-icon-box {
    width:56px; height:56px; border-radius:16px;
    display:flex; align-items:center; justify-content:center;
    font-size:24px; margin:0 auto 16px;
}
.pc-name { font-size:16px; font-weight:800; color:var(--navy); margin-bottom:4px; }
.pc-detail { font-size:12.5px; color:var(--muted); }
.pc-tag {
    display:inline-block; margin-top:12px; padding:4px 12px;
    border-radius:6px; font-size:11px; font-weight:700;
    background:#f0fdf4; color:#166534; border:1px solid #dcfce7;
}

/* ─── CARA DAFTAR ────────────────────────────────────────── */
.steps-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; }
.step-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:18px; padding:30px 24px; box-shadow:var(--shadow-card);
    transition:all 0.3s ease;
}
.step-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-hover); border-color:#7dd3fc; }
.step-num-badge {
    width:40px; height:40px; border-radius:12px;
    background:linear-gradient(135deg, #0284c7, #0d9488);
    color:var(--white); font-size:17px; font-weight:800;
    display:flex; align-items:center; justify-content:center; margin-bottom:20px;
    box-shadow:0 4px 14px rgba(2, 132, 199, 0.3);
}
.step-title { font-size:16px; font-weight:800; color:var(--navy); margin-bottom:8px; }
.step-desc { font-size:13px; color:var(--muted); line-height:1.6; }

/* ─── DOKTER ─────────────────────────────────────────────── */
.docs-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.doc-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:18px; padding:26px; box-shadow:var(--shadow-card);
    transition:all 0.3s ease;
}
.doc-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-hover); border-color:#7dd3fc; }
.doc-header { display:flex; align-items:center; gap:16px; margin-bottom:16px; }
.doc-avatar {
    width:56px; height:56px; border-radius:50%;
    background:#e0f2fe; color:#0284c7; border:2px solid #bae6fd;
    display:flex; align-items:center; justify-content:center;
    font-size:24px; flex-shrink:0;
}
.doc-name { font-size:16px; font-weight:800; color:var(--navy); }
.doc-spesialis { font-size:13px; color:var(--primary); font-weight:700; margin-top:2px; }
.doc-body { border-top:1px solid #f1f5f9; padding-top:16px; font-size:13px; color:var(--muted); }
.doc-str { font-family:monospace; font-size:11.5px; margin-top:6px; color:#64748b; }

/* ─── TARIF ──────────────────────────────────────────────── */
.tarif-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.tarif-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:20px; padding:36px 28px; box-shadow:var(--shadow-card);
    transition:all 0.3s ease; position:relative;
}
.tarif-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-hover); }
.tarif-card.featured {
    border:2px solid var(--primary);
    box-shadow:0 18px 40px -5px rgba(2, 132, 199, 0.2);
}
.tarif-featured-badge {
    position:absolute; top:-13px; right:24px;
    background:linear-gradient(135deg, #0284c7, #0d9488); color:white;
    font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.8px;
    padding:4px 14px; border-radius:99px; box-shadow:0 4px 12px rgba(2, 132, 199, 0.3);
}
.tarif-title { font-size:18px; font-weight:800; color:var(--navy); margin-bottom:8px; }
.tarif-price { font-size:32px; font-weight:800; color:var(--navy); margin-bottom:14px; letter-spacing:-0.5px; }
.tarif-price span { font-size:13px; font-weight:500; color:var(--muted); }
.tarif-list { list-style:none; margin-top:18px; display:flex; flex-direction:column; gap:10px; font-size:13px; color:var(--slate); }
.tarif-list li { display:flex; align-items:center; gap:10px; }
.tarif-list li i { color:var(--emerald); font-size:12px; }

/* ─── FOOTER ─────────────────────────────────────────────── */
footer {
    background:var(--navy); color:#94a3b8;
    padding:64px 48px 32px; font-size:13px; width:100%;
}
.footer-container { width:100%; max-width:1440px; margin:0 auto; }
.footer-top { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:48px; padding-bottom:48px; border-bottom:1px solid #1e293b; }
.ft-brand-title { font-size:18px; font-weight:800; color:var(--white); margin-bottom:10px; display:flex; align-items:center; gap:10px; }
.ft-brand-desc { font-size:13px; color:#94a3b8; line-height:1.65; max-width:360px; margin-bottom:18px; }
.ft-col-title { font-size:12px; font-weight:800; color:var(--white); text-transform:uppercase; letter-spacing:1px; margin-bottom:16px; }
.ft-links { display:flex; flex-direction:column; gap:10px; }
.ft-links a { color:#94a3b8; transition:color 0.2s; }
.ft-links a:hover { color:var(--white); }
.footer-bottom { padding-top:28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; font-size:12px; }

/* ─── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width:1200px) {
    #navbar, .hero-section, .full-sec-wrap, footer { padding-left:24px; padding-right:24px; }
    .hero-container { grid-template-columns:1fr; gap:40px; }
    .hero-title { font-size:42px; }
    .feat-grid, .docs-grid, .tarif-grid { grid-template-columns:repeat(2,1fr); }
    .poli-grid-section, .steps-grid { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:640px) {
    .hero-title { font-size:32px; }
    .feat-grid, .docs-grid, .tarif-grid, .poli-grid-section, .steps-grid { grid-template-columns:1fr; }
    .footer-top { grid-template-columns:1fr; }
    .nav-menu, .nav-actions { display:none; }
    .nav-toggle { display:block; }
    .hero-stats-row { grid-template-columns:repeat(2,1fr); }
}
</style>
</head>
<body>

<!-- FULL-WIDTH NAVBAR -->
<nav id="navbar">
    <div class="nav-brand">
        <div class="nav-logo-box">
            <i class="fas fa-hospital"></i>
        </div>
        <div class="nav-brand-text">
            <div class="n">RS Cahya Medika</div>
            <div class="s">Bondowoso &middot; Rumah Sakit Swasta</div>
        </div>
    </div>
    <div class="nav-menu">
        <a href="#layanan">Layanan</a>
        <a href="#cara-daftar">Cara Daftar</a>
        <a href="#dokter">Dokter</a>
        <a href="#tarif">Tarif</a>
        <a href="#kontak">Kontak</a>
    </div>
    <div class="nav-actions">
        <a href="{{ route('pasien.login') }}" class="btn-nav-ghost">Masuk</a>
        <a href="{{ route('register') }}" class="btn-nav-solid">Daftar Sekarang</a>
    </div>
</nav>

<!-- FULL-WIDTH HERO SECTION -->
<section class="hero-section" id="home">
    <div class="hero-container">
        <div>
            <div class="hero-badge">
                <i class="fas fa-shield-halved"></i>
                Terintegrasi SatuSehat Kemenkes RI
            </div>
            <h1 class="hero-title">
                Solusi Kesehatan Digital <br><span class="gradient-text">Terpadu &amp; Modern</span> di Bondowoso
            </h1>
            <p class="hero-desc">
                Pendaftaran berobat online serba cepat dan praktis. Pilih dokter spesialis penanggung jawab Anda, tentukan jadwal kunjungan, dan pantau antrean loket secara real-time.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    <i class="fas fa-calendar-plus"></i> Daftar Berobat Online
                </a>
                <a href="{{ route('pasien.login') }}" class="btn-hero-secondary">
                    <i class="fas fa-user-check"></i> Masuk Portal Pasien
                </a>
            </div>

            <div class="hero-stats-row">
                <div class="hs-card">
                    <div class="hs-val">8+</div>
                    <div class="hs-lbl">Poli Layanan</div>
                </div>
                <div class="hs-card">
                    <div class="hs-val">6+</div>
                    <div class="hs-lbl">Dokter Spesialis</div>
                </div>
                <div class="hs-card">
                    <div class="hs-val">24/7</div>
                    <div class="hs-lbl">IGD Siaga</div>
                </div>
                <div class="hs-card">
                    <div class="hs-val">100%</div>
                    <div class="hs-lbl">Rekam Medis Cloud</div>
                </div>
            </div>
        </div>

        {{-- HERO RIGHT: DIGITAL PORTAL DASHBOARD MOCKUP --}}
        <div class="portal-preview">
            <div class="live-queue-card">
                <div class="lq-top">
                    <div class="lq-badge">
                        <span class="lq-dot"></span> Live Antrean Loket Siaga
                    </div>
                    <div class="lq-rm">NO. RM SYNC: ONLINE</div>
                </div>
                <div class="lq-main">
                    <div>
                        <div class="lq-number">#A-018</div>
                        <div class="lq-poli">Poli Umum &amp; Spesialis</div>
                        <div class="lq-sub">Estimasi Tunggu: 5–10 Menit</div>
                    </div>
                    <a href="{{ route('pasien.login') }}" class="lq-btn">
                        <i class="fas fa-ticket"></i> Cek Tiket
                    </a>
                </div>
            </div>

            <div class="quick-feature-grid">
                <div class="qf-card">
                    <div class="qf-icon i-blue"><i class="fas fa-bolt"></i></div>
                    <div>
                        <div class="qf-title">Registrasi Cepat</div>
                        <div class="qf-sub">Hanya butuh NIK &amp; Nama</div>
                    </div>
                </div>
                <div class="qf-card">
                    <div class="qf-icon i-green"><i class="fas fa-shield-heart"></i></div>
                    <div>
                        <div class="qf-title">SatuSehat Sync</div>
                        <div class="qf-sub">Otomatis terhubung Kemenkes</div>
                    </div>
                </div>
                <div class="qf-card">
                    <div class="qf-icon i-purple"><i class="fas fa-clock-rotate-left"></i></div>
                    <div>
                        <div class="qf-title">Riwayat Medis</div>
                        <div class="qf-sub">Tersimpan aman di cloud</div>
                    </div>
                </div>
                <div class="qf-card">
                    <div class="qf-icon i-amber"><i class="fas fa-headset"></i></div>
                    <div>
                        <div class="qf-title">Customer Care</div>
                        <div class="qf-sub">Layanan siap bantu 24/7</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KEUNGGULAN -->
<div class="full-sec-wrap" id="keunggulan">
    <div class="sec-inner-wide">
        <div class="sec-head">
            <span class="sec-tag"><i class="fas fa-star" style="margin-right:4px"></i> Keunggulan Fasilitas</span>
            <h2 class="sec-title">Kenapa Memilih RS Cahya Medika?</h2>
            <p class="sec-sub">Pelayanan kesehatan modern yang mengedepankan keamanan data, efisiensi waktu, dan kenyamanan pasien.</p>
        </div>
        <div class="feat-grid">
            <div class="feat-card">
                <div class="fc-icon-wrap i1"><i class="fas fa-mobile-screen-button"></i></div>
                <div class="fc-title">Pendaftaran Mandiri 24/7</div>
                <div class="fc-body">Akses sistem pendaftaran online tanpa terikat jam kerja loket. Tentukan poli dan dokter dari ponsel Anda.</div>
            </div>
            <div class="feat-card">
                <div class="fc-icon-wrap i2"><i class="fas fa-link"></i></div>
                <div class="fc-title">Terhubung SatuSehat RI</div>
                <div class="fc-body">Sistem rekam medis langsung tersinkronisasi dengan ekosistem kesehatan nasional Kemenkes RI.</div>
            </div>
            <div class="feat-card">
                <div class="fc-icon-wrap i3"><i class="fas fa-user-doctor"></i></div>
                <div class="fc-title">Dokter Spesialis Terbaik</div>
                <div class="fc-body">Tim dokter spesialis berizin resmi (STR aktif) yang berdedikasi tinggi untuk penyembuhan pasien.</div>
            </div>
            <div class="feat-card">
                <div class="fc-icon-wrap i4"><i class="fas fa-file-medical"></i></div>
                <div class="fc-title">Rekam Medis Elektronik (RME)</div>
                <div class="fc-body">Catatan diagnosa, resep obat, dan riwayat kesehatan tersimpan rapi dan mudah diakses kembali.</div>
            </div>
            <div class="feat-card">
                <div class="fc-icon-wrap i5"><i class="fas fa-clock-rotate-left"></i></div>
                <div class="fc-title">Monitor Antrean Real-time</div>
                <div class="fc-body">Cek nomor antrean aktif langsung dari rumah, sehingga waktu tunggu di area rumah sakit jauh lebih singkat.</div>
            </div>
            <div class="feat-card">
                <div class="fc-icon-wrap i6"><i class="fas fa-shield-halved"></i></div>
                <div class="fc-title">Perlindungan Data Medis</div>
                <div class="fc-body">Keamanan data pasien terjamin sesuai dengan regulasi perlindungan data pribadi medis nasional.</div>
            </div>
        </div>
    </div>
</div>

<!-- POLI SECTION -->
<div class="full-sec-wrap bg-light" id="layanan">
    <div class="sec-inner-wide">
        <div class="sec-head">
            <span class="sec-tag"><i class="fas fa-hospital" style="margin-right:4px"></i> Layanan Poliklinik</span>
            <h2 class="sec-title">Poli &amp; Klinik Spesialis Aktif</h2>
            <p class="sec-sub">RS Cahya Medika Bondowoso melayani pendaftaran untuk 8 poliklinik spesialis utama.</p>
        </div>
        <div class="poli-grid-section">
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#e0f2fe;color:#0284c7"><i class="fas fa-stethoscope"></i></div>
                <div class="pc-name">Poli Umum</div>
                <div class="pc-detail">Lantai 1 &middot; 07:30 – 14:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#fce7f3;color:#db2777"><i class="fas fa-baby"></i></div>
                <div class="pc-name">Poli Anak</div>
                <div class="pc-detail">Lantai 1 &middot; 07:30 – 14:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#fef3c7;color:#d97706"><i class="fas fa-heart-pulse"></i></div>
                <div class="pc-name">Penyakit Dalam</div>
                <div class="pc-detail">Lantai 2 &middot; 08:00 – 14:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#dcfce7;color:#16a34a"><i class="fas fa-person-breastfeeding"></i></div>
                <div class="pc-name">Kebidanan &amp; Kandungan</div>
                <div class="pc-detail">Lantai 2 &middot; 08:00 – 14:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#e0e7ff;color:#4f46e5"><i class="fas fa-user-nurse"></i></div>
                <div class="pc-name">Poli Bedah</div>
                <div class="pc-detail">Lantai 2 &middot; 08:00 – 13:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#ffe4e6;color:#e11d48"><i class="fas fa-heartbeat"></i></div>
                <div class="pc-name">Poli Jantung</div>
                <div class="pc-detail">Lantai 3 &middot; 08:00 – 13:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#f3e8ff;color:#9333ea"><i class="fas fa-brain"></i></div>
                <div class="pc-name">Poli Saraf</div>
                <div class="pc-detail">Lantai 3 &middot; 08:00 – 13:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
            <div class="pc-card">
                <div class="pc-icon-box" style="background:#ccfbf1;color:#0d9488"><i class="fas fa-eye"></i></div>
                <div class="pc-name">Poli Mata</div>
                <div class="pc-detail">Lantai 1 &middot; 07:30 – 14:00</div>
                <span class="pc-tag">Senin – Jumat</span>
            </div>
        </div>
    </div>
</div>

<!-- CARA DAFTAR -->
<div class="full-sec-wrap" id="cara-daftar">
    <div class="sec-inner-wide">
        <div class="sec-head">
            <span class="sec-tag"><i class="fas fa-list-check" style="margin-right:4px"></i> Alur Pendaftaran</span>
            <h2 class="sec-title">4 Langkah Mudah Berobat Online</h2>
            <p class="sec-sub">Alur pendaftaran elektronik yang ringkas untuk mendapatkan nomor antrean dokter.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num-badge">1</div>
                <div class="step-title">Registrasi Akun</div>
                <div class="step-desc">Buat akun portal pasien dengan mengisi NIK KTP dan nama lengkap.</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">2</div>
                <div class="step-title">Pilih Poliklinik</div>
                <div class="step-desc">Pilih poliklinik dan dokter spesialis penanggung jawab perawatan Anda.</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">3</div>
                <div class="step-title">Jadwal &amp; Keluhan</div>
                <div class="step-desc">Tentukan tanggal kunjungan dan isi catatan keluhan singkat.</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">4</div>
                <div class="step-title">Dapatkan Tiket RM</div>
                <div class="step-desc">Nomor antrean resmi terbit otomatis dan siap ditunjukkan saat tiba di loket.</div>
            </div>
        </div>
    </div>
</div>

<!-- DOKTER -->
<div class="full-sec-wrap bg-light" id="dokter">
    <div class="sec-inner-wide">
        <div class="sec-head">
            <span class="sec-tag"><i class="fas fa-user-doctor" style="margin-right:4px"></i> Dokter Spesialis</span>
            <h2 class="sec-title">Dokter Penanggung Jawab Pasien</h2>
            <p class="sec-sub">Tenaga spesialis berpengalaman yang bertugas secara aktif di RS Cahya Medika.</p>
        </div>
        <div class="docs-grid">
            <div class="doc-card">
                <div class="doc-header">
                    <div class="doc-avatar"><i class="fas fa-user-doctor"></i></div>
                    <div>
                        <div class="doc-name">dr. Ahmad Fauzi, Sp.PD</div>
                        <div class="doc-spesialis">Spesialis Penyakit Dalam</div>
                    </div>
                </div>
                <div class="doc-body">
                    <div><i class="fas fa-calendar-days" style="margin-right:6px;color:var(--primary)"></i>Senin – Jumat (08:00 – 14:00)</div>
                    <div class="doc-str">STR: 3501-2-1-2019-000456</div>
                </div>
            </div>
            <div class="doc-card">
                <div class="doc-header">
                    <div class="doc-avatar"><i class="fas fa-user-doctor"></i></div>
                    <div>
                        <div class="doc-name">dr. Siti Rahma Dewi, Sp.A</div>
                        <div class="doc-spesialis">Spesialis Kesehatan Anak</div>
                    </div>
                </div>
                <div class="doc-body">
                    <div><i class="fas fa-calendar-days" style="margin-right:6px;color:var(--primary)"></i>Senin – Jumat (07:30 – 14:00)</div>
                    <div class="doc-str">STR: 3501-2-3-2020-000891</div>
                </div>
            </div>
            <div class="doc-card">
                <div class="doc-header">
                    <div class="doc-avatar"><i class="fas fa-user-doctor"></i></div>
                    <div>
                        <div class="doc-name">dr. Budi Santoso, Sp.OG</div>
                        <div class="doc-spesialis">Spesialis Kebidanan &amp; Kandungan</div>
                    </div>
                </div>
                <div class="doc-body">
                    <div><i class="fas fa-calendar-days" style="margin-right:6px;color:var(--primary)"></i>Senin – Jumat (08:00 – 14:00)</div>
                    <div class="doc-str">STR: 3501-2-5-2018-001234</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TARIF -->
<div class="full-sec-wrap" id="tarif">
    <div class="sec-inner-wide">
        <div class="sec-head">
            <span class="sec-tag"><i class="fas fa-receipt" style="margin-right:4px"></i> Biaya Pelayanan</span>
            <h2 class="sec-title">Tarif Konsultasi Pasien Mandiri</h2>
            <p class="sec-sub">Rincian tarif konsultasi dokter umum &amp; spesialis untuk pasien non-BPJS.</p>
        </div>
        <div class="tarif-grid">
            <div class="tarif-card">
                <div class="tarif-title">Poli Umum</div>
                <div class="tarif-price">Rp 100.000 <span>/ konsultasi</span></div>
                <ul class="tarif-list">
                    <li><i class="fas fa-check"></i> Konsultasi Dokter Umum</li>
                    <li><i class="fas fa-check"></i> Pemeriksaan Fisik Dasar</li>
                    <li><i class="fas fa-check"></i> Resep Elektronik</li>
                    <li><i class="fas fa-check"></i> Rekam Medis Cloud</li>
                </ul>
            </div>
            <div class="tarif-card featured">
                <div class="tarif-featured-badge">Paling Dipilih</div>
                <div class="tarif-title">Poli Spesialis</div>
                <div class="tarif-price">Rp 200.000 <span>/ konsultasi</span></div>
                <ul class="tarif-list">
                    <li><i class="fas fa-check"></i> Konsultasi Dokter Spesialis</li>
                    <li><i class="fas fa-check"></i> Pemeriksaan Khusus Poliklinik</li>
                    <li><i class="fas fa-check"></i> Evaluasi Penunjang Medis</li>
                    <li><i class="fas fa-check"></i> Terhubung ke SatuSehat</li>
                </ul>
            </div>
            <div class="tarif-card">
                <div class="tarif-title">IGD Siaga 24 Jam</div>
                <div class="tarif-price">Rp 150.000+ <span>/ tindakan</span></div>
                <ul class="tarif-list">
                    <li><i class="fas fa-check"></i> Penanganan Darurat Awal</li>
                    <li><i class="fas fa-check"></i> Observasi Tim Medis Siaga</li>
                    <li><i class="fas fa-check"></i> Tindakan Darurat Pertama</li>
                    <li><i class="fas fa-check"></i> Rujukan Rawat Inap</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer id="kontak">
    <div class="footer-container">
        <div class="footer-top">
            <div>
                <div class="ft-brand-title">
                    <i class="fas fa-hospital" style="color:#38bdf8"></i> RS Cahya Medika
                </div>
                <div class="ft-brand-desc">
                    Rumah sakit swasta modern Bondowoso yang berkomitmen memberikan pelayanan kesehatan berkualitas tinggi dengan teknologi digital terpadu.
                </div>
                <div style="font-size:12.5px;color:#94a3b8">
                    <i class="fas fa-location-dot" style="margin-right:6px;color:#38bdf8"></i> Jl. Mastrip, Bondowoso, Jawa Timur 68211
                </div>
            </div>
            <div>
                <div class="ft-col-title">Navigasi Utama</div>
                <div class="ft-links">
                    <a href="#home">Beranda Utama</a>
                    <a href="#layanan">Layanan Poliklinik</a>
                    <a href="#cara-daftar">Alur Pendaftaran</a>
                    <a href="#tarif">Info Tarif</a>
                </div>
            </div>
            <div>
                <div class="ft-col-title">Portal Akses</div>
                <div class="ft-links">
                    <a href="{{ route('pasien.login') }}">Masuk Pasien</a>
                    <a href="{{ route('admin.login') }}">Masuk Loket &amp; Admin</a>
                    <a href="{{ route('register') }}">Registrasi Pasien Baru</a>
                </div>
            </div>
            <div>
                <div class="ft-col-title">Kontak Darurat</div>
                <div style="font-size:13px;color:#f8fafc;font-weight:700">Telepon: (0332) 123456</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:4px">IGD 24 Jam: (0332) 123457</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:4px">Email: info@rscahyamedika.co.id</div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} RS Cahya Medika Bondowoso. Hak Cipta Dilindungi.</div>
            <div><i class="fas fa-shield-halved" style="color:#38bdf8;margin-right:4px"></i> Terintegrasi Platform SatuSehat Kemenkes RI</div>
        </div>
    </div>
</footer>

</body>
</html>
