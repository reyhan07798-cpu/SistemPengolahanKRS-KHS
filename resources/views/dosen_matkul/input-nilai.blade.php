@extends('layouts.dosen_mk')

@section('content')
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Input Nilai</h1>
        <p class="text-sm text-gray-500">Input nilai akhir mahasiswa untuk mata kuliah yang Anda ampu</p>
    </header>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Info Bobot Penilaian -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Bobot Penilaian:</span>
            <span class="ml-2">Tugas <strong class="text-dark">20%</strong></span>
            <span class="mx-2">•</span>
            <span>Praktikum <strong class="text-dark">15%</strong></span>
            <span class="mx-2">•</span>
            <span>UTS <strong class="text-dark">30%</strong></span>
            <span class="mx-2">•</span>
            <span>UAS <strong class="text-dark">30%</strong></span>
            <span class="mx-2">•</span>
            <span>Kehadiran <strong class="text-dark">5%</strong></span>
        </div>
    </div>

    <!-- Pilih Mata Kuliah -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-lg text-dark mb-4">Pilih Mata Kuliah</h3>
        <p class="text-sm text-gray-500 mb-4">Pilih mata kuliah untuk input nilai</p>
        
        <div class="max-w-md">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih mata kuliah</label>
            <select id="pilihMK" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary bg-white">
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach($mataKuliahList as $mk)
                <option value="{{ $mk['kode'] }}" 
                        data-nama="{{ $mk['nama'] }}"
                        data-sks="{{ $mk['sks'] }}"
                        data-semester="{{ $mk['semester'] }}"
                        data-jumlah="{{ $mk['jumlah_mahasiswa'] }}">
                    {{ $mk['kode'] }} - {{ $mk['nama'] }} ({{ $mk['jumlah_mahasiswa'] }} mahasiswa)
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Form Input Nilai (Hidden by default) -->
    <div id="formInputNilai" class="hidden">
        <!-- Info Mata Kuliah -->
        <div class="bg-primary text-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold mb-2" id="mkNama">-</h3>
                    <p class="text-sm opacity-90" id="mkInfo">-</p>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Total Mahasiswa</p>
                    <p class="text-2xl font-bold" id="mkJumlah">0</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('dosen_matkul.simpan-nilai') }}" method="POST" id="formNilai">
            @csrf
            <input type="hidden" name="kode_mk" id="inputKodeMK">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <!-- Table Header -->
                <div class="bg-gray-50 border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-4 px-6 py-3 text-xs font-semibold text-gray-700 uppercase">
                        <div class="col-span-3">Mahasiswa</div>
                        <div class="col-span-2 text-center">Tugas (20%)</div>
                        <div class="col-span-2 text-center">Praktikum (15%)</div>
                        <div class="col-span-2 text-center">UTS (30%)</div>
                        <div class="col-span-2 text-center">UAS (30%)</div>
                        <div class="col-span-1 text-center">Hadir (5%)</div>
                    </div>
                </div>

                <!-- Table Body -->
                <div id="daftarMahasiswa" class="divide-y divide-gray-200">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <button type="button" onclick="resetForm()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Reset
                </button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-secondary transition">
                    Simpan Semua Nilai
                </button>
            </div>
        </form>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Pilih Mata Kuliah</h3>
        <p class="text-gray-500">Silakan pilih mata kuliah untuk mulai input nilai mahasiswa</p>
    </div>
@endsection

@push('scripts')
<script>
let mahasiswaData = [];

// Bobot penilaian
const BOBOT = {
    tugas: 0.20,
    praktikum: 0.15,
    uts: 0.30,
    uas: 0.30,
    kehadiran: 0.05
};

document.getElementById('pilihMK').addEventListener('change', function() {
    const kodeMK = this.value;
    const selectedOption = this.options[this.selectedIndex];
    
    if (kodeMK) {
        // Update info MK
        document.getElementById('mkNama').textContent = `${selectedOption.dataset.nama} (${kodeMK})`;
        document.getElementById('mkInfo').textContent = `SKS: ${selectedOption.dataset.sks} | Semester: ${selectedOption.dataset.semester}`;
        document.getElementById('mkJumlah').textContent = selectedOption.dataset.jumlah;
        document.getElementById('inputKodeMK').value = kodeMK;
        
        // Fetch data mahasiswa
        fetchMahasiswa(kodeMK);
        
        // Show form, hide empty state
        document.getElementById('formInputNilai').classList.remove('hidden');
        document.getElementById('emptyState').classList.add('hidden');
    } else {
        document.getElementById('formInputNilai').classList.add('hidden');
        document.getElementById('emptyState').classList.remove('hidden');
    }
});

function fetchMahasiswa(kodeMK) {
    // Data dummy - di production ganti dengan fetch API ke backend
    const dummyData = {
        'IF101': [
            { nim: '3312501022', nama: 'Reyhan', kelas: 'A', nilai: { tugas: null, praktikum: null, uts: null, uas: null, kehadiran: null } },
            { nim: '3312501007', nama: 'Nabila Fatin', kelas: 'A', nilai: { tugas: null, praktikum: null, uts: null, uas: null, kehadiran: null } },
        ],
        'IF102': [
            { nim: '3312501017', nama: 'Irenessa Rosidin', kelas: 'A', nilai: { tugas: null, praktikum: null, uts: null, uas: null, kehadiran: null } },
            { nim: '3312501022', nama: 'Reyhan', kelas: 'A', nilai: { tugas: null, praktikum: null, uts: null, uas: null, kehadiran: null } },
        ],
    };
    
    mahasiswaData = dummyData[kodeMK] || [];
    renderMahasiswa();
}

function renderMahasiswa() {
    const container = document.getElementById('daftarMahasiswa');
    container.innerHTML = '';
    
    mahasiswaData.forEach((mhs, index) => {
        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-gray-50 transition';
        row.innerHTML = `
            <div class="col-span-3">
                <p class="font-semibold text-dark">${mhs.nama}</p>
                <p class="text-xs text-gray-500">${mhs.nim} - Kelas ${mhs.kelas}</p>
            </div>
            <div class="col-span-2">
                <input type="number" 
                       name="nilai[${index}][tugas]" 
                       min="0" 
                       max="100" 
                       value="${mhs.nilai.tugas ?? ''}"
                       class="input-nilai w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-primary focus:border-primary"
                       data-index="${index}"
                       data-komponen="tugas"
                       placeholder="0">
            </div>
            <div class="col-span-2">
                <input type="number" 
                       name="nilai[${index}][praktikum]" 
                       min="0" 
                       max="100" 
                       value="${mhs.nilai.praktikum ?? ''}"
                       class="input-nilai w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-primary focus:border-primary"
                       data-index="${index}"
                       data-komponen="praktikum"
                       placeholder="0">
            </div>
            <div class="col-span-2">
                <input type="number" 
                       name="nilai[${index}][uts]" 
                       min="0" 
                       max="100" 
                       value="${mhs.nilai.uts ?? ''}"
                       class="input-nilai w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-primary focus:border-primary"
                       data-index="${index}"
                       data-komponen="uts"
                       placeholder="0">
            </div>
            <div class="col-span-2">
                <input type="number" 
                       name="nilai[${index}][uas]" 
                       min="0" 
                       max="100" 
                       value="${mhs.nilai.uas ?? ''}"
                       class="input-nilai w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-primary focus:border-primary"
                       data-index="${index}"
                       data-komponen="uas"
                       placeholder="0">
            </div>
            <div class="col-span-1">
                <input type="number" 
                       name="nilai[${index}][kehadiran]" 
                       min="0" 
                       max="100" 
                       value="${mhs.nilai.kehadiran ?? ''}"
                       class="input-nilai w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-primary focus:border-primary"
                       data-index="${index}"
                       data-komponen="kehadiran"
                       placeholder="0">
            </div>
        `;
        container.appendChild(row);
    });
    
    // Add event listeners untuk auto-calculate
    document.querySelectorAll('.input-nilai').forEach(input => {
        input.addEventListener('input', calculateNilai);
    });
}

function calculateNilai(e) {
    const index = e.target.dataset.index;
    
    // Get all values for this student
    const tugas = parseFloat(document.querySelector(`input[name="nilai[${index}][tugas]"]`)?.value) || 0;
    const praktikum = parseFloat(document.querySelector(`input[name="nilai[${index}][praktikum]"]`)?.value) || 0;
    const uts = parseFloat(document.querySelector(`input[name="nilai[${index}][uts]"]`)?.value) || 0;
    const uas = parseFloat(document.querySelector(`input[name="nilai[${index}][uas]"]`)?.value) || 0;
    const kehadiran = parseFloat(document.querySelector(`input[name="nilai[${index}][kehadiran]"]`)?.value) || 0;
    
    // Calculate weighted average with NEW BOBOT
    const total = (tugas * BOBOT.tugas) + 
                  (praktikum * BOBOT.praktikum) + 
                  (uts * BOBOT.uts) + 
                  (uas * BOBOT.uas) + 
                  (kehadiran * BOBOT.kehadiran);
    
    // Determine grade
    let grade = 'E';
    let gradeColor = 'text-red-600 bg-red-100';
    
    if (total >= 85) {
        grade = 'A';
        gradeColor = 'text-green-700 bg-green-100';
    } else if (total >= 70) {
        grade = 'B';
        gradeColor = 'text-blue-700 bg-blue-100';
    } else if (total >= 55) {
        grade = 'C';
        gradeColor = 'text-yellow-700 bg-yellow-100';
    } else if (total >= 40) {
        grade = 'D';
        gradeColor = 'text-orange-700 bg-orange-100';
    }
    
    // Update display - tambahkan kolom Total & Grade di akhir baris
    const row = e.target.closest('.grid');
    let totalEl = row.querySelector('.nilai-akhir');
    let gradeEl = row.querySelector('.grade-badge');
    
    // Jika belum ada, buat elemen baru
    if (!totalEl) {
        const actionCell = document.createElement('div');
        actionCell.className = 'col-span-12 md:col-span-1 text-center flex flex-col items-center gap-1';
        actionCell.innerHTML = `
            <div class="nilai-akhir font-bold text-dark text-lg">-</div>
            <span class="grade-badge text-xs font-semibold px-2 py-0.5 rounded-full">-</span>
        `;
        row.appendChild(actionCell);
        totalEl = actionCell.querySelector('.nilai-akhir');
        gradeEl = actionCell.querySelector('.grade-badge');
    }
    
    // Update values
    if (total > 0 || tugas > 0 || praktikum > 0 || uts > 0 || uas > 0 || kehadiran > 0) {
        totalEl.textContent = total.toFixed(1);
        gradeEl.textContent = grade;
        gradeEl.className = `grade-badge text-xs font-semibold px-2 py-0.5 rounded-full ${gradeColor}`;
    } else {
        totalEl.textContent = '-';
        gradeEl.textContent = '-';
        gradeEl.className = 'grade-badge text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500';
    }
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mengosongkan semua nilai?')) {
        document.querySelectorAll('.input-nilai').forEach(input => {
            input.value = '';
        });
        document.querySelectorAll('.nilai-akhir').forEach(el => {
            el.textContent = '-';
        });
        document.querySelectorAll('.grade-badge').forEach(el => {
            el.textContent = '-';
            el.className = 'grade-badge text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500';
        });
    }
}
</script>
@endpush