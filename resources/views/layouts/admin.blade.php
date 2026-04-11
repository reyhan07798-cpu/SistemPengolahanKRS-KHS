@extends('layouts.app')
<body>

<div class="d-flex">

    <!-- SIDEBAR ADMIN -->
    <div class="sidebar p-3">
        <h4>SIPAKAR</h4>
        <p>Admin</p>

        <hr>

        <a href="/admin/dashboard">🏠 Beranda</a>
        <a href="/mahasiswa">🎓 Data Mahasiswa</a>
        <a href="#">👨‍🏫 Data Dosen</a>
        <a href="#">📚 Mata Kuliah</a>
    </div>

    <div class="flex-fill p-4">
        @yield('content')
    </div>

</div>

</body>