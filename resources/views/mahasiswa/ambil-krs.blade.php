@extends('layouts.mahasiswa')

@section('content')
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Ambil KRS</h1>
        <p class="text-sm text-gray-500">Lihat hasil studi dan kelola paket semester Anda.</p>
    </header>

    @if(session('success'))
        <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Peringatan SKS Melebihi Batas --}}
    <div id="sksWarning" class="hidden mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <span class="block sm:inline font-semibold">⚠️ Peringatan:</span>
        <span class="block sm:inline">Total SKS melebihi batas maksimal 24 SKS. Silahkan kurangi pilihan mata kuliah Anda.</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <x-krs-package-card title="Semester" :value="$data['semester_aktif']" accent="bg-slate-100"
            textColor="text-slate-900"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' />

        <x-krs-package-card title="SKS Paket" :value="$data['sks_paket']" accent="bg-emerald-100"
            textColor="text-emerald-700"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' />

        <x-krs-package-card title="SKS Pengulangan" :value="$data['sks_pengulangan']" accent="bg-amber-100"
            textColor="text-amber-700"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>' />

        <x-krs-package-card title="Total SKS" :value="$data['total_sks']" accent="bg-blue-100" textColor="text-blue-700"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 21c4.97 0 9-4.03 9-9S16.97 3 12 3 3 7.03 3 12s4.03 9 9 9z"/></svg>' />
    </div>

    <div class="bg-slate-100 rounded-[2rem] p-6 mb-8">
        <div class="bg-white rounded-[1.75rem] shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Filter KRS</h2>
                    <p class="text-sm text-slate-500">Lihat KRS semester aktif atau historis KRS sebelumnya.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('mahasiswa.ambil-krs') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="filter_tahun_ajaran" class="block text-sm font-medium text-slate-700 mb-2">Tahun Ajaran & Semester</label>
                    <select name="filter_tahun_ajaran" id="filter_tahun_ajaran"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($data['opsi_filter'] as $opsi)
                            <option value="{{ $opsi['value'] }}"
                                {{ $data['filter_tahun_ajaran'] == $opsi['tahun_ajaran'] && $data['filter_semester_gg'] == $opsi['semester_gg'] ? 'selected' : '' }}>
                                {{ $opsi['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#2C4A6B] px-6 py-2.5 text-sm font-semibold text-white shadow-lg hover:bg-slate-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tampilkan
                    </button>
                    @if($data['mode_historis'])
                        <a href="{{ route('mahasiswa.ambil-krs') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-300 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODE HISTORIS: TAMPILKAN KRS LAMA (VIEW ONLY) --}}
    {{-- ============================================================ --}}
    @if($data['mode_historis'])
        <div class="bg-slate-100 rounded-[2rem] p-6 mb-8">
            <div class="bg-white rounded-[1.75rem] shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Historis KRS</h2>
                        <p class="text-sm text-slate-500">
                            {{ $data['filter_tahun_ajaran'] }} -
                            {{ $data['filter_semester_gg'] == 'ganjil' ? 'Semester Ganjil (1, 3, 5, 7)' : 'Semester Genap (2, 4, 6, 8)' }}
                        </p>
                    </div>
                </div>

                @if($data['historis_krs']->count() > 0)
                    {{-- Paket Normal Historis --}}
                    @if($data['historis_paket']->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">📚 Paket Semester</h3>
                            <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 shadow-sm">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-200 text-slate-700 text-sm uppercase tracking-[0.2em]">
                                        <tr>
                                            <th class="px-6 py-4 text-left font-semibold">Kode</th>
                                            <th class="px-6 py-4 text-left font-semibold">Mata Kuliah</th>
                                            <th class="px-6 py-4 text-center font-semibold">SKS</th>
                                            <th class="px-6 py-4 text-center font-semibold">Semester</th>
                                            <th class="px-6 py-4 text-center font-semibold">Status</th>
                                            <th class="px-6 py-4 text-center font-semibold">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200 text-sm text-slate-700">
                                        @foreach($data['historis_paket'] as $krs)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="px-6 py-4 font-medium text-slate-900">{{ $krs->mataKuliah->kode_mk ?? '-' }}</td>
                                                <td class="px-6 py-4">{{ $krs->mataKuliah->nama ?? 'Mata Kuliah' }}</td>
                                                <td class="px-6 py-4 text-center">{{ $krs->mataKuliah->sks ?? '-' }}</td>
                                                <td class="px-6 py-4 text-center">{{ $krs->semester }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $krs->status == 'disetujui' ? 'bg-green-100 text-green-800' : ($krs->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($krs->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($krs->nilai)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-white text-xs" style="background-color: {{ getNilaiColor($krs->nilai->nilai) }};">
                                                            {{ $krs->nilai->nilai }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Pengulangan Historis --}}
                    @if($data['historis_retake']->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-amber-800 mb-3">🔄 Mata Kuliah Pengulangan</h3>
                            <div class="overflow-hidden rounded-[1.75rem] border border-amber-200 shadow-sm">
                                <table class="min-w-full divide-y divide-amber-200">
                                    <thead class="bg-amber-100 text-amber-800 text-sm uppercase tracking-[0.2em]">
                                        <tr>
                                            <th class="px-6 py-4 text-left font-semibold">Kode</th>
                                            <th class="px-6 py-4 text-left font-semibold">Mata Kuliah</th>
                                            <th class="px-6 py-4 text-center font-semibold">SKS</th>
                                            <th class="px-6 py-4 text-center font-semibold">Semester</th>
                                            <th class="px-6 py-4 text-center font-semibold">Status</th>
                                            <th class="px-6 py-4 text-center font-semibold">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-amber-100 text-sm text-slate-700">
                                        @foreach($data['historis_retake'] as $krs)
                                            <tr class="hover:bg-amber-50 transition">
                                                <td class="px-6 py-4 font-medium text-slate-900">{{ $krs->mataKuliah->kode_mk ?? '-' }}</td>
                                                <td class="px-6 py-4">{{ $krs->mataKuliah->nama ?? 'Mata Kuliah' }}</td>
                                                <td class="px-6 py-4 text-center">{{ $krs->mataKuliah->sks ?? '-' }}</td>
                                                <td class="px-6 py-4 text-center">{{ $krs->semester }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $krs->status == 'disetujui' ? 'bg-green-100 text-green-800' : ($krs->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($krs->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($krs->nilai)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-white text-xs" style="background-color: {{ getNilaiColor($krs->nilai->nilai) }};">
                                                            {{ $krs->nilai->nilai }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 text-slate-500">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Tidak ada data KRS untuk periode ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- ============================================================ --}}
        {{-- MODE AMBIL KRS (SEMESTER AKTIF) --}}
        {{-- ============================================================ --}}
        <div class="bg-slate-100 rounded-[2rem] p-6 mb-8">
            <div class="bg-white rounded-[1.75rem] shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Paket Semester</h2>
                        <p class="text-sm text-slate-500">Daftar mata kuliah yang tersedia untuk semester aktif.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('mahasiswa.store-krs') }}">
                    @csrf
                    <input type="hidden" name="semester_aktif" value="{{ $data['semester_aktif'] }}">
                    <input type="hidden" name="tahun_ajaran" value="{{ $data['tahun_ajaran'] }}">

                    {{-- ============================================================ --}}
                    {{-- PAKET SEMESTER NORMAL --}}
                    {{-- ============================================================ --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-3">📚 Paket Semester {{ $data['semester_aktif'] }}</h3>
                        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 shadow-sm">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-200 text-slate-700 text-sm uppercase tracking-[0.2em]">
                                    <tr>
                                        <th class="px-6 py-4 text-center font-semibold">Pilih</th>
                                        <th class="px-6 py-4 text-left font-semibold">Kode</th>
                                        <th class="px-6 py-4 text-left font-semibold">Mata Kuliah</th>
                                        <th class="px-6 py-4 text-left font-semibold">Dosen</th>
                                        <th class="px-6 py-4 text-center font-semibold">SKS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200 text-sm text-slate-700">
                                    @foreach($data['paket_semester'] as $index => $paket)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 text-center">
                                                <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $paket['id'] ?? $index }}" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary paket-checkbox" data-sks="{{ $paket['sks'] }}">
                                            </td>
                                            <td class="px-6 py-4 font-medium text-slate-900">{{ $paket['kode'] }}</td>
                                            <td class="px-6 py-4">{{ $paket['matkul'] }}</td>
                                            <td class="px-6 py-4">{{ $paket['dosen'] }}</td>
                                            <td class="px-6 py-4 text-center">{{ $paket['sks'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- MATA KULIAH PENGULANGAN --}}
                    {{-- ============================================================ --}}
                    @if(count($data['mk_pengulangan']) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-amber-800 mb-3">
                                🔄 Mata Kuliah Pengulangan
                                <span class="text-sm font-normal text-amber-600 ml-2">(Nilai E dari semester sebelumnya - D tidak perlu diulang)</span>
                            </h3>
                            <div class="overflow-hidden rounded-[1.75rem] border border-amber-200 shadow-sm">
                                <table class="min-w-full divide-y divide-amber-200">
                                    <thead class="bg-amber-100 text-amber-800 text-sm uppercase tracking-[0.2em]">
                                        <tr>
                                            <th class="px-6 py-4 text-center font-semibold">Pilih</th>
                                            <th class="px-6 py-4 text-left font-semibold">Kode</th>
                                            <th class="px-6 py-4 text-left font-semibold">Mata Kuliah</th>
                                            <th class="px-6 py-4 text-center font-semibold">SKS</th>
                                            <th class="px-6 py-4 text-center font-semibold">Nilai Lama</th>
                                            <th class="px-6 py-4 text-center font-semibold">Semester Lama</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-amber-100 text-sm text-slate-700">
                                        @foreach($data['mk_pengulangan'] as $mk)
                                            <tr class="hover:bg-amber-50 transition">
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" name="mata_kuliah_retake_ids[]" value="{{ $mk['mata_kuliah_id'] }}" class="h-4 w-4 rounded border-amber-300 text-amber-500 focus:ring-amber-400 retake-checkbox" data-sks="{{ $mk['sks'] }}">
                                                </td>
                                                <td class="px-6 py-4 font-medium text-slate-900">{{ $mk['kode_mk'] }}</td>
                                                <td class="px-6 py-4">{{ $mk['nama_mk'] }}</td>
                                                <td class="px-6 py-4 text-center">{{ $mk['sks'] }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-white text-xs" style="background-color: {{ getNilaiColor($mk['nilai_lama']) }};">
                                                        {{ $mk['nilai_lama'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center text-slate-500">
                                                    Sem {{ $mk['semester_lama'] }} - {{ $mk['tahun_ajaran_lama'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @error('mata_kuliah_ids')
                        <div class="mb-4 text-sm text-red-600">{{ $message }}</div>
                    @enderror

                    <div class="flex justify-end">
                        <button id="btnKrs" type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#2C4A6B] px-8 py-3 text-sm font-semibold text-white shadow-lg hover:bg-slate-800 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ambil KRS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
    // Validasi SKS Maksimal 24
    const paketCheckboxes = document.querySelectorAll('.paket-checkbox');
    const retakeCheckboxes = document.querySelectorAll('.retake-checkbox');
    const sksWarning = document.getElementById('sksWarning');
    const btnKrs = document.getElementById('btnKrs');
    const MAX_SKS = 24;

    function hitungTotalSks() {
        let total = 0;
        paketCheckboxes.forEach(cb => {
            if (cb.checked) total += parseInt(cb.dataset.sks);
        });
        retakeCheckboxes.forEach(cb => {
            if (cb.checked) total += parseInt(cb.dataset.sks);
        });
        return total;
    }

    function validasiSks() {
        const totalSks = hitungTotalSks();

        if (totalSks > MAX_SKS) {
            sksWarning.classList.remove('hidden');
            btnKrs.disabled = true;
            btnKrs.classList.add('opacity-50', 'cursor-not-allowed');
            btnKrs.classList.remove('hover:bg-slate-800');
            return false;
        } else {
            sksWarning.classList.add('hidden');
            btnKrs.disabled = false;
            btnKrs.classList.remove('opacity-50', 'cursor-not-allowed');
            btnKrs.classList.add('hover:bg-slate-800');
            return true;
        }
    }

    function handleCheckboxChange(event) {
        const totalSks = hitungTotalSks();

        // Kalau total sudah > 24 dan user coba centang lagi, block
        if (totalSks > MAX_SKS) {
            event.target.checked = false;
            alert('⚠️ Total SKS tidak boleh melebihi ' + MAX_SKS + ' SKS. Silahkan kurangi pilihan mata kuliah Anda.');
        }

        validasiSks();
    }

    paketCheckboxes.forEach(cb => {
        cb.addEventListener('change', handleCheckboxChange);
    });

    retakeCheckboxes.forEach(cb => {
        cb.addEventListener('change', handleCheckboxChange);
    });

    // Validasi saat submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        const totalSks = hitungTotalSks();
        if (totalSks > MAX_SKS) {
            e.preventDefault();
            alert('⚠️ Total SKS (' + totalSks + ') melebihi batas maksimal ' + MAX_SKS + ' SKS. Silahkan kurangi pilihan mata kuliah Anda.');
        }
    });

    // Inisialisasi
    validasiSks();
    </script>
@endsection
