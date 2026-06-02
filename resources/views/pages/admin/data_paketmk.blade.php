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

            <form action="{{ route('pages.admin.paketmk.store') }}" method="POST">
                @csrf
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
                            <div class="space-y-2 max-h-64 overflow-y-auto border rounded-lg p-3">
                                @foreach($allMataKuliah as $mk)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="mk{{ $mk->id }}" name="mata_kuliah[]" value="{{ $mk->id }}" 
                                            {{ old('mata_kuliah') && in_array($mk->id, old('mata_kuliah', [])) ? 'checked' : '' }}>
                                        <label for="mk{{ $mk->id }}" class="ml-2 cursor-pointer">
                                            {{ $mk->kode }} - {{ $mk->nama }} <span class="text-muted text-sm">({{ $mk->sks }} SKS)</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('mata_kuliah') <p class="nb-form-error">{{ $message }}</p> @enderror
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
    const rawData = @json($paketMK);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    function openModal() {
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
        document.addEventListener('DOMContentLoaded', () => { openModal(); });
    @endif

    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        data.forEach(pk => {
            const row = document.createElement('tr');
            const deleteUrl = `/admin/paket-mk/${pk.id}`;

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${pk.nama_paket}</td>
                <td class="text-center"><span class="nb-badge nb-badge-warning">Sem ${pk.semester}</span></td>
                <td class="hidden md:table-cell text-muted">${pk.prodi}</td>
                <td class="text-center">
                    <span class="font-extrabold text-ink" style="font-family: var(--font-heading);">${pk.total_sks}</span>
                    <span class="text-xs text-muted ml-1">SKS</span>
                </td>
                <td class="text-center">
                    <span class="font-extrabold text-ink" style="font-family: var(--font-heading);">${pk.jumlah_mk}</span>
                    <span class="text-xs text-muted ml-1">MK</span>
                </td>
                <td class="hidden lg:table-cell text-sm text-muted max-w-xs truncate">${pk.deskripsi || '-'}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Paket Mata Kuliah?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Mahasiswa yang menggunakan paket ini perlu memilih paket lain." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
                            @csrf @method('DELETE')
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

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush
