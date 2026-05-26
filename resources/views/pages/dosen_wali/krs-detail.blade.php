@extends('layouts.dosen')
@section('title', 'Detail KRS')
@section('page_title', 'Detail KRS')

@section('content')
<div class="nb-page-header">
    <div>
        <a href="{{ route('dosen.wali.krs-verifikasi') }}" class="nb-btn nb-btn-secondary nb-btn-sm mb-2">
            <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span> Kembali
        </a>
        <h1 class="mt-2">Detail KRS Mahasiswa</h1>
        <p>{{ $krs->semester }} {{ $krs->tahun_ajaran }}
           @if($isReadOnly) <span class="nb-badge nb-badge-stable ml-2">🔒 Read Only</span> @endif
        </p>
    </div>
</div>

<div class="nb-card mb-6">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div><p class="nb-label">Nama</p><p class="font-bold">{{ $krs->nama }}</p></div>
        <div><p class="nb-label">NIM</p><p class="font-bold">{{ $krs->nim }}</p></div>
        <div><p class="nb-label">Kelas</p><p class="font-bold">{{ $krs->kelas ?? '-' }}</p></div>
        <div><p class="nb-label">Status KRS</p>
            @php $badge = match(strtolower($krs->status)) {
                'disetujui'=>'nb-badge-success','ditolak'=>'nb-badge-danger',default=>'nb-badge-warning'
            }; @endphp
            <span class="nb-badge {{ $badge }}">{{ ucfirst($krs->status) }}</span>
        </div>
        <div><p class="nb-label">Total SKS</p><p class="font-bold">{{ $krs->total_sks }} SKS</p></div>
        @if($krs->catatan)
        <div class="col-span-2 md:col-span-3">
            <p class="nb-label">Catatan / Alasan</p>
            <p class="text-sm text-red-600 bg-red-50 rounded p-2">{{ $krs->catatan }}</p>
        </div>
        @endif
    </div>
</div>

<div class="nb-card-flat">
    <div class="nb-section-header"><h2>Mata Kuliah yang Diambil</h2>
        <span class="nb-badge nb-badge-primary">{{ count($detailMK) }} MK</span>
    </div>
    <table class="nb-table">
        <thead><tr>
            <th>No</th><th>Kode MK</th><th>Nama Mata Kuliah</th>
            <th class="text-center">SKS</th><th>Dosen Pengampu</th><th>Kelas</th>
        </tr></thead>
        <tbody>
        @forelse($detailMK as $i => $mk)
        <tr>
            <td class="text-muted font-bold">{{ $i+1 }}</td>
            <td><span class="nb-badge nb-badge-stable">{{ $mk->kode_mk }}</span></td>
            <td class="font-medium">{{ $mk->nama }}</td>
            <td class="text-center font-bold">{{ $mk->sks }}</td>
            <td class="text-sm text-muted">{{ $mk->nama_dosen ?? '-' }}</td>
            <td>{{ $mk->kelas_mk ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-8 text-muted">Tidak ada detail MK</td></tr>
        @endforelse
        </tbody>
        <tfoot>
            <tr style="background:var(--nb-surface-2);">
                <td colspan="3" class="text-right font-bold">Total SKS:</td>
                <td class="text-center font-extrabold text-primary">{{ $detailMK->sum('sks') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

@if(!$isReadOnly && strtolower($krs->status) === 'menunggu')
<div class="nb-card mt-6 flex gap-4 justify-end">
    <form method="POST" action="{{ route('dosen.wali.krs.approve', $krs->id) }}">
        @csrf @method('PATCH')
        <button type="submit" class="nb-btn nb-btn-primary"
                onclick="return confirm('Setujui KRS ini?')">
            <span class="material-symbols-outlined" style="font-size:16px;">check_circle</span> Setujui KRS
        </button>
    </form>
    <form method="POST" action="{{ route('dosen.wali.krs.reject', $krs->id) }}">
        @csrf
        <div class="flex gap-2 items-center">
            <input type="text" name="catatan" placeholder="Alasan penolakan..."
                   required class="border rounded-lg px-3 py-2 text-sm" style="min-width:250px;">
            <button type="submit" class="nb-btn nb-btn-sm" style="background:#dc2626;color:#fff;">
                <span class="material-symbols-outlined" style="font-size:16px;">cancel</span> Tolak
            </button>
        </div>
    </form>
</div>
@endif
@endsection
