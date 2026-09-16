<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien Baru — RS Cahya Medika Bondowoso</title>
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
            color:#0f172a; padding:40px 24px; position:relative;
        }

        /* TOP-LEFT BACK BUTTON TO HOME */
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
            width:100%; max-width:580px;
            background:white; border:1px solid #e2e8f0;
            border-radius:24px; padding:40px 36px;
            box-shadow:0 20px 40px -15px rgba(15, 23, 42, 0.07), 0 0 0 1px rgba(2, 132, 199, 0.04);
            position:relative; margin:20px 0;
        }
        .card-container::before {
            content:''; position:absolute; top:0; left:36px; right:36px; height:4px;
            background:linear-gradient(90deg, #059669, #0284c7);
            border-radius:4px 4px 0 0;
        }

        .header-brand { text-align:center; margin-bottom:24px; }
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

        .alert-error { background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 14px; color:#991b1b; font-size:12.5px; margin-bottom:20px; }
        .alert-error ul { margin-left:18px; margin-top:4px; line-height:1.6; }

        .sec-title-pill {
            display:flex; align-items:center; gap:10px; margin-bottom:16px; margin-top:8px;
        }
        .stp-icon {
            width:28px; height:28px; border-radius:8px; background:#eff6ff; color:#0284c7;
            display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800;
        }
        .stp-text { font-size:12.5px; font-weight:800; color:#0f172a; text-transform:uppercase; letter-spacing:0.5px; }
        .stp-line { flex:1; height:1px; background:#e2e8f0; }

        .g2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }

        .fg { margin-bottom:16px; }
        .fg label { display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:6px; }
        .req { color:#dc2626; }
        .input-wrap { position:relative; }
        .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13.5px; }
        .fg input, .fg select {
            width:100%; padding:11px 14px 11px 40px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; font-family:inherit; color:#0f172a; background:white; transition:all 0.15s;
        }
        .fg input.no-ico, .fg select.no-ico { padding-left:14px; }
        .fg input:focus, .fg select:focus { outline:none; border-color:#0284c7; box-shadow:0 0 0 3.5px rgba(2, 132, 199, 0.1); }
        .fg input.is-invalid, .fg select.is-invalid { border-color:#dc2626; }
        .pwd-eye { position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#94a3b8; cursor:pointer; font-size:13px; border:none; background:none; padding:4px; }
        .pwd-eye:hover { color:#0284c7; }

        .agree-wrap {
            background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px; margin-top:10px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; cursor:pointer;
        }
        .agree-wrap input { margin-top:2px; accent-color:#0284c7; cursor:pointer; }
        .agree-text { font-size:12px; color:#475569; line-height:1.5; }

        .btn-submit {
            width:100%; padding:14px; background:linear-gradient(135deg, #059669 0%, #0284c7 100%); color:white; border:none; border-radius:12px; font-size:14.5px; font-weight:800; font-family:inherit; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 14px rgba(2, 132, 199, 0.25); display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-submit:hover { opacity:0.95; transform:translateY(-1px); box-shadow:0 6px 20px rgba(2, 132, 199, 0.35); }

        .form-footer { text-align:center; margin-top:20px; font-size:13px; color:#64748b; }
        .form-footer a { color:#0284c7; font-weight:700; text-decoration:none; }
        .form-footer a:hover { text-decoration:underline; }

        @media(max-width:580px) { .g2, .g3 { grid-template-columns:1fr; } .card-container { padding:28px 20px; } .btn-back { top:16px; left:16px; } }
    </style>
</head>
<body>

    <!-- TOP-LEFT BACK BUTTON TO HOME FOR PASIEN -->
    <a href="{{ route('home') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Beranda
    </a>

    <div class="card-container">
        <div class="header-brand">
            <div class="logo-box">
                <i class="fas fa-hospital"></i>
            </div>
            <div class="brand-title">RS Cahya Medika</div>
            <div class="brand-sub">Registrasi Pasien Baru</div>
        </div>

        <div class="form-title">Daftar Akun Pasien</div>
        <div class="form-sub">Lengkapi formulir di bawah untuk mendaftar berobat online</div>

        @if($errors->any())
        <div class="alert-error">
            <div style="font-weight:700;margin-bottom:4px"><i class="fas fa-circle-exclamation" style="margin-right:6px"></i>Mohon periksa kembali inputan Anda:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" id="regForm">
            @csrf

            {{-- SECTION 1: DATA DIRI --}}
            <div class="sec-title-pill">
                <div class="stp-icon"><i class="fas fa-user"></i></div>
                <div class="stp-text">Data Identitas Pasien</div>
                <div class="stp-line"></div>
            </div>

            <div class="g2">
                <div class="fg">
                    <label>Nama Lengkap <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="nama_lengkap" class="{{ $errors->has('nama_lengkap')?'is-invalid':'' }}" placeholder="Nama sesuai KTP" value="{{ old('nama_lengkap') }}" required>
                    </div>
                </div>

                <div class="fg">
                    <label>NIK (16 Digit) <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" name="nik" maxlength="16" class="{{ $errors->has('nik')?'is-invalid':'' }}" placeholder="3511XXXXXXXXXXXX" value="{{ old('nik') }}" required oninput="this.value=this.value.replace(/\D/g,'')">
                    </div>
                </div>
            </div>

            <div class="g3">
                <div class="fg">
                    <label>Tanggal Lahir <span class="req">*</span></label>
                    <input type="date" name="tanggal_lahir" class="no-ico {{ $errors->has('tanggal_lahir')?'is-invalid':'' }}" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required>
                </div>

                <div class="fg">
                    <label>Jenis Kelamin <span class="req">*</span></label>
                    <select name="jenis_kelamin" class="no-ico {{ $errors->has('jenis_kelamin')?'is-invalid':'' }}" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="fg">
                    <label>No. HP / WhatsApp <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" name="no_hp" class="{{ $errors->has('no_hp')?'is-invalid':'' }}" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" required>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: KEAMANAN AKUN --}}
            <div class="sec-title-pill" style="margin-top:12px">
                <div class="stp-icon" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-lock"></i></div>
                <div class="stp-text">Keamanan Akun Pasien</div>
                <div class="stp-line"></div>
            </div>

            <div class="fg">
                <label>Alamat Email <span class="req">*</span></label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="{{ $errors->has('email')?'is-invalid':'' }}" placeholder="email@pasien.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="g2">
                <div class="fg">
                    <label>Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" id="regPwd" class="{{ $errors->has('password')?'is-invalid':'' }}" placeholder="Min. 8 karakter" required style="padding-right:40px">
                        <button type="button" class="pwd-eye" onclick="togglePwd('regPwd','regPwdIcon')"><i class="fas fa-eye" id="regPwdIcon"></i></button>
                    </div>
                </div>

                <div class="fg">
                    <label>Konfirmasi Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="regPwdConf" class="{{ $errors->has('password_confirmation')?'is-invalid':'' }}" placeholder="Ulangi password" required style="padding-right:40px">
                        <button type="button" class="pwd-eye" onclick="togglePwd('regPwdConf','regPwdConfIcon')"><i class="fas fa-eye" id="regPwdConfIcon"></i></button>
                    </div>
                </div>
            </div>

            <label class="agree-wrap">
                <input type="checkbox" name="agree" id="agree" {{ old('agree') ? 'checked' : '' }} required>
                <span class="agree-text">
                    Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi pelayanan pasien RS Cahya Medika Bondowoso.
                </span>
            </label>

            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="fas fa-user-plus"></i> Daftar Akun Pasien Baru
            </button>
        </form>

        <div class="form-footer">
            Sudah punya akun pasien? <a href="{{ route('login') }}">Login Pasien &rarr;</a>
        </div>
    </div>

<script>
function togglePwd(id, iconId){
    var inp=document.getElementById(id);
    var ic=document.getElementById(iconId);
    if(inp.type==='password'){inp.type='text';ic.className='fas fa-eye-slash';}
    else{inp.type='password';ic.className='fas fa-eye';}
}
document.getElementById('regForm').addEventListener('submit', function() {
    var btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Pendaftaran...';
});
</script>
</body>
</html>
