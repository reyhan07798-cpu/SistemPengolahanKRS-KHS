@extends('layouts.dosen_wali')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Beranda Dosen Wali</h1>
        <p class="text-sm text-gray-500">Selamat datang, Rusyda Nazhirah Yunus, S.S., M.Si</p>
    </header>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mahasiswa Bimbingan</p>
                    <h2 class="text-3xl font-bold text-dark mt-2">{{ $stats['mahasiswa_bimbingan'] }}</h2>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">KRS Menunggu</p>
                    <h2 class="text-3xl font-bold text-gray-400 mt-2">{{ $stats['krs_menunggu'] }}</h2>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">KRS Disetujui</p>
                    <h2 class="text-3xl font-bold text-dark mt-2">{{ $stats['krs_disetujui'] }}</h2>
                </div>
                <div class="p-2 bg-green-100 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">KRS Ditolak</p>
                    <h2 class="text-3xl font-bold text-gray-400 mt-2">{{ $stats['krs_ditolak'] }}</h2>
                </div>
                <div class="p-2 bg-red-100 rounded-lg text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18L18 6"></path>                    
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa Bimbingan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-dark mb-6">Mahasiswa Bimbingan</h3>
        
        <div class="space-y-4">
            @foreach($mahasiswa as $m)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-dark text-sm">{{ $m['nama'] }}</h4>
                        <p class="text-xs text-gray-500">{{ $m['nim'] }} - {{ $m['prodi'] }} - Kelas {{ $m['kelas'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">IPK</p>
                    <p class="font-bold text-dark">{{ number_format($m['ipk'], 2) }}</p>
                    
                    @if($m['status_krs'] == 'Disetujui')
                        <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold bg-green-100 text-green-700 rounded-full">Disetujui</span>
                    @elseif($m['status_krs'] == 'Menunggu')
                        <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold bg-orange-100 text-orange-700 rounded-full">Menunggu</span>
                    @else
                        <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-semibold bg-gray-200 text-gray-600 rounded-full">{{ $m['status_krs'] }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-dark mb-4">Ringkasan Akademik</h4>
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-600">Mahasiswa dengan IPK ≥ 3.0</span>
                <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded">2 dari 3</span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-600">Total KRS Disetujui</span>
                <span class="font-bold text-dark">{{ $stats['krs_disetujui'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Total KRS Menunggu</span>
                <span class="font-bold text-dark">{{ $stats['krs_menunggu'] }}</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h4 class="font-bold text-dark mb-4">Distribusi Kelas</h4>
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">Kelas A</span>
                <span class="font-bold text-dark">{{ $stats['mahasiswa_bimbingan'] }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-primary h-2.5 rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>
@endsection