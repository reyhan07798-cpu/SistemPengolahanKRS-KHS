@extends('layouts.dosen')
@section('title', 'Verifikasi KRS')
@section('page_title', 'Verifikasi KRS')

@section('content')
<div class="nb-page-header">
    <div>
        <span class="nb-eyebrow">Persetujuan</span>
        <h1 class="mt-2">Verifikasi KRS</h1>
        <p>Setujui atau tolak pengajuan KRS dari mahasiswa bimbingan Anda.</p>
    </div>
</div>

@if(session('success'))
    <div class="nb-alert nb-alert-success mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="nb-alert nb-alert-danger mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined">error</span> {{ session('error') }}
    </div>
@endif

{{-- Stats --}}
<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="nb-stat nb-stat--warning nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">schedule</span></div><p class="nb-stat-label">Menunggu</p></div>
        <div class="nb-stat-value">{{ $stats['menunggu'] }}</div>
    </div>
    <div class="nb-stat nb-stat--accent nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">check_circle</span></div><p class="nb-stat-label">Disetujui</p></div>
        <div class="nb-stat-value">{{ $stats['disetujui'] }}</div>
    </div>
    <div class="nb-stat nb-stat--danger nb-stat--ribbon">
        <div class="flex items-center gap-3"><div class="nb-stat-icon"><span class="material-symbols-outlined filled">cancel</span></div><p class="nb-stat-label">Ditolak</p></div>
        <div class="nb-stat-value">{{ $stats['ditolak'] }}</div>
    </div>
</div>

{{-- Filter AUTO-SUBMIT --}}
<div class="nb-card mb-6">
    <div class="flex items-center gap-3 mb-4">
        <span class="material-symbols-outlined text-primary">filter_list</span>
        <h3 class="nb-h3">Filter</h3>
    </div>
    <form method="GET" action="{{ route('dosen.wali.krs-verifikasi') }}" id="filterForm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="nb-label">Semester</label>
                <select name="semester_id" onchange="this.form.submit()">
                    <option value="">Semua Semester</option>
                    @foreach($allSem as $sem)
                        <option value="{{ $sem->id }}" {{ $filterSemesterId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->semester }} {{ $sem->tahun_ajaran }}
                            @if($sem->is_active) ★ @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="nb-label">Status</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="semua" {{ $filterStatus=='semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu"  {{ $filterStatus=='menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ $filterStatus=='disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"   {{ $filterStatus=='ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="nb-label">Kelas</label>
                <select name="kelas" onchange="this.form.submit()">
                    <option value="semua">Semua Kelas</option>
                    @foreach($kelasList as $kls)
                        <option value="{{ $kls }}" {{ $filterKelas==$kls ? 'selected' : '' }}>{{ $kls }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                @if($isReadOnly)
                    <span class="nb-badge nb-badge-stable w-full text-center py-2" style="font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">lock</span>
                        Semester Tidak Aktif (Read Only)
                    </span>
                @else
                    <span class="nb-badge nb-badge-success w-full text-center py-2" style="font-size:13px;">
                        <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">check_circle</span>
                        Semester Aktif
                    </span>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- Tabel KRS --}}
<div class="nb-card-flat">
    <div class="nb-section-header">
        <h2>Daftar Pengajuan KRS</h2>
        <span class="nb-badge nb-badge-primary">{{ count($daftarKrs) }} pengajuan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="nb-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th class="text-center">Jml MK</th>
                    <th class="text-center">Total SKS</th>
                    <th>Semester</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daftarKrs as $i => $krs)
                @php
                    $badge = match(strtolower($krs['status'])) {
                        'disetujui' => 'nb-badge-success',
                        'ditolak'   => 'nb-badge-danger',
                        default     => 'nb-badge-warning',
                    };
                    $canAct = $krs['is_active'] && !$isReadOnly && strtolower($krs['status']) === 'menunggu';
                @endphp
                <tr>
                    <td class="font-bold text-muted">{{ $i+1 }}</td>
                    <td>
                        <div class="font-bold text-sm text-ink">{{ $krs['nama'] }}</div>
                        <div class="text-xs text-muted">{{ $krs['nim'] }}</div>
                    </td>
                    <td class="text-center">{{ $krs['mk_count'] }}</td>
                    <td class="text-center font-bold">{{ $krs['total_sks'] }}</td>
                    <td class="text-sm">{{ $krs['semester'] }} {{ $krs['tahun_ajaran'] }}</td>
                    <td class="text-center"><span class="nb-badge {{ $badge }}">{{ $krs['status'] }}</span></td>
                    <td class="text-center text-sm text-muted">{{ $krs['tanggal'] }}</td>
                    <td class="text-center">
                        <div class="flex gap-2 justify-center flex-wrap">
                            {{-- Detail --}}
                            <a href="{{ route('dosen.wali.krs.detail', $krs['krs_id']) }}"
                               class="nb-btn nb-btn-secondary nb-btn-sm"
                               title="Lihat Detail">
                                <span class="material-symbols-outlined" style="font-size:14px;">visibility</span>
                            </a>
                            @if($canAct)
                            {{-- Setujui --}}
                            <form method="POST" action="{{ route('dosen.wali.krs.approve', $krs['krs_id']) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="nb-btn nb-btn-primary nb-btn-sm"
                                        onclick="return confirm('Setujui KRS {{ $krs["nama"] }}?')"
                                        title="Setujui">
                                    <span class="material-symbols-outlined" style="font-size:14px;">check</span>
                                </button>
                            </form>
                            {{-- Tolak --}}
                            <button type="button" class="nb-btn nb-btn-sm"
                                    style="background:#fee2e2;color:#991b1b;"
                                    onclick="showTolakModal({{ $krs['krs_id'] }}, '{{ $krs['nama'] }}')"
                                    title="Tolak">
                                <span class="material-symbols-outlined" style="font-size:14px;">close</span>
                            </button>
                            @elseif(!$krs['is_active'] && strtolower($krs['status']) === 'menunggu')
                            <span class="text-xs text-muted">🔒 Terkunci</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-12 text-muted">
                    <span class="material-symbols-outlined" style="font-size:48px;">inbox</span>
                    <p class="mt-2">Tidak ada data KRS sesuai filter.</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tolak KRS --}}
<div id="modalTolak" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="nb-card w-full max-w-md">
            <h3 class="nb-h3 mb-4">Tolak KRS</h3>
            <p class="text-muted mb-4" id="modalTolakNama">—</p>
            <form id="formTolak" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="nb-label">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan" rows="4" required
                              placeholder="Tuliskan alasan penolakan KRS..."
                              class="w-full border border-gray-300 rounded-lg p-3 text-sm"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="hideTolakModal()" class="nb-btn nb-btn-secondary">Batal</button>
                    <button type="submit" class="nb-btn nb-btn-primary" style="background:#dc2626;">Tolak KRS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showTolakModal(krsId, nama) {
    document.getElementById('modalTolakNama').textContent = 'Tolak KRS: ' + nama;
    document.getElementById('formTolak').action = '/dosen/wali/krs/' + krsId + '/reject';
    document.getElementById('modalTolak').classList.remove('hidden');
}
function hideTolakModal() {
    document.getElementById('modalTolak').classList.add('hidden');
}
</script>
@endpush
