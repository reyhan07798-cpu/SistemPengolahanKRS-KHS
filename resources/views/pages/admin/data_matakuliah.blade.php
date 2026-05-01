@extends('layouts.admin')

@section('title', 'Data Mata Kuliah')
@section('page_title', 'Data Mata Kuliah')

@section('content')
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
                        <th class="hidden lg:table-cell">Jadwal</th>
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

            <form action="{{ route('pages.admin.matakuliah.store') }}" method="POST">
                @csrf
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="nb-label">Kode MK <span class="text-danger">*</span></label>
                            <input type="text" name="kode" value="{{ old('kode') }}" placeholder="IF101" required>
                        </div>

                        <div>
                            <label class="nb-label">SKS <span class="text-danger">*</span></label>
                            <select name="sks" required>
                                <option value="">Pilih SKS</option>
                                <option value="1" {{ old('sks') == '1' ? 'selected' : '' }}>1 SKS</option>
                                <option value="2" {{ old('sks') == '2' ? 'selected' : '' }}>2 SKS</option>
                                <option value="3" {{ old('sks') == '3' ? 'selected' : '' }}>3 SKS</option>
                                <option value="4" {{ old('sks') == '4' ? 'selected' : '' }}>4 SKS</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Pemrograman Web" required>
                        </div>

                        <div>
                            <label class="nb-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesters as $s)
                                    <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Kapasitas</label>
                            <input type="number" name="kapasitas" value="{{ old('kapasitas', 40) }}" placeholder="40">
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Dosen Pengampu <span class="text-danger">*</span></label>
                            <select name="dosen_pengampu" required>
                                <option value="">Pilih dosen pengampu</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}" {{ old('dosen_pengampu') == $dosen->nama ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Hari <span class="text-danger">*</span></label>
                            <select name="hari" required>
                                <option value="">Pilih Hari</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Jam <span class="text-danger">*</span></label>
                            <input type="text" name="jam" value="{{ old('jam', '07:00 - 08:40') }}" placeholder="07:00 - 08:40" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Ruang <span class="text-danger">*</span></label>
                            <input type="text" name="ruang" value="{{ old('ruang') }}" placeholder="Lab Komputer 1" required>
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
    const rawData = @json($matakuliah);
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

        data.forEach(mk => {
            const row = document.createElement('tr');
            const editUrl = `/admin/matakuliah/${mk.id}/edit`;
            const deleteUrl = `/admin/matakuliah/${mk.id}`;

            row.innerHTML = `
                <td class="font-bold text-primary" style="font-family: var(--font-heading);">${mk.kode}</td>
                <td class="font-medium text-ink">${mk.nama}</td>
                <td class="text-center"><span class="nb-badge nb-badge-primary">${mk.sks} SKS</span></td>
                <td class="hidden sm:table-cell text-muted">Semester ${mk.semester}</td>
                <td class="hidden md:table-cell text-muted">${mk.dosen_pengampu}</td>
                <td class="hidden lg:table-cell text-sm text-muted">${mk.jadwal}</td>
                <td class="text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="${editUrl}" class="nb-row-action edit" title="Edit">
                            <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                        </a>
                        <form action="${deleteUrl}" method="POST" data-nb-confirm="true" data-nb-confirm-title="Hapus Mata Kuliah?" data-nb-confirm-desc="Tindakan ini tidak dapat dibatalkan. Mata kuliah ini akan hilang dari paket KRS." data-nb-confirm-button="Ya, Hapus" data-nb-confirm-icon="delete_forever" class="inline">
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
