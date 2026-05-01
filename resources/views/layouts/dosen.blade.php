<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPAKAR - Dosen')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-role="dosen">
    <div class="nb-overlay" id="nbOverlay" onclick="nbToggleSidebar()"></div>

    <div class="nb-app">

        <aside class="nb-sidebar" id="nbSidebar">
            <div class="nb-sidebar-brand">
                <div class="w-12 h-12 rounded-full border-2 border-ink bg-white flex items-center justify-center shrink-0 overflow-hidden p-1">
                    <img src="{{ asset('images/logo-dashboard.png') }}" alt="Logo SIPAKAR" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <h1>SIPAKAR</h1>
                    <p>Dosen</p>        
                </div>
            </div>

            <div class="nb-sidebar-section-title">Menu</div>

            <nav class="nb-sidebar-nav">
                {{-- Beranda --}}
                <a href="{{ route('pages.dosen.beranda') }}"
                   class="nb-nav-item {{ request()->routeIs('pages.dosen.beranda') ? 'active' : '' }}"
                   data-label="Beranda">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Beranda</span>
                </a>

                {{-- Group: Dosen Wali --}}
                @php
                    $isWaliActive = request()->routeIs('pages.dosen.wali.*');
                @endphp
                <div class="nb-nav-group {{ $isWaliActive ? 'open' : '' }}" id="navGroupWali">
                    <button type="button" class="nb-nav-trigger {{ $isWaliActive ? 'has-active' : '' }}" onclick="nbToggleNavGroup('navGroupWali')" data-label="Dosen Wali">
                        <span class="material-symbols-outlined">supervisor_account</span>
                        <span>Dosen Wali</span>
                        <span class="material-symbols-outlined nb-nav-chevron">expand_more</span>
                    </button>
                    <div class="nb-nav-submenu">
                        <a href="{{ route('pages.dosen.wali.krs.verifikasi') }}"
                           class="nb-nav-sub-item {{ request()->routeIs('pages.dosen.wali.krs.verifikasi') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">fact_check</span>
                            <span>Verifikasi KRS</span>
                        </a>
                        <a href="{{ route('pages.dosen.wali.khs') }}"
                           class="nb-nav-sub-item {{ request()->routeIs('pages.dosen.wali.khs') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">assessment</span>
                            <span>KHS Mahasiswa</span>
                        </a>
                    </div>
                </div>

                {{-- Group: Dosen Matkul --}}
                @php
                    $isMatkulActive = request()->routeIs('pages.dosen.matkul.*');
                @endphp
                <div class="nb-nav-group {{ $isMatkulActive ? 'open' : '' }}" id="navGroupMatkul">
                    <button type="button" class="nb-nav-trigger {{ $isMatkulActive ? 'has-active' : '' }}" onclick="nbToggleNavGroup('navGroupMatkul')" data-label="Dosen Matkul">
                        <span class="material-symbols-outlined">co_present</span>
                        <span>Dosen Matkul</span>
                        <span class="material-symbols-outlined nb-nav-chevron">expand_more</span>
                    </button>
                    <div class="nb-nav-submenu">
                        <a href="{{ route('pages.dosen.matkul.input-nilai') }}"
                           class="nb-nav-sub-item {{ request()->routeIs('pages.dosen.matkul.input-nilai') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">edit_note</span>
                            <span>Input Nilai</span>
                        </a>
                        <a href="{{ route('pages.dosen.matkul.lihat-nilai') }}"
                           class="nb-nav-sub-item {{ request()->routeIs('pages.dosen.matkul.lihat-nilai') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">analytics</span>
                            <span>Lihat Nilai</span>
                        </a>
                    </div>
                </div>

                {{-- Profil --}}
                <a href="{{ route('pages.dosen.profil') }}"
                   class="nb-nav-item {{ request()->routeIs('pages.dosen.profil') ? 'active' : '' }}"
                   data-label="Profil">
                    <span class="material-symbols-outlined">person</span>
                    <span>Profil</span>
                </a>
            </nav>

            <div class="nb-sidebar-footer">
                <div class="nb-status-dot">Sistem Aktif</div>
            </div>
        </aside>

        <div class="nb-main">
            <header class="nb-topbar">
                <div class="flex items-center gap-3">
                    <button class="nb-btn-icon nb-hamburger" onclick="nbToggleSidebar()" aria-label="Toggle menu">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="min-w-0">
                        @hasSection('breadcrumb')
                            @yield('breadcrumb')
                        @else
                            <x-breadcrumb :home="route('pages.dosen.beranda')" :items="[
                                ['label' => 'Dosen', 'url' => route('pages.dosen.beranda')],
                                ['label' => trim(View::yieldContent('page_title', 'Beranda'))]
                            ]" />
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col items-end leading-tight pr-2 border-r-2 border-[rgba(31,41,55,0.15)]">
                        <span class="text-xs font-bold uppercase tracking-wider text-muted" id="nbDate"></span>
                        <span class="text-sm font-bold text-ink" id="nbTime"></span>
                    </div>
                    <div class="nb-popup-anchor">
                        <button type="button" class="nb-avatar-sm" onclick="nbToggleProfilePopup()" aria-label="Profil pengguna" aria-haspopup="true">
                            <span class="material-symbols-outlined filled">person</span>
                        </button>
                        <div class="nb-popup" id="nbProfilePopup">
                            <div class="nb-popup-header">
                                <div class="nb-popup-name">{{ $dosen['nama'] ?? ($data['nama'] ?? 'Dosen') }}</div>
                                <div class="nb-popup-meta">{{ $dosen['email'] ?? ($data['email'] ?? 'dosen@univ.ac.id') }}</div>
                            </div>
                            <div class="nb-popup-body">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nb-popup-item danger">
                                        <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
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
            const isDesktop = window.innerWidth >= 1024;
            if (isDesktop) {
                sidebar?.classList.toggle('collapsed');
                const collapsed = sidebar?.classList.contains('collapsed');
                localStorage.setItem('nbSidebarCollapsed', collapsed ? '1' : '0');
            } else {
                sidebar?.classList.toggle('open');
                document.getElementById('nbOverlay')?.classList.toggle('open');
            }
        }
        function nbToggleMobileSidebar() {
            document.getElementById('nbSidebar')?.classList.toggle('open');
            document.getElementById('nbOverlay')?.classList.toggle('open');
        }
        function nbToggleProfilePopup() {
            document.getElementById('nbProfilePopup')?.classList.toggle('open');
        }
        function nbToggleNavGroup(id) {
            const sidebar = document.getElementById('nbSidebar');
            const isCollapsed = sidebar?.classList.contains('collapsed');
            const isDesktop = window.innerWidth >= 1024;

            if (isCollapsed && isDesktop) {
                // Expand sidebar dulu, lalu pastikan grup terbuka
                sidebar.classList.remove('collapsed');
                localStorage.setItem('nbSidebarCollapsed', '0');
                const group = document.getElementById(id);
                if (group && !group.classList.contains('open')) {
                    group.classList.add('open');
                }
            } else {
                // Normal toggle saat sidebar sudah expanded
                document.getElementById(id)?.classList.toggle('open');
            }
        }
        document.addEventListener('click', function (e) {
            const popup = document.getElementById('nbProfilePopup');
            const anchor = popup?.closest('.nb-popup-anchor');
            if (popup && anchor && !anchor.contains(e.target)) {
                popup.classList.remove('open');
            }
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
    </script>
    @stack('scripts')

    <x-confirm-dialog />
</body>
</html>
