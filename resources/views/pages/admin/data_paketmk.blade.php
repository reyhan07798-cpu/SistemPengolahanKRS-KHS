@extends('layouts.admin')

@section('title', 'Kelola Paket Matakuliah')
@section('page_title', 'Paket Mata Kuliah')

@section('content')    {{-- Hidden session message indicators --}}
    @if(session('success'))
        <div data-session-success="{{ session('success') }}" style="display:none;"></div>
    @endif
    @if(session('error'))
        <div data-session-error="{{ session('error') }}" style="display:none;"></div>
    @endif
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Paket Mata Kuliah</h1>
            <p>Kelola paket mata kuliah yang dibundle per semester & program studi.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Paket
        </button>
    </div>

    @if(session('success'))
        <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Bundle Kurikulum</span>
                <h2 class="mt-1">Daftar Paket</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th class="text-center">Semester</th>
                        <th class="hidden md:table-cell">Prodi</th>
                        <th class="text-center">Total SKS</th>
                        <th class="text-center">Jumlah MK</th>
                        <th class="hidden lg:table-cell">Deskripsi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">inventory_2</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data paket</p>
        </div>
    </div>

    {{-- MODAL TAMBAH PAKET MK --}}
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Paket Mata Kuliah Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="{{ route('pages.admin.paketmk.store') }}" method="POST" id="paketForm">
                @csrf
                <input type="hidden" name="_method" id="paketFormMethod" value="POST">
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="md:col-span-2">
                            <label class="nb-label">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" placeholder="Paket Semester 1" required>
                            @error('nama_paket') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>Semester {{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi" required>
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="2" placeholder="Deskripsi paket mata kuliah">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Pilih Mata Kuliah <span class="text-danger">*</span></label>
                            <div class="relative mb-3">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-muted" style="font-size:20px;">search</span>
                                <input
                                    type="text"
                                    id="mkSearchInput"
                                    placeholder="Cari kode atau nama mata kuliah"
                                    class="w-full pl-10"
                                    autocomplete="off"
                                >
                            </div>
                            <div id="mkChecklist" class="space-y-2 max-h-64 overflow-y-auto border rounded-lg p-3">
                                @foreach($allMataKuliah as $mk)
                                    <div
                                        class="mk-option flex items-center"
                                        data-search="{{ strtolower(($mk->kode ?? '') . ' ' . ($mk->nama ?? '') . ' ' . ($mk->prodi ?? '')) }}"
                                        data-semester="{{ $mk->semester_ke }}"
                                        data-prodi="{{ $mk->prodi }}"
                                    >
                                        <input type="checkbox" id="mk{{ $mk->id }}" name="mata_kuliah[]" value="{{ $mk->id }}" data-sks="{{ $mk->sks }}"
                                            {{ old('mata_kuliah') && in_array($mk->id, old('mata_kuliah', [])) ? 'checked' : '' }}>
                                        <label for="mk{{ $mk->id }}" class="ml-2 cursor-pointer">
                                            {{ $mk->kode }} - {{ $mk->nama }}
                                            <span class="text-muted text-sm">({{ $mk->sks }} SKS, Semester {{ $mk->semester_ke }}, {{ $mk->prodi }})</span>
                                        </label>
                                    </div>
                                @endforeach
                                <p id="mkSearchEmpty" class="hidden text-muted text-center py-4">Mata kuliah tidak ditemukan</p>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                                <span class="nb-badge nb-badge-primary">Total: <span id="jumlahMk">0</span> MK</span>
                                <span class="nb-badge nb-badge-success">SKS: <span id="totalSksPaket">0</span>/24</span>
                            </div>
                            <p id="sksPaketWarning" class="hidden nb-form-error mt-2">Total SKS paket tidak boleh lebih dari 24 SKS.</p>
                            @error('mata_kuliah') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="nb-modal-footer">
                    <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">
                        Batal
                    </button>
                    <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm" id="paketSubmitBtn">
                        <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const rawData = @json($paketMK);
    const mkData = @json($allMataKuliah);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const mkSearchInput = document.getElementById('mkSearchInput');
    const mkOptions = document.querySelectorAll('.mk-option');
    const mkSearchEmpty = document.getElementById('mkSearchEmpty');
    const paketSubmitBtn = document.getElementById('paketSubmitBtn');
    const jumlahMkSpan = document.getElementById('jumlahMk');
    const totalSksPaketSpan = document.getElementById('totalSksPaket');
    const sksPaketWarning = document.getElementById('sksPaketWarning');
    const paketForm = document.getElementById('paketForm');
    const paketFormMethod = document.getElementById('paketFormMethod');
    const modalTitle = document.getElementById('modal-title');
    const semesterSelect = paketForm?.elements.semester;
    const prodiSelect = paketForm?.elements.prodi;

    function selectedSemester() {
        return String(semesterSelect?.value || '');
    }

    function selectedProdi() {
        return String(prodiSelect?.value || '');
    }

    function optionMatchesScope(option) {
        const semester = selectedSemester();
        const prodi = selectedProdi();
        const semesterMatch = !semester || String(option.dataset.semester || '') === semester;
        const prodiMatch = !prodi || String(option.dataset.prodi || '') === prodi;

        return semesterMatch && prodiMatch;
    }

    function syncProdiOptions() {
        if (!prodiSelect) return;

        const semester = selectedSemester();
        const availableProdis = new Set(
            mkData
                .filter(mk => !semester || String(mk.semester_ke || '') === semester)
                .map(mk => mk.prodi)
                .filter(Boolean)
        );

        Array.from(prodiSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = availableProdis.size > 0 && !availableProdis.has(option.value);
        });

        if (prodiSelect.value && prodiSelect.selectedOptions[0]?.hidden) {
            prodiSelect.value = '';
        }
    }

    function uncheckHiddenOptions() {
        mkOptions.forEach(option => {
            if (option.classList.contains('hidden')) {
                const checkbox = option.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
        });
    }

    function filterMataKuliah() {
        const keyword = (mkSearchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;

        mkOptions.forEach(option => {
            const isMatch = optionMatchesScope(option) && option.dataset.search.includes(keyword);
            option.classList.toggle('hidden', !isMatch);
            if (isMatch) visibleCount++;
        });

        mkSearchEmpty?.classList.toggle('hidden', visibleCount > 0);
    }

    function resetMataKuliahFilter() {
        if (!mkSearchInput) return;

        mkSearchInput.value = '';
        filterMataKuliah();
    }

    function updatePaketSummary() {
        const checked = document.querySelectorAll('input[name="mata_kuliah[]"]:checked');
        let totalSks = 0;

        checked.forEach(checkbox => {
            totalSks += parseInt(checkbox.dataset.sks || '0', 10);
        });

        if (jumlahMkSpan) jumlahMkSpan.textContent = checked.length;
        if (totalSksPaketSpan) totalSksPaketSpan.textContent = totalSks;

        const isOverLimit = totalSks > 24;
        sksPaketWarning?.classList.toggle('hidden', !isOverLimit);
        if (paketSubmitBtn) paketSubmitBtn.disabled = isOverLimit;
    }

    function openModal(paket = null) {
        paketForm.reset();
        paketForm.action = paket ? `/admin/paket-mk/${paket.id}` : "{{ route('pages.admin.paketmk.store') }}";
        paketFormMethod.value = paket ? 'PUT' : 'POST';
        modalTitle.textContent = paket ? 'Edit Paket Mata Kuliah' : 'Tambah Paket Mata Kuliah Baru';

        document.querySelectorAll('input[name="mata_kuliah[]"]').forEach(checkbox => {
            checkbox.checked = paket
                ? (paket.mata_kuliah_ids || []).map(String).includes(String(checkbox.value))
                : false;
        });

        if (paket) {
            paketForm.elements.nama_paket.value = paket.nama_paket || '';
            paketForm.elements.semester.value = paket.semester || '';
            syncProdiOptions();
            paketForm.elements.prodi.value = paket.prodi || '';
            paketForm.elements.deskripsi.value = paket.deskripsi || '';
        } else {
            syncProdiOptions();
        }

        resetMataKuliahFilter();
        uncheckHiddenOptions();
        updatePaketSummary();
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        mkSearchInput?.focus();
    }
    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.getElementById('modalOverlay')?.addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });

    @if(old('_token') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => { openModal(); });
    @endif

    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        data.forEach(pk => {
            const row = document.createElement('tr');
            const deleteUrl = `/admin/paket-mk/${pk.id}`;
            const namaPaket = pk.nama_paket || '-';
            const semester = pk.semester || '-';
            const prodi = pk.prodi || '-';
            const totalSks = pk.total_sks || 0;
            const jumlahMk = pk.jumlah_mk || 0;

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${namaPaket}</td>
                <td class="text-center"><span class="nb-badge nb-badge-warning">Sem ${semester}</span></td>
                <td class="hidden md:table-cell text-muted">${prodi}</td>
                <td class="text-center">
                    <span class="font-extrabold text-ink" style="font-family: var(--font-heading);">${totalSks}</span>
                    <span class="text-xs text-muted ml-1">SKS</span>
                </td>
                <td class="text-center">
                    <span class="font-extrabold text-ink" style="font-family: var(--font-heading);">${jumlahMk}</span>
                    <span class="text-xs text-muted ml-1">MK</span>
                </td>
                <td class="hidden lg:table-cell text-sm text-muted max-w-xs truncate">${pk.deskripsi || '-'}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" class="nb-row-action edit js-edit-paket" data-id="${pk.id}" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </button>
                        <button type="button" class="nb-row-action danger" title="Hapus" onclick="deleteData('${deleteUrl}', 'Hapus Paket Mata Kuliah?', 'Paket mata kuliah akan disembunyikan dari tampilan admin.', '${namaPaket}')">
                            <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                        </button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        mkSearchInput?.addEventListener('input', filterMataKuliah);
        semesterSelect?.addEventListener('change', () => {
            syncProdiOptions();
            filterMataKuliah();
            uncheckHiddenOptions();
            updatePaketSummary();
        });
        prodiSelect?.addEventListener('change', () => {
            filterMataKuliah();
            uncheckHiddenOptions();
            updatePaketSummary();
        });
        document.querySelectorAll('input[name="mata_kuliah[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updatePaketSummary);
        });
        syncProdiOptions();
        filterMataKuliah();
        updatePaketSummary();
        renderTable(rawData);
    });

    tableBody.addEventListener('click', (event) => {
        const editButton = event.target.closest('.js-edit-paket');

        if (!editButton) {
            return;
        }

        const paket = rawData.find(item => String(item.id) === String(editButton.dataset.id));
        if (paket) openModal(paket);
    });
</script>
@endpush
