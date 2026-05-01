@extends('layouts.admin')

@section('title', 'Data Dosen')
@section('page_title', 'Data Dosen')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Data Dosen</h1>
            <p>Kelola data dosen pengajar dan dosen wali.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Dosen
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

    {{-- Stats Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">supervisor_account</span>
                </div>
                <p class="nb-stat-label">Dosen Wali</p>
            </div>
            <div class="nb-stat-value" id="countWali">0</div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">co_present</span>
                </div>
                <p class="nb-stat-label">Dosen Mata Kuliah</p>
            </div>
            <div class="nb-stat-value" id="countMK">0</div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Dosen Aktif</span>
                <h2 class="mt-1">Daftar Dosen</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th class="hidden md:table-cell">Email</th>
                        <th class="hidden sm:table-cell text-center">Tipe Dosen</th>
                        <th class="hidden lg:table-cell">Program Studi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">badge</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data dosen</p>
        </div>
    </div>

    {{-- MODAL TAMBAH DOSEN --}}
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Dosen Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="{{ route('pages.admin.dosen.store') }}" method="POST">
                @csrf
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="nb-label">NIP / NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" placeholder="198501012020011001" required>
                            @error('nik') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" required>
                            @error('nama') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@univ.ac.id" required>
                            @error('email') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Tipe Dosen <span class="text-danger">*</span></label>
                            <select name="tipe_dosen" required>
                                <option value="">Pilih tipe dosen</option>
                                <option value="Dosen Wali" {{ old('tipe_dosen') == 'Dosen Wali' ? 'selected' : '' }}>Dosen Wali</option>
                                <option value="Dosen Mata Kuliah" {{ old('tipe_dosen') == 'Dosen Mata Kuliah' ? 'selected' : '' }}>Dosen Mata Kuliah</option>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Program Studi    <span class="text-danger">*</span></label>
                            <select name="fakultas" required>
                                <option value="">Pilih Prodi</option>
                                @foreach($fakultasList as $fakultas)
                                    <option value="{{ $fakultas }}" {{ old('fakultas') == $fakultas ? 'selected' : '' }}>{{ $fakultas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        </div>

                        <div>
                            <label class="nb-label">Password Default <span class="text-danger">*</span></label>
                            <input type="text" name="password" value="{{ old('password', 'dosen123') }}" placeholder="dosen123" required>
                            @error('password') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="nb-modal-footer">
                    <button type="button" onclick="closeModal()" class="nb-btn nb-btn-secondary nb-btn-sm">Batal</button>
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
    const rawData = @json($dosen);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const countWaliSpan = document.getElementById('countWali');
    const countMKSpan = document.getElementById('countMK');

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

        let waliCount = 0;
        let mkCount = 0;

        data.forEach(dsn => {
            if (dsn.tipe_dosen === 'Dosen Wali') waliCount++; else mkCount++;

            const row = document.createElement('tr');
            const editUrl = `/admin/dosen/${dsn.id}/edit`;
            const deleteUrl = `/admin/dosen/${dsn.id}`;
            const initials = dsn.nama.split(' ').map(n => n[0]).join('').substring(0, 2);
            const badgeClass = dsn.tipe_dosen === 'Dosen Wali' ? 'nb-badge-success' : 'nb-badge-primary';

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${dsn.nik}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-accent-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                            <span class="text-ink font-extrabold text-xs" style="font-family: var(--font-heading);">${initials}</span>
                        </div>
                        <span class="font-medium text-ink">${dsn.nama}</span>
                    </div>
                </td>
                <td class="hidden md:table-cell text-sm text-primary">${dsn.email}</td>
                <td class="hidden sm:table-cell text-center"><span class="nb-badge ${badgeClass}">${dsn.tipe_dosen}</span></td>
                <td class="hidden lg:table-cell text-muted">${dsn.fakultas}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Data Dosen?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Data dosen akan dihapus permanen." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
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

        countWaliSpan.textContent = waliCount;
        countMKSpan.textContent = mkCount;
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush
