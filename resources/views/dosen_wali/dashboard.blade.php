<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Dosen Wali - SIPAKAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' },
                        sidebar: '#1e293b',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                    }
                }
            }
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1);
            border-left: 3px solid #3b82f6;
            padding-left: 17px;
        }
        .sidebar-link.active { background: rgba(59,130,246,0.15); }

        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.15); }

        .table-row { transition: background 0.15s ease; }
        .table-row:hover { background: #f8fafc; }

        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        .notification-dot {
            position: absolute; top: -2px; right: -2px;
            width: 8px; height: 8px;
            background: #ef4444; border-radius: 50%;
            border: 2px solid white;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.5s ease forwards; }
        .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }
        .animate-delay-5 { animation-delay: 0.5s; opacity: 0; }
        .animate-delay-6 { animation-delay: 0.6s; opacity: 0; }

        /* Mobile sidebar */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        .sidebar-overlay.show { display: block; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; z-index: 50; }
            .sidebar.open { transform: translateX(0); }
        }

        /* Toast notification */
        .toast {
            position: fixed; top: 24px; right: 24px; z-index: 100;
            background: white; border-radius: 12px; padding: 16px 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            transform: translateX(120%); transition: transform 0.4s ease;
            display: flex; align-items: center; gap: 12px;
            border-left: 4px solid #10b981;
        }
        .toast.show { transform: translateX(0); }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span class="iconify text-success text-xl" data-icon="lucide:check-circle"></span>
        <div>
            <p class="text-sm font-semibold text-gray-800" id="toast-title">Berhasil</p>
            <p class="text-xs text-gray-500" id="toast-msg">Data berhasil diperbarui</p>
        </div>
        <button onclick="hideToast()" class="ml-4 text-gray-400 hover:text-gray-600">
            <span class="iconify text-lg" data-icon="lucide:x"></span>
        </button>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- ==================== SIDEBAR ==================== -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full w-64 bg-sidebar text-white flex flex-col">
        <!-- Brand -->
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center">
                    <span class="iconify text-xl text-white" data-icon="lucide:graduation-cap"></span>
                </div>
                <div>
                    <h1 class="text-base font-bold tracking-tight">SIPAKAR</h1>
                    <p class="text-[10px] text-slate-400 tracking-wider uppercase">Dosen Wali Portal</p>
                </div>
            </div>
        </div>

        <!-- Profile Mini -->
        <div class="px-5 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-sm font-semibold">
                    RN
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">Rusyda Nazhirah Y.</p>
                    <p class="text-[11px] text-slate-400">Dosen Wali</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="px-4 mb-2 text-[10px] font-semibold tracking-widest uppercase text-slate-500">Menu Utama</p>

            <a href="#" class="sidebar-link active flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:layout-dashboard"></span>
                Beranda
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-slate-300" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:file-check"></span>
                Persetujuan KRS
                <span class="ml-auto bg-primary-500 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-slate-300" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:users"></span>
                KHS Mahasiswa
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-slate-300" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:user"></span>
                Profil
            </a>
        </nav>

        <!-- Logout -->
        <div class="px-3 py-4 border-t border-white/10">
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:text-red-300" onclick="handleLogout(event)">
                <span class="iconify text-lg" data-icon="lucide:log-out"></span>
                Keluar
            </a>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="flex-1 lg:ml-64 min-h-screen">

        <!-- Top Bar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/80">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                <!-- Left: Hamburger + Breadcrumb -->
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition">
                        <span class="iconify text-xl text-slate-600" data-icon="lucide:menu"></span>
                    </button>
                    <div class="hidden sm:flex items-center gap-2 text-sm">
                        <span class="text-slate-700 font-medium">Beranda</span>
                    </div>
                </div>

                <!-- Right: Search + Notification + Profile -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Search -->
                    <div class="hidden md:flex items-center bg-slate-100 rounded-xl px-3 py-2 gap-2 w-64">
                        <span class="iconify text-slate-400 text-lg" data-icon="lucide:search"></span>
                        <input type="text" placeholder="Cari mahasiswa..." class="bg-transparent text-sm outline-none w-full text-slate-600 placeholder-slate-400">
                    </div>

                    <!-- Notification -->
                    <button class="relative p-2 rounded-xl hover:bg-slate-100 transition" onclick="showToast('Notifikasi', 'Tidak ada notifikasi baru')">
                        <span class="iconify text-xl text-slate-500" data-icon="lucide:bell"></span>
                        <div class="notification-dot"></div>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative" id="profileDropdown">
                        <button onclick="toggleProfileMenu()" class="flex items-center gap-2 p-1.5 pr-3 rounded-xl hover:bg-slate-100 transition">
                            <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center text-xs font-bold text-white">RN</div>
                            <span class="hidden sm:block text-sm font-medium text-slate-700">Dosen Wali</span>
                            <span class="iconify text-slate-400 text-sm" data-icon="lucide:chevron-down"></span>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                <span class="iconify" data-icon="lucide:user"></span> Profil Saya
                            </a>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                <span class="iconify" data-icon="lucide:settings"></span> Pengaturan
                            </a>
                            <hr class="my-1 border-slate-100">
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50" onclick="handleLogout(event)">
                                <span class="iconify" data-icon="lucide:log-out"></span> Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            <!-- Greeting -->
            <div class="animate-in animate-delay-1">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Beranda Dosen Wali</h2>
                        <p class="text-slate-500 mt-1 text-sm">
                            Selamat datang, <span class="font-semibold text-primary-600">Rusyda Nazhirah Yunus, S.Kom., M.Kom.</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-400 bg-white px-4 py-2.5 rounded-xl border border-slate-200">
                        <span class="iconify text-sm" data-icon="lucide:calendar"></span>
                        <span id="currentDate"></span>
                        <span class="mx-1 text-slate-300">|</span>
                        <span class="iconify text-sm" data-icon="lucide:clock"></span>
                        <span id="currentTime"></span>
                    </div>
                </div>
            </div>

            <!-- ==================== STAT CARDS ==================== -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Mahasiswa Bimbingan -->
                <div class="stat-card animate-in animate-delay-2 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-primary-500" data-icon="lucide:users"></span>
                        </div>
                        <span class="badge badge-info">Aktif</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">3</p>
                    <p class="text-sm text-slate-500 mt-1">Mahasiswa Bimbingan</p>
                </div>

                <!-- Card 2: KRS Menunggu -->
                <div class="stat-card animate-in animate-delay-3 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-amber-500" data-icon="lucide:clock"></span>
                        </div>
                        <span class="badge badge-warning">Proses</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">0</p>
                    <p class="text-sm text-slate-500 mt-1">KRS Menunggu Persetujuan</p>
                </div>

                <!-- Card 3: KRS Disetujui -->
                <div class="stat-card animate-in animate-delay-4 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-emerald-500" data-icon="lucide:check-circle"></span>
                        </div>
                        <span class="badge badge-success">Selesai</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">2</p>
                    <p class="text-sm text-slate-500 mt-1">KRS Disetujui</p>
                </div>

                <!-- Card 4: KRS Ditolak -->
                <div class="stat-card animate-in animate-delay-5 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-red-500" data-icon="lucide:x-circle"></span>
                        </div>
                        <span class="badge badge-danger">Ditolak</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">0</p>
                    <p class="text-sm text-slate-500 mt-1">KRS Ditolak</p>
                </div>
            </div>

            <!-- ==================== STUDENT TABLE ==================== -->
            <div class="animate-in animate-delay-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Daftar Mahasiswa Bimbingan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Semester Genap 2024/2025</p>
                    </div>
                    <div class="flex items-center gap-2 mt-3 sm:mt-0">
                        <div class="flex items-center bg-slate-100 rounded-lg px-3 py-1.5 gap-2">
                            <span class="iconify text-slate-400 text-sm" data-icon="lucide:search"></span>
                            <input type="text" placeholder="Cari..." class="bg-transparent text-xs outline-none w-32 text-slate-600 placeholder-slate-400" id="tableSearch" oninput="filterTable()">
                        </div>
                        <button class="p-1.5 rounded-lg hover:bg-slate-100 transition" title="Filter">
                            <span class="iconify text-slate-400 text-lg" data-icon="lucide:sliders-horizontal"></span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/80">
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">No</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Nama Mahasiswa</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">NIM</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Semester</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">IPK</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Status KRS</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <!-- Row 1 -->
                            <tr class="table-row border-b border-slate-50 student-row" data-name="reyhan">
                                <td class="px-6 py-4 text-sm text-slate-500">1</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600">RH</div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Reyhan</p>
                                            <p class="text-[11px] text-slate-400">Teknik Informatika</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">2023010001</td>
                                <td class="px-6 py-4 text-sm text-slate-600">4</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-emerald-600">3.75</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-success">
                                        <span class="iconify mr-1 text-xs" data-icon="lucide:check"></span>Disetujui
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail', 'Menampilkan detail Reyhan')">
                                        <span class="iconify text-lg" data-icon="lucide:eye"></span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="table-row border-b border-slate-50 student-row" data-name="nabila fatin">
                                <td class="px-6 py-4 text-sm text-slate-500">2</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-pink-100 flex items-center justify-center text-xs font-bold text-pink-600">NF</div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Nabila Fatin</p>
                                            <p class="text-[11px] text-slate-400">Teknik Informatika</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">2023010002</td>
                                <td class="px-6 py-4 text-sm text-slate-600">4</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-emerald-600">3.52</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-success">
                                        <span class="iconify mr-1 text-xs" data-icon="lucide:check"></span>Disetujui
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail', 'Menampilkan detail Nabila Fatin')">
                                        <span class="iconify text-lg" data-icon="lucide:eye"></span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 3 -->
                            <tr class="table-row border-b border-slate-50 student-row" data-name="irenessa rosidin">
                                <td class="px-6 py-4 text-sm text-slate-500">3</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-xs font-bold text-purple-600">IR</div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Irenessa Rosidin</p>
                                            <p class="text-[11px] text-slate-400">Teknik Informatika</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-mono">2023010003</td>
                                <td class="px-6 py-4 text-sm text-slate-600">4</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-amber-600">3.10</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-warning">
                                        <span class="iconify mr-1 text-xs" data-icon="lucide:clock"></span>Menunggu
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button class="p-1.5 rounded-lg hover:bg-emerald-50 transition text-slate-400 hover:text-emerald-500" title="Setujui" onclick="approveKRS(this)">
                                            <span class="iconify text-lg" data-icon="lucide:check"></span>
                                        </button>
                                        <button class="p-1.5 rounded-lg hover:bg-red-50 transition text-slate-400 hover:text-red-500" title="Tolak" onclick="rejectKRS(this)">
                                            <span class="iconify text-lg" data-icon="lucide:x"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    <p class="text-xs text-slate-400">Menampilkan 1-3 dari 3 mahasiswa</p>
                    <div class="flex items-center gap-1 mt-2 sm:mt-0">
                        <button class="px-3 py-1.5 text-xs rounded-lg bg-primary-500 text-white font-medium">1</button>
                        <button class="px-3 py-1.5 text-xs rounded-lg text-slate-500 hover:bg-slate-100 transition">2</button>
                        <button class="px-3 py-1.5 text-xs rounded-lg text-slate-500 hover:bg-slate-100 transition">3</button>
                    </div>
                </div>
            </div>

            <!-- ==================== BOTTOM SECTION: Charts ==================== -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Ringkasan Akademik -->
                <div class="animate-in animate-delay-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Ringkasan Akademik</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Perbandingan KRS semester ini</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Disetujui</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Menunggu</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Ditolak</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="krsChart"></canvas>
                    </div>
                </div>

                <!-- Distribusi Kelas -->
                <div class="animate-in animate-delay-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Distribusi Kelas</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Mahasiswa per kelas</p>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-slate-500">Kelas A</p>
                                <span class="text-sm font-semibold text-slate-800">3</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full bg-white" style="width: 65%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-slate-500">Kelas B</p>
                                <span class="text-sm font-semibold text-slate-800">5</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full bg-white" style="width: 100%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-slate-500">Kelas C</p>
                                <span class="text-sm font-semibold text-slate-800">2</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full bg-white" style="width: 40%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer class="px-4 sm:px-6 lg:px-8 py-5 border-t border-slate-200 mt-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">
                <p>&copy; 2025 SIAKAD - Sistem Informasi Akademik. All rights reserved.</p>
                <p>Version 1.0.0 · Built with Laravel & Tailwind CSS</p>
            </div>
        </footer>
    </main>

    <!-- ==================== LOGOUT MODAL ==================== -->
    <div id="logoutModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center">
                    <span class="iconify text-3xl text-red-500" data-icon="lucide:log-out"></span>
                </div>
                <h3 class="text-lg font-semibold text-slate-800">Konfirmasi Keluar</h3>
                <p class="text-sm text-slate-500 mt-2">Apakah Anda yakin ingin keluar dari sistem?</p>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    Batal
                </button>
                <button onclick="confirmLogout()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    <!-- ==================== KRS DETAIL MODAL ==================== -->
    <div id="krsModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800" id="krsModalTitle">Detail KRS Mahasiswa</h3>
                <button onclick="closeKrsModal()" class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400">
                    <span class="iconify text-xl" data-icon="lucide:x"></span>
                </button>
            </div>
            <div class="p-6" id="krsModalContent">
                <!-- Dynamic content -->
            </div>
        </div>
    </div>

    <script>
        // ==================== DATE/TIME ====================
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // ==================== SIDEBAR TOGGLE ====================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        // ==================== SIDEBAR NAV ====================
        function navigateTo(el, e) {
            e.preventDefault();
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
                link.classList.add('text-slate-300');
                link.classList.remove('text-white');
            });
            el.classList.add('active', 'text-white');
            el.classList.remove('text-slate-300');
            showToast('Navigasi', 'Menuju: ' + el.textContent.trim());
            // Close mobile sidebar
            if (window.innerWidth < 1024) {
                toggleSidebar();
            }
        }

        // ==================== PROFILE DROPDOWN ====================
        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            if (!dropdown.contains(e.target)) {
                document.getElementById('profileMenu').classList.add('hidden');
            }
        });

        // ==================== TOAST ====================
        let toastTimeout;
        function showToast(title, msg) {
            clearTimeout(toastTimeout);
            document.getElementById('toast-title').textContent = title;
            document.getElementById('toast-msg').textContent = msg;
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            toastTimeout = setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }
        function hideToast() {
            document.getElementById('toast').classList.remove('show');
            clearTimeout(toastTimeout);
        }

        // ==================== TABLE SEARCH ====================
        function filterTable() {
            const query = document.getElementById('tableSearch').value.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                const name = row.getAttribute('data-name');
                row.style.display = name.includes(query) ? '' : 'none';
            });
        }

        // ==================== KRS ACTIONS ====================
        function approveKRS(btn) {
            const row = btn.closest('tr');
            const name = row.querySelector('.text-sm.font-medium').textContent;
            const statusCell = row.querySelector('.badge');
            const actionCell = row.querySelector('td:last-child');

            statusCell.className = 'badge badge-success';
            statusCell.innerHTML = '<span class="iconify mr-1 text-xs" data-icon="lucide:check"></span>Disetujui';
            actionCell.innerHTML = `
                <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail', 'Menampilkan detail ${name}')">
                    <span class="iconify text-lg" data-icon="lucide:eye"></span>
                </button>`;

            // Update stat cards
            updateStatCard(1, 2); // waiting -1, approved +1
            showToast('Berhasil', `KRS ${name} telah disetujui`);
        }

        function rejectKRS(btn) {
            const row = btn.closest('tr');
            const name = row.querySelector('.text-sm.font-medium').textContent;
            const statusCell = row.querySelector('.badge');
            const actionCell = row.querySelector('td:last-child');

            statusCell.className = 'badge badge-danger';
            statusCell.innerHTML = '<span class="iconify mr-1 text-xs" data-icon="lucide:x"></span>Ditolak';
            actionCell.innerHTML = `
                <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail', 'Menampilkan detail ${name}')">
                    <span class="iconify text-lg" data-icon="lucide:eye"></span>
                </button>`;

            updateStatCard(-1, 0); // waiting -1
            showToast('Ditolak', `KRS ${name} telah ditolak`);
        }

        function updateStatCard(waitingDelta, approvedDelta) {
            const cards = document.querySelectorAll('.stat-card .text-3xl');
            const waitingVal = parseInt(cards[1].textContent) + waitingDelta;
            const approvedVal = parseInt(cards[2].textContent) + approvedDelta;
            cards[1].textContent = waitingVal;
            cards[2].textContent = approvedVal;
        }

        // ==================== LOGOUT MODAL ====================
        function handleLogout(e) {
            e.preventDefault();
            document.getElementById('logoutModal').classList.remove('hidden');
            document.getElementById('logoutModal').classList.add('flex');
        }
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
            document.getElementById('logoutModal').classList.remove('flex');
        }
        function confirmLogout() {
            closeLogoutModal();
            showToast('Keluar', 'Anda telah berhasil keluar dari sistem');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        }

        // ==================== KRS MODAL ====================
        function closeKrsModal() {
            document.getElementById('krsModal').classList.add('hidden');
            document.getElementById('krsModal').classList.remove('flex');
        }

        // ==================== CHARTS ====================
        // KRS Doughnut Chart
        const krsCtx = document.getElementById('krsChart').getContext('2d');
        new Chart(krsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Menunggu', 'Ditolak'],
                datasets: [{
                    data: [2, 1, 0],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                    spacing: 4,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Inter', size: 13, weight: '600' },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: true,
                        boxPadding: 4,
                    }
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw(chart) {
                    const { ctx, width, height } = chart;
                    ctx.save();
                    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    ctx.font = 'bold 28px Inter';
                    ctx.fillStyle = '#1e293b';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(total, width / 2, height / 2 - 8);
                    ctx.font = '12px Inter';
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('Total KRS', width / 2, height / 2 + 16);
                    ctx.restore();
                }
            }]
        });


        // ==================== INTERSECTION OBSERVER FOR ANIMATIONS ====================
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>