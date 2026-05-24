@extends('layouts.mahasiswa')

@section('page_title', 'Profil')

@section('content')
    {{-- Header Profile Card --}}
    <div class="nb-card mb-8">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="nb-avatar" style="width: 6rem; height: 6rem; font-size: 2.5rem;">
                {{ strtoupper(substr($data['nama'] ?? 'M', 0, 1)) }}
            </div>

            <div class="min-w-0 flex-1">
                <span class="nb-eyebrow">Mahasiswa</span>

                <h1 class="nb-h1 mt-1" style="font-size: 2rem;">
                    {{ $data['nama'] ?? '-' }}
                </h1>

                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <span class="nb-badge nb-badge-primary">
                        <span class="material-symbols-outlined" style="font-size:14px; margin-right:4px;">school</span>
                        {{ $data['program_studi'] ?? 'Teknik Informatika' }}
                    </span>

                    <span class="nb-badge nb-badge-stable">
                        NIM: {{ $data['nim'] ?? '-' }}
                    </span>

                    @if(!empty($data['kelas']) && $data['kelas'] !== '-')
                        <span class="nb-badge nb-badge-stable">
                            Kelas: {{ $data['kelas'] }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
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

    @if(session('warning'))
        <div class="nb-alert nb-alert-warning mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="nb-alert nb-alert-danger mb-6">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined">error</span>
                <strong>Terdapat kesalahan input</strong>
            </div>

            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Informasi Pribadi --}}
    <div class="nb-card mb-8">
        <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
            <div>
                <span class="nb-eyebrow">Data Diri</span>
                <h2 class="nb-h3 mt-1">Informasi Pribadi</h2>
            </div>

            <button type="button" onclick="toggleEdit()" class="nb-btn nb-btn-primary nb-btn-sm">
                <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                Edit Profil
            </button>
        </div>

        <form action="{{ route('pages.mahasiswa.profil.update') }}" method="POST" id="formProfil">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="nb-label">Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama', $data['nama'] ?? '') }}"
                        disabled
                        id="inputNama"
                    >
                    @error('nama')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $data['email'] ?? '') }}"
                        disabled
                        id="inputEmail"
                    >
                    @error('email')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">No. HP</label>
                    <input
                        type="text"
                        name="no_hp"
                        value="{{ old('no_hp', $data['no_hp'] ?? '') }}"
                        disabled
                        id="inputHp"
                    >
                    @error('no_hp')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Alamat</label>
                    <input
                        type="text"
                        name="alamat"
                        value="{{ old('alamat', $data['alamat'] ?? '') }}"
                        disabled
                        id="inputAlamat"
                    >
                    @error('alamat')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 hidden" id="buttonSimpan">
                <div class="flex gap-3 flex-wrap">
                    <button type="submit" class="nb-btn nb-btn-primary">
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        Simpan Perubahan
                    </button>

                    <button type="button" onclick="toggleEdit()" class="nb-btn nb-btn-secondary">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Ubah Kata Sandi --}}
    <div class="nb-card">
        <div class="flex items-start gap-4 mb-6 flex-wrap">
            <div class="nb-stat-icon" style="background-color: var(--color-warning-soft); color: var(--color-warning);">
                <span class="material-symbols-outlined">lock</span>
            </div>

            <div class="flex-1 min-w-0">
                <span class="nb-eyebrow">Keamanan</span>
                <h2 class="nb-h3 mt-1">Ubah Kata Sandi</h2>
                <p class="text-sm text-muted mt-1">Perbarui kata sandi akun Anda secara berkala.</p>
            </div>

            <button type="button" onclick="togglePassword()" class="nb-btn nb-btn-warning nb-btn-sm">
                <span class="material-symbols-outlined" style="font-size:16px;">key</span>
                Ubah Kata Sandi
            </button>
        </div>

        <form action="{{ route('pages.mahasiswa.profil.password') }}" method="POST" id="formPassword" class="hidden">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="nb-label">Kata Sandi Lama</label>
                    <input type="password" name="password_lama" placeholder="Masukkan kata sandi lama">
                    @error('password_lama')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Kata Sandi Baru</label>
                    <input type="password" name="password_baru" placeholder="Masukkan kata sandi baru">
                    @error('password_baru')
                        <p class="nb-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_baru_confirmation" placeholder="Ulangi kata sandi baru">
                </div>

                <div class="flex gap-3 pt-2 flex-wrap">
                    <button type="submit" class="nb-btn nb-btn-primary">
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        Simpan Password
                    </button>

                    <button type="button" onclick="togglePassword()" class="nb-btn nb-btn-secondary">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function toggleEdit() {
        const inputs = ['inputNama', 'inputEmail', 'inputHp', 'inputAlamat'];
        const buttonSimpan = document.getElementById('buttonSimpan');

        inputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) input.disabled = !input.disabled;
        });

        if (buttonSimpan) {
            buttonSimpan.classList.toggle('hidden');
        }
    }

    function togglePassword() {
        const form = document.getElementById('formPassword');

        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>
@endpush