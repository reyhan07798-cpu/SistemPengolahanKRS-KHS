@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-slide-up">
        <div>
            <h2 class="font-display text-2xl lg:text-3xl font-bold text-jet-black-900">Data Dosen</h2>
            <p class="text-jet-black-500 mt-1">Kelola data dosen pengajar dan wali</p>
        </div>
        
        <!-- Tombol Buka Modal -->
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sidebar hover:bg-sidebar-hover text-white font-semibold rounded-xl transition-all shadow-lg shadow-sidebar/20 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Dosen
        </button>
    </div>

    <!-- Stats Cards (Seperti sebelumnya) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 lg:p-6 border border-tea-green-100 animate-slide-up delay-1">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-jet-black-500 text-sm font-medium">Dosen Wali</p>
                    <p class="text-3xl lg:text-4xl font-display font-bold text-jet-black-900 mt-2" id="countWali">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-tea-green-200 to-tea-green-300 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-sidebar" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="stat-card bg-white rounded-2xl p-5 lg:p-6 border border-tea-green-100 animate-slide-up delay-2">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-jet-black-500 text-sm font-medium">Dosen Mata Kuliah</p>
                    <p class="text-3xl lg:text-4xl font-display font-bold text-jet-black-900 mt-2" id="countMK">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-cerulean-200 to-cerulean-300 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cerulean-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-tea-green-100 overflow-hidden animate-slide-up delay-3 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-tea-green-50 border-b border-tea-green-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">NIK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden md:table-cell">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden sm:table-cell">Tipe Dosen</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden lg:table-cell">Fakultas</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-tea-green-100"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <p class="text-jet-black-500 font-medium">Tidak ada data dosen</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH DOSEN -->
    <!-- ========================================== -->
    <div id="modalOverlay" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-jet-black-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-tea-green-100">
                    <h3 class="text-xl font-display font-bold text-jet-black-900" id="modal-title">Tambah Dosen Baru</h3>
                    <button onclick="closeModal()" class="p-2 text-jet-black-400 hover:text-jet-black-600 hover:bg-jet-black-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form action="{{ route('pages.admin.dosen.store') }}" method="POST" class="mt-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        
                        <!-- NIP -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">NIP <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" placeholder="198501012020011001" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 @error('nik') border-red-500 @enderror" required>
                            @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

                        <!-- Tipe Dosen -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Tipe Dosen <span class="text-red-500">*</span></label>
                            <select name="tipe_dosen" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih tipe dosen</option>
                                <option value="Dosen Wali" {{ old('tipe_dosen') == 'Dosen Wali' ? 'selected' : '' }}>Dosen Wali</option>
                                <option value="Dosen Mata Kuliah" {{ old('tipe_dosen') == 'Dosen Mata Kuliah' ? 'selected' : '' }}>Dosen Mata Kuliah</option>
                            </select>
                        </div>

                        <!-- Fakultas -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
                            <select name="fakultas" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih fakultas</option>
                                @foreach($fakultasList as $fakultas)
                                    <option value="{{ $fakultas }}" {{ old('fakultas') == $fakultas ? 'selected' : '' }}>{{ $fakultas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Alamat -->
                        <div class="text-left md:col-span-2">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400">{{ old('alamat') }}</textarea>
                        </div>

                        <!-- Password Default -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Password Default <span class="text-red-500">*</span></label>
                            <input type="text" name="password" value="{{ old('password', 'dosen123') }}" placeholder="dosen123" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 @error('password') border-red-500 @enderror" required>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

@endsection

@push('scripts')
<script>
    const rawData = @json($dosen);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const countWaliSpan = document.getElementById('countWali');
    const countMKSpan = document.getElementById('countMK');

    // Modal Functions
    function openModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    @if(old('_token') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => { openModal(); });
    @endif

    // Table Render
    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        let waliCount = 0;
        let mkCount = 0;

        data.forEach(dsn => {
            if(dsn.tipe_dosen === 'Dosen Wali') waliCount++; else mkCount++;
            
            const row = document.createElement('tr');
            row.className = 'table-row hover:bg-tea-green-50/50 transition-colors';
            
            const editUrl = `/admin/dosen/${dsn.id}/edit`;
            const deleteUrl = `/admin/dosen/${dsn.id}`;

            const badgeClass = dsn.tipe_dosen === 'Dosen Wali' 
                ? 'bg-tea-green-100 text-tea-green-700' 
                : 'bg-cerulean-100 text-cerulean-700';

            row.innerHTML = `
                <td class="px-6 py-4 text-sm font-medium text-jet-black-700">${dsn.nik}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-muted-teal-300 to-tropical-teal-400 flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-medium text-xs">${dsn.nama.split(' ').map(n => n[0]).join('').substring(0,2)}</span>
                        </div>
                        <span class="font-medium text-jet-black-800">${dsn.nama}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden md:table-cell">${dsn.email}</td>
                <td class="px-6 py-4 text-sm hidden sm:table-cell">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg ${badgeClass}">${dsn.tipe_dosen}</span>
                </td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden lg:table-cell">${dsn.fakultas}</td>
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

        countWaliSpan.textContent = waliCount;
        countMKSpan.textContent = mkCount;
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush