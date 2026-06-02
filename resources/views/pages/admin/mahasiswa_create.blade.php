@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')
@section('page_title', 'Tambah Mahasiswa Baru')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Data Mahasiswa</span>
            <h1 class="mt-2">Tambah Mahasiswa</h1>
            <p>Form untuk menambahkan mahasiswa baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('pages.admin.mahasiswa.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="nb-card max-w-3xl">
        <form action="{{ route('pages.admin.mahasiswa.store') }}" method="POST">
            @csrf

            <div class="nb-section-header mb-8">
                <h2>Informasi Mahasiswa</h2>
                <p class="text-muted">Lengkapi data mahasiswa di bawah ini</p>
            </div>

            {{-- NIM & Nama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim') }}" placeholder="2021001001" required
                        class="w-full @error('nim') nb-input-error @enderror">
                    @error('nim')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" required
                        class="w-full @error('nama') nb-input-error @enderror">
                    @error('nama')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="nb-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@univ.ac.id" required
                    class="w-full @error('email') nb-input-error @enderror">
                @error('email')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Program Studi & Angkatan & Kelas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                    <select name="prodi" required class="w-full @error('prodi') nb-input-error @enderror">
                        <option value="">Pilih prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                        @endforeach
                    </select>
                    @error('prodi')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Angkatan <span class="text-danger">*</span></label>
                    <select name="angkatan" required class="w-full @error('angkatan') nb-input-error @enderror">
                        <option value="">Pilih Angkatan</option>
                        @foreach($angkatans as $angkatan)
                            <option value="{{ $angkatan }}" {{ old('angkatan') == $angkatan ? 'selected' : '' }}>{{ $angkatan }}</option>
                        @endforeach
                    </select>
                    @error('angkatan')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas" required class="w-full @error('kelas') nb-input-error @enderror">
                        <option value="">Pilih kelas</option>
                        <option value="A" {{ old('kelas') == 'A' ? 'selected' : '' }}>Kelas A</option>
                        <option value="B" {{ old('kelas') == 'B' ? 'selected' : '' }}>Kelas B</option>
                        <option value="C" {{ old('kelas') == 'C' ? 'selected' : '' }}>Kelas C</option>
                        <option value="D" {{ old('kelas') == 'D' ? 'selected' : '' }}>Kelas D</option>
                    </select>
                    @error('kelas')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Dosen Wali & No. HP --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Dosen Wali <span class="text-danger">*</span></label>
                    <select name="dosen_wali" required class="w-full @error('dosen_wali') nb-input-error @enderror">
                        <option value="">Pilih dosen wali</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->nama }}" {{ old('dosen_wali') == $dosen->nama ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                    @error('dosen_wali')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890"
                        class="w-full @error('no_hp') nb-input-error @enderror">
                    @error('no_hp')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Password & Alamat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Password Default <span class="text-danger">*</span></label>
                    <input type="text" name="password" value="{{ old('password', 'mhs123') }}" placeholder="mhs123" required
                        class="w-full @error('password') nb-input-error @enderror">
                    @error('password')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-8">
                <label class="nb-label">Alamat</label>
                <textarea name="alamat" rows="3" placeholder="Alamat lengkap"
                    class="w-full @error('alamat') nb-input-error @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="nb-section-footer">
                <a href="{{ route('pages.admin.mahasiswa.index') }}" class="nb-btn nb-btn-secondary">
                    Batal
                </a>
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                    Simpan Mahasiswa
                </button>
            </div>
        </form>
    </div>
@endsection
