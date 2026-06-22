@extends('layouts.admin')

@section('title', 'Data Dosen')
@section('page_title', 'Data Dosen')

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

    {{-- Filter Card --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">search</span>
            <h3 class="nb-h3">Filter Dosen</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label class="nb-label">Program Studi</label>
                <select id="filterProdi">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>
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

    <div id="adminDeleteModal" class="admin-delete-backdrop" aria-hidden="true">
        <div class="admin-delete-modal" role="dialog" aria-modal="true" aria-labelledby="adminDeleteTitle">
            <div class="admin-delete-header">
                <div class="admin-delete-icon">
                    <span class="material-symbols-outlined">delete</span>
                </div>
                <button type="button" class="admin-delete-close" id="adminDeleteClose" aria-label="Tutup">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="admin-delete-body">
                <span class="admin-delete-badge">Hapus Sementara</span>
                <h3 id="adminDeleteTitle">Hapus Dosen dari Tampilan?</h3>
                <p id="adminDeleteMessage">
                    Data dosen tidak akan hilang permanen, hanya disembunyikan dari tampilan admin.
                </p>
                <div class="admin-delete-target">
                    <span class="material-symbols-outlined">person</span>
                    <strong id="adminDeleteName">Dosen</strong>
                </div>
            </div>

            <div class="admin-delete-footer">
                <button type="button" class="admin-delete-btn admin-delete-cancel" id="adminDeleteCancel">Batal</button>
                <button type="button" class="admin-delete-btn admin-delete-confirm" id="adminDeleteConfirm">
                    <span class="material-symbols-outlined">delete</span>
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH / EDIT DOSEN --}}
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Dosen Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="{{ route('pages.admin.dosen.store') }}" method="POST" id="dosenForm">
                @csrf
                <input type="hidden" name="_method" id="dosenFormMethod" value="POST">

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
                            <label class="nb-label">Peran Dosen <span class="text-danger">*</span></label>
                            <select name="tipe_dosen" required>
                                <option value="">Pilih peran dosen</option>
                                <option value="keduanya" {{ old('tipe_dosen') == 'keduanya' ? 'selected' : '' }}>
                                    Dosen Wali &amp; Mata Kuliah
                                </option>
                                <option value="Dosen Mata Kuliah" {{ old('tipe_dosen') == 'Dosen Mata Kuliah' ? 'selected' : '' }}>
                                    Dosen Mata Kuliah
                                </option>
                            </select>
                            @error('tipe_dosen') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="fakultas" required>
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi }}" {{ old('fakultas') == $prodi ? 'selected' : '' }}>
                                        {{ $prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fakultas') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890">
                            @error('no_hp') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Password <span class="text-danger js-password-required">*</span></label>
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
<style>
    .admin-delete-backdrop {
        position: fixed;
        inset: 0;
        z-index: 80;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(15, 23, 42, 0.58);
        backdrop-filter: blur(6px);
    }

    .admin-delete-backdrop.show {
        display: flex;
        animation: adminFadeIn 160ms ease-out;
    }

    .admin-delete-modal {
        width: min(460px, 100%);
        border-radius: 14px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.12);
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
        overflow: hidden;
        animation: adminModalIn 180ms ease-out;
    }

    .admin-delete-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.25rem 0.25rem;
    }

    .admin-delete-icon {
        width: 52px;
        height: 52px;
        border-radius: 999px;
        display: grid;
        place-items: center;
        color: #b42318;
        background: linear-gradient(135deg, #fee4e2, #fff1f3);
        border: 1px solid #fecdca;
    }

    .admin-delete-icon .material-symbols-outlined {
        font-size: 28px;
    }

    .admin-delete-close {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: grid;
        place-items: center;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        cursor: pointer;
    }

    .admin-delete-body {
        padding: 0.75rem 1.25rem 1rem;
    }

    .admin-delete-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.25rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 800;
        color: #b42318;
        background: #fff1f3;
        border: 1px solid #fecdca;
    }

    .admin-delete-body h3 {
        margin: 0.8rem 0 0.4rem;
        font-size: 1.35rem;
        font-weight: 900;
        color: #111827;
    }

    .admin-delete-body p {
        margin: 0;
        color: #64748b;
        line-height: 1.55;
    }

    .admin-delete-target {
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.8rem 0.9rem;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        color: #334155;
    }

    .admin-delete-target strong {
        word-break: break-word;
    }

    .admin-delete-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.65rem;
        padding: 1rem 1.25rem 1.25rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .admin-delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-height: 40px;
        padding: 0.55rem 0.95rem;
        border-radius: 8px;
        font-weight: 800;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .admin-delete-cancel {
        color: #334155;
        background: #fff;
        border-color: #cbd5e1;
    }

    .admin-delete-confirm {
        color: #fff;
        background: #dc2626;
        border-color: #dc2626;
        box-shadow: 0 10px 18px rgba(220, 38, 38, 0.22);
    }

    .admin-delete-confirm:disabled {
        opacity: 0.75;
        cursor: wait;
    }

    @keyframes adminFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes adminModalIn {
        from { opacity: 0; transform: translateY(14px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>

<script>
    let rawData = @json($dosen);

    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const countWaliSpan = document.getElementById('countWali');
    const countMKSpan = document.getElementById('countMK');
    const csrfToken = "{{ csrf_token() }}";
    const baseUrl = "{{ url('admin/dosen') }}";
    const dosenForm = document.getElementById('dosenForm');
    const dosenFormMethod = document.getElementById('dosenFormMethod');
    const modalTitle = document.getElementById('modal-title');
    const passwordRequiredMark = document.querySelector('.js-password-required');

    function openModal(dosen = null) {
        dosenForm.reset();
        dosenForm.action = dosen ? `${baseUrl}/${dosen.id}` : "{{ route('pages.admin.dosen.store') }}";
        dosenFormMethod.value = dosen ? 'PUT' : 'POST';
        modalTitle.textContent = dosen ? 'Edit Data Dosen' : 'Tambah Dosen Baru';

        const passwordInput = dosenForm.elements.password;
        passwordInput.required = !dosen;
        passwordInput.value = dosen ? '' : 'dosen123';
        passwordInput.placeholder = dosen ? 'Kosongkan jika tidak diubah' : 'dosen123';
        passwordRequiredMark?.classList.toggle('hidden', !!dosen);

        if (dosen) {
            dosenForm.elements.nik.value = dosen.nik || '';
            dosenForm.elements.nama.value = dosen.nama || '';
            dosenForm.elements.email.value = dosen.email || '';
            dosenForm.elements.tipe_dosen.value = normalizeTipeDosen(dosen.tipe_dosen);
            dosenForm.elements.fakultas.value = dosen.fakultas || dosen.prodi || '';
            dosenForm.elements.no_hp.value = dosen.no_hp || '';
            dosenForm.elements.alamat.value = dosen.alamat || '';
        }

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

    function escapeAttr(value) {
        return String(safeText(value))
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function normalizeTipeDosen(value) {
        const tipe = String(value || '').toLowerCase();

        if (
            tipe === 'keduanya' ||
            tipe.includes('wali') ||
            tipe.includes('dosen wali & mata kuliah')
        ) {
            return 'keduanya';
        }

        return 'Dosen Mata Kuliah';
    }

    function displayTipeDosen(value) {
        return normalizeTipeDosen(value) === 'keduanya'
            ? 'Dosen Wali & Mata Kuliah'
            : 'Dosen Mata Kuliah';
    }

    function openDeleteModal(name) {
        if (window.nbConfirmDelete) {
            return window.nbConfirmDelete({
                title: 'Hapus Data Dosen?',
                desc: `Data ${name} akan disembunyikan dari tampilan admin.`,
                button: 'Ya, Hapus',
                icon: 'delete_forever',
            });
        }

        return Promise.resolve(window.confirm(`Hapus data ${name}?`));
    }

    function renderTable(data) {
        tableBody.innerHTML = '';

        if (data.length === 0) {
            emptyState.classList.remove('hidden');
            countWaliSpan.textContent = 0;
            countMKSpan.textContent = 0;
            return;
        }

        emptyState.classList.add('hidden');

        let waliCount = 0;
        let mkCount = 0;

        data.forEach(dsn => {
            const tipeValue = normalizeTipeDosen(dsn.tipe_dosen);
            const tipeLabel = displayTipeDosen(dsn.tipe_dosen);

            if (tipeValue === 'keduanya') {
                waliCount++;
                mkCount++;
            } else {
                mkCount++;
            }

            const row = document.createElement('tr');
            const namaDosen = safeText(dsn.nama);
            const initials = namaDosen
                .split(' ')
                .map(n => n[0])
                .join('')
                .substring(0, 2);

            const badgeClass = tipeValue === 'keduanya' ? 'nb-badge-success' : 'nb-badge-primary';
            const itemName = escapeAttr(namaDosen);

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${safeText(dsn.nik)}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-accent-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                            <span class="text-ink font-extrabold text-xs" style="font-family: var(--font-heading);">${initials}</span>
                        </div>
                        <span class="font-medium text-ink">${namaDosen}</span>
                    </div>
                </td>
                <td class="hidden md:table-cell text-sm text-primary">${safeText(dsn.email)}</td>
                <td class="hidden sm:table-cell text-center">
                    <span class="nb-badge ${badgeClass}">${tipeLabel}</span>
                </td>
                <td class="hidden lg:table-cell text-muted">${safeText(dsn.fakultas || dsn.prodi)}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" class="nb-row-action edit js-edit-dosen" data-id="${dsn.id}" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </button>
                        <button type="button" class="nb-row-action danger js-delete-dosen" data-id="${dsn.id}" data-name="${itemName}" title="Hapus">
                            <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                        </button>
                    </div>
                </td>
            `;

            tableBody.appendChild(row);
        });

        countWaliSpan.textContent = waliCount;
        countMKSpan.textContent = mkCount;
    }

    async function deleteDosen(id, name, button) {
        const confirmed = await openDeleteModal(name);

        if (!confirmed) {
            return;
        }

        button.disabled = true;

        try {
            const response = await fetch(`${baseUrl}/${id}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new URLSearchParams({ _method: 'DELETE' }),
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(result.message || 'Gagal menghapus dosen dari tampilan.');
            }

            rawData = rawData.filter(dsn => String(dsn.id) !== String(id));
            filterTable();

            if (window.nbToast) {
                nbToast(result.message || 'Data dosen berhasil dihapus dari tampilan admin.', 'success');
            } else {
                alert(result.message || 'Data dosen berhasil dihapus dari tampilan admin.');
            }
        } catch (error) {
            button.disabled = false;

            if (window.nbToast) {
                nbToast(error.message || 'Gagal menghapus dosen dari tampilan.', 'error');
            } else {
                alert(error.message || 'Gagal menghapus dosen dari tampilan.');
            }
        }
    }

    function filterTable() {
        const prodi = document.getElementById('filterProdi').value;
        const filtered = rawData.filter(dsn => !prodi || dsn.fakultas === prodi || dsn.prodi === prodi);
        renderTable(filtered);
    }

    document.getElementById('filterProdi').addEventListener('change', filterTable);

    tableBody.addEventListener('click', (event) => {
        const editButton = event.target.closest('.js-edit-dosen');

        if (editButton) {
            const dosen = rawData.find(item => String(item.id) === String(editButton.dataset.id));
            if (dosen) openModal(dosen);
            return;
        }

        const deleteButton = event.target.closest('.js-delete-dosen');

        if (!deleteButton) {
            return;
        }

        deleteDosen(deleteButton.dataset.id, deleteButton.dataset.name || 'dosen ini', deleteButton);
    });

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush