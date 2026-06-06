@extends('layouts.mahasiswa')

@section('page_title', 'Kartu Hasil Studi')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Kartu Hasil Studi</h1>
            <p>Rekap nilai dan capaian akademik Anda dari semua semester.</p>
        </div>
    </div>

    <div class="flex justify-end mb-4">
        <a href="{{ route('pages.mahasiswa.khs.pdf') }}" target="_blank" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">picture_as_pdf</span>
            Cetak KHS
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="nb-bento mb-6" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <p class="nb-stat-label">IPS</p>
            </div>
            <div class="nb-stat-value">{{ number_format($ipsSemesterAktif, 2) }}</div>
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
                        <th class="text-center">IPS</th>
                        <th class="text-center">IPK Kumulatif</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ipSemester as $ip)
                        @php
                            $semesterText = match ((string) $ip->semester) {
                                '1' => 'Ganjil',
                                '2' => 'Genap',
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                                default => $ip->semester,
                            };

                            $semesterBadge = $semesterText === 'Ganjil'
                                ? 'nb-badge-info'
                                : 'nb-badge-primary';

                            $ipsClass = $ip->ips >= 3.5
                                ? 'text-accent'
                                : ($ip->ips >= 3.0 ? 'text-primary' : 'text-muted');
                        @endphp

                        <tr>
                            <td class="text-center">
                                <span class="nb-badge {{ $semesterBadge }}">
                                    {{ $semesterText }}
                                </span>
                            </td>

                            <td class="text-center text-muted">
                                {{ $ip->tahun_ajaran }}
                            </td>

                            <td class="text-center font-bold text-primary">
                                {{ $ip->mk }} MK
                            </td>

                            <td class="text-center font-bold text-primary">
                                {{ $ip->sks }} SKS
                            </td>

                            <td class="text-center">
                                <span class="font-extrabold text-xl {{ $ipsClass }}" style="font-family:var(--font-heading);">
                                    {{ number_format($ip->ips, 2) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="font-bold text-ink" style="font-family:var(--font-heading);">
                                    {{ number_format($ip->ipk, 2) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="nb-badge {{ $ip->predikat['badge'] }}">
                                    {{ $ip->predikat['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-muted">
                                Belum ada data IP semester.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Nilai</h3>
        </div>

        <form method="GET" action="{{ route('pages.mahasiswa.lihat-khs') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="tahun_ajaran" class="nb-label">Tahun Ajaran</label>
                    <select id="tahun_ajaran" name="tahun_ajaran">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        @foreach($listTahun as $tahun)
                            <option value="{{ $tahun }}" @selected(($tahunFilter ?? '') === $tahun)>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semester" class="nb-label">Semester</label>
                    <select id="semester" name="semester">
                        <option value="">-- Semua Semester --</option>
                        <option value="Ganjil" @selected(($semesterFilter ?? '') === 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(($semesterFilter ?? '') === 'Genap')>Genap</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="nb-btn nb-btn-primary w-full">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Daftar Nilai --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">Transkrip</span>
                <h2 class="mt-1">Daftar Nilai</h2>
            </div>

            <span class="nb-badge nb-badge-primary">
                {{ $nilaiCount ?? $nilai->count() }} Mata Kuliah
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table" id="tabelNilai">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Angka (0–4)</th>
                        <th class="text-center">Tahun Ajaran</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($nilai as $n)
                        @php
                            $mutu = (float) $n->bobot;

                            $mutuClass = $mutu >= 3.5
                                ? 'text-accent'
                                : ($mutu >= 2.5 ? 'text-primary' : 'text-muted');

                            $nilaiBadge = match ($n->nilai) {
                                'A', 'A-' => 'nb-badge-success',
                                'B+', 'B' => 'nb-badge-primary',
                                'B-', 'C+', 'C' => 'nb-badge-warning',
                                default => 'nb-badge-danger',
                            };

                            $semesterText = match ((string) $n->semester) {
                                '1' => 'Ganjil',
                                '2' => 'Genap',
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                                default => $n->semester,
                            };

                            $semesterBadge = $semesterText === 'Ganjil'
                                ? 'nb-badge-info'
                                : 'nb-badge-primary';
                        @endphp

                        <tr>
                            <td class="font-bold text-primary" style="font-family:var(--font-heading);">
                                {{ $n->kode_mk }}
                            </td>

                            <td class="font-medium text-ink">
                                {{ $n->nama_mk }}
                            </td>

                            <td class="text-center">
                                {{ $n->sks }}
                            </td>

                            <td class="text-center">
                                <span class="nb-badge {{ $semesterBadge }}">
                                    {{ $semesterText }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="nb-badge {{ $nilaiBadge }}">
                                    {{ $n->nilai }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="font-extrabold {{ $mutuClass }}" style="font-family:var(--font-heading);">
                                    {{ number_format($mutu, 2) }}
                                </span>
                            </td>

                            <td class="text-center text-muted">
                                {{ $n->tahun_ajaran }}
                            </td>
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
@endsection