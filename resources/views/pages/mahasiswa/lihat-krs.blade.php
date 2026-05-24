@extends('layouts.mahasiswa')

@section('page_title', 'Lihat KRS')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Lihat KRS</h1>
            <p>Daftar KRS yang telah Anda ajukan dan status persetujuannya.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Jika belum ada KRS -->
    @if($krsRecords->isEmpty())
        <div class="nb-card">
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <span class="material-symbols-outlined text-gray-400" style="font-size:64px;">assignment</span>
                <h3 class="nb-h3 mt-4 text-gray-600">Belum ada KRS</h3>
                <p class="text-gray-500 mt-2 mb-6">Anda belum mengajukan KRS. Silakan ajukan KRS untuk semester ini.</p>
                <a href="{{ route('pages.mahasiswa.ambil-krs') }}" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined">add</span>
                    Ambil KRS
                </a>
            </div>
        </div>
    @else
        <!-- Daftar KRS Berdasarkan Periode -->
        @foreach($krsRecords as $periode => $krsData)
            <div class="nb-card mb-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <h3 class="nb-h3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">calendar_month</span>
                            {{ $periode }}
                        </h3>
                    </div>
                    <div class="flex gap-3">
                        <!-- Status Badge -->
                        @php
                            $stats = $statistik[$periode];
                            $statusColor = match(true) {
                                $stats['ditolak'] > 0 => 'danger',
                                $stats['menunggu'] > 0 => 'warning',
                                $stats['disetujui'] === $stats['total_mk'] => 'success',
                                default => 'info'
                            };
                            $statusText = match(true) {
                                $stats['ditolak'] > 0 => 'Ada yang Ditolak',
                                $stats['menunggu'] > 0 => 'Menunggu Persetujuan',
                                $stats['disetujui'] === $stats['total_mk'] => 'Disetujui Semua',
                                default => 'Sebagian Disetujui'
                            };
                        @endphp
                        <span class="nb-badge nb-badge-{{ $statusColor }}">{{ $statusText }}</span>
                    </div>
                </div>

                <!-- Statistik Periode -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total MK</p>
                        <p class="nb-h3">{{ $stats['total_mk'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total SKS</p>
                        <p class="nb-h3">{{ $stats['total_sks'] }} SKS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Disetujui</p>
                        <p class="nb-h3 text-success">{{ $stats['disetujui'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Menunggu</p>
                        <p class="nb-h3 text-warning">{{ $stats['menunggu'] }}</p>
                    </div>
                </div>

                <!-- Tabel Mata Kuliah -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Kode MK</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama Mata Kuliah</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-700">SKS</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-700">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($krsData as $krs)
                                @php
                                    $statusClass = match($krs->status) {
                                        'disetujui' => 'success',
                                        'ditolak' => 'danger',
                                        'menunggu' => 'warning',
                                        default => 'info'
                                    };
                                    $statusLabel = match($krs->status) {
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'menunggu' => 'Menunggu',
                                        default => 'Unknown'
                                    };
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4 font-mono text-gray-700">{{ $krs->mataKuliah->kode_mk ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $krs->mataKuliah->nama ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                            {{ $krs->mataKuliah->sks ?? 0 }} SKS
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                                            @if($statusClass === 'success')
                                                bg-success bg-opacity-20 text-success
                                            @elseif($statusClass === 'danger')
                                                bg-red-100 text-red-800
                                            @elseif($statusClass === 'warning')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            @if($statusClass === 'success')
                                                <span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>
                                            @elseif($statusClass === 'danger')
                                                <span class="material-symbols-outlined" style="font-size:16px;">cancel</span>
                                            @elseif($statusClass === 'warning')
                                                <span class="material-symbols-outlined" style="font-size:16px;">schedule</span>
                                            @endif
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center text-gray-600">
                                        @if($krs->catatan)
                                            <span title="{{ $krs->catatan }}" class="cursor-help text-yellow-600">
                                                {{ Str::limit($krs->catatan, 30) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Info Persetujuan -->
                @if($krs->first()?->tanggal_disetujui)
                    <div class="mt-4 pt-4 border-t border-gray-200 text-xs text-gray-600">
                        <p>Disetujui pada: <span class="font-semibold">{{ $krs->first()->tanggal_disetujui->format('d M Y H:i') }}</span></p>
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Tombol Kembali -->
    <div class="flex gap-3 mt-6">
        <a href="{{ route('pages.mahasiswa.beranda') }}" class="nb-btn nb-btn-outline">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Beranda
        </a>
        <a href="{{ route('pages.mahasiswa.ambil-krs') }}" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined">add</span>
            Ajukan KRS Baru
        </a>
    </div>
@endsection
