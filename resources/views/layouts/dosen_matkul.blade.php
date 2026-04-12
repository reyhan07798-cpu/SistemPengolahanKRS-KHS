<!DOCTYPE html>
<html>
<head>
    <title>SIPAKAR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background: #f5f7fb;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #0f3c91 0%, #0b6dff 45%, #1d99ff 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(6, 53, 107, 0.25);
        }

        .sidebar h2 {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .brand {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255,255,255,0.08);
            margin-bottom: 20px;
        }

        .brand p {
            margin-top: 8px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.4;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 20px;
            background: rgba(255,255,255,0.08);
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 0.95rem;
        }

        .profile-info {
            line-height: 1.2;
        }

        .profile-name {
            font-weight: 700;
            font-size: 0.98rem;
        }

        .profile-role {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.72);
        }

        .menu-title {
            margin: 0 0 8px;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.72);
            text-transform: uppercase;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 14px;
            border-radius: 16px;
            color: white;
            cursor: pointer;
            background: transparent;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255,255,255,0.14);
        }

        .menu-item .icon {
            width: 34px;
            height: 34px;
            border-radius: 14px;
            background: rgba(255,255,255,0.08);
            display: grid;
            place-items: center;
            font-size: 1rem;
        }

        .menu-item .label {
            flex: 1;
        }

        .menu-badge {
            min-width: 22px;
            padding: 0 8px;
            border-radius: 999px;
            background: #fde68a;
            color: #92400e;
            font-size: 0.78rem;
            font-weight: 700;
            text-align: center;
        }

        .logout-link {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 14px;
            border-radius: 16px;
            color: #c90000;
            background: rgba(248,113,113,0.12);
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .logout-link:hover {
            background: rgba(248,113,113,0.18);
        }

        .logout-link .icon {
            width: 34px;
            height: 34px;
            border-radius: 14px;
            background: rgba(248,113,113,0.16);
            color: #c90000;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .cards {
            display: flex;
            gap: 15px;
        }

        .card {
            flex: 1;
            padding: 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .card h4 {
            color: gray;
            font-size: 14px;
        }

        .card h2 {
            margin-top: 10px;
        }

        .section {
            margin-top: 25px;
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand">
        <h2>SIPAKAR</h2>
        <p>Dosen Matkul Portal</p>
    </div>

    <div class="profile-card">
        <div class="profile-avatar">CL</div>
        <div class="profile-info">
            <div class="profile-name">Cyntia Lasmi A.</div>
            <div class="profile-role">mk1@univ.ac.id</div>
        </div>
    </div>

    <div class="menu-title">Menu Utama</div>

    <div class="menu-list">
        <a href="#" class="menu-item active">
            <span class="icon">🏠</span>
            <span class="label">Beranda</span>
        </a>
        <a href="#" class="menu-item">
            <span class="icon">✍️</span>
            <span class="label">Input Nilai</span>
            <span class="menu-badge">0</span>
        </a>
        <a href="#" class="menu-item">
            <span class="icon">📄</span>
            <span class="label">Nilai Mahasiswa</span>
        </a>
         <a href="#" class="menu-item">
            <span class="icon">👤</span>
            <span class="label">Profil</span>
        </a>
    </div>

    <a href="{{ url('/') }}" class="logout-link">
        <span class="icon">⏻</span>
        <span>Keluar</span>
    </a>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>