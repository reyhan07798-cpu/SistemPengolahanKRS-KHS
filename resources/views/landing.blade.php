<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPAKAR - Politeknik Negeri Batam</title>
    <link rel="icon" type="image/png" sizes="16x16 32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    
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

    <!-- ========== HEADER (SimpleHeader style) ========== -->
    <header class="sh-header" id="shHeader">
        <nav class="sh-nav">
            <a href="#beranda" class="sh-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="sh-brand-logo">
                <span class="sh-brand-text">SIPAKAR</span>
            </a>

            {{-- Desktop links --}}
            <div class="sh-links">
                <a href="#beranda" class="sh-link">Beranda</a>
                <a href="#fitur" class="sh-link">Fitur</a>
                <a href="#tentang" class="sh-link">Tentang</a>
                <a href="{{ route('login') }}" class="sh-btn sh-btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk
                </a>
            </div>

            {{-- Mobile toggle --}}
            <button type="button" class="sh-toggle" id="shToggle" aria-label="Toggle menu" aria-expanded="false">
                <svg width="24" height="24" viewBox="0 0 32 32" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="sh-toggle-icon">
                    <path class="sh-toggle-path"
                          d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"
                          stroke-dasharray="12 63"/>
                    <path d="M7 16 27 16"/>
                </svg>
            </button>
        </nav>
    </header>

    {{-- Mobile drawer + overlay (di luar header agar tidak terkurung
         oleh stacking context dari backdrop-filter di header) --}}
    <div class="sh-drawer-overlay" id="shOverlay"></div>
    <aside class="sh-drawer" id="shDrawer">
        <div class="sh-drawer-content">
            <div class="sh-drawer-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="sh-brand-logo">
                <span class="sh-brand-text">SIPAKAR</span>
            </div>
            <nav class="sh-drawer-links">
                <a href="#beranda" class="sh-drawer-link" data-sh-close>Beranda</a>
                <a href="#fitur" class="sh-drawer-link" data-sh-close>Fitur</a>
                <a href="#tentang" class="sh-drawer-link" data-sh-close>Tentang</a>
            </nav>
        </div>
        <div class="sh-drawer-footer">
            <a href="{{ route('login') }}" class="sh-btn sh-btn-primary sh-btn-block">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </a>
        </div>
    </aside>

    <!-- ========== HERO SECTION (HERO-PAGE STYLE) ========== -->
    <section class="hero-x" id="beranda">
        {{-- Decorative gradient blobs --}}
        <div class="hero-x-blob hero-x-blob-1"></div>
        <div class="hero-x-blob hero-x-blob-2"></div>
        <div class="hero-x-blob hero-x-blob-3"></div>

        {{-- Hero Content --}}
        <div class="hero-x-content">
            {{-- Top Badge --}}
            <div class="hero-x-badge-wrap" data-aos="fade-down">
                <div class="hero-x-badge">
                    <!-- <i class="bi bi-stars"></i> -->
                    Civitas Akademika Polibatam
                </div>
            </div>

            {{-- Headline with rotating image --}}
            <h1 class="hero-x-title" data-aos="fade-up" data-aos-delay="100">
                Sistem Akademik
                <span class="hero-x-pill" id="heroPill">
                    <img src="{{ asset('images/logo-dashboard.png') }}" alt="Logo" class="hero-x-pill-img active" data-i="0">
                    <img src="{{ asset('images/Logo-Polibatam.png') }}" alt="Logo Polibatam" class="hero-x-pill-img" data-i="2">
                </span>
                <span class="sparkle-text sparkle-text--gold" data-sparkle-count="10">Polibatam</span>
            </h1>

            {{-- Subtext --}}
            <p class="hero-x-subtitle" data-aos="fade-up" data-aos-delay="200">
                Kelola KRS, KHS, dan informasi akademik dalam satu platform terintegrasi yang aman, mudah, dan dapat diakses kapan saja.
            </p>
        </div>
    </section>

    <!-- ========== FEATURES SECTION (Gallery Hover Carousel) ========== -->
    <section class="gh-section" id="fitur">
        <div class="gh-inner">
            {{-- Heading besar di atas (pola sama dengan section Tentang/Jurusan) --}}
            <div class="text-center">
                <h2 class="section-title" data-aos="fade-up">
                    Fitur <span class="sparkle-text sparkle-text--gold" data-sparkle-count="10">Unggulan</span>
                </h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Sistem informasi akademik dengan berbagai fitur modern untuk memudahkan kegiatan akademik Anda di Politeknik Negeri Batam.
                </p>
            </div>

            {{-- Sub-header: tombol navigasi carousel --}}
            <div class="gh-subheader" data-aos="fade-up" data-aos-delay="200">
                <span class="gh-subheader-label">Jelajahi semua fitur</span>
                <div class="gh-nav-buttons">
                    <button type="button" class="gh-nav-btn" id="ghPrev" aria-label="Slide sebelumnya" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="gh-nav-btn" id="ghNext" aria-label="Slide berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- Carousel --}}
            <div class="gh-carousel" id="ghCarousel">
                <div class="gh-track" id="ghTrack">

                    {{-- Card 1: KRS Online --}}
                    <a href="{{ route('login') }}" class="if-card if-card--1" data-aos="fade-up" data-aos-delay="100">
                        <div class="if-card-inner">
                            <div class="if-card-image">
                                <i class="bi bi-clipboard-check if-card-icon"></i>
                                <span class="if-card-tag">Akademik</span>
                            </div>
                            <div class="if-card-button-pocket">
                                <span class="if-card-button">
                                    <i class="bi bi-arrow-up-right"></i>
                                </span>
                            </div>
                        </div>
                        <div class="if-card-content">
                            <h4>Manajemen KRS Online</h4>
                            <p>Isi dan kelola Kartu Rencana Studi secara online dengan monitoring status persetujuan real-time dari Dosen Wali.</p>
                            <ul class="if-card-tags">
                                <li class="if-tag if-tag-primary">Real-time</li>
                                <li class="if-tag if-tag-soft">KRS Online</li>
                            </ul>
                        </div>
                    </a>

                    {{-- Card 2: KHS Real-time --}}
                    <a href="{{ route('login') }}" class="if-card if-card--2" data-aos="fade-up" data-aos-delay="200">
                        <div class="if-card-inner">
                            <div class="if-card-image">
                                <i class="bi bi-journal-richtext if-card-icon"></i>
                                <span class="if-card-tag">Nilai</span>
                            </div>
                            <div class="if-card-button-pocket">
                                <span class="if-card-button">
                                    <i class="bi bi-arrow-up-right"></i>
                                </span>
                            </div>
                        </div>
                        <div class="if-card-content">
                            <h4>Akses KHS Real-time</h4>
                            <p>Lihat Kartu Hasil Studi dan IPK secara real-time kapan saja. Sistem otomatis menghitung nilai akademik.</p>
                            <ul class="if-card-tags">
                                <li class="if-tag if-tag-primary">IPK Live</li>
                                <li class="if-tag if-tag-soft">Auto-calc</li>
                            </ul>
                        </div>
                    </a>

                    {{-- Card 3: Persetujuan Digital --}}
                    <a href="{{ route('login') }}" class="if-card if-card--3" data-aos="fade-up" data-aos-delay="300">
                        <div class="if-card-inner">
                            <div class="if-card-image">
                                <i class="bi bi-check2-circle if-card-icon"></i>
                                <span class="if-card-tag">Workflow</span>
                            </div>
                            <div class="if-card-button-pocket">
                                <span class="if-card-button">
                                    <i class="bi bi-arrow-up-right"></i>
                                </span>
                            </div>
                        </div>
                        <div class="if-card-content">
                            <h4>Persetujuan Digital</h4>
                            <p>Dosen Wali dapat menyetujui atau menolak KRS secara digital dengan notifikasi otomatis ke mahasiswa.</p>
                            <ul class="if-card-tags">
                                <li class="if-tag if-tag-primary">Approve/Reject</li>
                                <li class="if-tag if-tag-soft">Notifikasi</li>
                            </ul>
                        </div>
                    </a>

                    {{-- Card 4: Multi-Role --}}
                    <a href="{{ route('login') }}" class="if-card if-card--4" data-aos="fade-up" data-aos-delay="400">
                        <div class="if-card-inner">
                            <div class="if-card-image">
                                <i class="bi bi-people-fill if-card-icon"></i>
                                <span class="if-card-tag">Akses</span>
                            </div>
                            <div class="if-card-button-pocket">
                                <span class="if-card-button">
                                    <i class="bi bi-arrow-up-right"></i>
                                </span>
                            </div>
                        </div>
                        <div class="if-card-content">
                            <h4>Sistem Multi-Role</h4>
                            <p>Mahasiswa, Dosen Wali, Dosen Mata Kuliah, dan Admin punya dashboard sendiri dengan akses sesuai peran.</p>
                            <ul class="if-card-tags">
                                <li class="if-tag if-tag-primary">4 Role</li>
                                <li class="if-tag if-tag-soft">Dashboard</li>
                            </ul>
                        </div>
                    </a>

                    {{-- Card 5: Keamanan Data --}}
                    <a href="{{ route('login') }}" class="if-card if-card--5" data-aos="fade-up" data-aos-delay="500">
                        <div class="if-card-inner">
                            <div class="if-card-image">
                                <i class="bi bi-shield-lock-fill if-card-icon"></i>
                                <span class="if-card-tag">Privasi</span>
                            </div>
                            <div class="if-card-button-pocket">
                                <span class="if-card-button">
                                    <i class="bi bi-arrow-up-right"></i>
                                </span>
                            </div>
                        </div>
                        <div class="if-card-content">
                            <h4>Keamanan Data</h4>
                            <p>Otentikasi berlapis dengan proteksi data akademik melalui standar enkripsi industri yang teruji.</p>
                            <ul class="if-card-tags">
                                <li class="if-tag if-tag-primary">Encrypted</li>
                                <li class="if-tag if-tag-soft">Auth Layer</li>
                            </ul>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </section>

    <!-- ========== ABOUT SECTION ========== -->
    <section class="hs-section about-section" id="tentang">
        <div class="hs-content" data-aos="fade-right">
            {{-- Logo + slogan --}}
            <div class="hs-header">
                <img src="{{ asset('images/Logo-Polibatam.png') }}" alt="Logo Polibatam" class="hs-logo">
                <div class="hs-header-text">
                    <p class="hs-brand-name">Polibatam</p>
                    <p class="hs-slogan">FOR YOUR GOALS BEYOND HORIZON</p>
                </div>
            </div>

            {{-- Title + subtitle + CTA --}}
            <div class="hs-main">
                <h2 class="hs-title">
                    Tentang <br>
                    <span class="hs-title-accent">Politeknik Negeri Batam</span>
                </h2>
                <div class="hs-divider"></div>
                <p class="hs-subtitle">
                    Polibatam adalah perguruan tinggi vokasi negeri di Kota Batam, Kepulauan Riau. Berkomitmen menghasilkan lulusan siap kerja yang berdaya saing di era Industri 4.0 dengan pembelajaran berbasis project, akreditasi unggul, dan kerjasama industri.
                </p>
                <ul class="hs-feature-list">
                    <li><i class="bi bi-check-circle-fill"></i> Pembelajaran Project Based Learning (PBL)</li>
                    <li><i class="bi bi-check-circle-fill"></i> Akreditasi Unggul dan Baik Sekali</li>
                    <li><i class="bi bi-check-circle-fill"></i> Kerjasama internasional & industri Batam</li>
                </ul>
                <a href="https://www.polibatam.ac.id" target="_blank" class="hs-cta">
                    KUNJUNGI WEBSITE RESMI
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            {{-- Bottom contact info --}}
            <footer class="hs-footer">
                <div class="hs-info">
                    <i class="bi bi-globe2 hs-info-icon"></i>
                    <span>polibatam.ac.id</span>
                </div>
                <div class="hs-info">
                    <i class="bi bi-telephone-fill hs-info-icon"></i>
                    <span> +62-778-469858</span>
                </div>
                <div class="hs-info">
                    <i class="bi bi-geo-alt-fill hs-info-icon"></i>
                    <span>Jl. Ahmad Yani Batam Kota. Kota Batam. kepulauan Riau. Indonesia</span>
                </div>
            </footer>
        </div>

        {{-- Right side: image with diagonal clip-path --}}
        <div class="hs-image"
             style="background-image: url('{{ asset('images/default-campus.jpg') }}');"
             data-aos="fade-left">
        </div>
    </section>

    <!-- ========== QUOTE / VISION SECTION (AboutSection-2 style) ========== -->
    <section class="ts-section" id="visi">
        <div class="ts-inner" id="tsAnchor">
            <h1 class="ts-quote" data-aos="fade-up">
                Kami sedang
                <span class="ts-pill ts-pill-blue">membangun ulang</span>
                cara mengelola akademik agar lebih
                <span class="ts-pill ts-pill-orange">terbuka</span>,
                terintegrasi, dan
                <span class="ts-pill ts-pill-green">berfokus pada Anda</span>.
            </h1>

            <div class="ts-bottom" data-aos="fade-up" data-aos-delay="200">
                <div class="ts-tagline">
                    <div class="ts-tagline-top">Kami SIPAKAR Polibatam, dan kami akan</div>
                    <div class="ts-tagline-bottom">membawa Anda lebih jauh</div>
                </div>
                <a href="https://www.polibatam.ac.id" target="_blank" class="ts-cta">
                    <i class="bi bi-lightning-charge-fill"></i>
                    Tentang Polibatam
                </a>
            </div>
        </div>
    </section>

    <!-- ========== CINEMATIC FOOTER (parallax reveal) ========== -->
    <div class="cf-wrapper" id="cfWrapper">
        <footer class="cf-footer">
            {{-- Aurora glow + grid background --}}
            <div class="cf-aurora"></div>
            <div class="cf-grid"></div>

            {{-- Main center content --}}
            <div class="cf-center">
                <h2 class="cf-heading" id="cfHeading">Siap memulai perjalanan akademik?</h2>
                <p class="cf-subtitle">
                    Bergabunglah dengan civitas akademika Politeknik Negeri Batam dan kelola aktivitas akademik Anda dalam satu platform.
                </p>

                <div class="cf-pills" id="cfPills">
                    {{-- Primary CTA --}}
                    <a href="https://www.polibatam.ac.id" target="_blank" class="cf-pill cf-pill-primary cf-magnetic">
                        <i class="bi bi-globe"></i>
                        Kunjungi Polibatam
                    </a>
                </div>

                {{-- Social media + contact --}}
                <div class="cf-social">
                    <a href="https://www.facebook.com/polibatamofficial/" target="_blank" class="cf-social-btn cf-magnetic" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/polibatamofficial/" target="_blank" class="cf-social-btn cf-magnetic" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/c/PolibatamTV" target="_blank" class="cf-social-btn cf-magnetic" aria-label="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="mailto:info@polibatam.ac.id" class="cf-social-btn cf-magnetic" aria-label="Email">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="cf-bottom">
                <div class="cf-copyright">
                    © 2026 SIPAKAR · Politeknik Negeri Batam. All rights reserved.
                </div>
                <button type="button" class="cf-totop cf-magnetic" id="cfToTop" aria-label="Kembali ke atas">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </div>
        </footer>
    </div>

    <!-- Scroll Top Button -->
    <button class="scroll-top" id="scrollTop">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</body>
</html>
