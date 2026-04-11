@extends('layouts.app')
<body>

<div class="d-flex">

    <!-- SIDEBAR MAHASISWA -->
    <div class="sidebar p-3">
        <h4>SIPAKAR</h4>
        <p>Mahasiswa</p>

        <hr>

        <a href="/mahasiswa/dashboard">🏠 Beranda</a>
        <a href="#">📝 Ambil KRS</a>
        <a href="#">📄 Lihat KHS</a>
        <a href="#">👤 Profil</a>
    </div>

    <div class="flex-fill p-4">
        @yield('content')
    </div>

</div>

</body>