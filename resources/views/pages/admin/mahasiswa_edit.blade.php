@extends('layouts.admin')

@section('title', 'Edit Mahasiswa')
@section('page_title', 'Edit Mahasiswa')

@section('content')
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Edit Data Mahasiswa</h1>
            <p>Update informasi mahasiswa dan akun login.</p>
        </div>
        <a href="{{ route('pages.admin.mahasiswa.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="nb-card max-w-3xl">
        <form action="{{ route('pages.admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="nb-section-header mb-8">
                <h2>Informasi Mahasiswa</h2>
                <p class="text-muted">Perbarui data mahasiswa di bawah ini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" placeholder="2021001001" required
                        class="w-full @error('nim') nb-input-error @enderror">
                    @error('nim')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" placeholder="Nama lengkap" required
                        class="w-full @error('nama') nb-input-error @enderror">
                    @error('nama')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="nb-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}" placeholder="email@univ.ac.id" required
                    class="w-full @error('email') nb-input-error @enderror">
                @error('email')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                    @php
                        $selectedProdi = old('prodi', optional($mahasiswa->prodi)->nama_prodi);
                    @endphp
                    <select name="prodi" required class="w-full @error('prodi') nb-input-error @enderror">
                        <option value="">Pilih prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi }}" {{ $selectedProdi == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                        @endforeach
                    </select>
                    @error('prodi')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Angkatan <span class="text-danger">*</span></label>
                    <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}" min="2000" max="2100" step="1" placeholder="2026" required
                        class="w-full @error('angkatan') nb-input-error @enderror">
                    @error('angkatan')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @php
                $kelasValue = strtoupper(str_replace(' ', '-', old('kelas', $mahasiswa->kelas ?? '')));
                preg_match('/^(.+?)(\d{1,2})([A-Z])-(.+)$/', $kelasValue, $kelasParts);
                $selectedSemesterKe = old('semester_ke_awal', $kelasParts[2] ?? 1);
                $selectedKelasGrup = old('kelas_grup', $kelasParts[3] ?? 'A');
                $selectedSesiKelas = old('sesi_kelas', $kelasParts[4] ?? 'PAGI');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="nb-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester_ke_awal" required class="w-full @error('semester_ke_awal') nb-input-error @enderror">
                        @for($i = 1; $i <= 14; $i++)
                            <option value="{{ $i }}" {{ (int) $selectedSemesterKe === $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester_ke_awal')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Grup Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_grup" required class="w-full @error('kelas_grup') nb-input-error @enderror">
                        @foreach($kelasGroups as $group)
                            <option value="{{ $group }}" {{ $selectedKelasGrup == $group ? 'selected' : '' }}>{{ $group }}</option>
                        @endforeach
                    </select>
                    @error('kelas_grup')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Sesi Kelas <span class="text-danger">*</span></label>
                    <select name="sesi_kelas" required class="w-full @error('sesi_kelas') nb-input-error @enderror">
                        @foreach($sesiOptions as $sesi)
                            <option value="{{ $sesi }}" {{ $selectedSesiKelas == $sesi ? 'selected' : '' }}>{{ $sesi }}</option>
                        @endforeach
                    </select>
                    @error('sesi_kelas')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Dosen Wali</label>
                    <select name="dosen_wali_id" class="w-full @error('dosen_wali_id') nb-input-error @enderror">
                        <option value="">Pilih dosen wali</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_wali_id', $mahasiswa->dosen_wali_id) == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                    @error('dosen_wali_id')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}" placeholder="081234567890"
                        class="w-full @error('no_hp') nb-input-error @enderror">
                    @error('no_hp')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="nb-label">Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                    class="w-full @error('password') nb-input-error @enderror">
                @error('password')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-8">
                <label class="nb-label">Alamat</label>
                <textarea name="alamat" rows="3" placeholder="Alamat lengkap"
                    class="w-full @error('alamat') nb-input-error @enderror">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                @error('alamat')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="nb-section-footer">
                <a href="{{ route('pages.admin.mahasiswa.index') }}" class="nb-btn nb-btn-secondary">
                    Batal
                </a>
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
