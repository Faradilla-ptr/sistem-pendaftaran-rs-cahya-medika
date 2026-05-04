<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RS Cahya Medika Bondowoso - Pendaftaran Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy: #0c4a6e;
            --teal: #06b6d4;
            --teal-dark: #0891b2;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--navy);
            color: var(--white);
            overflow-x: hidden;
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0c4a6e 0%, #0f5e8f 40%, #0891b2 100%);
        }

        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.04;
            background-image: radial-gradient(circle, white 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .hero-blob {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(6,182,212,0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-blob-2 {
            position: absolute;
            bottom: -150px;
            left: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* NAV */
        nav {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 60px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .nav-logo {
            width: 46px; height: 46px;
            background: var(--teal);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        .nav-text .name {
            font-size: 16px;
            font-weight: 800;
            line-height: 1.2;
        }

        .nav-text .sub {
            font-size: 11px;
            opacity: 0.6;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link-ghost {
            color: rgba(255,255,255,0.8);
        }
        .nav-link-ghost:hover { color: white; background: rgba(255,255,255,0.1); }

        .nav-link-primary {
            background: var(--teal);
            color: white;
        }
        .nav-link-primary:hover { background: var(--teal-dark); }

        /* HERO CONTENT */
        .hero-content {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            align-items: center;
            padding: 60px 60px 80px;
            gap: 60px;
        }

        .hero-left {
            flex: 1;
            max-width: 560px;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6,182,212,0.2);
            border: 1px solid rgba(6,182,212,0.3);
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            color: #67e8f9;
            margin-bottom: 24px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-title .accent {
            color: var(--teal);
        }

        .hero-desc {
            font-size: 16px;
            line-height: 1.7;
            color: rgba(255,255,255,0.7);
            margin-bottom: 36px;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: var(--teal);
            color: white;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 8px 24px rgba(6,182,212,0.4);
        }
        .btn-hero-primary:hover {
            background: var(--teal-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(6,182,212,0.5);
        }

        .btn-hero-ghost {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: rgba(255,255,255,0.1);
            color: white;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-hero-ghost:hover { background: rgba(255,255,255,0.2); }

        /* HERO STATS */
        .hero-stats {
            display: flex;
            gap: 28px;
            margin-top: 44px;
            padding-top: 36px;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .stat-item .number {
            font-size: 28px;
            font-weight: 800;
            color: var(--teal);
        }

        .stat-item .label {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
        }

        /* HERO RIGHT CARD */
        .hero-right {
            flex-shrink: 0;
        }

        .quick-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 28px;
            width: 340px;
        }

        .quick-card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-poli {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .poli-item {
            background: rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 14px 12px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .poli-item:hover {
            background: rgba(6,182,212,0.2);
            border-color: var(--teal);
        }

        .poli-icon { font-size: 24px; margin-bottom: 6px; }
        .poli-name { font-size: 11px; font-weight: 600; opacity: 0.9; }

        .quick-info {
            background: rgba(6,182,212,0.15);
            border: 1px solid rgba(6,182,212,0.3);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 12px;
            line-height: 1.6;
            color: #a5f3fc;
        }

        /* FEATURES SECTION */
        .section {
            background: white;
            color: #1e293b;
            padding: 80px 60px;
        }

        .section-tag {
            display: inline-block;
            background: #e0f2fe;
            color: var(--teal-dark);
            padding: 5px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--navy);
        }

        .section-desc {
            font-size: 15px;
            color: #64748b;
            max-width: 500px;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 50px;
        }

        .feature-card {
            padding: 28px;
            background: #f8fafc;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        .feature-card:hover {
            background: var(--navy);
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(12,74,110,0.2);
        }

        .feature-icon {
            width: 54px; height: 54px;
            background: #e0f2fe;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .feature-card:hover .feature-icon {
            background: rgba(6,182,212,0.2);
        }

        .feature-card h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        .feature-card:hover p { color: rgba(255,255,255,0.65); }

        /* FOOTER */
        footer {
            background: #071e2d;
            color: rgba(255,255,255,0.6);
            padding: 40px 60px;
            text-align: center;
            font-size: 13px;
        }

        footer .footer-brand {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
        }

        footer .footer-info {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        footer .footer-info span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }

        @media (max-width: 900px) {
            nav, .hero-content, .section { padding-left: 24px; padding-right: 24px; }
            .hero-content { flex-direction: column; padding-top: 40px; }
            .hero-title { font-size: 36px; }
            .hero-right { width: 100%; }
            .quick-card { width: 100%; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-pattern"></div>
        <div class="hero-blob"></div>
        <div class="hero-blob-2"></div>

        <nav>
            <div class="nav-brand">
                <div class="nav-logo">🏥</div>
                <div class="nav-text">
                    <div class="name">RS Cahya Medika</div>
                    <div class="sub">Bondowoso · Rumah Sakit Swasta</div>
                </div>
            </div>
            <div class="nav-links">
                <a href="{{ route('login') }}" class="nav-link nav-link-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="nav-link nav-link-primary">Daftar Sekarang</a>
            </div>
        </nav>

        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-tag">
                    <i class="fas fa-link" style="font-size:10px"></i>
                    Terintegrasi SatuSehat Kemenkes RI
                </div>
                <h1 class="hero-title">
                    Layanan Kesehatan <span class="accent">Terpercaya</span> di Bondowoso
                </h1>
                <p class="hero-desc">
                    Daftar berobat online dengan mudah & cepat. RS Cahya Medika hadir sebagai rumah sakit swasta baru dengan layanan premium. Data Anda tersimpan aman & terhubung ke sistem nasional SatuSehat.
                </p>
                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <i class="fas fa-user-plus"></i>
                        Daftar Pasien Baru
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero-ghost">
                        <i class="fas fa-sign-in-alt"></i>
                        Sudah Punya Akun
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="number">8+</div>
                        <div class="label">Poli Layanan</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">6+</div>
                        <div class="label">Dokter Spesialis</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">24/7</div>
                        <div class="label">IGD Siaga</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">100%</div>
                        <div class="label">Non-BPJS</div>
                    </div>
                </div>
            </div>

            <div class="hero-right">
                <div class="quick-card">
                    <div class="quick-card-title">
                        <span style="font-size:20px">🏥</span>
                        Layanan Poli Kami
                    </div>
                    <div class="quick-poli">
                        <div class="poli-item">
                            <div class="poli-icon">🏥</div>
                            <div class="poli-name">Poli Umum</div>
                        </div>
                        <div class="poli-item">
                            <div class="poli-icon">👶</div>
                            <div class="poli-name">Poli Anak</div>
                        </div>
                        <div class="poli-item">
                            <div class="poli-icon">🫀</div>
                            <div class="poli-name">Penyakit Dalam</div>
                        </div>
                        <div class="poli-item">
                            <div class="poli-icon">🤱</div>
                            <div class="poli-name">Kandungan</div>
                        </div>
                        <div class="poli-item">
                            <div class="poli-icon">❤️</div>
                            <div class="poli-name">Jantung</div>
                        </div>
                        <div class="poli-item">
                            <div class="poli-icon">🔬</div>
                            <div class="poli-name">Bedah</div>
                        </div>
                    </div>
                    <div class="quick-info">
                        <i class="fas fa-info-circle" style="margin-right:6px"></i>
                        <strong>Informasi:</strong> RS Cahya Medika adalah rumah sakit swasta baru yang belum bekerjasama dengan BPJS Kesehatan. Saat ini melayani pasien umum (non-BPJS).
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="section">
        <div class="section-tag">Keunggulan Kami</div>
        <h2 class="section-title">Kenapa Memilih<br>RS Cahya Medika?</h2>
        <p class="section-desc">Kami hadir dengan teknologi modern dan tenaga medis berpengalaman untuk memberikan pelayanan terbaik bagi masyarakat Bondowoso.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Pendaftaran Online Mudah</h3>
                <p>Daftar berobat kapan saja dan di mana saja. Pilih dokter, jadwal, dan poli sesuai kebutuhan Anda tanpa perlu antre panjang.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔗</div>
                <h3>Terintegrasi SatuSehat</h3>
                <p>Data kesehatan Anda terhubung ke platform SatuSehat milik Kemenkes RI untuk rekam medis yang terpadu dan aman.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏥</div>
                <h3>Dokter Spesialis Berpengalaman</h3>
                <p>Tim dokter spesialis kami siap memberikan penanganan terbaik dengan standar pelayanan rumah sakit modern.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3>Rekam Medis Digital</h3>
                <p>Riwayat kesehatan Anda tersimpan secara digital dan dapat diakses dengan mudah kapan pun Anda membutuhkan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Proses Cepat & Efisien</h3>
                <p>Sistem antrian digital memastikan Anda tidak perlu menunggu lama. Pantau status antrian secara real-time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Data Aman & Terlindungi</h3>
                <p>Keamanan data pasien adalah prioritas kami. Semua informasi dienkripsi dan hanya dapat diakses oleh pihak berwenang.</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-brand">RS Cahya Medika Bondowoso</div>
        <p>Rumah Sakit Umum Swasta · Terintegrasi SatuSehat Kemenkes RI</p>
        <div class="footer-info">
            <span><i class="fas fa-map-marker-alt"></i> Bondowoso, Jawa Timur</span>
            <span><i class="fas fa-phone"></i> 0332-123456</span>
            <span><i class="fas fa-envelope"></i> info@rscahyamedika.co.id</span>
            <span><i class="fas fa-clock"></i> IGD 24 Jam</span>
        </div>
        <p style="margin-top: 20px; font-size: 11px; opacity: 0.4;">
            © {{ date('Y') }} RS Cahya Medika Bondowoso. All rights reserved.
        </p>
    </footer>
</body>
</html>
