<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien — RS Cahya Medika Bondowoso</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Plus Jakarta Sans',sans-serif; min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            background: radial-gradient(circle at 10% 20%, rgba(2, 132, 199, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(13, 148, 136, 0.05) 0%, transparent 40%),
                        #f8fafc;
            color:#0f172a; padding:24px; position:relative;
        }

        /* BACK BUTTON ON TOP-LEFT FOR PASIEN */
        .btn-back {
            position:absolute; top:24px; left:24px;
            display:inline-flex; align-items:center; gap:8px;
            padding:9px 18px; border-radius:99px;
            background:white; border:1px solid #e2e8f0;
            color:#334155; font-size:13px; font-weight:700;
            text-decoration:none; box-shadow:0 2px 6px rgba(15,23,42,0.04);
            transition:all 0.2s; z-index:10;
        }
        .btn-back:hover { background:#f1f5f9; color:#0f172a; border-color:#cbd5e1; transform:translateX(-2px); }

        /* CENTER CARD CONTAINER */
        .card-container {
            width:100%; max-width:420px;
            background:white; border:1px solid #e2e8f0;
            border-radius:24px; padding:36px 32px;
            box-shadow:0 20px 40px -15px rgba(15, 23, 42, 0.07), 0 0 0 1px rgba(2, 132, 199, 0.04);
            position:relative;
        }
        .card-container::before {
            content:''; position:absolute; top:0; left:32px; right:32px; height:4px;
            background:linear-gradient(90deg, #059669, #0284c7);
            border-radius:4px 4px 0 0;
        }

        .header-brand { text-align:center; margin-bottom:28px; }
        .logo-box {
            width:48px; height:48px; border-radius:14px;
            background:linear-gradient(135deg, #059669, #0284c7);
            color:white; font-size:20px; display:flex; align-items:center; justify-content:center;
            margin:0 auto 12px; box-shadow:0 6px 16px rgba(2, 132, 199, 0.25);
        }
        .brand-title { font-size:16px; font-weight:800; color:#0f172a; line-height:1.2; }
        .brand-sub { font-size:12px; color:#64748b; margin-top:2px; font-weight:500; }

        .form-title { font-size:22px; font-weight:800; color:#0f172a; text-align:center; margin-bottom:4px; }
        .form-sub { font-size:13px; color:#64748b; text-align:center; margin-bottom:24px; }

        .alert-error { background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 14px; color:#991b1b; font-size:12.5px; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
        .alert-success { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 14px; color:#166534; font-size:12.5px; margin-bottom:20px; display:flex; align-items:center; gap:8px; }

        .fg { margin-bottom:18px; }
        .fg label { display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px; }
        .input-wrap { position:relative; }
        .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13.5px; }
        .fg input {
            width:100%; padding:11px 14px 11px 40px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; font-family:inherit; color:#0f172a; background:white; transition:all 0.15s;
        }
        .fg input:focus { outline:none; border-color:#0284c7; box-shadow:0 0 0 3.5px rgba(2, 132, 199, 0.1); }
        .fg input.is-invalid { border-color:#dc2626; }
        .pwd-eye { position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#94a3b8; cursor:pointer; font-size:13px; border:none; background:none; padding:4px; }
        .pwd-eye:hover { color:#0284c7; }

        .btn-submit {
            width:100%; padding:12px; background:linear-gradient(135deg, #059669 0%, #0284c7 100%); color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; font-family:inherit; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 14px rgba(2, 132, 199, 0.25); display:flex; align-items:center; justify-content:center; gap:8px; margin-top:6px;
        }
        .btn-submit:hover { opacity:0.95; transform:translateY(-1px); box-shadow:0 6px 20px rgba(2, 132, 199, 0.35); }

        /* DEMO ACCOUNT CARD BUTTON */
        .demo-card-btn {
            width:100%; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px;
            padding:12px 14px; margin-top:22px; cursor:pointer; text-align:left;
            transition:all 0.2s; font-family:inherit; display:block;
        }
        .demo-card-btn:hover { background:#dcfce7; border-color:#86efac; transform:translateY(-1px); box-shadow:0 4px 12px rgba(22,163,74,0.12); }

        .form-footer { text-align:center; margin-top:22px; font-size:13px; color:#64748b; }
        .form-footer a { color:#0284c7; font-weight:700; text-decoration:none; }
        .form-footer a:hover { text-decoration:underline; }

        @media(max-width:480px) { .card-container { padding:28px 20px; } .btn-back { top:16px; left:16px; } }
    </style>
</head>
<body>

    <!-- TOP-LEFT BACK BUTTON TO HOME FOR PASIEN -->
    <a href="{{ route('home') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Beranda
    </a>

    <div class="card-container">
        <div class="header-brand">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px">
                <img src="{{ asset('logo.png') }}" alt="Logo RS Cahya Medika" style="height:42px;width:auto;object-fit:contain">
                <div style="text-align:left">
                    <div class="brand-title" style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.2">RS Cahya Medika</div>
                    <div style="font-size:11px;font-weight:700;color:#0891b2;letter-spacing:0.4px">BONDOWOSO</div>
                </div>
            </div>
            <div class="brand-sub">Portal Akses Pasien</div>
        </div>

        <div class="form-title">Login Pasien</div>
        <div class="form-sub">Masukkan email dan password akun pasien Anda</div>

        @if($errors->any())
        <div class="alert-error"><i class="fas fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
        @endif

        @if(session('success'))
        <div class="alert-success"><i class="fas fa-circle-check"></i>{{ session('success') }}</div>
        @endif

        <form action="{{ route('pasien.login.post') }}" method="POST">
            @csrf
            <div class="fg">
                <label>Email Pasien</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="emailInput" class="{{ $errors->has('email')?'is-invalid':'' }}" placeholder="email@pasien.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="fg">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="pwdInput" class="{{ $errors->has('password')?'is-invalid':'' }}" placeholder="••••••••" required style="padding-right:40px">
                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdInput','pwdIcon')"><i class="fas fa-eye" id="pwdIcon"></i></button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-right-to-bracket"></i> Masuk Akun Pasien
            </button>
        </form>

        {{-- CLICKABLE DEMO ACCOUNT CARD BUTTON --}}
        <button type="button" class="demo-card-btn" onclick="fillDemo('pasien@demo.com', 'pasien123')">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div style="font-weight:700;font-size:12px;color:#166534">
                    <i class="fas fa-key" style="color:#16a34a;margin-right:6px"></i> Akun Demo Pasien
                </div>
                <span style="font-size:10px;color:#15803d;font-weight:700;background:white;padding:2px 8px;border-radius:6px;border:1px solid #bbf7d0">Klik untuk autofill &rarr;</span>
            </div>
            <div style="font-size:11.5px;color:#166534;margin-top:5px;font-family:monospace">
                Email: pasien@demo.com | Pass: pasien123
            </div>
        </button>

        <div class="form-footer">
            Belum punya akun pasien? <a href="{{ route('register') }}">Daftar Pasien Baru &rarr;</a>
        </div>
    </div>

<script>
function togglePwd(id, iconId){
    var inp=document.getElementById(id);
    var ic=document.getElementById(iconId);
    if(inp.type==='password'){inp.type='text';ic.className='fas fa-eye-slash';}
    else{inp.type='password';ic.className='fas fa-eye';}
}
function fillDemo(email, pwd){
    document.getElementById('emailInput').value = email;
    document.getElementById('pwdInput').value = pwd;
}
</script>
</body>
</html>
