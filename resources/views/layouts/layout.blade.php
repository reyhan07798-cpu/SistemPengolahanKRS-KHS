<!DOCTYPE html>
<html>
<head>
<title>SIPAKAR - Sistem Akademik</title>
    <link rel="icon" type="image/png" sizes="16x16 32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
</head>
<body>

<nav>
    <a href="/home">Home</a> |
    <a href="/about">About</a> |
    <a href="/product">Product</a> |
    <a href="/contact">Contact</a> |
    <a href="/admin/dashboard">Admin</a> |
    <a href="/mahasiswa/beranda">Mahasiswa</a>
</nav>

<hr>

@yield('content')

</body>
</html>