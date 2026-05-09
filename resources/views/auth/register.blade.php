<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Pasien Baru — RS Cahya Medika</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ── Base ───────────────────────────────────────────────── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
    --navy:  #0c4a6e;
    --teal:  #06b6d4;
    --teal2: #0891b2;
    --green: #059669;
    --red:   #dc2626;
    --gray:  #64748b;
    --light: #f8fafc;
    --border:#e2e8f0;
    --dark:  #1e293b;
}
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f1f5f9;
    min-height: 100vh;
    display: flex;
    color: var(--dark);
}

/* ── Layout ─────────────────────────────────────────────── */
.page-left {
    width: 400px;
    min-height: 100vh;
    background: linear-gradient(160deg, #071e2d 0%, #0c4a6e 55%, #0e7490 100%);
    position: sticky;
    top: 0;
    height: 100vh;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    padding: 40px 36px;
}
.page-left::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 24px 24px;
}
.left-blob1 {
    position: absolute;
    top: -120px; right: -100px;
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(6,182,212,0.2) 0%, transparent 65%);
    border-radius: 50%;
}
.left-blob2 {
    position: absolute;
    bottom: -150px; left: -100px;
    width: 340px; height: 340px;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 65%);
    border-radius: 50%;
}
.left-inner { position: relative; z-index: 1; flex: 1; display: flex; flex-direction: column; }

/* Left — Brand */
.left-brand {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 52px;
}
.brand-logo {
    width: 44px; height: 44px;
    background: var(--teal);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.brand-name  { font-size: 15px; font-weight: 800; color: #fff; line-height: 1.2; }
.brand-sub   { font-size: 10px; color: rgba(255,255,255,0.45); }

/* Left — Headline */
.left-headline {
    font-family: 'Playfair Display', serif;
    font-size: 30px;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 10px;
}
.left-headline em { color: #67e8f9; font-style: italic; }
.left-sub {
    font-size: 13px;
    color: rgba(255,255,255,0.55);
    line-height: 1.7;
    margin-bottom: 36px;
}

/* Left — Benefits */
.benefit-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 36px; }
.benefit-item { display: flex; align-items: flex-start; gap: 12px; }
.bi-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: rgba(6,182,212,0.18);
    border: 1px solid rgba(6,182,212,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.bi-title { font-size: 13px; font-weight: 700; color: #fff; line-height: 1.3; }
.bi-sub   { font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 2px; }

/* Left — SatuSehat badge */
.ss-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(6,182,212,0.15);
    border: 1px solid rgba(6,182,212,0.3);
    color: #a5f3fc;
    padding: 9px 16px; border-radius: 10px;
    font-size: 11px; font-weight: 700;
    margin-bottom: auto;
}

/* Left — Footer */
.left-footer {
    font-size: 11px;
    color: rgba(255,255,255,0.3);
    margin-top: 32px;
    line-height: 1.6;
}
.left-footer a { color: rgba(255,255,255,0.45); text-decoration: none; }
.left-footer a:hover { color: rgba(255,255,255,0.7); }

/* ── Right Panel (form) ─────────────────────────────────── */
.page-right {
    flex: 1;
    background: #fff;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 48px 48px 60px;
}
.form-container { width: 100%; max-width: 620px; }

/* Form header */
.form-header { margin-bottom: 32px; }
.form-header h1 {
    font-size: 26px;
    font-weight: 900;
    color: var(--navy);
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}
.form-header p { font-size: 14px; color: var(--gray); }

/* Steps indicator */
.steps-bar {
    display: flex;
    align-items: center;
    margin-bottom: 36px;
    padding: 16px 20px;
    background: #f8fafc;
    border-radius: 14px;
    border: 1px solid var(--border);
}
.step-dot {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
}
.sd-circle {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800;
    flex-shrink: 0; transition: all 0.3s;
}
.sd-circle.active { background: var(--navy); color: #fff; }
.sd-circle.done   { background: var(--green); color: #fff; }
.sd-circle.idle   { background: #e2e8f0; color: #94a3b8; }
.sd-label { font-size: 11px; font-weight: 700; }
.sd-label.active { color: var(--navy); }
.sd-label.done   { color: var(--green); }
.sd-label.idle   { color: #94a3b8; }
.step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 8px; }
.step-line.done { background: var(--green); }

/* Error box */
.error-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-left: 4px solid var(--red);
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 28px;
    font-size: 13px;
    color: #991b1b;
}
.error-box strong { display: flex; align-items: center; gap: 7px; margin-bottom: 6px; font-size: 13px; }
.error-box ul { margin-left: 20px; line-height: 1.8; }

/* Section divider */
.form-section { margin-bottom: 32px; }
.sec-divider {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 20px;
}
.sec-icon-box {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.sec-icon-box.blue  { background: #e0f2fe; color: var(--teal2); }
.sec-icon-box.green { background: #d1fae5; color: var(--green); }
.sec-icon-box.amber { background: #fef3c7; color: #d97706; }
.sec-title-text {
    font-size: 13px;
    font-weight: 800;
    color: var(--navy);
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
.sec-line { flex: 1; height: 1px; background: var(--border); }

/* Grid */
.g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

/* Form elements */
.fgroup { display: flex; flex-direction: column; gap: 6px; }
.flabel {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    display: flex; align-items: center; gap: 4px;
}
.req { color: var(--red); }

.input-wrap { position: relative; }
.input-ico {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8; font-size: 13px;
    pointer-events: none;
}
.finput {
    width: 100%;
    padding: 11px 14px 11px 38px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--dark);
    background: #fff;
    transition: all 0.2s;
    outline: none;
}
.finput.no-ico { padding-left: 14px; }
.finput.with-eye { padding-right: 42px; }
.finput:focus {
    border-color: var(--teal2);
    box-shadow: 0 0 0 3px rgba(8,145,178,0.1);
    background: #fafeff;
}
.finput.is-invalid { border-color: var(--red); box-shadow: 0 0 0 3px rgba(220,38,38,0.08); }
.finput:focus.is-invalid { box-shadow: 0 0 0 3px rgba(220,38,38,0.12); }

.fselect {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--dark);
    background: #fff;
    cursor: pointer;
    outline: none;
    transition: all 0.2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 18px;
    padding-right: 36px;
}
.fselect:focus { border-color: var(--teal2); box-shadow: 0 0 0 3px rgba(8,145,178,0.1); }

.pwd-toggle {
    position: absolute;
    right: 11px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #94a3b8; cursor: pointer;
    font-size: 14px; padding: 4px;
    transition: color 0.2s;
}
.pwd-toggle:hover { color: var(--teal2); }

/* Password strength */
.strength-wrap { margin-top: 7px; }
.strength-bars { display: flex; gap: 4px; margin-bottom: 4px; }
.sbar {
    flex: 1; height: 3px; border-radius: 3px;
    background: #e2e8f0; transition: background 0.3s;
}
.sbar.weak   { background: #ef4444; }
.sbar.medium { background: #f59e0b; }
.sbar.strong { background: var(--green); }
.strength-text { font-size: 10px; font-weight: 700; color: #94a3b8; }

/* Invalid message */
.inv-msg {
    font-size: 11px; color: var(--red);
    display: flex; align-items: center; gap: 4px;
    margin-top: 4px;
}

/* Agree box */
.agree-box {
    display: flex; align-items: flex-start; gap: 12px;
    background: #f8fafc;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 24px;
    cursor: pointer;
    transition: border-color 0.2s;
}
.agree-box:has(input:checked) { border-color: var(--teal2); background: #f0fdff; }
.agree-box input[type="checkbox"] {
    width: 17px; height: 17px;
    margin-top: 1px;
    accent-color: var(--teal2);
    flex-shrink: 0; cursor: pointer;
}
.agree-text { font-size: 12.5px; color: #475569; line-height: 1.65; }
.agree-text a { color: var(--teal2); font-weight: 700; text-decoration: none; }
.agree-text a:hover { text-decoration: underline; }

/* Submit button */
.btn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--navy) 0%, var(--teal2) 100%);
    color: #fff;
    border: none; border-radius: 12px;
    font-size: 15px; font-weight: 800;
    font-family: inherit;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    transition: all 0.3s;
    box-shadow: 0 4px 16px rgba(8,145,178,0.3);
    letter-spacing: 0.2px;
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(8,145,178,0.4);
}
.btn-submit:active { transform: translateY(0); }

/* Footer links */
.form-foot {
    text-align: center;
    margin-top: 20px;
    font-size: 13px;
    color: var(--gray);
}
.form-foot a { color: var(--teal2); font-weight: 700; text-decoration: none; }
.form-foot a:hover { text-decoration: underline; }

.back-link {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 12px;
    font-size: 12px; color: #94a3b8;
    text-decoration: none;
    transition: color 0.2s;
}
.back-link:hover { color: var(--gray); }

/* Info non-bpjs bar */
.info-bar {
    display: flex; align-items: flex-start; gap: 12px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 10px;
    padding: 13px 16px;
    margin-bottom: 28px;
    font-size: 12px; color: #92400e; line-height: 1.6;
}
.info-bar i { margin-top: 2px; flex-shrink: 0; color: #f59e0b; }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 900px) {
    .page-left { display: none; }
    .page-right { padding: 32px 24px 48px; }
}
@media (max-width: 480px) {
    .g2, .g3 { grid-template-columns: 1fr; }
    .steps-bar { display: none; }
}
</style>
</head>
<body>

<!-- ═══════════════════ LEFT PANEL ═══════════════════ -->
<div class="page-left">
    <div class="left-blob1"></div>
    <div class="left-blob2"></div>
    <div class="left-inner">

        <!-- Brand -->
        <div class="left-brand">
            <div class="brand-logo">🏥</div>
            <div>
                <div class="brand-name">RS Cahya Medika</div>
                <div class="brand-sub">Bondowoso · Rumah Sakit Swasta</div>
            </div>
        </div>

        <!-- Headline -->
        <h2 class="left-headline">
            Bergabung &<br>Nikmati Layanan<br><em>Kesehatan Modern</em>
        </h2>
        <p class="left-sub">Buat akun pasien gratis dan daftar berobat kapan saja, dari mana saja dalam hitungan menit.</p>

        <!-- Benefits -->
        <div class="benefit-list">
            <div class="benefit-item">
                <div class="bi-icon">📱</div>
                <div>
                    <div class="bi-title">Pendaftaran Online 24/7</div>
                    <div class="bi-sub">Daftar berobat tanpa perlu datang ke loket</div>
                </div>
            </div>
            <div class="benefit-item">
                <div class="bi-icon">📋</div>
                <div>
                    <div class="bi-title">Rekam Medis Digital</div>
                    <div class="bi-sub">Riwayat kunjungan & diagnosa tersimpan otomatis</div>
                </div>
            </div>
            <div class="benefit-item">
                <div class="bi-icon">⚡</div>
                <div>
                    <div class="bi-title">Pantau Antrian Real-time</div>
                    <div class="bi-sub">Tidak perlu menunggu lama di ruang tunggu</div>
                </div>
            </div>
            <div class="benefit-item">
                <div class="bi-icon">🔗</div>
                <div>
                    <div class="bi-title">Terintegrasi SatuSehat</div>
                    <div class="bi-sub">Data terhubung ke sistem nasional Kemenkes RI</div>
                </div>
            </div>
        </div>

        <!-- SatuSehat Badge -->
        <div class="ss-badge">
            <i class="fas fa-shield-halved" style="font-size:13px"></i>
            Terverifikasi Platform SatuSehat Kemenkes RI
        </div>

        <!-- Footer -->
        <div class="left-footer">
            © {{ date('Y') }} RS Cahya Medika Bondowoso<br>
            <a href="{{ route('home') }}">Beranda</a> &nbsp;·&nbsp;
            <a href="#">Kebijakan Privasi</a> &nbsp;·&nbsp;
            <a href="#">Syarat & Ketentuan</a>
        </div>
    </div>
</div>

<!-- ═══════════════════ RIGHT PANEL (FORM) ═══════════════════ -->
<div class="page-right">
    <div class="form-container">

        <!-- Header -->
        <div class="form-header">
            <h1>Buat Akun Pasien</h1>
            <p>Isi data di bawah untuk mendaftar sebagai pasien RS Cahya Medika Bondowoso.</p>
        </div>

        <!-- Steps -->
        <div class="steps-bar">
            <div class="step-dot">
                <div class="sd-circle active">1</div>
                <span class="sd-label active">Data Diri</span>
            </div>
            <div class="step-line"></div>
            <div class="step-dot">
                <div class="sd-circle idle">2</div>
                <span class="sd-label idle">Akun</span>
            </div>
            <div class="step-line"></div>
            <div class="step-dot">
                <div class="sd-circle idle">3</div>
                <span class="sd-label idle">Konfirmasi</span>
            </div>
        </div>

        <!-- Error -->
        @if($errors->any())
        <div class="error-box">
            <strong><i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Non-BPJS Notice -->
        <div class="info-bar">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong style="display:block;margin-bottom:2px">Informasi Penting</strong>
                RS Cahya Medika Bondowoso saat ini melayani pasien umum <strong>(non-BPJS)</strong>. Seluruh layanan berbayar mandiri. Kami belum bekerjasama dengan BPJS Kesehatan.
            </div>
        </div>

        <form action="{{ route('register.post') }}" method="POST" id="regForm">
        @csrf

        <!-- ══ STEP 1: DATA DIRI ══ -->
        <div class="form-section">
            <div class="sec-divider">
                <div class="sec-icon-box blue"><i class="fas fa-user"></i></div>
                <span class="sec-title-text">Data Diri</span>
                <div class="sec-line"></div>
                <span style="font-size:11px;color:#94a3b8;white-space:nowrap">Langkah 1 dari 3</span>
            </div>

            <div class="g2" style="margin-bottom:14px">
                <div class="fgroup">
                    <label class="flabel">Nama Lengkap <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-user input-ico"></i>
                        <input type="text" name="nama_lengkap"
                            class="finput {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                            placeholder="Nama sesuai KTP" value="{{ old('nama_lengkap') }}" required>
                    </div>
                    @error('nama_lengkap')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
                </div>

                <div class="fgroup">
                    <label class="flabel">NIK (16 digit) <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-id-card input-ico"></i>
                        <input type="text" name="nik" maxlength="16"
                            class="finput {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                            placeholder="3511XXXXXXXXXXXX" value="{{ old('nik') }}"
                            required oninput="this.value=this.value.replace(/\D/g,'')">
                    </div>
                    @error('nik')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="g3">
                <div class="fgroup">
                    <label class="flabel">Tanggal Lahir <span class="req">*</span></label>
                    <input type="date" name="tanggal_lahir"
                        class="finput no-ico {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}"
                        value="{{ old('tanggal_lahir') }}"
                        max="{{ date('Y-m-d') }}" required>
                    @error('tanggal_lahir')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
                </div>

                <div class="fgroup">
                    <label class="flabel">Jenis Kelamin <span class="req">*</span></label>
                    <select name="jenis_kelamin"
                        class="fselect {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
                </div>

                <div class="fgroup">
                    <label class="flabel">No. HP <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-phone input-ico"></i>
                        <input type="tel" name="no_hp"
                            class="finput {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                            placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" required>
                    </div>
                    @error('no_hp')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- ══ STEP 2: AKUN ══ -->
        <div class="form-section">
            <div class="sec-divider">
                <div class="sec-icon-box green"><i class="fas fa-lock"></i></div>
                <span class="sec-title-text">Informasi Akun</span>
                <div class="sec-line"></div>
                <span style="font-size:11px;color:#94a3b8;white-space:nowrap">Langkah 2 dari 3</span>
            </div>

            <div class="fgroup" style="margin-bottom:14px">
                <label class="flabel">Alamat Email <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-ico"></i>
                    <input type="email" name="email"
                        class="finput {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="email@example.com" value="{{ old('email') }}" required>
                </div>
                @error('email')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
            </div>

            <div class="g2">
                <div class="fgroup">
                    <label class="flabel">Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-ico"></i>
                        <input type="password" name="password" id="pwd"
                            class="finput with-eye {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Min. 8 karakter" required
                            oninput="checkStrength(this.value)">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('pwd','icoP')" tabindex="-1">
                            <i class="fas fa-eye" id="icoP"></i>
                        </button>
                    </div>
                    <div class="strength-wrap">
                        <div class="strength-bars">
                            <div class="sbar" id="bar1"></div>
                            <div class="sbar" id="bar2"></div>
                            <div class="sbar" id="bar3"></div>
                        </div>
                        <div class="strength-text" id="strText"></div>
                    </div>
                    @error('password')<div class="inv-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
                </div>

                <div class="fgroup">
                    <label class="flabel">Konfirmasi Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-ico"></i>
                        <input type="password" name="password_confirmation" id="pwdConf"
                            class="finput with-eye"
                            placeholder="Ulangi password" required
                            oninput="checkMatch()">
                        <button type="button" class="pwd-toggle" onclick="togglePwd('pwdConf','icoC')" tabindex="-1">
                            <i class="fas fa-eye" id="icoC"></i>
                        </button>
                    </div>
                    <div class="strength-text" id="matchText" style="margin-top:7px"></div>
                </div>
            </div>
        </div>

        <!-- ══ STEP 3: KONFIRMASI ══ -->
        <div class="form-section">
            <div class="sec-divider">
                <div class="sec-icon-box amber"><i class="fas fa-check-circle"></i></div>
                <span class="sec-title-text">Konfirmasi</span>
                <div class="sec-line"></div>
                <span style="font-size:11px;color:#94a3b8;white-space:nowrap">Langkah 3 dari 3</span>
            </div>

            <label class="agree-box" for="agree">
                <input type="checkbox" name="agree" id="agree" {{ old('agree') ? 'checked' : '' }} required>
                <div class="agree-text">
                    Dengan mendaftar, saya menyetujui <a href="#" onclick="return false">Syarat & Ketentuan</a> serta
                    <a href="#" onclick="return false">Kebijakan Privasi</a> RS Cahya Medika Bondowoso.
                    Data saya akan diproses sesuai ketentuan yang berlaku dan dapat terhubung ke platform
                    <strong>SatuSehat Kemenkes RI</strong>.
                </div>
            </label>
            @error('agree')<div class="inv-msg" style="margin-bottom:12px"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>@enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit" id="btnSubmit">
            <i class="fas fa-user-plus"></i>
            Buat Akun & Daftar Sekarang
        </button>

        </form>

        <!-- Footer links -->
        <div class="form-foot">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini →</a>
        </div>
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left" style="font-size:11px"></i> Kembali ke Beranda
        </a>

    </div>
</div>

<script>
/* ── Toggle password visibility ─────────────── */
function togglePwd(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

/* ── Password strength ───────────────────────── */
function checkStrength(val) {
    const bars = [
        document.getElementById('bar1'),
        document.getElementById('bar2'),
        document.getElementById('bar3'),
    ];
    const text = document.getElementById('strText');
    bars.forEach(b => (b.className = 'sbar'));

    if (!val) { text.textContent = ''; return; }

    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { cls: 'weak',   label: 'Lemah',  color: '#ef4444',  bars: 1 },
        { cls: 'medium', label: 'Sedang', color: '#f59e0b',  bars: 2 },
        { cls: 'strong', label: 'Kuat',   color: '#059669',  bars: 3 },
    ];
    const lvl = levels[Math.min(score, 2)];
    for (let i = 0; i < lvl.bars; i++) bars[i].classList.add(lvl.cls);
    text.textContent = lvl.label;
    text.style.color = lvl.color;
    text.style.fontWeight = '700';
}

/* ── Confirm password match ──────────────────── */
function checkMatch() {
    const pw1  = document.getElementById('pwd').value;
    const pw2  = document.getElementById('pwdConf').value;
    const txt  = document.getElementById('matchText');
    if (!pw2) { txt.textContent = ''; return; }
    if (pw1 === pw2) {
        txt.textContent = '✓ Password cocok';
        txt.style.color = '#059669';
        txt.style.fontWeight = '700';
    } else {
        txt.textContent = '✗ Password tidak cocok';
        txt.style.color = '#ef4444';
        txt.style.fontWeight = '700';
    }
}

/* ── Submit loading state ────────────────────── */
document.getElementById('regForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
});
</script>
</body>
</html>
