<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAKAR - Dosen Mata Kuliah</title>
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
            --accent-orange: #F4A261;
            --accent-green: #7FB77E;
            --accent-blue: #8FBFE0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-gray);
        }

        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .bg-secondary { background-color: var(--secondary); }
        .bg-dark { background-color: var(--dark); }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar-link svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
        }
    </style>
</head>
<body class="h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-dark flex flex-col justify-between hidden md:flex">
        <div>
            <!-- Logo -->
            <div class="p-6 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="w-12 h-12 object-contain">                
                <div>
                    <h1 class="font-bold text-white text-lg leading-tight">SIPAKAR</h1>
                    <p class="text-xs text-gray-300">Beranda Dosen MK</p>
                </div>
            </div>

            <!-- User Profile -->
            <div class="px-6 py-4 border-b border-gray-600">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden">
                        <svg class="w-6 h-6 text-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white truncate w-40">Cyntia Lasmi Andesti</p>
                        <p class="text-xs text-gray-300">MKI@univ.ac.id</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <a href="{{ route('pages.dosen_matkul.beranda') }}" class="sidebar-link {{ request()->routeIs('pages.dosen_matkul.beranda') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('pages.dosen_matkul.input-nilai') }}" class="sidebar-link {{ request()->routeIs('pages.dosen_matkul.input-nilai') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Input Nilai
                </a>
                <a href="{{ route('pages.dosen_matkul.lihat-nilai') }}" class="sidebar-link {{ request()->routeIs('pages.dosen_matkul.lihat-nilai') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 ２h-２a２ ２ ０ ０１-２-２z"></path>
                    </svg>
                    Nilai Mahasiswa
                </a>
                <a href="{{ route('pages.dosen_matkul.profil') }}" class="sidebar-link {{ request()->routeIs('pages.dosen_matkul.profil') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <div class="p-4">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-500 rounded-lg text-gray-300 hover:bg-white hover:text-dark transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto p-8">
        @yield('content')
    </main>
    @stack('scripts')

</body>
</html>