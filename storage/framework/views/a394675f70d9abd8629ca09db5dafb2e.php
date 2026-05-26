<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'SIPAKAR - Dosen'); ?></title>
    <link rel="icon" type="image/png" sizes="16x16 32x32" href="<?php echo e(asset('images/logo.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('images/logo.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        @media (min-width: 1024px) {

            .nb-sidebar.collapsed .nb-sidebar-brand h1,
            .nb-sidebar.collapsed .nb-sidebar-brand p {
                display: none;
            }

            .nb-sidebar.collapsed .nb-sidebar-brand {
                justify-content: center;
            }

            .nb-sidebar.collapsed .nb-sidebar-section-title {
                display: none;
            }

            .nb-sidebar.collapsed .nb-nav-trigger>span:not(.material-symbols-outlined) {
                display: none;
            }

            .nb-sidebar.collapsed .nb-nav-trigger .nb-nav-chevron {
                display: none;
            }

            .nb-sidebar.collapsed .nb-nav-trigger {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .nb-sidebar.collapsed .nb-nav-submenu {
                display: none !important;
            }

            .nb-sidebar.collapsed .nb-nav-item>span:not(.material-symbols-outlined) {
                display: none;
            }

            .nb-sidebar.collapsed .nb-nav-item {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .nb-sidebar.collapsed .nb-sidebar-footer span {
                display: none;
            }

            .nb-sidebar.collapsed .nb-sidebar-footer {
                justify-content: center;
            }

            .nb-sidebar.collapsed .nb-locked {
                display: none;
            }
        }
    </style>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body data-role="dosen">
    <div class="nb-overlay" id="nbOverlay" onclick="nbToggleSidebar()"></div>

    <div class="nb-app">
        
        <aside class="nb-sidebar" id="nbSidebar">
            <div class="nb-sidebar-brand">
                <div
                    class="w-12 h-12 rounded-full border-2 border-white/20 bg-white flex items-center justify-center shrink-0 overflow-hidden p-1">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo SIPAKAR" class="w-full h-full object-contain"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="w-full h-full bg-primary-soft flex items-center justify-center" style="display:none">
                        <span class="material-symbols-outlined text-primary" style="font-size:24px">school</span>
                    </div>
                </div>
                <div class="min-w-0">
                    <h1>SIPAKAR</h1>
                    <p>Portal Dosen</p>
                </div>
            </div>

            <div class="nb-sidebar-section-title">Menu Utama</div>

            <nav class="nb-sidebar-nav">
                
                <?php
                    $isWaliActive = request()->routeIs('dosen.wali.*');
                    $hasWaliRole = session('is_dosen_wali', false);
                ?>
                <div class="nb-nav-group <?php echo e($isWaliActive ? 'open' : ''); ?>" id="navGroupWali">
                    <button type="button" class="nb-nav-trigger <?php echo e($isWaliActive ? 'has-active' : ''); ?>"
                        onclick="nbToggleNavGroup('navGroupWali')" data-label="Dosen Wali">
                        <span class="material-symbols-outlined">supervisor_account</span>
                        <span>Dosen Wali</span>
                        <span class="material-symbols-outlined nb-nav-chevron">expand_more</span>
                    </button>
                    <div class="nb-nav-submenu">
                        <?php if($hasWaliRole): ?>
                            <a href="<?php echo e(route('dosen.wali.beranda')); ?>"
                                class="nb-nav-sub-item <?php echo e(request()->routeIs('dosen.wali.beranda') ? 'active' : ''); ?>">
                                <span class="material-symbols-outlined">home</span>
                                <span>Beranda</span>
                            </a>
                            <a href="<?php echo e(route('dosen.wali.krs-verifikasi')); ?>"
                                class="nb-nav-sub-item <?php echo e(request()->routeIs('dosen.wali.krs-verifikasi') ? 'active' : ''); ?>">
                                <span class="material-symbols-outlined">fact_check</span>
                                <span>Verifikasi KRS</span>
                            </a>
                            <a href="<?php echo e(route('dosen.wali.khs')); ?>"
                                class="nb-nav-sub-item <?php echo e(request()->routeIs('dosen.wali.khs') ? 'active' : ''); ?>">
                                <span class="material-symbols-outlined">assessment</span>
                                <span>KHS Mahasiswa</span>
                            </a>
                        <?php else: ?>
                            <div class="nb-locked">🔒 Fitur Wali<br><span>Hanya untuk Dosen Wali</span></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php
                    $isMatkulActive = request()->routeIs('dosen.mk.*');
                    $hasMatkulRole = session('is_dosen_mk', false);
                ?>
                <div class="nb-nav-group <?php echo e($isMatkulActive ? 'open' : ''); ?>" id="navGroupMatkul">
                    <button type="button" class="nb-nav-trigger <?php echo e($isMatkulActive ? 'has-active' : ''); ?>"
                        onclick="nbToggleNavGroup('navGroupMatkul')" data-label="Dosen Matkul">
                        <span class="material-symbols-outlined">co_present</span>
                        <span>Dosen Matkul</span>
                        <span class="material-symbols-outlined nb-nav-chevron">expand_more</span>
                    </button>
                    <div class="nb-nav-submenu">
                        <?php if($hasMatkulRole): ?>
                            <a href="<?php echo e(route('dosen.mk.beranda')); ?>"
                                class="nb-nav-sub-item <?php echo e(request()->routeIs('dosen.mk.beranda') ? 'active' : ''); ?>">
                                <span class="material-symbols-outlined">home</span>
                                <span>Beranda</span>
                            </a>
                            <a href="<?php echo e(route('dosen.mk.input-nilai')); ?>"
                                class="nb-nav-sub-item <?php echo e(request()->routeIs('dosen.mk.input-nilai') ? 'active' : ''); ?>">
                                <span class="material-symbols-outlined">edit_note</span>
                                <span>Input Nilai</span>
                            </a>
                            <a href="<?php echo e(route('dosen.mk.lihat-nilai')); ?>"
                                class="nb-nav-sub-item <?php echo e(request()->routeIs('dosen.mk.lihat-nilai') ? 'active' : ''); ?>">
                                <span class="material-symbols-outlined">analytics</span>
                                <span>Lihat Nilai</span>
                            </a>
                        <?php else: ?>
                            <div class="nb-locked">🔒 Fitur Mata Kuliah<br><span>Hanya untuk Dosen MK</span></div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 8px; padding-top: 8px;">
                    <a href="<?php echo e(route('dosen.profil')); ?>"
                        class="nb-nav-item <?php echo e(request()->routeIs('dosen.profil') ? 'active' : ''); ?>"
                        data-label="Profil Saya">
                        <span class="material-symbols-outlined">person</span>
                        <span>Profil Saya</span>
                    </a>
                </div>
            </nav>

            <div class="nb-sidebar-footer">
                <div class="nb-status-dot"><span>Sistem Aktif</span></div>
            </div>
        </aside>

        
        <div class="nb-main">
            <header class="nb-topbar">
                <div class="flex items-center gap-3">
                    <button class="nb-btn-icon nb-hamburger" onclick="nbToggleSidebar()" aria-label="Toggle menu">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="min-w-0">
                        <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
                            <?php echo $__env->yieldContent('breadcrumb'); ?>
                        <?php else: ?>
                            <div class="nb-breadcrumb">
                                <a
                                    href="<?php echo e(session('is_dosen_wali') ? route('dosen.wali.beranda') : route('dosen.mk.beranda')); ?>">Dosen</a>
                                <span class="sep">/</span>
                                <span><?php echo e(trim(View::yieldContent('page_title', 'Beranda'))); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col items-end leading-tight nb-clock">
                        <span class="date" id="nbDate"></span>
                        <span class="time" id="nbTime"></span>
                    </div>
                    <div class="nb-popup-anchor">
                        <button type="button" class="nb-avatar-sm" onclick="nbToggleProfilePopup()">
                            <span class="material-symbols-outlined">person</span>
                        </button>
                        <div class="nb-popup" id="nbProfilePopup">
                            <div class="nb-popup-header">
                                <div class="nb-popup-name"><?php echo e(session('user_name', 'Dosen')); ?></div>
                                <div class="nb-popup-meta"><?php echo e(session('user_email', 'dosen@univ.ac.id')); ?></div>
                            </div>
                            <div class="nb-popup-body">
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="nb-popup-item danger">
                                        <span class="material-symbols-outlined">logout</span>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="nb-content">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        function nbToggleSidebar() {
            const sidebar = document.getElementById('nbSidebar');
            const overlay = document.getElementById('nbOverlay');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                // Desktop: toggle collapsed, pastikan mobile state bersih
                sidebar?.classList.remove('open');
                overlay?.classList.remove('open');
                sidebar?.classList.toggle('collapsed');
                localStorage.setItem('nbSidebarCollapsed', sidebar?.classList.contains('collapsed') ? '1' : '0');
            } else {
                // Mobile: toggle open, pastikan collapsed tidak ikut
                sidebar?.classList.remove('collapsed');
                sidebar?.classList.toggle('open');
                overlay?.classList.toggle('open');
            }
        }

        function nbToggleNavGroup(id) {
            const sidebar = document.getElementById('nbSidebar');
            if (sidebar?.classList.contains('collapsed') && window.innerWidth >= 1024) {
                sidebar.classList.remove('collapsed');
                localStorage.setItem('nbSidebarCollapsed', '0');
            }
            document.getElementById(id)?.classList.toggle('open');
        }

        function nbToggleProfilePopup() {
            document.getElementById('nbProfilePopup')?.classList.toggle('open');
        }

        document.addEventListener('click', function (e) {
            const popup = document.getElementById('nbProfilePopup');
            const anchor = popup?.closest('.nb-popup-anchor');
            if (popup && anchor && !anchor.contains(e.target)) popup.classList.remove('open');
        });

        function nbUpdateClock() {
            const now = new Date();
            const d = document.getElementById('nbDate');
            const t = document.getElementById('nbTime');
            if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            if (t) t.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        document.addEventListener('DOMContentLoaded', () => {
            nbUpdateClock();
            setInterval(nbUpdateClock, 30000);
            // Hanya apply collapsed di desktop
            if (window.innerWidth >= 1024 && localStorage.getItem('nbSidebarCollapsed') === '1') {
                document.getElementById('nbSidebar')?.classList.add('collapsed');
            } else {
                document.getElementById('nbSidebar')?.classList.remove('collapsed');
            }
        });

        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('nbSidebar');
            const overlay = document.getElementById('nbOverlay');
            if (window.innerWidth >= 1024) {
                // Pindah ke desktop: bersihkan mobile state
                sidebar?.classList.remove('open');
                overlay?.classList.remove('open');
            } else {
                // Pindah ke mobile: bersihkan desktop state
                sidebar?.classList.remove('collapsed');
                sidebar?.classList.remove('open');
                overlay?.classList.remove('open');
            }
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/layouts/dosen.blade.php ENDPATH**/ ?>