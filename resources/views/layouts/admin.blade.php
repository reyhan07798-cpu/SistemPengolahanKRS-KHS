<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPAKAR - Admin Dashboard')</title>
    @vite('resources/css/admin.css')
</head>
<body>
    <div class="app-wrap">
        <div class="sidebar">
            <div class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPAKAR" class="w-8 h-8 object-contain">
                <div class="brand-text">
                    <h1>SIPAKAR</h1>
                    <p>Beranda Admin</p>
                </div>

            <div class="menu-title">Menu Utama</div>

            <nav class="menu-list">
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon">▦</span>
                    <span class="menu-label">Beranda</span>
                </a>
                <a href="{{ route('admin.mahasiswa.index') }}" class="menu-item {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                    <span class="menu-icon">👥</span>
                    <span class="menu-label">Data Mahasiswa</span>
                </a>
                <a href="{{ route('admin.dosen.index') }}" class="menu-item {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
                    <span class="menu-icon">👨‍🏫</span>
                    <span class="menu-label">Data Dosen</span>
                </a>
                <a href="{{ route('admin.matakuliah.index') }}" class="menu-item {{ request()->routeIs('admin.matakuliah.*') ? 'active' : '' }}">
                    <span class="menu-icon">📚</span>
                    <span class="menu-label">Data Mata Kuliah</span>
                </a>
                <a href="{{ route('admin.tahunajaran.index') }}" class="menu-item {{ request()->routeIs('admin.tahunajaran.*') ? 'active' : '' }}">
                    <span class="menu-icon">📅</span>
                    <span class="menu-label">Tahun Ajaran</span>
                </a>
                <a href="{{ route('admin.paketmk.index') }}" class="menu-item {{ request()->routeIs('admin.paketmk.*') ? 'active' : '' }}">
                    <span class="menu-icon">📦</span>
                    <span class="menu-label">Paket MK</span>
                </a>
            </nav>

            <a href="{{ url('/') }}" class="logout-link">
                <span class="logout-icon">⏻</span>
                <span>Keluar</span>
            </a>
        </div>

        <div class="content">
            @yield('content')
        </div>
</body>
</html>
