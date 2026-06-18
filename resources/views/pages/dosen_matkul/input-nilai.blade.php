@extends('layouts.dosen')

@section('title', 'Input Nilai')
@section('page_title', 'Input Nilai')

@section('content')
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Penilaian</span>
        <h1 class="mt-2">Input Nilai</h1>
        <p>Pilih mata kuliah — kelas otomatis terfilter. Isi nilai langsung di tabel.</p>
    </div>
</div>

@if(session('success'))
    <div class="nb-alert nb-alert-success mb-6">
        {{ session('success') }}
    </div>
@endif

@if($isReadOnly && $mkAktif)
    <div class="nb-alert nb-alert-warning mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">lock</span>
        <strong>Semester tidak aktif.</strong> Nilai hanya bisa dilihat.
    </div>
@endif

{{-- Filter --}}
<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Pilih Mata Kuliah</h3>
    </div>

    <form method="GET" action="{{ route('dosen.mk.input-nilai') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="nb-label">Tahun Ajaran</label>
                <select name="tahun_ajaran" id="selectTahunAjaran" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta }}" {{ $filterTahunAjaran == $ta ? 'selected' : '' }}>
                            {{ $ta }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="nb-label">Semester</label>
                <select name="semester_id" id="selectSemester" onchange="this.form.submit()">
                    <option value="">Semua Semester</option>
                    @foreach($allSem as $sem)
                        <option value="{{ $sem->id }}" {{ $filterSemesterId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->semester }} {{ $sem->tahun_ajaran }}
                            @if($sem->is_active) ★ Aktif @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="nb-label">Mata Kuliah <span class="text-red-500">*</span></label>
                <select name="kode_mk" id="selectMK" onchange="onMKChange(this)">
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($mataKuliahList as $mk)
                        <option value="{{ $mk['kode'] }}" {{ $filterKodeMK == $mk['kode'] ? 'selected' : '' }}>
                            {{ $mk['kode'] }} – {{ $mk['nama'] }}
                            @if(!$mk['is_active']) 🔒 @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" id="selectKelas" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasDariMK as $kls)
                        <option value="{{ $kls }}" {{ $filterKelas == $kls ? 'selected' : '' }}>
                            {{ $kls }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($mkAktif)
            <div class="mt-3">
                @if(!$isReadOnly)
                    <span class="nb-badge nb-badge-success py-2 px-4">
                        ✓ Semester Aktif — dapat input
                    </span>
                @else
                    <span class="nb-badge nb-badge-stable py-2 px-4">
                        🔒 Semester Tidak Aktif — Read Only
                    </span>
                @endif
            </div>
        @endif
    </form>
</div>

@if($mkAktif && count($mahasiswaList) > 0)

    @if(!$isReadOnly)
        <div class="nb-card mb-6" id="bobotCard">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-primary">tune</span>
                <h3 class="nb-h3">
                    Bobot Penilaian
                    <span class="nb-badge nb-badge-stable ml-2" style="font-size:11px;">
                        Total harus 100%
                    </span>
                </h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach([
                    ['key' => 'tugas', 'label' => 'Tugas (%)'],
                    ['key' => 'praktikum', 'label' => 'Praktikum (%)'],
                    ['key' => 'uts', 'label' => 'UTS (%)'],
                    ['key' => 'uas', 'label' => 'UAS (%)'],
                    ['key' => 'kehadiran', 'label' => 'Kehadiran (%)'],
                ] as $b)
                    @php
                        $defaultBobot = [
                            'tugas' => 20,
                            'praktikum' => 15,
                            'uts' => 30,
                            'uas' => 30,
                            'kehadiran' => 5,
                        ];

                        $val = $mahasiswaList[0]['bobot_'.$b['key']] ?? $defaultBobot[$b['key']];
                    @endphp

                    <div>
                        <label class="nb-label">{{ $b['label'] }}</label>
                        <input type="number"
                               id="bobot_{{ $b['key'] }}"
                               value="{{ $val }}"
                               min="0"
                               max="100"
                               step="1"
                               class="text-center bobot-input w-full"
                               oninput="updateBobotTotal(); recalcAll()">
                    </div>
                @endforeach
            </div>

            <div class="mt-3 flex items-center gap-3">
                <span class="nb-label mb-0">Total:</span>
                <span id="bobotTotal" class="font-extrabold text-lg text-primary">100%</span>
                <span id="bobotWarn" class="text-xs text-red-500 hidden">
                    ⚠ Total harus 100%
                </span>
            </div>
        </div>
    @endif

    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">
                    {{ $mkAktif->kode_mk }} · {{ $mkAktif->kelas ?? '-' }}
                </span>
                <h2 class="mt-1">{{ $mkAktif->nama }}</h2>
            </div>

            <div class="flex items-center gap-3">
                <span class="nb-badge nb-badge-primary">
                    {{ count($mahasiswaList) }} mahasiswa
                </span>

                @if(!$isReadOnly)
                    <button onclick="finalisasi()" id="btnFinalisasi" class="nb-btn nb-btn-primary nb-btn-sm">
                        <span class="material-symbols-outlined" style="font-size:16px;">verified</span>
                        Finalisasi
                    </button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table" id="tabelNilai">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th>NIM / Nama</th>
                        <th class="text-center" style="min-width:80px">
                            Tugas<br>
                            <small id="hdr_tugas" class="text-muted font-normal">
                                ({{ $mahasiswaList[0]['bobot_tugas'] ?? 20 }}%)
                            </small>
                        </th>
                        <th class="text-center" style="min-width:80px">
                            Praktikum<br>
                            <small id="hdr_praktikum" class="text-muted font-normal">
                                ({{ $mahasiswaList[0]['bobot_praktikum'] ?? 15 }}%)
                            </small>
                        </th>
                        <th class="text-center" style="min-width:80px">
                            UTS<br>
                            <small id="hdr_uts" class="text-muted font-normal">
                                ({{ $mahasiswaList[0]['bobot_uts'] ?? 30 }}%)
                            </small>
                        </th>
                        <th class="text-center" style="min-width:80px">
                            UAS<br>
                            <small id="hdr_uas" class="text-muted font-normal">
                                ({{ $mahasiswaList[0]['bobot_uas'] ?? 30 }}%)
                            </small>
                        </th>
                        <th class="text-center" style="min-width:80px">
                            Kehadiran<br>
                            <small id="hdr_kehadiran" class="text-muted font-normal">
                                ({{ $mahasiswaList[0]['bobot_kehadiran'] ?? 5 }}%)
                            </small>
                        </th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($mahasiswaList as $idx => $mhs)
                        <tr id="row_{{ $mhs['id'] }}">
                            <td class="text-muted font-bold">
                                {{ $idx + 1 }}
                            </td>

                            <td>
                                <div class="font-bold text-ink text-sm">
                                    {{ $mhs['nama'] }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $mhs['nim'] }}
                                </div>
                            </td>

                            @foreach(['tugas','praktikum','uts','uas','kehadiran'] as $k)
                                <td class="text-center p-1">
                                    @if(!$isReadOnly)
                                        <input type="number"
                                               id="{{ $k }}_{{ $mhs['id'] }}"
                                               value="{{ $mhs['nilai_'.$k] ?? '' }}"
                                               min="0"
                                               max="100"
                                               step="0.5"
                                               placeholder="—"
                                               data-mhs="{{ $mhs['id'] }}"
                                               oninput="onNilaiInput({{ $mhs['id'] }})"
                                               style="width:68px;padding:5px 3px;border:1.5px solid var(--nb-border);border-radius:6px;font-size:12px;text-align:center;background:var(--nb-surface);">
                                    @else
                                        <span class="text-sm font-medium">
                                            {{ $mhs['nilai_'.$k] ?? '—' }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="text-center">
                                <span id="akhir_{{ $mhs['id'] }}"
                                      class="font-extrabold text-primary"
                                      style="font-family:var(--font-heading);">
                                    {{ $mhs['nilai_akhir'] !== null ? number_format($mhs['nilai_akhir'], 1) : '—' }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span id="grade_{{ $mhs['id'] }}"
                                      class="nb-badge {{ $mhs['grade'] ? 'nb-badge-success' : 'nb-badge-stable' }}">
                                    {{ $mhs['grade'] ?? '—' }}
                                </span>
                            </td>

                            @php
                                $statusBadge = match($mhs['status'] ?? null) {
                                    'final' => ['label' => 'Final', 'class' => 'nb-badge-success'],
                                    'draft' => ['label' => 'Draft', 'class' => 'nb-badge-warning'],
                                    default => ['label' => 'Belum Diisi', 'class' => 'nb-badge-stable'],
                                };
                            @endphp
                            <td class="text-center">
                                <span id="status_{{ $mhs['id'] }}">
                                    <span class="nb-badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@elseif($mkAktif && count($mahasiswaList) === 0)
    <div class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">group_off</span>
        <h3 class="nb-h3 mt-4">Belum Ada Mahasiswa</h3>
        <p class="text-muted mt-2">
            Belum ada mahasiswa yang KRS-nya disetujui untuk mata kuliah ini{{ $filterKelas ? ' di kelas '.$filterKelas : '' }}.
        </p>
    </div>
@else
    <div class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">edit_note</span>
        <h3 class="nb-h3 mt-4">Pilih Mata Kuliah</h3>
        <p class="text-muted mt-2">
            Pilih mata kuliah di atas untuk menampilkan daftar mahasiswa.
        </p>
    </div>
@endif
@endsection

@push('scripts')
<script>
const KODE_MK = '{{ $filterKodeMK ?? "" }}';
const KELAS = '{{ $filterKelas ?? "" }}';
const CSRF = '{{ csrf_token() }}';
const URL_SIMPAN = '{{ route("dosen.mk.simpan-nilai") }}';
const URL_FINALISASI = '{{ route("dosen.mk.finalisasi-nilai") }}';
const URL_KELAS = '{{ route("dosen.mk.kelas-by-mk") }}';

function onMKChange(sel) {
    const kodeMk = sel.value;
    const kelasSelect = document.getElementById('selectKelas');

    if (!kodeMk) {
        kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
        document.getElementById('filterForm').submit();
        return;
    }

    fetch(URL_KELAS + '?kode_mk=' + encodeURIComponent(kodeMk))
        .then(r => r.json())
        .then(data => {
            kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';

            (data.kelas || []).forEach(k => {
                const opt = document.createElement('option');
                opt.value = k;
                opt.textContent = k;
                kelasSelect.appendChild(opt);
            });

            if ((data.kelas || []).length === 1) {
                kelasSelect.value = data.kelas[0];
            }

            document.getElementById('filterForm').submit();
        })
        .catch(error => {
            console.error(error);
            document.getElementById('filterForm').submit();
        });
}

function getBobots() {
    return {
        tugas: parseFloat(document.getElementById('bobot_tugas')?.value || 20),
        praktikum: parseFloat(document.getElementById('bobot_praktikum')?.value || 15),
        uts: parseFloat(document.getElementById('bobot_uts')?.value || 30),
        uas: parseFloat(document.getElementById('bobot_uas')?.value || 30),
        kehadiran: parseFloat(document.getElementById('bobot_kehadiran')?.value || 5),
    };
}

function updateBobotTotal() {
    const b = getBobots();
    const total = b.tugas + b.praktikum + b.uts + b.uas + b.kehadiran;

    const el = document.getElementById('bobotTotal');
    const warn = document.getElementById('bobotWarn');

    if (el) {
        el.textContent = total + '%';
    }

    if (warn) {
        warn.classList.toggle('hidden', Math.abs(total - 100) < 0.01);
    }

    ['tugas', 'praktikum', 'uts', 'uas', 'kehadiran'].forEach(k => {
        const h = document.getElementById('hdr_' + k);

        if (h) {
            h.textContent = '(' + b[k] + '%)';
        }
    });
}

function gradeFromN(n) {
    if (n >= 85) return { g: 'A', cls: 'nb-badge-success' };
    if (n >= 80) return { g: 'A-', cls: 'nb-badge-success' };
    if (n >= 75) return { g: 'B+', cls: 'nb-badge-primary' };
    if (n >= 70) return { g: 'B', cls: 'nb-badge-primary' };
    if (n >= 65) return { g: 'B-', cls: 'nb-badge-primary' };
    if (n >= 60) return { g: 'C+', cls: 'nb-badge-warning' };
    if (n >= 55) return { g: 'C', cls: 'nb-badge-warning' };
    if (n >= 40) return { g: 'D', cls: 'nb-badge-warning' };

    return { g: 'E', cls: 'nb-badge-danger' };
}

function hitungBaris(id) {
    const b = getBobots();

    const v = k => parseFloat(document.getElementById(k + '_' + id)?.value) || 0;

    const akhir = (
        v('tugas') * b.tugas +
        v('praktikum') * b.praktikum +
        v('uts') * b.uts +
        v('uas') * b.uas +
        v('kehadiran') * b.kehadiran
    ) / 100;

    const el = document.getElementById('akhir_' + id);
    const gr = document.getElementById('grade_' + id);

    if (el) {
        el.textContent = akhir > 0 ? akhir.toFixed(1) : '—';
    }

    if (gr && akhir > 0) {
        const g = gradeFromN(akhir);
        gr.textContent = g.g;
        gr.className = 'nb-badge ' + g.cls;
    }
}

function recalcAll() {
    document.querySelectorAll('[id^="akhir_"]').forEach(el => {
        hitungBaris(el.id.replace('akhir_', ''));
    });

    updateBobotTotal();
}

const autoSaveTimers = {};

function onNilaiInput(id) {
    hitungBaris(id);

    clearTimeout(autoSaveTimers[id]);
    autoSaveTimers[id] = setTimeout(() => simpanSatu(id, { silent: true }), 800);
}

function setStatusBadge(id, label, cls) {
    const el = document.getElementById('status_' + id);

    if (el) {
        el.innerHTML = '<span class="nb-badge ' + cls + '">' + label + '</span>';
    }
}

async function simpanSatu(id, opts = {}) {
    const b = getBobots();
    const total = b.tugas + b.praktikum + b.uts + b.uas + b.kehadiran;

    if (Math.abs(total - 100) > 0.01) {
        if (!opts.silent) {
            alert('Total bobot harus 100%. Sekarang: ' + total + '%');
        }
        return false;
    }

    setStatusBadge(id, 'Menyimpan…', 'nb-badge-stable');

    const fd = new FormData();

    fd.append('_token', CSRF);
    fd.append('kode_mk', KODE_MK);
    fd.append('kelas', KELAS);
    fd.append('mahasiswa_id', id);

    ['tugas', 'praktikum', 'uts', 'uas', 'kehadiran'].forEach(k => {
        fd.append('bobot_' + k, b[k]);
        fd.append('nilai_' + k + '[' + id + ']', document.getElementById(k + '_' + id)?.value || 0);
    });

    try {
        const res = await fetch(URL_SIMPAN, {
            method: 'POST',
            body: fd,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        const contentType = res.headers.get('content-type');

        if (!res.ok) {
            let message = 'Gagal menyimpan nilai.';

            if (contentType && contentType.includes('application/json')) {
                const err = await res.json();
                message = err.message || message;
            } else {
                console.error(await res.text());
            }

            setStatusBadge(id, 'Gagal Tersimpan', 'nb-badge-danger');
            if (!opts.silent) alert(message);
            return false;
        }

        const data = await res.json();

        if (data.success) {
            const el = document.getElementById('akhir_' + id);
            const gr = document.getElementById('grade_' + id);

            if (el) {
                el.textContent = parseFloat(data.nilai_akhir).toFixed(1);
            }

            if (gr) {
                const g = gradeFromN(data.nilai_akhir);
                gr.textContent = data.grade;
                gr.className = 'nb-badge ' + g.cls;
            }

            setStatusBadge(id, 'Draft', 'nb-badge-warning');
            return true;
        } else {
            setStatusBadge(id, 'Gagal Tersimpan', 'nb-badge-danger');
            if (!opts.silent) alert(data.message || 'Gagal menyimpan nilai.');
            return false;
        }
    } catch (e) {
        console.error('Catch Error:', e);
        setStatusBadge(id, 'Gagal Tersimpan', 'nb-badge-danger');
        if (!opts.silent) alert('Terjadi kesalahan sistem. Buka Console Browser (F12) untuk detail.');
        return false;
    }
}

async function finalisasi() {
    const rows = document.querySelectorAll('[id^="row_"]');
    const btn = document.getElementById('btnFinalisasi');

    if (!rows.length) return;

    const b = getBobots();
    const totalBobot = b.tugas + b.praktikum + b.uts + b.uas + b.kehadiran;

    if (Math.abs(totalBobot - 100) > 0.01) {
        alert('Total bobot harus 100%. Sekarang: ' + totalBobot + '%');
        return;
    }

    if (!confirm('Finalisasi nilai untuk ' + rows.length + ' mahasiswa? Setelah difinalisasi, nilai akan langsung bisa dilihat mahasiswa di KHS mereka.')) {
        return;
    }

    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Menyimpan draft...';
    }

    // Pastikan semua perubahan terbaru di setiap baris sudah tersimpan sebagai draft
    for (const row of rows) {
        await simpanSatu(row.id.replace('row_', ''), { silent: true });
    }

    if (btn) {
        btn.textContent = 'Memfinalisasi...';
    }

    const fd = new FormData();
    fd.append('_token', CSRF);
    fd.append('kode_mk', KODE_MK);
    fd.append('kelas', KELAS);

    try {
        const res = await fetch(URL_FINALISASI, {
            method: 'POST',
            body: fd,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        const data = await res.json();

        if (data.success) {
            document.querySelectorAll('[id^="row_"]').forEach(row => {
                setStatusBadge(row.id.replace('row_', ''), 'Final', 'nb-badge-success');
            });

            alert(data.message);
        } else {
            alert(data.message || 'Gagal memfinalisasi nilai.');
        }
    } catch (e) {
        console.error('Catch Error:', e);
        alert('Terjadi kesalahan sistem saat memfinalisasi. Buka Console Browser (F12) untuk detail.');
    }

    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">verified</span> Finalisasi';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updateBobotTotal();
    recalcAll();
});
</script>
@endpush