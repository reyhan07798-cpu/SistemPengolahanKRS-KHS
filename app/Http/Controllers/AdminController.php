<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\TahunAjaran;
use App\Models\PaketMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD ADMIN
    // ==========================================
    public function dashboardAdmin()
    {
        $mahasiswa = collect([
            (object)['nim' => '2021001', 'nama' => 'Irenessa Rosdin', 'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'angkatan' => 2021, 'ipk' => 3.96],
            (object)['nim' => '2021045', 'nama' => 'Nabila Fatin', 'kelas' => 'B', 'prodi' => 'Sistem Informasi', 'angkatan' => 2021, 'ipk' => 3.92],
            (object)['nim' => '2022023', 'nama' => 'Ahmad Rizki', 'kelas' => 'A', 'prodi' => 'Teknik Informatika', 'angkatan' => 2022, 'ipk' => 3.88],
            (object)['nim' => '2020034', 'nama' => 'Dewi Sartika', 'kelas' => 'C', 'prodi' => 'Sistem Informasi', 'angkatan' => 2020, 'ipk' => 3.85],
            (object)['nim' => '2021012', 'nama' => 'Budi Santoso', 'kelas' => 'B', 'prodi' => 'Teknik Informatika', 'angkatan' => 2021, 'ipk' => 3.80],
        ]);

        $totalMahasiswa = $mahasiswa->count();
        $totalDosen = 5;
        $totalMataKuliah = 10;
        $avgIpk = $mahasiswa->avg('ipk');
        $prodis = $mahasiswa->pluck('prodi')->unique()->values();
        $angkatans = $mahasiswa->pluck('angkatan')->unique()->sortDesc()->values();

        return view('admin.dashboard_admin', compact(
            'mahasiswa', 'totalMahasiswa', 'totalDosen', 'totalMataKuliah', 'avgIpk', 'prodis', 'angkatans'
        ));
    }

    // ==========================================
    // 2. MAHASISWA CRUD
    // ==========================================
    public function indexMahasiswa()
    {
        $dosens = collect([]);

        if (Schema::hasTable('mahasiswas')) {
            $mahasiswa = Mahasiswa::orderBy('created_at', 'desc')->get();
            $prodis = Mahasiswa::distinct()->pluck('prodi')->sort()->values();
            $angkatans = Mahasiswa::distinct()->pluck('angkatan')->sortDesc()->values();
            
            if (Schema::hasTable('dosens')) {
                $dosens = Dosen::orderBy('nama', 'asc')->get();
            }
        } else {
            $mahasiswa = collect([
                (object)['id' => 1, 'nim' => '3312501017', 'nama' => 'Irenessa Rosdin', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'angkatan' => '2025', 'dosen_wali' => 'Dr. Budi Santoso', 'email' => 'irenessa@campus.ac.id'],
                (object)['id' => 2, 'nim' => '3312501018', 'nama' => 'Nabila Fatin', 'prodi' => 'Sistem Informasi', 'kelas' => 'B', 'angkatan' => '2025', 'dosen_wali' => 'Prof. Dewi Lestari', 'email' => 'nabila@campus.ac.id'],
                (object)['id' => 3, 'nim' => '3312501019', 'nama' => 'Ahmad Rizki', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'angkatan' => '2024', 'dosen_wali' => 'Dr. Budi Santoso', 'email' => 'ahmad.r@campus.ac.id'],
                (object)['id' => 4, 'nim' => '3312501020', 'nama' => 'Siti Nurhaliza', 'prodi' => 'Teknik Komputer', 'kelas' => 'C', 'angkatan' => '2024', 'dosen_wali' => 'Dr. Eko Prasetyo', 'email' => 'siti.n@campus.ac.id'],
                (object)['id' => 5, 'nim' => '3312501021', 'nama' => 'Fajar Nugroho', 'prodi' => 'Sistem Informasi', 'kelas' => 'B', 'angkatan' => '2023', 'dosen_wali' => 'Prof. Dewi Lestari', 'email' => 'fajar.n@campus.ac.id'],
            ]);
            $prodis = $mahasiswa->pluck('prodi')->unique()->sort()->values();
            $angkatans = $mahasiswa->pluck('angkatan')->unique()->sortDesc()->values();
            
            $dosens = collect([
                (object)['id' => 1, 'nama' => 'Dr. Budi Santoso, M.Kom'],
                (object)['id' => 2, 'nama' => 'Prof. Dewi Lestari, M.Sc'],
                (object)['id' => 3, 'nama' => 'Dr. Eko Prasetyo'],
            ]);
        }

        return view('admin.data_mahasiswa', compact('mahasiswa', 'prodis', 'angkatans', 'dosens'));
    }

    public function createMahasiswa()
    {
        return view('admin.mahasiswa_create');
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:mahasiswas,email',
            'prodi' => 'required|string|max:100',
            'angkatan' => 'required|string|max:4',
            'kelas' => 'required|string|max:10',
            'dosen_wali' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:4',
        ]);

        try {
            if (Schema::hasTable('mahasiswas')) {
                Mahasiswa::create($request->all());
            }
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function editMahasiswa($id)
    {
        if (Schema::hasTable('mahasiswas')) {
            $mahasiswa = Mahasiswa::findOrFail($id);
        } else {
            $mahasiswa = (object)['id' => $id, 'nim' => '3312501017', 'nama' => 'Irenessa Rosdin', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'angkatan' => '2025', 'dosen_wali' => 'Dr. Budi Santoso', 'email' => 'irenessa@campus.ac.id'];
        }
        return view('admin.mahasiswa_edit', compact('mahasiswa'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $id,
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:100',
            'kelas' => 'required|string|max:10',
            'angkatan' => 'required|string|max:4',
            'dosen_wali' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:mahasiswas,email,' . $id,
        ]);

        try {
            if (Schema::hasTable('mahasiswas')) {
                $mahasiswa = Mahasiswa::findOrFail($id);
                $mahasiswa->update($request->all());
            }
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroyMahasiswa($id)
    {
        try {
            if (Schema::hasTable('mahasiswas')) {
                $mahasiswa = Mahasiswa::findOrFail($id);
                $mahasiswa->delete();
            }
            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 3. DOSEN CRUD
    // ==========================================
    public function indexDosen()
    {
        $fakultasList = collect([
            'Fakultas Teknik', 'Fakultas Sains', 'Fakultas Ekonomi', 'Fakultas Hukum', 'Fakultas Kedokteran', 'Fakultas Ilmu Komputer'
        ]);

        if (Schema::hasTable('dosens')) {
            $dosen = Dosen::orderBy('created_at', 'desc')->get();
        } else {
            $dosen = collect([
                (object)['id' => 1, 'nik' => '198501012020011001', 'nama' => 'Dr. Budi Santoso, M.Kom', 'email' => 'budi.santoso@campus.ac.id', 'tipe_dosen' => 'Dosen Wali', 'fakultas' => 'Fakultas Teknik', 'alamat' => 'Jl. Pendidikan No. 1'],
                (object)['id' => 2, 'nik' => '198702152019022002', 'nama' => 'Prof. Dewi Lestari, M.Sc', 'email' => 'dewi.lestari@campus.ac.id', 'tipe_dosen' => 'Dosen Wali', 'fakultas' => 'Fakultas Sains', 'alamat' => 'Jl. Ilmu No. 5'],
                (object)['id' => 3, 'nik' => '197803102018031003', 'nama' => 'Dr. Eko Prasetyo', 'email' => 'eko.prasetyo@campus.ac.id', 'tipe_dosen' => 'Dosen Mata Kuliah', 'fakultas' => 'Fakultas Teknik', 'alamat' => 'Jl. Teknologi No. 10'],
                (object)['id' => 4, 'nik' => '198512052021012004', 'nama' => 'Dr. Ani Wijaya', 'email' => 'ani.wijaya@campus.ac.id', 'tipe_dosen' => 'Dosen Mata Kuliah', 'fakultas' => 'Fakultas Ekonomi', 'alamat' => 'Jl. Bisnis No. 2'],
                (object)['id' => 5, 'nik' => '199001202022011005', 'nama' => 'Ahmad Rizki, M.Kom', 'email' => 'ahmad.rizki@campus.ac.id', 'tipe_dosen' => 'Dosen Mata Kuliah', 'fakultas' => 'Fakultas Ilmu Komputer', 'alamat' => 'Jl. Raya No. 7'],
            ]);
        }

        return view('admin.data_dosen', compact('dosen', 'fakultasList'));
    }

    public function createDosen()
    {
        return view('admin.dosen_create');
    }

    public function storeDosen(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20|unique:dosens,nik',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:dosens,email',
            'tipe_dosen' => 'required|string',
            'fakultas' => 'required|string',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:4',
        ]);

        try {
            if (Schema::hasTable('dosens')) {
                Dosen::create($request->all());
            }
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
        }
    }

    public function editDosen($id)
    {
        if (Schema::hasTable('dosens')) {
            $dosen = Dosen::findOrFail($id);
        } else {
            $dosen = (object)['id' => $id, 'nik' => '198501012020011001', 'nama' => 'Dr. Budi Santoso, M.Kom', 'email' => 'budi.santoso@campus.ac.id', 'tipe_dosen' => 'Dosen Wali', 'fakultas' => 'Fakultas Teknik', 'alamat' => '-'];
        }
        return view('admin.dosen_edit', compact('dosen'));
    }

    public function updateDosen(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string|max:20|unique:dosens,nik,' . $id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:dosens,email,' . $id,
            'tipe_dosen' => 'required|string',
            'fakultas' => 'required|string',
        ]);

        try {
            if (Schema::hasTable('dosens')) {
                $dosen = Dosen::findOrFail($id);
                $dosen->update($request->all());
            }
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data.');
        }
    }

    public function destroyDosen($id)
    {
        try {
            if (Schema::hasTable('dosens')) {
                $dosen = Dosen::findOrFail($id);
                $dosen->delete();
            }
            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    // ==========================================
    // 4. MATA KULIAH CRUD
    // ==========================================
    public function indexMatakuliah()
    {
        $dosens = collect([]);
        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);
        $days = collect(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);

        if (Schema::hasTable('dosens')) {
            $dosens = Dosen::orderBy('nama', 'asc')->get();
        } else {
            $dosens = collect([
                (object)['id' => 1, 'nama' => 'Dr. Budi Santoso, M.Kom'],
                (object)['id' => 2, 'nama' => 'Prof. Dewi Lestari, M.Sc'],
                (object)['id' => 3, 'nama' => 'Dr. Eko Prasetyo'],
            ]);
        }

        if (Schema::hasTable('mata_kuliahs')) {
            $matakuliah = MataKuliah::orderBy('created_at', 'desc')->get();
        } else {
            $matakuliah = collect([
                (object)['id' => 1, 'kode' => 'IF101', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => '3', 'dosen_pengampu' => 'Dr. Budi Santoso', 'jadwal' => 'Senin, 08:00 - 10:00, Lab Komputer 1'],
                (object)['id' => 2, 'kode' => 'IF102', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => '3', 'dosen_pengampu' => 'Prof. Dewi Lestari', 'jadwal' => 'Selasa, 13:00 - 15:00, Lab Komputer 2'],
            ]);
        }

        return view('admin.data_matakuliah', compact('matakuliah', 'dosens', 'semesters', 'days'));
    }

    public function createMatakuliah()
    {
        return view('admin.matakuliah_create');
    }

    public function storeMatakuliah(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliahs,kode',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer',
            'semester' => 'required|string',
            'kapasitas' => 'nullable|integer',
            'dosen_pengampu' => 'required|string|max:255',
            'hari' => 'required|string|max:20',
            'jam' => 'required|string|max:20',
            'ruang' => 'required|string|max:50',
        ]);

        try {
            $jadwalString = $request->hari . ', ' . $request->jam . ', ' . $request->ruang;
            
            $data = $request->all();
            $data['jadwal'] = $jadwalString;

            if (Schema::hasTable('mata_kuliahs')) {
                MataKuliah::create($data);
            }
            return redirect()->route('admin.matakuliah.index')->with('success', 'Data mata kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
        }
    }

    public function editMatakuliah($id)
    {
        if (Schema::hasTable('mata_kuliahs')) {
            $matakuliah = MataKuliah::findOrFail($id);
        } else {
            $matakuliah = (object)['id' => $id, 'kode' => 'IF101', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => '3', 'dosen_pengampu' => 'Dr. Budi Santoso', 'jadwal' => 'Senin, 08:00 - 10:00, Lab Komputer 1'];
        }
        return view('admin.matakuliah_edit', compact('matakuliah'));
    }

    public function updateMatakuliah(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliahs,kode,' . $id,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer',
            'semester' => 'required|string',
            'dosen_pengampu' => 'required|string|max:255',
            'jadwal' => 'required|string|max:255',
        ]);

        try {
            if (Schema::hasTable('mata_kuliahs')) {
                $mk = MataKuliah::findOrFail($id);
                $mk->update($request->all());
            }
            return redirect()->route('admin.matakuliah.index')->with('success', 'Data mata kuliah berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data.');
        }
    }

    public function destroyMatakuliah($id)
    {
        try {
            if (Schema::hasTable('mata_kuliahs')) {
                $mk = MataKuliah::findOrFail($id);
                $mk->delete();
            }
            return redirect()->route('admin.matakuliah.index')->with('success', 'Data mata kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    // ==========================================
    // 5. TAHUN AJARAN CRUD
    // ==========================================
    public function indexTahunAjaran()
    {
        $currentYear = date('Y');
        $tahunOptions = collect();
        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
            $nextYear = $i + 1;
            $tahunOptions->push("$i/$nextYear");
        }

        if (Schema::hasTable('tahun_ajarans')) {
            $tahunAjaran = TahunAjaran::orderBy('created_at', 'desc')->get();
        } else {
            $tahunAjaran = collect([
                (object)['id' => 1, 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025', 'status' => 'Nonaktif'],
                (object)['id' => 2, 'semester' => 'Genap', 'tahun_ajaran' => '2024/2025', 'status' => 'Nonaktif'],
                (object)['id' => 3, 'semester' => 'Ganjil', 'tahun_ajaran' => '2023/2024', 'status' => 'Aktif'],
                (object)['id' => 4, 'semester' => 'Genap', 'tahun_ajaran' => '2023/2024', 'status' => 'Nonaktif'],
            ]);
        }

        return view('admin.data_tahunajaran', compact('tahunAjaran', 'tahunOptions'));
    }

    public function createTahunAjaran()
    {
        return view('admin.tahunajaran_create');
    }

    public function storeTahunAjaran(Request $request)
    {
        $request->validate([
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        try {
            $status = $request->has('status') ? 'Aktif' : 'Nonaktif';

            $data = $request->all();
            $data['status'] = $status;

            if (Schema::hasTable('tahun_ajarans')) {
                TahunAjaran::create($data);
            }
            return redirect()->route('admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
        }
    }

    public function editTahunAjaran($id)
    {
        if (Schema::hasTable('tahun_ajarans')) {
            $tahunAjaran = TahunAjaran::findOrFail($id);
        } else {
            $tahunAjaran = (object)['id' => $id, 'semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025', 'status' => 'Nonaktif'];
        }
        return view('admin.tahunajaran_edit', compact('tahunAjaran'));
    }

    public function updateTahunAjaran(Request $request, $id)
    {
        $request->validate([
            'semester' => 'required|string|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        try {
            if (Schema::hasTable('tahun_ajarans')) {
                $ta = TahunAjaran::findOrFail($id);
                $ta->update($request->all());
            }
            return redirect()->route('admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data.');
        }
    }

    public function destroyTahunAjaran($id)
    {
        try {
            if (Schema::hasTable('tahun_ajarans')) {
                $ta = TahunAjaran::findOrFail($id);
                $ta->delete();
            }
            return redirect()->route('admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    // ==========================================
    // 6. PAKET MATA KULIAH CRUD
    // ==========================================
    public function indexPaketMK()
    {
        $allMataKuliah = collect([
            (object)['id' => 1, 'kode' => 'DB101', 'nama' => 'Basis Data', 'sks' => 4],
            (object)['id' => 2, 'kode' => 'ENG101', 'nama' => 'Bahasa Inggris', 'sks' => 2],
            (object)['id' => 3, 'kode' => 'JK101', 'nama' => 'Jaringan Komputer', 'sks' => 3],
            (object)['id' => 4, 'kode' => 'PWD101', 'nama' => 'Pemrograman Web Dasar', 'sks' => 3],
            (object)['id' => 5, 'kode' => 'RPL101', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3],
            (object)['id' => 6, 'kode' => 'PBO101', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3],
            (object)['id' => 7, 'kode' => 'PRP101', 'nama' => 'Proyek Pembuatan Prototipe', 'sks' => 3],
        ]);

        $prodis = collect(['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer', 'Semua Prodi']);
        $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

        if (Schema::hasTable('paket_mata_kuliahs')) {
            $paketMK = PaketMataKuliah::orderBy('created_at', 'desc')->get();
        } else {
            $paketMK = collect([
                (object)[
                    'id' => 1,
                    'nama_paket' => 'Paket Normal Semester 3',
                    'semester' => '3',
                    'prodi' => 'Teknik Informatika',
                    'total_sks' => 12,
                    'jumlah_mk' => 4,
                    'deskripsi' => 'Paket semester 3 untuk Teknik Informatika',
                    'mata_kuliah_ids' => [1, 3, 4, 5]
                ],
                (object)[
                    'id' => 2,
                    'nama_paket' => 'Paket Normal Semester 1',
                    'semester' => '1',
                    'prodi' => 'Sistem Informasi',
                    'total_sks' => 9,
                    'jumlah_mk' => 3,
                    'deskripsi' => 'Paket semester 1 untuk Sistem Informasi',
                    'mata_kuliah_ids' => [2, 4, 6]
                ],
            ]);
        }

        return view('admin.data_paketmk', compact('paketMK', 'allMataKuliah', 'prodis', 'semesters'));
    }

    public function createPaketMK()
    {
        return view('admin.paketmk_create');
    }

    public function storePaketMK(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|string|max:10',
            'prodi' => 'required|string|max:100',
            'mata_kuliah' => 'required|array',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $totalSks = 0;
            foreach ($request->mata_kuliah as $mkId) {
                $mk = MataKuliah::find($mkId);
                if ($mk) $totalSks += $mk->sks;
            }

            $data = $request->all();
            $data['total_sks'] = $totalSks;
            $data['jumlah_mk'] = count($request->mata_kuliah);

            if (Schema::hasTable('paket_mata_kuliahs')) {
                PaketMataKuliah::create($data);
            }
            return redirect()->route('admin.paketmk.index')->with('success', 'Data paket mata kuliah berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
        }
    }

    public function editPaketMK($id)
    {
        if (Schema::hasTable('paket_mata_kuliahs')) {
            $paketMK = PaketMataKuliah::findOrFail($id);
        } else {
            $paketMK = (object)[
                'id' => $id,
                'nama_paket' => 'Paket Normal Semester 3',
                'semester' => '3',
                'prodi' => 'Teknik Informatika',
                'total_sks' => 12,
                'jumlah_mk' => 4,
                'deskripsi' => 'Paket semester 3 untuk Teknik Informatika',
                'mata_kuliah_ids' => [1, 3, 4, 5]
            ];
        }
        return view('admin.paketmk_edit', compact('paketMK'));
    }

    public function updatePaketMK(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|string|max:10',
            'prodi' => 'required|string|max:100',
            'mata_kuliah' => 'required|array',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $totalSks = 0;
            foreach ($request->mata_kuliah as $mkId) {
                $mk = MataKuliah::find($mkId);
                if ($mk) $totalSks += $mk->sks;
            }

            $data = $request->all();
            $data['total_sks'] = $totalSks;
            $data['jumlah_mk'] = count($request->mata_kuliah);

            if (Schema::hasTable('paket_mata_kuliahs')) {
                $paket = PaketMataKuliah::findOrFail($id);
                $paket->update($data);
            }
            return redirect()->route('admin.paketmk.index')->with('success', 'Data paket mata kuliah berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data.');
        }
    }

    public function destroyPaketMK($id)
    {
        try {
            if (Schema::hasTable('paket_mata_kuliahs')) {
                $paket = PaketMataKuliah::findOrFail($id);
                $paket->delete();
            }
            return redirect()->route('admin.paketmk.index')->with('success', 'Data paket mata kuliah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
