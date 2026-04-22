@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8 animate-slide-up">
        <h2 class="font-display text-2xl lg:text-3xl font-bold text-jet-black-900">Selamat Datang, Admin</h2>
        <p class="text-jet-black-500 mt-1">Berikut ringkasan data akademik hari ini</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 lg:p-6 border border-tea-green-100 animate-slide-up delay-1">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-jet-black-500 text-sm font-medium">Total Mahasiswa</p>
                    <p class="text-3xl lg:text-4xl font-display font-bold text-jet-black-900 mt-2">{{ $totalMahasiswa ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-tea-green-200 to-tea-green-300 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-sidebar" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Card Dosen -->
        <div class="stat-card bg-white rounded-2xl p-5 lg:p-6 border border-tea-green-100 animate-slide-up delay-2">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-jet-black-500 text-sm font-medium">Total Dosen</p>
                    <p class="text-3xl lg:text-4xl font-display font-bold text-jet-black-900 mt-2">{{ $totalDosen ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-cerulean-200 to-cerulean-300 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cerulean-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Card Mata Kuliah -->
        <div class="stat-card bg-white rounded-2xl p-5 lg:p-6 border border-tea-green-100 animate-slide-up delay-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-jet-black-500 text-sm font-medium">Total Mata Kuliah</p>
                    <p class="text-3xl lg:text-4xl font-display font-bold text-jet-black-900 mt-2">{{ $totalMataKuliah ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-tropical-teal-200 to-tropical-teal-300 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-tropical-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Card IPK -->
        <div class="stat-card bg-white rounded-2xl p-5 lg:p-6 border border-tea-green-100 animate-slide-up delay-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-jet-black-500 text-sm font-medium">Rata-rata IPK</p>
                    <p class="text-3xl lg:text-4xl font-display font-bold text-jet-black-900 mt-2">{{ number_format($avgIpk ?? 0, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-muted-teal-200 to-muted-teal-300 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-muted-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Ranking -->
    <div class="bg-white rounded-2xl border border-tea-green-100 overflow-hidden animate-slide-up delay-5">
        <div class="px-6 py-5 border-b border-tea-green-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-display text-lg font-bold text-jet-black-900">Peringkat IPK Mahasiswa</h3>
            </div>
            <div class="flex gap-3">
                <select id="filterProdi" onchange="filterTable()" class="px-4 py-2 bg-tea-green-50 border border-tea-green-200 rounded-xl text-sm">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                    @endforeach
                </select>
                <button onclick="resetFilters()" class="px-4 py-2 bg-jet-black-800 hover:bg-jet-black-700 text-white rounded-xl text-sm">Reset</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-tea-green-50">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Ranking</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-jet-black-600 uppercase">Nama</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">IPK</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-jet-black-600 uppercase">Predikat</th>
                    </tr>
                </thead>
                <tbody id="rankingTable" class="divide-y divide-tea-green-100"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <p class="text-jet-black-500 font-medium">Tidak ada data</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const mahasiswaData = @json($mahasiswa);

    function populateTable(data) {
        const tbody = document.getElementById('rankingTable');
        const emptyState = document.getElementById('emptyState');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        data.forEach((mhs, index) => {
            const row = document.createElement('tr');
            row.className = 'table-row animate-fade';
            
            const predikatClass = mhs.ipk >= 3.85 ? 'badge-cumlaude' : mhs.ipk >= 3.70 ? 'badge-sangat-baik' : 'badge-baik';
            const predikatText = mhs.ipk >= 3.85 ? 'Cumlaude' : mhs.ipk >= 3.70 ? 'Sangat Baik' : 'Baik';
            
            row.innerHTML = `
                <td class="px-6 py-4"><span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-display font-bold text-sm ${index < 3 ? 'bg-gradient-to-br from-tea-green-400 to-muted-teal-500 text-white' : 'bg-tea-green-100 text-jet-black-600'}">${index + 1}</span></td>
                <td class="px-6 py-4"><div class="flex items-center gap-3"><div class="w-9 h-9 rounded-full bg-gradient-to-br from-cerulean-300 to-tropical-teal-400 flex items-center justify-center"><span class="text-white font-medium text-xs">${mhs.nama.split(' ').map(n => n[0]).join('')}</span></div><span class="font-medium text-jet-black-800">${mhs.nama}</span></div></td>
                <td class="px-6 py-4 text-center"><span class="font-display font-bold text-lg ${mhs.ipk >= 3.5 ? 'text-tea-green-600' : 'text-jet-black-700'}">${mhs.ipk.toFixed(2)}</span></td>
                <td class="px-6 py-4 text-center"><span class="px-3 py-1.5 text-xs font-semibold rounded-full ${predikatClass}">${predikatText}</span></td>
            `;
            tbody.appendChild(row);
        });
    }

    function filterTable() {
        const prodi = document.getElementById('filterProdi').value;
        let filtered = mahasiswaData.filter(m => !prodi || m.prodi === prodi);
        populateTable(filtered);
    }

    function resetFilters() {
        document.getElementById('filterProdi').value = '';
        populateTable(mahasiswaData);
    }

    document.addEventListener('DOMContentLoaded', () => {
        populateTable(mahasiswaData);
    });
</script>
@endpush