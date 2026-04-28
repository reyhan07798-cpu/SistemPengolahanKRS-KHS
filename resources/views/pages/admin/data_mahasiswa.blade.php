@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-slide-up">
        <div>
            <h2 class="font-display text-2xl lg:text-3xl font-bold text-jet-black-900">Data Mahasiswa</h2>
            <p class="text-jet-black-500 mt-1">Kelola data mahasiswa terdaftar</p>
        </div>
        
        <!-- Tombol Buka Modal -->
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sidebar hover:bg-sidebar-hover text-white font-semibold rounded-xl transition-all shadow-lg shadow-sidebar/20 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Mahasiswa
        </button>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-2xl border border-tea-green-100 p-6 mb-6 animate-slide-up delay-1">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-jet-black-600 mb-1">Cari Mahasiswa</label>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari NIM atau Nama..." class="w-full pl-10 pr-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-tea-green-400">
                    <svg class="w-5 h-5 text-jet-black-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-jet-black-600 mb-1">Prodi</label>
                <select id="filterProdi" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)<option value="{{ $prodi }}">{{ $prodi }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-jet-black-600 mb-1">Angkatan</label>
                <select id="filterAngkatan" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatans as $angkatan)<option value="{{ $angkatan }}">{{ $angkatan }}</option>@endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-tea-green-100 overflow-hidden animate-slide-up delay-2 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-tea-green-50 border-b border-tea-green-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">NIM</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden md:table-cell">Prodi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden lg:table-cell">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden sm:table-cell">Angkatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden xl:table-cell">Dosen Wali</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden lg:table-cell">Email</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-tea-green-100"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <p class="text-jet-black-500 font-medium">Tidak ada data mahasiswa</p>
        </div>
        <div class="px-6 py-4 border-t border-tea-green-100 flex items-center justify-between">
            <p class="text-sm text-jet-black-500">Total: <span id="totalData" class="font-medium text-jet-black-700">0</span> mahasiswa</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH MAHASISWA (SESUAI GAMBAR) -->
    <!-- ========================================== -->
    <div id="modalOverlay" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-jet-black-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-tea-green-100">
                    <h3 class="text-xl font-display font-bold text-jet-black-900" id="modal-title">Tambah Mahasiswa Baru</h3>
                    <button onclick="closeModal()" class="p-2 text-jet-black-400 hover:text-jet-black-600 hover:bg-jet-black-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form action="{{ route('pages.admin.mahasiswa.store') }}" method="POST" class="mt-6">
                    @csrf
                    
                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        
                        <!-- NIM -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" value="{{ old('nim') }}" placeholder="2021001001" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 @error('nim') border-red-500 @enderror" required>
                            @error('nim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 @error('nama') border-red-500 @enderror" required>
                            @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="text-left md:col-span-2">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@univ.ac.id" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 @error('email') border-red-500 @enderror" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Program Studi -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                            <select name="prodi" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Angkatan -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Angkatan <span class="text-red-500">*</span></label>
                            <select name="angkatan" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih Angkatan</option>
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan }}" {{ old('angkatan') == $angkatan ? 'selected' : '' }}>{{ $angkatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kelas -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                            <select name="kelas" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih kelas</option>
                                <option value="A" {{ old('kelas') == 'A' ? 'selected' : '' }}>Kelas A</option>
                                <option value="B" {{ old('kelas') == 'B' ? 'selected' : '' }}>Kelas B</option>
                                <option value="C" {{ old('kelas') == 'C' ? 'selected' : '' }}>Kelas C</option>
                            </select>
                        </div>

                        <!-- Dosen Wali -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Dosen Wali <span class="text-red-500">*</span></label>
                            <select name="dosen_wali" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih dosen wali</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}" {{ old('dosen_wali') == $dosen->nama ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- No. HP -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400">
                        </div>

                        <!-- Password Default -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Password Default <span class="text-red-500">*</span></label>
                            <input type="text" name="password" value="{{ old('password', 'mhs123') }}" placeholder="mhs123" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 @error('password') border-red-500 @enderror" required>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="text-left md:col-span-2">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-tea-green-100">
                        <button type="button" onclick="closeModal()" class="px-6 py-2.5 text-sm font-semibold text-jet-black-600 bg-white border border-jet-black-200 rounded-xl hover:bg-jet-black-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-tea-green-500 hover:bg-tea-green-600 rounded-xl shadow-sm transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODAL -->

@endsection

@push('scripts')
<script>
    const rawData = @json($mahasiswa);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const totalDataSpan = document.getElementById('totalData');

    // ==========================================
    // MODAL FUNCTIONS
    // ==========================================
    function openModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Check if there are old input or errors, open modal automatically
    @if(old('_token') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            openModal();
        });
    @endif

    // ==========================================
    // TABLE FUNCTIONS
    // ==========================================
    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); totalDataSpan.textContent = '0'; return; }
        emptyState.classList.add('hidden');
        totalDataSpan.textContent = data.length;

        data.forEach(mhs => {
            const row = document.createElement('tr');
            row.className = 'table-row hover:bg-tea-green-50/50 transition-colors';
            
            const editUrl = `/admin/mahasiswa/${mhs.id}/edit`;
            const deleteUrl = `/admin/mahasiswa/${mhs.id}`;

            row.innerHTML = `
                <td class="px-6 py-4 text-sm font-medium text-jet-black-700">${mhs.nim}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-cerulean-300 to-tropical-teal-400 flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-medium text-xs">${mhs.nama.split(' ').map(n => n[0]).join('').substring(0,2)}</span>
                        </div>
                        <span class="font-medium text-jet-black-800">${mhs.nama}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden md:table-cell">${mhs.prodi}</td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden lg:table-cell"><span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-tea-green-100 text-tea-green-700">${mhs.kelas}</span></td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden sm:table-cell">${mhs.angkatan}</td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden xl:table-cell">${mhs.dosen_wali}</td>
                <td class="px-6 py-4 text-sm text-cerulean-600 hidden lg:table-cell">${mhs.email}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="${editUrl}" class="p-2 text-muted-teal-600 hover:bg-muted-teal-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
        const prodiFilter = document.getElementById('filterProdi').value;
        const angkatanFilter = document.getElementById('filterAngkatan').value;
        const filtered = rawData.filter(mhs => {
            const matchSearch = mhs.nim.toLowerCase().includes(searchTerm) || mhs.nama.toLowerCase().includes(searchTerm);
            const matchProdi = !prodiFilter || mhs.prodi === prodiFilter;
            const matchAngkatan = !angkatanFilter || mhs.angkatan === angkatanFilter;
            return matchSearch && matchProdi && matchAngkatan;
        });
        renderTable(filtered);
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilters);
    document.getElementById('filterProdi').addEventListener('change', applyFilters);
    document.getElementById('filterAngkatan').addEventListener('change', applyFilters);

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush