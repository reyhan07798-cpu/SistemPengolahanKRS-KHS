@extends('layouts.dosen_mk')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Nilai Mahasiswa</h1>
        <p class="text-sm text-gray-500">Monitor hasil studi mahasiswa pada mata kuliah yang Anda ampu</p>
    </header>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Mahasiswa -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $stats['total_mahasiswa'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Nilai Terinput -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nilai Terinput</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $stats['nilai_terinput'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Rata-rata Nilai -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $stats['rata_nilai'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h3 class="font-bold text-dark mb-4">Filter Mata Kuliah</h3>
        <form method="GET" action="{{ route('pages.dosen_matkul.lihat-nilai') }}">
            <div class="flex gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Pilih Mata Kuliah</label>
                    <select name="mata_kuliah" class="bg-gray-50 border border-gray-200 text-sm rounded-lg p-2.5 w-72 focus:ring-primary focus:border-primary">
                        <option value="semua" {{ $filterMK == 'semua' ? 'selected' : '' }}>Semua Mata Kuliah</option>
                        @foreach($daftarMK as $mk)
                        <option value="{{ $mk }}" {{ $filterMK == $mk ? 'selected' : '' }}>{{ $mk }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                    Terapkan Filter
                </button>
                @if($filterMK != 'semua')
                <a href="{{ route('dosen_matkul.lihat-nilai') }}" class="px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Info Filter Aktif -->
    @if($filterMK != 'semua')
    <div class="bg-primary/10 border border-primary/20 rounded-lg px-4 py-3 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>
        <span class="text-sm text-primary">
            Menampilkan data untuk: <strong>{{ $filterMK }}</strong>
        </span>
    </div>
    @endif

    <!-- Tabel Mahasiswa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-dark">Daftar Mahasiswa</h3>
            <span class="text-sm text-gray-500">{{ count($mahasiswa) }} data ditemukan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">NIM</th>
                        <th class="px-6 py-3">Nama Mahasiswa</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">Mata Kuliah</th> <!-- ✅ Ganti Prodi dengan Mata Kuliah -->
                        <th class="px-6 py-3 text-center">Nilai</th>
                        <th class="px-6 py-3 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $mhs)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-dark">{{ $mhs['no'] }}</td>
                        <td class="px-6 py-4">{{ $mhs['nim'] }}</td>
                        <td class="px-6 py-4 font-medium text-dark">{{ $mhs['nama'] }}</td>
                        <td class="px-6 py-4">{{ $mhs['kelas'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-primary bg-primary/10 px-2 py-1 rounded">
                                {{ $mhs['mata_kuliah'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-dark">{{ number_format($mhs['nilai'], 1) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $gradeColor = match($mhs['grade']) {
                                    'A' => 'bg-green-100 text-green-700',
                                    'B' => 'bg-blue-100 text-blue-700',
                                    'C' => 'bg-yellow-100 text-yellow-700',
                                    'D' => 'bg-orange-100 text-orange-700',
                                    default => 'bg-red-100 text-red-700',
                                };
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $gradeColor }}">
                                {{ $mhs['grade'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tidak ada data mahasiswa untuk mata kuliah ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection