<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RS Cahya Medika Bondowoso</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:grid;grid-template-columns:1fr 1fr;background:#f4f6f9}

        /* LEFT */
        .left{background:linear-gradient(160deg,#1e293b 0%,#1d4ed8 60%,#0891b2 100%);position:relative;display:flex;flex-direction:column;justify-content:center;padding:60px;overflow:hidden}
        .left::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:26px 26px}
        .left-blob{position:absolute;bottom:-80px;right:-80px;width:360px;height:360px;background:radial-gradient(circle,rgba(8,145,178,0.2) 0%,transparent 70%);border-radius:50%}
        .left-content{position:relative;z-index:1}
        .left-logo{display:flex;align-items:center;gap:12px;margin-bottom:56px}
        .logo-icon{width:42px;height:42px;background:#1d4ed8;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:19px;border:2px solid rgba(255,255,255,0.2)}
        .logo-text .name{color:white;font-size:15px;font-weight:700}
        .logo-text .sub{color:rgba(255,255,255,0.45);font-size:11px;margin-top:1px}
        .left h2{font-size:38px;line-height:1.2;color:white;margin-bottom:14px;font-weight:800}
        .left h2 span{color:#67e8f9}
        .left p{font-size:14px;color:rgba(255,255,255,0.6);line-height:1.7;margin-bottom:36px}
        .ss-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(8,145,178,0.2);border:1px solid rgba(8,145,178,0.35);color:#a5f3fc;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600}

        /* RIGHT */
        .right{display:flex;align-items:center;justify-content:center;padding:40px}
        .form-box{width:100%;max-width:390px}
        .form-header{margin-bottom:32px}
        .form-header h1{font-size:24px;font-weight:800;color:#111827;margin-bottom:5px}
        .form-header p{font-size:13px;color:#6b7280}

        .alert-error{background:#fef2f2;border:1px solid #fecaca;border-radius:9px;padding:12px 14px;color:#991b1b;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px}
        .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:9px;padding:12px 14px;color:#166534;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px}

        .fg{margin-bottom:18px}
        .fg label{display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px}
        .input-wrap{position:relative}
        .input-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px}
        .fg input{width:100%;padding:10px 12px 10px 38px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;font-family:inherit;color:#111827;background:white;transition:border-color 0.15s}
        .fg input:focus{outline:none;border-color:#1d4ed8;box-shadow:0 0 0 3px rgba(29,78,216,0.08)}
        .fg input.is-invalid{border-color:#dc2626}
        .invalid-msg{color:#dc2626;font-size:11px;margin-top:3px}
        .pwd-eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;cursor:pointer;font-size:13px;border:none;background:none;padding:4px}
        .pwd-eye:hover{color:#1d4ed8}

        .remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
        .remember-label{display:flex;align-items:center;gap:7px;font-size:13px;color:#374151;cursor:pointer}

        .btn-submit{width:100%;padding:12px;background:linear-gradient(135deg,#1e293b,#1d4ed8);color:white;border:none;border-radius:9px;font-size:14px;font-weight:700;font-family:inherit;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:9px}
        .btn-submit:hover{opacity:0.9;transform:translateY(-1px);box-shadow:0 6px 20px rgba(29,78,216,0.25)}

        .divider{display:flex;align-items:center;gap:10px;margin:22px 0;color:#9ca3af;font-size:12px}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e5e7eb}

        .demo-box{background:#eff6ff;border:1px solid #bfdbfe;border-radius:9px;padding:13px 14px;font-size:12px;color:#1e40af}
        .demo-box strong{display:block;margin-bottom:6px;font-size:12px}
        code{background:white;padding:2px 6px;border-radius:4px;font-size:11px;border:1px solid #dbeafe}

        .form-footer{text-align:center;margin-top:22px;font-size:13px;color:#6b7280}
        .form-footer a{color:#1d4ed8;font-weight:600;text-decoration:none}
        .back-link{display:block;text-align:center;margin-top:14px;font-size:12px;color:#9ca3af;text-decoration:none}
        .back-link:hover{color:#374151}

        @media(max-width:768px){body{grid-template-columns:1fr}.left{display:none}}
    </style>
</head>
<body>
    <div class="left">
        <div class="left-blob"></div>
        <div class="left-content">
            <div class="left-logo">
                <div class="logo-icon">🏥</div>
                <div class="logo-text">
                    <div class="name">RS Cahya Medika</div>
                    <div class="sub">Bondowoso · Rumah Sakit Swasta</div>
                </div>
            </div>
            <h2>Selamat Datang<br>di Portal <span>Pasien</span></h2>
            <p>Akses layanan pendaftaran online, riwayat kunjungan, dan informasi kesehatan Anda dengan mudah dan aman.</p>
            <div class="ss-badge">
                <i class="fas fa-shield-check"></i>
                Data terintegrasi dengan SatuSehat Kemenkes RI
            </div>
        </div>
    </div>

    <div class="right">
        <div class="form-box">
            <div class="form-header">
                <h1>Masuk ke Akun</h1>
                <p>Masukkan email dan password Anda untuk melanjutkan</p>
            </div>

            @if($errors->any())
            <div class="alert-error"><i class="fas fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
            @endif

            @if(session('success'))
            <div class="alert-success"><i class="fas fa-circle-check"></i>{{ session('success') }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="fg">
                    <label>Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" class="{{ $errors->has('email')?'is-invalid':'' }}" placeholder="email@example.com" value="{{ old('email') }}" required>
                    </div>
                    @error('email')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="fg">
                    <label>Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="loginPwd" class="{{ $errors->has('password')?'is-invalid':'' }}" placeholder="••••••••" required style="padding-right:40px">
                        <button type="button" class="pwd-eye" onclick="togglePwd('loginPwd','loginPwdIcon')"><i class="fas fa-eye" id="loginPwdIcon"></i></button>
                    </div>
                    @error('password')<div class="invalid-msg">{{ $message }}</div>@enderror
                </div>
                <div class="remember-row">
                    <label class="remember-label"><input type="checkbox" name="remember"> Ingat Saya</label>
                </div>
                <button type="submit" class="btn-submit"><i class="fas fa-right-to-bracket"></i> Masuk Sekarang</button>
            </form>

            <div class="divider">atau</div>

            <div class="demo-box">
                <strong>🔑 Akun Demo:</strong>
                <div style="margin-bottom:4px"><strong>Staff Loket:</strong> pendaftaran@rscahyamedika.co.id | <code>admin123</code></div>
                <div><strong>Pasien:</strong> pasien@demo.com | <code>pasien123</code></div>
            </div>

            <div class="form-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang →</a></div>
            <a href="{{ route('home') }}" class="back-link">← Kembali ke Beranda</a>
        </div>
    </div>
<script>
function togglePwd(id, iconId){
    var inp=document.getElementById(id);
    var ic=document.getElementById(iconId);
    if(inp.type==='password'){inp.type='text';ic.className='fas fa-eye-slash';}
    else{inp.type='password';ic.className='fas fa-eye';}
}
</script>
</body>
</html>
