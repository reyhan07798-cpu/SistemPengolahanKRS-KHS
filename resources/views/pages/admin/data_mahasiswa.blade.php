@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Data Mahasiswa')

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
            <h1 class="mt-2">Data Mahasiswa</h1>
            <p>Kelola data mahasiswa terdaftar di sistem akademik.</p>
        </div>
        <button type="button" onclick="openModal()" class="nb-btn nb-btn-primary">
            <span class="material-symbols-outlined" style="font-size:20px;">add</span>
            Tambah Mahasiswa
        </button>
    </div>

    {{-- Filter Card --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter & Pencarian</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="nb-label">Cari Mahasiswa</label>
                <input type="text" id="searchInput" placeholder="Cari NIM atau Nama...">
            </div>
            <div>
                <label class="nb-label">Prodi</label>
                <select id="filterProdi">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="nb-label">Angkatan</label>
                <input type="number" id="filterAngkatan" min="2000" max="2100" step="1" placeholder="Semua Angkatan">
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Daftar Aktif</span>
                <h2 class="mt-1">Mahasiswa Terdaftar</h2>
            </div>
            <span class="nb-badge nb-badge-primary">Total: <span id="totalData">0</span></span>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th class="hidden md:table-cell">Prodi</th>
                        <th class="hidden lg:table-cell text-center whitespace-nowrap">Kelas</th>
                        <th class="hidden sm:table-cell text-center">Angkatan</th>
                        <th class="hidden md:table-cell text-center">IPK</th>
                        <th class="hidden xl:table-cell">Dosen Wali</th>
                        <th class="hidden lg:table-cell">Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">inbox</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data mahasiswa</p>
        </div>
    </div>

    {{-- MODAL TAMBAH MAHASISWA --}}
    <div id="modalOverlay" class="nb-modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="nb-modal" onclick="event.stopPropagation()">
            <div class="nb-modal-header">
                <h3 id="modal-title">Tambah Mahasiswa Baru</h3>
                <button type="button" onclick="closeModal()" class="nb-modal-close" aria-label="Tutup">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                </button>
            </div>

            <form action="{{ route('pages.admin.mahasiswa.store') }}" method="POST" id="mahasiswaForm">
                @csrf
                <input type="hidden" name="_method" id="mahasiswaFormMethod" value="POST">
                <div class="nb-modal-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label class="nb-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" value="{{ old('nim') }}" placeholder="2021001001" required>
                            @error('nim') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" required>
                            @error('nama') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@univ.ac.id"
                                required>
                            @error('email') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi" required>
                                <option value="">Pilih prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="nb-label">Angkatan <span class="text-danger">*</span></label>
                            <input type="number" name="angkatan" value="{{ old('angkatan') }}" min="2000" max="2100"
                                step="1" placeholder="2026" required>
                            @error('angkatan') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Dosen Wali</label>
                            <select name="dosen_wali_id">
                                <option value="">Pilih dosen wali</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_wali_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            @error('dosen_wali_id') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Semester Awal <span class="text-danger">*</span></label>
                            <select name="semester_ke_awal" required>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester_ke_awal', 1) == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester_ke_awal') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Grup Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_grup" required>
                                @foreach($kelasGroups as $group)
                                    <option value="{{ $group }}" {{ old('kelas_grup', 'A') == $group ? 'selected' : '' }}>
                                        {{ $group }}</option>
                                @endforeach
                            </select>
                            @error('kelas_grup') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">Sesi Kelas <span class="text-danger">*</span></label>
                            <select name="sesi_kelas" required>
                                @foreach($sesiOptions as $sesi)
                                    <option value="{{ $sesi }}" {{ old('sesi_kelas', 'PAGI') == $sesi ? 'selected' : '' }}>
                                        {{ $sesi }}</option>
                                @endforeach
                            </select>
                            @error('sesi_kelas') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="nb-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890">
                        </div>

                        <div>
                            <label class="nb-label">Password <span class="text-danger js-password-required">*</span></label>
                            <input type="text" name="password" value="{{ old('password', 'mhs123') }}" placeholder="mhs123"
                                required>
                            @error('password') <p class="nb-form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="nb-label">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
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
        const rawData = @json($mahasiswa);
        const tableBody = document.getElementById('tableBody');
        const emptyState = document.getElementById('emptyState');
        const totalDataSpan = document.getElementById('totalData');
        const mahasiswaForm = document.getElementById('mahasiswaForm');
        const mahasiswaFormMethod = document.getElementById('mahasiswaFormMethod');
        const modalTitle = document.getElementById('modal-title');
        const passwordRequiredMark = document.querySelector('.js-password-required');

        function parseKelas(kelas) {
            const normalized = String(kelas || '').trim().toUpperCase().replace(/\s+/g, '-');
            const match = normalized.match(/^(.+?)(\d{1,2})([A-Z])-(.+)$/);

            return {
                semester: match ? match[2] : '1',
                group: match ? match[3] : 'A',
                sesi: match ? match[4] : 'PAGI',
            };
        }

        function openModal(mahasiswa = null) {
            mahasiswaForm.reset();
            mahasiswaForm.action = mahasiswa
                ? `/admin/mahasiswa/${mahasiswa.id}`
                : "{{ route('pages.admin.mahasiswa.store') }}";
            mahasiswaFormMethod.value = mahasiswa ? 'PUT' : 'POST';
            modalTitle.textContent = mahasiswa ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru';

            const passwordInput = mahasiswaForm.elements.password;
            passwordInput.required = !mahasiswa;
            passwordInput.value = mahasiswa ? '' : 'mhs123';
            passwordInput.placeholder = mahasiswa ? 'Kosongkan jika tidak diubah' : 'mhs123';
            passwordRequiredMark?.classList.toggle('hidden', !!mahasiswa);

            if (mahasiswa) {
                mahasiswaForm.elements.nim.value = mahasiswa.nim || '';
                mahasiswaForm.elements.nama.value = mahasiswa.nama || '';
                mahasiswaForm.elements.email.value = mahasiswa.email || '';
                mahasiswaForm.elements.prodi.value = mahasiswa.prodi || '';
                mahasiswaForm.elements.angkatan.value = mahasiswa.angkatan || '';
                const kelasParts = parseKelas(mahasiswa.kelas);
                mahasiswaForm.elements.semester_ke_awal.value = kelasParts.semester;
                mahasiswaForm.elements.kelas_grup.value = kelasParts.group;
                mahasiswaForm.elements.sesi_kelas.value = kelasParts.sesi;
                mahasiswaForm.elements.dosen_wali_id.value = mahasiswa.dosen_wali_id || '';
                mahasiswaForm.elements.no_hp.value = mahasiswa.no_hp || '';
                mahasiswaForm.elements.alamat.value = mahasiswa.alamat || '';
            } else {
                mahasiswaForm.elements.semester_ke_awal.value = '1';
                mahasiswaForm.elements.kelas_grup.value = 'A';
                mahasiswaForm.elements.sesi_kelas.value = 'PAGI';
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

        function escapeAttribute(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        @if(old('_token') || $errors->any())
            document.addEventListener('DOMContentLoaded', () => { openModal(); });
        @endif

            function renderTable(data) {
                tableBody.innerHTML = '';
                if (data.length === 0) {
                    emptyState.classList.remove('hidden');
                    totalDataSpan.textContent = '0';
                    return;
                }
                emptyState.classList.add('hidden');
                totalDataSpan.textContent = data.length;

                data.forEach(mhs => {
                    const row = document.createElement('tr');
                    const deleteUrl = `/admin/mahasiswa/${mhs.id}`;
                    const nama = mhs.nama || '-';
                    const nim = mhs.nim || '-';
                    const prodi = mhs.prodi || '-';
                    const kelas = mhs.kelas || '-';
                    const angkatan = mhs.angkatan || '-';
                    const dosenWali = mhs.dosen_wali || '-';
                    const email = mhs.email || '-';
                    const ipk = Number(mhs.ipk || 0).toFixed(2);
                    const initials = nama.split(' ').map(n => n[0]).join('').substring(0, 2);

                    row.innerHTML = `
                    <td class="font-bold text-primary" style="font-family: var(--font-heading);">${nim}</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                                <span class="text-primary font-extrabold text-xs" style="font-family: var(--font-heading);">${initials}</span>
                            </div>
                            <span class="font-medium text-ink">${nama}</span>
                        </div>
                    </td>
                    <td class="hidden md:table-cell text-muted">${prodi}</td>
                    <td class="hidden lg:table-cell text-center whitespace-nowrap"><span class="nb-badge nb-badge-stable whitespace-nowrap">${kelas}</span></td>
                    <td class="hidden sm:table-cell text-center text-muted">${angkatan}</td>
                    <td class="hidden md:table-cell text-center"><span class="nb-badge nb-badge-primary">${ipk}</span></td>
                    <td class="hidden xl:table-cell text-muted text-sm">${dosenWali}</td>
                    <td class="hidden lg:table-cell text-sm text-primary">${email}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" class="nb-row-action edit js-edit-mahasiswa" data-id="${mhs.id}" title="Edit">
                                <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                            </button>
                            <button type="button" class="nb-row-action danger js-delete-mahasiswa" title="Hapus" data-url="${deleteUrl}" data-name="${escapeAttribute(nama)}">
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                            </button>
                        </div>
                    </td>
                `;
                    tableBody.appendChild(row);
                });
            }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const prodiFilter = document.getElementById('filterProdi').value;
            const angkatanFilter = document.getElementById('filterAngkatan').value;
            const filtered = rawData.filter(mhs => {
                const nim = String(mhs.nim || '').toLowerCase();
                const nama = String(mhs.nama || '').toLowerCase();
                const matchSearch = nim.includes(searchTerm) || nama.includes(searchTerm);
                const matchProdi = !prodiFilter || mhs.prodi === prodiFilter;
                const matchAngkatan = !angkatanFilter || String(mhs.angkatan) === String(angkatanFilter);
                return matchSearch && matchProdi && matchAngkatan;
            });
            renderTable(filtered);
        }

        document.getElementById('searchInput').addEventListener('keyup', applyFilters);
        document.getElementById('filterProdi').addEventListener('change', applyFilters);
        document.getElementById('filterAngkatan').addEventListener('input', applyFilters);
        tableBody.addEventListener('click', (event) => {
            const editButton = event.target.closest('.js-edit-mahasiswa');
            if (editButton) {
                const mahasiswa = rawData.find(item => String(item.id) === String(editButton.dataset.id));
                if (mahasiswa) openModal(mahasiswa);
                return;
            }

            const button = event.target.closest('.js-delete-mahasiswa');

            if (!button) {
                return;
            }

            deleteData(
                button.dataset.url,
                'Hapus Data Mahasiswa?',
                'Data mahasiswa akan disembunyikan dari tampilan admin.',
                button.dataset.name || 'mahasiswa ini'
            );
        });

        document.addEventListener('DOMContentLoaded', () => {
            renderTable(rawData);
        });
    </script>
@endpush