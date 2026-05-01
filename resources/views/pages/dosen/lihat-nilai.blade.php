@extends('layouts.dosen')

@section('title', 'Lihat Nilai')
@section('page_title', 'Lihat Nilai')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen Matkul · Nilai</span>
            <h1 class="mt-2">Nilai Mahasiswa</h1>
            <p>Pantau hasil studi mahasiswa pada mata kuliah yang Anda ampu.</p>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">groups</span>
                </div>
                <div class="min-w-0">
                    <p class="nb-stat-label">Total Mahasiswa</p>
                    <p class="nb-stat-value mt-1">{{ $stats['total_mahasiswa'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">edit_note</span>
                </div>
                <div class="min-w-0">
                    <p class="nb-stat-label">Nilai Terinput</p>
                    <p class="nb-stat-value mt-1">{{ $stats['nilai_terinput'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">analytics</span>
                </div>
                <div class="min-w-0">
                    <p class="nb-stat-label">Rata-rata Nilai</p>
                    <p class="nb-stat-value mt-1">{{ $stats['rata_nilai'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Mata Kuliah</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen.matkul.lihat-nilai') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="nb-label">Pilih Mata Kuliah</label>
                    <select name="mata_kuliah">
                        <option value="semua" {{ ($filterMK ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua Mata Kuliah</option>
                        @foreach($daftarMK ?? [] as $mk)
                            <option value="{{ $mk }}" {{ ($filterMK ?? '') == $mk ? 'selected' : '' }}>{{ $mk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="nb-btn nb-btn-primary w-full">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                        Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(($filterMK ?? 'semua') != 'semua')
        <div class="nb-alert nb-alert-info mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">filter_alt</span>
            <span>Menampilkan data untuk: <strong>{{ $filterMK }}</strong></span>
        </div>
    @endif

    {{-- Tabel Mahasiswa --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Hasil Studi</span>
                <h2 class="mt-1">Daftar Nilai Mahasiswa</h2>
            </div>
            <span class="nb-badge nb-badge-primary">{{ count($mahasiswa ?? []) }} data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th class="text-center">Kelas</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa ?? [] as $mhs)
                        @php
                            $gradeBadge = match($mhs['grade']) {
                                'A' => 'nb-badge-success',
                                'B' => 'nb-badge-primary',
                                'C' => 'nb-badge-warning',
                                'D' => 'nb-badge-warning',
                                default => 'nb-badge-danger',
                            };
                        @endphp
                        <tr>
                            <td class="font-bold text-muted">{{ $mhs['no'] }}</td>
                            <td class="font-bold text-primary text-sm" style="font-family: var(--font-heading);">{{ $mhs['nim'] }}</td>
                            <td class="font-medium text-ink">{{ $mhs['nama'] }}</td>
                            <td class="text-center"><span class="nb-badge nb-badge-stable">{{ $mhs['kelas'] }}</span></td>
                            <td><span class="nb-badge nb-badge-primary">{{ $mhs['mata_kuliah'] }}</span></td>
                            <td class="text-center font-extrabold text-primary text-lg" style="font-family: var(--font-heading);">{{ number_format($mhs['nilai'], 1) }}</td>
                            <td class="text-center"><span class="nb-badge {{ $gradeBadge }}">{{ $mhs['grade'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <span class="material-symbols-outlined text-muted" style="font-size:48px;">search_off</span>
                                <p class="mt-2 text-muted font-medium">Tidak ada data nilai untuk filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
