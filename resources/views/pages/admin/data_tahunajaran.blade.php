@extends('layouts.admin')

@section('title', 'Data Tahun Ajaran')
@section('page_title', 'Tahun Ajaran')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Data Tahun Ajaran</h1>
            <p>Kelola periode tahun ajaran akademik aktif & nonaktif.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Tahun Ajaran
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
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Periode Akademik</span>
                <h2 class="mt-1">Daftar Tahun Ajaran</h2>
            </div>
            <span class="nb-badge nb-badge-primary">Total: <span id="totalData">0</span> Periode</span>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">event</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data tahun ajaran</p>
        </div>
    </div>

    {{-- MODAL TAMBAH TAHUN AJARAN --}}
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" style="max-width: 28rem;" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Semester Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="{{ route('pages.admin.tahunajaran.store') }}" method="POST">
                @csrf
                <div class="nb-modal-body">
                    <div class="space-y-5">
                        <div>
                            <label class="nb-label">Nama Semester</label>
                            <select name="semester">
                                <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select name="tahun_ajaran" required>
                                <option value="">Pilih tahun ajaran</option>
                                @foreach($tahunOptions as $tahun)
                                    <option value="{{ $tahun }}" {{ old('tahun_ajaran') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-surface-alt border-2 border-ink rounded-md p-4">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <span class="nb-label" style="margin-bottom:2px;">Set sebagai Aktif</span>
                                    <p class="text-xs text-muted">Periode aktif saat ini.</p>
                                </div>
                                <div class="relative">
                                    <input type="checkbox" name="status" value="Aktif" class="nb-no-style sr-only peer" {{ old('status') ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-surface border-2 border-ink rounded-full peer-checked:bg-accent transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-ink after:border-2 after:border-ink after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6 peer-checked:after:bg-white"></div>
                                </div>
                            </label>
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
    const rawData = @json($tahunAjaran);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const totalDataSpan = document.getElementById('totalData');

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
        if (data.length === 0) { emptyState.classList.remove('hidden'); totalDataSpan.textContent = '0'; return; }
        emptyState.classList.add('hidden');
        totalDataSpan.textContent = data.length;

        data.forEach(ta => {
            const row = document.createElement('tr');
            const editUrl = `/admin/tahun-ajaran/${ta.id}/edit`;
            const deleteUrl = `/admin/tahun-ajaran/${ta.id}`;
            const statusBadge = ta.status === 'Aktif' ? 'nb-badge-success' : 'nb-badge-stable';
            const semesterBadge = ta.semester === 'Ganjil' ? 'nb-badge-primary' : 'nb-badge-warning';

            row.innerHTML = `
                <td><span class="nb-badge ${semesterBadge}">${ta.semester}</span></td>
                <td class="font-bold text-ink" style="font-family: var(--font-heading);">${ta.tahun_ajaran}</td>
                <td class="text-center"><span class="nb-badge ${statusBadge}">${ta.status}</span></td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Tahun Ajaran?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Pastikan tidak ada KRS aktif di periode ini." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
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
