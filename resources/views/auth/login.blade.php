<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RS Cahya Medika Bondowoso</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #f8fafc;
        }

        /* LEFT PANEL */
        .left-panel {
            background: linear-gradient(160deg, #0c4a6e 0%, #0f5e8f 50%, #0891b2 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        .left-blob {
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(6,182,212,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }

        .left-content {
            position: relative;
            z-index: 1;
        }

        .left-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 60px;
        }

        .logo-box {
            width: 48px; height: 48px;
            background: #06b6d4;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        .logo-text .name { color: white; font-size: 16px; font-weight: 800; }
        .logo-text .sub { color: rgba(255,255,255,0.5); font-size: 11px; }

        .left-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            line-height: 1.15;
            color: white;
            margin-bottom: 18px;
        }

        .left-title .accent { color: #67e8f9; }

        .left-desc {
            font-size: 15px;
            color: rgba(255,255,255,0.65);
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .ss-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6,182,212,0.2);
            border: 1px solid rgba(6,182,212,0.35);
            color: #a5f3fc;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* RIGHT PANEL */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-header h1 {
            font-size: 26px;
            font-weight: 800;
            color: #0c4a6e;
            margin-bottom: 6px;
        }

        .form-header p {
            font-size: 14px;
            color: #64748b;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 14px 16px;
            color: #991b1b;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            color: #1e293b;
            background: white;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 4px rgba(6,182,212,0.1);
        }

        .form-input.is-invalid { border-color: #dc2626; }
        .invalid-msg { color: #dc2626; font-size: 11px; margin-top: 4px; }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #475569;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0c4a6e, #0891b2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(12,74,110,0.3);
        }

        .form-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 13px;
            color: #64748b;
        }

        .form-footer a {
            color: #0891b2;
            font-weight: 700;
            text-decoration: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #94a3b8;
            font-size: 12px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .demo-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 12px;
            color: #0369a1;
        }

        .demo-info strong { display: block; margin-bottom: 6px; }
        .demo-info code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .left-panel { display: none; }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="left-blob"></div>
        <div class="left-content">
            <div class="left-logo">
                <div class="logo-box">🏥</div>
                <div class="logo-text">
                    <div class="name">RS Cahya Medika</div>
                    <div class="sub">Bondowoso · Rumah Sakit Swasta</div>
                </div>
            </div>

            <h2 class="left-title">Selamat Datang<br>di Portal <span class="accent">Pasien</span></h2>
            <p class="left-desc">
                Akses layanan pendaftaran online, riwayat kunjungan, dan informasi kesehatan Anda dengan mudah dan aman.
            </p>

            <div class="ss-badge">
                <i class="fas fa-shield-check"></i>
                Data terintegrasi dengan SatuSehat Kemenkes RI
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-box">
            <div class="form-header">
                <h1>Masuk ke Akun</h1>
                <p>Masukkan email dan password Anda untuk melanjutkan</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div style="background:#d1fae5;border-radius:12px;padding:14px 16px;color:#065f46;font-size:13px;margin-bottom:24px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            placeholder="email@example.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <div class="invalid-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember"> Ingat Saya
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk Sekarang
                </button>
            </form>

            <div class="divider">atau</div>

            <div class="demo-info">
                <strong>🔑 Akun Demo:</strong>
                <div style="margin-bottom:4px"><strong>Admin:</strong> admin@rscahyamedika.co.id | <code>admin123</code></div>
                <div><strong>Pasien:</strong> pasien@demo.com | <code>pasien123</code></div>
            </div>

            <div class="form-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang →</a>
            </div>

            <div style="text-align:center;margin-top:16px">
                <a href="{{ route('home') }}" style="color:#94a3b8;font-size:12px;text-decoration:none">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
