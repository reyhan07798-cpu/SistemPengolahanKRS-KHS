@extends('layouts.mahasiswa')

@section('page_title', 'Ambil KRS')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Ambil KRS</h1>
            <p>Pilih mata kuliah untuk semester aktif. Maksimal 24 SKS per semester.</p>
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

    <div id="readOnlyWarning" class="hidden nb-alert nb-alert-warning mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">lock</span>
        <div>
            <strong>Mode Read Only</strong>
            <p class="text-sm mt-1">
                Semester yang dipilih bukan semester aktif atau Anda sudah mengajukan KRS pada semester ini.
            </p>
        </div>
    </div>

    <div class="nb-bento mb-6" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">event</span>
                </div>
                <p class="nb-stat-label">Semester Aktif</p>
            </div>
            <div class="nb-stat-value" id="displaySemesterAktif">
                {{ $data['semester_label'] ?? '-' }}
            </div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">verified</span>
                </div>
                <p class="nb-stat-label">Sisa SKS</p>
            </div>
            <div class="nb-stat-value" id="sisaSks">
                {{ $data['sisa_sks'] ?? 24 }}
            </div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    @if($data['status_krs'] === 'Disetujui' || $data['status_krs'] === 'Menunggu')
                        <span class="material-symbols-outlined filled" style="color: var(--color-success);">check_circle</span>
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

    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter Paket Semester</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="nb-label">Tahun Ajaran</label>
                <select id="filterTahun">
                    @foreach($data['tahun_ajaran_list'] as $tahun)
                        <option value="{{ $tahun->tahun_ajaran }}"
                            {{ $tahun->tahun_ajaran == $data['tahun_ajaran_aktif'] ? 'selected' : '' }}>
                            {{ $tahun->tahun_ajaran }}
                            {{ $tahun->tahun_ajaran == $data['tahun_ajaran_aktif'] ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="nb-label">Semester</label>
                <select id="filterSemester">
                    @foreach($data['semester_list'] as $sem)
                        <option value="{{ $sem->semester }}"
                            {{ $sem->semester == $data['semester_aktif_value'] ? 'selected' : '' }}>
                            Semester {{ $sem->semester }}
                            {{ $sem->semester == $data['semester_aktif_value'] ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="button" onclick="loadPaketSemester()" class="nb-btn nb-btn-primary w-full">
                    <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                    Tampilkan Paket
                </button>
            </div>
        </div>
        <div class="mt-3 text-sm text-muted">
            <strong>Catatan:</strong> Paket mata kuliah dikelompokkan berdasarkan <em>semester</em>. Kelas mahasiswa hanya memengaruhi pengampu (dosen) atau jadwal, bukan ketersediaan mata kuliah pada paket.
        </div>
    </div>

    <div id="warningSks" class="hidden mb-4">
        <div class="nb-alert nb-alert-warning flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            <span id="warningText">Anda telah mencapai batas maksimal 24 SKS per semester.</span>
        </div>
    </div>

    <form method="POST" action="{{ route('pages.mahasiswa.store-krs') }}" id="formKrs">
        @csrf

        <input type="hidden" name="semester" id="inputSemester" value="{{ $data['semester_aktif_value'] ?? '' }}">
        <input type="hidden" name="tahun_ajaran" id="inputTahun" value="{{ $data['tahun_ajaran_aktif'] ?? '' }}">

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

        <div id="summarySection" class="nb-card mb-6">
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

    <div id="emptyState" class="nb-card text-center py-12">
        <span class="material-symbols-outlined text-muted" style="font-size:64px;">assignment</span>
        <h3 class="nb-h3 mt-4">Klik "Tampilkan Paket"</h3>
        <p class="text-muted mt-2">Pilih semester dan tahun ajaran, lalu klik tombol untuk melihat mata kuliah.</p>
    </div>
@endsection

@push('scripts')
<script>
    const MAX_SKS = 24;
    let selectedSks = 0;
    let paketData = {};
    let isReadOnlyMode = false;
    let existingTotalSks = {{ $data['total_sks'] ?? 0 }};

    document.addEventListener('DOMContentLoaded', function () {
        loadPaketSemester();
    });

    function loadPaketSemester() {
        const semester = document.getElementById('filterSemester').value;
        const tahun = document.getElementById('filterTahun').value;

        document.getElementById('inputSemester').value = semester;
        document.getElementById('inputTahun').value = tahun;
        document.getElementById('labelSemesterWajib').textContent = `Semester ${semester} ${tahun}`;

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
                                <strong>Gagal Memuat</strong>
                                <p class="text-sm mt-1">${data.message}</p>
                            </div>
                        </div>
                    `;

                    paketData = {};
                    isReadOnlyMode = false;
                    existingTotalSks = 0;
                    resetForm();
                    return;
                }

                emptyState.classList.add('hidden');

                paketData = data.paket_semester || {};
                isReadOnlyMode = data.is_read_only || false;
                existingTotalSks = parseInt(data.total_sks_diambil || 0);

                document.getElementById('readOnlyWarning').classList.toggle('hidden', !isReadOnlyMode);
                document.getElementById('summarySection').classList.toggle('hidden', isReadOnlyMode);

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

        document.getElementById('containerWajib').classList.toggle('hidden', (paketData.wajib || []).length === 0);
        document.getElementById('containerMengulang').classList.toggle('hidden', (paketData.mengulang || []).length === 0);

        if ((paketData.wajib || []).length === 0 && (paketData.mengulang || []).length === 0) {
            const emptyState = document.getElementById('emptyState');
            emptyState.classList.remove('hidden');
            emptyState.innerHTML = `
                <span class="material-symbols-outlined text-muted" style="font-size:64px;">folder_off</span>
                <h3 class="nb-h3 mt-4">Tidak ada paket mata kuliah</h3>
                <p class="text-muted mt-2">Belum ada mata kuliah untuk semester dan tahun ajaran yang dipilih.</p>
            `;
        }
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

            const nilaiLama = mk.nilaiLama || '-';
            const prasyarat = mk.prasyarat || '-';

            const lastCell = isMengulang
                ? `<span class="nb-badge nb-badge-danger">${nilaiLama}</span>`
                : `<span class="text-muted text-sm">${prasyarat}</span>`;

            const matkulCell = isMengulang
                ? `<div class="font-medium text-ink">${mk.matkul || '-'}</div>
                   <div class="text-xs text-muted mt-1">Semester asal: ${mk.semesterAsal || '-'}</div>`
                : `<span class="font-medium text-ink">${mk.matkul || '-'}</span>`;

            row.innerHTML = `
                <td class="text-center">
                    <input
                        type="checkbox"
                        name="mata_kuliah_ids[]"
                        value="${mk.id}"
                        data-sks="${mk.sks}"
                        class="chk-mk ${isMengulang ? 'chk-mengulang' : 'chk-wajib'} w-5 h-5 cursor-pointer"
                        ${isMengulang ? 'data-mengulang="true"' : ''}
                        style="accent-color:var(--color-accent);"
                    >
                </td>
                <td class="font-bold text-primary" style="font-family:var(--font-heading);">${mk.kode || '-'}</td>
                <td>${matkulCell}</td>
                <td class="text-sm text-muted">${mk.dosen || '-'}</td>
                <td class="text-center font-bold">${mk.sks || 0}</td>
                <td class="text-center">${lastCell}</td>
            `;

            tbody.appendChild(row);
        });

        tbody.querySelectorAll('.chk-mk').forEach(chk => {
            chk.addEventListener('change', hitungSks);
        });
    }

    function hitungSks() {
        let allWajibChecked = true;

        document.querySelectorAll('.chk-wajib').forEach(chk => {
            if (!chk.checked) {
                allWajibChecked = false;
            }
        });

        document.querySelectorAll('.chk-mengulang').forEach(chk => {
            if (!allWajibChecked) {
                chk.checked = false;
                chk.disabled = true;
            }
        });

        selectedSks = 0;

        document.querySelectorAll('.chk-mk:checked').forEach(chk => {
            selectedSks += parseInt(chk.dataset.sks) || 0;
        });

        document.getElementById('totalSks').textContent = selectedSks;
        document.getElementById('sisaSks').textContent = Math.max(0, MAX_SKS - selectedSks);

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
        } else if (!allWajibChecked) {
            warning.classList.remove('hidden');
            warningText.textContent = 'Pilih semua mata kuliah wajib paket semester aktif terlebih dahulu.';
            warningInner.classList.remove('nb-alert-danger');
            warningInner.classList.add('nb-alert-warning');
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

            if (chk.classList.contains('chk-mengulang') && !allWajibChecked) {
                chk.disabled = true;
            } else {
                chk.disabled = (selectedSks + sksMk > MAX_SKS);
            }
        });

        updateHelpText(allWajibChecked);
    }

    function updateHelpText(allWajibChecked = true) {
        const helpText = document.getElementById('krsHelpText');

        if (!helpText) {
            return;
        }

        if (!allWajibChecked && document.querySelectorAll('.chk-mengulang').length > 0) {
            helpText.textContent = 'Pilih semua Mata Kuliah Wajib terlebih dahulu sebelum memilih MK Mengulang.';
            helpText.style.color = 'var(--color-warning)';
        } else if (selectedSks === 0) {
            helpText.textContent = 'Pilih minimal 1 mata kuliah untuk mengaktifkan tombol Ajukan KRS.';
            helpText.style.color = '';
        } else if (selectedSks > MAX_SKS) {
            helpText.textContent = `Total SKS ${selectedSks} melebihi batas ${MAX_SKS}. Kurangi pilihan mata kuliah.`;
            helpText.style.color = 'var(--color-danger)';
        } else {
            helpText.textContent = 'Jumlah SKS valid. Tekan Ajukan KRS untuk mengirim pilihan Anda.';
            helpText.style.color = '';
        }
    }

    function resetForm() {
        document.querySelectorAll('.chk-mk').forEach(chk => {
            chk.checked = chk.classList.contains('chk-wajib');
            chk.disabled = false;
        });

        const totalSksEl = document.getElementById('totalSks');
        const sisaSksEl = document.getElementById('sisaSks');
        const btnSubmit = document.getElementById('btnSubmit');

        document.getElementById('warningSks').classList.add('hidden');

        if (isReadOnlyMode) {
            document.querySelectorAll('.chk-mk').forEach(chk => {
                chk.disabled = true;
            });

            if (totalSksEl) {
                totalSksEl.textContent = existingTotalSks;
            }

            if (sisaSksEl) {
                sisaSksEl.textContent = Math.max(0, MAX_SKS - existingTotalSks);
            }

            if (btnSubmit) {
                btnSubmit.disabled = true;
            }
        } else {
            hitungSks();
            return;
        }

        updateHelpText();
    }
</script>
@endpush
