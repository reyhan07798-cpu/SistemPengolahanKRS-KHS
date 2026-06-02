@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')
@section('page_title', 'Tambah Tahun Ajaran Baru')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Tambah Tahun Ajaran</h1>
            <p>Form untuk menambahkan tahun ajaran baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('pages.admin.tahunajaran.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="nb-card max-w-2xl">
        <form action="{{ route('pages.admin.tahunajaran.store') }}" method="POST">
            @csrf

            <div class="nb-section-header mb-8">
                <h2>Informasi Tahun Ajaran</h2>
                <p class="text-muted">Lengkapi data tahun ajaran di bawah ini</p>
            </div>

            {{-- Semester --}}
            <div class="mb-6">
                <label class="nb-label">Semester <span class="text-danger">*</span></label>
                <select name="semester" required class="w-full @error('semester') nb-input-error @enderror">
                    <option value="">Pilih Semester</option>
                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tahun Ajaran --}}
            <div class="mb-6">
                <label class="nb-label">Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="tahun_ajaran" required class="w-full @error('tahun_ajaran') nb-input-error @enderror">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunOptions as $tahun)
                        <option value="{{ $tahun }}" {{ old('tahun_ajaran') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
                @error('tahun_ajaran')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }} class="w-4 h-4">
                    <span class="nb-label mb-0">Jadikan tahun ajaran aktif</span>
                </label>
                <p class="text-muted text-sm mt-2">Tahun ajaran aktif akan digunakan sebagai tahun ajaran berjalan dalam sistem.</p>
            </div>

            {{-- Form Actions --}}
            <div class="flex gap-3 justify-end pt-6 border-t">
                <a href="{{ route('pages.admin.tahunajaran.index') }}" class="nb-btn nb-btn-secondary">
                    Batal
                </a>
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:20px;">save</span>
                    Simpan Tahun Ajaran
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="nb-alert nb-alert-info mt-6 max-w-2xl">
        <span class="material-symbols-outlined">info</span>
        <div>
            <strong>Catatan:</strong> Tahun ajaran yang ditambahkan akan tersedia untuk pengaturan KRS dan KHS mahasiswa.
        </div>
    </div>
@endsection
