@extends('layouts.admin')

@section('title', 'Edit Mata Kuliah')
@section('page_title', 'Edit Mata Kuliah')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Edit Mata Kuliah</h1>
            <p>Ubah informasi mata kuliah {{ $matakuliah->kode ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('pages.admin.matakuliah.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="nb-card max-w-3xl">
        <form action="{{ route('pages.admin.matakuliah.update', $matakuliah->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="nb-section-header mb-8">
                <h2>Informasi Mata Kuliah</h2>
                <p class="text-muted">Update data mata kuliah di bawah ini</p>
            </div>

            {{-- Kode & Nama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $matakuliah->kode ?? '') }}" placeholder="IF101" required
                        class="w-full @error('kode') nb-input-error @enderror">
                    @error('kode')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">SKS <span class="text-danger">*</span></label>
                    <select name="sks" required class="w-full @error('sks') nb-input-error @enderror">
                        <option value="">Pilih SKS</option>
                        <option value="1" {{ old('sks', $matakuliah->sks ?? '') == '1' ? 'selected' : '' }}>1 SKS</option>
                        <option value="2" {{ old('sks', $matakuliah->sks ?? '') == '2' ? 'selected' : '' }}>2 SKS</option>
                        <option value="3" {{ old('sks', $matakuliah->sks ?? '') == '3' ? 'selected' : '' }}>3 SKS</option>
                        <option value="4" {{ old('sks', $matakuliah->sks ?? '') == '4' ? 'selected' : '' }}>4 SKS</option>
                        <option value="5" {{ old('sks', $matakuliah->sks ?? '') == '5' ? 'selected' : '' }}>5 SKS</option>
                        <option value="6" {{ old('sks', $matakuliah->sks ?? '') == '6' ? 'selected' : '' }}>6 SKS</option>
                    </select>
                    @error('sks')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Nama Mata Kuliah --}}
            <div class="mb-6">
                <label class="nb-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $matakuliah->nama ?? '') }}" placeholder="Pemrograman Web" required
                    class="w-full @error('nama') nb-input-error @enderror">
                @error('nama')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Semester & Dosen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester" required class="w-full @error('semester') nb-input-error @enderror">
                        <option value="">Pilih Semester</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester', $matakuliah->semester ?? '') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Dosen Pengampu <span class="text-danger">*</span></label>
                    <input type="text" name="dosen_pengampu" value="{{ old('dosen_pengampu', $matakuliah->dosen_pengampu ?? '') }}" placeholder="Dr. Budi Santoso" required
                        class="w-full @error('dosen_pengampu') nb-input-error @enderror">
                    @error('dosen_pengampu')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Jadwal Section --}}
            <div class="nb-section-header mb-6">
                <h3 class="nb-h3">Jadwal Perkuliahan</h3>
            </div>

            {{-- Parse jadwal if exists --}}
            @php
                $jadwal = $matakuliah->jadwal ?? '';
                $jadwalParts = explode(', ', $jadwal);
                $hari = $jadwalParts[0] ?? '';
                $jam = $jadwalParts[1] ?? '07:00 - 08:40';
                $ruang = $jadwalParts[2] ?? '';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="nb-label">Hari <span class="text-danger">*</span></label>
                    <select name="hari" required class="w-full @error('hari') nb-input-error @enderror">
                        <option value="">Pilih Hari</option>
                        <option value="Senin" {{ old('hari', $hari) == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('hari', $hari) == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('hari', $hari) == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('hari', $hari) == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('hari', $hari) == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('hari', $hari) == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                    @error('hari')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Jam <span class="text-danger">*</span></label>
                    <input type="text" name="jam" value="{{ old('jam', $jam) }}" placeholder="07:00 - 08:40" required
                        class="w-full @error('jam') nb-input-error @enderror">
                    @error('jam')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Ruang/Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="ruang" value="{{ old('ruang', $ruang) }}" placeholder="Lab Komputer 1" required
                        class="w-full @error('ruang') nb-input-error @enderror">
                    @error('ruang')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex gap-3 justify-end mt-8 pt-6 border-t">
                <a href="{{ route('pages.admin.matakuliah.index') }}" class="nb-btn nb-btn-secondary">
                    Batal
                </a>
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:20px;">save</span>
                    Update Mata Kuliah
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="nb-alert nb-alert-info mt-6 max-w-3xl">
        <span class="material-symbols-outlined">info</span>
        <div>
            <strong>Catatan:</strong> Perubahan yang dilakukan akan langsung mempengaruhi data paket KRS yang menggunakan mata kuliah ini.
        </div>
    </div>
@endsection
