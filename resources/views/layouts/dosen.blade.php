<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPAKAR - Dosen')</title>
    
    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body data-role="dosen">
    <div class="nb-overlay" id="nbOverlay" onclick="nbToggleSidebar()"></div>

    <div class="nb-app">
        {{-- SIDEBAR --}}
        <aside class="nb-sidebar" id="nbSidebar">
            <div class="nb-sidebar-brand">
                <div class="w-12 h-12 rounded-full border-2 border-white/20 bg-white flex items-center justify-center shrink-0 overflow-hidden p-1">
                    <img src="{{ asset('images/logo-dashboard.png') }}" alt="Logo SIPAKAR" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <h1>SIPAKAR</h1>
                    <p>Portal Dosen</p>
                </div>
            </div>

            <div class="nb-sidebar-section-title">Menu Utama</div>

            <nav class="nb-sidebar-nav">
                {{-- Dosen Wali --}}
                @php
                    $isWaliActive = request()->routeIs('dosen.wali.*');
                    $hasWaliRole = session('is_dosen_wali', false);
                @endphp
                <div class="nb-nav-group {{ $isWaliActive ? 'open' : '' }}" id="navGroupWali">
                    <button type="button" class="nb-nav-trigger {{ $isWaliActive ? 'has-active' : '' }}" 
                            onclick="nbToggleNavGroup('navGroupWali')">
                        <span class="material-symbols-outlined">supervisor_account</span>
                        <span>Dosen Wali</span>
                        <span class="material-symbols-outlined nb-nav-chevron">expand_more</span>
                    </button>
                    <div class="nb-nav-submenu">
                        @if($hasWaliRole)
                            <a href="{{ route('dosen.wali.beranda') }}" class="nb-nav-sub-item {{ request()->routeIs('dosen.wali.beranda') ? 'active' : '' }}">
                                <span class="material-symbols-outlined">home</span>
                                <span>Beranda</span>
                            </a>
                            <a href="{{ route('dosen.wali.krs-verifikasi') }}" class="nb-nav-sub-item {{ request()->routeIs('dosen.wali.krs-verifikasi') ? 'active' : '' }}">
                                <span class="material-symbols-outlined">fact_check</span>
                                <span>Verifikasi KRS</span>
                            </a>
                            <a href="{{ route('dosen.wali.khs') }}" class="nb-nav-sub-item {{ request()->routeIs('dosen.wali.khs') ? 'active' : '' }}">
                                <span class="material-symbols-outlined">assessment</span>
                                <span>KHS Mahasiswa</span>
                            </a>
                        @else
                            <div class="nb-locked">🔒 Fitur Wali<br><span>Hanya untuk Dosen Wali</span></div>
                        @endif
                    </div>
                </div>

                {{-- Dosen Matkul --}}
                @php
                    $isMatkulActive = request()->routeIs('dosen.mk.*');
                    $hasMatkulRole = session('is_dosen_mk', false);
                @endphp
                <div class="nb-nav-group {{ $isMatkulActive ? 'open' : '' }}" id="navGroupMatkul">
                    <button type="button" class="nb-nav-trigger {{ $isMatkulActive ? 'has-active' : '' }}" 
                            onclick="nbToggleNavGroup('navGroupMatkul')">
                        <span class="material-symbols-outlined">co_present</span>
                        <span>Dosen Matkul</span>
                        <span class="material-symbols-outlined nb-nav-chevron">expand_more</span>
                    </button>
                    <div class="nb-nav-submenu">
                        @if($hasMatkulRole)
                            <a href="{{ route('dosen.mk.beranda') }}" class="nb-nav-sub-item {{ request()->routeIs('dosen.mk.beranda') ? 'active' : '' }}">
                                <span class="material-symbols-outlined">home</span>
                                <span>Beranda</span>
                            </a>
                            <a href="{{ route('dosen.mk.input-nilai') }}" class="nb-nav-sub-item {{ request()->routeIs('dosen.mk.input-nilai') ? 'active' : '' }}">
                                <span class="material-symbols-outlined">edit_note</span>
                                <span>Input Nilai</span>
                            </a>
                            <a href="{{ route('dosen.mk.lihat-nilai') }}" class="nb-nav-sub-item {{ request()->routeIs('dosen.mk.lihat-nilai') ? 'active' : '' }}">
                                <span class="material-symbols-outlined">analytics</span>
                                <span>Lihat Nilai</span>
                            </a>
                        @else
                            <div class="nb-locked">🔒 Fitur Mata Kuliah<br><span>Hanya untuk Dosen MK</span></div>
                        @endif
                    </div>
                </div>

                {{-- Profil --}}
                <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 8px; padding-top: 8px;">
                    <a href="{{ route('dosen.profil') }}" class="nb-nav-item {{ request()->routeIs('dosen.profil') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">person</span>
                        <span>Profil Saya</span>
                    </a>
                </div>
            </nav>

            <div class="nb-sidebar-footer">
                <div class="nb-status-dot"><span>Sistem Aktif</span></div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="nb-main">
            <header class="nb-topbar">
                <div class="flex items-center gap-3">
                    <button class="nb-hamburger" onclick="nbToggleSidebar()">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="min-w-0">
                        @hasSection('breadcrumb')
                            @yield('breadcrumb')
                        @else
                            <div class="nb-breadcrumb">
                                <a href="{{ session('is_dosen_wali') ? route('dosen.wali.beranda') : route('dosen.mk.beranda') }}">Dosen</a>
                                <span class="sep">/</span>
                                <span>{{ trim(View::yieldContent('page_title', 'Beranda')) }}</span>
                            </div>
                        @endif
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
                                <div class="nb-popup-name">{{ session('user_name', 'Dosen') }}</div>
                                <div class="nb-popup-meta">{{ session('user_email', 'dosen@univ.ac.id') }}</div>
                            </div>
                            <div class="nb-popup-body">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
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
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function nbToggleSidebar() {
            const sidebar = document.getElementById('nbSidebar');
            const overlay = document.getElementById('nbOverlay');
            const isDesktop = window.innerWidth >= 1024;
            if (isDesktop) {
                sidebar?.classList.toggle('collapsed');
                localStorage.setItem('nbSidebarCollapsed', sidebar?.classList.contains('collapsed') ? '1' : '0');
            } else {
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

        document.addEventListener('click', function(e) {
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
            if (window.innerWidth >= 1024 && localStorage.getItem('nbSidebarCollapsed') === '1') {
                document.getElementById('nbSidebar')?.classList.add('collapsed');
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('nbSidebar')?.classList.remove('open');
                document.getElementById('nbOverlay')?.classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>