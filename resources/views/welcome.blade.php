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
    --navy-card:     #1e293b;
    --teal:          #0d9488;
    --emerald:       #059669;
    --slate:         #334155;
    --muted:         #64748b;
    --light-bg:      #f8fafc;
    --white:         #ffffff;
    --border:        #e2e8f0;
    --shadow-sm:     0 2px 8px rgba(15, 23, 42, 0.04);
    --shadow-card:   0 12px 32px -6px rgba(15, 23, 42, 0.06), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
    --shadow-hover:  0 22px 50px -10px rgba(2, 132, 199, 0.2), 0 8px 20px -4px rgba(15, 23, 42, 0.08);
}
html { scroll-behavior: smooth; }
body { font-family:'Plus Jakarta Sans',sans-serif; color:var(--navy); background:#f8fafc; line-height:1.6; font-size:14px; overflow-x:hidden; width:100%; }
a { text-decoration:none; color:inherit; }

/* ─── FULL-WIDTH NAVBAR (KEPT INTACT AS REQUESTED) ──────── */
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

/* ─── REDESIGNED SPACIOUS HERO SECTION (NO MOCKUP CARD) ─── */
.hero-section {
    padding: 140px 48px 90px; width:100%;
    background:
        radial-gradient(circle at 50% 0%, rgba(56, 189, 248, 0.15) 0%, transparent 55%),
        radial-gradient(circle at 90% 90%, rgba(13, 148, 136, 0.08) 0%, transparent 45%),
        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border-bottom:1px solid var(--border);
    position:relative; text-align:center;
}
.hero-container-centered {
    width:100%; max-width:980px; margin:0 auto;
    display:flex; flex-direction:column; align-items:center;
}
.hero-badge {
    display:inline-flex; align-items:center; gap:8px;
    background:linear-gradient(135deg, #e0f2fe, #bae6fd);
    border:1px solid #7dd3fc;
    padding:8px 22px; border-radius:99px;
    font-size:12.5px; font-weight:800; color:#0369a1; margin-bottom:26px;
    box-shadow:0 4px 14px rgba(2, 132, 199, 0.12);
}
.hero-title {
    font-size:56px; font-weight:900; color:var(--navy);
    line-height:1.15; letter-spacing:-1.5px; margin-bottom:24px;
}
.gradient-text {
    background:linear-gradient(135deg, #0284c7 0%, #0d9488 50%, #059669 100%);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.hero-desc {
    font-size:17.5px; color:var(--muted); line-height:1.8;
    margin-bottom:40px; max-width:760px; font-weight:500;
}
.hero-buttons { display:flex; gap:16px; justify-content:center; flex-wrap:wrap; margin-bottom:54px; }
.btn-hero-primary {
    display:inline-flex; align-items:center; gap:10px;
    padding:18px 38px; border-radius:16px;
    background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color:var(--white); font-size:16px; font-weight:800;
    box-shadow:0 10px 28px rgba(2, 132, 199, 0.38); transition:all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-hero-primary:hover { transform:translateY(-3px); box-shadow:0 14px 36px rgba(2, 132, 199, 0.48); }
.btn-hero-secondary {
    display:inline-flex; align-items:center; gap:10px;
    padding:18px 34px; border-radius:16px; background:var(--white);
    color:var(--slate); font-size:16px; font-weight:800;
    border:1.5px solid var(--border); box-shadow:0 4px 16px rgba(15, 23, 42, 0.05);
    transition:all 0.25s;
}
.btn-hero-secondary:hover { background:#f1f5f9; color:var(--navy); border-color:#cbd5e1; transform:translateY(-2px); }

/* STAT HIGHLIGHTS BAR */
.hero-stats-row {
    display:grid; grid-template-columns:repeat(4,1fr); gap:20px;
    width:100%; max-width:1100px; margin:0 auto;
    padding-top:36px; border-top:1px solid var(--border);
}
.hs-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:20px; padding:22px 20px; text-align:center;
    box-shadow:var(--shadow-card); transition:all 0.25s;
}
.hs-card:hover { border-color:#38bdf8; transform:translateY(-4px); box-shadow:var(--shadow-hover); }
.hs-val { font-size:32px; font-weight:900; color:var(--navy); line-height:1; letter-spacing:-0.5px; }
.hs-lbl { font-size:12.5px; color:var(--muted); margin-top:6px; font-weight:700; }

/* ─── FULL-WIDTH SECTIONS ────────────────────────────────── */
.full-sec-wrap { width:100%; padding:96px 48px; border-bottom:1px solid var(--border); }
.full-sec-wrap.bg-light { background:#ffffff; }
.sec-inner-wide { width:100%; max-width:1440px; margin:0 auto; }
.sec-head { margin-bottom:56px; text-align:center; }
.sec-tag {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 18px; border-radius:99px;
    font-size:11.5px; font-weight:800; text-transform:uppercase; letter-spacing:0.8px;
    background:#e0f2fe; color:#0369a1; margin-bottom:14px; border:1px solid #bae6fd;
}
.sec-title { font-size:42px; font-weight:900; color:var(--navy); letter-spacing:-1.2px; line-height:1.2; }
.sec-sub { font-size:16px; color:var(--muted); margin:12px auto 0; max-width:680px; font-weight:500; }

/* ─── KEUNGGULAN ─────────────────────────────────────────── */
.feat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:28px; }
.feat-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:24px; padding:38px 30px; box-shadow:var(--shadow-card);
    transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position:relative; overflow:hidden;
}
.feat-card:hover {
    transform:translateY(-6px); box-shadow:var(--shadow-hover);
    border-color:#38bdf8;
}
.fc-icon-wrap {
    width:60px; height:60px; border-radius:18px;
    display:flex; align-items:center; justify-content:center;
    font-size:26px; margin-bottom:24px; flex-shrink:0;
    box-shadow:0 8px 20px rgba(0,0,0,0.05);
}
.fc-icon-wrap.i1 { background:#e0f2fe; color:#0284c7; }
.fc-icon-wrap.i2 { background:#ccfbf1; color:#0d9488; }
.fc-icon-wrap.i3 { background:#dcfce7; color:#16a34a; }
.fc-icon-wrap.i4 { background:#fef3c7; color:#d97706; }
.fc-icon-wrap.i5 { background:#f3e8ff; color:#9333ea; }
.fc-icon-wrap.i6 { background:#e0e7ff; color:#4f46e5; }
.fc-title { font-size:19px; font-weight:800; color:var(--navy); margin-bottom:12px; }
.fc-body  { font-size:14px; color:var(--muted); line-height:1.7; }

/* ─── POLI SECTION ───────────────────────────────────────── */
.poli-grid-section { display:grid; grid-template-columns:repeat(4,1fr); gap:24px; }
.pc-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:22px; padding:30px 24px; text-align:center;
    transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow:var(--shadow-card);
}
.pc-card:hover {
    border-color:var(--primary); transform:translateY(-6px);
    box-shadow:var(--shadow-hover);
}
.pc-icon-box {
    width:64px; height:64px; border-radius:20px;
    display:flex; align-items:center; justify-content:center;
    font-size:28px; margin:0 auto 20px;
}
.pc-name { font-size:17px; font-weight:800; color:var(--navy); margin-bottom:6px; }
.pc-detail { font-size:13px; color:var(--muted); font-weight:500; }
.pc-tag {
    display:inline-block; margin-top:16px; padding:5px 16px;
    border-radius:99px; font-size:11px; font-weight:800;
    background:#f0fdf4; color:#166534; border:1px solid #bbf7d0;
}

/* ─── CARA DAFTAR ────────────────────────────────────────── */
.steps-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:26px; }
.step-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:22px; padding:36px 28px; box-shadow:var(--shadow-card);
    transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-align:left;
}
.step-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-hover); border-color:#38bdf8; }
.step-num-badge {
    width:48px; height:48px; border-radius:16px;
    background:linear-gradient(135deg, #0284c7, #0d9488);
    color:var(--white); font-size:20px; font-weight:900;
    display:flex; align-items:center; justify-content:center; margin-bottom:24px;
    box-shadow:0 8px 20px rgba(2, 132, 199, 0.32);
}
.step-title { font-size:18px; font-weight:800; color:var(--navy); margin-bottom:10px; }
.step-desc { font-size:14px; color:var(--muted); line-height:1.7; }

/* ─── DOKTER ─────────────────────────────────────────────── */
.docs-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:28px; }
.doc-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:24px; padding:32px; box-shadow:var(--shadow-card);
    transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.doc-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-hover); border-color:#38bdf8; }
.doc-header { display:flex; align-items:center; gap:20px; margin-bottom:20px; }
.doc-avatar {
    width:64px; height:64px; border-radius:50%;
    background:#e0f2fe; color:#0284c7; border:3px solid #bae6fd;
    display:flex; align-items:center; justify-content:center;
    font-size:28px; flex-shrink:0; box-shadow:0 6px 16px rgba(2, 132, 199, 0.18);
}
.doc-name { font-size:18px; font-weight:800; color:var(--navy); }
.doc-spesialis { font-size:14px; color:var(--primary); font-weight:700; margin-top:4px; }
.doc-body { border-top:1px solid #f1f5f9; padding-top:20px; font-size:13.5px; color:var(--muted); }
.doc-str { font-family:monospace; font-size:12px; margin-top:10px; color:#64748b; background:#f8fafc; padding:6px 10px; border-radius:8px; display:inline-block; font-weight:600; }

/* ─── TARIF ──────────────────────────────────────────────── */
.tarif-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:28px; }
.tarif-card {
    background:var(--white); border:1px solid var(--border);
    border-radius:26px; padding:42px 34px; box-shadow:var(--shadow-card);
    transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position:relative;
}
.tarif-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-hover); }
.tarif-card.featured {
    border:2.5px solid var(--primary);
    box-shadow:0 24px 55px -10px rgba(2, 132, 199, 0.25);
}
.tarif-featured-badge {
    position:absolute; top:-15px; right:32px;
    background:linear-gradient(135deg, #0284c7, #0d9488); color:white;
    font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.8px;
    padding:6px 18px; border-radius:99px; box-shadow:0 6px 18px rgba(2, 132, 199, 0.35);
}
.tarif-title { font-size:20px; font-weight:800; color:var(--navy); margin-bottom:12px; }
.tarif-price { font-size:36px; font-weight:900; color:var(--navy); margin-bottom:18px; letter-spacing:-0.5px; }
.tarif-price span { font-size:14px; font-weight:600; color:var(--muted); }
.tarif-list { list-style:none; margin-top:22px; display:flex; flex-direction:column; gap:14px; font-size:14px; color:var(--slate); }
.tarif-list li { display:flex; align-items:center; gap:12px; }
.tarif-list li i { color:var(--emerald); font-size:16px; }

/* ─── FOOTER ─────────────────────────────────────────────── */
footer {
    background:var(--navy); color:#94a3b8;
    padding:80px 48px 40px; font-size:14px; width:100%;
}
.footer-container { width:100%; max-width:1440px; margin:0 auto; }
.footer-top { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:56px; padding-bottom:56px; border-bottom:1px solid #1e293b; }
.ft-brand-title { font-size:22px; font-weight:900; color:var(--white); margin-bottom:14px; display:flex; align-items:center; gap:12px; }
.ft-brand-desc { font-size:14px; color:#94a3b8; line-height:1.75; max-width:400px; margin-bottom:22px; }
.ft-col-title { font-size:13px; font-weight:800; color:var(--white); text-transform:uppercase; letter-spacing:1px; margin-bottom:20px; }
.ft-links { display:flex; flex-direction:column; gap:14px; }
.ft-links a { color:#94a3b8; transition:color 0.2s; font-weight:500; }
.ft-links a:hover { color:var(--white); }
.footer-bottom { padding-top:36px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:18px; font-size:13px; }

/* ─── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width:1200px) {
    #navbar, .hero-section, .full-sec-wrap, footer { padding-left:28px; padding-right:28px; }
    .hero-title { font-size:44px; }
    .feat-grid, .docs-grid, .tarif-grid { grid-template-columns:repeat(2,1fr); }
    .poli-grid-section, .steps-grid { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:640px) {
    .hero-title { font-size:34px; }
    .feat-grid, .docs-grid, .tarif-grid, .poli-grid-section, .steps-grid { grid-template-columns:1fr; }
    .footer-top { grid-template-columns:1fr; }
    .nav-menu, .nav-actions { display:none; }
    .nav-toggle { display:block; }
    .hero-stats-row { grid-template-columns:repeat(2,1fr); }
}
</style>
</head>
<body>

<!-- FULL-WIDTH NAVBAR (KEPT INTACT AS REQUESTED) -->
<nav id="navbar">
    <div class="nav-brand">
        <img src="{{ asset('logo.png') }}" alt="Logo RS Cahya Medika" style="height:38px;width:auto;object-fit:contain">
        <div class="nav-brand-text">
            <div class="n">RS Cahya Medika</div>
            <div class="s" style="color:#0891b2;font-weight:700">BONDOWOSO</div>
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

<!-- FULL-WIDTH REDESIGNED CENTERED HERO SECTION -->
<section class="hero-section" id="home">
    <div class="hero-container-centered">
        <div class="hero-badge">
            <i class="fas fa-shield-halved"></i>
            Terintegrasi SatuSehat Kemenkes RI &middot; RS Cahya Medika Bondowoso
        </div>
        <h1 class="hero-title">
            Solusi Kesehatan Digital <br><span class="gradient-text">Terpadu &amp; Modern</span> di Bondowoso
        </h1>
        <p class="hero-desc">
            Pendaftaran berobat online serba cepat, praktis, dan terpercaya. Pilih dokter spesialis penanggung jawab Anda, tentukan jadwal kunjungan, dan pantau status rekam medis Anda secara mudah.
        </p>
        <div class="hero-buttons">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                <i class="fas fa-calendar-plus"></i> Daftar Berobat Online
            </a>
            <a href="{{ route('pasien.login') }}" class="btn-hero-secondary">
                <i class="fas fa-user-circle"></i> Masuk Portal Pasien
            </a>
            <a href="{{ route('admin.login') }}" class="btn-hero-secondary" style="background:#f8fafc">
                <i class="fas fa-desktop"></i> Akses Staf / Loket
            </a>
        </div>

        <div class="hero-stats-row">
            <div class="hs-card">
                <div class="hs-val">8+</div>
                <div class="hs-lbl">Poliklinik Spesialis</div>
            </div>
            <div class="hs-card">
                <div class="hs-val">6+</div>
                <div class="hs-lbl">Dokter Spesialis Aktif</div>
            </div>
            <div class="hs-card">
                <div class="hs-val">24/7</div>
                <div class="hs-lbl">IGD Siaga &amp; Care</div>
            </div>
            <div class="hs-card">
                <div class="hs-val">100%</div>
                <div class="hs-lbl">Rekam Medis Cloud Sync</div>
            </div>
        </div>
    </div>
</section>

<!-- KEUNGGULAN UTAMA -->
<div class="full-sec-wrap" id="keunggulan">
    <div class="sec-inner-wide">
        <div class="sec-head">
            <span class="sec-tag"><i class="fas fa-star" style="margin-right:4px"></i> Keunggulan Layanan</span>
            <h2 class="sec-title">Kenapa Memilih RS Cahya Medika?</h2>
            <p class="sec-sub">Pelayanan kesehatan swasta modern Bondowoso yang mengedepankan keamanan data, efisiensi waktu, dan kenyamanan pasien.</p>
        </div>
        <div class="feat-grid">
            <div class="feat-card">
                <div class="fc-icon-wrap i1"><i class="fas fa-mobile-screen"></i></div>
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
                <div class="step-num-badge">01</div>
                <div class="step-title">Registrasi Akun</div>
                <div class="step-desc">Buat akun portal pasien dengan mengisi NIK KTP dan nama lengkap.</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">02</div>
                <div class="step-title">Pilih Poliklinik</div>
                <div class="step-desc">Pilih poliklinik dan dokter spesialis penanggung jawab perawatan Anda.</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">03</div>
                <div class="step-title">Jadwal &amp; Keluhan</div>
                <div class="step-desc">Tentukan tanggal kunjungan dan isi catatan keluhan singkat.</div>
            </div>
            <div class="step-card">
                <div class="step-num-badge">04</div>
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
                    <li><i class="fas fa-circle-check"></i> Konsultasi Dokter Umum</li>
                    <li><i class="fas fa-circle-check"></i> Pemeriksaan Fisik Dasar</li>
                    <li><i class="fas fa-circle-check"></i> Resep Elektronik</li>
                    <li><i class="fas fa-circle-check"></i> Rekam Medis Cloud</li>
                </ul>
            </div>
            <div class="tarif-card featured">
                <div class="tarif-featured-badge">Paling Dipilih</div>
                <div class="tarif-title">Poli Spesialis</div>
                <div class="tarif-price">Rp 200.000 <span>/ konsultasi</span></div>
                <ul class="tarif-list">
                    <li><i class="fas fa-circle-check"></i> Konsultasi Dokter Spesialis</li>
                    <li><i class="fas fa-circle-check"></i> Pemeriksaan Khusus Poliklinik</li>
                    <li><i class="fas fa-circle-check"></i> Evaluasi Penunjang Medis</li>
                    <li><i class="fas fa-circle-check"></i> Terhubung ke SatuSehat</li>
                </ul>
            </div>
            <div class="tarif-card">
                <div class="tarif-title">IGD Siaga 24 Jam</div>
                <div class="tarif-price">Rp 150.000+ <span>/ tindakan</span></div>
                <ul class="tarif-list">
                    <li><i class="fas fa-circle-check"></i> Penanganan Darurat Awal</li>
                    <li><i class="fas fa-circle-check"></i> Observasi Tim Medis Siaga</li>
                    <li><i class="fas fa-circle-check"></i> Tindakan Darurat Pertama</li>
                    <li><i class="fas fa-circle-check"></i> Rujukan Rawat Inap</li>
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
                    <img src="{{ asset('logo.png') }}" alt="Logo RS Cahya Medika" style="height:36px;width:auto;object-fit:contain">
                    <div style="line-height:1.2">
                        <div style="font-size:17px;font-weight:800;color:white">RS Cahya Medika</div>
                        <div style="font-size:11px;color:#38bdf8;font-weight:700">BONDOWOSO</div>
                    </div>
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
