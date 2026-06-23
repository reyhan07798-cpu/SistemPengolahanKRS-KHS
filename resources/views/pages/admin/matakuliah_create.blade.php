@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')
@section('page_title', 'Tambah Mata Kuliah Baru')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Tambah Mata Kuliah</h1>
            <p>Form untuk menambahkan mata kuliah baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('pages.admin.matakuliah.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="nb-card max-w-3xl">
        <form action="{{ route('pages.admin.matakuliah.store') }}" method="POST">
            @csrf

            <div class="nb-section-header mb-8">
                <h2>Informasi Mata Kuliah</h2>
                <p class="text-muted">Lengkapi data mata kuliah di bawah ini</p>
            </div>

            {{-- Kode & Nama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" name="kode_mk" value="{{ old('kode_mk') }}" placeholder="IF101" required
                        class="w-full @error('kode_mk') nb-input-error @enderror">
                    @error('kode_mk')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">SKS <span class="text-danger">*</span></label>
                    <select name="sks" required class="w-full @error('sks') nb-input-error @enderror">
                        <option value="">Pilih SKS</option>
                        <option value="1" {{ old('sks') == '1' ? 'selected' : '' }}>1 SKS</option>
                        <option value="2" {{ old('sks') == '2' ? 'selected' : '' }}>2 SKS</option>
                        <option value="3" {{ old('sks') == '3' ? 'selected' : '' }}>3 SKS</option>
                        <option value="4" {{ old('sks') == '4' ? 'selected' : '' }}>4 SKS</option>
                        <option value="5" {{ old('sks') == '5' ? 'selected' : '' }}>5 SKS</option>
                        <option value="6" {{ old('sks') == '6' ? 'selected' : '' }}>6 SKS</option>
                    </select>
                    @error('sks')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Nama Mata Kuliah --}}
            <div class="mb-6">
                <label class="nb-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Pemrograman Web" required
                    class="w-full @error('nama') nb-input-error @enderror">
                @error('nama')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Semester & Dosen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester_ke" required class="w-full @error('semester_ke') nb-input-error @enderror">
                        <option value="">Pilih Semester</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester_ke') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester_ke')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                    <select id="input_prodi" name="prodi" required class="w-full @error('prodi') nb-input-error @enderror">
                        <option value="">Pilih Program Studi</option>
                        @foreach($prodis ?? [] as $prodi)
                            <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                        @endforeach
                    </select>
                    @error('prodi')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="nb-label">Dosen Pengampu</label>
                <select id="input_dosen_id" name="dosen_id" class="w-full @error('dosen_id') nb-input-error @enderror">
                    <option value="">Pilih prodi dulu</option>
                    @foreach($dosens ?? [] as $dosen)
                        <option
                            value="{{ $dosen->id }}"
                            data-prodi="{{ $dosen->fakultas ?? '' }}"
                            data-role="{{ $dosen->tipe_dosen ?? '' }}"
                            {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}
                        >
                            {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_id')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Jadwal Section --}}
            <div class="nb-section-header mb-6">
                <h3 class="nb-h3">Jadwal Perkuliahan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="nb-label">Hari <span class="text-danger">*</span></label>
                    <select name="hari" required class="w-full @error('hari') nb-input-error @enderror">
                        <option value="">Pilih Hari</option>
                        <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                    @error('hari')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Jam <span class="text-danger">*</span></label>
                    <input type="text" name="jam" value="{{ old('jam', '07:00 - 08:40') }}" placeholder="07:00 - 08:40" required
                        class="w-full @error('jam') nb-input-error @enderror">
                    @error('jam')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Ruang/Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="ruang" value="{{ old('ruang') }}" placeholder="Lab Komputer 1" required
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
                    Simpan Mata Kuliah
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="nb-alert nb-alert-info mt-6 max-w-3xl">
        <span class="material-symbols-outlined">info</span>
        <div>
            <strong>Catatan:</strong> Mata kuliah yang ditambahkan akan muncul di daftar mata kuliah dan dapat digunakan dalam paket KRS.
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const prodiSelect = document.getElementById('input_prodi');
    const dosenSelect = document.getElementById('input_dosen_id');
    const dosenOptionItems = Array.from(dosenSelect?.options || []).filter(option => option.value);

    function normalizeValue(value) {
        return String(value || '').trim().toLowerCase();
    }

    function isDosenMataKuliah(option) {
        const role = normalizeValue(option.dataset.role);

        return role === 'keduanya' || role.includes('mata kuliah') || role.includes('mk');
    }

    function filterDosenOptions() {
        if (!prodiSelect || !dosenSelect) return;

        const selectedProdi = prodiSelect.value;
        const normalizedProdi = normalizeValue(selectedProdi);
        let visibleCount = 0;
        let selectedOptionVisible = !dosenSelect.value;

        dosenSelect.options[0].textContent = selectedProdi ? '-- Pilih Dosen --' : 'Pilih prodi dulu';

        dosenOptionItems.forEach(option => {
            const match = normalizedProdi &&
                normalizeValue(option.dataset.prodi) === normalizedProdi &&
                isDosenMataKuliah(option);

            option.hidden = !match;
            option.disabled = !match;

            if (match) visibleCount++;
            if (option.selected && match) selectedOptionVisible = true;
        });

        if (!selectedOptionVisible) {
            dosenSelect.value = '';
        }

        dosenSelect.options[0].textContent = selectedProdi && visibleCount === 0
            ? 'Tidak ada dosen mata kuliah untuk prodi ini'
            : dosenSelect.options[0].textContent;
    }

    prodiSelect?.addEventListener('change', filterDosenOptions);
    document.addEventListener('DOMContentLoaded', filterDosenOptions);
</script>
@endpush
