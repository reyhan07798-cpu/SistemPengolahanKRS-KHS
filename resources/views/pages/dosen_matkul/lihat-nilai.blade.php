@extends('layouts.dosen')
@section('title', 'Lihat Nilai')
@section('page_title', 'Lihat Nilai')

@section('content')
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Nilai</span>
        <h1 class="mt-2">Nilai Mahasiswa</h1>
        <p>Rekap nilai rinci mahasiswa per mata kuliah yang Anda ampu.</p>
    </div>
</div>

{{-- Stats --}}
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

{{-- Filter AUTO-SUBMIT --}}
<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Filter</h3>
    </div>
    <form method="GET" action="{{ route('dosen.mk.lihat-nilai') }}" id="filterLihatForm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Tahun Ajaran --}}
            <div>
                <label class="nb-label">Tahun Ajaran</label>
                <select name="tahun_ajaran" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta }}" {{ $filterTahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Semester --}}
            <div>
                <label class="nb-label">Semester</label>
                <select name="semester_id" onchange="this.form.submit()">
                    <option value="">Semua Semester</option>
                    @foreach($allSem as $sem)
                        <option value="{{ $sem->id }}" {{ $filterSemesterId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->semester }} {{ $sem->tahun_ajaran }}
                            @if($sem->is_active) ★ @endif
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Mata Kuliah --}}
            <div>
                <label class="nb-label">Mata Kuliah</label>
                <select name="mata_kuliah_id" onchange="onMKChangeLihat(this)">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($daftarMK as $mk)
                        <option value="{{ $mk['id'] }}" {{ $filterMK == $mk['id'] ? 'selected' : '' }}>
                            {{ $mk['kode_mk'] }} – {{ $mk['nama'] }} ({{ $mk['kelas'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Kelas --}}
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" id="selectKelasLihat" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasDariMK as $kls)
                        <option value="{{ $kls }}" {{ $filterKelas == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                    @endforeach
                </select>
            </div>
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
                    <th class="text-center" title="Nilai Tugas">Tugas</th>
                    <th class="text-center" title="Nilai Praktikum">Prak.</th>
                    <th class="text-center" title="Nilai UTS">UTS</th>
                    <th class="text-center" title="Nilai UAS">UAS</th>
                    <th class="text-center" title="Nilai Kehadiran">Had.</th>
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
                    $gradeBadge = match(true) {
                        in_array($grade,['A','A-'])           => 'nb-badge-success',
                        in_array($grade,['B+','B','B-'])      => 'nb-badge-primary',
                        in_array($grade,['C+','C'])           => 'nb-badge-warning',
                        $grade === 'D'                        => 'nb-badge-warning',
                        default                               => 'nb-badge-danger',
                    };
                @endphp
                <tr>
                    <td class="font-bold text-muted">{{ $mhs['no'] }}</td>
                    <td>
                        <div class="font-bold text-ink text-sm">{{ $mhs['nama'] }}</div>
                        <div class="text-xs text-muted">{{ $mhs['nim'] }}</div>
                    </td>
                    <td><span class="nb-badge nb-badge-stable text-xs">{{ $mhs['kode_mk'] }}</span></td>
                    <td class="text-center text-sm">{{ $mhs['nilai_tugas']     !== null ? number_format($mhs['nilai_tugas'],1)     : '—' }}</td>
                    <td class="text-center text-sm">{{ $mhs['nilai_praktikum'] !== null ? number_format($mhs['nilai_praktikum'],1) : '—' }}</td>
                    <td class="text-center text-sm">{{ $mhs['nilai_uts']       !== null ? number_format($mhs['nilai_uts'],1)       : '—' }}</td>
                    <td class="text-center text-sm">{{ $mhs['nilai_uas']       !== null ? number_format($mhs['nilai_uas'],1)       : '—' }}</td>
                    <td class="text-center text-sm">{{ $mhs['nilai_kehadiran'] !== null ? number_format($mhs['nilai_kehadiran'],1) : '—' }}</td>
                    <td class="text-center font-extrabold text-primary text-lg" style="font-family:var(--font-heading);">
                        {{ $nilaiAkhir > 0 ? number_format($nilaiAkhir, 1) : '—' }}
                    </td>
                    <td class="text-center">
                        <span class="nb-badge {{ $gradeBadge }}">{{ $grade }}</span>
                    </td>
                    <td class="text-center">
                        <span class="font-extrabold text-lg {{ $mutu >= 3.5 ? 'text-accent' : ($mutu >= 2.5 ? 'text-primary' : 'text-muted') }}"
                              style="font-family:var(--font-heading);">
                            {{ $mutu > 0 ? number_format($mutu, 2) : '—' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-12">
                        <span class="material-symbols-outlined text-muted" style="font-size:48px;">search_off</span>
                        <p class="mt-2 text-muted font-medium">Belum ada data nilai. Pilih mata kuliah atau input nilai terlebih dahulu.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const URL_KELAS_LIHAT = '{{ route("dosen.mk.kelas-by-mk") }}';

function onMKChangeLihat(sel) {
    const mkId = sel.value;
    const kelasSelect = document.getElementById('selectKelasLihat');
    if (!mkId) {
        kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
        document.getElementById('filterLihatForm').submit();
        return;
    }
    fetch(URL_KELAS_LIHAT + '?mata_kuliah_id=' + mkId)
        .then(r => r.json())
        .then(data => {
            kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
            (data.kelas || []).forEach(k => {
                const opt = document.createElement('option');
                opt.value = k; opt.textContent = k;
                kelasSelect.appendChild(opt);
            });
            if ((data.kelas || []).length === 1) kelasSelect.value = data.kelas[0];
            document.getElementById('filterLihatForm').submit();
        });
}
</script>
@endpush
