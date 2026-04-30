<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAKAR - Dosen Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #2F5D8A;
            --secondary: #4A7FB5;
            --dark: #24496B;
            --white: #FFFFFF;
            --light-gray: #F4F6F8;
            --gray: #E0E5EC;
            --text-gray: #6B7280;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-gray);
            margin: 0;
            padding: 0;
        }

        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .bg-secondary { background-color: var(--secondary); }
        .bg-dark { background-color: var(--dark); }
        .text-dark { color: var(--dark); }
        .text-white { color: var(--white); }

        /* Sidebar Link Styles */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar-link svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        /* Submenu Styles */
        .submenu {
            max-height: 0;
            overflow: hidden;
            padding-left: 1rem;
            margin-left: 1rem;
            border-left: 2px solid rgba(255, 255, 255, 0.15);
            transition: max-height 0.3s ease-out;
        }

        .submenu.open {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }

        /* Toggle Button Styles */
        .toggle-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.8);
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
            flex-shrink: 0;
            text-align: left;
        }

        .toggle-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .arrow-icon {
            width: 1rem;
            height: 1rem;
            transition: transform 0.25s;
            color: rgba(255, 255, 255, 0.5);
            flex-shrink: 0;
        }

        .arrow-icon.rotated {
            transform: rotate(180deg);
        }

        /* Custom Scrollbar untuk Nav */
        .nav-scrollable {
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 4px;
        }

        .nav-scrollable::-webkit-scrollbar {
            width: 4px;
        }

        .nav-scrollable::-webkit-scrollbar-track {
            background: transparent;
        }

        .nav-scrollable::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .nav-scrollable::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Locked Feature Box */
        .locked-feature {
            padding: 0.75rem;
            font-size: 0.75rem;
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.12);
            border-radius: 0.5rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-style: italic;
            margin: 0.25rem 0;
        }

        .locked-feature span {
            font-size: 0.625rem;
            opacity: 0.7;
        }

        /* Section Title */
        .section-title {
            font-size: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.38);
            font-weight: 700;
            padding: 0 0.5rem;
            margin: 0 0 0.5rem;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2F5D8A',
                        secondary: '#4A7FB5',
                        dark: '#24496B',
                    }
                }
            }
        }
    </script>
</head>

<body class="h-screen flex overflow-hidden">

    <aside class="w-64 bg-dark flex flex-col hidden md:flex overflow-hidden">
        
        <!-- Logo & Profile (Fixed - Tidak Scroll) -->
        <div class="flex-shrink-0">
            <!-- Logo -->
            <div class="p-6 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="font-bold text-white text-lg leading-tight">SIPAKAR</h1>
                    <p class="text-xs text-gray-300">Portal Dosen</p>
                </div>
            </div>

            <!-- Profile -->
            <div class="px-6 py-4 border-b border-gray-600">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                        <svg class="w-6 h-6 text-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-white truncate w-40" title="{{ session('user_name') }}">
                            {{ session('user_name', 'Dosen') }}
                        </p>
                        <p class="text-xs text-gray-300">{{ session('role_display', 'Dosen') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation (Scrollable Area) -->
        <nav class="flex-1 nav-scrollable">
            
            <!-- Dosen Wali Menu -->
            <div class="mb-1">
                <button class="toggle-btn" onclick="toggleMenu('wali')" type="button">
                    <span class="font-medium">Dosen Wali</span>
                    <svg class="arrow-icon" id="arrow-wali" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div class="submenu" id="submenu-wali">
                    @if(session('is_dosen_wali'))
                        <a href="{{ route('dosen.wali.beranda') }}" class="sidebar-link {{ request()->routeIs('dosen.wali.beranda') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Beranda
                        </a>
                        <a href="{{ route('dosen.wali.krs-verifikasi') }}" class="sidebar-link {{ request()->routeIs('dosen.wali.krs-verifikasi') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Verifikasi KRS
                        </a>
                        <a href="{{ route('dosen.wali.khs') }}" class="sidebar-link {{ request()->routeIs('dosen.wali.khs') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            KHS Mahasiswa
                        </a>
                    @else
                        <div class="locked-feature">
                            🔒 Fitur Wali<br>
                            <span>Hanya untuk Dosen Wali</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dosen Mata Kuliah Menu -->
            <div class="mb-1">
                <button class="toggle-btn" onclick="toggleMenu('matkul')" type="button">
                    <span class="font-medium">Dosen Mata Kuliah</span>
                    <svg class="arrow-icon" id="arrow-matkul" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div class="submenu" id="submenu-matkul">
                    @if(session('is_dosen_mk'))
                        <a href="{{ route('dosen.mk.beranda') }}" class="sidebar-link {{ request()->routeIs('dosen.mk.beranda') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Beranda
                        </a>
                        <a href="{{ route('dosen.mk.input-nilai') }}" class="sidebar-link {{ request()->routeIs('dosen.mk.input-nilai') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Input Nilai
                        </a>
                        <a href="{{ route('dosen.mk.lihat-nilai') }}" class="sidebar-link {{ request()->routeIs('dosen.mk.lihat-nilai') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Nilai
                        </a>
                    @else
                        <div class="locked-feature">
                            🔒 Fitur Mata Kuliah<br>
                            <span>Hanya untuk Dosen Mata Kuliah</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profil Section -->
            <div class="border-t border-gray-600 pt-4 mt-2">
                <p class="section-title">Akun</p>
                <a href="{{ route('dosen.profil') }}" class="sidebar-link {{ request()->routeIs('dosen.profil') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil Saya
                </a>
            </div>
        </nav>

        <!-- Logout (Fixed Bottom - Tidak Scroll) -->
        <div class="flex-shrink-0 px-4 pb-6 border-t border-gray-600 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-500 rounded-lg text-gray-300 hover:bg-white hover:text-dark transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
        
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8 bg-[#F4F6F8]">
        @yield('content')
    </main>

<script>
    function toggleMenu(name) {
        var sub = document.getElementById('submenu-' + name);
        var arrow = document.getElementById('arrow-' + name);
        if (!sub) return;
        
        // Toggle class untuk animasi
        sub.classList.toggle('open');
        if (arrow) arrow.classList.toggle('rotated');
        
        // Auto-scroll ke submenu jika terbuka dan posisinya terpotong
        if (sub.classList.contains('open')) {
            setTimeout(function() {
                var sidebar = document.querySelector('aside');
                var subRect = sub.getBoundingClientRect();
                var sidebarRect = sidebar.getBoundingClientRect();
                
                // Jika bagian bawah submenu melewati batas sidebar, scroll ke sana
                if (subRect.bottom > sidebarRect.bottom - 20) {
                    sub.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 150); // Delay kecil agar animasi max-height selesai dulu
        }
    }
    
    // Auto-buka submenu yang memiliki link aktif saat page load
    document.addEventListener('DOMContentLoaded', function () {
        ['wali', 'matkul'].forEach(function (name) {
            var sub = document.getElementById('submenu-' + name);
            if (sub && sub.querySelector('.active')) {
                sub.classList.add('open');
                var arrow = document.getElementById('arrow-' + name);
                if (arrow) arrow.classList.add('rotated');
                
                // Scroll ke submenu aktif
                setTimeout(function() {
                    sub.scrollIntoView({ behavior: 'auto', block: 'nearest' });
                }, 100);
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>