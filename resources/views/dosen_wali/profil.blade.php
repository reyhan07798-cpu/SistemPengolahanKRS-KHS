@extends('layouts.dosen_wali')

@section('content')
    <!-- Header Profile -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center text-white text-4xl font-bold">
                {{ substr(explode(' ', $dosen['nama'])[0], 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-dark">{{ $dosen['nama'] }}</h1>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                        {{ $dosen['nip'] }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $dosen['program_studi'] }}
                    </span>
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

        <form action="{{ route('profil.update') }}" method="POST" id="formProfil">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" name="nama" value="{{ $dosen['nama'] }}" 
                               class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" 
                               disabled id="inputNama">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ $dosen['email'] }}" 
                               class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" 
                               disabled id="inputEmail">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input type="text" name="no_hp" value="{{ $dosen['no_hp'] }}" 
                               class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" 
                               disabled id="inputHp">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-start pointer-events-none">
                            <svg class="h-9 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="alamat" value="{{ $dosen['alamat'] }}" 
                               class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" 
                               disabled id="inputAlamat">
                    </div>
                </div>
            </div>

            <div class="mt-6 hidden" id="buttonSimpan">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="toggleEdit()" class="px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition ml-2">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <!-- Ubah Kata Sandi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start gap-4 mb-6">
            <div class="p-3 bg-gray-100 rounded-lg">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-dark">Ubah Kata Sandi</h2>
                <p class="text-sm text-gray-500">Perbarui kata sandi akun Anda</p>
            </div>
            <button onclick="togglePassword()" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                Ubah Kata Sandi
            </button>
        </div>

        <form action="{{ route('profil.password') }}" method="POST" id="formPassword" class="hidden">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                    <input type="password" name="password_lama" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password_baru" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_baru_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        Simpan Password
                    </button>
                    <button type="button" onclick="togglePassword()" class="px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleEdit() {
            const inputs = ['inputNama', 'inputEmail', 'inputHp', 'inputAlamat'];
            const buttonSimpan = document.getElementById('buttonSimpan');
            
            inputs.forEach(id => {
                const input = document.getElementById(id);
                input.disabled = !input.disabled;
            });
            
            buttonSimpan.classList.toggle('hidden');
        }

        function togglePassword() {
            const formPassword = document.getElementById('formPassword');
            formPassword.classList.toggle('hidden');
        }
    </script>
@endsection