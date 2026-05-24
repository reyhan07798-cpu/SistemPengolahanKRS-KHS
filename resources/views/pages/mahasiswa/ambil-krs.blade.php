@extends('layouts.mahasiswa')

@section('page_title', 'Ambil KRS')

@section('content')
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

    @if(session('warning'))
        <div class="nb-alert nb-alert-warning mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Info Semester Aktif --}}
    @if(!$data['is_semester_active'])
        <div class="nb-alert nb-alert-warning mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:24px;">info</span>
            <div>
                <strong>Belum ada semester aktif</strong>
                <p class="text-sm mt-1">Hubungi administrator untuk mengaktifkan semester.</p>
            </div>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="nb-bento mb-6" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">event</span>
                </div>
                <p class="nb-stat-label">Semester Aktif</p>
            </div>

            <div class="nb-stat-value" id="displaySemesterAktif">
                @if($data['is_semester_active'])
                    {{ $data['semester_label'] }}
                @else
                    -
                @endif
            </div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">verified</span>
                </div>
                <p class="nb-stat-label">Sisa SKS</p>
            </div>

            <div class="nb-stat-value" id="sisaSks">24</div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    @if($data['status_krs'] === 'Sudah Mengajukan')
                        <span class="material-symbols-outlined filled" style="color: var(--color-success);">
                            check_circle
                        </span>
                    @else
                        <span class="material-symbols-outlined filled">pending</span>
                    @endif
                </div>
                <p class="nb-stat-label">Status KRS</p>
            </div>

            <div class="nb-stat-value" style="font-size:1.5rem;" id="statusKrs">
                {{ $data['status_krs'] }}
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Paket Semester</h3>
        </div>

        @if(!$data['is_semester_active'])
            <div class="nb-alert nb-alert-info mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">info</span>
                <span>Filter ini tidak tersedia karena belum ada semester aktif.</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="nb-label">Tahun Ajaran</label>
                <select id="filterTahun" @if(!$data['is_semester_active']) disabled @endif>
                    @if($data['is_semester_active'])
                        <option value="{{ $data['tahun_ajaran_aktif'] }}" selected>
                            {{ $data['tahun_ajaran_aktif'] }} (Aktif)
                        </option>
                    @else
                        <option value="">-- Tidak ada semester aktif --</option>
                    @endif
                </select>
            </div>

            <div>
                <label class="nb-label">Semester</label>
                <select id="filterSemester" @if(!$data['is_semester_active']) disabled @endif>
                    @if($data['is_semester_active'])
                        <option value="{{ $data['semester_label'] }}" selected>
                            {{ $data['semester_label'] }} (Aktif)
                        </option>
                    @else
                        <option value="">-- Tidak ada semester aktif --</option>
                    @endif
                </select>
            </div>

            <div class="flex items-end">
                <button
                    type="button"
                    onclick="loadPaketSemester()"
                    class="nb-btn nb-btn-primary w-full"
                    @if(!$data['is_semester_active']) disabled @endif
                >
                    <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                    Tampilkan Paket
                </button>
            </div>
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

        <input type="hidden" name="semester" id="inputSemester" value="{{ $data['semester_label'] ?? '' }}">
        <input type="hidden" name="tahun_ajaran" id="inputTahun" value="{{ $data['tahun_ajaran_aktif'] ?? '' }}">

        {{-- Paket Wajib --}}
        <div id="containerWajib" class="nb-card-flat mb-6 hidden">
            <div class="nb-section-header">
                <div>
                    <span class="nb-eyebrow" style="color:var(--color-accent-soft);">
                        Paket Semester <span id="labelSemesterWajib">{{ $data['semester_label'] ?? '-' }}</span>
                    </span>
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
                    <tbody id="tabelWajib"></tbody>
                </table>
            </div>
        </div>

        {{-- Paket Mengulang --}}
        <div id="containerMengulang" class="nb-card-flat mb-6 hidden">
            <div class="nb-section-header" style="background-color:var(--color-warning);">
                <div>
                    <span class="nb-eyebrow" style="color:rgba(31,41,55,0.7);">Mengulang</span>
                    <h2 class="mt-1" style="color:var(--color-ink);">Mata Kuliah Mengulang</h2>
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
                    <tbody id="tabelMengulang"></tbody>
                </table>
            </div>
        </div>

        {{-- Summary SKS --}}
        <div class="nb-card mb-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <p class="nb-label">Total SKS Terpilih</p>
                    <p class="nb-stat-value mt-1" id="totalSks">0</p>
                    <p class="text-xs text-muted mt-1 font-bold uppercase tracking-wider">
                        Maksimal: 24 SKS
                    </p>
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

            <p id="krsHelpText" class="text-xs text-muted mt-3">
                Pilih minimal 1 mata kuliah untuk mengaktifkan tombol Ajukan KRS.
            </p>
        </div>
    </form>

    {{-- Empty State --}}
    <div id="emptyState" class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">assignment</span>
        <h3 class="nb-h3 mt-4">Klik "Tampilkan Paket"</h3>
        <p class="text-muted mt-2">
            Pilih semester dan tahun ajaran, lalu klik tombol untuk melihat mata kuliah.
        </p>
    </div>
@endsection

@push('scripts')
<script>
    const MAX_SKS = 24;
    let selectedSks = 0;
    let paketData = {};

    const semesterAktifData = {
        label: @json($data['semester_label'] ?? ''),
        ke: @json($data['semester_aktif'] ?? ''),
        tahun: @json($data['tahun_ajaran_aktif'] ?? ''),
        isActive: @json($data['is_semester_active'] ?? false)
    };

    document.addEventListener('DOMContentLoaded', function () {
        const filterSemester = document.getElementById('filterSemester');
        const filterTahun = document.getElementById('filterTahun');
        const displaySemesterAktif = document.getElementById('displaySemesterAktif');
        const inputSemester = document.getElementById('inputSemester');
        const inputTahun = document.getElementById('inputTahun');
        const labelSemesterWajib = document.getElementById('labelSemesterWajib');
        const formKrs = document.getElementById('formKrs');

        if (semesterAktifData.isActive) {
            if (filterSemester) filterSemester.value = semesterAktifData.label;
            if (filterTahun) filterTahun.value = semesterAktifData.tahun;
            if (displaySemesterAktif) displaySemesterAktif.textContent = semesterAktifData.label;
            if (inputSemester) inputSemester.value = semesterAktifData.label;
            if (inputTahun) inputTahun.value = semesterAktifData.tahun;
            if (labelSemesterWajib) labelSemesterWajib.textContent = semesterAktifData.label;
        }

        if (filterSemester) {
            filterSemester.addEventListener('change', function () {
                if (displaySemesterAktif) {
                    displaySemesterAktif.textContent = this.value;
                }

                if (inputSemester) {
                    inputSemester.value = this.value;
                }

                if (labelSemesterWajib) {
                    labelSemesterWajib.textContent = this.value;
                }
            });
        }

        if (filterTahun) {
            filterTahun.addEventListener('change', function () {
                if (inputTahun) {
                    inputTahun.value = this.value;
                }
            });
        }

        if (formKrs) {
            formKrs.addEventListener('submit', function (e) {
                if (selectedSks === 0) {
                    e.preventDefault();
                    alert('Harap pilih minimal satu mata kuliah!');
                    return;
                }

                if (selectedSks > MAX_SKS) {
                    e.preventDefault();
                    alert(`Total SKS (${selectedSks}) melebihi batas maksimal ${MAX_SKS} SKS!`);
                }
            });
        }
    });

    function loadPaketSemester() {
        const semester = document.getElementById('filterSemester').value;
        const tahun = document.getElementById('filterTahun').value;

        document.getElementById('inputSemester').value = semester;
        document.getElementById('inputTahun').value = tahun;
        document.getElementById('labelSemesterWajib').textContent = semester;

        const emptyState = document.getElementById('emptyState');
        const containerWajib = document.getElementById('containerWajib');
        const containerMengulang = document.getElementById('containerMengulang');

        emptyState.innerHTML = `
            <div class="flex items-center justify-center gap-3 py-12">
                <div class="animate-spin">
                    <span class="material-symbols-outlined">hourglass_top</span>
                </div>
                <span>Memuat paket semester...</span>
            </div>
        `;

        emptyState.classList.remove('hidden');
        containerWajib.classList.add('hidden');
        containerMengulang.classList.add('hidden');

        fetch(`{{ route('pages.mahasiswa.api.paket-semester') }}?semester=${encodeURIComponent(semester)}&tahun_ajaran=${encodeURIComponent(tahun)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    emptyState.innerHTML = `
                        <div class="nb-alert nb-alert-danger flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined">error</span>
                            <div>
                                <strong>Semester Tidak Aktif</strong>
                                <p class="text-sm mt-1">${data.message}</p>
                                ${data.semester_aktif ? `<p class="text-sm font-semibold mt-2">Semester Aktif: <strong>${data.semester_aktif} ${data.tahun_aktif}</strong></p>` : ''}
                            </div>
                        </div>
                    `;

                    paketData = {};
                    resetForm();
                    return;
                }

                emptyState.classList.add('hidden');
                paketData = data.paket_semester || {};
                renderTabel();
                resetForm();
            })
            .catch(error => {
                console.error('Error:', error);

                emptyState.innerHTML = `
                    <div class="nb-alert nb-alert-danger flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        <div>
                            <strong>Terjadi Kesalahan</strong>
                            <p class="text-sm mt-1">Gagal memuat paket semester. Silakan coba lagi.</p>
                        </div>
                    </div>
                `;
            });
    }

    function renderTabel() {
        renderTabelSection('tabelWajib', paketData.wajib || [], false);
        renderTabelSection('tabelMengulang', paketData.mengulang || [], true);

        document.getElementById('containerWajib').classList.toggle(
            'hidden',
            (paketData.wajib || []).length === 0
        );

        document.getElementById('containerMengulang').classList.toggle(
            'hidden',
            (paketData.mengulang || []).length === 0
        );
    }

    function renderTabelSection(tbodyId, data, isMengulang) {
        const tbody = document.getElementById(tbodyId);
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-muted font-medium">
                        Tidak ada data
                    </td>
                </tr>
            `;
            return;
        }

        data.forEach(mk => {
            const row = document.createElement('tr');

            const nilaiLama = mk.nilaiLama || mk.nilai_lama || '-';
            const prasyarat = mk.prasyarat || '-';

            const lastCell = isMengulang
                ? `<span class="nb-badge nb-badge-danger">${nilaiLama}</span>`
                : `<span class="text-muted text-sm">${prasyarat}</span>`;

            row.innerHTML = `
                <td class="text-center">
                    <input
                        type="checkbox"
                        name="mata_kuliah_ids[]"
                        value="${mk.id}"
                        data-sks="${mk.sks}"
                        class="chk-mk w-5 h-5 cursor-pointer"
                        ${isMengulang ? 'data-mengulang="true"' : ''}
                        style="accent-color:var(--color-accent);"
                    >
                </td>
                <td class="font-bold text-primary" style="font-family:var(--font-heading);">
                    ${mk.kode || '-'}
                </td>
                <td class="font-medium text-ink">
                    ${mk.matkul || mk.nama || '-'}
                </td>
                <td class="text-sm text-muted">
                    ${mk.dosen || '-'}
                </td>
                <td class="text-center font-bold">
                    ${mk.sks || 0}
                </td>
                <td class="text-center">
                    ${lastCell}
                </td>
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
            warningText.textContent = `Anda melebihi batas maksimal ${MAX_SKS} SKS. Total: ${selectedSks} SKS.`;
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
            const sksMk = parseInt(chk.dataset.sks) || 0;
            chk.disabled = selectedSks + sksMk > MAX_SKS;
        });

        updateHelpText();
    }

    function updateHelpText() {
        const helpText = document.getElementById('krsHelpText');

        if (!helpText) return;

        if (selectedSks === 0) {
            helpText.textContent = 'Pilih minimal 1 mata kuliah untuk mengaktifkan tombol Ajukan KRS.';
        } else if (selectedSks > MAX_SKS) {
            helpText.textContent = `Total SKS ${selectedSks} melebihi batas ${MAX_SKS}. Kurangi pilihan mata kuliah.`;
        } else {
            helpText.textContent = 'Jumlah SKS valid. Tekan Ajukan KRS untuk mengirim pilihan Anda.';
        }
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

        updateHelpText();
    }
</script>
@endpush