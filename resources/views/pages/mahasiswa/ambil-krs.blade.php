@extends('layouts.mahasiswa')

@section('content')
    <header class="mb-8">
        <h1 class="text-2xl font-bold text-dark">Ambil KRS</h1>
        <p class="text-sm text-gray-500">Pilih mata kuliah untuk semester ini. Maksimal 24 SKS.</p>
    </header>

    @if(session('success'))
    <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Info Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Semester Aktif</p>
                    <h3 class="text-3xl font-bold text-dark mt-2" id="displaySemesterAktif">2</h3>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sisa SKS</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2" id="sisaSks">24</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status KRS</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-2">Belum Diajukan</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 21c4.97 0 9-4.03 9-9S16.97 3 12 3 3 7.03 3 12s4.03 9 9 9z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-dark mb-4">Filter Paket Semester</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                <select id="filterTahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="2025/2026" selected>2025/2026</option>
                    <option value="2024/2025">2024/2025</option>
                    <option value="2023/2024">2023/2024</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <!-- ID ini akan diisi otomatis oleh JavaScript -->
                <select id="filterSemester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <!-- Opsi akan digenerate otomatis sesuai semester aktif -->
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadPaketSemester()" class="w-full px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-secondary transition">
                    Tampilkan Paket
                </button>
            </div>
        </div>
    </div>

    <!-- Warning SKS -->
    <div id="warningSks" class="hidden mb-4 p-4 bg-orange-100 border border-orange-400 text-orange-700 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <span id="warningText">Anda telah mencapai batas maksimal 24 SKS per semester.</span>
    </div>

    <!-- Form KRS -->
    <form method="POST" action="{{ route('pages.mahasiswa.store-krs') }}" id="formKrs">
        @csrf
        <input type="hidden" name="semester" id="inputSemester" value="2">
        <input type="hidden" name="tahun_ajaran" id="inputTahun" value="2025/2026">

        <!-- Paket Wajib -->
        <div id="containerWajib" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-dark">📦 Paket Semester <span id="labelSemesterWajib">2</span> (Wajib)</h3>
                <p class="text-sm text-gray-500">Mata kuliah wajib untuk semester ini</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-center">Pilih</th>
                            <th class="px-6 py-3 text-left">Kode</th>
                            <th class="px-6 py-3 text-left">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left">Dosen</th>
                            <th class="px-6 py-3 text-center">SKS</th>
                            <th class="px-6 py-3 text-center">Prasyarat</th>
                        </tr>
                    </thead>
                    <tbody id="tabelWajib" class="bg-white divide-y divide-gray-200 text-sm">
                        <!-- Diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paket Mengulang -->
        <div id="containerMengulang" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-dark">🔄 Mata Kuliah Mengulang</h3>
                <p class="text-sm text-gray-500">MK yang belum lulus (nilai D/E) dan bisa diambil ulang</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-center">Pilih</th>
                            <th class="px-6 py-3 text-left">Kode</th>
                            <th class="px-6 py-3 text-left">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left">Dosen</th>
                            <th class="px-6 py-3 text-center">SKS</th>
                            <th class="px-6 py-3 text-center">Nilai Lama</th>
                        </tr>
                    </thead>
                    <tbody id="tabelMengulang" class="bg-white divide-y divide-gray-200 text-sm">
                        <!-- Diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary SKS -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <p class="text-sm text-gray-600">Total SKS Terpilih:</p>
                    <p class="text-3xl font-bold text-dark" id="totalSks">0</p>
                    <p class="text-xs text-gray-500">Maksimal: 24 SKS</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="resetForm()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Reset
                    </button>
                    <button type="submit" id="btnSubmit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-secondary transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Ajukan KRS
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Empty State -->
    <div id="emptyState" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Klik "Tampilkan Paket"</h3>
        <p class="text-gray-500">Pilih semester dan tahun ajaran, lalu klik tombol untuk melihat mata kuliah.</p>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // ==========================================
        // 1. SIMULASI DATA MAHASISWA (Dari Database)
        // ==========================================
        // Ganti angka ini nanti dengan data dinamis dari controller
        const semesterAktifMahasiswa = 2; 
        const MAX_SKS = 24;
        let selectedSks = 0;
        let paketData = {};

        // ==========================================
        // 2. DUMMY DATA PAKET SEMESTER
        // ==========================================
        const dummyData = {
            1: {
                wajib: [
                    { id: 1, kode: 'IF101', matkul: 'Algoritma & Pemrograman', dosen: 'Dr. Ahmad Fauzi, M.Kom', sks: 4, prasyarat: '-' },
                    { id: 2, kode: 'IF102', matkul: 'Matematika Diskrit', dosen: 'Dr. Siti Nurhaliza, M.Si', sks: 3, prasyarat: '-' },
                    { id: 3, kode: 'IF103', matkul: 'Bahasa Indonesia', dosen: 'Dra. Rina Susanti', sks: 2, prasyarat: '-' },
                    { id: 4, kode: 'IF104', matkul: 'Pendidikan Pancasila', dosen: 'Prof. Dr. Budi Santoso', sks: 2, prasyarat: '-' },
                ],
                mengulang: []
            },
            2: {
                wajib: [
                    { id: 5, kode: 'IF201', matkul: 'Basis Data', dosen: 'Dr. Budi Santoso, M.T', sks: 4, prasyarat: 'IF101' },
                    { id: 6, kode: 'IF202', matkul: 'Pemrograman Web', dosen: 'Dr. Andi Wijaya, M.Kom', sks: 4, prasyarat: 'IF101' },
                    { id: 7, kode: 'IF203', matkul: 'Jaringan Komputer', dosen: 'Dr. Citra Lestari, M.T', sks: 4, prasyarat: 'IF102' },
                    { id: 8, kode: 'IF204', matkul: 'Proyek Pembuatan Prototipe', dosen: 'Dr. Dewi Kusuma, M.Kom', sks: 4, prasyarat: '-' },
                    { id: 9, kode: 'IF205', matkul: 'Sistem Operasi', dosen: 'Dr. Eko Prasetyo, M.Kom', sks: 3, prasyarat: 'IF101' },
                    { id: 10, kode: 'IF206', matkul: 'Struktur Data', dosen: 'Dr. Fitri Handayani, M.T', sks: 3, prasyarat: 'IF101' },
                ],
                mengulang: [
                    { id: 101, kode: 'IF103', matkul: 'Bahasa Indonesia', dosen: 'Dra. Rina Susanti', sks: 2, nilaiLama: 'D' },
                    { id: 102, kode: 'IF104', matkul: 'Pendidikan Pancasila', dosen: 'Prof. Dr. Budi Santoso', sks: 2, nilaiLama: 'E' },
                ]
            },
            3: {
                wajib: [
                    { id: 11, kode: 'IF301', matkul: 'Pemrograman Berorientasi Objek', dosen: 'Dr. Eka Prasetyo, M.Kom', sks: 4, prasyarat: 'IF202' },
                    { id: 12, kode: 'IF302', matkul: 'Rekayasa Perangkat Lunak', dosen: 'Dr. Fitri Handayani, M.T', sks: 4, prasyarat: 'IF201' },
                ],
                mengulang: []
            },
            4: {
                wajib: [
                    { id: 13, kode: 'IF401', matkul: 'Kecerdasan Buatan', dosen: 'Dr. Hadi Purnomo, M.Kom', sks: 4, prasyarat: 'IF301' },
                ],
                mengulang: []
            }
        };

        // ==========================================
        // 3. INIT SAAT HALAMAN LOAD
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan semester aktif di card atas
            document.getElementById('displaySemesterAktif').textContent = semesterAktifMahasiswa;
            
            // Isi dropdown semester secara dinamis (Hanya 1 sampai Semester Aktif)
            const selectSemester = document.getElementById('filterSemester');
            selectSemester.innerHTML = ''; // Reset options
            
            for (let i = 1; i <= semesterAktifMahasiswa; i++) {
                const option = document.createElement('option');
                option.value = i;
                
                // Tambahkan label (Aktif) untuk semester saat ini
                if (i === semesterAktifMahasiswa) {
                    option.textContent = `Semester ${i} (Aktif)`;
                    option.selected = true; // Default terpilih
                } else {
                    option.textContent = `Semester ${i}`;
                }
                
                selectSemester.appendChild(option);
            }

            console.log(`Halaman Siap. Mahasiswa berada di Semester ${semesterAktifMahasiswa}`);
        });

        // ==========================================
        // 4. FUNGSI LOAD PAKET
        // ==========================================
        function loadPaketSemester() {
            const semester = document.getElementById('filterSemester').value;
            const tahun = document.getElementById('filterTahun').value;
            
            // Update hidden inputs
            document.getElementById('inputSemester').value = semester;
            document.getElementById('inputTahun').value = tahun;
            document.getElementById('labelSemesterWajib').textContent = semester;
            
            // Load data dari dummy
            paketData = dummyData[semester] || { wajib: [], mengulang: [] };
            
            // Render tabel
            renderTabel();
            
            // Reset form
            resetForm();
            
            // Hide empty state
            document.getElementById('emptyState').classList.add('hidden');
        }

        // ==========================================
        // 5. RENDER TABEL
        // ==========================================
        function renderTabel() {
            renderTabelSection('tabelWajib', paketData.wajib, false);
            renderTabelSection('tabelMengulang', paketData.mengulang, true);
            
            // Show/hide containers
            document.getElementById('containerWajib').classList.toggle('hidden', paketData.wajib.length === 0);
            document.getElementById('containerMengulang').classList.toggle('hidden', paketData.mengulang.length === 0);
        }

        function renderTabelSection(tbodyId, data, isMengulang) {
            const tbody = document.getElementById(tbodyId);
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada data</td></tr>`;
                return;
            }
            
            data.forEach(mk => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition';
                row.innerHTML = `
                    <td class="px-6 py-4 text-center">
                        <input type="checkbox" 
                               name="mata_kuliah_ids[]" 
                               value="${mk.id}" 
                               data-sks="${mk.sks}"
                               class="chk-mk h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                               ${isMengulang ? 'data-mengulang="true"' : ''}>
                    </td>
                    <td class="px-6 py-4 font-medium text-dark font-mono">${mk.kode}</td>
                    <td class="px-6 py-4">${mk.matkul}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${mk.dosen}</td>
                    <td class="px-6 py-4 text-center font-semibold">${mk.sks}</td>
                    <td class="px-6 py-4 text-center">
                        ${isMengulang 
                            ? `<span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-700">${mk.nilaiLama}</span>`
                            : `<span class="text-sm text-gray-500">${mk.prasyarat || '-'}</span>`
                        }
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // Event listener checkbox
            tbody.querySelectorAll('.chk-mk').forEach(chk => {
                chk.addEventListener('change', hitungSks);
            });
        }

        // ==========================================
        // 6. VALIDASI SKS REAL-TIME
        // ==========================================
        function hitungSks() {
            selectedSks = 0;
            document.querySelectorAll('.chk-mk:checked').forEach(chk => {
                selectedSks += parseInt(chk.dataset.sks) || 0;
            });
            
            // Update display
            document.getElementById('totalSks').textContent = selectedSks;
            document.getElementById('sisaSks').textContent = MAX_SKS - selectedSks;
            
            const warning = document.getElementById('warningSks');
            const warningText = document.getElementById('warningText');
            const btnSubmit = document.getElementById('btnSubmit');
            
            if (selectedSks > MAX_SKS) {
                warning.classList.remove('hidden');
                warningText.textContent = `Anda telah melebihi batas maksimal ${MAX_SKS} SKS. Total saat ini: ${selectedSks} SKS.`;
                warning.className = 'mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2';
                btnSubmit.disabled = true;
            } else if (selectedSks === MAX_SKS) {
                warning.classList.remove('hidden');
                warningText.textContent = `Anda telah mencapai batas maksimal ${MAX_SKS} SKS.`;
                warning.className = 'mb-4 p-4 bg-orange-100 border border-orange-400 text-orange-700 rounded-lg flex items-center gap-2';
                btnSubmit.disabled = false;
            } else {
                warning.classList.add('hidden');
                btnSubmit.disabled = selectedSks === 0;
            }
            
            // Disable checkbox jika sudah mencapai batas
            document.querySelectorAll('.chk-mk:not(:checked)').forEach(chk => {
                const mkSks = parseInt(chk.dataset.sks) || 0;
                chk.disabled = (selectedSks + mkSks) > MAX_SKS;
            });
        }

        // ==========================================
        // 7. RESET FORM
        // ==========================================
        function resetForm() {
            document.querySelectorAll('.chk-mk').forEach(chk => {
                chk.checked = false;
                chk.disabled = false;
            });
            selectedSks = 0;
            document.getElementById('totalSks').textContent = '0';
            document.getElementById('sisaSks').textContent = MAX_SKS;
            document.getElementById('warningSks').classList.add('hidden');
            document.getElementById('btnSubmit').disabled = true;
        }

        // Validasi submit
        document.getElementById('formKrs').addEventListener('submit', function(e) {
            if (selectedSks === 0) {
                e.preventDefault();
                alert('Harap pilih minimal satu mata kuliah!');
                return;
            }
            if (selectedSks > MAX_SKS) {
                e.preventDefault();
                alert(`Total SKS (${selectedSks}) melebihi batas maksimal ${MAX_SKS} SKS!`);
                return;
            }
        });
    </script>
@endsection