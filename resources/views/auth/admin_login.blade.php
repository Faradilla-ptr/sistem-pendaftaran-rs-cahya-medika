<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Petugas & Admin — RS Cahya Medika Bondowoso</title>
    <!-- Favicon / Logo Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}?v=4">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}?v=4">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at 50% 30%, #0f172a 0%, #070f1e 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 40px 20px;
            color: #ffffff;
            position: relative; overflow: hidden;
        }

        /* Ambient Glow Blobs */
        .ambient-glow-1 {
            position: absolute; top: -150px; left: 50%; transform: translateX(-50%);
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .admin-wrapper {
            width: 100%; max-width: 440px;
            position: relative; z-index: 2;
        }

        .admin-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            padding: 42px 38px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(2, 132, 199, 0.1);
            position: relative;
        }

        .admin-card::before {
            content: ''; position: absolute;
            top: 0; left: 10%; right: 10%; height: 2px;
            background: linear-gradient(90deg, transparent, #38bdf8, transparent);
        }

        .card-header {
            text-align: center; margin-bottom: 32px;
        }

        .header-logo {
            width: 58px; height: 58px; border-radius: 18px;
            background: linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
            color: #ffffff; font-size: 26px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 25px rgba(2, 132, 199, 0.4);
        }

        .header-title { font-size: 22px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; }
        .header-subtitle { font-size: 13px; color: #94a3b8; margin-top: 4px; }

        .sec-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 12px; border-radius: 99px;
            background: rgba(56, 189, 248, 0.12); border: 1px solid rgba(56, 189, 248, 0.25);
            color: #38bdf8; font-size: 11px; font-weight: 700;
            margin-top: 12px;
        }

        /* Error / Success */
        .alert-box {
            border-radius: 14px; padding: 12px 16px;
            font-size: 13px; font-weight: 600; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-error { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 11px; font-weight: 800;
            color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .input-box { position: relative; }
        .input-box i.input-icon {
            position: absolute; left: 16px; top: 50%;
            transform: translateY(-50%); color: #64748b; font-size: 15px;
            transition: color 0.2s;
        }

        .input-control {
            width: 100%; padding: 14px 44px 14px 46px;
            border: 1.5px solid rgba(255, 255, 255, 0.12); border-radius: 14px;
            font-size: 14px; font-family: inherit; color: #ffffff;
            background: rgba(15, 23, 42, 0.6); transition: all 0.2s ease;
        }

        .input-control:focus {
            outline: none; background: rgba(15, 23, 42, 0.9);
            border-color: #38bdf8; box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        }
        .input-box:focus-within i.input-icon { color: #38bdf8; }

        .btn-eye {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); color: #64748b;
            border: none; background: none; cursor: pointer; font-size: 15px; padding: 4px;
        }
        .btn-eye:hover { color: #38bdf8; }

        .btn-admin-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #0284c7 0%, #0d9488 100%);
            color: #ffffff; border: none; border-radius: 14px;
            font-size: 14px; font-weight: 800; cursor: pointer;
            transition: all 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 8px 25px rgba(2, 132, 199, 0.35);
            margin-top: 10px;
        }

        .btn-admin-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(2, 132, 199, 0.45);
        }

        /* Demo Role Quick Chips */
        .demo-roles-container {
            margin-top: 28px; padding: 16px;
            background: rgba(2, 132, 199, 0.05); border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
        }

        .demo-roles-title {
            font-size: 11px; font-weight: 800; color: #94a3b8; margin-bottom: 12px;
            text-align: center; text-transform: uppercase; letter-spacing: 0.5px;
        }

        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .btn-role-chip {
            padding: 12px 10px; background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px;
            font-size: 11px; font-weight: 800; color: #e2e8f0;
            cursor: pointer; text-align: center; transition: all 0.2s ease;
        }

        .btn-role-chip:hover {
            border-color: #38bdf8; background: rgba(56, 189, 248, 0.15); color: #ffffff;
            transform: translateY(-1px);
        }
        .btn-role-chip i { display: block; font-size: 16px; margin-bottom: 4px; }

        .footer-note {
            text-align: center; margin-top: 24px; font-size: 11px; color: #64748b;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>

    <div class="admin-wrapper">
        <div class="admin-card">
            <div class="card-header">
                <div style="margin-bottom:14px;">
                    <img src="{{ asset('logo.png') }}" alt="Logo" style="max-height: 56px; width: auto; object-fit: contain;">
                </div>
                <h1 class="header-title">Portal Petugas & Admin</h1>
                <p class="header-subtitle">RS Cahya Medika Bondowoso</p>
                <div class="sec-badge">
                    <i class="fas fa-lock"></i> Restricted Staff Access Only
                </div>
            </div>

            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-box alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Petugas / Admin</label>
                    <div class="input-box">
                        <i class="fas fa-user-tie input-icon"></i>
                        <input type="email" id="emailInput" name="email" class="input-control" placeholder="nama@rscahyamedika.co.id" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-box">
                        <i class="fas fa-key input-icon"></i>
                        <input type="password" id="passwordInput" name="password" class="input-control" placeholder="••••••••" required>
                        <button type="button" class="btn-eye" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-admin-submit">
                    <i class="fas fa-right-to-bracket"></i> Masuk Sistem Staf
                </button>
            </form>

            <div class="demo-roles-container">
                <div class="demo-roles-title">
                    <i class="fas fa-bolt" style="color: #38bdf8;"></i> Login Cepat Staff (Sidang Skripsi)
                </div>
                <div class="role-grid">
                    <button type="button" class="btn-role-chip" onclick="fillCredentials('pendaftaran@rscahyamedika.co.id', 'pendaftaran123')">
                        <i class="fas fa-id-card" style="color: #38bdf8;"></i>
                        Pendaftaran
                    </button>

                    <button type="button" class="btn-role-chip" onclick="fillCredentials('rekammedis@rscahyamedika.co.id', 'rekammedis123')">
                        <i class="fas fa-file-medical" style="color: #2dd4bf;"></i>
                        Rekam Medis
                    </button>
                </div>
            </div>

            <div class="footer-note">
                Sistem Pendaftaran & Rekam Medis Terintegrasi SATUSEHAT &copy; {{ date('Y') }}
            </div>
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('emailInput').value = email;
            document.getElementById('passwordInput').value = password;
        }

        function togglePassword() {
            const pwd = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
