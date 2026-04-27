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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <x-krs-package-card title="Semester" :value="$data['semester_aktif']" accent="bg-slate-100"
            textColor="text-slate-900"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' />

        <x-krs-package-card title="Total SKS" :value="$data['total_sks']" accent="bg-emerald-100"
            textColor="text-emerald-700"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' />

        <x-krs-package-card title="Status KRS" :value="$data['status_krs']" accent="bg-blue-100" textColor="text-blue-700"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 21c4.97 0 9-4.03 9-9S16.97 3 12 3 3 7.03 3 12s4.03 9 9 9z"/></svg>' />
    </div>

    <div class="bg-slate-100 rounded-[2rem] p-6 mb-8">
        <div class="bg-white rounded-[1.75rem] shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Paket Semester</h2>
                    <p class="text-sm text-slate-500">Daftar mata kuliah yang tersedia untuk semester aktif.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('pages.mahasiswa.store-krs') }}">
                @csrf

                <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 shadow-sm mb-6">
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
                                        <input type="checkbox" name="mata_kuliah_ids[]" value="{{ $paket['id'] ?? $index }}" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
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

                @error('mata_kuliah_ids')
                    <div class="mb-4 text-sm text-red-600">{{ $message }}</div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-3xl p-5 text-center shadow-sm border border-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">SKS Total</p>
                        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $data['total_sks'] }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-3xl p-5 text-center shadow-sm border border-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Mata Kuliah</p>
                        <p class="mt-3 text-3xl font-bold text-slate-900">{{ count($data['paket_semester']) }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-3xl p-5 text-center shadow-sm border border-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Semester</p>
                        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $data['semester_aktif'] }}</p>
                        <p class="text-sm text-slate-500 mt-1">Aktif</p>
                    </div>
                </div>

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
@endsection
