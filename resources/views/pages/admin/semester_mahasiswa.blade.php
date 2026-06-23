@extends('layouts.admin')

@section('title', 'Semester Mahasiswa')
@section('page_title', 'Semester Mahasiswa')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Akademik</span>
            <h1 class="mt-2">Semester Mahasiswa</h1>
            <p>Kelola progres semester mahasiswa berdasarkan periode, prodi, dan kelas.</p>
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

    @if($errors->any())
        <div class="nb-alert nb-alert-danger mb-6">
            <strong>Data belum valid.</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="nb-bento mb-6" style="grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));">
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">groups</span>
                </div>
                <p class="nb-stat-label">Total</p>
            </div>
            <div class="nb-stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="nb-stat nb-stat--accent nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">check_circle</span>
                </div>
                <p class="nb-stat-label">Aktif</p>
            </div>
            <div class="nb-stat-value">{{ $stats['aktif'] }}</div>
        </div>
        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">pause_circle</span>
                </div>
                <p class="nb-stat-label">Cuti</p>
            </div>
            <div class="nb-stat-value">{{ $stats['cuti'] }}</div>
        </div>
        <div class="nb-stat nb-stat--info nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">replay</span>
                </div>
                <p class="nb-stat-label">Mengulang</p>
            </div>
            <div class="nb-stat-value">{{ $stats['mengulang'] }}</div>
        </div>
        <div class="nb-stat nb-stat--warning nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled">rule</span>
                </div>
                <p class="nb-stat-label">Belum Diatur</p>
            </div>
            <div class="nb-stat-value">{{ $stats['belum_diatur'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 2xl:grid-cols-[1.1fr_0.9fr] gap-6 mb-6">
        <div class="nb-card">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">filter_list</span>
                        <h2 class="nb-h3">Filter Data</h2>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="nb-badge nb-badge-primary">
                            {{ $selectedSemester ? $selectedSemester->semester . ' ' . $selectedSemester->tahun_ajaran : '-' }}
                        </span>
                        <span class="nb-badge nb-badge-stable">{{ $selectedProdi ?: 'Semua Prodi' }}</span>
                        <span class="nb-badge nb-badge-stable">{{ $selectedKelas ?: 'Semua Kelas' }}</span>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('pages.admin.semester-mahasiswa.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1.2fr_1fr_1fr_auto] gap-4 items-end">
                <div>
                    <label class="nb-label">Semester</label>
                    <select name="semester_id">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ (int) $selectedSemesterId === (int) $semester->id ? 'selected' : '' }}>
                                {{ $semester->semester }} {{ $semester->tahun_ajaran }}
                                @if($semester->is_active) - Aktif @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Prodi</label>
                    <select name="prodi">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi }}" {{ $selectedProdi === $prodi ? 'selected' : '' }}>
                                {{ $prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Kelas</label>
                    <select name="kelas">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ $selectedKelas === $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="nb-btn nb-btn-primary w-full">
                    <span class="material-symbols-outlined" style="font-size:18px;">search</span>
                    Tampilkan
                </button>
            </form>
        </div>

        <div class="nb-card">
            <div class="flex items-start gap-3 mb-5">
                <div class="nb-stat-icon" style="width:44px;height:44px;">
                    <span class="material-symbols-outlined filled">trending_up</span>
                </div>
                <div>
                    <h2 class="nb-h3">Naik Semester Massal</h2>
                    <p class="text-sm text-muted mt-1">Proses hanya mengambil mahasiswa aktif sesuai filter prodi dan kelas.</p>
                </div>
            </div>
            @php
                $selectedPeriodOrder = (int) ($selectedSemester->period_order ?? 0);
                $firstPromotableSemester = $semesters->first(fn ($semester) => (int) $semester->period_order > $selectedPeriodOrder);
            @endphp
            <form method="POST" action="{{ route('pages.admin.semester-mahasiswa.promote') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end" id="promote-semester-form">
                @csrf
                <div>
                    <label class="nb-label">Dari Semester</label>
                    <select name="from_semester_id" required id="from_semester_id">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" data-period-order="{{ $semester->period_order }}" {{ (int) $selectedSemesterId === (int) $semester->id ? 'selected' : '' }}>
                                {{ $semester->semester }} {{ $semester->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Ke Semester</label>
                    <select name="to_semester_id" required id="to_semester_id">
                        @foreach($semesters as $semester)
                            @php
                                $isPromotable = (int) $semester->period_order > $selectedPeriodOrder;
                            @endphp
                            <option value="{{ $semester->id }}"
                                data-period-order="{{ $semester->period_order }}"
                                {{ $isPromotable ? '' : 'disabled' }}
                                {{ $firstPromotableSemester && (int) $firstPromotableSemester->id === (int) $semester->id ? 'selected' : '' }}>
                                {{ $semester->semester }} {{ $semester->tahun_ajaran }}
                                @if($semester->is_active) - Aktif @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Prodi</label>
                    <select name="prodi">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi }}" {{ $selectedProdi === $prodi ? 'selected' : '' }}>
                                {{ $prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="nb-label">Kelas</label>
                    <select name="kelas">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ $selectedKelas === $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="nb-btn nb-btn-primary md:col-span-2 w-full" onclick="return confirm('Naikkan mahasiswa aktif sesuai filter ke semester tujuan?')">
                    <span class="material-symbols-outlined" style="font-size:18px;">trending_up</span>
                    Proses Naik Semester
                </button>
            </form>
        </div>
    </div>

    <div class="nb-card-flat">
        <div class="nb-section-header">
            <div>
                <span class="nb-eyebrow" style="color: var(--color-accent-soft);">Periode Dipilih</span>
                <h2 class="mt-1">
                    {{ $selectedSemester ? $selectedSemester->semester . ' ' . $selectedSemester->tahun_ajaran : '-' }}
                </h2>
                <p class="text-sm text-muted mt-1">
                    Prodi: {{ $selectedProdi ?: 'Semua' }} / Kelas: {{ $selectedKelas ?: 'Semua' }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="nb-badge nb-badge-primary">{{ $mahasiswa->count() }} mahasiswa</span>
                @if($stats['belum_diatur'] > 0)
                    <span class="nb-badge nb-badge-warning">{{ $stats['belum_diatur'] }} belum diatur</span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="nb-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Prodi</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Semester Ke</th>
                        <th class="text-center">Status</th>
                        <th>Catatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $mhs)
                        @php
                            $status = $mhs->status ?? 'belum_diatur';
                            $currentSemesterKe = (int) ($mhs->semester_ke ?? 1);
                            $statusLabels = [
                                'aktif' => 'Aktif',
                                'cuti' => 'Cuti',
                                'mengulang' => 'Mengulang',
                                'lulus' => 'Lulus',
                                'nonaktif' => 'Nonaktif',
                                'belum_diatur' => 'Belum Diatur',
                            ];
                            $statusBadges = [
                                'aktif' => 'nb-badge-success',
                                'cuti' => 'nb-badge-warning',
                                'mengulang' => 'nb-badge-primary',
                                'lulus' => 'nb-badge-stable',
                                'nonaktif' => 'nb-badge-danger',
                                'belum_diatur' => 'nb-badge-warning',
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-soft border-2 border-ink flex items-center justify-center flex-shrink-0">
                                        <span class="text-primary font-extrabold text-xs" style="font-family: var(--font-heading);">
                                            {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-ink">{{ $mhs->nama }}</div>
                                        <div class="text-sm text-muted">{{ $mhs->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-muted">{{ $mhs->prodi }}</span>
                            </td>
                            <td class="text-center">
                                <span class="nb-badge nb-badge-stable">{{ $mhs->kelas ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <form id="form-semester-{{ $mhs->id }}" method="POST" action="{{ route('pages.admin.semester-mahasiswa.update', $mhs->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="semester_id" value="{{ $selectedSemesterId }}">
                                    <input type="hidden" name="prodi" value="{{ $selectedProdi }}">
                                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
                                    <input type="number" name="semester_ke" min="{{ $mhs->progress_id ? $currentSemesterKe : 1 }}" max="14" value="{{ old('semester_ke', $mhs->semester_ke ?? ($selectedSemester->semester_ke ?? 1)) }}" class="w-20 text-center">
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="mb-2">
                                    <span class="nb-badge {{ $statusBadges[$status] ?? 'nb-badge-stable' }}">
                                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                                    </span>
                                </div>
                                <select name="status" form="form-semester-{{ $mhs->id }}">
                                    @foreach(['aktif' => 'Aktif', 'cuti' => 'Cuti', 'mengulang' => 'Mengulang', 'lulus' => 'Lulus', 'nonaktif' => 'Nonaktif'] as $value => $label)
                                        <option value="{{ $value }}" {{ ($mhs->status ?? 'aktif') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="catatan" form="form-semester-{{ $mhs->id }}" value="{{ old('catatan', $mhs->catatan) }}" placeholder="Opsional">
                            </td>
                            <td class="text-center">
                                <button type="submit" form="form-semester-{{ $mhs->id }}" class="nb-btn nb-btn-primary nb-btn-sm">
                                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                                    Simpan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-muted">Belum ada data mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fromSemester = document.getElementById('from_semester_id');
            const toSemester = document.getElementById('to_semester_id');

            const syncPromotableOptions = () => {
                if (!fromSemester || !toSemester) {
                    return;
                }

                const selectedFrom = fromSemester.options[fromSemester.selectedIndex];
                const fromOrder = Number(selectedFrom?.dataset.periodOrder || 0);
                let selectedOptionIsValid = false;
                let firstValidOption = null;

                Array.from(toSemester.options).forEach((option) => {
                    const isValid = Number(option.dataset.periodOrder || 0) > fromOrder;
                    option.disabled = !isValid;

                    if (isValid && !firstValidOption) {
                        firstValidOption = option;
                    }

                    if (option.selected && isValid) {
                        selectedOptionIsValid = true;
                    }
                });

                if (!selectedOptionIsValid && firstValidOption) {
                    toSemester.value = firstValidOption.value;
                }
            };

            syncPromotableOptions();
            fromSemester?.addEventListener('change', syncPromotableOptions);
        });
    </script>
@endsection
