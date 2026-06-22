@extends('layouts.admin')

@section('title', 'Tambah Paket Mata Kuliah')
@section('page_title', 'Tambah Paket Mata Kuliah Baru')

@section('content')
    {{-- Page Header --}}
    <div class="nb-page-header">
        <div>
            <span class="nb-eyebrow">Master Data</span>
            <h1 class="mt-2">Tambah Paket Mata Kuliah</h1>
            <p>Form untuk membuat paket mata kuliah baru untuk KRS mahasiswa.</p>
        </div>
        <a href="{{ route('pages.admin.paketmk.index') }}" class="nb-btn nb-btn-secondary">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="nb-card max-w-4xl">
        <form action="{{ route('pages.admin.paketmk.store') }}" method="POST">
            @csrf

            <div class="nb-section-header mb-8">
                <h2>Informasi Paket Mata Kuliah</h2>
                <p class="text-muted">Lengkapi data paket mata kuliah di bawah ini</p>
            </div>

            {{-- Nama Paket --}}
            <div class="mb-6">
                <label class="nb-label">Nama Paket <span class="text-danger">*</span></label>
                <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" placeholder="Paket Semester 1 Teknik Informatika" required
                    class="w-full @error('nama_paket') nb-input-error @enderror">
                @error('nama_paket')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Semester & Prodi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="nb-label">Semester <span class="text-danger">*</span></label>
                    <select name="semester" required class="w-full @error('semester') nb-input-error @enderror">
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $s)
                            <option value="{{ $s }}" {{ old('semester') == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                        @endforeach
                    </select>
                    @error('semester')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="nb-label">Program Studi <span class="text-danger">*</span></label>
                    <select name="prodi" required class="w-full @error('prodi') nb-input-error @enderror">
                        <option value="">Pilih Program Studi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi }}" {{ old('prodi') == $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                        @endforeach
                    </select>
                    @error('prodi')
                        <span class="nb-error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-8">
                <label class="nb-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi paket mata kuliah..."
                    class="w-full @error('deskripsi') nb-input-error @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="nb-error-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- Mata Kuliah Section --}}
            <div class="nb-section-header mb-6">
                <h3 class="nb-h3">Pilih Mata Kuliah</h3>
                <p class="text-muted text-sm">Pilih mata kuliah yang akan dimasukkan dalam paket ini</p>
            </div>

            {{-- Mata Kuliah Checklist --}}
            <div class="mb-6 bg-light rounded-lg p-4 border">
                @if($allMataKuliah->count() > 0)

                    {{-- Pilih Semua --}}
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded bg-white border mb-4 font-semibold">
                        <input type="checkbox" id="selectAllMataKuliah">
                        <span>Pilih Semua Mata Kuliah</span>
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($allMataKuliah as $mk)
                            <label
                                class="mk-option flex items-start gap-3 cursor-pointer p-3 rounded hover:bg-white transition"
                                data-semester="{{ $mk->semester_ke }}"
                                data-prodi="{{ $mk->prodi }}"
                            >
                                <input type="checkbox" name="mata_kuliah[]" value="{{ $mk->id }}"
                                    data-sks="{{ $mk->sks }}"
                                    {{ in_array($mk->id, old('mata_kuliah', [])) ? 'checked' : '' }} class="mt-1 mk-checkbox">
                                <div class="flex-1">
                                    <div class="font-medium">{{ $mk->kode }}</div>
                                    <div class="text-sm text-muted">{{ $mk->nama }}</div>
                                    <div class="text-xs text-primary font-medium mt-1">
                                        {{ $mk->sks }} SKS - Semester {{ $mk->semester_ke }} - {{ $mk->prodi }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-6">Tidak ada mata kuliah tersedia</p>
                @endif

                @error('mata_kuliah')
                    <div class="nb-error-text mt-3">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ringkasan --}}
            <div class="bg-info-light rounded-lg p-4 mb-8">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-info mt-1" style="font-size:20px;">info</span>
                    <div>
                        <div class="font-medium text-info">Ringkasan Paket</div>
                        <div class="text-sm text-info-dark mt-1">
                            <div>Total Mata Kuliah: <span id="jumlahMk" class="font-bold">0</span></div>
                            <div>Total SKS: <span id="totalSks" class="font-bold">0</span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex gap-3 justify-end pt-6 border-t">
                <a href="{{ route('pages.admin.paketmk.index') }}" class="nb-btn nb-btn-secondary">
                    Batal
                </a>
                <button type="submit" class="nb-btn nb-btn-primary">
                    <span class="material-symbols-outlined" style="font-size:20px;">save</span>
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const mkData = @json($allMataKuliah);
    const semesterSelect = document.querySelector('select[name="semester"]');
    const prodiSelect = document.querySelector('select[name="prodi"]');
    const mkOptions = document.querySelectorAll('.mk-option');
    const selectAllMataKuliah = document.getElementById('selectAllMataKuliah');

    function syncProdiOptions() {
        const semester = String(semesterSelect?.value || '');
        const availableProdis = new Set(
            mkData
                .filter(mk => !semester || String(mk.semester_ke || '') === semester)
                .map(mk => mk.prodi)
                .filter(Boolean)
        );

        Array.from(prodiSelect?.options || []).forEach(option => {
            option.hidden = option.value && availableProdis.size > 0 && !availableProdis.has(option.value);
        });

        if (prodiSelect?.value && prodiSelect.selectedOptions[0]?.hidden) {
            prodiSelect.value = '';
        }
    }

    function getVisibleCheckboxes() {
        return Array.from(document.querySelectorAll('.mk-option'))
            .filter(option => !option.classList.contains('hidden'))
            .map(option => option.querySelector('input[name="mata_kuliah[]"]'))
            .filter(Boolean);
    }

    function updateSelectAllState() {
        if (!selectAllMataKuliah) return;

        const visibleCheckboxes = getVisibleCheckboxes();

        if (visibleCheckboxes.length === 0) {
            selectAllMataKuliah.checked = false;
            selectAllMataKuliah.indeterminate = false;
            selectAllMataKuliah.disabled = true;
            return;
        }

        const checkedCount = visibleCheckboxes.filter(checkbox => checkbox.checked).length;

        selectAllMataKuliah.disabled = false;
        selectAllMataKuliah.checked = checkedCount === visibleCheckboxes.length;
        selectAllMataKuliah.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length;
    }

    function filterMataKuliah() {
        const semester = String(semesterSelect?.value || '');
        const prodi = String(prodiSelect?.value || '');

        mkOptions.forEach(option => {
            const isVisible = (!semester || String(option.dataset.semester || '') === semester)
                && (!prodi || String(option.dataset.prodi || '') === prodi);

            option.classList.toggle('hidden', !isVisible);

            if (!isVisible) {
                const checkbox = option.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
        });

        updateSelectAllState();
    }

    function updateSummary() {
        const checkboxes = document.querySelectorAll('input[name="mata_kuliah[]"]:checked');
        let totalSks = 0;

        checkboxes.forEach(checkbox => {
            const mkId = parseInt(checkbox.value);
            const mk = mkData.find(m => Number(m.id) === mkId);
            if (mk) totalSks += Number(mk.sks || 0);
        });

        document.getElementById('jumlahMk').textContent = checkboxes.length;
        document.getElementById('totalSks').textContent = totalSks;

        updateSelectAllState();
    }

    document.querySelectorAll('input[name="mata_kuliah[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });

    selectAllMataKuliah?.addEventListener('change', function () {
        const visibleCheckboxes = getVisibleCheckboxes();

        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });

        updateSummary();
    });

    semesterSelect?.addEventListener('change', () => {
        syncProdiOptions();
        filterMataKuliah();
        updateSummary();
    });

    prodiSelect?.addEventListener('change', () => {
        filterMataKuliah();
        updateSummary();
    });

    document.addEventListener('DOMContentLoaded', () => {
        syncProdiOptions();
        filterMataKuliah();
        updateSummary();
    });
</script>
@endpush