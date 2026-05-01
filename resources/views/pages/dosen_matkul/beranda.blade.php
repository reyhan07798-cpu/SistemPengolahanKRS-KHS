@extends('layouts.dosen')

@section('title', 'Beranda Dosen Matkul')
@section('page_title', 'Beranda')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen Mata Kuliah</span>
            <h1 class="mt-2">Selamat Datang</h1>
            <p>Kelola nilai dan pantau mahasiswa pada mata kuliah yang Anda ampu.</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('pages.dosen_matkul.input-nilai') }}" class="nb-btn nb-btn-primary">
                <span class="material-symbols-outlined" style="font-size:20px;">edit_note</span>
                Input Nilai
            </a>
            <a href="{{ route('pages.dosen_matkul.lihat-nilai') }}" class="nb-btn nb-btn-secondary">
                <span class="material-symbols-outlined" style="font-size:20px;">analytics</span>
                Lihat Nilai
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">menu_book</span>
                </div>
                <p class="nb-stat-label">Mata Kuliah Diampu</p>
            </div>
            <div class="nb-stat-value">{{ $stats['mata_kuliah_diampu'] }}</div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">groups</span>
                </div>
                <p class="nb-stat-label">Total Mahasiswa</p>
            </div>
            <div class="nb-stat-value">{{ $stats['total_mahasiswa'] }}</div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">edit_note</span>
                </div>
                <p class="nb-stat-label">Nilai Diinput</p>
            </div>
            <div class="nb-stat-value">{{ $stats['nilai_diinput'] }}</div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">pending_actions</span>
                </div>
                <p class="nb-stat-label">Belum Dinilai</p>
            </div>
            <div class="nb-stat-value">{{ $stats['belum_dinilai'] }}</div>
        </div>
    </div>

    {{-- Mata Kuliah yang Diampu --}}
    <div class="nb-card mb-8">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <span class="nb-eyebrow">Pengajaran</span>
                <h3 class="nb-h3 mt-1">Mata Kuliah yang Diampu</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($mataKuliah as $mk)
                <div class="nb-card flex flex-col gap-3" style="padding: 1.25rem;">
                    <div class="flex justify-between items-start">
                        <span class="nb-badge nb-badge-stable">{{ $mk['kode'] }}</span>
                        <span class="nb-badge nb-badge-success">{{ $mk['sks'] }} SKS</span>
                    </div>
                    <h4 class="font-extrabold text-ink text-lg" style="font-family: var(--font-heading);">{{ $mk['nama'] }}</h4>
                    <div class="space-y-1 text-sm">
                        <p class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-muted" style="font-size:16px;">calendar_today</span>
                            <span class="text-muted">Semester {{ $mk['semester'] }}</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-muted" style="font-size:16px;">schedule</span>
                            <span class="text-muted">{{ $mk['jadwal'] }}</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-muted" style="font-size:16px;">room</span>
                            <span class="text-muted">{{ $mk['ruang'] }}</span>
                        </p>
                    </div>
                    <div class="pt-3 border-t-2 border-[rgba(31,41,55,0.15)] flex justify-between items-center">
                        <span class="nb-label" style="margin-bottom:0;">Mahasiswa</span>
                        <span class="font-extrabold text-primary" style="font-family: var(--font-heading);">
                            {{ $mk['mahasiswa'] }}/{{ $mk['kapasitas'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Mahasiswa Terbaru --}}
    <div class="nb-card">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <span class="nb-eyebrow">Aktivitas</span>
                <h3 class="nb-h3 mt-1">Mahasiswa Terbaru</h3>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($mahasiswaTerbaru as $mhs)
                @php
                    $initials = collect(explode(' ', $mhs['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                @endphp
                <div class="nb-list-row">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="nb-avatar-sm" style="cursor: default;">
                            <span class="font-extrabold text-xs" style="font-family: var(--font-heading);">{{ $initials }}</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-ink text-sm">{{ $mhs['nama'] }}</h4>
                            <p class="text-xs text-muted">{{ $mhs['nim'] }} · {{ $mhs['prodi'] }} · Kelas {{ $mhs['kelas'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="nb-label" style="margin-bottom:2px;">Rata-rata Nilai</p>
                        <p class="font-extrabold text-primary text-lg" style="font-family: var(--font-heading);">{{ number_format($mhs['rata_nilai'], 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
