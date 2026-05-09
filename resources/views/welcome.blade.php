<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RS Cahya Medika Bondowoso — Pendaftaran Online Non-BPJS</title>
<meta name="description" content="RS Cahya Medika Bondowoso — Rumah sakit swasta modern dengan layanan dokter spesialis. Daftar berobat online mudah, cepat, dan terhubung SatuSehat Kemenkes RI.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ─── RESET & BASE ─────────────────────────────────────── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
    --navy:    #0c4a6e;
    --navy2:   #0f5e8f;
    --teal:    #06b6d4;
    --teal2:   #0891b2;
    --teal3:   #0e7490;
    --green:   #059669;
    --white:   #ffffff;
    --slate:   #f8fafc;
    --gray:    #64748b;
    --dark:    #1e293b;
    --border:  #e2e8f0;
    --shadow:  0 4px 24px rgba(12,74,110,0.08);
    --shadow-lg: 0 20px 60px rgba(12,74,110,0.14);
}
html { scroll-behavior: smooth; }
body { font-family:'Plus Jakarta Sans',sans-serif; color:var(--dark); overflow-x:hidden; background:#fff; }
a { text-decoration:none; }
img { max-width:100%; }

/* ─── SCROLL ANIMATIONS ─────────────────────────────────── */
.reveal { opacity:0; transform:translateY(36px); transition:opacity 0.7s ease, transform 0.7s ease; }
.reveal.visible { opacity:1; transform:translateY(0); }
.reveal-left  { opacity:0; transform:translateX(-40px); transition:opacity 0.7s ease, transform 0.7s ease; }
.reveal-right { opacity:0; transform:translateX(40px);  transition:opacity 0.7s ease, transform 0.7s ease; }
.reveal-left.visible, .reveal-right.visible { opacity:1; transform:translateX(0); }
.delay-1 { transition-delay:0.1s; }
.delay-2 { transition-delay:0.2s; }
.delay-3 { transition-delay:0.3s; }
.delay-4 { transition-delay:0.4s; }
.delay-5 { transition-delay:0.5s; }
.delay-6 { transition-delay:0.6s; }

/* ─── NAVBAR ─────────────────────────────────────────────── */
#navbar {
    position:fixed; top:0; left:0; right:0; z-index:1000;
    padding:0 60px;
    height:72px;
    display:flex; align-items:center; justify-content:space-between;
    transition:all 0.3s;
}
#navbar.scrolled {
    background:rgba(12,74,110,0.97);
    backdrop-filter:blur(12px);
    box-shadow:0 4px 24px rgba(0,0,0,0.15);
}
.nav-brand { display:flex; align-items:center; gap:12px; }
.nav-logo-box {
    width:44px; height:44px; background:var(--teal); border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:20px;
    flex-shrink:0;
}
.nav-brand-text .n { font-size:15px; font-weight:800; color:#fff; line-height:1.2; }
.nav-brand-text .s { font-size:10px; color:rgba(255,255,255,0.55); }
.nav-menu { display:flex; align-items:center; gap:4px; }
.nav-menu a {
    color:rgba(255,255,255,0.8); font-size:13px; font-weight:600;
    padding:8px 14px; border-radius:8px; transition:all 0.2s;
}
.nav-menu a:hover { color:#fff; background:rgba(255,255,255,0.1); }
.nav-actions { display:flex; align-items:center; gap:8px; }
.btn-nav-ghost {
    padding:9px 18px; border-radius:10px; font-size:13px; font-weight:700;
    color:rgba(255,255,255,0.85); border:1px solid rgba(255,255,255,0.25);
    transition:all 0.2s;
}
.btn-nav-ghost:hover { background:rgba(255,255,255,0.12); color:#fff; }
.btn-nav-solid {
    padding:9px 20px; border-radius:10px; font-size:13px; font-weight:700;
    background:var(--teal); color:#fff;
    transition:all 0.2s;
}
.btn-nav-solid:hover { background:var(--teal2); }

/* hamburger */
.nav-toggle { display:none; color:#fff; font-size:22px; background:none; border:none; cursor:pointer; }
.nav-mobile { display:none; }

/* ─── HERO ──────────────────────────────────────────────── */
.hero {
    min-height:100vh;
    background:linear-gradient(135deg,#071e2d 0%,#0c4a6e 45%,#0891b2 100%);
    position:relative; overflow:hidden;
    display:flex; flex-direction:column;
}
.hero::before {
    content:''; position:absolute; inset:0;
    background-image:radial-gradient(circle,rgba(255,255,255,0.035) 1px,transparent 1px);
    background-size:28px 28px;
}
.blob1 {
    position:absolute; top:-120px; right:-80px;
    width:700px; height:700px; border-radius:50%;
    background:radial-gradient(circle,rgba(6,182,212,0.22) 0%,transparent 65%);
    pointer-events:none;
}
.blob2 {
    position:absolute; bottom:-200px; left:-150px;
    width:600px; height:600px; border-radius:50%;
    background:radial-gradient(circle,rgba(14,116,144,0.15) 0%,transparent 65%);
    pointer-events:none;
}
.blob3 {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:800px; height:800px; border-radius:50%;
    background:radial-gradient(circle,rgba(6,182,212,0.05) 0%,transparent 60%);
    pointer-events:none;
}
.hero-inner {
    position:relative; z-index:1;
    flex:1; display:flex; align-items:center;
    padding:120px 60px 80px;
    gap:60px; max-width:1280px; margin:0 auto; width:100%;
}
.hero-left { flex:1; }
.hero-tag {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(6,182,212,0.18);
    border:1px solid rgba(6,182,212,0.35);
    padding:6px 16px; border-radius:99px;
    font-size:12px; font-weight:700; color:#67e8f9;
    margin-bottom:24px;
    animation: fadeDown 0.8s ease both;
}
@keyframes fadeDown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
.hero-h1 {
    font-family:'Playfair Display',serif;
    font-size:58px; line-height:1.1; color:#fff;
    margin-bottom:22px;
    animation: fadeUp 0.9s ease 0.1s both;
}
.hero-h1 em { color:var(--teal); font-style:italic; }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.hero-p {
    font-size:16px; line-height:1.75; color:rgba(255,255,255,0.68);
    margin-bottom:36px; max-width:500px;
    animation: fadeUp 0.9s ease 0.2s both;
}
.hero-btns { display:flex; gap:14px; flex-wrap:wrap; animation: fadeUp 0.9s ease 0.3s both; }
.btn-primary-lg {
    display:inline-flex; align-items:center; gap:10px;
    padding:15px 32px; border-radius:12px;
    background:var(--teal); color:#fff;
    font-size:15px; font-weight:700;
    box-shadow:0 8px 28px rgba(6,182,212,0.45);
    transition:all 0.3s;
}
.btn-primary-lg:hover { background:var(--teal2); transform:translateY(-2px); box-shadow:0 14px 36px rgba(6,182,212,0.55); }
.btn-ghost-lg {
    display:inline-flex; align-items:center; gap:10px;
    padding:15px 28px; border-radius:12px;
    background:rgba(255,255,255,0.1); color:#fff;
    font-size:15px; font-weight:600;
    border:1px solid rgba(255,255,255,0.2);
    transition:all 0.2s;
}
.btn-ghost-lg:hover { background:rgba(255,255,255,0.18); }

/* Hero stats bar */
.hero-stats {
    display:flex; gap:36px; margin-top:48px; padding-top:36px;
    border-top:1px solid rgba(255,255,255,0.1);
    animation: fadeUp 0.9s ease 0.4s both;
}
.hs-item .hn { font-size:32px; font-weight:900; color:var(--teal); letter-spacing:-1px; }
.hs-item .hl { font-size:11px; color:rgba(255,255,255,0.45); margin-top:2px; font-weight:500; }

/* Hero right card */
.hero-right { flex-shrink:0; animation: fadeUp 0.9s ease 0.3s both; }
.hero-card {
    background:rgba(255,255,255,0.07);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,0.13);
    border-radius:24px; padding:28px; width:360px;
}
.hc-title { font-size:15px; font-weight:800; color:#fff; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
.poli-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:20px; }
.poli-btn {
    background:rgba(255,255,255,0.07);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:14px; padding:14px 10px; text-align:center;
    transition:all 0.2s; cursor:default;
}
.poli-btn:hover { background:rgba(6,182,212,0.2); border-color:var(--teal); }
.pb-icon { font-size:22px; margin-bottom:6px; }
.pb-name { font-size:10px; font-weight:700; color:rgba(255,255,255,0.85); line-height:1.3; }
.hc-notice {
    background:rgba(6,182,212,0.12); border:1px solid rgba(6,182,212,0.3);
    border-radius:12px; padding:14px 16px; font-size:12px; color:#a5f3fc; line-height:1.6;
}

/* Hero bottom wave */
.hero-wave {
    position:relative; z-index:1; line-height:0;
}
.hero-wave svg { display:block; }

/* ─── SECTION SHARED ─────────────────────────────────────── */
.sec { padding:96px 60px; max-width:1280px; margin:0 auto; }
.sec-full { padding:96px 60px; }
.sec-full > .sec-inner { max-width:1280px; margin:0 auto; }
.sec-tag {
    display:inline-block; padding:5px 14px; border-radius:99px;
    font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:1.5px;
    background:#e0f2fe; color:var(--teal2); margin-bottom:14px;
}
.sec-title {
    font-family:'Playfair Display',serif;
    font-size:40px; font-weight:700; color:var(--navy);
    line-height:1.2; margin-bottom:14px;
}
.sec-sub { font-size:15px; color:var(--gray); line-height:1.75; max-width:520px; }
.sec-head { margin-bottom:52px; }

/* ─── STATS STRIP ─────────────────────────────────────────── */
.stats-strip {
    background:linear-gradient(135deg,var(--navy),var(--teal2));
    padding:52px 60px;
}
.stats-inner { max-width:1280px; margin:0 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:32px; }
.ss-item { text-align:center; }
.ss-num {
    font-size:44px; font-weight:900; color:#fff;
    letter-spacing:-2px; line-height:1;
}
.ss-num span { color:var(--teal); }
.ss-label { font-size:13px; color:rgba(255,255,255,0.65); margin-top:6px; font-weight:500; }

/* ─── KEUNGGULAN ─────────────────────────────────────────── */
.feat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.feat-card {
    padding:32px 28px; border-radius:20px;
    border:1px solid var(--border); background:#fff;
    transition:all 0.35s; position:relative; overflow:hidden;
}
.feat-card::before {
    content:''; position:absolute; inset:0;
    background:linear-gradient(135deg,var(--navy),var(--teal2));
    opacity:0; transition:opacity 0.35s;
}
.feat-card:hover::before { opacity:1; }
.feat-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); }
.fc-icon-wrap {
    width:58px; height:58px; border-radius:16px;
    background:#f0f9ff; display:flex; align-items:center; justify-content:center;
    font-size:26px; margin-bottom:20px;
    position:relative; z-index:1; transition:background 0.35s;
}
.feat-card:hover .fc-icon-wrap { background:rgba(6,182,212,0.25); }
.fc-title { font-size:16px; font-weight:800; margin-bottom:10px; position:relative; z-index:1; transition:color 0.35s; }
.fc-body  { font-size:13px; color:var(--gray); line-height:1.65; position:relative; z-index:1; transition:color 0.35s; }
.feat-card:hover .fc-title { color:#fff; }
.feat-card:hover .fc-body  { color:rgba(255,255,255,0.65); }

/* ─── LAYANAN POLI ───────────────────────────────────────── */
.poli-section { background:#f8fafc; }
.poli-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.pc {
    background:#fff; border-radius:20px;
    border:2px solid var(--border);
    padding:28px 20px; text-align:center;
    transition:all 0.3s; cursor:default;
    position:relative; overflow:hidden;
}
.pc::after {
    content:''; position:absolute; bottom:0; left:0; right:0;
    height:4px; background:linear-gradient(90deg,var(--teal),var(--navy));
    transform:scaleX(0); transform-origin:left;
    transition:transform 0.3s;
}
.pc:hover { border-color:var(--teal2); transform:translateY(-4px); box-shadow:0 16px 40px rgba(8,145,178,0.12); }
.pc:hover::after { transform:scaleX(1); }
.pc-icon {
    width:64px; height:64px; border-radius:18px;
    background:linear-gradient(135deg,#e0f2fe,#bae6fd);
    display:flex; align-items:center; justify-content:center;
    font-size:28px; margin:0 auto 16px;
    transition:all 0.3s;
}
.pc:hover .pc-icon { background:linear-gradient(135deg,var(--teal),var(--navy)); }
.pc-name { font-size:14px; font-weight:800; color:var(--navy); margin-bottom:6px; }
.pc-lantai { font-size:11px; color:var(--gray); }
.pc-jam   { font-size:11px; color:var(--teal2); font-weight:600; margin-top:4px; }

/* ─── CARA DAFTAR ────────────────────────────────────────── */
.steps { display:grid; grid-template-columns:repeat(4,1fr); gap:0; position:relative; }
.steps::before {
    content:''; position:absolute;
    top:40px; left:calc(12.5%); right:calc(12.5%);
    height:2px;
    background:linear-gradient(90deg,var(--teal),var(--navy));
    z-index:0;
}
.step-item { text-align:center; position:relative; z-index:1; padding:0 16px; }
.step-num {
    width:80px; height:80px; border-radius:50%;
    background:linear-gradient(135deg,var(--teal),var(--navy));
    color:#fff; font-size:28px; font-weight:900;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 20px;
    box-shadow:0 8px 24px rgba(6,182,212,0.35);
    border:4px solid #fff;
}
.step-title { font-size:15px; font-weight:800; color:var(--navy); margin-bottom:8px; }
.step-desc  { font-size:13px; color:var(--gray); line-height:1.6; }

/* ─── DOKTER ─────────────────────────────────────────────── */
.docs-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.doc-card {
    background:#fff; border-radius:20px;
    border:1px solid var(--border);
    overflow:hidden; transition:all 0.3s;
}
.doc-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); border-color:var(--teal2); }
.doc-top {
    height:120px;
    background:linear-gradient(135deg,var(--navy),var(--teal2));
    position:relative; display:flex; align-items:center; justify-content:center;
}
.doc-avatar {
    width:80px; height:80px; border-radius:50%;
    background:rgba(255,255,255,0.15);
    border:4px solid rgba(255,255,255,0.4);
    display:flex; align-items:center; justify-content:center;
    font-size:32px;
}
.doc-poli-tag {
    position:absolute; top:12px; right:14px;
    background:rgba(255,255,255,0.2);
    backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.3);
    color:#fff; font-size:10px; font-weight:700;
    padding:4px 10px; border-radius:99px;
}
.doc-body { padding:20px; }
.doc-name { font-size:15px; font-weight:800; color:var(--navy); margin-bottom:4px; }
.doc-spesialis { font-size:12px; color:var(--teal2); font-weight:700; margin-bottom:12px; }
.doc-jadwal {
    display:flex; flex-wrap:wrap; gap:5px; margin-bottom:14px;
}
.dj-tag {
    background:#f0f9ff; color:var(--teal2);
    font-size:10px; font-weight:700;
    padding:3px 9px; border-radius:6px;
}
.doc-str { font-size:11px; color:var(--gray); display:flex; align-items:center; gap:5px; }

/* ─── TENTANG ─────────────────────────────────────────────── */
.about-section { background:var(--navy); color:#fff; }
.about-grid { display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; }
.about-tag { background:rgba(6,182,212,0.2); border:1px solid rgba(6,182,212,0.3); color:#67e8f9; margin-bottom:16px; }
.about-title { font-family:'Playfair Display',serif; font-size:38px; color:#fff; margin-bottom:18px; line-height:1.2; }
.about-desc { font-size:15px; color:rgba(255,255,255,0.65); line-height:1.8; margin-bottom:28px; }
.about-list { display:flex; flex-direction:column; gap:14px; }
.al-item { display:flex; align-items:flex-start; gap:12px; }
.al-icon { width:32px; height:32px; border-radius:8px; background:rgba(6,182,212,0.2); display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; margin-top:2px; }
.al-text { font-size:13px; color:rgba(255,255,255,0.72); line-height:1.6; }
.al-text strong { color:#fff; }

.about-cards { display:flex; flex-direction:column; gap:16px; }
.ab-card {
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:16px; padding:20px 22px;
    display:flex; align-items:center; gap:16px;
}
.abc-icon { font-size:28px; flex-shrink:0; }
.abc-title { font-size:14px; font-weight:700; color:#fff; margin-bottom:4px; }
.abc-sub   { font-size:12px; color:rgba(255,255,255,0.5); }

/* ─── INFO TARIF ─────────────────────────────────────────── */
.tarif-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.tarif-card {
    background:#fff; border-radius:20px;
    border:2px solid var(--border);
    padding:32px 24px; text-align:center;
    transition:all 0.3s; position:relative; overflow:hidden;
}
.tarif-card.featured {
    border-color:var(--teal2);
    box-shadow:0 16px 48px rgba(8,145,178,0.15);
}
.tarif-badge {
    position:absolute; top:16px; right:16px;
    background:var(--teal); color:#fff;
    font-size:10px; font-weight:800; padding:4px 10px; border-radius:99px;
    text-transform:uppercase; letter-spacing:0.5px;
}
.tarif-icon { font-size:36px; margin-bottom:14px; }
.tarif-name { font-size:16px; font-weight:800; color:var(--navy); margin-bottom:8px; }
.tarif-price { font-size:28px; font-weight:900; color:var(--teal2); margin-bottom:4px; letter-spacing:-1px; }
.tarif-price span { font-size:14px; font-weight:500; color:var(--gray); }
.tarif-desc { font-size:13px; color:var(--gray); line-height:1.6; margin-top:12px; }
.tarif-list { list-style:none; margin-top:16px; display:flex; flex-direction:column; gap:8px; text-align:left; }
.tarif-list li { font-size:12px; color:var(--gray); display:flex; align-items:center; gap:8px; }
.tarif-list li::before { content:'✓'; color:var(--green); font-weight:700; }

/* ─── JAM OPERASIONAL ────────────────────────────────────── */
.jam-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }
.jam-card { background:#fff; border-radius:16px; border:1px solid var(--border); padding:22px 24px; display:flex; align-items:center; gap:16px; }
.jc-icon { width:48px; height:48px; border-radius:14px; background:#e0f2fe; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.jc-title { font-size:14px; font-weight:800; color:var(--navy); }
.jc-time  { font-size:13px; color:var(--gray); margin-top:3px; }
.jc-badge { display:inline-block; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700; margin-top:5px; }
.jc-badge.open   { background:#d1fae5; color:#065f46; }
.jc-badge.always { background:#fef3c7; color:#92400e; }

/* ─── KONTAK ─────────────────────────────────────────────── */
.kontak-section { background:#f8fafc; }
.kontak-grid { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:start; }
.kontak-items { display:flex; flex-direction:column; gap:16px; }
.kontak-item {
    display:flex; align-items:flex-start; gap:16px;
    background:#fff; border-radius:16px; border:1px solid var(--border);
    padding:18px 20px;
}
.ki-icon { width:44px; height:44px; border-radius:12px; background:#e0f2fe; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.ki-label { font-size:11px; font-weight:700; color:var(--gray); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; }
.ki-value { font-size:14px; font-weight:700; color:var(--navy); }
.ki-sub   { font-size:12px; color:var(--gray); margin-top:2px; }
.map-placeholder {
    background:linear-gradient(135deg,var(--navy),var(--teal2));
    border-radius:20px; overflow:hidden;
    min-height:360px; display:flex; flex-direction:column;
    align-items:center; justify-content:center; color:#fff;
    position:relative;
}
.map-placeholder::before {
    content:''; position:absolute; inset:0;
    background-image:radial-gradient(circle,rgba(255,255,255,0.04) 1px,transparent 1px);
    background-size:24px 24px;
}
.map-icon { font-size:48px; margin-bottom:16px; position:relative; z-index:1; }
.map-text { font-size:16px; font-weight:700; text-align:center; position:relative; z-index:1; }
.map-sub  { font-size:13px; opacity:0.65; text-align:center; margin-top:6px; position:relative; z-index:1; }
.map-pin  { display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); padding:10px 18px; border-radius:10px; font-size:13px; font-weight:600; margin-top:16px; position:relative; z-index:1; }

/* ─── CTA SECTION ─────────────────────────────────────────── */
.cta-section {
    background:linear-gradient(135deg,var(--navy) 0%,var(--teal2) 100%);
    padding:100px 60px; text-align:center; position:relative; overflow:hidden;
}
.cta-section::before { content:''; position:absolute; inset:0; background-image:radial-gradient(circle,rgba(255,255,255,0.04) 1px,transparent 1px); background-size:28px 28px; }
.cta-inner { position:relative; z-index:1; max-width:640px; margin:0 auto; }
.cta-title { font-family:'Playfair Display',serif; font-size:44px; color:#fff; margin-bottom:18px; line-height:1.15; }
.cta-sub { font-size:16px; color:rgba(255,255,255,0.7); line-height:1.7; margin-bottom:40px; }
.cta-btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
.btn-cta-white {
    display:inline-flex; align-items:center; gap:10px;
    padding:16px 36px; border-radius:12px;
    background:#fff; color:var(--navy);
    font-size:15px; font-weight:800;
    box-shadow:0 8px 28px rgba(0,0,0,0.15);
    transition:all 0.3s;
}
.btn-cta-white:hover { transform:translateY(-2px); box-shadow:0 14px 40px rgba(0,0,0,0.2); }
.btn-cta-outline {
    display:inline-flex; align-items:center; gap:10px;
    padding:16px 28px; border-radius:12px;
    background:rgba(255,255,255,0.12); color:#fff;
    font-size:15px; font-weight:700;
    border:1px solid rgba(255,255,255,0.25);
    transition:all 0.2s;
}
.btn-cta-outline:hover { background:rgba(255,255,255,0.2); }

/* ─── FOOTER ─────────────────────────────────────────────── */
footer {
    background:#050f1a; color:rgba(255,255,255,0.55);
    padding:60px 60px 32px;
}
.footer-inner { max-width:1280px; margin:0 auto; }
.footer-top { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:48px; padding-bottom:48px; border-bottom:1px solid rgba(255,255,255,0.08); }
.ft-brand .logo { display:flex; align-items:center; gap:12px; margin-bottom:16px; }
.ft-logo-box { width:42px; height:42px; background:var(--teal2); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.ft-name { font-size:15px; font-weight:800; color:#fff; }
.ft-sub  { font-size:10px; color:rgba(255,255,255,0.4); }
.ft-desc { font-size:13px; line-height:1.75; margin-bottom:20px; }
.ft-socials { display:flex; gap:10px; }
.ft-social {
    width:36px; height:36px; border-radius:10px;
    background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1);
    display:flex; align-items:center; justify-content:center;
    font-size:14px; color:rgba(255,255,255,0.55);
    transition:all 0.2s;
}
.ft-social:hover { background:var(--teal2); color:#fff; border-color:var(--teal2); }
.ft-col-title { font-size:12px; font-weight:800; color:#fff; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px; }
.ft-links { display:flex; flex-direction:column; gap:10px; }
.ft-links a { font-size:13px; color:rgba(255,255,255,0.5); transition:color 0.2s; }
.ft-links a:hover { color:#fff; }
.ft-contact-item { display:flex; align-items:flex-start; gap:10px; margin-bottom:10px; font-size:12px; }
.ft-contact-item i { color:var(--teal2); margin-top:2px; flex-shrink:0; }
.footer-bottom { padding-top:24px; display:flex; justify-content:space-between; align-items:center; font-size:12px; flex-wrap:wrap; gap:8px; }
.fb-right { display:flex; gap:16px; }
.fb-right a { color:rgba(255,255,255,0.4); font-size:12px; transition:color 0.2s; }
.fb-right a:hover { color:rgba(255,255,255,0.7); }
.satusehat-badge {
    display:inline-flex; align-items:center; gap:7px;
    background:rgba(6,182,212,0.15); border:1px solid rgba(6,182,212,0.3);
    color:#67e8f9; padding:6px 14px; border-radius:10px; font-size:11px; font-weight:700;
    margin-top:16px;
}

/* ─── BACK TO TOP ────────────────────────────────────────── */
#backTop {
    position:fixed; bottom:24px; right:24px; z-index:999;
    width:44px; height:44px; border-radius:12px;
    background:var(--teal2); color:#fff; border:none; cursor:pointer;
    font-size:16px; display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 16px rgba(8,145,178,0.4);
    opacity:0; transform:translateY(12px);
    transition:all 0.3s;
}
#backTop.show { opacity:1; transform:translateY(0); }

/* ─── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width:1024px) {
    .hero-h1 { font-size:44px; }
    .hero-inner { padding:110px 32px 64px; gap:40px; }
    .hero-card { width:320px; }
    .feat-grid { grid-template-columns:repeat(2,1fr); }
    .poli-cards { grid-template-columns:repeat(3,1fr); }
    .docs-grid { grid-template-columns:repeat(2,1fr); }
    .tarif-grid { grid-template-columns:1fr 1fr; }
    .footer-top { grid-template-columns:1fr 1fr; gap:32px; }
    .sec, .sec-full { padding:72px 32px; }
}
@media (max-width:768px) {
    #navbar { padding:0 20px; }
    .nav-menu, .nav-actions { display:none; }
    .nav-toggle { display:block; }
    .nav-mobile {
        display:none; position:fixed; top:72px; left:0; right:0;
        background:rgba(12,74,110,0.97); backdrop-filter:blur(12px);
        padding:20px; flex-direction:column; gap:8px; z-index:999;
    }
    .nav-mobile.open { display:flex; }
    .nav-mobile a { color:#fff; font-size:15px; font-weight:600; padding:12px 16px; border-radius:10px; display:block; }
    .nav-mobile a:hover { background:rgba(255,255,255,0.1); }
    .hero-inner { flex-direction:column; padding:100px 20px 60px; }
    .hero-h1 { font-size:36px; }
    .hero-card { width:100%; }
    .hero-stats { gap:20px; flex-wrap:wrap; }
    .stats-inner { grid-template-columns:repeat(2,1fr); }
    .feat-grid, .poli-cards, .docs-grid, .tarif-grid { grid-template-columns:1fr; }
    .steps { grid-template-columns:1fr 1fr; }
    .steps::before { display:none; }
    .about-grid, .kontak-grid { grid-template-columns:1fr; }
    .jam-grid { grid-template-columns:1fr; }
    .sec, .sec-full { padding:60px 20px; }
    .sec-title { font-size:30px; }
    .cta-title { font-size:30px; }
    .cta-section { padding:72px 20px; }
    footer { padding:48px 20px 24px; }
    .footer-top { grid-template-columns:1fr; gap:28px; }
    .footer-bottom { flex-direction:column; text-align:center; }
}
</style>
</head>
<body>

<!-- ════════════════════════════ NAVBAR ═══════════════════════════ -->
<nav id="navbar">
    <div class="nav-brand">
        <div class="nav-logo-box">🏥</div>
        <div class="nav-brand-text">
            <div class="n">RS Cahya Medika</div>
            <div class="s">Bondowoso · Rumah Sakit Swasta</div>
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
        <a href="{{ route('login') }}" class="btn-nav-ghost">Masuk</a>
        <a href="{{ route('register') }}" class="btn-nav-solid">Daftar Sekarang</a>
    </div>
    <button class="nav-toggle" onclick="toggleMenu()" aria-label="Menu">
        <i class="fas fa-bars" id="menuIcon"></i>
    </button>
</nav>

<!-- Mobile Menu -->
<div class="nav-mobile" id="navMobile">
    <a href="#layanan" onclick="closeMenu()">Layanan Poli</a>
    <a href="#cara-daftar" onclick="closeMenu()">Cara Daftar</a>
    <a href="#dokter" onclick="closeMenu()">Dokter</a>
    <a href="#tarif" onclick="closeMenu()">Tarif</a>
    <a href="#kontak" onclick="closeMenu()">Kontak</a>
    <hr style="border-color:rgba(255,255,255,0.1);margin:8px 0">
    <a href="{{ route('login') }}" onclick="closeMenu()">Masuk</a>
    <a href="{{ route('register') }}" onclick="closeMenu()" style="background:var(--teal);border-radius:10px;text-align:center">Daftar Pasien Baru</a>
</div>

<!-- ════════════════════════════ HERO ════════════════════════════ -->
<section class="hero" id="home">
    <div class="blob1"></div>
    <div class="blob2"></div>
    <div class="blob3"></div>

    <div class="hero-inner">
        <!-- LEFT -->
        <div class="hero-left">
            <div class="hero-tag">
                <i class="fas fa-shield-halved" style="font-size:11px"></i>
                Terintegrasi SatuSehat Kemenkes RI
            </div>

            <h1 class="hero-h1">
                Layanan Kesehatan<br><em>Modern & Terpercaya</em><br>di Bondowoso
            </h1>

            <p class="hero-p">
                Daftar berobat online dengan mudah, pilih dokter spesialis favorit Anda, dan pantau antrian secara real-time. Data kesehatan Anda aman dan terhubung ke sistem nasional.
            </p>

            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-primary-lg">
                    <i class="fas fa-user-plus"></i> Daftar Pasien Baru
                </a>
                <a href="{{ route('login') }}" class="btn-ghost-lg">
                    <i class="fas fa-sign-in-alt"></i> Sudah Punya Akun
                </a>
            </div>

            <div class="hero-stats">
                <div class="hs-item">
                    <div class="hn">8<span>+</span></div>
                    <div class="hl">Poli Layanan</div>
                </div>
                <div class="hs-item">
                    <div class="hn">6<span>+</span></div>
                    <div class="hl">Dokter Spesialis</div>
                </div>
                <div class="hs-item">
                    <div class="hn">24/7</div>
                    <div class="hl">IGD Siaga</div>
                </div>
                <div class="hs-item">
                    <div class="hn">100<span>%</span></div>
                    <div class="hl">Digital Record</div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="hero-right">
            <div class="hero-card">
                <div class="hc-title">
                    <span>🏥</span> Layanan Poli Tersedia
                </div>
                <div class="poli-grid">
                    <div class="poli-btn"><div class="pb-icon">🏥</div><div class="pb-name">Poli Umum</div></div>
                    <div class="poli-btn"><div class="pb-icon">👶</div><div class="pb-name">Poli Anak</div></div>
                    <div class="poli-btn"><div class="pb-icon">🫀</div><div class="pb-name">Penyakit Dalam</div></div>
                    <div class="poli-btn"><div class="pb-icon">🤱</div><div class="pb-name">Kebidanan</div></div>
                    <div class="poli-btn"><div class="pb-icon">❤️</div><div class="pb-name">Jantung</div></div>
                    <div class="poli-btn"><div class="pb-icon">🔬</div><div class="pb-name">Bedah</div></div>
                    <div class="poli-btn"><div class="pb-icon">🧠</div><div class="pb-name">Saraf</div></div>
                    <div class="poli-btn"><div class="pb-icon">👁️</div><div class="pb-name">Mata</div></div>
                </div>
                <div class="hc-notice">
                    <i class="fas fa-info-circle" style="margin-right:7px"></i>
                    <strong>Informasi:</strong> RS Cahya Medika saat ini melayani pasien umum <strong>non-BPJS</strong>. Hadir dengan layanan premium dan teknologi rekam medis digital.
                </div>
            </div>
        </div>
    </div>

    <!-- Wave -->
    <div class="hero-wave">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 80L60 66.7C120 53 240 27 360 18.7C480 13 600 27 720 34.7C840 40 960 40 1080 36C1200 30.7 1320 20 1380 13.3L1440 8V80H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- ════════════════════════════ STATS STRIP ═══════════════════════════ -->
<div class="stats-strip">
    <div class="stats-inner">
        <div class="ss-item reveal delay-1">
            <div class="ss-num"><span id="cnt-pasien">22</span><span>+</span></div>
            <div class="ss-label">Pasien Terdaftar</div>
        </div>
        <div class="ss-item reveal delay-2">
            <div class="ss-num"><span id="cnt-kunjungan">50</span><span>+</span></div>
            <div class="ss-label">Total Kunjungan</div>
        </div>
        <div class="ss-item reveal delay-3">
            <div class="ss-num"><span>8</span></div>
            <div class="ss-label">Poli & Klinik Aktif</div>
        </div>
        <div class="ss-item reveal delay-4">
            <div class="ss-num"><span>6</span><span>+</span></div>
            <div class="ss-label">Dokter Spesialis</div>
        </div>
    </div>
</div>

<!-- ════════════════════════════ KEUNGGULAN ═══════════════════════════ -->
<section id="keunggulan">
    <div class="sec">
        <div class="sec-head reveal">
            <div class="sec-tag">Keunggulan Kami</div>
            <h2 class="sec-title">Kenapa Memilih<br>RS Cahya Medika?</h2>
            <p class="sec-sub">Kami hadir dengan teknologi modern dan tenaga medis berpengalaman untuk memberikan pelayanan terbaik di Bondowoso dan sekitarnya.</p>
        </div>
        <div class="feat-grid">
            <div class="feat-card reveal delay-1">
                <div class="fc-icon-wrap">📱</div>
                <div class="fc-title">Pendaftaran Online 24/7</div>
                <div class="fc-body">Daftar berobat kapan saja dan di mana saja. Pilih dokter, jadwal, dan poli sesuai kebutuhan tanpa perlu antre panjang di loket.</div>
            </div>
            <div class="feat-card reveal delay-2">
                <div class="fc-icon-wrap">🔗</div>
                <div class="fc-title">Terintegrasi SatuSehat</div>
                <div class="fc-body">Data kesehatan Anda terhubung ke platform SatuSehat Kemenkes RI. Rekam medis terpadu, aman, dan dapat diakses antar fasilitas kesehatan.</div>
            </div>
            <div class="feat-card reveal delay-3">
                <div class="fc-icon-wrap">👨‍⚕️</div>
                <div class="fc-title">Dokter Spesialis Berpengalaman</div>
                <div class="fc-body">Tim dokter spesialis kami siap memberikan penanganan terbaik dengan standar pelayanan modern dan berkomitmen pada kesehatan pasien.</div>
            </div>
            <div class="feat-card reveal delay-4">
                <div class="fc-icon-wrap">📋</div>
                <div class="fc-title">Rekam Medis Digital</div>
                <div class="fc-body">Seluruh riwayat kesehatan tersimpan secara digital. Akses riwayat kunjungan, diagnosa, dan resep obat kapan pun Anda butuhkan.</div>
            </div>
            <div class="feat-card reveal delay-5">
                <div class="fc-icon-wrap">⚡</div>
                <div class="fc-title">Antrian Digital Real-time</div>
                <div class="fc-body">Sistem antrian cerdas memberi tahu nomor giliran Anda secara real-time. Tidak perlu duduk berjam-jam menunggu di ruang tunggu.</div>
            </div>
            <div class="feat-card reveal delay-6">
                <div class="fc-icon-wrap">🔒</div>
                <div class="fc-title">Privasi & Keamanan Data</div>
                <div class="fc-body">Keamanan data pasien adalah prioritas. Semua informasi medis dienkripsi dan hanya dapat diakses oleh tenaga medis berwenang.</div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ POLI SECTION ═══════════════════════════ -->
<section id="layanan" class="sec-full poli-section">
    <div class="sec-inner">
        <div class="sec-head reveal">
            <div class="sec-tag">Layanan Kami</div>
            <h2 class="sec-title">Poli & Klinik Unggulan</h2>
            <p class="sec-sub">Tersedia 8 poli spesialis dengan dokter berpengalaman dan fasilitas medis terkini untuk memenuhi semua kebutuhan kesehatan Anda.</p>
        </div>
        <div class="poli-cards">
            <div class="pc reveal delay-1">
                <div class="pc-icon">🏥</div>
                <div class="pc-name">Poli Umum</div>
                <div class="pc-lantai">Lantai 1</div>
                <div class="pc-jam">07:30 – 14:00</div>
            </div>
            <div class="pc reveal delay-2">
                <div class="pc-icon">👶</div>
                <div class="pc-name">Poli Anak</div>
                <div class="pc-lantai">Lantai 1</div>
                <div class="pc-jam">07:30 – 14:00</div>
            </div>
            <div class="pc reveal delay-3">
                <div class="pc-icon">🫀</div>
                <div class="pc-name">Poli Penyakit Dalam</div>
                <div class="pc-lantai">Lantai 2</div>
                <div class="pc-jam">08:00 – 14:00</div>
            </div>
            <div class="pc reveal delay-4">
                <div class="pc-icon">🤱</div>
                <div class="pc-name">Poli Kebidanan & Kandungan</div>
                <div class="pc-lantai">Lantai 2</div>
                <div class="pc-jam">08:00 – 14:00</div>
            </div>
            <div class="pc reveal delay-1">
                <div class="pc-icon">🔬</div>
                <div class="pc-name">Poli Bedah</div>
                <div class="pc-lantai">Lantai 2</div>
                <div class="pc-jam">08:00 – 13:00</div>
            </div>
            <div class="pc reveal delay-2">
                <div class="pc-icon">❤️</div>
                <div class="pc-name">Poli Jantung</div>
                <div class="pc-lantai">Lantai 3</div>
                <div class="pc-jam">08:00 – 13:00</div>
            </div>
            <div class="pc reveal delay-3">
                <div class="pc-icon">🧠</div>
                <div class="pc-name">Poli Saraf</div>
                <div class="pc-lantai">Lantai 3</div>
                <div class="pc-jam">08:00 – 13:00</div>
            </div>
            <div class="pc reveal delay-4">
                <div class="pc-icon">👁️</div>
                <div class="pc-name">Poli Mata</div>
                <div class="pc-lantai">Lantai 1</div>
                <div class="pc-jam">07:30 – 14:00</div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ CARA DAFTAR ═══════════════════════════ -->
<section id="cara-daftar">
    <div class="sec">
        <div class="sec-head reveal" style="text-align:center;max-width:none">
            <div class="sec-tag" style="margin:0 auto 14px">Cara Mendaftar</div>
            <h2 class="sec-title" style="text-align:center">4 Langkah Mudah<br>Daftar Berobat Online</h2>
            <p class="sec-sub" style="margin:0 auto;text-align:center">Proses pendaftaran yang simpel dan cepat, bisa dilakukan dari mana saja hanya dalam beberapa menit.</p>
        </div>
        <div class="steps" style="margin-top:60px">
            <div class="step-item reveal delay-1">
                <div class="step-num">1</div>
                <div class="step-title">Buat Akun</div>
                <div class="step-desc">Daftar akun baru dengan email, NIK, dan data diri. Hanya butuh 2 menit.</div>
            </div>
            <div class="step-item reveal delay-2">
                <div class="step-num">2</div>
                <div class="step-title">Pilih Poli & Dokter</div>
                <div class="step-desc">Pilih poli yang sesuai dan dokter favorit Anda beserta jadwal yang tersedia.</div>
            </div>
            <div class="step-item reveal delay-3">
                <div class="step-num">3</div>
                <div class="step-title">Tentukan Jadwal</div>
                <div class="step-desc">Pilih tanggal dan jam kunjungan, isi keluhan utama, lalu konfirmasi pendaftaran.</div>
            </div>
            <div class="step-item reveal delay-4">
                <div class="step-num">4</div>
                <div class="step-title">Datang & Dilayani</div>
                <div class="step-desc">Datang 15 menit sebelum jadwal dengan KTP. Pantau nomor antrian Anda secara online.</div>
            </div>
        </div>
        <div style="text-align:center;margin-top:52px" class="reveal">
            <a href="{{ route('register') }}" class="btn-primary-lg" style="display:inline-flex">
                <i class="fas fa-user-plus"></i> Mulai Daftar Sekarang
            </a>
        </div>
    </div>
</section>

<!-- ════════════════════════════ DOKTER ═══════════════════════════ -->
<section id="dokter" class="sec-full" style="background:#f8fafc">
    <div class="sec-inner">
        <div class="sec-head reveal">
            <div class="sec-tag">Tim Medis</div>
            <h2 class="sec-title">Dokter Spesialis Kami</h2>
            <p class="sec-sub">Ditangani oleh dokter-dokter spesialis berpengalaman yang berkomitmen memberikan pelayanan kesehatan terbaik.</p>
        </div>
        <div class="docs-grid">
            <div class="doc-card reveal delay-1">
                <div class="doc-top">
                    <div class="doc-avatar">👨‍⚕️</div>
                    <div class="doc-poli-tag">Penyakit Dalam</div>
                </div>
                <div class="doc-body">
                    <div class="doc-name">dr. Ahmad Fauzi, Sp.PD</div>
                    <div class="doc-spesialis">Dokter Spesialis Penyakit Dalam</div>
                    <div class="doc-jadwal">
                        <span class="dj-tag">Sen</span><span class="dj-tag">Sel</span><span class="dj-tag">Rab</span><span class="dj-tag">Kam</span><span class="dj-tag">Jum</span>
                    </div>
                    <div class="doc-str"><i class="fas fa-id-card" style="color:var(--teal2)"></i> STR: 3501-2-1-2019-000456</div>
                </div>
            </div>
            <div class="doc-card reveal delay-2">
                <div class="doc-top">
                    <div class="doc-avatar">👩‍⚕️</div>
                    <div class="doc-poli-tag">Poli Anak</div>
                </div>
                <div class="doc-body">
                    <div class="doc-name">dr. Siti Rahma Dewi, Sp.A</div>
                    <div class="doc-spesialis">Dokter Spesialis Anak</div>
                    <div class="doc-jadwal">
                        <span class="dj-tag">Sen</span><span class="dj-tag">Sel</span><span class="dj-tag">Rab</span><span class="dj-tag">Kam</span><span class="dj-tag">Jum</span>
                    </div>
                    <div class="doc-str"><i class="fas fa-id-card" style="color:var(--teal2)"></i> STR: 3501-2-3-2020-000891</div>
                </div>
            </div>
            <div class="doc-card reveal delay-3">
                <div class="doc-top">
                    <div class="doc-avatar">👨‍⚕️</div>
                    <div class="doc-poli-tag">Kebidanan</div>
                </div>
                <div class="doc-body">
                    <div class="doc-name">dr. Budi Santoso, Sp.OG</div>
                    <div class="doc-spesialis">Dokter Spesialis Obgyn</div>
                    <div class="doc-jadwal">
                        <span class="dj-tag">Sen</span><span class="dj-tag">Sel</span><span class="dj-tag">Rab</span><span class="dj-tag">Kam</span><span class="dj-tag">Jum</span>
                    </div>
                    <div class="doc-str"><i class="fas fa-id-card" style="color:var(--teal2)"></i> STR: 3501-2-5-2018-001234</div>
                </div>
            </div>
            <div class="doc-card reveal delay-4">
                <div class="doc-top">
                    <div class="doc-avatar">👩‍⚕️</div>
                    <div class="doc-poli-tag">Poli Bedah</div>
                </div>
                <div class="doc-body">
                    <div class="doc-name">dr. Maya Indah Lestari, Sp.B</div>
                    <div class="doc-spesialis">Dokter Spesialis Bedah Umum</div>
                    <div class="doc-jadwal">
                        <span class="dj-tag">Sen</span><span class="dj-tag">Sel</span><span class="dj-tag">Rab</span><span class="dj-tag">Kam</span><span class="dj-tag">Jum</span>
                    </div>
                    <div class="doc-str"><i class="fas fa-id-card" style="color:var(--teal2)"></i> STR: 3501-2-4-2021-000677</div>
                </div>
            </div>
            <div class="doc-card reveal delay-5">
                <div class="doc-top">
                    <div class="doc-avatar">👨‍⚕️</div>
                    <div class="doc-poli-tag">Poli Jantung</div>
                </div>
                <div class="doc-body">
                    <div class="doc-name">dr. Rudi Pratama, Sp.JP</div>
                    <div class="doc-spesialis">Dokter Spesialis Jantung</div>
                    <div class="doc-jadwal">
                        <span class="dj-tag">Sen</span><span class="dj-tag">Sel</span><span class="dj-tag">Rab</span><span class="dj-tag">Kam</span><span class="dj-tag">Jum</span>
                    </div>
                    <div class="doc-str"><i class="fas fa-id-card" style="color:var(--teal2)"></i> STR: 3501-2-6-2019-000312</div>
                </div>
            </div>
            <div class="doc-card reveal delay-6">
                <div class="doc-top">
                    <div class="doc-avatar">👩‍⚕️</div>
                    <div class="doc-poli-tag">Poli Umum</div>
                </div>
                <div class="doc-body">
                    <div class="doc-name">dr. Dewi Kusuma</div>
                    <div class="doc-spesialis">Dokter Umum</div>
                    <div class="doc-jadwal">
                        <span class="dj-tag">Sen</span><span class="dj-tag">Sel</span><span class="dj-tag">Rab</span><span class="dj-tag">Kam</span><span class="dj-tag">Jum</span>
                    </div>
                    <div class="doc-str"><i class="fas fa-id-card" style="color:var(--teal2)"></i> STR: 3501-1-1-2022-002145</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ TENTANG RS ═══════════════════════════ -->
<section class="sec-full about-section">
    <div class="sec-inner">
        <div class="about-grid">
            <div class="reveal-left">
                <div class="sec-tag about-tag">Tentang Kami</div>
                <h2 class="about-title">RS Cahya Medika<br>Untuk Bondowoso Sehat</h2>
                <p class="about-desc">
                    RS Cahya Medika Bondowoso hadir sebagai rumah sakit swasta modern yang berkomitmen memberikan layanan kesehatan berkualitas tinggi. Dilengkapi dengan fasilitas medis terkini dan tenaga medis berpengalaman.
                </p>
                <div class="about-list">
                    <div class="al-item">
                        <div class="al-icon">🏥</div>
                        <div class="al-text"><strong>Fasilitas Modern:</strong> Gedung baru dengan peralatan medis terkini, ruang rawat yang nyaman, dan lingkungan yang bersih.</div>
                    </div>
                    <div class="al-item">
                        <div class="al-icon">🔗</div>
                        <div class="al-text"><strong>SatuSehat Ready:</strong> Sistem informasi rumah sakit terintegrasi penuh dengan platform SatuSehat Kemenkes RI.</div>
                    </div>
                    <div class="al-item">
                        <div class="al-icon">🚑</div>
                        <div class="al-text"><strong>IGD 24 Jam:</strong> Instalasi Gawat Darurat beroperasi 24 jam sehari, 7 hari seminggu untuk penanganan darurat.</div>
                    </div>
                    <div class="al-item">
                        <div class="al-icon">📱</div>
                        <div class="al-text"><strong>Digitalisasi Penuh:</strong> Dari pendaftaran online hingga rekam medis digital — semua serba mudah dan paperless.</div>
                    </div>
                </div>
                <div class="satusehat-badge" style="margin-top:28px">
                    <i class="fas fa-shield-halved"></i> Terverifikasi Platform SatuSehat Kemenkes RI
                </div>
            </div>
            <div class="reveal-right">
                <div class="about-cards">
                    <div class="ab-card">
                        <div class="abc-icon">🚑</div>
                        <div>
                            <div class="abc-title">IGD 24 Jam Siaga</div>
                            <div class="abc-sub">Penanganan gawat darurat sepanjang waktu dengan tim medis terlatih</div>
                        </div>
                    </div>
                    <div class="ab-card">
                        <div class="abc-icon">🧪</div>
                        <div>
                            <div class="abc-title">Laboratorium Klinik</div>
                            <div class="abc-sub">Pemeriksaan laboratorium lengkap dengan hasil cepat dan akurat</div>
                        </div>
                    </div>
                    <div class="ab-card">
                        <div class="abc-icon">🔬</div>
                        <div>
                            <div class="abc-title">Radiologi & Imaging</div>
                            <div class="abc-sub">X-Ray, USG, dan pemeriksaan pencitraan medis modern</div>
                        </div>
                    </div>
                    <div class="ab-card">
                        <div class="abc-icon">💊</div>
                        <div>
                            <div class="abc-title">Apotek Rumah Sakit</div>
                            <div class="abc-sub">Farmasi lengkap dengan obat-obatan berstandar BPOM dan BPJS</div>
                        </div>
                    </div>
                    <div class="ab-card">
                        <div class="abc-icon">🏋️</div>
                        <div>
                            <div class="abc-title">Fisioterapi & Rehabilitasi</div>
                            <div class="abc-sub">Program pemulihan dan rehabilitasi medis komprehensif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ TARIF ═══════════════════════════ -->
<section id="tarif">
    <div class="sec">
        <div class="sec-head reveal" style="text-align:center;max-width:none">
            <div class="sec-tag" style="margin:0 auto 14px">Informasi Biaya</div>
            <h2 class="sec-title" style="text-align:center">Tarif Layanan</h2>
            <p class="sec-sub" style="margin:0 auto;text-align:center">Biaya konsultasi yang transparan dan terjangkau. RS Cahya Medika melayani pasien umum (non-BPJS) dengan tarif yang kompetitif.</p>
        </div>
        <div class="tarif-grid">
            <div class="tarif-card reveal delay-1">
                <div class="tarif-icon">🏥</div>
                <div class="tarif-name">Poli Umum</div>
                <div class="tarif-price">Rp 100.000 <span>/ konsultasi</span></div>
                <div class="tarif-desc">Konsultasi dokter umum untuk keluhan ringan hingga sedang</div>
                <ul class="tarif-list">
                    <li>Konsultasi dokter umum</li>
                    <li>Pemeriksaan fisik dasar</li>
                    <li>Resep obat elektronik</li>
                    <li>Rekam medis digital</li>
                </ul>
            </div>
            <div class="tarif-card featured reveal delay-2">
                <div class="tarif-badge">Terpopuler</div>
                <div class="tarif-icon">👨‍⚕️</div>
                <div class="tarif-name">Poli Spesialis</div>
                <div class="tarif-price">Rp 200.000 <span>/ konsultasi</span></div>
                <div class="tarif-desc">Konsultasi dengan dokter spesialis pilihan Anda</div>
                <ul class="tarif-list">
                    <li>Konsultasi dokter spesialis</li>
                    <li>Pemeriksaan fisik lengkap</li>
                    <li>Interpretasi hasil laboratorium</li>
                    <li>Resep & rekam medis digital</li>
                    <li>Kirim data ke SatuSehat</li>
                </ul>
            </div>
            <div class="tarif-card reveal delay-3">
                <div class="tarif-icon">🚑</div>
                <div class="tarif-name">IGD / Gawat Darurat</div>
                <div class="tarif-price">Mulai Rp 150.000 <span>/ kasus</span></div>
                <div class="tarif-desc">Penanganan gawat darurat 24 jam oleh tim medis terlatih</div>
                <ul class="tarif-list">
                    <li>Triage & penanganan awal</li>
                    <li>Observasi medis</li>
                    <li>Tindakan medis darurat</li>
                    <li>Koordinasi rawat inap bila perlu</li>
                </ul>
            </div>
        </div>
        <div class="reveal" style="margin-top:24px;background:#fffbeb;border:1px solid #fde68a;border-radius:16px;padding:18px 24px;display:flex;align-items:flex-start;gap:14px">
            <div style="font-size:22px;flex-shrink:0">⚠️</div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#92400e;margin-bottom:4px">Catatan Penting — Layanan Non-BPJS</div>
                <div style="font-size:13px;color:#b45309;line-height:1.65">RS Cahya Medika Bondowoso adalah rumah sakit swasta yang baru berdiri dan <strong>belum bekerjasama dengan BPJS Kesehatan</strong>. Seluruh layanan merupakan layanan berbayar mandiri. Biaya di atas dapat berubah sesuai tindakan dan pemeriksaan tambahan. Untuk informasi lebih lanjut, hubungi kami di <strong>0332-123456</strong>.</div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ JAM OPERASIONAL ═══════════════════════════ -->
<section class="sec-full" style="background:#f8fafc">
    <div class="sec-inner">
        <div class="sec-head reveal" style="text-align:center;max-width:none">
            <div class="sec-tag" style="margin:0 auto 14px">Jam Layanan</div>
            <h2 class="sec-title" style="text-align:center">Jam Operasional</h2>
            <p class="sec-sub" style="margin:0 auto;text-align:center">Kami siap melayani Anda di jam-jam berikut. IGD kami beroperasi 24 jam tanpa henti.</p>
        </div>
        <div class="jam-grid">
            <div class="jam-card reveal delay-1">
                <div class="jc-icon">🚑</div>
                <div>
                    <div class="jc-title">IGD (Instalasi Gawat Darurat)</div>
                    <div class="jc-time">Setiap hari, termasuk hari libur</div>
                    <div class="jc-badge always">24 Jam / 7 Hari</div>
                </div>
            </div>
            <div class="jam-card reveal delay-2">
                <div class="jc-icon">🏥</div>
                <div>
                    <div class="jc-title">Poli Rawat Jalan (Umum)</div>
                    <div class="jc-time">Senin – Jumat · 07:30 – 14:00 WIB</div>
                    <div class="jc-badge open">Buka Hari Kerja</div>
                </div>
            </div>
            <div class="jam-card reveal delay-3">
                <div class="jc-icon">👨‍⚕️</div>
                <div>
                    <div class="jc-title">Poli Spesialis</div>
                    <div class="jc-time">Senin – Jumat · 08:00 – 12:00 WIB</div>
                    <div class="jc-badge open">Buka Hari Kerja</div>
                </div>
            </div>
            <div class="jam-card reveal delay-4">
                <div class="jc-icon">💊</div>
                <div>
                    <div class="jc-title">Apotek & Administrasi</div>
                    <div class="jc-time">Senin – Sabtu · 07:00 – 21:00 WIB</div>
                    <div class="jc-badge open">Buka Luas</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ KONTAK ═══════════════════════════ -->
<section id="kontak" class="sec-full kontak-section">
    <div class="sec-inner">
        <div class="sec-head reveal">
            <div class="sec-tag">Hubungi Kami</div>
            <h2 class="sec-title">Informasi Kontak<br>& Lokasi</h2>
            <p class="sec-sub">Kami siap membantu Anda. Hubungi kami melalui telepon, email, atau kunjungi langsung RS Cahya Medika Bondowoso.</p>
        </div>
        <div class="kontak-grid">
            <div class="kontak-items">
                <div class="kontak-item reveal delay-1">
                    <div class="ki-icon">📍</div>
                    <div>
                        <div class="ki-label">Alamat</div>
                        <div class="ki-value">Jl. Mastrip, Bondowoso</div>
                        <div class="ki-sub">Kabupaten Bondowoso, Jawa Timur 68211</div>
                    </div>
                </div>
                <div class="kontak-item reveal delay-2">
                    <div class="ki-icon">📞</div>
                    <div>
                        <div class="ki-label">Telepon & IGD</div>
                        <div class="ki-value">0332-123456</div>
                        <div class="ki-sub">IGD: 0332-123457 (24 jam)</div>
                    </div>
                </div>
                <div class="kontak-item reveal delay-3">
                    <div class="ki-icon">✉️</div>
                    <div>
                        <div class="ki-label">Email</div>
                        <div class="ki-value">info@rscahyamedika.co.id</div>
                        <div class="ki-sub">Untuk pertanyaan umum dan informasi</div>
                    </div>
                </div>
                <div class="kontak-item reveal delay-4">
                    <div class="ki-icon">💬</div>
                    <div>
                        <div class="ki-label">WhatsApp</div>
                        <div class="ki-value">0812-3456-7890</div>
                        <div class="ki-sub">Pertanyaan pendaftaran & informasi poli</div>
                    </div>
                </div>
                <div class="kontak-item reveal delay-5">
                    <div class="ki-icon">🔗</div>
                    <div>
                        <div class="ki-label">SatuSehat Organization ID</div>
                        <div class="ki-value" style="font-size:12px;font-family:monospace">3f912d5b-8aaf-47c5-baa6-8769247b4b89</div>
                        <div class="ki-sub">Verifikasi di platform SatuSehat Kemenkes</div>
                    </div>
                </div>
            </div>
            <div class="reveal-right">
                <div class="map-placeholder">
                    <div class="map-icon">🗺️</div>
                    <div class="map-text">RS Cahya Medika Bondowoso</div>
                    <div class="map-sub">Jl. Mastrip, Bondowoso, Jawa Timur</div>
                    <div class="map-pin">
                        <i class="fas fa-map-marker-alt"></i>
                        Kabupaten Bondowoso, Jawa Timur 68211
                    </div>
                    <div style="margin-top:20px;display:flex;gap:12px;position:relative;z-index:1;flex-wrap:wrap;justify-content:center">
                        <a href="https://maps.google.com/?q=Bondowoso,Jawa+Timur" target="_blank"
                           style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);color:#fff;padding:10px 18px;border-radius:10px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:7px">
                            <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                        </a>
                        <a href="tel:0332123456"
                           style="background:var(--teal);color:#fff;padding:10px 18px;border-radius:10px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:7px">
                            <i class="fas fa-phone"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ CTA FINAL ═══════════════════════════ -->
<section class="cta-section">
    <div class="cta-inner">
        <div class="reveal">
            <div class="sec-tag" style="background:rgba(6,182,212,0.2);color:#67e8f9;border:1px solid rgba(6,182,212,0.3);margin:0 auto 20px">Mulai Sekarang</div>
        </div>
        <h2 class="cta-title reveal">Jaga Kesehatan Anda<br>Bersama Kami</h2>
        <p class="cta-sub reveal">Daftar sekarang dan nikmati kemudahan pendaftaran berobat online. Data Anda aman, proses cepat, dan dokter siap membantu.</p>
        <div class="cta-btns reveal">
            <a href="{{ route('register') }}" class="btn-cta-white">
                <i class="fas fa-user-plus"></i> Daftar Pasien Baru
            </a>
            <a href="{{ route('login') }}" class="btn-cta-outline">
                <i class="fas fa-sign-in-alt"></i> Sudah Punya Akun
            </a>
        </div>
    </div>
</section>

<!-- ════════════════════════════ FOOTER ═══════════════════════════ -->
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <!-- Brand -->
            <div class="ft-brand">
                <div class="logo">
                    <div class="ft-logo-box">🏥</div>
                    <div>
                        <div class="ft-name">RS Cahya Medika</div>
                        <div class="ft-sub">Bondowoso · Rumah Sakit Swasta</div>
                    </div>
                </div>
                <p class="ft-desc">Rumah sakit swasta modern yang berkomitmen memberikan layanan kesehatan berkualitas tinggi dengan teknologi digital terkini di Bondowoso, Jawa Timur.</p>
                <div class="ft-socials">
                    <a href="#" class="ft-social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="ft-social"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="ft-social"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="ft-social"><i class="fab fa-youtube"></i></a>
                </div>
                <div class="satusehat-badge" style="margin-top:16px">
                    <i class="fas fa-shield-halved"></i> Terintegrasi SatuSehat Kemenkes RI
                </div>
            </div>

            <!-- Layanan -->
            <div>
                <div class="ft-col-title">Layanan Poli</div>
                <div class="ft-links">
                    <a href="#layanan">Poli Umum</a>
                    <a href="#layanan">Poli Anak</a>
                    <a href="#layanan">Penyakit Dalam</a>
                    <a href="#layanan">Kebidanan & Kandungan</a>
                    <a href="#layanan">Poli Bedah</a>
                    <a href="#layanan">Poli Jantung</a>
                    <a href="#layanan">Poli Saraf</a>
                    <a href="#layanan">Poli Mata</a>
                </div>
            </div>

            <!-- Informasi -->
            <div>
                <div class="ft-col-title">Informasi</div>
                <div class="ft-links">
                    <a href="#tentang">Tentang RS</a>
                    <a href="#cara-daftar">Cara Daftar</a>
                    <a href="#tarif">Tarif Layanan</a>
                    <a href="#dokter">Tim Dokter</a>
                    <a href="#kontak">Hubungi Kami</a>
                    <a href="{{ route('register') }}">Daftar Pasien</a>
                    <a href="{{ route('login') }}">Login Akun</a>
                </div>
            </div>

            <!-- Kontak -->
            <div>
                <div class="ft-col-title">Kontak Kami</div>
                <div class="ft-contact-item"><i class="fas fa-map-marker-alt"></i><span>Jl. Mastrip, Bondowoso<br>Jawa Timur 68211</span></div>
                <div class="ft-contact-item"><i class="fas fa-phone"></i><span>0332-123456</span></div>
                <div class="ft-contact-item"><i class="fas fa-ambulance"></i><span>IGD: 0332-123457 (24 Jam)</span></div>
                <div class="ft-contact-item"><i class="fas fa-envelope"></i><span>info@rscahyamedika.co.id</span></div>
                <div class="ft-contact-item"><i class="fab fa-whatsapp"></i><span>0812-3456-7890</span></div>
                <div class="ft-contact-item" style="margin-top:16px"><i class="fas fa-clock"></i><span>Poli: Senin–Jumat 07:30–14:00<br>IGD: 24 Jam / 7 Hari</span></div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} RS Cahya Medika Bondowoso. Hak Cipta Dilindungi.</span>
            <div class="fb-right">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to top -->
<button id="backTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Kembali ke atas">
    <i class="fas fa-chevron-up"></i>
</button>

<script>
// ── Navbar scroll effect ───────────────────────────────────
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
    document.getElementById('backTop').classList.toggle('show', window.scrollY > 400);
});

// ── Mobile menu ────────────────────────────────────────────
function toggleMenu() {
    const m = document.getElementById('navMobile');
    const i = document.getElementById('menuIcon');
    const open = m.classList.toggle('open');
    i.className = open ? 'fas fa-times' : 'fas fa-bars';
}
function closeMenu() {
    document.getElementById('navMobile').classList.remove('open');
    document.getElementById('menuIcon').className = 'fas fa-bars';
}

// ── Scroll reveal ──────────────────────────────────────────
const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            io.unobserve(e.target);
        }
    });
}, { threshold: 0.1 });
revealEls.forEach(el => io.observe(el));

// ── Smooth anchor scrolling ────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const id = a.getAttribute('href').slice(1);
        const el = document.getElementById(id);
        if (el) {
            e.preventDefault();
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
</body>
</html>
