@extends('layouts.dosen')
@section('title', 'Beranda Dosen Wali')
@section('page_title', 'Beranda')

@section('content')
<x-hero-header-dosen
    eyebrow="Dosen Wali"
    description="Pantau dan kelola KRS serta hasil studi mahasiswa bimbingan Anda."
    :buttons="[
        ['label' => 'Verifikasi KRS', 'route' => route('dosen.wali.krs-verifikasi'), 'icon' => 'fact_check'],
        ['label' => 'Lihat KHS',      'route' => route('dosen.wali.khs'),             'icon' => 'analytics', 'variant' => 'nb-btn-secondary']
    ]"
/>

<x-stat-bento
    :stats="$stats"
    :config="[
        'mahasiswa_bimbingan' => ['color' => 'nb-stat--info',    'icon' => 'groups',          'label' => 'Mahasiswa Bimbingan'],
        'krs_menunggu'        => ['color' => 'nb-stat--warning', 'icon' => 'pending_actions',  'label' => 'KRS Menunggu'],
        'krs_disetujui'       => ['color' => 'nb-stat--accent',  'icon' => 'check_circle',     'label' => 'KRS Disetujui'],
        'krs_ditolak'         => ['color' => 'nb-stat--danger',  'icon' => 'cancel',           'label' => 'KRS Ditolak'],
    ]"
/>

<div class="nb-card">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div>
            <span class="nb-eyebrow">Bimbingan</span>
            <h3 class="nb-h3 mt-1">Mahasiswa Bimbingan</h3>
        </div>
        <a href="{{ route('dosen.wali.krs-verifikasi') }}" class="nb-btn nb-btn-primary nb-btn-sm">
            <span class="material-symbols-outlined" style="font-size:16px;">fact_check</span> Verifikasi KRS
        </a>
    </div>

    @if(count($mahasiswaList) > 0)
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM / Nama</th>
                    <th>Kelas</th>
                    <th class="text-center">IPK</th>
                    <th class="text-center">Status KRS</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($mahasiswaList as $i => $m)
            @php
                $krsBadge = match(strtolower($m['status_krs'])) {
                    'disetujui'         => 'nb-badge-success',
                    'ditolak'           => 'nb-badge-danger',
                    'menunggu'          => 'nb-badge-warning',
                    'belum mengajukan'  => 'nb-badge-stable',
                    default             => 'nb-badge-stable',
                };
                $ipkClass = $m['ipk'] >= 3.5 ? 'text-accent' : ($m['ipk'] >= 2.5 ? 'text-primary' : 'text-muted');
            @endphp
            <tr>
                <td class="font-bold text-muted">{{ $i+1 }}</td>
                <td>
                    <div class="font-bold text-sm text-ink">{{ $m['nama'] }}</div>
                    <div class="text-xs text-muted">{{ $m['nim'] }}</div>
                </td>
                <td>{{ $m['kelas'] }}</td>
                <td class="text-center">
                    <span class="font-extrabold text-lg {{ $ipkClass }}" style="font-family:var(--font-heading);">
                        {{ number_format($m['ipk'],2) }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="nb-badge {{ $krsBadge }}">{{ $m['status_krs'] }}</span>
                </td>
                <td class="text-center">
                    <a href="{{ route('dosen.wali.khs') }}?kelas={{ $m['kelas'] }}"
                       class="nb-btn nb-btn-secondary nb-btn-sm" title="Lihat KHS">
                        <span class="material-symbols-outlined" style="font-size:14px;">analytics</span>
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-10 text-muted">
        <span class="material-symbols-outlined" style="font-size:56px;">groups</span>
        <p class="mt-3 font-medium">Belum ada mahasiswa bimbingan.</p>
        <p class="text-sm mt-1">Hubungi admin untuk menetapkan mahasiswa bimbingan.</p>
    </div>
    @endif
</div>
@endsection
