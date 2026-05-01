@extends('layouts.dosen')

@section('title', 'Input Nilai')
@section('page_title', 'Input Nilai')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen Matkul · Penilaian</span>
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
                @foreach($mataKuliahList ?? [] as $mk)
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
                        <th class="text-center">Status Nilai</th>
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
                <div class="nb-info-card flex items-center gap-4 mb-5">
                    <div class="nb-avatar-sm" style="cursor:default;" id="modalAvatar">A</div>
                    <div class="min-w-0">
                        <p class="font-bold text-ink" id="modalNama">Nama Mahasiswa</p>
                        <p class="text-xs text-muted mt-1" id="modalNIM">NIM - Kelas</p>
                    </div>
                </div>

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

                <div class="nb-info-card flex justify-between items-center mt-5">
                    <span class="nb-label" style="margin-bottom:0;">Nilai Akhir & Grade</span>
                    <div class="flex items-center gap-3">
                        <span class="font-extrabold text-2xl text-primary" style="font-family: var(--font-heading);" id="modalTotal">-</span>
                        <span class="nb-badge nb-badge-stable" id="modalGrade">-</span>
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
        if (currentMK && dataNilai[currentMK]) {
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
            const statusBadge = mhs.nilai !== null
                ? `<span class="nb-badge nb-badge-success">${mhs.nilai} (${mhs.grade})</span>`
                : `<span class="nb-badge nb-badge-stable">Belum Diinput</span>`;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="font-bold text-muted">${idx + 1}</td>
                <td class="font-bold text-primary text-sm" style="font-family: var(--font-heading);">${mhs.nim}</td>
                <td class="font-medium text-ink">${mhs.nama}</td>
                <td class="text-center"><span class="nb-badge nb-badge-stable">${mhs.kelas}</span></td>
                <td class="text-center">${statusBadge}</td>
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

        document.getElementById('modalTotal').textContent = total > 0 ? total.toFixed(1) : '-';
        const gradeEl = document.getElementById('modalGrade');
        gradeEl.textContent = total > 0 ? grade : '-';
        gradeEl.className = `nb-badge ${total > 0 ? badge : 'nb-badge-stable'}`;
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
