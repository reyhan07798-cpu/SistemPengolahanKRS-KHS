@extends('layouts.dosen')

@section('title', 'Beranda Dosen Matkul')
@section('page_title', 'Beranda')

@section('content')
<x-hero-header-dosen 
    eyebrow="Dosen Mata Kuliah"
    description="Kelola nilai dan pantau mahasiswa pada mata kuliah yang Anda ampu."
    :buttons="[
        ['label' => 'Input Nilai', 'route' => route('dosen.mk.input-nilai'), 'icon' => 'edit_note'],
        ['label' => 'Lihat Nilai', 'route' => route('dosen.mk.lihat-nilai'), 'icon' => 'analytics', 'variant' => 'nb-btn-secondary']
    ]" 
/>

<x-stat-bento 
    :stats="$stats"
    :config="[
        'mata_kuliah_diampu' => ['color' => 'nb-stat--info', 'icon' => 'menu_book', 'label' => 'Mata Kuliah Diampu'],
        'total_mahasiswa' => ['color' => 'nb-stat--primary', 'icon' => 'groups', 'label' => 'Total Mahasiswa'],
        'nilai_diinput' => ['color' => 'nb-stat--accent', 'icon' => 'edit_note', 'label' => 'Nilai Diinput'],
        'belum_dinilai' => ['color' => 'nb-stat--warning', 'icon' => 'pending_actions', 'label' => 'Belum Dinilai']
    ]"
/>

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
                    <div class="nb-avatar-sm" style="cursor: default; background-color: #DBEAFE; color: #1E40AF;">                            <span class="font-extrabold text-xs" style="font-family: var(--font-heading);">{{ $initials }}</span>
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
