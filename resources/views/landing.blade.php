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
    <!-- Custom CSS -->
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body>

    <!-- ========== NAVBAR ========== -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo.png') }}"
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
                    
                    <h1 class="hero-title">
                        Sistem Informasi <span>Akademik</span> Terintegrasi<br>
                        <small class="text-white-50 fw-normal">Politeknik Negeri Batam</small>
                    </h1>
                    <br>
                    <p class="hero-subtitle">
                        Platform digital untuk mengelola aktivitas akademik mahasiswa secara mudah dan efisien. 
                        Akses KRS online, KHS real-time, jadwal kuliah, hingga informasi akademik dalam satu sistem 
                        terintegrasi yang aman dan user-friendly, kapan saja dan di mana saja.
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
</body>
</html>
