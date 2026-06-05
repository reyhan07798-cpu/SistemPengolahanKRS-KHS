@extends('layouts.admin')

@section('title', 'Edit Dosen')
@section('page_title', 'Edit Dosen')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Edit Data Dosen</h1>
            <p>Update informasi dosen dan akun login.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="nb-card">
        <form action="{{ route('pages.admin.dosen.update', $dosen->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- NIP / NIK --}}
                <div>
                    <label class="nb-label">NIP / NIK <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="nik" 
                        value="{{ old('nik', $dosen->nik) }}" 
                        placeholder="198501012020011001"
                        class="@error('nik') is-invalid @enderror"
                        required
                    >
                    @error('nik')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="nb-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="nama" 
                        value="{{ old('nama', $dosen->nama) }}" 
                        placeholder="Nama lengkap"
                        class="@error('nama') is-invalid @enderror"
                        required
                    >
                    @error('nama')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="md:col-span-2">
                    <label class="nb-label">Email <span class="text-danger">*</span></label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $dosen->email) }}" 
                        placeholder="email@univ.ac.id"
                        class="@error('email') is-invalid @enderror"
                        required
                    >
                    @error('email')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Peran Dosen --}}
                <div>
                    <label class="nb-label">Peran Dosen <span class="text-danger">*</span></label>
                    <select 
                        name="tipe_dosen"
                        class="@error('tipe_dosen') is-invalid @enderror"
                        required
                    >
                        @php($selectedTipeDosen = old('tipe_dosen', $dosen->tipe_dosen))
                        <option value="">Pilih peran dosen</option>
                        <option value="keduanya" {{ in_array($selectedTipeDosen, ['keduanya', 'Dosen Wali']) ? 'selected' : '' }}>Dosen Wali & Matakuliah</option>
                        <option value="Dosen Mata Kuliah" {{ $selectedTipeDosen == 'Dosen Mata Kuliah' ? 'selected' : '' }}>Dosen Mata Kuliah</option>
                    </select>
                    @error('tipe_dosen')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Program Studi --}}
                <div>
                    <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                    <select 
                        name="fakultas"
                        class="@error('fakultas') is-invalid @enderror"
                        required
                    >
                        <option value="">Pilih Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi }}" {{ old('fakultas', $dosen->fakultas) == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                        @endforeach
                    </select>
                    @error('fakultas')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div>
                    <label class="nb-label">Nomor HP</label>
                    <input 
                        type="text" 
                        name="no_hp" 
                        value="{{ old('no_hp', $dosen->no_hp) }}" 
                        placeholder="081234567890"
                        class="@error('no_hp') is-invalid @enderror"
                    >
                    @error('no_hp')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="nb-label">Alamat</label>
                    <textarea 
                        name="alamat" 
                        rows="2" 
                        placeholder="Alamat lengkap"
                        class="@error('alamat') is-invalid @enderror"
                    >{{ old('alamat', $dosen->alamat) }}</textarea>
                    @error('alamat')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password (Optional) --}}
                <div class="md:col-span-2">
                    <label class="nb-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Masukkan password baru..."
                        class="@error('password') is-invalid @enderror"
                    >
                    <p class="text-muted text-sm mt-1">Password minimal 4 karakter</p>
                    @error('password')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="mt-8 flex gap-3 justify-end">
                <a href="{{ route('pages.admin.dosen.index') }}" class="nb-btn nb-btn-secondary">
                    Batal
                </a>
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="nb-alert nb-alert-info mt-6">
        <div class="flex gap-3">
            <span class="material-symbols-outlined flex-shrink-0">info</span>
            <div>
                <strong>Informasi Login Dosen</strong>
                <p class="text-sm mt-1">Dosen dapat login dengan menggunakan NIP/NIK atau Email sebagai username, dan password yang telah ditetapkan.</p>
            </div>
        </div>
    </div>
@endsection
