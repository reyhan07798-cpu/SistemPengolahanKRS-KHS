@extends('layouts.dosen')

@section('title', 'Input Nilai')
@section('page_title', 'Input Nilai')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Penilaian</span>
            <h1 class="mt-2">Input Nilai</h1>
            <p>Input nilai mahasiswa langsung di tabel — klik sel untuk mengisi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- ═══════════════════════ FILTER ═══════════════════════ --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter & Pilih Mata Kuliah</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen_matkul.input-nilai') }}" id="filterForm">
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
                    <select name="mata_kuliah_id" id="selectMK">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($mataKuliahList as $mk)
                            <option value="{{ $mk['id'] }}" {{ $filterMK == $mk['id'] ? 'selected' : '' }}>
                                {{ $mk['kode'] }} - {{ $mk['nama'] }} ({{ $mk['kelas'] }})
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
                    <span class="material-symbols-outlined" style="font-size:18px;">search</span> Tampilkan
                </button>
            </div>
        </form>
    </div>

    @if($mkAktif)
    {{-- ═════════════════ BOBOT PENILAIAN (VARIABEL) ═════════════════ --}}
    <div class="nb-card mb-6" id="bobotCard">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">tune</span>
            <h3 class="nb-h3">Bobot Penilaian <span class="nb-badge nb-badge-stable ml-2" style="font-size:12px;">Variabel — bisa diubah</span></h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4" id="bobotForm">
            @php
                $bobotDefault = $bobotAktif ?? ['tugas'=>20,'praktikum'=>15,'uts'=>30,'uas'=>30,'kehadiran'=>5];
            @endphp
            @foreach([
                ['key'=>'tugas',     'label'=>'Tugas',     'color'=>'nb-badge-stable'],
                ['key'=>'praktikum', 'label'=>'Praktikum', 'color'=>'nb-badge-stable'],
                ['key'=>'uts',       'label'=>'UTS',       'color'=>'nb-badge-primary'],
                ['key'=>'uas',       'label'=>'UAS',       'color'=>'nb-badge-primary'],
                ['key'=>'kehadiran', 'label'=>'Kehadiran', 'color'=>'nb-badge-success'],
            ] as $b)
            <div>
                <label class="nb-label">{{ $b['label'] }} (%)</label>
                <input type="number" id="bobot_{{ $b['key'] }}" name="bobot_{{ $b['key'] }}"
                       value="{{ $bobotDefault[$b['key']] }}" min="0" max="100" step="1"
                       class="text-center bobot-input"
                       onchange="updateBobotTotal(); recalculateAll();">
            </div>
            @endforeach
        </div>
        <div class="mt-3 flex items-center gap-3">
            <span class="nb-label" style="margin-bottom:0;">Total:</span>
            <span id="bobotTotal" class="font-extrabold text-lg text-primary" style="font-family:var(--font-heading);">100%</span>
            <span id="bobotWarning" class="text-xs text-red-500 hidden">⚠ Total harus 100%</span>
        </div>
    </div>

    {{-- ════════════════════ TABEL INPUT NILAI ════════════════════ --}}
    @if(count($mahasiswaList) > 0)
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">
                    {{ $mkAktif->kode_mk }} · {{ $mkAktif->kelas ?? 'A' }}
                </span>
                <h2 class="mt-1">{{ $mkAktif->nama }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="nb-badge nb-badge-primary">{{ count($mahasiswaList) }} Mahasiswa</span>
                <button type="button" onclick="simpanSemua()" class="nb-btn nb-btn-primary nb-btn-sm" id="btnSimpanSemua">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan Semua
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table" id="tabelNilai">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Mahasiswa</th>
                        <th class="text-center" style="min-width:90px;">
                            Tugas<br><small class="text-muted font-normal" id="hdr_tugas">(20%)</small>
                        </th>
                        <th class="text-center" style="min-width:90px;">
                            Praktikum<br><small class="text-muted font-normal" id="hdr_praktikum">(15%)</small>
                        </th>
                        <th class="text-center" style="min-width:90px;">
                            UTS<br><small class="text-muted font-normal" id="hdr_uts">(30%)</small>
                        </th>
                        <th class="text-center" style="min-width:90px;">
                            UAS<br><small class="text-muted font-normal" id="hdr_uas">(30%)</small>
                        </th>
                        <th class="text-center" style="min-width:90px;">
                            Kehadiran<br><small class="text-muted font-normal" id="hdr_kehadiran">(5%)</small>
                        </th>
                        <th class="text-center" style="min-width:90px;">Nilai Akhir</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($mahasiswaList as $idx => $mhs)
                    <tr id="row_{{ $mhs['id'] }}">
                        <td class="text-muted font-bold">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-bold text-ink text-sm">{{ $mhs['nama'] }}</div>
                        </td>
                        {{-- Kolom nilai komponen — langsung editable --}}
                        @foreach(['tugas','praktikum','uts','uas','kehadiran'] as $komp)
                        <td class="text-center p-1">
                            <input type="number"
                                   id="{{ $komp }}_{{ $mhs['id'] }}"
                                   data-mhs="{{ $mhs['id'] }}"
                                   data-komp="{{ $komp }}"
                                   value="{{ $mhs['nilai_'.$komp] ?? '' }}"
                                   min="0" max="100" step="0.1"
                                   placeholder="—"
                                   class="nilai-input text-center"
                                   style="width:75px;padding:6px 4px;border:1.5px solid var(--nb-border);border-radius:6px;font-size:13px;background:var(--nb-surface);"
                                   onchange="hitungBaris({{ $mhs['id'] }})"
                                   oninput="hitungBaris({{ $mhs['id'] }})">
                        </td>
                        @endforeach
                        <td class="text-center">
                            <span id="akhir_{{ $mhs['id'] }}" class="font-extrabold text-primary" style="font-family:var(--font-heading);">
                                {{ $mhs['nilai_akhir'] !== null ? number_format($mhs['nilai_akhir'],1) : '—' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span id="grade_{{ $mhs['id'] }}" class="nb-badge {{ $mhs['grade'] ? 'nb-badge-success' : 'nb-badge-stable' }}">
                                {{ $mhs['grade'] ?? '—' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button"
                                    onclick="simpanSatu({{ $mhs['id'] }})"
                                    id="btn_{{ $mhs['id'] }}"
                                    class="nb-btn nb-btn-primary nb-btn-sm">
                                <span class="material-symbols-outlined" style="font-size:14px;">save</span>
                            </button>
                            <span id="status_{{ $mhs['id'] }}" class="text-xs ml-1" style="display:none;color:green;">✓</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">group_off</span>
        <h3 class="nb-h3 mt-4">Belum Ada Mahasiswa</h3>
        <p class="text-muted mt-2">Belum ada mahasiswa yang mengambil mata kuliah ini atau KRS belum disetujui.</p>
    </div>
    @endif

    @else
    <div class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">edit_note</span>
        <h3 class="nb-h3 mt-4">Pilih Mata Kuliah</h3>
        <p class="text-muted mt-2">Pilih mata kuliah di filter atas untuk mulai input nilai.</p>
    </div>
    @endif
@endsection

@push('scripts')
<script>
const MK_ID  = {{ $filterMK ?: 'null' }};
const CSRF   = '{{ csrf_token() }}';
const URL    = '{{ route("pages.dosen_matkul.simpan-nilai") }}';

function getBobotValues() {
    return {
        tugas:     parseFloat(document.getElementById('bobot_tugas')?.value)     || 20,
        praktikum: parseFloat(document.getElementById('bobot_praktikum')?.value) || 15,
        uts:       parseFloat(document.getElementById('bobot_uts')?.value)        || 30,
        uas:       parseFloat(document.getElementById('bobot_uas')?.value)        || 30,
        kehadiran: parseFloat(document.getElementById('bobot_kehadiran')?.value)  || 5,
    };
}

function updateBobotTotal() {
    const b = getBobotValues();
    const total = b.tugas + b.praktikum + b.uts + b.uas + b.kehadiran;
    const el = document.getElementById('bobotTotal');
    const warn = document.getElementById('bobotWarning');
    if (el) el.textContent = total + '%';
    if (warn) warn.classList.toggle('hidden', Math.abs(total - 100) < 0.01);
    // Update header labels
    ['tugas','praktikum','uts','uas','kehadiran'].forEach(k => {
        const hdr = document.getElementById('hdr_' + k);
        if (hdr) hdr.textContent = '(' + b[k] + '%)';
    });
}

function gradeFromNilai(n) {
    if (n >= 85) return {grade:'A',  badge:'nb-badge-success'};
    if (n >= 80) return {grade:'A-', badge:'nb-badge-success'};
    if (n >= 75) return {grade:'B+', badge:'nb-badge-primary'};
    if (n >= 70) return {grade:'B',  badge:'nb-badge-primary'};
    if (n >= 65) return {grade:'B-', badge:'nb-badge-primary'};
    if (n >= 60) return {grade:'C+', badge:'nb-badge-warning'};
    if (n >= 55) return {grade:'C',  badge:'nb-badge-warning'};
    if (n >= 40) return {grade:'D',  badge:'nb-badge-warning'};
    return {grade:'E', badge:'nb-badge-danger'};
}

function hitungBaris(mhsId) {
    const b = getBobotValues();
    const get = (k) => parseFloat(document.getElementById(k + '_' + mhsId)?.value) || 0;
    const akhir = (get('tugas') * b.tugas + get('praktikum') * b.praktikum +
                   get('uts')   * b.uts   + get('uas')       * b.uas +
                   get('kehadiran') * b.kehadiran) / 100;

    const akhirEl = document.getElementById('akhir_' + mhsId);
    const gradeEl = document.getElementById('grade_' + mhsId);
    if (akhirEl) akhirEl.textContent = akhir > 0 ? akhir.toFixed(1) : '—';
    if (gradeEl && akhir > 0) {
        const g = gradeFromNilai(akhir);
        gradeEl.textContent = g.grade;
        gradeEl.className = 'nb-badge ' + g.badge;
    }
}

function recalculateAll() {
    document.querySelectorAll('[id^="akhir_"]').forEach(el => {
        const mhsId = el.id.replace('akhir_', '');
        hitungBaris(mhsId);
    });
    updateBobotTotal();
}

async function simpanSatu(mhsId) {
    const b = getBobotValues();
    const total = b.tugas + b.praktikum + b.uts + b.uas + b.kehadiran;
    if (Math.abs(total - 100) > 0.01) {
        alert('Total bobot harus 100%. Sekarang: ' + total + '%');
        return;
    }

    const btn = document.getElementById('btn_' + mhsId);
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">hourglass_empty</span>';

    const body = new FormData();
    body.append('_token', CSRF);
    body.append('mata_kuliah_id', MK_ID);
    body.append('mahasiswa_id', mhsId);
    body.append('bobot_tugas', b.tugas);
    body.append('bobot_praktikum', b.praktikum);
    body.append('bobot_uts', b.uts);
    body.append('bobot_uas', b.uas);
    body.append('bobot_kehadiran', b.kehadiran);

    ['tugas','praktikum','uts','uas','kehadiran'].forEach(k => {
        const v = document.getElementById(k + '_' + mhsId)?.value;
        body.append('nilai_' + k + '[' + mhsId + ']', v || 0);
    });

    try {
        const res = await fetch(URL, { method: 'POST', body });
        const data = await res.json();
        if (data.success) {
            const statusEl = document.getElementById('status_' + mhsId);
            if (statusEl) { statusEl.style.display = 'inline'; setTimeout(() => statusEl.style.display='none', 2000); }
            // Update display
            const akhirEl = document.getElementById('akhir_' + mhsId);
            const gradeEl = document.getElementById('grade_' + mhsId);
            if (akhirEl) akhirEl.textContent = parseFloat(data.nilai_akhir).toFixed(1);
            if (gradeEl) {
                const g = gradeFromNilai(data.nilai_akhir);
                gradeEl.textContent = data.grade;
                gradeEl.className = 'nb-badge ' + g.badge;
            }
        } else {
            alert(data.message);
        }
    } catch(e) {
        alert('Gagal menyimpan. Coba lagi.');
    }

    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">save</span>';
}

async function simpanSemua() {
    const rows = document.querySelectorAll('[id^="row_"]');
    const btn = document.getElementById('btnSimpanSemua');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    for (const row of rows) {
        const mhsId = row.id.replace('row_', '');
        await simpanSatu(mhsId);
    }
    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan Semua';
}

// Init
updateBobotTotal();
</script>
@endpush