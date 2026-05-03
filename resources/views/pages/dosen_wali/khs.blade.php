@extends('layouts.dosen')

@section('title', 'KHS Mahasiswa')
@section('page_title', 'KHS Mahasiswa')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">KHS Mahasiswa</h1>
            <p>Pantau hasil studi mahasiswa bimbingan Anda.</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon"><span class="material-symbols-outlined filled">groups</span></div>
                <div><p class="nb-stat-label">Total Mahasiswa</p><p class="nb-stat-value mt-1">{{ $totalMahasiswa }}</p></div>
            </div>
        </div>
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon"><span class="material-symbols-outlined filled">trending_up</span></div>
                <div><p class="nb-stat-label">Rata-Rata IPK</p><p class="nb-stat-value mt-1">{{ number_format($rataIpk, 3) }}</p></div>
            </div>
        </div>
        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-4">
                <div class="nb-stat-icon"><span class="material-symbols-outlined filled">workspace_premium</span></div>
                <div><p class="nb-stat-label">IPK ≥ 3.5</p><p class="nb-stat-value mt-1">{{ $ipkTinggi }}</p></div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="nb-card mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary">filter_list</span>
            <h3 class="nb-h3">Filter</h3>
        </div>
        <form method="GET" action="{{ route('pages.dosen_wali.khs') }}">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="nb-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran">
                        <option value="semua" {{ $filterTahunAjaran == 'semua' ? 'selected' : '' }}>Semua</option>
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $filterTahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Semester</label>
                    <select name="semester">
                        <option value="semua" {{ $filterSemester == 'semua' ? 'selected' : '' }}>Semua</option>
                        @foreach($semesterList as $sem)
                            <option value="{{ $sem }}" {{ $filterSemester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Kelas</label>
                    <select name="kelas">
                        <option value="semua" {{ $filterKelas == 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                        <option value="A"     {{ $filterKelas == 'A'     ? 'selected' : '' }}>Kelas A</option>
                        <option value="B"     {{ $filterKelas == 'B'     ? 'selected' : '' }}>Kelas B</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="nb-btn nb-btn-primary w-full">
                        <span class="material-symbols-outlined" style="font-size:18px;">search</span> Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">Peringkat</span>
                <h2 class="mt-1">Daftar Mahasiswa — {{ $filterTahunAjaran == 'semua' ? 'Semua Tahun' : $filterTahunAjaran }} / {{ $filterSemester == 'semua' ? 'Semua Semester' : $filterSemester }}</h2>
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
                    @forelse($mahasiswa as $mhs)
                        @php
                            $isTop3 = ($mhs['ranking'] ?? 99) <= 3;
                            $statusBadge = ($mhs['status_krs'] ?? '') === 'Aktif' ? 'nb-badge-success' : 'nb-badge-stable';
                            $ipkClass = $mhs['ipk'] >= 3.5 ? 'text-accent' : 'text-primary';
                        @endphp
                        <tr>
                            <td>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md font-extrabold text-sm border-2 border-ink {{ $isTop3 ? 'bg-accent text-white' : 'bg-surface-alt text-ink' }}">
                                    {{ $mhs['ranking'] }}
                                </span>
                            </td>
                            <td class="font-bold text-primary text-sm">{{ $mhs['nim'] }}</td>
                            <td class="font-medium text-ink">{{ $mhs['nama'] }}</td>
                            <td class="text-center"><span class="nb-badge nb-badge-stable">{{ $mhs['kelas'] }}</span></td>
                            <td class="hidden md:table-cell text-muted">{{ $mhs['prodi'] }}</td>
                            <td class="text-center font-bold text-primary">{{ $mhs['mk_lulus'] }}</td>
                            <td class="text-center font-extrabold text-lg {{ $ipkClass }}">{{ number_format($mhs['ipk'], 2) }}</td>
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

    {{-- Tabel IP Per Semester Per Mahasiswa --}}
    <div class="nb-card-flat mt-6">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color:var(--color-accent-soft);">Detail Akademik</span>
                <h2 class="mt-1">IP Semester Mahasiswa Bimbingan</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th class="text-center">Sem 1</th>
                        <th class="text-center">Sem 2</th>
                        <th class="text-center">Sem 3</th>
                        <th class="text-center">Sem 4</th>
                        <th class="text-center">IPK</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ipPerMahasiswa = [
                            [
                                'nama' => 'Nabila Fatin',    'nim' => '3312501007',
                                'sem' => [3.80, 3.92, 3.90, 4.00], 'ipk' => 3.92,
                            ],
                            [
                                'nama' => 'Irenessa Rosidin','nim' => '3312501017',
                                'sem' => [3.70, 3.85, 3.90, null], 'ipk' => 3.86,
                            ],
                            [
                                'nama' => 'Reyhan',          'nim' => '3312501022',
                                'sem' => [3.60, 3.75, 3.85, null], 'ipk' => 3.85,
                            ],
                        ];
                    @endphp
                    @foreach($ipPerMahasiswa as $mhs)
                        @php
                            $predikat = match(true) {
                                $mhs['ipk'] >= 3.75 => ['label' => 'Dengan Pujian',     'badge' => 'nb-badge-success'],
                                $mhs['ipk'] >= 3.50 => ['label' => 'Sangat Memuaskan',  'badge' => 'nb-badge-primary'],
                                $mhs['ipk'] >= 3.00 => ['label' => 'Memuaskan',         'badge' => 'nb-badge-warning'],
                                default             => ['label' => 'Cukup',             'badge' => 'nb-badge-stable'],
                            };
                        @endphp
                        <tr>
                            <td class="font-bold text-ink">{{ $mhs['nama'] }}</td>
                            <td class="font-bold text-primary text-sm" style="font-family:var(--font-heading);">{{ $mhs['nim'] }}</td>
                            @foreach($mhs['sem'] as $ip)
                                <td class="text-center">
                                    @if($ip !== null)
                                        @php $cls = $ip >= 3.5 ? 'text-accent' : ($ip >= 3.0 ? 'text-primary' : 'text-muted'); @endphp
                                        <span class="font-bold {{ $cls }}" style="font-family:var(--font-heading);">{{ number_format($ip, 2) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center">
                                <span class="font-extrabold text-lg {{ $mhs['ipk'] >= 3.5 ? 'text-accent' : 'text-primary' }}" style="font-family:var(--font-heading);">{{ number_format($mhs['ipk'], 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="nb-badge {{ $predikat['badge'] }}">{{ $predikat['label'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
