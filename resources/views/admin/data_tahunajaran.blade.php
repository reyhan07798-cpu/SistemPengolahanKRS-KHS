@extends('layouts.app')

@section('title', 'Data Tahun Ajaran')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-slide-up">
        <div>
            <h2 class="font-display text-2xl lg:text-3xl font-bold text-jet-black-900">Data Tahun Ajaran</h2>
            <p class="text-jet-black-500 mt-1">Kelola periode tahun ajaran akademik</p>
        </div>
        
        <!-- Tombol Buka Modal -->
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sidebar hover:bg-sidebar-hover text-white font-semibold rounded-xl transition-all shadow-lg shadow-sidebar/20 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Tahun Ajaran
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-tea-green-100 overflow-hidden animate-slide-up delay-1 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-tea-green-50 border-b border-tea-green-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-tea-green-100"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-tea-green-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-jet-black-500 font-medium">Tidak ada data tahun ajaran</p>
        </div>
        <div class="px-6 py-4 border-t border-tea-green-100 flex items-center justify-between">
            <p class="text-sm text-jet-black-500">Total: <span id="totalData" class="font-medium text-jet-black-700">0</span> periode</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH TAHUN AJARAN -->
    <!-- ========================================== -->
    <div id="modalOverlay" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-jet-black-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-tea-green-100">
                    <h3 class="text-xl font-display font-bold text-jet-black-900" id="modal-title">Tambah Semester Baru</h3>
                    <button onclick="closeModal()" class="p-2 text-jet-black-400 hover:text-jet-black-600 hover:bg-jet-black-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Form Body -->
                <form action="{{ route('admin.tahunajaran.store') }}" method="POST" class="mt-6">
                    @csrf
                    
                    <div class="space-y-5">
                        
                        <!-- Nama Semester -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Nama Semester</label>
                            <select name="semester" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer">
                                <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>

                        <!-- Tahun Ajaran -->
                        <div class="text-left">
                            <label class="block text-sm font-medium text-jet-black-700 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                            <select name="tahun_ajaran" class="w-full px-4 py-2.5 bg-tea-green-50 border border-tea-green-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-tea-green-400 cursor-pointer" required>
                                <option value="">Pilih tahun ajaran</option>
                                @foreach($tahunOptions as $tahun)
                                    <option value="{{ $tahun }}" {{ old('tahun_ajaran') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Set sebagai Aktif (Switch) -->
                        <div class="text-left">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm font-medium text-jet-black-700">Set sebagai Aktif</span>
                                <div class="relative">
                                    <input type="checkbox" name="status" value="Aktif" class="sr-only peer" {{ old('status') ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-tea-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-tea-green-500"></div>
                                </div>
                            </label>
                            <p class="text-xs text-jet-black-400 mt-1">Jika diaktifkan, semester ini akan menjadi periode aktif saat ini.</p>
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
            row.className = 'table-row hover:bg-tea-green-50/50 transition-colors';
            
            const editUrl = `/admin/tahun-ajaran/${ta.id}/edit`;
            const deleteUrl = `/admin/tahun-ajaran/${ta.id}`;

            const statusClass = ta.status === 'Aktif' 
                ? 'bg-tea-green-100 text-tea-green-700 border border-tea-green-200' 
                : 'bg-jet-black-50 text-jet-black-500 border border-jet-black-100';

            const semesterClass = ta.semester === 'Ganjil' 
                ? 'bg-cerulean-50 text-cerulean-700 border border-cerulean-100' 
                : 'bg-tropical-teal-50 text-tropical-teal-700 border border-tropical-teal-100';

            row.innerHTML = `
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg ${semesterClass}">${ta.semester}</span>
                </td>
                <td class="px-6 py-4 text-sm font-bold text-jet-black-800">${ta.tahun_ajaran}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">${ta.status}</span>
                </td>
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