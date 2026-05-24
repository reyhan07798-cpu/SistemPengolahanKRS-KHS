@extends('layouts.dosen')

@section('title', 'Lihat Nilai')
@section('page_title', 'Lihat Nilai')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Nilai</span>
            <h1 class="mt-2">Nilai Mahasiswa</h1>
            <p>Pantau dan rekap nilai rinci mahasiswa pada mata kuliah yang Anda ampu.</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon"><span class="material-symbols-outlined filled">groups</span></div>
                <div><p class="nb-stat-label">Total Mahasiswa</p><p class="nb-stat-value mt-1">{{ $stats['total_mahasiswa'] }}</p></div>
            </div>
        </div>
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon"><span class="material-symbols-outlined filled">edit_note</span></div>
                <div><p class="nb-stat-label">Nilai Terinput</p><p class="nb-stat-value mt-1">{{ $stats['nilai_terinput'] }}</p></div>
            </div>
        </div>
        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon"><span class="material-symbols-outlined filled">analytics</span></div>
                <div><p class="nb-stat-label">Rata-rata Nilai</p><p class="nb-stat-value mt-1">{{ $stats['rata_nilai'] }}</p></div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen_matkul.lihat-nilai') }}">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="nb-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran">
                        <option value="">Semua</option>
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $filterTahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Semester</label>
                    <select name="semester">
                        <option value="">Semua</option>
                        @foreach($semesterList as $sem)
                            <option value="{{ $sem }}" {{ $filterSemester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Mata Kuliah</label>
                    <select name="mata_kuliah_id">
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($daftarMK as $mk)
                            <option value="{{ $mk->id }}" {{ $filterMK == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode_mk }} - {{ $mk->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Kelas</label>
                    <select name="kelas">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kls)
                            <option value="{{ $kls }}" {{ $filterKelas == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">search</span> Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Nilai Rinci --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">Rekap Nilai Rinci</span>
                <h2 class="mt-1">Daftar Nilai Mahasiswa</h2>
            </div>
            <span class="nb-badge nb-badge-primary">{{ count($mahasiswa) }} data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM / Nama</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">Praktikum</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Mutu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $mhs)
                        @php
                            $nilaiAkhir = (float)($mhs['nilai_akhir'] ?? 0);
                            $grade      = $mhs['grade'] ?? '—';
                            $mutu       = (float)($mhs['mutu'] ?? 0);
                            $gradeBadge = match($grade) {
                                'A','A-'       => 'nb-badge-success',
                                'B+','B','B-'  => 'nb-badge-primary',
                                'C+','C'       => 'nb-badge-warning',
                                'D'            => 'nb-badge-warning',
                                default        => 'nb-badge-danger',
                            };
                            $mutuClass = $mutu >= 3.5 ? 'text-accent' : ($mutu >= 2.5 ? 'text-primary' : 'text-muted');
                        @endphp
                        <tr>
                            <td class="font-bold text-muted">{{ $mhs['no'] }}</td>
                            <td>
                                <div class="font-bold text-ink text-sm">{{ $mhs['nama'] }}</div>
                            </td>
                            <td><span class="nb-badge nb-badge-stable text-xs">{{ $mhs['kode_mk'] }} - {{ $mhs['nama_mk'] }}</span></td>
                            <td class="text-center text-sm">{{ $mhs['nilai_tugas'] !== null ? number_format($mhs['nilai_tugas'],1) : '—' }}</td>
                            <td class="text-center text-sm">{{ $mhs['nilai_praktikum'] !== null ? number_format($mhs['nilai_praktikum'],1) : '—' }}</td>
                            <td class="text-center text-sm">{{ $mhs['nilai_uts'] !== null ? number_format($mhs['nilai_uts'],1) : '—' }}</td>
                            <td class="text-center text-sm">{{ $mhs['nilai_uas'] !== null ? number_format($mhs['nilai_uas'],1) : '—' }}</td>
                            <td class="text-center text-sm">{{ $mhs['nilai_kehadiran'] !== null ? number_format($mhs['nilai_kehadiran'],1) : '—' }}</td>
                            <td class="text-center font-extrabold text-primary text-lg" style="font-family:var(--font-heading);">
                                {{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 1) : '—' }}
                            </td>
                            <td class="text-center"><span class="nb-badge {{ $gradeBadge }}">{{ $grade }}</span></td>
                            <td class="text-center">
                                <span class="font-extrabold text-lg {{ $mutuClass }}" style="font-family:var(--font-heading);">
                                    {{ $mutu > 0 ? number_format($mutu, 2) : '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-12">
                                <span class="material-symbols-outlined text-muted" style="font-size:48px;">search_off</span>
                                <p class="mt-2 text-muted font-medium">Belum ada data nilai. Gunakan filter di atas atau input nilai terlebih dahulu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection