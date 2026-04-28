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
        <div class="flex items-center gap-2 text-sm text-gray-600 flex-wrap">
            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Bobot Penilaian:</span>
            <span class="ml-1">Tugas <strong class="text-dark">20%</strong></span>
            <span class="mx-1">•</span>
            <span>Praktikum <strong class="text-dark">15%</strong></span>
            <span class="mx-1">•</span>
            <span>UTS <strong class="text-dark">30%</strong></span>
            <span class="mx-1">•</span>
            <span>UAS <strong class="text-dark">30%</strong></span>
            <span class="mx-1">•</span>
            <span>Kehadiran <strong class="text-dark">5%</strong></span>
        </div>
    </div>

    <!-- Pilih Mata Kuliah -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-lg text-dark mb-4">Pilih Mata Kuliah</h3>
        <div class="max-w-md">
            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah yang Diampu</label>
            <select id="pilihMK" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary bg-white">
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach($mataKuliahList as $mk)
                <option value="{{ $mk['kode'] }}" 
                        data-nama="{{ $mk['nama'] }}"
                        data-sks="{{ $mk['sks'] }}"
                        data-semester="{{ $mk['semester'] }}">
                    {{ $mk['kode'] }} - {{ $mk['nama'] }} ({{ $mk['jumlah_mahasiswa'] }} mahasiswa)
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tabel Mahasiswa (Hidden by default) -->
    <div id="tabelContainer" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-dark text-lg" id="tabelJudul">Daftar Mahasiswa</h3>
                <p class="text-sm text-gray-500" id="tabelInfo">-</p>
            </div>
            <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full" id="tabelCount">0 Mahasiswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">NIM</th>
                        <th class="px-6 py-3">Nama Mahasiswa</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3 text-center">Status Nilai</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="daftarMahasiswa" class="divide-y divide-gray-200">
                    <!-- Diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Pilih Mata Kuliah</h3>
        <p class="text-gray-500">Silakan pilih mata kuliah untuk mulai menginput nilai</p>
    </div>

    <!-- MODAL INPUT NILAI -->
    <div id="nilaiModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg transform transition-all scale-100">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-dark">Input Nilai Mahasiswa</h3>
                    <p class="text-xs text-gray-500 mt-1" id="modalMKInfo">-</p>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-4">
                <!-- Info Mahasiswa -->
                <div class="bg-gray-50 p-4 rounded-lg flex items-center gap-4">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-full flex items-center justify-center font-bold" id="modalAvatar">A</div>
                    <div>
                        <p class="font-semibold text-dark" id="modalNama">Nama Mahasiswa</p>
                        <p class="text-xs text-gray-500" id="modalNIM">NIM - Kelas</p>
                    </div>
                </div>

                <!-- Form Input Komponen -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tugas (20%)</label>
                        <input type="number" id="inTugas" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Praktikum (15%)</label>
                        <input type="number" id="inPraktikum" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">UTS (30%)</label>
                        <input type="number" id="inUTS" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">UAS (30%)</label>
                        <input type="number" id="inUAS" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="0">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Kehadiran (5%)</label>
                        <input type="number" id="inHadir" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="0">
                    </div>
                </div>

                <!-- Hasil Perhitungan -->
                <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Nilai Akhir & Grade:</span>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-dark" id="modalTotal">-</span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600" id="modalGrade">-</span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 rounded-b-xl">
                <button onclick="closeModal()" class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-white transition">Batal</button>
                <button onclick="simpanNilai()" class="px-5 py-2 bg-primary text-white font-medium rounded-lg hover:bg-secondary shadow-sm transition">Simpan Nilai</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Data Dummy Lengkap
    let dataNilai = {
        'IF101': [
            { nim: '3312501022', nama: 'Reyhan', kelas: 'A', nilai: null, grade: null },
            { nim: '3312501007', nama: 'Nabila Fatin', kelas: 'A', nilai: null, grade: null },
            { nim: '3312501017', nama: 'Irenessa Rosidin', kelas: 'A', nilai: null, grade: null }
        ],
        'IF102': [
            { nim: '3312501010', nama: 'Della Reska', kelas: 'B', nilai: null, grade: null },
            { nim: '3312501023', nama: 'Samuel Deidra', kelas: 'B', nilai: 78.5, grade: 'B' } // Contoh sudah ada nilai
        ]
    };

    const BOBOT = { tugas: 0.20, praktikum: 0.15, uts: 0.30, uas: 0.30, hadir: 0.05 };
    let currentMK = null;
    let currentIdx = null;

    // 1. Event Pilih Mata Kuliah
    document.getElementById('pilihMK').addEventListener('change', function() {
        currentMK = this.value;
        if (currentMK) {
            const opt = this.options[this.selectedIndex];
            document.getElementById('tabelJudul').textContent = `Daftar Mahasiswa - ${opt.dataset.nama}`;
            document.getElementById('tabelInfo').textContent = `SKS: ${opt.dataset.sks} | Semester: ${opt.dataset.semester}`;
            document.getElementById('tabelCount').textContent = `${dataNilai[currentMK].length} Mahasiswa`;
            
            document.getElementById('tabelContainer').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            renderTable();
        } else {
            document.getElementById('tabelContainer').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
        }
    });

    // 2. Render Tabel
    function renderTable() {
        const tbody = document.getElementById('daftarMahasiswa');
        tbody.innerHTML = '';
        
        dataNilai[currentMK].forEach((mhs, idx) => {
            const statusBadge = mhs.nilai !== null 
                ? `<span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">${mhs.nilai} (${mhs.grade})</span>`
                : `<span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-500">Belum Diinput</span>`;

            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition';
            row.innerHTML = `
                <td class="px-6 py-4 font-medium text-gray-500">${idx + 1}</td>
                <td class="px-6 py-4 font-mono text-gray-600">${mhs.nim}</td>
                <td class="px-6 py-4 font-semibold text-dark">${mhs.nama}</td>
                <td class="px-6 py-4">${mhs.kelas}</td>
                <td class="px-6 py-4 text-center">${statusBadge}</td>
                <td class="px-6 py-4 text-center">
                    <button onclick="openModal(${idx})" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-primary text-white hover:bg-secondary transition">
                        ${mhs.nilai !== null ? 'Edit Nilai' : 'Input Nilai'}
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // 3. Buka Modal
    window.openModal = function(idx) {
        currentIdx = idx;
        const mhs = dataNilai[currentMK][idx];
        const mkNama = document.getElementById('pilihMK').options[document.getElementById('pilihMK').selectedIndex].dataset.nama;
        
        document.getElementById('modalMKInfo').textContent = `${mkNama} (${currentMK})`;
        document.getElementById('modalNama').textContent = mhs.nama;
        document.getElementById('modalNIM').textContent = `${mhs.nim} - Kelas ${mhs.kelas}`;
        document.getElementById('modalAvatar').textContent = mhs.nama.charAt(0);
        
        // Isi input jika sudah ada nilai sebelumnya
        if (mhs.nilai !== null) {
            // Karena dummy data sederhana, kita reset input saat edit untuk demo
            // Di production, simpan komponen nilai di object data
            document.getElementById('inTugas').value = '';
            document.getElementById('inPraktikum').value = '';
            document.getElementById('inUTS').value = '';
            document.getElementById('inUAS').value = '';
            document.getElementById('inHadir').value = '';
            document.getElementById('modalTotal').textContent = '-';
            document.getElementById('modalGrade').textContent = '-';
        } else {
            document.getElementById('inTugas').value = '';
            document.getElementById('inPraktikum').value = '';
            document.getElementById('inUTS').value = '';
            document.getElementById('inUAS').value = '';
            document.getElementById('inHadir').value = '';
            document.getElementById('modalTotal').textContent = '-';
            document.getElementById('modalGrade').textContent = '-';
        }

        document.getElementById('nilaiModal').classList.remove('hidden');
        setupModalListeners();
    };

    window.closeModal = function() {
        document.getElementById('nilaiModal').classList.add('hidden');
        removeModalListeners();
    };

    // 4. Hitung Real-time di Modal
    function calculateModal() {
        const t = parseFloat(document.getElementById('inTugas').value) || 0;
        const p = parseFloat(document.getElementById('inPraktikum').value) || 0;
        const u = parseFloat(document.getElementById('inUTS').value) || 0;
        const a = parseFloat(document.getElementById('inUAS').value) || 0;
        const h = parseFloat(document.getElementById('inHadir').value) || 0;

        const total = (t * BOBOT.tugas) + (p * BOBOT.praktikum) + (u * BOBOT.uts) + (a * BOBOT.uas) + (h * BOBOT.hadir);
        
        let grade = 'E', color = 'bg-red-100 text-red-700';
        if (total >= 85) { grade = 'A'; color = 'bg-green-100 text-green-700'; }
        else if (total >= 70) { grade = 'B'; color = 'bg-blue-100 text-blue-700'; }
        else if (total >= 55) { grade = 'C'; color = 'bg-yellow-100 text-yellow-700'; }
        else if (total >= 40) { grade = 'D'; color = 'bg-orange-100 text-orange-700'; }

        document.getElementById('modalTotal').textContent = total > 0 ? total.toFixed(1) : '-';
        const gradeEl = document.getElementById('modalGrade');
        gradeEl.textContent = total > 0 ? grade : '-';
        gradeEl.className = `ml-2 px-2 py-1 text-xs font-semibold rounded-full ${color}`;
    }

    function setupModalListeners() {
        ['inTugas', 'inPraktikum', 'inUTS', 'inUAS', 'inHadir'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateModal);
        });
    }

    function removeModalListeners() {
        ['inTugas', 'inPraktikum', 'inUTS', 'inUAS', 'inHadir'].forEach(id => {
            document.getElementById(id).removeEventListener('input', calculateModal);
        });
    }

    // 5. Simpan Nilai (Simulasi Frontend)
    window.simpanNilai = function() {
        const t = parseFloat(document.getElementById('inTugas').value) || 0;
        const p = parseFloat(document.getElementById('inPraktikum').value) || 0;
        const u = parseFloat(document.getElementById('inUTS').value) || 0;
        const a = parseFloat(document.getElementById('inUAS').value) || 0;
        const h = parseFloat(document.getElementById('inHadir').value) || 0;

        if (t + p + u + a + h === 0) {
            alert('Harap isi minimal satu komponen nilai!');
            return;
        }

        const total = (t * BOBOT.tugas) + (p * BOBOT.praktikum) + (u * BOBOT.uts) + (a * BOBOT.uas) + (h * BOBOT.hadir);
        let grade = 'E';
        if (total >= 85) grade = 'A';
        else if (total >= 70) grade = 'B';
        else if (total >= 55) grade = 'C';
        else if (total >= 40) grade = 'D';

        // Update dummy data
        dataNilai[currentMK][currentIdx].nilai = total.toFixed(1);
        dataNilai[currentMK][currentIdx].grade = grade;

        closeModal();
        renderTable();
        
        // Simulasi notifikasi sukses
        setTimeout(() => {
            alert(`✅ Nilai untuk ${dataNilai[currentMK][currentIdx].nama} berhasil disimpan!\nNilai Akhir: ${total.toFixed(1)} | Grade: ${grade}`);
        }, 300);
    };

    // Tutup modal jika klik di luar area
    document.getElementById('nilaiModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush