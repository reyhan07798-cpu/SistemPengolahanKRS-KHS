@extends('layouts.dosen')

@section('title', 'KHS Mahasiswa')
@section('page_title', 'KHS Mahasiswa')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen Wali · Akademik</span>
            <h1 class="mt-2">KHS Mahasiswa</h1>
            <p>Pantau hasil studi mahasiswa bimbingan Anda.</p>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">groups</span>
                </div>
                <div class="min-w-0">
                    <p class="nb-stat-label">Total Mahasiswa</p>
                    <p class="nb-stat-value mt-1">{{ $totalMahasiswa ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <div class="min-w-0">
                    <p class="nb-stat-label">Rata-Rata IPK</p>
                    <p class="nb-stat-value mt-1">{{ number_format($rataIpk ?? 0, 3) }}</p>
                </div>
            </div>
        </div>

        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">workspace_premium</span>
                </div>
                <div class="min-w-0">
                    <p class="nb-stat-label">IPK ≥ 3.5</p>
                    <p class="nb-stat-value mt-1">{{ $ipkTinggi ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen.wali.khs') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="nb-label">Kelas</label>
                    <select name="kelas">
                        <option value="semua" {{ ($filterKelas ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                        <option value="A" {{ ($filterKelas ?? '') == 'A' ? 'selected' : '' }}>Kelas A</option>
                        <option value="B" {{ ($filterKelas ?? '') == 'B' ? 'selected' : '' }}>Kelas B</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="nb-btn nb-btn-primary">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                        Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Mahasiswa --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Peringkat</span>
                <h2 class="mt-1">Daftar Mahasiswa</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th class="text-center">Kelas</th>
                        <th class="hidden md:table-cell">Prodi</th>
                        <th class="text-center">MK Lulus</th>
                        <th class="text-center">IPK</th>
                        <th class="text-center">Status KRS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa ?? [] as $mhs)
                        @php
                            $isTop3 = ($mhs['ranking'] ?? 99) <= 3;
                            $statusBadge = ($mhs['status_krs'] ?? '') === 'Aktif' ? 'nb-badge-success' : 'nb-badge-stable';
                            $ipkClass = $mhs['ipk'] >= 3.5 ? 'text-accent' : 'text-primary';
                        @endphp
                        <tr>
                            <td>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md font-extrabold text-sm border-2 border-ink {{ $isTop3 ? 'bg-accent text-white' : 'bg-surface-alt text-ink' }}" style="font-family: var(--font-heading);">
                                    {{ $mhs['ranking'] }}
                                </span>
                            </td>
                            <td class="font-bold text-primary text-sm" style="font-family: var(--font-heading);">{{ $mhs['nim'] }}</td>
                            <td class="font-medium text-ink">{{ $mhs['nama'] }}</td>
                            <td class="text-center"><span class="nb-badge nb-badge-stable">{{ $mhs['kelas'] }}</span></td>
                            <td class="hidden md:table-cell text-muted">{{ $mhs['prodi'] }}</td>
                            <td class="text-center font-bold text-primary">{{ $mhs['mk_lulus'] }}</td>
                            <td class="text-center font-extrabold text-lg {{ $ipkClass }}" style="font-family: var(--font-heading);">{{ number_format($mhs['ipk'], 2) }}</td>
                            <td class="text-center"><span class="nb-badge {{ $statusBadge }}">{{ $mhs['status_krs'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <span class="material-symbols-outlined text-muted" style="font-size:48px;">groups</span>
                                <p class="mt-2 text-muted font-medium">Tidak ada data mahasiswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
