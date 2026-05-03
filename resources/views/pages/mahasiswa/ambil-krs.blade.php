@extends('layouts.mahasiswa')

@section('page_title', 'Ambil KRS')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Ambil KRS</h1>
            <p>Pilih mata kuliah untuk semester ini. Maksimal 24 SKS per semester.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <x-stat-bento 
        :stats="['semester_aktif' => 2, 'sisa_sks' => 24, 'status_krs' => 'Belum Diajukan']"
        :config="[
            'semester_aktif' => ['color' => 'nb-stat--info', 'icon' => 'event', 'label' => 'Semester Aktif'],
            'sisa_sks' => ['color' => 'nb-stat--accent', 'icon' => 'verified', 'label' => 'Sisa SKS'],
            'status_krs' => ['color' => 'nb-stat--warning', 'icon' => 'pending', 'label' => 'Status KRS']
        ]" 
        stat-value-style="'font-size: 1.5rem;'" />

    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Paket Semester</h3>
        </div>
        <x-filter-mahasiswa tahun-label="Tahun Ajaran" semester-label="Semester" id-prefix="filter" />
        <div class="flex items-end mt-4">
            <button type="button" onclick="loadPaketSemester()" class="nb-btn nb-btn-primary w-full">
                <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                Tampilkan Paket
            </button>
        </div>
    </div>

    {{-- Warning SKS --}}
    <div id="warningSks" class="hidden mb-4">
        <div class="nb-alert nb-alert-warning flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            <span id="warningText">Anda telah mencapai batas maksimal 24 SKS per semester.</span>
        </div>
    </div>

    {{-- Form KRS --}}
    <form method="POST" action="{{ route('pages.mahasiswa.store-krs') }}" id="formKrs">
        @csrf
        <input type="hidden" name="semester" id="inputSemester" value="2">
        <input type="hidden" name="tahun_ajaran" id="inputTahun" value="2025/2026">

        {{-- Paket Wajib --}}
        <div id="containerWajib" class="nb-card-flat mb-6 hidden">
            <div class="nb-section-header">
                <div>
                    <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Paket Semester <span id="labelSemesterWajib">2</span></span>
                    <h2 class="mt-1">Mata Kuliah Wajib</h2>
                </div>
                <span class="nb-badge nb-badge-success">Wajib Diambil</span>
            </div>
            <div class="overflow-x-auto">
                <table class="nb-table">
                    <thead>
                        <tr>
                            <th class="text-center">Pilih</th>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Prasyarat</th>
                        </tr>
                    </thead>
                    <tbody id="tabelWajib">
                        {{-- Diisi oleh JavaScript --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paket Mengulang --}}
        <div id="containerMengulang" class="nb-card-flat mb-6 hidden">
            <div class="nb-section-header" style="background-color: var(--color-warning);">
                <div>
                    <span class="nb-eyebrow" style="color: rgba(31,41,55,0.7);">Mengulang</span>
                    <h2 class="mt-1" style="color: var(--color-ink);">Mata Kuliah Mengulang</h2>
                </div>
                <span class="nb-badge nb-badge-stable">Nilai D / E</span>
            </div>
            <div class="overflow-x-auto">
                <table class="nb-table">
                    <thead>
                        <tr>
                            <th class="text-center">Pilih</th>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">Nilai Lama</th>
                        </tr>
                    </thead>
                    <tbody id="tabelMengulang">
                        {{-- Diisi oleh JavaScript --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary SKS --}}
        <div class="nb-card mb-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <p class="nb-label">Total SKS Terpilih</p>
                    <p class="nb-stat-value mt-1" id="totalSks">0</p>
                    <p class="text-xs text-muted mt-1 font-bold uppercase tracking-wider">Maksimal: 24 SKS</p>
                </div>
                <div class="flex gap-3 flex-wrap">
                    <button type="button" onclick="resetForm()" class="nb-btn nb-btn-secondary">
                        <span class="material-symbols-outlined" style="font-size:18px;">refresh</span>
                        Reset
                    </button>
                    <button type="submit" id="btnSubmit" class="nb-btn nb-btn-primary" disabled>
                        <span class="material-symbols-outlined" style="font-size:18px;">send</span>
                        Ajukan KRS
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Empty State --}}
    <div id="emptyState" class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">assignment</span>
        <h3 class="nb-h3 mt-4">Klik "Tampilkan Paket"</h3>
        <p class="text-muted mt-2">Pilih semester dan tahun ajaran, lalu klik tombol untuk melihat mata kuliah.</p>
    </div>

    @push('scripts')
    <script>
        const semesterAktifMahasiswa = 2;
        const MAX_SKS = 24;
        let selectedSks = 0;
        let paketData = {};

        const dummyData = {
            1: {
                wajib: [
                    { id: 1, kode: 'IF101', matkul: 'Algoritma & Pemrograman', dosen: 'Dr. Ahmad Fauzi, M.Kom', sks: 4, prasyarat: '-' },
                    { id: 2, kode: 'IF102', matkul: 'Matematika Diskrit', dosen: 'Dr. Siti Nurhaliza, M.Si', sks: 3, prasyarat: '-' },
                    { id: 3, kode: 'IF103', matkul: 'Bahasa Indonesia', dosen: 'Dra. Rina Susanti', sks: 2, prasyarat: '-' },
                    { id: 4, kode: 'IF104', matkul: 'Pendidikan Pancasila', dosen: 'Prof. Dr. Budi Santoso', sks: 2, prasyarat: '-' },
                ],
                mengulang: []
            },
            2: {
                wajib: [
                    { id: 5, kode: 'IF201', matkul: 'Basis Data', dosen: 'Dr. Budi Santoso, M.T', sks: 4, prasyarat: 'IF101' },
                    { id: 6, kode: 'IF202', matkul: 'Pemrograman Web', dosen: 'Dr. Andi Wijaya, M.Kom', sks: 4, prasyarat: 'IF101' },
                    { id: 7, kode: 'IF203', matkul: 'Jaringan Komputer', dosen: 'Dr. Citra Lestari, M.T', sks: 4, prasyarat: 'IF102' },
                    { id: 8, kode: 'IF204', matkul: 'Proyek Pembuatan Prototipe', dosen: 'Dr. Dewi Kusuma, M.Kom', sks: 4, prasyarat: '-' },
                    { id: 9, kode: 'IF205', matkul: 'Sistem Operasi', dosen: 'Dr. Eko Prasetyo, M.Kom', sks: 3, prasyarat: 'IF101' },
                    { id: 10, kode: 'IF206', matkul: 'Struktur Data', dosen: 'Dr. Fitri Handayani, M.T', sks: 3, prasyarat: 'IF101' },
                ],
                mengulang: [
                    { id: 101, kode: 'IF103', matkul: 'Bahasa Indonesia', dosen: 'Dra. Rina Susanti', sks: 2, nilaiLama: 'D' },
                    { id: 102, kode: 'IF104', matkul: 'Pendidikan Pancasila', dosen: 'Prof. Dr. Budi Santoso', sks: 2, nilaiLama: 'E' },
                ]
            },
            3: {
                wajib: [
                    { id: 11, kode: 'IF301', matkul: 'Pemrograman Berorientasi Objek', dosen: 'Dr. Eka Prasetyo, M.Kom', sks: 4, prasyarat: 'IF202' },
                    { id: 12, kode: 'IF302', matkul: 'Rekayasa Perangkat Lunak', dosen: 'Dr. Fitri Handayani, M.T', sks: 4, prasyarat: 'IF201' },
                ],
                mengulang: []
            },
            4: {
                wajib: [
                    { id: 13, kode: 'IF401', matkul: 'Kecerdasan Buatan', dosen: 'Dr. Hadi Purnomo, M.Kom', sks: 4, prasyarat: 'IF301' },
                ],
                mengulang: []
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('displaySemesterAktif').textContent = semesterAktifMahasiswa;
            const selectSemester = document.getElementById('filterSemester');
            selectSemester.innerHTML = '';
            for (let i = 1; i <= semesterAktifMahasiswa; i++) {
                const option = document.createElement('option');
                option.value = i;
                if (i === semesterAktifMahasiswa) {
                    option.textContent = `Semester ${i} (Aktif)`;
                    option.selected = true;
                } else {
                    option.textContent = `Semester ${i}`;
                }
                selectSemester.appendChild(option);
            }
        });

        function loadPaketSemester() {
            const semester = document.getElementById('filterSemester').value;
            const tahun = document.getElementById('filterTahun').value;
            document.getElementById('inputSemester').value = semester;
            document.getElementById('inputTahun').value = tahun;
            document.getElementById('labelSemesterWajib').textContent = semester;
            paketData = dummyData[semester] || { wajib: [], mengulang: [] };
            renderTabel();
            resetForm();
            document.getElementById('emptyState').classList.add('hidden');
        }

        function renderTabel() {
            renderTabelSection('tabelWajib', paketData.wajib, false);
            renderTabelSection('tabelMengulang', paketData.mengulang, true);
            document.getElementById('containerWajib').classList.toggle('hidden', paketData.wajib.length === 0);
            document.getElementById('containerMengulang').classList.toggle('hidden', paketData.mengulang.length === 0);
        }

        function renderTabelSection(tbodyId, data, isMengulang) {
            const tbody = document.getElementById(tbodyId);
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-muted font-medium">Tidak ada data</td></tr>`;
                return;
            }
            data.forEach(mk => {
                const row = document.createElement('tr');
                const lastCell = isMengulang
                    ? `<span class="nb-badge nb-badge-danger">${mk.nilaiLama}</span>`
                    : `<span class="text-muted text-sm">${mk.prasyarat || '-'}</span>`;
                row.innerHTML = `
                    <td class="text-center">
                        <input type="checkbox" name="mata_kuliah_ids[]" value="${mk.id}" data-sks="${mk.sks}" class="chk-mk nb-no-style w-5 h-5 cursor-pointer accent-current" ${isMengulang ? 'data-mengulang="true"' : ''} style="accent-color: var(--color-accent);">
                    </td>
                    <td class="font-bold text-primary" style="font-family: var(--font-heading);">${mk.kode}</td>
                    <td class="font-medium text-ink">${mk.matkul}</td>
                    <td class="text-sm text-muted">${mk.dosen}</td>
                    <td class="text-center font-bold">${mk.sks}</td>
                    <td class="text-center">${lastCell}</td>
                `;
                tbody.appendChild(row);
            });
            tbody.querySelectorAll('.chk-mk').forEach(chk => {
                chk.addEventListener('change', hitungSks);
            });
        }

        function hitungSks() {
            selectedSks = 0;
            document.querySelectorAll('.chk-mk:checked').forEach(chk => {
                selectedSks += parseInt(chk.dataset.sks) || 0;
            });
            document.getElementById('totalSks').textContent = selectedSks;
            document.getElementById('sisaSks').textContent = MAX_SKS - selectedSks;
            const warning = document.getElementById('warningSks');
            const warningInner = warning.querySelector('.nb-alert');
            const warningText = document.getElementById('warningText');
            const btnSubmit = document.getElementById('btnSubmit');
            if (selectedSks > MAX_SKS) {
                warning.classList.remove('hidden');
                warningText.textContent = `Anda melebihi batas maksimal ${MAX_SKS} SKS. Total saat ini: ${selectedSks} SKS.`;
                warningInner.classList.remove('nb-alert-warning');
                warningInner.classList.add('nb-alert-danger');
                btnSubmit.disabled = true;
            } else if (selectedSks === MAX_SKS) {
                warning.classList.remove('hidden');
                warningText.textContent = `Anda telah mencapai batas maksimal ${MAX_SKS} SKS.`;
                warningInner.classList.remove('nb-alert-danger');
                warningInner.classList.add('nb-alert-warning');
                btnSubmit.disabled = false;
            } else {
                warning.classList.add('hidden');
                btnSubmit.disabled = selectedSks === 0;
            }
            document.querySelectorAll('.chk-mk:not(:checked)').forEach(chk => {
                const mkSks = parseInt(chk.dataset.sks) || 0;
                chk.disabled = (selectedSks + mkSks) > MAX_SKS;
            });
        }

        function resetForm() {
            document.querySelectorAll('.chk-mk').forEach(chk => {
                chk.checked = false;
                chk.disabled = false;
            });
            selectedSks = 0;
            document.getElementById('totalSks').textContent = '0';
            document.getElementById('sisaSks').textContent = MAX_SKS;
            document.getElementById('warningSks').classList.add('hidden');
            document.getElementById('btnSubmit').disabled = true;
        }

        document.getElementById('formKrs').addEventListener('submit', function (e) {
            if (selectedSks === 0) {
                e.preventDefault();
                alert('Harap pilih minimal satu mata kuliah!');
                return;
            }
            if (selectedSks > MAX_SKS) {
                e.preventDefault();
                alert(`Total SKS (${selectedSks}) melebihi batas maksimal ${MAX_SKS} SKS!`);
                return;
            }
        });
    </script>
    @endpush
@endsection
