<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPAKAR - Mahasiswa')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body data-role="mahasiswa">
    <div class="nb-overlay" id="nbOverlay" onclick="nbToggleSidebar()"></div>

    <div class="nb-app">
        <!-- Sidebar -->
        <aside class="nb-sidebar" id="nbSidebar">
            <div class="nb-sidebar-brand">
                <div
                    class="w-12 h-12 rounded-full border-2 border-white/20 bg-white flex items-center justify-center shrink-0 overflow-hidden p-1">
                    <img src="{{ asset('images/logo-dashboard.png') }}" alt="Logo SIPAKAR"
                        class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <h1>SIPAKAR</h1>
                    <p>Mahasiswa</p>
                </div>
            </div>

            <div class="nb-sidebar-section-title">Menu</div>

            <nav class="nb-sidebar-nav">
                <a href="{{ route('pages.mahasiswa.beranda') }}"
                    class="nb-nav-item {{ request()->routeIs('pages.mahasiswa.beranda') ? 'active' : '' }}"
                    data-label="Beranda">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('pages.mahasiswa.ambil-krs') }}"
                    class="nb-nav-item {{ request()->routeIs('pages.mahasiswa.ambil-krs') ? 'active' : '' }}"
                    data-label="Ambil KRS">
                    <span class="material-symbols-outlined">assignment</span>
                    <span>Ambil KRS</span>
                </a>
                <a href="{{ route('pages.mahasiswa.lihat-khs') }}"
                    class="nb-nav-item {{ request()->routeIs('pages.mahasiswa.lihat-khs') ? 'active' : '' }}"
                    data-label="Lihat KHS">
                    <span class="material-symbols-outlined">grade</span>
                    <span>Lihat KHS</span>
                </a>
                <a href="{{ route('pages.mahasiswa.profil') }}"
                    class="nb-nav-item {{ request()->routeIs('pages.mahasiswa.profil') ? 'active' : '' }}"
                    data-label="Profil">
                    <span class="material-symbols-outlined">person</span>
                    <span>Profil</span>
                </a>
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
                        @hasSection('breadcrumb')
                            @yield('breadcrumb')
                        @else
                            <div class="nb-breadcrumb">
                                <a href="{{ route('pages.mahasiswa.beranda') }}">Mahasiswa</a>
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
                        <button type="button" class="nb-avatar-sm" onclick="nbToggleProfilePopup()"
                            aria-label="Profil pengguna" aria-haspopup="true">
                            <span class="material-symbols-outlined filled">person</span>
                        </button>
                        <div class="nb-popup" id="nbProfilePopup">
                            <div class="nb-popup-header">
                                <div class="nb-popup-name">{{ $data['nama'] ?? 'Mahasiswa' }}</div>
                                <div class="nb-popup-meta">{{ $data['email'] ?? 'mahasiswa@univ.ac.id' }}</div>
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