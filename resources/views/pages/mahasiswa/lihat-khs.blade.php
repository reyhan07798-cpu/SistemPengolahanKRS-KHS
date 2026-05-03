@extends('layouts.mahasiswa')

@section('page_title', 'Kartu Hasil Studi')

@php
    if (!isset($data) || !isset($data['nama'])) {
        \Log::warning('Data tidak ditemukan di lihat-khs', ['data' => $data ?? 'NULL']);
    }
@endphp

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Kartu Hasil Studi</h1>
            <p>Rekap nilai dan capaian akademik Anda dari semua semester.</p>
        </div>
    </div>

    <div class="flex justify-end mb-4">
        <a href="/mahasiswa/khs/pdf" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">picture_as_pdf</span>
            Cetak PDF
        </a>
    </div>

    <x-stat-bento 
        :stats="['ipk' => $ipk, 'total_sks' => $totalSks, 'mata_kuliah' => $mataKuliahCount]"
        :config="[
            'ipk' => ['color' => 'nb-stat--accent', 'icon' => 'trending_up'],
            'total_sks' => ['color' => 'nb-stat--primary', 'icon' => 'menu_book'],
            'mata_kuliah' => ['color' => 'nb-stat--warning', 'icon' => 'grade']
        ]" />

    {{-- IP Per Semester --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">bar_chart</span>
            <h3 class="nb-h3">Indeks Prestasi Per Semester</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Tahun Ajaran</th>
                        <th class="text-center">MK Diambil</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">IPS (Semester)</th>
                        <th class="text-center">IPK (Kumulatif)</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
@php
                        $ipSemester = [
                            ['sem' => 1, 'tahun' => '2023/2024', 'mk' => 5, 'sks' => 15, 'ips' => 3.60, 'ipk' => 3.60],
                            ['sem' => 2, 'tahun' => '2023/2024', 'mk' => 5, 'sks' => 15, 'ips' => 3.72, 'ipk' => 3.66],
                            ['sem' => 3, 'tahun' => '2024/2025', 'mk' => 5, 'sks' => 15, 'ips' => 3.54, 'ipk' => 3.62],
                            ['sem' => 4, 'tahun' => '2024/2025', 'mk' => 5, 'sks' => 15, 'ips' => 3.68, 'ipk' => 3.64],
                        ];
                        
                        // Calculate predikat for each semester
                        foreach($ipSemester as &$ip) {
                            $ip['predikat'] = $ip['ips'] >= 3.75 ? 'Dengan Pujian' : 
                                            ($ip['ips'] >= 3.50 ? 'Sangat Memuaskan' : 
                                            ($ip['ips'] >= 3.00 ? 'Memuaskan' : 'Cukup'));
                        }
                    @endphp
                    @foreach($ipSemester as $ip)
                        <x-ip-semester-row 
                            :sem="$ip['sem']"
                            :tahun="$ip['tahun']"
                            :mk="$ip['mk']"
                            :sks="$ip['sks']"
                            :ips="$ip['ips']"
                            :ipk="$ip['ipk']"
                            :predikat="$ip['predikat']"
                        />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Nilai</h3>
        </div>
        <x-filter-mahasiswa id-prefix="filter" />
        <div class="flex items-end mt-4">
            <button type="button" onclick="filterData()" class="nb-btn nb-btn-primary w-full">
                <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                Filter
            </button>
        </div>
    </div>

    {{-- Daftar Nilai Table --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Transkrip</span>
                <h2 class="mt-1">Daftar Nilai</h2>
            </div>
            <span class="nb-badge nb-badge-primary">{{ $mataKuliahCount }} Mata Kuliah</span>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Angka (0–4)</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Tahun Ajaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $n)
                        @php
                            $mutuMap = ['A'=>4.00,'A-'=>3.75,'B+'=>3.50,'B'=>3.25,'B-'=>3.00,'C+'=>2.75,'C'=>2.50,'C-'=>2.25,'D'=>1.00,'E'=>0.00];
                        @endphp
                        <x-khs-nilai-row 
                            kode_mk="{{ $n->kode_mk ?? 'IF' . str_pad($loop->index + 201, 3, '0', STR_PAD_LEFT) }}"
                            :nama_mk="$n->nama_mk ?? 'Mata Kuliah ' . ($loop->index + 1)"
                            sks="{{ $n->sks }}"
                            nilai="{{ $n->nilai }}"
                            :mutu="$mutuMap[$n->nilai] ?? 0.00"
                            bobot="{{ $n->bobot }}"
                            tahun_ajaran="{{ $n->tahun_ajaran }}"
                        />
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <span class="material-symbols-outlined text-muted" style="font-size:48px;">grade</span>
                                <p class="mt-2 text-muted font-medium">Belum ada data nilai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
        <script>
            function filterData() {
                const tahunAjaran = document.getElementById('tahun_ajaran').value;
                const semester = document.getElementById('semester').value;
                
                document.querySelectorAll('tbody tr').forEach(row => {
                    const tahunCell = row.cells[row.cells.length - 1].textContent.trim(); // Tahun Ajaran column
                    const showRow = (!tahunAjaran || tahunCell.includes(tahunAjaran)) && (!semester || tahunCell.includes(semester));
                    row.style.display = showRow ? '' : 'none';
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('tahun_ajaran')?.addEventListener('change', filterData);
                document.getElementById('semester')?.addEventListener('change', filterData);
            });
        </script>
    @endpush
@endsection
