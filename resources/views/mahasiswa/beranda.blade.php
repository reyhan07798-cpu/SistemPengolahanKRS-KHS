@extends('layouts.mahasiswa')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Beranda Mahasiswa</h1>
        <p class="text-sm text-gray-500">Selamat datang, {{ $data['nama'] }}</p>
    </header>

    <!-- Statistik Cards --> 
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <x-stat-card title="Semester Aktif"
            :value="$data['semester_aktif']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
            bgColor="bg-gray-100" textColor="text-gray-500" />

        <x-stat-card title="Total SKS" :value="$data['total_sks']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
            bgColor="bg-gray-100" textColor="text-gray-500" />

        <x-stat-card title="IPK" :value="number_format($data['ipk'], 2)"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
            bgColor="bg-green-100" textColor="text-green-600" />

        <x-stat-card title="Mata Kuliah Lulus" :value="$data['mata_kuliah_lulus']"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
            bgColor="bg-red-100" textColor="text-red-500" />
    </div>

    <!-- Informasi Mahasiswa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-slate-900 mb-6">Informasi Mahasiswa</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-info-box label="NIM" :value="$data['nim']" />
                <x-info-box label="Nama" :value="$data['nama']" />
                <x-info-box label="Program Studi" :value="$data['prodi']" />
            </div>
            <div>
                <x-info-box label="Angkatan" :value="$data['angkatan']" />
                <x-info-box label="Email" :value="$data['email']" />
                <x-info-box label="Semester Aktif" :value="$data['semester_aktif']" />
            </div>
        </div>
    </div>

    <!-- Nilai Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-slate-900 mb-6">Nilai Terbaru</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata
                            Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['nilai_terbaru'] as $nilai)
                        <x-grade-row :matkul="$nilai['matkul']" :sks="$nilai['sks']" :nilai="$nilai['nilai']"
                            :bobot="$nilai['bobot']" />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- KRS Aktif -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-lg text-slate-900 mb-6">KRS Aktif</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata
                            Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['krs_aktif'] as $krs)
                        <x-krs-table-row :kode="$krs['kode']" :matkul="$krs['matkul']" :sks="$krs['sks']"
                            :status="$krs['status']" />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection