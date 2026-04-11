<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIPAKAR Polibatam</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'polibatam-blue': '#1e3a8a',
                        'polibatam-orange': '#f97316',
                    }
                }
            }
        }
    </script>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f7fb;
    }

    .sidebar {
        width: 250px;
        min-height: 100vh;
        background: linear-gradient(180deg, #0ea5a4, #1e3a8a);
        color: white;
    }

    .sidebar a {
        color: white;
        display: block;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 5px;
        text-decoration: none;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.2);
    }

    .card-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
</style>
    @stack('styles')
</head>
<body style="font-family: 'Poppins', sans-serif;">

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar p-3">
        <h4>SIPAKAR</h4>

        <hr>

        <a href="/admin/dashboard">🏠 Beranda</a>
        <a href="/mahasiswa">🎓 Data Mahasiswa</a>
        <a href="#">👨‍🏫 Data Dosen</a>
        <a href="#">📚 Mata Kuliah</a>
        <a href="#">📅 Tahun Ajaran</a>

        <div class="mt-5">
            <a href="#" class="btn btn-light w-100">Logout</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="flex-fill p-4">

        @yield('content')

    </div>

</div>
</body>
</html>