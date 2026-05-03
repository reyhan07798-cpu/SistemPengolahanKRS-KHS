<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPAKAR - @yield('title', 'Sistem Pendidikan Akademik')</title>
    <link rel="icon" type="image/png" sizes="16x16 32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="nb-overlay" id="nbOverlay" onclick="nbToggleSidebar()"></div>

    <div class="nb-app">

        <aside class="nb-sidebar" id="nbSidebar">
            <div class="nb-sidebar-brand">
                <div class="w-10 h-10 bg-white border-2 border-ink rounded-md flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="w-6 h-6 object-contain" onerror="this.nextElementSibling.style.display='block'">
                    <svg class="w-6 h-6 text-primary fallback-logo" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1>SIPAKAR</h1>
                    <p>Sistem Akademik</p>
                </div>
            </div>

            <div class="nb-sidebar-section-title">Navigasi</div>

            <nav class="nb-sidebar-nav">
                <a href="{{ route('pages.admin.dashboard') }}" class="nb-nav-item {{ request()->routeIs('pages.admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('pages.admin.mahasiswa.index') }}" class="nb-nav-item {{ request()->routeIs('pages.admin.mahasiswa.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Data Mahasiswa</span>
                </a>
                <a href="{{ route('pages.admin.dosen.index') }}" class="nb-nav-item {{ request()->routeIs('pages.admin.dosen.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Data Dosen</span>
                </a>
                <a href="{{ route('pages.admin.matakuliah.index') }}" class="nb-nav-item {{ request()->routeIs('pages.admin.matakuliah.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Mata Kuliah</span>
                </a>
                <a href="{{ route('pages.admin.tahunajaran.index') }}" class="nb-nav-item {{ request()->routeIs('pages.admin.tahunajaran.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Tahun Ajaran</span>
                </a>
                <a href="{{ route('pages.admin.paketmk.index') }}" class="nb-nav-item {{ request()->routeIs('pages.admin.paketmk.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Paket MK</span>
                </a>
            </nav>

            <div class="nb-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nb-btn nb-btn-danger nb-btn-sm w-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="nb-main">
            <header class="nb-topbar">
                <div class="flex items-center gap-3">
                <button class="nb-btn-icon nb-hamburger" onclick="nbToggleSidebar()" aria-label="Toggle menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <span class="nb-eyebrow">SIPAKAR</span>
                    <span class="nb-topbar-title">@yield('page_title', 'Beranda')</span>
                </div>
                <div class="hidden sm:flex flex-col items-end">
                    <span class="text-xs font-bold uppercase tracking-wider text-muted" id="nbDate"></span>
                    <span class="text-sm font-bold text-ink" id="nbTime"></span>
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
        function nbUpdateClock() {
            const now = new Date();
            const d = document.getElementById('nbDate');
            const t = document.getElementById('nbTime');
            if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            if (t) t.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
        document.addEventListener('DOMContentLoaded', () => {
            nbUpdateClock();
            setInterval(nbUpdateClock, 60000);
            if (window.innerWidth >= 1024 && localStorage.getItem('nbSidebarCollapsed') === '1') {
                document.getElementById('nbSidebar')?.classList.add('collapsed');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
