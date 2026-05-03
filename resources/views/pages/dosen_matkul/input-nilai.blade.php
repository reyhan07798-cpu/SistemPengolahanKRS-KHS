@extends('layouts.dosen')

@section('title', 'Input Nilai')
@section('page_title', 'Input Nilai')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Penilaian</span>
            <h1 class="mt-2">Input Nilai</h1>
            <p>Input nilai akhir mahasiswa untuk mata kuliah yang Anda ampu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif


    {{-- Filter Tahun Ajaran & Semester --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Periode Penilaian</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen_matkul.input-nilai') }}">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="nb-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran">
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $filterTahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Semester</label>
                    <select name="semester">
                        @foreach($semesterList as $sem)
                            <option value="{{ $sem }}" {{ $filterSemester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="nb-btn nb-btn-primary w-full">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span> Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Bobot Penilaian --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-primary">info</span>
            <h3 class="nb-h3" style="font-size:1.125rem;">Bobot Penilaian</h3>
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="nb-badge nb-badge-stable">Tugas <strong class="ml-1">20%</strong></span>
            <span class="nb-badge nb-badge-stable">Praktikum <strong class="ml-1">15%</strong></span>
            <span class="nb-badge nb-badge-primary">UTS <strong class="ml-1">30%</strong></span>
            <span class="nb-badge nb-badge-primary">UAS <strong class="ml-1">30%</strong></span>
            <span class="nb-badge nb-badge-success">Kehadiran <strong class="ml-1">5%</strong></span>
        </div>
    </div>

    {{-- Pilih Mata Kuliah --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">menu_book</span>
            <h3 class="nb-h3">Pilih Mata Kuliah</h3>
        </div>
        <div class="max-w-md">
            <label class="nb-label">Mata Kuliah yang Diampu</label>
            <select id="pilihMK">
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach($mataKuliahList as $mk)
                    <option value="{{ $mk['kode'] }}"
                            data-nama="{{ $mk['nama'] }}"
                            data-sks="{{ $mk['sks'] }}"
                            data-semester="{{ $mk['semester'] }}">
                        {{ $mk['kode'] }} - {{ $mk['nama'] }} ({{ $mk['jumlah_mahasiswa'] }} mahasiswa)
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div id="tabelContainer" class="hidden nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);" id="tabelInfo">-</span>
                <h2 class="mt-1" id="tabelJudul">Daftar Mahasiswa</h2>
            </div>
            <span class="nb-badge nb-badge-primary" id="tabelCount">0 Mahasiswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Nilai (0–100)</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Angka (0–4)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="daftarMahasiswa"></tbody>
            </table>
        </div>
    </div>

    {{-- Empty State --}}
    <div id="emptyState" class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">edit_note</span>
        <h3 class="nb-h3 mt-4">Pilih Mata Kuliah</h3>
        <p class="text-muted mt-2">Silakan pilih mata kuliah untuk mulai menginput nilai.</p>
    </div>

    {{-- MODAL INPUT NILAI --}}
    <div id="nilaiModal" class="nb-modal-overlay hidden" role="dialog" aria-modal="true">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <div>
                    <h3>Input Nilai Mahasiswa</h3>
                    <p class="text-xs mt-1" style="color: var(--color-accent-soft);" id="modalMKInfo">-</p>
                </div>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <div class="nb-modal-body">
                {{-- Info Mahasiswa --}}
                <div class="nb-info-card flex items-center gap-4 mb-5">
                    <div class="nb-avatar-sm" style="cursor:default;" id="modalAvatar">A</div>
                    <div class="min-w-0">
                        <p class="font-bold text-ink" id="modalNama">Nama Mahasiswa</p>
                        <p class="text-xs text-muted mt-1" id="modalNIM">NIM - Kelas</p>
                    </div>
                </div>

                {{-- Form Input Komponen --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="nb-label">Tugas (20%)</label>
                        <input type="number" id="inTugas" min="0" max="100" class="text-center" placeholder="0">
                    </div>
                    <div>
                        <label class="nb-label">Praktikum (15%)</label>
                        <input type="number" id="inPraktikum" min="0" max="100" class="text-center" placeholder="0">
                    </div>
                    <div>
                        <label class="nb-label">UTS (30%)</label>
                        <input type="number" id="inUTS" min="0" max="100" class="text-center" placeholder="0">
                    </div>
                    <div>
                        <label class="nb-label">UAS (30%)</label>
                        <input type="number" id="inUAS" min="0" max="100" class="text-center" placeholder="0">
                    </div>
                    <div class="col-span-2">
                        <label class="nb-label">Kehadiran (5%)</label>
                        <input type="number" id="inHadir" min="0" max="100" class="text-center" placeholder="0">
                    </div>
                </div>

                {{-- Hasil Perhitungan --}}
                <div class="nb-info-card mt-5" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:center;">
                    <div>
                        <p class="nb-label" style="margin-bottom:4px;">Nilai Akhir & Grade</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-extrabold text-2xl text-primary" style="font-family:var(--font-heading);" id="modalTotal">—</span>
                            <span class="nb-badge nb-badge-stable" id="modalGrade">—</span>
                        </div>
                    </div>
                    <div style="border-left:2px solid var(--nb-border);padding-left:16px;">
                        <p class="nb-label" style="margin-bottom:4px;">Angka (0–4)</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-extrabold text-2xl text-accent" style="font-family:var(--font-heading);" id="modalMutu">—</span>
                            <span class="text-xs text-muted">Indeks Prestasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nb-modal-footer">
                <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">Batal</button>
                <button type="button" onclick="simpanNilai()" class="nb-btn nb-btn-primary nb-btn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    Simpan Nilai
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let dataNilai = {
        'IF101': [
            { nim: '3312501022', nama: 'Reyhan', kelas: 'A', nilai: null, grade: null },
            { nim: '3312501007', nama: 'Nabila Fatin', kelas: 'A', nilai: null, grade: null },
            { nim: '3312501017', nama: 'Irenessa Rosidin', kelas: 'A', nilai: null, grade: null }
        ],
        'IF102': [
            { nim: '3312501010', nama: 'Della Reska', kelas: 'B', nilai: null, grade: null },
            { nim: '3312501023', nama: 'Samuel Deidra', kelas: 'B', nilai: 78.5, grade: 'B' }
        ]
    };

    const BOBOT = { tugas: 0.20, praktikum: 0.15, uts: 0.30, uas: 0.30, hadir: 0.05 };
    let currentMK = null;
    let currentIdx = null;

    document.getElementById('pilihMK').addEventListener('change', function () {
        currentMK = this.value;
        if (currentMK) {
            const opt = this.options[this.selectedIndex];
            document.getElementById('tabelJudul').textContent = `Daftar Mahasiswa - ${opt.dataset.nama}`;
            document.getElementById('tabelInfo').textContent = `SKS ${opt.dataset.sks} · Semester ${opt.dataset.semester}`;
            document.getElementById('tabelCount').textContent = `${dataNilai[currentMK].length} Mahasiswa`;

            document.getElementById('tabelContainer').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            renderTable();
        } else {
            document.getElementById('tabelContainer').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
        }
    });

    function renderTable() {
        const tbody = document.getElementById('daftarMahasiswa');
        tbody.innerHTML = '';

        dataNilai[currentMK].forEach((mhs, idx) => {
            function nilaiToMutu(n) {
                if (n === null) return null;
                if (n >= 85) return 4.00;
                if (n >= 80) return 3.75;
                if (n >= 75) return 3.50;
                if (n >= 70) return 3.00;
                if (n >= 65) return 2.75;
                if (n >= 60) return 2.50;
                if (n >= 55) return 2.00;
                if (n >= 40) return 1.00;
                return 0.00;
            }
            const nilaiDisplay = mhs.nilai !== null
                ? `<span class="font-extrabold text-primary" style="font-family:var(--font-heading);">${mhs.nilai}</span>`
                : `<span class="nb-badge nb-badge-stable">—</span>`;
            const gradeDisplay = mhs.grade !== null
                ? `<span class="nb-badge ${mhs.grade==='A'?'nb-badge-success':mhs.grade==='B'?'nb-badge-primary':'nb-badge-warning'}">${mhs.grade}</span>`
                : `<span class="nb-badge nb-badge-stable">—</span>`;
            const mutu = nilaiToMutu(parseFloat(mhs.nilai));
            const mutuDisplay = mutu !== null
                ? `<span class="font-extrabold ${mutu>=3.5?'text-accent':mutu>=2.5?'text-primary':'text-muted'}" style="font-family:var(--font-heading);">${mutu.toFixed(2)}</span>`
                : `<span class="nb-badge nb-badge-stable">—</span>`;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="font-bold text-muted">${idx + 1}</td>
                <td class="font-bold text-primary text-sm" style="font-family: var(--font-heading);">${mhs.nim}</td>
                <td class="font-medium text-ink">${mhs.nama}</td>
                <td class="text-center"><span class="nb-badge nb-badge-stable">${mhs.kelas}</span></td>
                <td class="text-center">${nilaiDisplay}</td>
                <td class="text-center">${gradeDisplay}</td>
                <td class="text-center">${mutuDisplay}</td>
                <td class="text-center">
                    <button type="button" onclick="openModal(${idx})" class="nb-btn nb-btn-primary nb-btn-sm">
                        <span class="material-symbols-outlined" style="font-size:14px;">${mhs.nilai !== null ? 'edit' : 'add'}</span>
                        ${mhs.nilai !== null ? 'Edit' : 'Input'}
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    window.openModal = function (idx) {
        currentIdx = idx;
        const mhs = dataNilai[currentMK][idx];
        const mkNama = document.getElementById('pilihMK').options[document.getElementById('pilihMK').selectedIndex].dataset.nama;

        document.getElementById('modalMKInfo').textContent = `${mkNama} (${currentMK})`;
        document.getElementById('modalNama').textContent = mhs.nama;
        document.getElementById('modalNIM').textContent = `${mhs.nim} · Kelas ${mhs.kelas}`;
        document.getElementById('modalAvatar').textContent = mhs.nama.charAt(0);

        ['inTugas','inPraktikum','inUTS','inUAS','inHadir'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('modalTotal').textContent = '-';
        const gradeEl = document.getElementById('modalGrade');
        gradeEl.textContent = '-';
        gradeEl.className = 'nb-badge nb-badge-stable';

        document.getElementById('nilaiModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setupModalListeners();
    };

    window.closeModal = function () {
        document.getElementById('nilaiModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        removeModalListeners();
    };

    function calculateModal() {
        const t = parseFloat(document.getElementById('inTugas').value) || 0;
        const p = parseFloat(document.getElementById('inPraktikum').value) || 0;
        const u = parseFloat(document.getElementById('inUTS').value) || 0;
        const a = parseFloat(document.getElementById('inUAS').value) || 0;
        const h = parseFloat(document.getElementById('inHadir').value) || 0;

        const total = (t * BOBOT.tugas) + (p * BOBOT.praktikum) + (u * BOBOT.uts) + (a * BOBOT.uas) + (h * BOBOT.hadir);

        let grade = 'E', badge = 'nb-badge-danger';
        if (total >= 85) { grade = 'A'; badge = 'nb-badge-success'; }
        else if (total >= 70) { grade = 'B'; badge = 'nb-badge-primary'; }
        else if (total >= 55) { grade = 'C'; badge = 'nb-badge-warning'; }
        else if (total >= 40) { grade = 'D'; badge = 'nb-badge-warning'; }

        document.getElementById('modalTotal').textContent = total > 0 ? total.toFixed(1) : '—';
        const gradeEl = document.getElementById('modalGrade');
        gradeEl.textContent = total > 0 ? grade : '—';
        gradeEl.className = `nb-badge ${total > 0 ? badge : 'nb-badge-stable'}`;

        // Hitung mutu 0-4
        let mutu = 0;
        if (total >= 85) mutu = 4.00;
        else if (total >= 80) mutu = 3.75;
        else if (total >= 75) mutu = 3.50;
        else if (total >= 70) mutu = 3.00;
        else if (total >= 65) mutu = 2.75;
        else if (total >= 60) mutu = 2.50;
        else if (total >= 55) mutu = 2.00;
        else if (total >= 40) mutu = 1.00;
        const mutuEl = document.getElementById('modalMutu');
        if (mutuEl) {
            mutuEl.textContent = total > 0 ? mutu.toFixed(2) : '—';
            mutuEl.className = `font-extrabold text-2xl ${mutu >= 3.5 ? 'text-accent' : mutu >= 2.5 ? 'text-primary' : 'text-muted'}`;
            mutuEl.style.fontFamily = 'var(--font-heading)';
        }
    }

    function setupModalListeners() {
        ['inTugas', 'inPraktikum', 'inUTS', 'inUAS', 'inHadir'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateModal);
        });
    }
    function removeModalListeners() {
        ['inTugas', 'inPraktikum', 'inUTS', 'inUAS', 'inHadir'].forEach(id => {
            document.getElementById(id).removeEventListener('input', calculateModal);
        });
    }

    window.simpanNilai = function () {
        const t = parseFloat(document.getElementById('inTugas').value) || 0;
        const p = parseFloat(document.getElementById('inPraktikum').value) || 0;
        const u = parseFloat(document.getElementById('inUTS').value) || 0;
        const a = parseFloat(document.getElementById('inUAS').value) || 0;
        const h = parseFloat(document.getElementById('inHadir').value) || 0;

        if (t + p + u + a + h === 0) {
            alert('Harap isi minimal satu komponen nilai!');
            return;
        }

        const total = (t * BOBOT.tugas) + (p * BOBOT.praktikum) + (u * BOBOT.uts) + (a * BOBOT.uas) + (h * BOBOT.hadir);
        let grade = 'E';
        if (total >= 85) grade = 'A';
        else if (total >= 70) grade = 'B';
        else if (total >= 55) grade = 'C';
        else if (total >= 40) grade = 'D';

        dataNilai[currentMK][currentIdx].nilai = total.toFixed(1);
        dataNilai[currentMK][currentIdx].grade = grade;

        closeModal();
        renderTable();

        setTimeout(() => {
            alert(`✅ Nilai untuk ${dataNilai[currentMK][currentIdx].nama} berhasil disimpan!\nNilai Akhir: ${total.toFixed(1)} | Grade: ${grade}`);
        }, 300);
    };

    document.getElementById('nilaiModal').addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush
