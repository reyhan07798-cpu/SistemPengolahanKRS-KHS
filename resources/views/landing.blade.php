<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAKAR - Politeknik Negeri Batam</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #172554;
            --secondary: #f97316;
            --secondary-dark: #ea580c;
            --light: #f8fafc;
            --dark: #0f172a;
        }
        
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            background: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* ========== NAVBAR ========== */
        .navbar {
            padding: 1rem 0;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
            padding: 0.5rem 0;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .navbar.scrolled .navbar-brand {
            color: var(--primary) !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.9) !important;
            margin: 0 0.5rem;
            position: relative;
        }
        
        .navbar.scrolled .nav-link {
            color: var(--dark) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .btn-login {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: var(--secondary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
        }

        /* ========== HERO SECTION ========== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(to top, var(--light), transparent);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            color: white;
            margin-bottom: 1.5rem;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            line-height: 1.1;
            margin-bottom: 1rem;
        }
        
        .hero-title span {
            color: var(--secondary);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2rem;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-primary-custom {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary-custom:hover {
            background: var(--secondary-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(249, 115, 22, 0.4);
        }
        
        .btn-secondary-custom {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary-custom:hover {
            background: white;
            color: var(--primary);
            border-color: white;
        }
        
        .hero-image {
            position: relative;
            z-index: 2;
        }
        
        .hero-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }
        
        .hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .hero-detail-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 1rem;
            display: grid;
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .detail-icon {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            color: white;
            font-size: 1.25rem;
        }

        .detail-icon.blue {
            background: var(--primary);
        }

        .detail-icon.green {
            background: #16a34a;
        }

        .detail-icon.orange {
            background: var(--secondary);
        }

        .detail-item h6 {
            margin-bottom: 0.25rem;
            font-size: 1rem;
            font-weight: 700;
        }

        .detail-item p {
            margin: 0;
            color: #64748b;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* ========== STATS BAR ========== */
        .stats-bar {
            background: white;
            padding: 3rem 0;
            margin-top: -100px;
            position: relative;
            z-index: 10;
        }
        
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .stats-icon i {
            font-size: 1.5rem;
            color: white;
        }

        /* ========== FEATURES SECTION ========== */
        .section {
            padding: 6rem 0;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .section-title span {
            color: var(--secondary);
        }
        
        .section-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 3rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border-color: transparent;
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }
        
        .feature-icon.blue {
            background: rgba(30, 58, 138, 0.1);
            color: var(--primary);
        }
        
        .feature-icon.orange {
            background: rgba(249, 115, 22, 0.1);
            color: var(--secondary);
        }
        
        .feature-icon.green {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .feature-icon.purple {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        
        .feature-desc {
            color: #64748b;
            line-height: 1.7;
        }

        /* ========== ABOUT SECTION ========== */
        .about-section {
            background: white;
        }
        
        .about-image {
            position: relative;
        }
        
        .about-image img {
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        }
        
        .about-badge {
            position: absolute;
            bottom: -30px;
            right: -30px;
            background: var(--secondary);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(249, 115, 22, 0.3);
        }
        
        .about-badge h3 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        
        .about-list {
            list-style: none;
            padding: 0;
        }
        
        .about-list li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .about-list li i {
            width: 24px;
            height: 24px;
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
            margin-top: 0.25rem;
        }

        /* ========== JURUSAN SECTION ========== */
        .jurusan-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            overflow: hidden;
        }
        
        .jurusan-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .jurusan-section .section-title,
        .jurusan-section .section-subtitle {
            color: white;
        }
        
        .jurusan-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.1);
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .jurusan-card:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-10px);
        }
        
        .jurusan-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .jurusan-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .jurusan-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .jurusan-list {
            list-style: none;
            padding: 0;
        }
        
        .jurusan-list li {
            color: rgba(255,255,255,0.8);
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .jurusan-list li i {
            color: var(--secondary);
        }

        /* ========== CTA SECTION ========== */
        .cta-section {
            background: white;
        }
        
        .cta-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 30px;
            padding: 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: var(--secondary);
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .cta-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .cta-desc {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* ========== FOOTER ========== */
        .footer {
            background: var(--dark);
            padding: 4rem 0 2rem;
        }
        
        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
        }
        
        .footer-desc {
            color: #94a3b8;
            margin-bottom: 1.5rem;
        }
        
        .footer-title {
            color: white;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--secondary);
        }
        
        .footer-contact li {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #94a3b8;
        }
        
        .footer-contact li i {
            color: var(--secondary);
            font-size: 1.25rem;
            margin-top: 0.25rem;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 2rem;
            margin-top: 3rem;
            text-align: center;
            color: #64748b;
        }

        /* ========== SCROLL TOP ========== */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.25rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.4);
        }
        
        .scroll-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-top:hover {
            background: var(--secondary-dark);
            transform: translateY(-5px);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-image {
                margin-top: 3rem;
            }
            
            .about-badge {
                position: relative;
                bottom: auto;
                right: auto;
                margin-top: 1.5rem;
                display: inline-block;
            }
            
            .cta-card {
                padding: 2.5rem;
            }
            
            .cta-title {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 767px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.75rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .hero-buttons .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- ========== NAVBAR ========== -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/Logo huruf _S_ futuristik.png') }}"
                     alt="Logo SIPAKAR"
                     class="rounded-circle"
                     style="width: 50px; height: 50px; object-fit: cover;">
                <span class="ms-1" style="font-family: 'Poppins', sans-serif; font-weight: 600;">SIPAKAR</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-4 text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jurusan">Jurusan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION ========== -->
    <section class="hero" id="beranda">
        <div class="container hero-content">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-12 text-center" data-aos="fade-right">
                    <div class="hero-badge">
                        <i class="bi bi-mortarboard"></i>
                        Politeknik Negeri Batam
                    </div>
                    <h1 class="hero-title">
                        Sistem Informasi <span>Akademik</span> Terintegrasi<br>
                        <small class="text-white-50 fw-normal">Politeknik Negeri Batam</small>
                    </h1>
                    <p class="hero-subtitle">
                        Platform digital modern untuk mengelola seluruh aktivitas akademik mahasiswa dengan mudah dan efisien.
                        Akses Kartu Rencana Studi (KRS) secara online, Kartu Hasil Studi (KHS) real-time, transkrip nilai lengkap,
                        jadwal kuliah terupdate, informasi pembayaran SPP, dan berbagai layanan akademik lainnya dalam satu sistem
                        terintegrasi yang aman, user-friendly, dan dapat diakses kapan saja dimana saja melalui perangkat mobile
                        maupun desktop. Dirancang khusus untuk memenuhi kebutuhan mahasiswa, dosen wali, dosen pengampu mata kuliah,
                        dan administrator dalam era digital saat ini dengan teknologi terkini dan standar keamanan tinggi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURES SECTION ========== -->
    <section class="section" id="fitur">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title" data-aos="fade-up">Fitur <span>Unggulan</span></h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Sistem informasi akademik dengan berbagai fitur modern untuk memudahkan kegiatan akademik
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon blue">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <h4 class="feature-title">Manajemen KRS Online</h4>
                        <p class="feature-desc">Isi dan kelola Kartu Rencana Studi secara online dengan panduan lengkap dan monitoring status persetujuan real-time dari Dosen Wali.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon orange">
                            <i class="bi bi-journal-richtext"></i>
                        </div>
                        <h4 class="feature-title">Akses KHS Real-time</h4>
                        <p class="feature-desc">Lihat Kartu Hasil Studi dan IPK secara real-time kapan saja. Sistem otomatis menghitung dan memperbarui nilai akademik.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon purple">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <h4 class="feature-title">Persetujuan Digital</h4>
                        <p class="feature-desc">Dosen Wali dapat menyetujui atau menolak KRS secara digital dengan notifikasi otomatis ke mahasiswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ABOUT SECTION ========== -->
    <section class="section about-section" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-image">
                        <img src="{{ asset('images/default-campus.jpg') }}" 
                             alt="Politeknik Negeri Batam" class="img-fluid">
                        <div class="about-badge">
                            <h3>25+</h3>
                            <div class="small">Tahun Berdiri</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h2 class="section-title">Tentang <span>Politeknik Negeri Batam</span></h2>
                    <p class="text-muted mb-4">
                        Politeknik Negeri Batam (Polibatam) adalah perguruan tinggi vokasi negeri yang berlokasi di Kota Batam, Kepulauan Riau. Dengan tagline "For Your Goals Beyond Horizon", Polibatam berkomitmen menghasilkan lulusan yang siap kerja dan berdaya saing di era Industri 4.0.
                    </p>
                    <ul class="about-list">
                        <li>
                            <i class="bi bi-check"></i>
                            <span>Pembelajaran berbasis Project Based Learning (PBL)</span>
                        </li>
                        <li>
                            <i class="bi bi-check"></i>
                            <span>Akreditasi Unggul dan Baik Sekali untuk seluruh prodi</span>
                        </li>
                        <li>
                            <i class="bi bi-check"></i>
                            <span>Kerjasama internasional dengan berbagai negara</span>
                        </li>
                        <li>
                            <i class="bi bi-check"></i>
                            <span>Fasilitas modern dan laboratorium lengkap</span>
                        </li>
                        <li>
                            <i class="bi bi-check"></i>
                            <span>Link and match dengan industri di kawasan Batam</span>
                        </li>
                    </ul>
                    <a href="" target="_blank" class="btn btn-primary-custom mt-3">
                        <i class="bi bi-globe"></i>
                        Kunjungi Website
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== JURUSAN SECTION ========== -->
    <section class="section jurusan-section" id="jurusan">
        <div class="container position-relative">
            <div class="text-center">
                <h2 class="section-title" data-aos="fade-up">Jurusan & <span>Program Studi</span></h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Beragam program studi jenjang D3 dan Sarjana Terapan yang dibutuhkan di dunia Industri 4.0
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <h4 class="jurusan-title">Manajemen & Bisnis</h4>
                        <ul class="jurusan-list">
                            <li><i class="bi bi-check-circle-fill"></i> D3 Akuntansi</li>
                            <li><i class="bi bi-check-circle-fill"></i> D3 Manajemen Pemasaran</li>
                            <li><i class="bi bi-check-circle-fill"></i> D3 Bisnis Internasional</li>
                            <li><i class="bi bi-check-circle-fill"></i> D4 Manajemen Bisnis</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <h4 class="jurusan-title">Teknik Elektro</h4>
                        <ul class="jurusan-list">
                            <li><i class="bi bi-check-circle-fill"></i> D3 Teknik Elektronika</li>
                            <li><i class="bi bi-check-circle-fill"></i> D3 Teknik Listrik</li>
                            <li><i class="bi bi-check-circle-fill"></i> D3 Teknik Telekomunikasi</li>
                            <li><i class="bi bi-check-circle-fill"></i> D4 Teknik Elektro Industri</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <h4 class="jurusan-title">Teknik Informatika</h4>
                        <ul class="jurusan-list">
                            <li><i class="bi bi-check-circle-fill"></i> D3 Teknik Informatika</li>
                            <li><i class="bi bi-check-circle-fill"></i> D3 Sistem Informasi</li>
                            <li><i class="bi bi-check-circle-fill"></i> D4 Teknik Informatika</li>
                            <li><i class="bi bi-check-circle-fill"></i> D4 Sistem Informasi Bisnis</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            <i class="bi bi-gear"></i>
                        </div>
                        <h4 class="jurusan-title">Teknik Mesin</h4>
                        <ul class="jurusan-list">
                            <li><i class="bi bi-check-circle-fill"></i> D3 Teknik Mesin</li>
                            <li><i class="bi bi-check-circle-fill"></i> D3 Teknik Mekatronika</li>
                            <li><i class="bi bi-check-circle-fill"></i> D4 Teknik Mesin Produksi</li>
                            <li><i class="bi bi-check-circle-fill"></i> D4 Teknik Mekatronika</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">SIPAKAR Polibatam</div>
                    <p class="footer-desc">
                        Sistem Informasi Akademik Politeknik Negeri Batam. Kelola aktivitas akademik dengan mudah dan aman.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="https://www.facebook.com/poltekbatam" target="_blank" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                           style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/poltekbatam" target="_blank" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                           style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@poltekbatam" target="_blank" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                           style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); color: white;">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Menu</h5>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#jurusan">Jurusan</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="footer-title">Layanan</h5>
                    <ul class="footer-links">
                        <li><a href="#">KRS Online</a></li>
                        <li><a href="#">KHS Online</a></li>
                        <li><a href="#">Transkrip</a></li>
                        <li><a href="#">Jadwal Kuliah</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="footer-title">Kontak</h5>
                    <ul class="footer-contact list-unstyled">
                        <li>
                            <i class="bi bi-geo-alt"></i>
                            <span>Jl. Ahmad Yani Batam Kota, Kota Batam, Kepulauan Riau</span>
                        </li>
                        <li>
                            <i class="bi bi-telephone"></i>
                            <span>+62-778-469858 Ext.1017</span>
                        </li>
                        <li>
                            <i class="bi bi-envelope"></i>
                            <span>info@polibatam.ac.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; 2024 SIPAKAR Politeknik Negeri Batam. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll Top Button -->
    <button class="scroll-top" id="scrollTop">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Navbar scroll effect
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Scroll to top
        const scrollTopBtn = document.getElementById('scrollTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });
        
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Smooth scroll for nav links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });
    </script>
</body>
</html>
