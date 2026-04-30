@extends('layouts.dosen')

@section('content')
    <!-- Header Profile -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
        <div class="flex items-center gap-6">
            <div style="display:flex;align-items:center;justify-content:center;width:96px;height:96px;border-radius:50%;background:#2F5D8A;color:white;font-size:2rem;font-weight:700;flex-shrink:0;">
                {{ substr(explode(' ', $dosen['nama'])[0], 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-dark">{{ $dosen['nama'] }}</h1>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <span>NIK: {{ $dosen['nidn'] }}</span>
                    <span>{{ $dosen['program_studi'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pribadi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-dark">Informasi Pribadi</h2>
            <button onclick="toggleEdit()" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                Edit Profil
            </button>
        </div>

        <form action="{{ route('dosen.profil.update') }}" method="POST" id="formProfil">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $dosen['nama'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" disabled id="inputNama">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $dosen['email'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" disabled id="inputEmail">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                    <input type="text" name="no_hp" value="{{ $dosen['no_hp'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" disabled id="inputHp">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <input type="text" name="alamat" value="{{ $dosen['alamat'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" disabled id="inputAlamat">
                </div>
            </div>
            <div class="mt-6 hidden" id="buttonSimpan">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">Simpan Perubahan</button>
                <button type="button" onclick="toggleEdit()" class="px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg ml-2">Batal</button>
            </div>
        </form>
    </div>

    <!-- Ubah Kata Sandi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold text-dark">Ubah Kata Sandi</h2>
                <p class="text-sm text-gray-500">Perbarui kata sandi akun Anda</p>
            </div>
            <button onclick="togglePassword()" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                Ubah Kata Sandi
            </button>
        </div>

        <form action="{{ route('dosen.profil.password') }}" method="POST" id="formPassword" class="hidden">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                    <input type="password" name="password_lama" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password_baru" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_baru_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">Simpan Password</button>
                    <button type="button" onclick="togglePassword()" class="px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg">Batal</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleEdit() {
            ['inputNama','inputEmail','inputHp','inputAlamat'].forEach(id => {
                const el = document.getElementById(id);
                el.disabled = !el.disabled;
            });
            document.getElementById('buttonSimpan').classList.toggle('hidden');
        }
        function togglePassword() {
            document.getElementById('formPassword').classList.toggle('hidden');
        }
    </script>
@endsection
