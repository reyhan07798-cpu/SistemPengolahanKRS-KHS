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
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Bobot</th>
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
                        <tr>
                            <td class="font-bold text-primary" style="font-family: var(--font-heading);">
                                {{ $n->kode_mk ?? 'IF' . str_pad($loop->index + 201, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="font-medium text-ink">{{ $n->nama_mk ?? 'Mata Kuliah ' . ($loop->index + 1) }}</td>
                            <td class="text-center">{{ $n->sks }}</td>
                            <td class="text-center">
                                <span class="nb-badge {{ $nilaiBadge }}">{{ $n->nilai }}</span>
                            </td>
                            <td class="text-center font-bold text-primary">{{ $n->bobot }}</td>
                            <td class="text-center text-muted">{{ $n->tahun_ajaran }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
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