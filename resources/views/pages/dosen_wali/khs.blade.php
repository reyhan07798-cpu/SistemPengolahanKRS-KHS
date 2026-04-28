@extends('layouts.dosen_wali')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">KHS Mahasiswa</h1>
        <p class="text-sm text-gray-500">Monitor hasil studi mahasiswa bimbingan</p>
    </header>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Mahasiswa -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $totalMahasiswa }}</h3>
                </div>
            </div>
        </div>

        <!-- Rata-Rata IPK -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rata-Rata IPK</p>
                    <h3 class="text-2xl font-bold text-dark">{{ number_format($rataIpk, 3) }}</h3>
                </div>
            </div>
        </div>

        <!-- IPK ≥ 3.5 -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">IPK ≥ 3.5</p>
                    <h3 class="text-2xl font-bold text-dark">{{ $ipkTinggi }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h3 class="font-bold text-dark mb-4">Filter</h3>
        <form method="GET" action="{{ route('pages.dosen_wali.khs') }}">
            <div class="flex gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Kelas</label>
                    <select name="kelas" class="bg-gray-50 border border-gray-200 text-sm rounded-lg p-2.5 w-40 focus:ring-primary focus:border-primary">
                        <option value="semua" {{ $filterKelas == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                        <option value="A" {{ $filterKelas == 'A' ? 'selected' : '' }}>Kelas A</option>
                        <option value="B" {{ $filterKelas == 'B' ? 'selected' : '' }}>Kelas B</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Mahasiswa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-dark">Daftar Mahasiswa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Ranking</th>
                        <th class="px-6 py-3">NIM</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">Prodi</th>
                        <th class="px-6 py-3">MK Lulus</th>
                        <th class="px-6 py-3">IPK</th>
                        <th class="px-6 py-3">Status KRS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $mhs)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-dark">#{{ $mhs['ranking'] }}</td>
                        <td class="px-6 py-4">{{ $mhs['nim'] }}</td>
                        <td class="px-6 py-4 font-medium text-dark">{{ $mhs['nama'] }}</td>
                        <td class="px-6 py-4">{{ $mhs['kelas'] }}</td>
                        <td class="px-6 py-4">{{ $mhs['prodi'] }}</td>
                        <td class="px-6 py-4">{{ $mhs['mk_lulus'] }}</td>
                        <td class="px-6 py-4 font-bold {{ $mhs['ipk'] >= 3.5 ? 'text-green-600' : 'text-dark' }}">
                            {{ number_format($mhs['ipk'], 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($mhs['status_krs'] == 'Aktif')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Aktif</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $mhs['status_krs'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-400">Tidak ada data mahasiswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection