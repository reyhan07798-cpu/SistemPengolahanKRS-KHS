@extends('layouts.admin')

@section('title', 'Data Mata Kuliah')
@section('page_title', 'Data Mata Kuliah')

@section('content')
    {{-- Hidden session message indicators --}}
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
            <h1 class="mt-2">Data Mata Kuliah</h1>
            <p>Daftar mata kuliah yang tersedia di sistem.</p>
        </div>

        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Mata Kuliah
        </button>
    </div>

    {{-- Table Card --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Kurikulum</span>
                <h2 class="mt-1">Daftar Mata Kuliah</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="hidden sm:table-cell">Semester</th>
                        <th class="hidden md:table-cell">Dosen Pengampu</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">menu_book</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data mata kuliah</p>
        </div>
    </div>

    {{-- MODAL TAMBAH MATA KULIAH --}}
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Mata Kuliah Baru</h3>

                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form id="modalForm" action="{{ route('pages.admin.matakuliah.store') }}" method="POST">
                @csrf

                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="nb-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                            <input
                                id="input_kode_mk"
                                type="text"
                                name="kode_mk"
                                value="{{ old('kode_mk') }}"
                                placeholder="Contoh: IF101"
                                required
                            >
                            @error('kode_mk')
                                <p class="nb-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="nb-label">SKS <span class="text-danger">*</span></label>
                            <select id="input_sks" name="sks" required>
                                <option value="">Pilih SKS</option>
                                <option value="1" {{ old('sks') == '1' ? 'selected' : '' }}>1 SKS</option>
                                <option value="2" {{ old('sks') == '2' ? 'selected' : '' }}>2 SKS</option>
                                <option value="3" {{ old('sks') == '3' ? 'selected' : '' }}>3 SKS</option>
                                <option value="4" {{ old('sks') == '4' ? 'selected' : '' }}>4 SKS</option>
                                <option value="5" {{ old('sks') == '5' ? 'selected' : '' }}>5 SKS</option>
                                <option value="6" {{ old('sks') == '6' ? 'selected' : '' }}>6 SKS</option>
                            </select>
                            @error('sks')
                                <p class="nb-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                            <input
                                id="input_nama"
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                placeholder="Contoh: Pemrograman Web"
                                required
                            >
                            @error('nama')
                                <p class="nb-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="nb-label">Semester <span class="text-danger">*</span></label>
                            <select id="input_semester_ke" name="semester_ke" required>
                                <option value="">Pilih Semester</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester_ke') == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester_ke')
                                <p class="nb-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="nb-label">Dosen Pengampu</label>
                            <select id="input_dosen_id" name="dosen_id">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens ?? [] as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <p class="nb-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="nb-modal-footer">
                    <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">
                        Batal
                    </button>

                    <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm">
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
    const rawData = @json($matakuliah ?? []);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    const csrfField = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
    const methodDeleteField = '<input type="hidden" name="_method" value="DELETE">';
    const methodPutField = '<input type="hidden" name="_method" value="PUT">';

    const storeUrl = "{{ route('pages.admin.matakuliah.store') }}";
    const baseUrl = "{{ url('admin/matakuliah') }}";

    function openModal() {
        const form = document.getElementById('modalForm');
        document.getElementById('modal-title').innerText = 'Tambah Mata Kuliah Baru';
        form.action = storeUrl;
        // remove method override if exists
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        // reset fields
        document.getElementById('input_kode_mk').value = '';
        document.getElementById('input_sks').value = '';
        document.getElementById('input_nama').value = '';
        document.getElementById('input_semester_ke').value = '';
        document.getElementById('input_dosen_id').value = '';

        // reset submit button (preserve icon)
        const submitBtn = document.querySelector('#modalForm button[type="submit"]');
        if (submitBtn) submitBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan';

        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(id) {
        const mk = rawData.find(m => m.id == id);
        if (!mk) {
            alert('Data mata kuliah tidak ditemukan.');
            return;
        }

        const form = document.getElementById('modalForm');
        document.getElementById('modal-title').innerText = 'Edit Mata Kuliah';
        form.action = baseUrl + '/' + id;

        // add PUT method override if not present
        const existing = form.querySelector('input[name="_method"]');
        if (!existing) {
            form.insertAdjacentHTML('afterbegin', methodPutField);
        } else {
            existing.value = 'PUT';
        }

        // fill fields
        document.getElementById('input_kode_mk').value = mk.kode_mk || '';
        document.getElementById('input_sks').value = mk.sks || '';
        document.getElementById('input_nama').value = mk.nama || '';
        document.getElementById('input_semester_ke').value = mk.semester_ke || '';
        document.getElementById('input_dosen_id').value = mk.dosen_id ?? '';

        // change submit button text (preserve icon)
        const submitBtn = document.querySelector('#modalForm button[type="submit"]');
        if (submitBtn) submitBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">save</span> Update Mata Kuliah';

        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('modalOverlay')?.addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });

    @if(old('_token') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            openModal();
        });
    @endif

    function safeText(value) {
        return value ?? '-';
    }

    function renderTable(data) {
        tableBody.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        data.forEach(mk => {
            const row = document.createElement('tr');
            const deleteUrl = `/admin/matakuliah/${mk.id}`;

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">
                    ${safeText(mk.kode_mk)}
                </td>

                <td class="font-medium text-ink">
                    ${safeText(mk.nama)}
                </td>

                <td class="text-center">
                    <span class="nb-badge nb-badge-primary">${safeText(mk.sks)} SKS</span>
                </td>

                <td class="hidden sm:table-cell text-muted">
                    Semester ${safeText(mk.semester_ke)}
                </td>

                <td class="hidden md:table-cell text-muted">
                    ${safeText(mk.dosen_pengampu)}
                </td>

                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openEditModal(${mk.id})" class="nb-row-action" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </button>

                        <form
                            action="${deleteUrl}"
                            method="POST"
                            data-nb-confirm="true"
                            data-nb-confirm-title="Hapus Mata Kuliah?"
                            data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Mata kuliah ini akan hilang dari paket KRS."
                            data-nb-confirm-button="Ya, Hapus"
                            data-nb-confirm-icon="delete_forever"
                            class="inline"
                        >
                            ${csrfField}${methodDeleteField}

                            <button type="submit" class="nb-row-action danger" title="Hapus">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();

        const filtered = rawData.filter(mk => {
            const kode = (mk.kode_mk ?? '').toLowerCase();
            const nama = (mk.nama ?? '').toLowerCase();

            return kode.includes(searchTerm) || nama.includes(searchTerm);
        });

        renderTable(filtered);
    }

    document.getElementById('searchInput')?.addEventListener('keyup', applyFilters);

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush