@extends('layouts.dosen')

@section('title', 'Verifikasi KRS')
@section('page_title', 'Verifikasi KRS')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Dosen Wali · Persetujuan</span>
            <h1 class="mt-2">Verifikasi KRS</h1>
            <p>Setujui atau tolak pengajuan KRS dari mahasiswa bimbingan Anda.</p>
        </div>
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

    {{-- Summary Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">schedule</span>
                </div>
                <p class="nb-stat-label">Menunggu Verifikasi</p>
            </div>
            <div class="nb-stat-value">{{ $stats['menunggu'] ?? 0 }}</div>
        </div>

        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">check_circle</span>
                </div>
                <p class="nb-stat-label">Disetujui</p>
            </div>
            <div class="nb-stat-value">{{ $stats['disetujui'] ?? 0 }}</div>
        </div>

        <div class="nb-stat nb-stat--danger nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">cancel</span>
                </div>
                <p class="nb-stat-label">Ditolak</p>
            </div>
            <div class="nb-stat-value">{{ $stats['ditolak'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen.wali.krs.verifikasi') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="nb-label">Status</label>
                    <select name="status">
                        <option value="semua" {{ ($filterStatus ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="Menunggu" {{ ($filterStatus ?? '') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Disetujui" {{ ($filterStatus ?? '') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ ($filterStatus ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="nb-label">Kelas</label>
                    <select name="kelas">
                        <option value="semua" {{ ($filterKelas ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="A" {{ ($filterKelas ?? '') == 'A' ? 'selected' : '' }}>Kelas A</option>
                        <option value="B" {{ ($filterKelas ?? '') == 'B' ? 'selected' : '' }}>Kelas B</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="nb-btn nb-btn-primary w-full">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                        Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel KRS --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Pengajuan</span>
                <h2 class="mt-1">Daftar KRS Mahasiswa</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Mata Kuliah</th>
                        <th class="text-center">Total SKS</th>
                        <th class="text-center">Status</th>
                        <th class="hidden md:table-cell">Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarKrs ?? [] as $krs)
                        @php
                            $statusBadge = match($krs['status']) {
                                'Disetujui' => 'nb-badge-success',
                                'Ditolak' => 'nb-badge-danger',
                                default => 'nb-badge-warning',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="font-bold text-ink">{{ $krs['nama'] }}</div>
                                <div class="text-xs text-muted mt-1">{{ $krs['nim'] }}</div>
                            </td>
                            <td class="text-center"><span class="nb-badge nb-badge-stable">{{ $krs['kelas'] }}</span></td>
                            <td class="text-center font-bold text-primary">{{ $krs['mk_count'] }} MK</td>
                            <td class="text-center font-bold text-primary">{{ $krs['total_sks'] }} SKS</td>
                            <td class="text-center"><span class="nb-badge {{ $statusBadge }}">{{ $krs['status'] }}</span></td>
                            <td class="hidden md:table-cell text-sm text-muted">{{ $krs['tanggal'] }}</td>
                            <td class="text-center">
                                @if($krs['status'] == 'Menunggu')
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('pages.dosen.wali.krs.approve', $krs['nim']) }}" method="POST" class="inline"
                                              data-nb-confirm="true"
                                              data-nb-confirm-variant="primary"
                                              data-nb-confirm-icon="check_circle"
                                              data-nb-confirm-title="Setujui KRS Mahasiswa?"
                                              data-nb-confirm-desc="Mahasiswa akan menerima notifikasi bahwa KRS-nya telah disetujui."
                                              data-nb-confirm-button="Ya, Setujui">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="nb-row-action success" title="Setujui">
                                                <span class="material-symbols-outlined">check</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('pages.dosen.wali.krs.reject', $krs['nim']) }}" method="POST" class="inline"
                                              data-nb-confirm="true"
                                              data-nb-confirm-icon="cancel"
                                              data-nb-confirm-title="Tolak KRS Mahasiswa?"
                                              data-nb-confirm-desc="Mahasiswa akan diminta mengajukan ulang KRS-nya."
                                              data-nb-confirm-button="Ya, Tolak">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="nb-row-action danger" title="Tolak">
                                                <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <span class="material-symbols-outlined text-muted" style="font-size:48px;">inbox</span>
                                <p class="mt-2 text-muted font-medium">Tidak ada data KRS yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
