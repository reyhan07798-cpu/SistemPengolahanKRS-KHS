@extends('layouts.admin')

@section('title', 'Edit Tahun Ajaran')
@section('page_title', 'Edit Tahun Ajaran')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Edit Tahun Ajaran</h1>
            <p>Ubah informasi tahun ajaran {{ $tahunAjaran->tahun_ajaran ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('pages.admin.tahunajaran.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="nb-card max-w-2xl">
        <form action="{{ route('pages.admin.tahunajaran.update', $tahunAjaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="nb-section-header mb-8">
                <h2>Informasi Tahun Ajaran</h2>
                <p class="text-muted">Update data tahun ajaran di bawah ini</p>
            </div>

            {{-- Semester --}}
            <div class="mb-6">
                <label class="nb-label">Semester <span class="text-danger">*</span></label>
                <select name="semester" required class="w-full @error('semester') nb-input-error @enderror">
                    <option value="">Pilih Semester</option>
                    <option value="Ganjil" {{ old('semester', $tahunAjaran->semester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ old('semester', $tahunAjaran->semester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tahun Ajaran --}}
            <div class="mb-6">
                <label class="nb-label">Tahun Ajaran <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="tahun_ajaran"
                    value="{{ old('tahun_ajaran', $tahunAjaran->tahun_ajaran ?? '') }}"
                    placeholder="Contoh: 2026/2027"
                    pattern="[0-9]{4}/[0-9]{4}"
                    inputmode="numeric"
                    required
                    class="w-full @error('tahun_ajaran') nb-input-error @enderror"
                >
                @error('tahun_ajaran')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="status" value="1" {{ old('status') || ($tahunAjaran->status ?? '') === 'Aktif' ? 'checked' : '' }} class="w-4 h-4">
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
                    Update Tahun Ajaran
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="nb-alert nb-alert-info mt-6 max-w-2xl">
        <span class="material-symbols-outlined">info</span>
        <div>
            <strong>Catatan:</strong> Perubahan status tahun ajaran akan mempengaruhi KRS dan KHS yang berjalan.
        </div>
    </div>
@endsection
