@extends('layouts.mahasiswa')

@section('page_title', 'Beranda')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dashboard</span>
            <h1 class="mt-2">Selamat datang, {{ $data['nama'] }}</h1>
            <p>Ringkasan akademik dan progres studi Anda semester ini.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('pages.mahasiswa.ambil-krs') }}" class="nb-btn nb-btn-secondary">
                <span class="material-symbols-outlined" style="font-size:20px;">assignment</span>
                Ambil KRS
            </a>
            <a href="{{ route('pages.mahasiswa.lihat-khs') }}" class="nb-btn nb-btn-primary">
                <span class="material-symbols-outlined" style="font-size:20px;">grade</span>
                Lihat KHS
            </a>
        </div>
    </div>

    {{-- Bento Grid Statistik --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">event</span>
                </div>
                <p class="nb-stat-label">Semester Aktif</p>
            </div>
            <div class="nb-stat-value">{{ $data['semester_aktif'] }}</div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">assignment</span>
                </div>
                <p class="nb-stat-label">Total SKS</p>
            </div>
            <div class="nb-stat-value">{{ $data['total_sks'] }}</div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <p class="nb-stat-label">IPK</p>
            </div>
            <div class="nb-stat-value">{{ number_format($data['ipk'], 2) }}</div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">workspace_premium</span>
                </div>
                <p class="nb-stat-label">MK Lulus</p>
            </div>
            <div class="nb-stat-value">{{ $data['mata_kuliah_lulus'] }}</div>
        </div>
    </div>

    {{-- Informasi Mahasiswa --}}
    <div class="nb-card mb-8">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
            <div>
                <span class="nb-eyebrow">Profil</span>
                <h3 class="nb-h3 mt-1">Informasi Mahasiswa</h3>
            </div>
            <a href="{{ route('pages.mahasiswa.profil') }}" class="nb-btn nb-btn-secondary nb-btn-sm">
                <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                Kelola Profil
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <p class="nb-label">NIM</p>
                <p class="text-base font-bold text-ink mb-4">{{ $data['nim'] }}</p>

                <p class="nb-label">Nama</p>
                <p class="text-base font-bold text-ink mb-4">{{ $data['nama'] }}</p>

                <p class="nb-label">Program Studi</p>
                <p class="text-base font-bold text-ink">{{ $data['prodi'] }}</p>
            </div>

            <div>
                <p class="nb-label">Angkatan</p>
                <p class="text-base font-bold text-ink mb-4">{{ $data['angkatan'] }}</p>

                <p class="nb-label">Email</p>
                <p class="text-base font-bold text-ink mb-4 break-all">{{ $data['email'] }}</p>

                <p class="nb-label">Semester Aktif</p>
                <p class="text-base font-bold text-ink">Semester {{ $data['semester_aktif'] }}</p>
            </div>
        </div>
    </div>

    {{-- Nilai Terbaru --}}
    <div class="nb-card-flat mb-8">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Akademik</span>
                <h2 class="mt-1">Nilai Terbaru</h2>
            </div>
            <a href="{{ route('pages.mahasiswa.lihat-khs') }}" class="nb-btn nb-btn-secondary nb-btn-sm">
                Lihat Semua
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>

        @if(count($data['nilai_terbaru']) > 0)
            <div class="overflow-x-auto">
                <table class="nb-table">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['nilai_terbaru'] as $nilai)
                            <x-grade-row
                                :matkul="$nilai['matkul']"
                                :sks="$nilai['sks']"
                                :nilai="$nilai['nilai']"
                                :bobot="$nilai['bobot']" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-muted" style="font-size:48px;">grade</span>
                <p class="mt-2 text-muted font-medium">Belum ada data nilai</p>
            </div>
        @endif
    </div>

    {{-- KRS Aktif --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Semester Berjalan</span>
                <h2 class="mt-1">KRS Aktif</h2>
            </div>
            <a href="{{ route('pages.mahasiswa.ambil-krs') }}" class="nb-btn nb-btn-secondary nb-btn-sm">
                Kelola KRS
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_forward</span>
            </a>
        </div>

        @if(count($data['krs_aktif']) > 0)
            <div class="overflow-x-auto">
                <table class="nb-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['krs_aktif'] as $krs)
                            <x-krs-table-row
                                :kode="$krs['kode']"
                                :matkul="$krs['matkul']"
                                :sks="$krs['sks']"
                                :status="$krs['status']" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-muted" style="font-size:48px;">assignment</span>
                <p class="mt-2 text-muted font-medium">Belum ada KRS aktif</p>
            </div>
        @endif
    </div>
@endsection
