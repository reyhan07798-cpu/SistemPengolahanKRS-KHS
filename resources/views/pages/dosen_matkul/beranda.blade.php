@extends('layouts.dosen_mk')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Beranda Dosen Mata Kuliah</h1>
        <p class="text-sm text-gray-500">Selamat datang, Cyntia Lasmi Andesti, S.Kom., M.Kom</p>
    </header>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Mata Kuliah Diampu -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Kuliah Diampu</p>
                    <h2 class="text-3xl font-bold text-dark mt-2">{{ $stats['mata_kuliah_diampu'] }}</h2>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Mahasiswa -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Mahasiswa</p>
                    <h2 class="text-3xl font-bold text-dark mt-2">{{ $stats['total_mahasiswa'] }}</h2>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Nilai Diinput -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nilai Diinput</p>
                    <h2 class="text-3xl font-bold text-dark mt-2">{{ $stats['nilai_diinput'] }}</h2>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Belum Dinilai -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Belum Dinilai</p>
                    <h2 class="text-3xl font-bold text-dark mt-2">{{ $stats['belum_dinilai'] }}</h2>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Mata Kuliah yang Diampu -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-dark mb-6">Mata Kuliah yang Diampu</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($mataKuliah as $mk)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $mk['kode'] }}</span>
                    <span class="text-xs font-semibold text-white bg-primary px-2 py-1 rounded">{{ $mk['sks'] }} SKS</span>
                </div>
                <h4 class="font-bold text-dark mb-2">{{ $mk['nama'] }}</h4>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><span class="font-semibold">Semester:</span> {{ $mk['semester'] }}</p>
                    <p><span class="font-semibold">Jadwal:</span> {{ $mk['jadwal'] }}</p>
                    <p><span class="font-semibold">Ruang:</span> {{ $mk['ruang'] }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold">Mahasiswa:</span> 
                        <span class="text-primary font-bold">
                            {{ $mk['mahasiswa'] }}/{{ $mk['kapasitas'] }}
                        </span>
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Mahasiswa Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-lg text-dark mb-6">Mahasiswa Terbaru</h3>
        
        <div class="space-y-4">
            @foreach($mahasiswaTerbaru as $mhs)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-dark text-sm">{{ $mhs['nama'] }}</h4>
                        <p class="text-xs text-gray-500">{{ $mhs['nim'] }} - {{ $mhs['prodi'] }} - Kelas {{ $mhs['kelas'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Rata-rata Nilai</p>
                    <p class="font-bold text-dark">{{ number_format($mhs['rata_nilai'], 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection