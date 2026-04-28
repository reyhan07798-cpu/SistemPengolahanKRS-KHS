<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAKAR - Dosen Wali</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/dosen_wali.css', 'resources/js/app.js'])

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

        .text-primary {
            color: var(--primary);
        }

        .bg-primary {
            background-color: var(--primary);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .bg-dark {
            background-color: var(--dark);
        }

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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
    <aside class="w-64 bg-dark flex flex-col hidden md:flex">
        <div class="flex-1">
            <!-- Logo -->
            <div class="p-6 flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="font-bold text-white text-lg leading-tight">SIPAKAR</h1>
                    <p class="text-xs text-gray-300">Beranda Dosen Wali</p>
                </div>

            <!-- User Profile -->
            <div class="px-6 py-4 border-b border-gray-600">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden">
                        <svg class="w-6 h-6 text-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white truncate w-40">Rusyda Nazhirah Yunus</p>
                        <p class="text-xs text-gray-300">wali@univ.ac.id</p>
                    </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <a href="{{ route('dosen_wali.beranda') }}"
                    class="sidebar-link {{ request()->routeIs('dosen_wali.beranda') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('dosen_wali.krs.verifikasi') }}"
                    class="sidebar-link {{ request()->routeIs('dosen_wali.krs.verifikasi') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Verifikasi KRS
                </a>
                <a href="{{ route('dosen_wali.khs') }}"
                    class="sidebar-link {{ request()->routeIs('dosen_wali.khs') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    KHS Mahasiswa
                </a>
                <a href="{{ route('dosen_wali.profil') }}"
                    class="sidebar-link {{ request()->routeIs('dosen_wali.profil') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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

</body>

</html>
