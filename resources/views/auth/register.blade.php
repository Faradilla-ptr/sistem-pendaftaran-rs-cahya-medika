<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien - RS Cahya Medika Bondowoso</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 100%);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
        }

        .register-card {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 780px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        }

        /* HEADER */
        .card-top {
            background: linear-gradient(135deg, #0c4a6e, #0891b2);
            padding: 36px 40px;
            position: relative;
            overflow: hidden;
        }

        .card-top::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .card-top-blob {
            position: absolute;
            top: -60px; right: -60px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(6,182,212,0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .card-top-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .top-logo {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }

        .top-text h1 {
            font-size: 22px;
            font-weight: 800;
            color: white;
            margin-bottom: 3px;
        }

        .top-text p {
            font-size: 13px;
            color: rgba(255,255,255,0.65);
        }

        .top-badge {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(6,182,212,0.2);
            border: 1px solid rgba(6,182,212,0.4);
            color: #a5f3fc;
            padding: 7px 14px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        /* FORM BODY */
        .card-body {
            padding: 36px 40px;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #0c4a6e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0f2fe;
        }

        .section-label i {
            width: 28px; height: 28px;
            background: #e0f2fe;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #0891b2;
            font-size: 12px;
        }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

        .form-group { margin-bottom: 0; }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .required { color: #dc2626; margin-left: 2px; }

        .input-wrap { position: relative; }

        .input-ico {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            font-family: inherit;
            color: #1e293b;
            background: white;
            transition: all 0.2s;
        }

        .form-input.no-ico { padding-left: 12px; }

        .form-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6,182,212,0.1);
        }

        .form-input.is-invalid { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.08); }
        .invalid-msg { color: #dc2626; font-size: 11px; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

        .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            font-family: inherit;
            color: #1e293b;
            background: white;
            cursor: pointer;
        }

        .form-select:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6,182,212,0.1); }

        /* PASSWORD STRENGTH */
        .pwd-strength {
            margin-top: 6px;
            display: flex;
            gap: 4px;
        }
        .pwd-bar {
            flex: 1;
            height: 3px;
            border-radius: 3px;
            background: #e2e8f0;
            transition: background 0.3s;
        }
        .pwd-bar.weak { background: #dc2626; }
        .pwd-bar.medium { background: #f59e0b; }
        .pwd-bar.strong { background: #059669; }

        /* AGREE */
        .agree-box {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }

        .agree-box input[type="checkbox"] {
            width: 16px; height: 16px;
            margin-top: 2px;
            accent-color: #0891b2;
            flex-shrink: 0;
        }

        .agree-text {
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }

        .agree-text a { color: #0891b2; font-weight: 600; }

        /* SUBMIT */
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
            box-shadow: 0 10px 28px rgba(12,74,110,0.35);
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748b;
        }

        .form-footer a { color: #0891b2; font-weight: 700; text-decoration: none; }

        .error-box {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #991b1b;
        }

        .error-box ul { margin: 6px 0 0 16px; }

        @media (max-width: 640px) {
            .card-body { padding: 24px 20px; }
            .card-top { padding: 24px 20px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .top-badge { display: none; }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="card-top">
            <div class="card-top-blob"></div>
            <div class="card-top-inner">
                <div class="top-logo">🏥</div>
                <div class="top-text">
                    <h1>Pendaftaran Pasien Baru</h1>
                    <p>RS Cahya Medika Bondowoso · Rumah Sakit Swasta (Non-BPJS)</p>
                </div>
                <div class="top-badge">
                    <i class="fas fa-shield-check"></i>
                    Terintegrasi SatuSehat
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="error-box">
                    <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <!-- DATA DIRI -->
                <div class="form-section">
                    <div class="section-label">
                        <i class="fas fa-user"></i>
                        Data Diri
                    </div>

                    <div class="grid-2" style="margin-bottom:16px">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-user input-ico"></i>
                                <input type="text" name="nama_lengkap" class="form-input {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                                    placeholder="Nama sesuai KTP" value="{{ old('nama_lengkap') }}" required>
                            </div>
                            @error('nama_lengkap')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK (16 digit) <span class="required">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-id-card input-ico"></i>
                                <input type="text" name="nik" maxlength="16" class="form-input {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                                    placeholder="3511XXXXXXXXXXXX" value="{{ old('nik') }}" required>
                            </div>
                            @error('nik')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid-3" style="margin-bottom:16px">
                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-input no-ico {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}"
                                value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                            <select name="jenis_kelamin" class="form-select {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. HP <span class="required">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-phone input-ico"></i>
                                <input type="text" name="no_hp" class="form-input {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                                    placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" required>
                            </div>
                            @error('no_hp')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- AKUN -->
                <div class="form-section">
                    <div class="section-label">
                        <i class="fas fa-lock"></i>
                        Informasi Akun
                    </div>

                    <div class="grid-2" style="margin-bottom:16px">
                        <div class="form-group">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-envelope input-ico"></i>
                                <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="email@example.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password <span class="required">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-key input-ico"></i>
                                <input type="password" name="password" id="pwd" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Min. 8 karakter" required oninput="checkStrength(this.value)">
                            </div>
                            <div class="pwd-strength">
                                <div class="pwd-bar" id="bar1"></div>
                                <div class="pwd-bar" id="bar2"></div>
                                <div class="pwd-bar" id="bar3"></div>
                            </div>
                            @error('password')<div class="invalid-msg"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                            <div class="input-wrap">
                                <i class="fas fa-key input-ico"></i>
                                <input type="password" name="password_confirmation" class="form-input"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AGREE -->
                <div class="agree-box">
                    <input type="checkbox" name="agree" id="agree" {{ old('agree') ? 'checked' : '' }} required>
                    <div class="agree-text">
                        Dengan mendaftar, saya menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> RS Cahya Medika Bondowoso. Data saya akan diproses sesuai ketentuan yang berlaku dan terhubung ke platform SatuSehat Kemenkes RI.
                    </div>
                </div>
                @error('agree')<div class="invalid-msg" style="margin-bottom:12px"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror

                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i>
                    Buat Akun & Daftar Sekarang
                </button>

                <div class="form-footer">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini →</a>
                </div>

                <div style="text-align:center;margin-top:12px">
                    <a href="{{ route('home') }}" style="color:#94a3b8;font-size:12px;text-decoration:none">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkStrength(val) {
            const b1 = document.getElementById('bar1');
            const b2 = document.getElementById('bar2');
            const b3 = document.getElementById('bar3');
            [b1,b2,b3].forEach(b => { b.className = 'pwd-bar'; });

            if (val.length >= 4) b1.classList.add('weak');
            if (val.length >= 8 && /[A-Z]/.test(val)) b2.classList.add('medium');
            if (val.length >= 10 && /[A-Z]/.test(val) && /[0-9]/.test(val)) {
                b1.classList.add('strong'); b2.classList.add('strong'); b3.classList.add('strong');
            }
        }
    </script>
</body>
</html>
