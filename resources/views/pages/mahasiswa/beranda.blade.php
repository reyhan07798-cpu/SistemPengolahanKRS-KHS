@extends('layouts.mahasiswa')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Beranda Mahasiswa</h1>
        <p class="text-sm text-gray-500">Selamat datang, {{ $data['nama'] }}</p>
    </header>

    <!-- Statistik Cards - Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Semester Aktif -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Semester Aktif</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $data['semester_aktif'] }}</h3>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total SKS -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Total SKS</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $data['total_sks'] }}</h3>
                </div>
                <div class="p-2 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- IPK -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">IPK</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ number_format($data['ipk'], 2) }}</h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Mata Kuliah Lulus -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Mata Kuliah Lulus</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $data['mata_kuliah_lulus'] }}</h3>
                </div>
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Mahasiswa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-dark mb-6">Informasi Mahasiswa</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">NIM</p>
                <p class="text-sm font-semibold text-dark mb-4">{{ $data['nim'] }}</p>
                
                <p class="text-xs font-medium text-gray-500 mb-1">Nama</p>
                <p class="text-sm font-semibold text-dark mb-4">{{ $data['nama'] }}</p>
                
                <p class="text-xs font-medium text-gray-500 mb-1">Program Studi</p>
                <p class="text-sm font-semibold text-dark">{{ $data['prodi'] }}</p>
            </div>
            
            <div>
                <p class="text-xs font-medium text-gray-500 mb-1">Angkatan</p>
                <p class="text-sm font-semibold text-dark mb-4">{{ $data['angkatan'] }}</p>
                
                <p class="text-xs font-medium text-gray-500 mb-1">Email</p>
                <p class="text-sm font-semibold text-dark mb-4">{{ $data['email'] }}</p>
                
                <p class="text-xs font-medium text-gray-500 mb-1">Semester Aktif</p>
                <p class="text-sm font-semibold text-dark">{{ $data['semester_aktif'] }}</p>
            </div>
        </div>
    </div>

    <!-- Nilai Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-dark mb-6">Nilai Terbaru</h3>
        
        @if(count($data['nilai_terbaru']) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['nilai_terbaru'] as $nilai)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-dark">{{ $nilai['matkul'] }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $nilai['sks'] }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $gradeColor = match($nilai['nilai']) {
                                    'A' => 'bg-green-100 text-green-700',
                                    'B' => 'bg-blue-100 text-blue-700',
                                    'C' => 'bg-yellow-100 text-yellow-700',
                                    'D' => 'bg-orange-100 text-orange-700',
                                    default => 'bg-red-100 text-red-700',
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $gradeColor }}">
                                {{ $nilai['nilai'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center font-medium">{{ $nilai['bobot'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-gray-500 py-4">Belum ada data nilai</p>
        @endif
    </div>

    <!-- KRS Aktif -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-lg text-dark mb-6">KRS Aktif</h3>
        
        @if(count($data['krs_aktif']) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['krs_aktif'] as $krs)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-mono text-dark">{{ $krs['kode'] }}</td>
                        <td class="px-6 py-4 text-sm text-dark">{{ $krs['matkul'] }}</td>
                        <td class="px-6 py-4 text-sm text-center">{{ $krs['sks'] }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = match($krs['status']) {
                                    'Disetujui' => 'bg-green-100 text-green-700',
                                    'Menunggu' => 'bg-orange-100 text-orange-700',
                                    'Ditolak' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                {{ $krs['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-gray-500 py-4">Belum ada KRS aktif</p>
        @endif
    </div>
@endsection