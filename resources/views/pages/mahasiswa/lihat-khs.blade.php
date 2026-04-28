@extends('layouts.mahasiswa')
@php
    // DEBUG: Hapus setelah selesai
    if (!isset($data) || !isset($data['nama'])) {
        \Log::warning('Data tidak ditemukan di lihat-khs', ['data' => $data ?? 'NULL']);
    }
@endphp
@section('content')
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Kartu Hasil Studi</h1>
        <p class="text-sm text-gray-500">Lihat hasil studi Anda.</p>
    </header>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <x-khs-stat-card title="IPK" :value="$ipk" accent="bg-slate-100" textColor="text-slate-900"
            icon='<svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>' />

        <x-khs-stat-card title="Total SKS" :value="$totalSks" accent="bg-emerald-100" textColor="text-emerald-700"
            icon='<svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>' />

        <x-khs-stat-card title="Mata Kuliah" :value="$mataKuliahCount" accent="bg-blue-100" textColor="text-blue-700"
            icon='<svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' />
    </div>

    <!-- Filter Section -->
    <div class="bg-slate-100 rounded-2xl p-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Filter</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tahun Ajaran Filter -->
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                    <select id="tahun_ajaran" name="tahun_ajaran"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        <option value="2025/2026" selected>2025/2026</option>
                        <option value="2024/2025">2024/2025</option>
                        <option value="2023/2024">2023/2024</option>
                    </select>
                </div>

                <!-- Semester Filter -->
                <div>
                    <label for="semester" class="block text-sm font-semibold text-slate-700 mb-2">Semester</label>
                    <select id="semester" name="semester"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">-- Semua Semester --</option>
                        <option value="1" selected>Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                        <option value="6">Semester 6</option>
                        <option value="7">Semester 7</option>
                        <option value="8">Semester 8</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Nilai Table -->
    <div class="bg-slate-100 rounded-2xl p-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Daftar Nilai</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-200 text-slate-700 text-sm uppercase tracking-[0.2em]">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Kode</th>
                            <th class="px-6 py-4 text-left font-semibold">Mata Kuliah</th>
                            <th class="px-6 py-4 text-center font-semibold">SKS</th>
                            <th class="px-6 py-4 text-center font-semibold">Nilai</th>
                            <th class="px-6 py-4 text-center font-semibold">Bobot</th>
                            <th class="px-6 py-4 text-center font-semibold">Tahun Ajaran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200 text-sm text-slate-700">
                        @forelse($nilai as $n)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $n->kode_mk ?? 'IF' . str_pad($loop->index + 201, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4">{{ $n->nama_mk ?? 'Mata Kuliah ' . ($loop->index + 1) }}</td>
                                <td class="px-6 py-4 text-center">{{ $n->sks }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-white"
                                        style="background-color: {{ $n->color }};">
                                        {{ $n->nilai }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center font-semibold">{{ $n->bobot }}</td>
                                <td class="px-6 py-4 text-center">{{ $n->tahun_ajaran }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Belum ada data nilai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
        document.getElementById('tahun_ajaran').addEventListener('change', function () {
            filterData();
        });

        document.getElementById('semester').addEventListener('change', function () {
            filterData();
        });

        function filterData() {
            const tahunAjaran = document.getElementById('tahun_ajaran').value;
            const semester = document.getElementById('semester').value;

            // TODO: Implementasi filter dengan AJAX jika diperlukan
            console.log('Filter: Tahun Ajaran = ' + tahunAjaran + ', Semester = ' + semester);
        }

        // Function untuk mendapatkan warna nilai (bisa dipindahkan ke controller/helper)
        function getNilaiColor(nilai) {
            const colors = {
                'A': '#22c55e',  // green
                'A-': '#84cc16', // lime
                'B+': '#eab308', // yellow
                'B': '#f97316',  // orange
                'B-': '#ef4444', // red
                'C+': '#dc2626', // dark-red
                'C': '#7f1d1d',  // very-dark-red
                'D': '#4b5563',  // gray
                'E': '#1f2937'   // dark-gray
            };
            return colors[nilai] || '#666';
        }
    </script>

    @php
        // Helper function untuk get warna nilai
        if (!function_exists('getNilaiColor')) {
            function getNilaiColor($nilai)
            {
                $colors = [
                    'A' => '#22c55e',
                    'A-' => '#84cc16',
                    'B+' => '#eab308',
                    'B' => '#f97316',
                    'B-' => '#ef4444',
                    'C+' => '#dc2626',
                    'C' => '#7f1d1d',
                    'D' => '#4b5563',
                    'E' => '#1f2937'
                ];
                return $colors[$nilai] ?? '#666';
            }
        }
    @endphp
@endsection