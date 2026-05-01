@extends('layouts.dosen')

@section('title', 'Beranda Dosen Wali')
@section('page_title', 'Beranda')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen Wali</span>
            <h1 class="mt-2">Selamat Datang</h1>
            <p>Pantau status KRS dan progres akademik mahasiswa bimbingan Anda.</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('pages.dosen_wali.krs.verifikasi') }}" class="nb-btn nb-btn-secondary">
                <span class="material-symbols-outlined" style="font-size:20px;">fact_check</span>
                Verifikasi KRS
            </a>
            <a href="{{ route('pages.dosen_wali.khs') }}" class="nb-btn nb-btn-primary">
                <span class="material-symbols-outlined" style="font-size:20px;">assessment</span>
                Lihat KHS
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">groups</span>
                </div>
                <p class="nb-stat-label">Mahasiswa Bimbingan</p>
            </div>
            <div class="nb-stat-value">{{ $stats['mahasiswa_bimbingan'] }}</div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">schedule</span>
                </div>
                <p class="nb-stat-label">KRS Menunggu</p>
            </div>
            <div class="nb-stat-value">{{ $stats['krs_menunggu'] }}</div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">check_circle</span>
                </div>
                <p class="nb-stat-label">KRS Disetujui</p>
            </div>
            <div class="nb-stat-value">{{ $stats['krs_disetujui'] }}</div>
        </div>

        <div class="nb-stat nb-stat--danger nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">cancel</span>
                </div>
                <p class="nb-stat-label">KRS Ditolak</p>
            </div>
            <div class="nb-stat-value">{{ $stats['krs_ditolak'] }}</div>
        </div>
    </div>

    {{-- Daftar Mahasiswa Bimbingan --}}
    <div class="nb-card mb-8">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <span class="nb-eyebrow">Bimbingan</span>
                <h3 class="nb-h3 mt-1">Mahasiswa Bimbingan</h3>
            </div>
            <a href="{{ route('pages.dosen_wali.khs') }}" class="nb-btn nb-btn-secondary nb-btn-sm">
                Lihat Semua
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>

        <div class="space-y-3">
            @foreach($mahasiswa as $m)
                @php
                    $statusBadge = match($m['status_krs'] ?? '') {
                        'Disetujui' => 'nb-badge-success',
                        'Menunggu' => 'nb-badge-warning',
                        'Ditolak' => 'nb-badge-danger',
                        default => 'nb-badge-stable',
                    };
                    $initials = collect(explode(' ', $m['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                @endphp
                <div class="nb-list-row">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="nb-avatar-sm" style="cursor: default;">
                            <span class="font-extrabold text-xs" style="font-family: var(--font-heading);">{{ $initials }}</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-ink text-sm">{{ $m['nama'] }}</h4>
                            <p class="text-xs text-muted">{{ $m['nim'] }} · {{ $m['prodi'] }} · Kelas {{ $m['kelas'] }}</p>
                        </div>
                    </div>
                    <div class="text-right flex items-center gap-4">
                        <div>
                            <p class="nb-label" style="margin-bottom:2px;">IPK</p>
                            <p class="font-extrabold text-primary text-lg" style="font-family: var(--font-heading);">{{ number_format($m['ipk'], 2) }}</p>
                        </div>
                        <span class="nb-badge {{ $statusBadge }}">{{ $m['status_krs'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bottom Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="nb-card">
            <span class="nb-eyebrow">Statistik</span>
            <h4 class="nb-h3 mt-1 mb-4">Ringkasan Akademik</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b-2 border-[rgba(31,41,55,0.15)]">
                    <span class="text-sm text-muted font-medium">Mahasiswa IPK ≥ 3.0</span>
                    <span class="nb-badge nb-badge-stable">2 dari 3</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b-2 border-[rgba(31,41,55,0.15)]">
                    <span class="text-sm text-muted font-medium">Total KRS Disetujui</span>
                    <span class="font-extrabold text-primary text-lg" style="font-family: var(--font-heading);">{{ $stats['krs_disetujui'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-muted font-medium">Total KRS Menunggu</span>
                    <span class="font-extrabold text-primary text-lg" style="font-family: var(--font-heading);">{{ $stats['krs_menunggu'] }}</span>
                </div>
            </div>
        </div>

        <div class="nb-card">
            <span class="nb-eyebrow">Distribusi</span>
            <h4 class="nb-h3 mt-1 mb-4">Distribusi Kelas</h4>
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-muted font-medium">Kelas A</span>
                <span class="font-extrabold text-primary text-lg" style="font-family: var(--font-heading);">{{ $stats['mahasiswa_bimbingan'] }}</span>
            </div>
            <div class="w-full bg-surface-alt border-2 border-ink rounded-full h-4 overflow-hidden">
                <div class="bg-accent h-full transition-all" style="width: 100%; border-right: 2px solid var(--color-ink);"></div>
            </div>
        </div>
    </div>
@endsection
