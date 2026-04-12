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
            min-height: 100vh;
            background: #e5e7eb;
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
            background: linear-gradient(180deg, #0f3c91 0%, #0b6dff 45%, #1d99ff 100%);
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

        .profile-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 18px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.08);
            margin-bottom: 28px;
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #4f46e5;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 1rem;
            color: white;
        }

        .profile-name {
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.2;
        }

        .profile-email {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 4px;
            line-height: 1.3;
        }

        .menu-title {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            color: rgba(255, 255, 255, 0.78);
            text-transform: uppercase;
            margin-bottom: 14px;
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
        }

        .heading p {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.6;
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
            color: #111827;
        }

        .panel {
            background: white;
            border-radius: 28px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
            margin-bottom: 28px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .panel-title {
            font-size: 1.05rem;
            font-weight: 700;
        }

        .student-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .student-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 20px;
            border-radius: 22px;
            background: #f8fafc;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .student-avatar {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: #e5e7eb;
            color: #111827;
            font-weight: 700;
        }

        .student-name {
            font-weight: 700;
            font-size: 0.98rem;
            color: #111827;
        }

        .student-meta {
            font-size: 0.92rem;
            color: #6b7280;
        }

        .student-values {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .student-ipk strong {
            display: block;
            font-size: 1rem;
            font-weight: 800;
            color: #111827;
        }

        .student-ipk span {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .badge.success {
            color: #166534;
            background: #dcfce7;
        }

        .badge.warning {
            color: #92400e;
            background: #fef3c7;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 20px;
        }

        .summary-card,
        .distribution-card {
            background: white;
            border-radius: 28px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .summary-card h3,
        .distribution-card h3 {
            font-size: 1.05rem;
            margin-bottom: 18px;
            font-weight: 700;
        }

        .summary-list {
            display: grid;
            gap: 14px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .summary-item strong {
            color: #111827;
            display: block;
            font-size: 1rem;
            margin-top: 4px;
        }

        .summary-pill {
            background: #f3f4f6;
            color: #374151;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .distribution-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
            color: #6b7280;
        }

        .distribution-label {
            min-width: 90px;
            font-weight: 600;
            color: #111827;
        }

        .distribution-bar {
            flex: 1;
            height: 10px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .distribution-fill {
            height: 100%;
            width: 65%;
            background: #0b6dff;
            border-radius: 999px;
        }

        .distribution-number {
            font-weight: 700;
            color: #111827;
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
                    <p>Beranda Dosen Wali</p>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-avatar">RN</div>
                <div>
                    <div class="profile-name">Rusyda Nazhirah Yunus</div>
                    <div class="profile-email">wali1@univ.ac.id</div>
                </div>
            </div>

            <div class="menu-title">Menu Utama</div>

            <nav class="menu-list">
                <a href="#" class="menu-item active">
                    <span class="menu-icon">▦</span>
                    <span class="menu-label">Beranda</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-icon">📝</span>
                    <span class="menu-label">Persetujuan KRS</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-icon">🎓</span>
                    <span class="menu-label">KHS Mahasiswa</span>
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
