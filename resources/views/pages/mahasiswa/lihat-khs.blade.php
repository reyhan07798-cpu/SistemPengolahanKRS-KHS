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

    {{-- Statistik Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <p class="nb-stat-label">IPK</p>
            </div>
            <div class="nb-stat-value">{{ $ipk }}</div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">menu_book</span>
                </div>
                <p class="nb-stat-label">Total SKS</p>
            </div>
            <div class="nb-stat-value">{{ $totalSks }}</div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">grade</span>
                </div>
                <p class="nb-stat-label">Mata Kuliah</p>
            </div>
            <div class="nb-stat-value">{{ $mataKuliahCount }}</div>
        </div>
    </div>

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
                    @endphp
                    @foreach($ipSemester as $ip)
                        @php
                            $predikat = match(true) {
                                $ip['ips'] >= 3.75 => ['label' => 'Dengan Pujian', 'badge' => 'nb-badge-success'],
                                $ip['ips'] >= 3.50 => ['label' => 'Sangat Memuaskan', 'badge' => 'nb-badge-primary'],
                                $ip['ips'] >= 3.00 => ['label' => 'Memuaskan', 'badge' => 'nb-badge-warning'],
                                default             => ['label' => 'Cukup', 'badge' => 'nb-badge-stable'],
                            };
                            $ipsClass = $ip['ips'] >= 3.5 ? 'text-accent' : ($ip['ips'] >= 3.0 ? 'text-primary' : 'text-muted');
                        @endphp
                        <tr>
                            <td class="text-center font-bold text-ink">Semester {{ $ip['sem'] }}</td>
                            <td class="text-center text-muted">{{ $ip['tahun'] }}</td>
                            <td class="text-center font-bold text-primary">{{ $ip['mk'] }} MK</td>
                            <td class="text-center font-bold text-primary">{{ $ip['sks'] }} SKS</td>
                            <td class="text-center">
                                <span class="font-extrabold text-xl {{ $ipsClass }}" style="font-family:var(--font-heading);">{{ number_format($ip['ips'], 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-bold text-ink" style="font-family:var(--font-heading);">{{ number_format($ip['ipk'], 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="nb-badge {{ $predikat['badge'] }}">{{ $predikat['label'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="nb-card mb-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h2 class="nb-h3">Filter</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="tahun_ajaran" class="nb-label">Tahun Ajaran</label>
                <select id="tahun_ajaran" name="tahun_ajaran">
                    <option value="">-- Semua Tahun Ajaran --</option>
                    <option value="2025/2026" selected>2025/2026</option>
                    <option value="2024/2025">2024/2025</option>
                    <option value="2023/2024">2023/2024</option>
                </select>
            </div>

            <div>
                <label for="semester" class="nb-label">Semester</label>
                <select id="semester" name="semester">
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
                            $nilaiBadge = match ($n->nilai) {
                                'A', 'A-' => 'nb-badge-success',
                                'B+', 'B' => 'nb-badge-primary',
                                'B-', 'C+', 'C' => 'nb-badge-warning',
                                default => 'nb-badge-danger',
                            };
                        @endphp
                        @php
                            $mutuMap = ['A'=>4.00,'A-'=>3.75,'B+'=>3.50,'B'=>3.25,'B-'=>3.00,'C+'=>2.75,'C'=>2.50,'C-'=>2.25,'D'=>1.00,'E'=>0.00];
                            $mutuVal = $mutuMap[$n->nilai] ?? 0.00;
                            $mutuClass = $mutuVal >= 3.5 ? 'text-accent' : ($mutuVal >= 2.5 ? 'text-primary' : 'text-muted');
                        @endphp
                        <tr>
                            <td class="font-bold text-primary" style="font-family: var(--font-heading);">
                                {{ $n->kode_mk ?? 'IF' . str_pad($loop->index + 201, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="font-medium text-ink">{{ $n->nama_mk ?? 'Mata Kuliah ' . ($loop->index + 1) }}</td>
                            <td class="text-center">{{ $n->sks }}</td>
                            <td class="text-center">
                                <span class="nb-badge {{ $nilaiBadge }}">{{ $n->nilai }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-extrabold {{ $mutuClass }}" style="font-family:var(--font-heading);">{{ number_format($mutuVal, 2) }}</span>
                            </td>
                            <td class="text-center font-bold text-primary">{{ $n->bobot }}</td>
                            <td class="text-center text-muted">{{ $n->tahun_ajaran }}</td>
                        </tr>
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
            document.getElementById('tahun_ajaran')?.addEventListener('change', filterData);
            document.getElementById('semester')?.addEventListener('change', filterData);
            function filterData() {
                const tahunAjaran = document.getElementById('tahun_ajaran').value;
                const semester = document.getElementById('semester').value;
                console.log('Filter: Tahun Ajaran = ' + tahunAjaran + ', Semester = ' + semester);
            }
        </script>
    @endpush
@endsection