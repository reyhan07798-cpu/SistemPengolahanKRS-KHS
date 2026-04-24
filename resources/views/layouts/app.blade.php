<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAKAR - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#4B5320',
                        'sidebar-hover': '#5d6c29',
                        'sidebar-active': '#3a4118',
                        'jet-black': {
                            50: 'rgb(238 244 246)', 100: 'rgb(221 234 238)', 200: 'rgb(187 213 221)',
                            300: 'rgb(153 192 204)', 400: 'rgb(119 171 187)', 500: 'rgb(85 150 170)',
                            600: 'rgb(68 120 136)', 700: 'rgb(51 90 102)', 800: 'rgb(34 60 68)',
                            900: 'rgb(17 30 34)', 950: 'rgb(12 21 24)'
                        },
                        'cerulean': {
                            50: 'rgb(238 245 247)', 100: 'rgb(220 234 239)', 200: 'rgb(185 213 223)',
                            300: 'rgb(150 193 207)', 400: 'rgb(115 172 191)', 500: 'rgb(80 151 175)',
                            600: 'rgb(64 121 140)', 700: 'rgb(48 91 105)', 800: 'rgb(32 60 70)',
                            900: 'rgb(16 30 35)', 950: 'rgb(11 21 24)'
                        },
                        'tropical-teal': {
                            50: 'rgb(239 245 245)', 100: 'rgb(223 236 234)', 200: 'rgb(191 217 213)',
                            300: 'rgb(159 198 193)', 400: 'rgb(128 179 172)', 500: 'rgb(96 159 151)',
                            600: 'rgb(77 128 121)', 700: 'rgb(57 96 91)', 800: 'rgb(38 64 60)',
                            900: 'rgb(19 32 30)', 950: 'rgb(13 22 21)'
                        },
                        'muted-teal': {
                            50: 'rgb(239 245 240)', 100: 'rgb(224 235 226)', 200: 'rgb(193 215 196)',
                            300: 'rgb(162 195 167)', 400: 'rgb(131 175 137)', 500: 'rgb(99 156 108)',
                            600: 'rgb(80 124 86)', 700: 'rgb(60 93 65)', 800: 'rgb(40 62 43)',
                            900: 'rgb(20 31 22)', 950: 'rgb(14 22 15)'
                        },
                        'tea-green': {
                            50: 'rgb(242 246 238)', 100: 'rgb(228 238 221)', 200: 'rgb(201 220 188)',
                            300: 'rgb(174 203 154)', 400: 'rgb(148 186 120)', 500: 'rgb(121 168 87)',
                            600: 'rgb(97 135 69)', 700: 'rgb(72 101 52)', 800: 'rgb(48 67 35)',
                            900: 'rgb(24 34 17)', 950: 'rgb(17 24 12)'
                        }
                    },
                    fontFamily: {
                        display: ['Poppins', 'sans-serif'],
                        body: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #e5e7eb; }
        ::-webkit-scrollbar-thumb { background: rgb(96 159 151); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #4B5320; }
        
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-slide-up { animation: slideUp 0.5s ease-out forwards; }
        .animate-fade { animation: fadeIn 0.4s ease-out forwards; }
        
        .bg-pattern {
            background-image: radial-gradient(circle at 10% 90%, rgba(121, 168, 87, 0.1) 0%, transparent 40%),
                              radial-gradient(circle at 90% 10%, rgba(80, 151, 175, 0.08) 0%, transparent 40%);
        }
        .nav-link { position: relative; transition: all 0.2s ease; }
        .nav-link::before {
            content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 3px;
            background: rgb(174 203 154); transform: scaleY(0); transition: transform 0.2s ease;
        }
        .nav-link:hover::before, .nav-link.active::before { transform: scaleY(1); }
        .sidebar-active-item { background: rgba(255, 255, 255, 0.1); border-left: 3px solid rgb(174 203 154); }
        .sidebar-mobile { transform: translateX(-100%); transition: transform 0.3s ease; }
        .sidebar-mobile.open { transform: translateX(0); }
    </style>
</head>
<body class="bg-tea-green-50 font-body min-h-screen">
    
    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-jet-black-900/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-sidebar sidebar-mobile lg:transform-none flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-tea-green-300 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-sidebar" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display font-bold text-white text-lg tracking-tight">SIPAKAR</h1>
                        <p class="text-tea-green-200 text-xs">Sistem Pendidikan Akademik</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-active-item text-white' : 'text-tea-green-100 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-medium">Beranda</span>
                </a>
                
                <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'sidebar-active-item text-white' : 'text-tea-green-100 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Data Mahasiswa</span>
                </a>
                
                <a href="{{ route('admin.dosen.index') }}" class="nav-link {{ request()->routeIs('admin.dosen.*') ? 'sidebar-active-item text-white' : 'text-tea-green-100 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="font-medium">Data Dosen</span>
                </a>
                
                <a href="{{ route('admin.matakuliah.index') }}" class="nav-link {{ request()->routeIs('admin.matakuliah.*') ? 'sidebar-active-item text-white' : 'text-tea-green-100 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-medium">Mata Kuliah</span>
                </a>

                <a href="{{ route('admin.tahunajaran.index') }}" class="nav-link {{ request()->routeIs('admin.tahunajaran.*') ? 'sidebar-active-item text-white' : 'text-tea-green-100 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-medium">Tahun Ajaran</span>
                </a>

                <a href="{{ route('admin.paketmk.index') }}" class="nav-link {{ request()->routeIs('admin.paketmk.*') ? 'sidebar-active-item text-white' : 'text-tea-green-100 hover:text-white hover:bg-white/5' }} flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="font-medium">Kelola Paket MK</span>
                </a>
            </nav>
            
            <!-- Tombol Logout (DIPERBAIKI: Sekarang Selalu Terlihat) -->
            <div class="p-4 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-2 py-2 text-tea-green-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors text-sm group">
                        <svg class="w-5 h-5 text-tea-green-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>

            <!-- User Profile -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-tea-green-300 to-muted-teal-400 flex items-center justify-center">
                        <span class="text-sidebar font-bold text-sm">AD</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium text-sm truncate">Administrator</p>
                        <p class="text-tea-green-200 text-xs truncate">admin@sipakar.ac.id</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 lg:ml-0">
            <!-- Top Bar -->
            <header class="bg-white/80 backdrop-blur-sm border-b border-tea-green-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 lg:px-8 py-4">
                    
                    <!-- TOMBOL HAMBURGER (TOMBOL TOGGLE SIDEBAR) -->
                    <!-- lg:hidden artinya tombol ini HILANG di layar besar (Desktop), muncul di HP -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-tea-green-100 transition-colors">
                        <svg class="w-6 h-6 text-jet-black-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <!-- Ikon Hamburger (3 Garis) -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Breadcrumb Desktop -->
                    <div class="hidden lg:flex items-center gap-2 text-sm">
                        <span class="text-jet-black-400">Dashboard</span>
                        <svg class="w-4 h-4 text-jet-black-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-sidebar font-medium">Beranda</span>
                    </div>
                    
                    <div class="hidden sm:block text-right">
                        <p class="text-xs text-jet-black-400" id="currentDate"></p>
                        <p class="text-sm font-medium text-jet-black-700" id="currentTime"></p>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="p-4 lg:p-8 bg-pattern min-h-screen">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('hidden');
            }
        }

        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            const dateElement = document.getElementById('currentDate');
            const timeElement = document.getElementById('currentTime');
            if (dateElement) dateElement.textContent = now.toLocaleDateString('id-ID', dateOptions);
            if (timeElement) timeElement.textContent = now.toLocaleTimeString('id-ID', timeOptions);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateDateTime();
            setInterval(updateDateTime, 60000);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                if (sidebar && overlay) {
                    sidebar.classList.remove('open');
                    overlay.classList.add('hidden');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>