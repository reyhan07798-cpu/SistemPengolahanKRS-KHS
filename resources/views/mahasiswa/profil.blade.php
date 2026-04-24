@extends('layouts.mahasiswa')

@section('content')

    <!-- Header Profile -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center text-white text-4xl font-bold">
                {{ substr(explode(' ', $data['nama'])[0], 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-dark">{{ $data['nama'] }}</h1>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <!-- Badge Mahasiswa dengan Icon -->
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        Teknik Informatika
                    </span>

                    <!-- Badge NIM -->
                    <span
                        class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                        NIM : {{ $data['nim'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pribadi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-dark">Informasi Pribadi</h2>
            <button onclick="toggleEdit()"
                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                Edit Profil
            </button>
        </div>

        {{-- ✅ Tampilkan pesan sukses jika ada --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Tampilkan error validasi jika ada --}}
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.profil.update') }}" method="POST" id="formProfil"> @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="nama" value="{{ old('nama', $data['nama']) }}"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            disabled id="inputNama">
                    </div>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $data['email']) }}"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            disabled id="inputEmail">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $data['no_hp']) }}"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            disabled id="inputHp">
                    </div>
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat (full width) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-start pointer-events-none">
                            <svg class="h-9 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="alamat" value="{{ $data['alamat'] }}"
                            class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            disabled id="inputAlamat">
                    </div>
                </div>
            </div>
            @error('alamat')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
    </div>

    </div>

    <!-- Button Simpan -->
    <div class="mt-6 hidden" id="buttonSimpan">
        <button type="submit"
            class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
            Simpan Perubahan
        </button>
        <button type="button" onclick="toggleEdit()"
            class="px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition ml-2">
            Batal
        </button>
    </div>
    </form>
    </div>

    <!-- Ubah Kata Sandi (Opsional - Bisa Dihapus Jika Tidak Dipakai) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start gap-4 mb-6">
            <div class="p-3 bg-gray-100 rounded-lg">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-dark">Ubah Kata Sandi</h2>
                <p class="text-sm text-gray-500">Perbarui kata sandi akun Anda</p>
            </div>
            <button onclick="togglePassword()"
                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary transition">
                Ubah Kata Sandi
            </button>
        </div>

        <form action="#" method="POST" id="formPassword" class="hidden">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <input type="password" name="password_lama" placeholder="Kata sandi lama"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <input type="password" name="password_baru" placeholder="Kata sandi baru"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <input type="password" name="password_baru_confirmation" placeholder="Konfirmasi kata sandi baru"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">

                <div class="flex gap-2 pt-4">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan Password
                    </button>
                    <button type="button" onclick="togglePassword()" class="px-6 py-2 bg-gray-300 rounded-lg">
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
                if (input) {
                    input.disabled = !input.disabled;
                }
            });

            buttonSimpan.classList.toggle('hidden');
        }

        function togglePassword() {
            const form = document.getElementById('formPassword');
            if (form) {
                form.classList.toggle('hidden');
            }
        }
    </script>

@endsection