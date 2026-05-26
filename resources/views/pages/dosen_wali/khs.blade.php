@extends('layouts.dosen')
@section('title', 'KHS Mahasiswa Bimbingan')
@section('page_title', 'KHS Mahasiswa')

@section('content')
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Akademik</span>
        <h1 class="mt-2">KHS Mahasiswa Bimbingan</h1>
        <p>Pantau hasil studi mahasiswa bimbingan Anda.
           @if($isReadOnly) <span class="nb-badge nb-badge-stable ml-2">🔒 Read Only</span> @endif
        </p>
    </div>
</div>

<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="nb-stat nb-stat--info nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">groups</span></div><p class="nb-stat-label">Total Mahasiswa</p></div>
        <div class="nb-stat-value">{{ $totalMahasiswa }}</div>
    </div>
    <div class="nb-stat nb-stat--accent nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">analytics</span></div><p class="nb-stat-label">Rata-rata IPK</p></div>
        <div class="nb-stat-value">{{ number_format($rataIpk,2) }}</div>
    </div>
    <div class="nb-stat nb-stat--success nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">star</span></div><p class="nb-stat-label">IPK ≥ 3.5</p></div>
        <div class="nb-stat-value">{{ $ipkTinggi }}</div>
    </div>
</div>

{{-- Filter AUTO-SUBMIT --}}
<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Filter</h3>
    </div>
    <form method="GET" action="{{ route('dosen.wali.khs') }}">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <label class="nb-label">Semester</label>
                <select name="semester_id" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach($allSem as $sem)
                        <option value="{{ $sem->id }}" {{ $filterSemesterId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->semester }} {{ $sem->tahun_ajaran }}
                            @if($sem->is_active) ★ @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kls)
                        <option value="{{ $kls }}" {{ $filterKelas==$kls ? 'selected' : '' }}>{{ $kls }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                @if($isReadOnly)
                    <span class="nb-badge nb-badge-stable w-full text-center py-2">🔒 Semester Tidak Aktif</span>
                @else
                    <span class="nb-badge nb-badge-success w-full text-center py-2">✓ Semester Aktif</span>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="nb-card-flat">
    <div class="nb-section-header">
        <h2>Rekap Nilai Mahasiswa</h2>
        <span class="nb-badge nb-badge-primary">{{ count($mahasiswaList) }} mahasiswa</span>
    </div>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead><tr>
                <th>Rank</th><th>NIM / Nama</th>
                <th class="text-center">MK Lulus</th><th class="text-center">IP Semester</th>
                <th class="text-center">IPK Kumulatif</th>
            </tr></thead>
            <tbody>
            @forelse($mahasiswaList as $mhs)
            @php
                $ipkBadge = $mhs['ipk'] >= 3.5 ? 'nb-badge-success' : ($mhs['ipk'] >= 2.5 ? 'nb-badge-primary' : 'nb-badge-warning');
            @endphp
            <tr>
                <td>
                    @if($mhs['ranking'] <= 3)
                        <span class="nb-badge nb-badge-primary" style="font-family:var(--font-heading);">#{{ $mhs['ranking'] }}</span>
                    @else
                        <span class="text-muted">{{ $mhs['ranking'] }}</span>
                    @endif
                </td>
                <td>
                    <div class="font-bold text-ink text-sm">{{ $mhs['nama'] }}</div>
                    <div class="text-xs text-muted">{{ $mhs['nim'] }}</div>
                </td>
                <td class="text-center font-bold">{{ $mhs['mk_lulus'] }}</td>
                <td class="text-center font-extrabold text-primary" style="font-family:var(--font-heading);">
                    {{ number_format($mhs['ip'],2) }}
                </td>
                <td class="text-center">
                    <span class="nb-badge {{ $ipkBadge }} font-extrabold" style="font-family:var(--font-heading);font-size:14px;">
                        {{ number_format($mhs['ipk'],2) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-muted">
                <span class="material-symbols-outlined" style="font-size:48px;">school</span>
                <p class="mt-2">Belum ada data nilai mahasiswa bimbingan.</p>
            </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
