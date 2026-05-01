@extends('layouts.dosen')

@section('title', 'Beranda Dosen')
@section('page_title', 'Beranda')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen</span>
            <h1 class="mt-2">Selamat Datang</h1>
            <p>Kelola tugas Anda sebagai dosen wali dan dosen mata kuliah dalam satu tempat.</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('pages.dosen.wali.krs.verifikasi') }}" class="nb-btn nb-btn-secondary">
                <span class="material-symbols-outlined" style="font-size:20px;">fact_check</span>
                Verifikasi KRS
            </a>
            <a href="{{ route('pages.dosen.matkul.input-nilai') }}" class="nb-btn nb-btn-primary">
                <span class="material-symbols-outlined" style="font-size:20px;">edit_note</span>
                Input Nilai
            </a>
        </div>
    </div>

    {{-- Statistik Cards (Wali) --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-primary">supervisor_account</span>
            <h3 class="nb-h3">Sebagai Dosen Wali</h3>
        </div>
        <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 0;">
            <div class="nb-stat nb-stat--info nb-stat--ribbon">
                <div class="flex items-center gap-3">
                    <div class="nb-stat-icon">
                        <span class="material-symbols-outlined filled">groups</span>
                    </div>
                    <p class="nb-stat-label">Mahasiswa Bimbingan</p>
                </div>
                <div class="nb-stat-value">{{ $stats['mahasiswa_bimbingan'] ?? 0 }}</div>
            </div>

            <div class="nb-stat nb-stat--warning nb-stat--ribbon">
                <div class="flex items-center gap-3">
                    <div class="nb-stat-icon">
                        <span class="material-symbols-outlined filled">schedule</span>
                    </div>
                    <p class="nb-stat-label">KRS Menunggu</p>
                </div>
                <div class="nb-stat-value">{{ $stats['krs_menunggu'] ?? 0 }}</div>
            </div>

            <div class="nb-stat nb-stat--accent nb-stat--ribbon">
                <div class="flex items-center gap-3">
                    <div class="nb-stat-icon">
                        <span class="material-symbols-outlined filled">check_circle</span>
                    </div>
                    <p class="nb-stat-label">KRS Disetujui</p>
                </div>
                <div class="nb-stat-value">{{ $stats['krs_disetujui'] ?? 0 }}</div>
            </div>

            <div class="nb-stat nb-stat--danger nb-stat--ribbon">
                <div class="flex items-center gap-3">
                    <div class="nb-stat-icon">
                        <span class="material-symbols-outlined filled">cancel</span>
                    </div>
                    <p class="nb-stat-label">KRS Ditolak</p>
                </div>
                <div class="nb-stat-value">{{ $stats['krs_ditolak'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('pages.dosen.wali.krs.verifikasi') }}" class="nb-card nb-stat--primary hover:bg-surface-alt transition-colors block" style="text-decoration: none;">
            <div class="flex items-start gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">fact_check</span>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="nb-eyebrow">Wali</span>
                    <h3 class="nb-h3 mt-1">Verifikasi KRS</h3>
                    <p class="text-sm text-muted mt-2">Setujui atau tolak pengajuan KRS dari mahasiswa bimbingan Anda.</p>
                </div>
                <span class="material-symbols-outlined text-muted">arrow_forward</span>
            </div>
        </a>

        <a href="{{ route('pages.dosen.matkul.input-nilai') }}" class="nb-card nb-stat--accent hover:bg-surface-alt transition-colors block" style="text-decoration: none;">
            <div class="flex items-start gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">edit_note</span>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="nb-eyebrow">Matkul</span>
                    <h3 class="nb-h3 mt-1">Input Nilai</h3>
                    <p class="text-sm text-muted mt-2">Input nilai akhir mahasiswa untuk mata kuliah yang Anda ampu.</p>
                </div>
                <span class="material-symbols-outlined text-muted">arrow_forward</span>
            </div>
        </a>
    </div>

    {{-- Daftar Mahasiswa Bimbingan --}}
    @if(isset($mahasiswa) && count($mahasiswa) > 0)
        <div class="nb-card">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <div>
                    <span class="nb-eyebrow">Bimbingan</span>
                    <h3 class="nb-h3 mt-1">Mahasiswa Bimbingan</h3>
                </div>
                <a href="{{ route('pages.dosen.wali.khs') }}" class="nb-btn nb-btn-secondary nb-btn-sm">
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
    @endif
@endsection
