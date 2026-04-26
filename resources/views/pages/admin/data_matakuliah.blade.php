@extends('layouts.app')

@section('title', 'Data Mata Kuliah')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-slide-up">
        <div>
            <h2 class="font-display text-2xl lg:text-3xl font-bold text-jet-black-900">Data Mata Kuliah</h2>
            <p class="text-jet-black-500 mt-1">Daftar mata kuliah yang tersedia</p>
        </div>
        
        <!-- Tombol Buka Modal -->
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sidebar hover:bg-sidebar-hover text-white font-semibold rounded-xl transition-all shadow-lg shadow-sidebar/20 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Mata Kuliah
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-tea-green-100 overflow-hidden animate-slide-up delay-1 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-tea-green-50 border-b border-tea-green-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">SKS</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden sm:table-cell">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden md:table-cell">Dosen Pengampu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden lg:table-cell">Jadwal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-tea-green-100"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-tea-green-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <p class="text-jet-black-500 font-medium">Tidak ada data mata kuliah</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH MATA KULIAH -->
    <!-- ========================================== -->
    <div id="modalOverlay" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-jet-black-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-tea-green-100">
                    <h3 class="text-xl font-display font-bold text-jet-black-900" id="modal-title">Tambah Mata Kuliah Baru</h3>
                    <button onclick="closeModal()" class="p-2 text-jet-black-400 hover:text-jet-black-600 hover:bg-jet-black-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form action="{{ route('pages.admin.matakuliah.store') }}" method="POST" class="mt-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        
                        <!-- Kode MK -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Kode MK <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" value="{{ old('kode') }}" placeholder="IF101" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400" required>
                        </div>

                        <!-- SKS -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">SKS <span class="text-red-500">*</span></label>
                            <select name="sks" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih SKS</option>
                                <option value="1" {{ old('sks') == '1' ? 'selected' : '' }}>1 SKS</option>
                                <option value="2" {{ old('sks') == '2' ? 'selected' : '' }}>2 SKS</option>
                                <option value="3" {{ old('sks') == '3' ? 'selected' : '' }}>3 SKS</option>
                                <option value="4" {{ old('sks') == '4' ? 'selected' : '' }}>4 SKS</option>
                            </select>
                        </div>

                        <!-- Nama MK -->
                        <div class="text-left md:col-span-2">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400" required>
                        </div>

                        <!-- Semester -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Semester <span class="text-red-500">*</span></label>
                            <select name="semester" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesters as $s)
                                    <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kapasitas -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Kapasitas</label>
                            <input type="number" name="kapasitas" value="{{ old('kapasitas', 40) }}" placeholder="40" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400">
                        </div>

                        <!-- Dosen Pengampu -->
                        <div class="text-left md:col-span-2">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Dosen Pengampu <span class="text-red-500">*</span></label>
                            <select name="dosen_pengampu" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih dosen pengampu</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}" {{ old('dosen_pengampu') == $dosen->nama ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hari -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Hari <span class="text-red-500">*</span></label>
                            <select name="hari" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih Hari</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jam -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Jam <span class="text-red-500">*</span></label>
                            <input type="text" name="jam" value="{{ old('jam', '07:00 - 08:40') }}" placeholder="07:00 - 08:40" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400" required>
                        </div>

                        <!-- Ruang -->
                        <div class="text-left md:col-span-2">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Ruang <span class="text-red-500">*</span></label>
                            <input type="text" name="ruang" value="{{ old('ruang') }}" placeholder="Lab Komputer 1" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400" required>
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

    @if(old('_token') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => { openModal(); });
    @endif

    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        data.forEach(mk => {
            const row = document.createElement('tr');
            row.className = 'table-row hover:bg-tea-green-50/50 transition-colors';
            
            const editUrl = `/admin/matakuliah/${mk.id}/edit`;
            const deleteUrl = `/admin/matakuliah/${mk.id}`;

            row.innerHTML = `
                <td class="px-6 py-4 text-sm font-bold text-sidebar font-display">${mk.kode}</td>
                <td class="px-6 py-4 text-sm font-medium text-jet-black-800">${mk.nama}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-cerulean-100 text-cerulean-700">${mk.sks} SKS</span>
                </td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden sm:table-cell">Semester ${mk.semester}</td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden md:table-cell">${mk.dosen_pengampu}</td>
                <td class="px-6 py-4 text-sm text-jet-black-600 hidden lg:table-cell">${mk.jadwal}</td>
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

    document.addEventListener('DOMContentLoaded', () => {
        renderTable(rawData);
    });
</script>
@endpush