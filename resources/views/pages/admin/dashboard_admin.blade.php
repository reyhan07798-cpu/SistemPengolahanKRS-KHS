@extends('layouts.admin')

@section('title', 'Beranda Admin')
@section('page_title', 'Beranda')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Admin</span>
            <h1 class="mt-2">Selamat Datang, Admin</h1>
            <p>Berikut ringkasan data akademik hari ini.</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('pages.admin.mahasiswa.index') }}" class="nb-btn nb-btn-secondary">
                <span class="material-symbols-outlined" style="font-size:20px;">group</span>
                Kelola Mahasiswa
            </a>
            <a href="{{ route('pages.admin.matakuliah.index') }}" class="nb-btn nb-btn-primary">
                <span class="material-symbols-outlined" style="font-size:20px;">menu_book</span>
                Kelola MK
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">group</span>
                </div>
                <p class="nb-stat-label">Total Mahasiswa</p>
            </div>
            <div class="nb-stat-value">{{ $totalMahasiswa ?? 0 }}</div>
        </div>

        <div class="nb-stat nb-stat--primary nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">badge</span>
                </div>
                <p class="nb-stat-label">Total Dosen</p>
            </div>
            <div class="nb-stat-value">{{ $totalDosen ?? 0 }}</div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">menu_book</span>
                </div>
                <p class="nb-stat-label">Total Mata Kuliah</p>
            </div>
            <div class="nb-stat-value">{{ $totalMataKuliah ?? 0 }}</div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <p class="nb-stat-label">Rata-rata IPK</p>
            </div>
            <div class="nb-stat-value">{{ number_format($avgIpk ?? 0, 2) }}</div>
        </div>
    </div>

    {{-- Tabel Ranking --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Akademik</span>
                <h2 class="mt-1">Peringkat IPK Mahasiswa</h2>
            </div>
            <div class="flex gap-3 items-center flex-nowrap">
                <select id="filterProdi" onchange="filterTable()" style="min-width: 180px;">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                    @endforeach
                </select>
                <button onclick="resetFilters()" class="nb-btn nb-btn-secondary nb-btn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">refresh</span>
                    Reset
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama</th>
                        <th class="text-center">IPK</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody id="rankingTable"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden py-12 text-center">
            <span class="material-symbols-outlined text-muted" style="font-size:48px;">inbox</span>
            <p class="mt-2 text-muted font-medium">Tidak ada data</p>
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
            const predikatBadge = mhs.ipk >= 3.85 ? 'nb-badge-success' : mhs.ipk >= 3.70 ? 'nb-badge-primary' : 'nb-badge-stable';
            const predikatText  = mhs.ipk >= 3.85 ? 'Cumlaude' : mhs.ipk >= 3.70 ? 'Sangat Baik' : 'Baik';
            const isTop3 = index < 3;
            const initials = mhs.nama.split(' ').map(n => n[0]).join('').substring(0, 2);

            row.innerHTML = `
                <td>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md font-extrabold text-sm border-2 border-ink ${isTop3 ? 'bg-accent text-white' : 'bg-surface-alt text-ink'}" style="font-family: var(--font-heading);">${index + 1}</span>
                </td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                            <span class="text-primary font-extrabold text-xs" style="font-family: var(--font-heading);">${initials}</span>
                        </div>
                        <span class="font-medium text-ink">${mhs.nama}</span>
                    </div>
                </td>
                <td class="text-center">
                    <span class="font-extrabold text-lg text-primary" style="font-family: var(--font-heading);">${mhs.ipk.toFixed(2)}</span>
                </td>
                <td class="text-center">
                    <span class="nb-badge ${predikatBadge}">${predikatText}</span>
                </td>
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
