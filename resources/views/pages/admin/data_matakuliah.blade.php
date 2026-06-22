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
                        <th class="hidden md:table-cell">Prodi</th>
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

    <div id="adminDeleteModal" class="admin-delete-backdrop" aria-hidden="true">
        <div class="admin-delete-modal" role="dialog" aria-modal="true" aria-labelledby="adminDeleteTitle">
            <div class="admin-delete-header">
                <div class="admin-delete-icon">
                    <span class="material-symbols-outlined">menu_book</span>
                </div>
                <button type="button" class="admin-delete-close" id="adminDeleteClose" aria-label="Tutup">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="admin-delete-body">
                <span class="admin-delete-badge">Soft Delete</span>
                <h3 id="adminDeleteTitle">Hapus Mata Kuliah dari Tampilan?</h3>
                <p id="adminDeleteMessage">
                    Data mata kuliah tetap disimpan agar riwayat KRS dan nilai mahasiswa tidak hilang.
                </p>
                <div class="admin-delete-target">
                    <span class="material-symbols-outlined">library_books</span>
                    <strong id="adminDeleteName">Mata Kuliah</strong>
                </div>
            </div>

            <div class="admin-delete-footer">
                <button type="button" class="admin-delete-btn admin-delete-cancel" id="adminDeleteCancel">Batal</button>
                <button type="button" class="admin-delete-btn admin-delete-confirm" id="adminDeleteConfirm">
                    <span class="material-symbols-outlined">archive</span>
                    Ya, Hapus Tampilan
                </button>
            </div>
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
                            <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                            <select id="input_prodi" name="prodi" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodis ?? [] as $prodi)
                                    <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>
                                        {{ $prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi')
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

    .mk-semester-row td {
        background: #f1f5f9;
        color: #003b73;
        font-weight: 900;
        font-family: var(--font-heading);
        letter-spacing: 0.03em;
        text-transform: uppercase;
        padding: 14px 20px;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
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
    let rawData = @json($matakuliah ?? []);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    const methodPutField = '<input type="hidden" name="_method" value="PUT">';
    const csrfToken = "{{ csrf_token() }}";

    const storeUrl = "{{ route('pages.admin.matakuliah.store') }}";
    const baseUrl = "{{ url('admin/matakuliah') }}";
    let pendingDeleteResolver = null;

    function openModal() {
        const form = document.getElementById('modalForm');
        document.getElementById('modal-title').innerText = 'Tambah Mata Kuliah Baru';
        form.action = storeUrl;

        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        document.getElementById('input_kode_mk').value = '';
        document.getElementById('input_sks').value = '';
        document.getElementById('input_nama').value = '';
        document.getElementById('input_semester_ke').value = '';
        document.getElementById('input_prodi').value = '';
        document.getElementById('input_dosen_id').value = '';

        const submitBtn = document.querySelector('#modalForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan';
        }

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

        const existing = form.querySelector('input[name="_method"]');

        if (!existing) {
            form.insertAdjacentHTML('afterbegin', methodPutField);
        } else {
            existing.value = 'PUT';
        }

        document.getElementById('input_kode_mk').value = mk.kode_mk || '';
        document.getElementById('input_sks').value = mk.sks || '';
        document.getElementById('input_nama').value = mk.nama || '';
        document.getElementById('input_semester_ke').value = mk.semester_ke || '';
        document.getElementById('input_prodi').value = mk.prodi || '';
        document.getElementById('input_dosen_id').value = mk.dosen_id ?? '';

        const submitBtn = document.querySelector('#modalForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="material-symbols-outlined" style="font-size:16px;">save</span> Update Mata Kuliah';
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

    function openDeleteModal(name) {
        if (window.nbConfirmDelete) {
            return window.nbConfirmDelete({
                title: 'Hapus Mata Kuliah?',
                desc: `Data ${name} akan disembunyikan dari tampilan admin.`,
                button: 'Ya, Hapus',
                icon: 'delete_forever',
            });
        }

        return Promise.resolve(window.confirm(`Hapus data ${name}?`));
    }

    function closeDeleteModal(result = false) {
        document.getElementById('adminDeleteModal').classList.remove('show');
        document.getElementById('adminDeleteModal').setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        if (pendingDeleteResolver) {
            pendingDeleteResolver(result);
            pendingDeleteResolver = null;
        }
    }

    function renderTable(data) {
        tableBody.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        const sortedData = [...data].sort((a, b) => {
            const semesterA = Number(a.semester_ke || 0);
            const semesterB = Number(b.semester_ke || 0);

            if (semesterA !== semesterB) {
                return semesterA - semesterB;
            }

            return String(a.kode_mk || '').localeCompare(String(b.kode_mk || ''));
        });

        const groupedData = sortedData.reduce((groups, mk) => {
            const semester = mk.semester_ke || 'Lainnya';

            if (!groups[semester]) {
                groups[semester] = [];
            }

            groups[semester].push(mk);

            return groups;
        }, {});

        Object.keys(groupedData).forEach(semester => {
            const semesterRow = document.createElement('tr');
            semesterRow.className = 'mk-semester-row';

            semesterRow.innerHTML = `
                <td colspan="7">
                    Semester ${semester}
                </td>
            `;

            tableBody.appendChild(semesterRow);

            groupedData[semester].forEach(mk => {
                const row = document.createElement('tr');
                const itemName = escapeAttr(mk.nama);

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
                        ${safeText(mk.prodi)}
                    </td>

                    <td class="hidden md:table-cell text-muted">
                        ${safeText(mk.dosen_pengampu)}
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" onclick="openEditModal(${mk.id})" class="nb-row-action" title="Edit">
                                <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                            </button>

                            <button type="button" class="nb-row-action danger js-delete-mk" data-id="${mk.id}" data-name="${itemName}" title="Hapus">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            </button>
                        </div>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        });
    }

    async function deleteMatakuliah(id, name, button) {
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
                throw new Error(result.message || 'Gagal menghapus mata kuliah.');
            }

            rawData = rawData.filter(mk => String(mk.id) !== String(id));
            applyFilters();

            if (window.nbToast) {
                nbToast(result.message || 'Data mata kuliah berhasil dihapus dari tampilan admin!', 'success');
            }
        } catch (error) {
            button.disabled = false;

            if (window.nbToast) {
                nbToast(error.message, 'error');
            } else {
                alert(error.message);
            }
        }
    }

    function applyFilters() {
        const searchTerm = (document.getElementById('searchInput')?.value || '').toLowerCase();

        const filtered = rawData.filter(mk => {
            const kode = (mk.kode_mk ?? '').toLowerCase();
            const nama = (mk.nama ?? '').toLowerCase();
            const prodi = (mk.prodi ?? '').toLowerCase();

            return kode.includes(searchTerm) || nama.includes(searchTerm) || prodi.includes(searchTerm);
        });

        renderTable(filtered);
    }

    document.getElementById('searchInput')?.addEventListener('keyup', applyFilters);
    document.getElementById('adminDeleteCancel')?.addEventListener('click', () => closeDeleteModal(false));
    document.getElementById('adminDeleteClose')?.addEventListener('click', () => closeDeleteModal(false));
    document.getElementById('adminDeleteConfirm')?.addEventListener('click', () => closeDeleteModal(true));
    document.getElementById('adminDeleteModal')?.addEventListener('click', event => {
        if (event.target.id === 'adminDeleteModal') closeDeleteModal(false);
    });

    tableBody.addEventListener('click', (event) => {
        const button = event.target.closest('.js-delete-mk');

        if (!button) {
            return;
        }

        deleteMatakuliah(button.dataset.id, button.dataset.name || 'mata kuliah ini', button);
    });

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush