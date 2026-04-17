@extends('layouts.dosen_wali')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Verifikasi KRS</h1>
        <p class="text-sm text-gray-500">Verifikasi KRS mahasiswa bimbingan</p>
    </header>

    <!-- Summary Cards KRS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-gray-100 rounded-lg text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Menunggu Verifikasi</p>
                <h2 class="text-2xl font-bold text-dark">{{ $stats['menunggu'] }}</h2>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-green-100 rounded-lg text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Disetujui</p>
                <h2 class="text-2xl font-bold text-dark">{{ $stats['disetujui'] }}</h2>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-red-100 rounded-lg text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18L18 6"></path>                    
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Ditolak</p>
                <h2 class="text-2xl font-bold text-dark">{{ $stats['ditolak'] }}</h2>
            </div>
        </div>
    </div>

    <!-- Filter Area -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h3 class="font-bold text-dark mb-4">Filter</h3>
        <form method="GET" action="{{ route('krs.verifikasi') }}">
            <div class="flex gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                    <select name="status" class="bg-gray-50 border border-gray-200 text-sm rounded-lg p-2.5 w-40 focus:ring-primary focus:border-primary">
                        <option value="semua" {{ $filterStatus == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="Menunggu" {{ $filterStatus == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Disetujui" {{ $filterStatus == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ $filterStatus == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Kelas</label>
                    <select name="kelas" class="bg-gray-50 border border-gray-200 text-sm rounded-lg p-2.5 w-40 focus:ring-primary focus:border-primary">
                        <option value="semua" {{ $filterKelas == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="A" {{ $filterKelas == 'A' ? 'selected' : '' }}>Kelas A</option>
                        <option value="B" {{ $filterKelas == 'B' ? 'selected' : '' }}>Kelas B</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel KRS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-dark">Daftar KRS Mahasiswa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Mahasiswa</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">Mata Kuliah</th>
                        <th class="px-6 py-3">Total SKS</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarKrs as $krs)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-dark">
                            {{ $krs['nama'] }}
                            <div class="text-xs text-gray-400">{{ $krs['nim'] }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $krs['kelas'] }}</td>
                        <td class="px-6 py-4">{{ $krs['mk_count'] }} MK</td>
                        <td class="px-6 py-4">{{ $krs['total_sks'] }} SKS</td>
                        <td class="px-6 py-4">
                            @if($krs['status'] == 'Disetujui')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Disetujui</span>
                            @elseif($krs['status'] == 'Ditolak')
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Ditolak</span>
                            @else
                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $krs['tanggal'] }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                @if($krs['status'] == 'Menunggu')
                                <!-- Tombol Setujui -->
                                <form action="{{ route('krs.approve', $krs['nim']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-200 flex items-center justify-center transition" title="Setujui" onclick="return confirm('Apakah Anda yakin ingin menyetujui KRS ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                                
                                <!-- Tombol Tolak -->
                                <form action="{{ route('krs.reject', $krs['nim']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition" title="Tolak" onclick="return confirm('Apakah Anda yakin ingin menolak KRS ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <!-- Tampilkan status saja jika sudah disetujui/ditolak -->
                                <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">Tidak ada data KRS yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection