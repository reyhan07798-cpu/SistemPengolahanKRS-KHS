<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPAKAR - Mahasiswa Dashboard')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f8fafc;
            color: #111827;
        }

        .app-wrap {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 300px;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 45%, #2563eb 100%);
            color: white;
            padding: 28px 22px;
            box-shadow: 0 20px 45px rgba(6, 53, 107, 0.22);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 30px;
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.16);
            display: grid;
            place-items: center;
            font-size: 1.1rem;
        }

        .brand-text h1 {
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand-text p {
            font-size: 0.86rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 6px;
        }

        .menu-title {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            color: rgba(255, 255, 255, 0.78);
            text-transform: uppercase;
            margin-bottom: 14px;
            margin-top: 20px;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 18px;
            text-decoration: none;
            color: white;
            transition: background 0.2s ease;
            background: rgba(255, 255, 255, 0.08);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.18);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .menu-icon {
            width: 38px;
            height: 38px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.12);
            font-size: 1rem;
        }

        .menu-label {
            font-size: 0.95rem;
            font-weight: 600;
            flex: 1;
        }

        .logout-link {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(248, 113, 113, 0.18);
            color: #c90000;
            text-decoration: none;
            font-weight: 700;
            transition: background 0.2s ease;
        }

        .logout-link:hover {
            background: rgba(248, 113, 113, 0.26);
        }

        .logout-icon {
            width: 38px;
            height: 38px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(248, 113, 113, 0.24);
            color: #c90000;
            font-size: 1rem;
        }

        .content {
            flex: 1;
            padding: 32px;
        }

        .heading {
            margin-bottom: 28px;
        }

        .heading h1 {
            font-size: 2.1rem;
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .heading p {
            font-size: 1rem;
            color: #6b7280;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .stat-title {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 2.1rem;
            font-weight: 800;
            color: #1e40af;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }

        .panel {
            background: white;
            border-radius: 28px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .panel-header {
            margin-bottom: 22px;
        }

        .panel-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e3a8a;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-table td:first-child {
            color: #6b7280;
            width: 40%;
        }

        .info-table td:last-child {
            color: #111827;
            font-weight: 600;
        }

        .nilai-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .nilai-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 16px;
        }

        .nilai-matkul {
            font-weight: 600;
            color: #111827;
        }

        .nilai-bobot {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nilai-badge {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #3b82f6;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .table-panel {
            background: white;
            border-radius: 28px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .table-header {
            margin-bottom: 22px;
        }

        .table-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e3a8a;
        }

        .krs-table {
            width: 100%;
            border-collapse: collapse;
        }

        .krs-table thead {
            background: #1e40af;
            color: white;
        }

        .krs-table th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
        }

        .krs-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .krs-table tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .status-disetujui {
            background: #dcfce7;
            color: #166534;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 1120px) {
            .stats-grid,
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 820px) {
            .app-wrap {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .content {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="app-wrap">
        <div class="sidebar">
            <div class="brand">
                <div class="brand-icon">🎓</div>
                <div class="brand-text">
                    <h1>SIPAKAR</h1>
                    <p>Beranda Mahasiswa</p>
                </div>
            </div>

            <div class="menu-title">Menu Utama</div>

            <nav class="menu-list">
                <a href="{{ route('mahasiswa.dashboard') }}" class="menu-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon">🏠</span>
                    <span class="menu-label">Beranda</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-icon">📝</span>
                    <span class="menu-label">Ambil KRS</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-icon">📜</span>
                    <span class="menu-label">Lihat KHS</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-icon">👤</span>
                    <span class="menu-label">Profil</span>
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
    </div>
</body>
</html>