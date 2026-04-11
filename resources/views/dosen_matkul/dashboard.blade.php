<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Dosen Mata Kuliah - SIPAKAR</title>
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
        .sidebar-link { transition: all 0.2s ease; border-left: 3px solid transparent; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #3b82f6;
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
        .badge-purple { background: #ede9fe; color: #5b21b6; }
        .notification-dot {
            position: absolute; top: -2px; right: -2px;
            width: 8px; height: 8px; background: #ef4444;
            border-radius: 50%; border: 2px solid white;
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
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        .sidebar-overlay.show { display: block; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; z-index: 50; }
            .sidebar.open { transform: translateX(0); }
        }
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

    <!-- Toast -->
    <div id="toast" class="toast">
        <span class="iconify text-emerald-500 text-xl" data-icon="lucide:check-circle"></span>
        <div>
            <p class="text-sm font-semibold text-gray-800" id="toast-title">Berhasil</p>
            <p class="text-xs text-gray-500" id="toast-msg">Data berhasil diperbarui</p>
        </div>
        <button onclick="hideToast()" class="ml-4 text-gray-400 hover:text-gray-600">
            <span class="iconify text-lg" data-icon="lucide:x"></span>
        </button>
    </div>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- ========== SIDEBAR ========== -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full w-64 bg-sidebar text-white flex flex-col">
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center">
                    <span class="iconify text-xl text-white" data-icon="lucide:graduation-cap"></span>
                </div>
                <div>
                    <h1 class="text-base font-bold tracking-tight">SIPAKAR</h1>
                    <p class="text-[10px] text-slate-400 tracking-wider uppercase">Dosen Matkul Portal</p>
                </div>
            </div>
        </div>
        <div class="px-5 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-sm font-semibold">CL</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">Cyntia Lasmi A.</p>
                    <p class="text-[11px] text-slate-400">Dosen Mata Kuliah</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="px-4 mb-2 text-[10px] font-semibold tracking-widest uppercase text-slate-500">Menu Utama</p>
            <a href="#" class="sidebar-link active flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-white" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:layout-dashboard"></span> Beranda
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-slate-300" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:user"></span> Profil
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-slate-300" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:pen-line"></span> Input Nilai
                <span class="ml-auto bg-amber-500 text-[10px] font-bold px-2 py-0.5 rounded-full text-white">0</span>
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-slate-300" onclick="navigateTo(this, event)">
                <span class="iconify text-lg" data-icon="lucide:file-spreadsheet"></span> Rekap Nilai
            </a>
        </nav>
        <div class="px-3 py-4 border-t border-white/10">
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:text-red-300" onclick="handleLogout(event)">
                <span class="iconify text-lg" data-icon="lucide:log-out"></span> Keluar
            </a>
        </div>
    </aside>

    <!-- ========== MAIN CONTENT ========== -->
    <main class="flex-1 lg:ml-64 min-h-screen">

        <!-- Top Bar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/80">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition">
                        <span class="iconify text-xl text-slate-600" data-icon="lucide:menu"></span>
                    </button>
                    <div class="hidden sm:flex items-center gap-2 text-sm">
                        <span class="text-slate-700 font-medium">Beranda</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="hidden md:flex items-center bg-slate-100 rounded-xl px-3 py-2 gap-2 w-64">
                        <span class="iconify text-slate-400 text-lg" data-icon="lucide:search"></span>
                        <input type="text" placeholder="Cari mata kuliah..." class="bg-transparent text-sm outline-none w-full text-slate-600 placeholder-slate-400">
                    </div>
                    <button class="relative p-2 rounded-xl hover:bg-slate-100 transition" onclick="showToast('Notifikasi','Tidak ada notifikasi baru')">
                        <span class="iconify text-xl text-slate-500" data-icon="lucide:bell"></span>
                        <div class="notification-dot"></div>
                    </button>
                    <div class="relative" id="profileDropdown">
                        <button onclick="toggleProfileMenu()" class="flex items-center gap-2 p-1.5 pr-3 rounded-xl hover:bg-slate-100 transition">
                            <div class="w-8 h-8 rounded-lg bg-purple-500 flex items-center justify-center text-xs font-bold text-white">CL</div>
                            <span class="hidden sm:block text-sm font-medium text-slate-700">Dosen Matkul</span>
                            <span class="iconify text-slate-400 text-sm" data-icon="lucide:chevron-down"></span>
                        </button>
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
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Beranda Dosen Mata Kuliah</h2>
                        <p class="text-slate-500 mt-1 text-sm">Selamat datang, <span class="font-semibold text-purple-600">Cyntia Lasmi Andesti, S.Kom., M.Kom.</span></p>
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

            <!-- ========== STAT CARDS ========== -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Mata Kuliah Diampu -->
                <div class="stat-card animate-in animate-delay-2 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-purple-500" data-icon="lucide:book-open"></span>
                        </div>
                        <span class="badge badge-purple">Aktif</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">3</p>
                    <p class="text-sm text-slate-500 mt-1">Mata Kuliah Diampu</p>
                </div>

                <!-- Total Mahasiswa -->
                <div class="stat-card animate-in animate-delay-3 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-blue-500" data-icon="lucide:users"></span>
                        </div>
                        <span class="badge badge-info">Terdaftar</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">3</p>
                    <p class="text-sm text-slate-500 mt-1">Total Mahasiswa</p>
                </div>

                <!-- Nilai Sudah Diinput -->
                <div class="stat-card animate-in animate-delay-4 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-emerald-500" data-icon="lucide:check-circle"></span>
                        </div>
                        <span class="badge badge-success">Selesai</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">3</p>
                    <p class="text-sm text-slate-500 mt-1">Nilai Sudah Diinput</p>
                </div>

                <!-- Belum Dinilai -->
                <div class="stat-card animate-in animate-delay-5 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                            <span class="iconify text-2xl text-red-500" data-icon="lucide:alert-circle"></span>
                        </div>
                        <span class="badge badge-success">Aman</span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800">0</p>
                    <p class="text-sm text-slate-500 mt-1">Belum Dinilai</p>
                </div>
            </div>

            <!-- ========== TABEL MATA KULIAH ========== -->
            <div class="animate-in animate-delay-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Mata Kuliah yang Diampu</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Semester Genap 2024/2025</p>
                    </div>
                    <div class="flex items-center gap-2 mt-3 sm:mt-0">
                        <div class="flex items-center bg-slate-100 rounded-lg px-3 py-1.5 gap-2">
                            <span class="iconify text-slate-400 text-sm" data-icon="lucide:search"></span>
                            <input type="text" placeholder="Cari matkul..." class="bg-transparent text-xs outline-none w-32 text-slate-600 placeholder-slate-400" id="matkulSearch" oninput="filterMatkul()">
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/80">
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">No</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Mata Kuliah</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Semester</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">SKS</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Jadwal</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Ruang</th>
                                <th class="text-left px-6 py-3 text-[11px] font-semibold tracking-wider uppercase text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="matkulTableBody">
                            <!-- Baris 1 -->
                            <tr class="table-row border-b border-slate-50 matkul-row" data-name="pemrograman dasar">
                                <td class="px-6 py-4 text-sm text-slate-500">1</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <span class="iconify text-blue-600 text-lg" data-icon="lucide:code-2"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Pemrograman Dasar</p>
                                            <p class="text-[11px] text-slate-400">IF101</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-info">Semester 1</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700">3</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-slate-600">
                                        <span class="iconify text-slate-400 text-sm" data-icon="lucide:calendar-days"></span>
                                        Senin, 08:00 - 09:40
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge bg-blue-50 text-blue-700">Lab Komputer 3</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail','Menampilkan detail Pemrograman Dasar')">
                                            <span class="iconify text-lg" data-icon="lucide:eye"></span>
                                        </button>
                                        <button class="p-1.5 rounded-lg hover:bg-emerald-50 transition text-slate-400 hover:text-emerald-500" title="Input Nilai" onclick="showToast('Input Nilai','Menuju input nilai Pemrograman Dasar')">
                                            <span class="iconify text-lg" data-icon="lucide:pen-line"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Baris 2 -->
                            <tr class="table-row border-b border-slate-50 matkul-row" data-name="basis data">
                                <td class="px-6 py-4 text-sm text-slate-500">2</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <span class="iconify text-emerald-600 text-lg" data-icon="lucide:database"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Basis Data</p>
                                            <p class="text-[11px] text-slate-400">IF201</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-success">Semester 2</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700">3</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-slate-600">
                                        <span class="iconify text-slate-400 text-sm" data-icon="lucide:calendar-days"></span>
                                        Rabu, 10:00 - 11:40
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge bg-emerald-50 text-emerald-700">R. 301</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail','Menampilkan detail Basis Data')">
                                            <span class="iconify text-lg" data-icon="lucide:eye"></span>
                                        </button>
                                        <button class="p-1.5 rounded-lg hover:bg-emerald-50 transition text-slate-400 hover:text-emerald-500" title="Input Nilai" onclick="showToast('Input Nilai','Menuju input nilai Basis Data')">
                                            <span class="iconify text-lg" data-icon="lucide:pen-line"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Baris 3 -->
                            <tr class="table-row border-b border-slate-50 matkul-row" data-name="pemrograman berorientasi objek">
                                <td class="px-6 py-4 text-sm text-slate-500">3</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <span class="iconify text-purple-600 text-lg" data-icon="lucide:braces"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Pemrograman Berorientasi Objek</p>
                                            <p class="text-[11px] text-slate-400">IF301</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-purple">Semester 3</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700">3</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-slate-600">
                                        <span class="iconify text-slate-400 text-sm" data-icon="lucide:calendar-days"></span>
                                        Jumat, 13:00 - 14:40
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge bg-purple-50 text-purple-700">R. 204</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <button class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-400 hover:text-primary-500" title="Detail" onclick="showToast('Detail','Menampilkan detail PBO')">
                                            <span class="iconify text-lg" data-icon="lucide:eye"></span>
                                        </button>
                                        <button class="p-1.5 rounded-lg hover:bg-emerald-50 transition text-slate-400 hover:text-emerald-500" title="Input Nilai" onclick="showToast('Input Nilai','Menuju input nilai PBO')">
                                            <span class="iconify text-lg" data-icon="lucide:pen-line"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    <p class="text-xs text-slate-400">Menampilkan 1-3 dari 3 mata kuliah</p>
                </div>
            </div>

            <!-- ========== BAGIAN BAWAH: MAHASISWA + CHART ========== -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Mahasiswa Terbaru -->
                <div class="animate-in animate-delay-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-lg font-semibold text-slate-800">Mahasiswa Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Mahasiswa yang mengambil mata kuliah</p>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <!-- Mahasiswa 1 -->
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition cursor-pointer" onclick="showToast('Profil','Menampilkan profil Reyhan')">
                            <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600 shrink-0">RH</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">Reyhan</p>
                                <p class="text-[11px] text-slate-400">2023010001 · Teknik Informatika · Semester 4</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-emerald-600">3.67</p>
                                <p class="text-[10px] text-slate-400">IPK</p>
                            </div>
                        </div>
                        <!-- Mahasiswa 2 -->
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition cursor-pointer" onclick="showToast('Profil','Menampilkan profil Nabila Fatin')">
                            <div class="w-11 h-11 rounded-full bg-pink-100 flex items-center justify-center text-sm font-bold text-pink-600 shrink-0">NF</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">Nabila Fatin</p>
                                <p class="text-[11px] text-slate-400">2023010002 · Teknik Informatika · Semester 4</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-emerald-600">3.50</p>
                                <p class="text-[10px] text-slate-400">IPK</p>
                            </div>
                        </div>
                        <!-- Mahasiswa 3 -->
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/50 transition cursor-pointer" onclick="showToast('Profil','Menampilkan profil Irenessa Rosidin')">
                            <div class="w-11 h-11 rounded-full bg-purple-100 flex items-center justify-center text-sm font-bold text-purple-600 shrink-0">IR</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">Irenessa Rosidin</p>
                                <p class="text-[11px] text-slate-400">2023010003 · Teknik Informatika · Semester 4</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-amber-600">3.45</p>
                                <p class="text-[10px] text-slate-400">IPK</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50">
                        <a href="#" class="text-xs font-medium text-primary-600 hover:text-primary-700 transition flex items-center gap-1" onclick="event.preventDefault(); showToast('Navigasi','Menuju Daftar Mahasiswa')">
                            Lihat semua mahasiswa
                            <span class="iconify text-sm" data-icon="lucide:arrow-right"></span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="px-4 sm:px-6 lg:px-8 py-5 border-t border-slate-200 mt-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">
                <p>&copy; 2025 SIPAKAR - Sistem Pengolahan KRS/KHS. All rights reserved.</p>
                <p>Version 1.0.0 · Built with Laravel & Tailwind CSS</p>
            </div>
        </footer>
    </main>

    <!-- ========== LOGOUT MODAL ========== -->
    <div id="logoutModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center">
                    <span class="iconify text-3xl text-red-500" data-icon="lucide:log-out"></span>
                </div>
                <h3 class="text-lg font-semibold text-slate-800">Konfirmasi Keluar</h3>
                <p class="text-sm text-slate-500 mt-2">Apakah Anda yakin ingin keluar dari sistem?</p>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                <button onclick="confirmLogout()" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition">Ya, Keluar</button>
            </div>
        </div>
    </div>

    <script>
        // Date/Time
        function updateDateTime() {
            const now = new Date();
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        // Nav
        function navigateTo(el, e) {
            e.preventDefault();
            document.querySelectorAll('.sidebar-link').forEach(l => { l.classList.remove('active','text-white'); l.classList.add('text-slate-300'); });
            el.classList.add('active','text-white');
            el.classList.remove('text-slate-300');
            showToast('Navigasi', 'Menuju: ' + el.textContent.trim());
            if (window.innerWidth < 1024) toggleSidebar();
        }

        // Profile dropdown
        function toggleProfileMenu() { document.getElementById('profileMenu').classList.toggle('hidden'); }
        document.addEventListener('click', function(e) {
            if (!document.getElementById('profileDropdown').contains(e.target)) document.getElementById('profileMenu').classList.add('hidden');
        });

        // Toast
        let toastTimeout;
        function showToast(title, msg) {
            clearTimeout(toastTimeout);
            document.getElementById('toast-title').textContent = title;
            document.getElementById('toast-msg').textContent = msg;
            document.getElementById('toast').classList.add('show');
            toastTimeout = setTimeout(() => document.getElementById('toast').classList.remove('show'), 3000);
        }
        function hideToast() { document.getElementById('toast').classList.remove('show'); clearTimeout(toastTimeout); }

        // Table search
        function filterMatkul() {
            const q = document.getElementById('matkulSearch').value.toLowerCase();
            document.querySelectorAll('.matkul-row').forEach(r => { r.style.display = r.dataset.name.includes(q) ? '' : 'none'; });
        }

        // Logout modal
        function handleLogout(e) { e.preventDefault(); document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
        function closeLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
        function confirmLogout() { closeLogoutModal(); showToast('Keluar','Anda telah berhasil keluar dari sistem'); }

        // Profile dropdown
        function toggleProfileMenu() { document.getElementById('profileMenu').classList.toggle('hidden'); }
        document.addEventListener('click', function(e) {
            if (!document.getElementById('profileDropdown').contains(e.target)) document.getElementById('profileMenu').classList.add('hidden');
        });

    </script>
</body>
</html>