@extends('layouts.app')

@section('title', 'Kelola Paket Matakuliah')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-slide-up">
        <div>
            <h2 class="font-display text-2xl lg:text-3xl font-bold text-jet-black-900">Kelola Paket Matakuliah</h2>
            <p class="text-jet-black-500 mt-1">Daftar paket mata kuliah per semester</p>
        </div>
        
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sidebar hover:bg-sidebar-hover text-white font-semibold rounded-xl transition-all shadow-lg shadow-sidebar/20 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Paket
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-tea-green-100 overflow-hidden animate-slide-up delay-1 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-tea-green-50 border-b border-tea-green-100">
                        <th class="px-4 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Nama Paket</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Semester</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden md:table-cell">Prodi</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Total SKS</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Jumlah MK</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase hidden lg:table-cell">Deskripsi</th>
                        <th class="px-4 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-tea-green-100"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <p class="text-jet-black-500 font-medium">Tidak ada data paket</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH PAKET MK -->
    <!-- ========================================== -->
    <div id="modalOverlay" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-jet-black-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-tea-green-100">
                    <h3 class="text-xl font-display font-bold text-jet-black-900" id="modal-title">Tambah Paket Baru</h3>
                    <button onclick="closeModal()" class="p-2 text-jet-black-400 hover:text-jet-black-600 hover:bg-jet-black-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form action="{{ route('admin.paketmk.store') }}" method="POST" class="mt-6">
                    @csrf
                    
                    <div class="space-y-5">
                        
                        <!-- Nama Paket -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Nama Paket <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" placeholder="Paket Normal Semester 3" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Semester -->
                            <div class="text-left">
                                <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Semester <span class="text-red-500">*</span></label>
                                <select name="semester" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                    @foreach($semesters as $s)
                                        <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Prodi -->
                            <div class="text-left">
                                <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Prodi</label>
                                <select name="prodi" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer">
                                    @foreach($prodis as $p)
                                        <option value="{{ $p }}" {{ old('prodi') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Mata Kuliah dalam Paket -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Mata Kuliah dalam Paket <span class="text-red-500">*</span></label>
                            <div class="bg-tea-green-50 border border-tea-green-200 rounded-xl p-3 max-h-48 overflow-y-auto space-y-2">
                                @foreach($allMataKuliah as $mk)
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded-lg transition-colors">
                                        <input type="checkbox" name="mata_kuliah[]" value="{{ $mk->id }}" data-sks="{{ $mk->sks }}" class="w-4 h-4 text-tea-green-600 bg-gray-100 border-gray-300 rounded focus:ring-tea-green-500 mk-checkbox">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-jet-black-800">{{ $mk->nama }}</span>
                                            <span class="text-xs text-jet-black-400 ml-2">({{ $mk->sks }} SKS)</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <!-- Counter -->
                            <div class="flex justify-between items-center mt-2 px-1">
                                <span class="text-xs font-medium text-jet-black-600">Terpilih: <span id="selectedCount">0</span> MK</span>
                                <span class="text-xs font-bold text-sidebar">Total: <span id="totalSks">0</span> SKS</span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat paket..." class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400">{{ old('deskripsi') }}</textarea>
                        </div>

                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-tea-green-100">
                        <button type="button" onclick="closeModal()" class="px-6 py-2.5 text-sm font-semibold text-jet-black-600 bg-white border border-jet-black-200 rounded-xl hover:bg-jet-black-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-sidebar hover:bg-sidebar-hover rounded-xl shadow-sm transition-colors">
                            Tambah Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const rawData = @json($paketMK);
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    // Modal Functions
    function openModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateCounters(); // Reset counter saat buka modal
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    @if(old('_token') || $errors->any())
        document.addEventListener('DOMContentLoaded', () => { openModal(); });
    @endif

    // Counter Logic
    const checkboxes = document.querySelectorAll('.mk-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const totalSksSpan = document.getElementById('totalSks');

    function updateCounters() {
        let count = 0;
        let sks = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                count++;
                sks += parseInt(cb.dataset.sks);
            }
        });
        selectedCountSpan.textContent = count;
        totalSksSpan.textContent = sks;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCounters);
    });

    // Table Render
    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) { emptyState.classList.remove('hidden'); return; }
        emptyState.classList.add('hidden');

        data.forEach(pk => {
            const row = document.createElement('tr');
            row.className = 'table-row hover:bg-tea-green-50/50 transition-colors';
            
            const editUrl = `/admin/paket-mk/${pk.id}/edit`;
            const deleteUrl = `/admin/paket-mk/${pk.id}`;

            row.innerHTML = `
                <td class="px-4 py-4 text-sm font-bold text-sidebar font-display">${pk.nama_paket}</td>
                <td class="px-4 py-4 text-center">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-tropical-teal-50 text-tropical-teal-700 border border-tropical-teal-100">Sem ${pk.semester}</span>
                </td>
                <td class="px-4 py-4 text-sm text-jet-black-600 hidden md:table-cell">${pk.prodi}</td>
                <td class="px-4 py-4 text-center">
                    <span class="font-bold text-jet-black-800">${pk.total_sks}</span>
                    <span class="text-xs text-jet-black-400">SKS</span>
                </td>
                <td class="px-4 py-4 text-center">
                    <span class="font-bold text-jet-black-800">${pk.jumlah_mk}</span>
                    <span class="text-xs text-jet-black-400">MK</span>
                </td>
                <td class="px-4 py-4 text-xs text-jet-black-500 hidden lg:table-cell max-w-xs truncate">${pk.deskripsi || '-'}</td>
                <td class="px-4 py-4 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="${editUrl}" class="p-2 text-muted-teal-600 hover:bg-muted-teal-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
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