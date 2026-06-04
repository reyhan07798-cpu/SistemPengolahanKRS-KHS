<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\TahunAjaran;
use App\Models\PaketMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

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
$prodis = collect([
    'Teknik Informatika',
    'Sistem Informasi', 
    'Teknologi Rekayasa Multimedia',
    'Rekayasa Keamanan Siber',
    'Rekayasa Perangkat Lunak',
    'Teknologi Permainan',
    'Animasi'
]);
        $angkatans = $mahasiswa->pluck('angkatan')->unique()->sortDesc()->values();

        return view('pages.admin.dashboard_admin', compact(
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
$prodis = collect([
    'Teknik Informatika',
    'Sistem Informasi', 
    'Teknologi Rekayasa Multimedia',
    'Rekayasa Keamanan Siber',
    'Rekayasa Perangkat Lunak',
    'Teknologi Permainan',
    'Animasi'
]);
            $angkatans = Mahasiswa::distinct()->pluck('angkatan')->sortDesc()->values();
            
            if (Schema::hasTable('dosens')) {
                $dosens = Dosen::orderBy('nama', 'asc')->get();
            }
        } else {
            $mahasiswa = collect([
                (object)['id' => 1, 'nim' => '3312501017', 'nama' => 'Irenessa Rosdin', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'angkatan' => '2025', 'dosen_wali' => 'Dr. Budi Santoso', 'email' => 'irenessa@campus.ac.id'],
                (object)['id' => 2, 'nim' => '3312501018', 'nama' => 'Nabila Fatin', 'prodi' => 'Teknik Informatika', 'kelas' => 'B', 'angkatan' => '2025', 'dosen_wali' => 'Prof. Dewi Lestari', 'email' => 'nabila@campus.ac.id'],
                (object)['id' => 3, 'nim' => '3312501022', 'nama' => 'Reyhan', 'prodi' => 'Teknik Informatika', 'kelas' => 'A', 'angkatan' => '2025', 'dosen_wali' => 'Dr. Budi Santoso', 'email' => 'Reyhan@campus.ac.id'],
                (object)['id' => 4, 'nim' => '3312501020', 'nama' => 'Siti Nurhaliza', 'prodi' => 'Teknik Informatika', 'kelas' => 'C', 'angkatan' => '2024', 'dosen_wali' => 'Dr. Eko Prasetyo', 'email' => 'siti.n@campus.ac.id'],
                (object)['id' => 5, 'nim' => '3312501021', 'nama' => 'Fajar Nugroho', 'prodi' => 'Teknik Informatika', 'kelas' => 'B', 'angkatan' => '2023', 'dosen_wali' => 'Prof. Dewi Lestari', 'email' => 'fajar.n@campus.ac.id'],
            ]);
$prodis = collect([
    'Teknik Informatika',
    'Sistem Informasi', 
    'Teknologi Rekayasa Multimedia',
    'Rekayasa Keamanan Siber',
    'Rekayasa Perangkat Lunak',
    'Teknologi Permainan',
    'Animasi'
]);
            $angkatans = $mahasiswa->pluck('angkatan')->unique()->sortDesc()->values();
            
            $dosens = collect([
                (object)['id' => 1, 'nama' => 'Dr. Budi Santoso, M.Kom'],
                (object)['id' => 2, 'nama' => 'Prof. Dewi Lestari, M.Sc'],
                (object)['id' => 3, 'nama' => 'Dr. Eko Prasetyo'],
            ]);
        }

        return view('pages.admin.data_mahasiswa', compact('mahasiswa', 'prodis', 'angkatans', 'dosens'));
    }

    public function createMahasiswa()
    {
        return view('pages.admin.mahasiswa_create');
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
        return view('pages.admin.mahasiswa_edit', compact('mahasiswa'));
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
    $prodis = collect([
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Rekayasa Multimedia',
        'Rekayasa Keamanan Siber',
        'Rekayasa Perangkat Lunak',
        'Teknologi Permainan',
        'Animasi',
    ]);

    $dosen = Schema::hasTable('dosen')
        ? Dosen::orderBy('created_at', 'desc')->get()
        : collect();

    return view('pages.admin.data_dosen', compact('dosen', 'prodis'));
}

public function createDosen()
{
    $prodis = collect([
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Rekayasa Multimedia',
        'Rekayasa Keamanan Siber',
        'Rekayasa Perangkat Lunak',
        'Teknologi Permainan',
        'Animasi',
    ]);

    return view('pages.admin.dosen_create', compact('prodis'));
}

public function storeDosen(Request $request)
{
    $validated = $request->validate([
        'nik' => 'required|string|max:30|unique:dosen,nik',
        'nip' => 'nullable|string|max:30|unique:dosen,nip',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:100|unique:dosen,email|unique:users,email',
        'tipe_dosen' => 'required|string',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:20',
        'password' => 'required|string|min:4',
    ]);

    try {
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (Schema::hasColumn('users', 'nik')) {
            $user->update(['nik' => $validated['nik']]);
        }

        if (Schema::hasColumn('users', 'role')) {
            $role = 'dosen_mk';
            $tipe = strtolower($validated['tipe_dosen']);

            if (
                str_contains($tipe, 'wali') &&
                (str_contains($tipe, 'mata kuliah') || str_contains($tipe, 'mk') || str_contains($tipe, 'keduanya'))
            ) {
                $role = 'dosen';
            } elseif (str_contains($tipe, 'wali')) {
                $role = 'dosen_wali';
            }

            $user->update(['role' => $role]);
        }

        Dosen::create([
            'user_id' => $user->id,
            'nik' => $validated['nik'],
            'nip' => $validated['nip'] ?? null,
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'tipe_dosen' => $validated['tipe_dosen'],
        ]);

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan! Akun login sudah siap digunakan.');
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
            ->withInput();
    }
}

public function editDosen($id)
{
    $dosen = Dosen::findOrFail($id);

    $prodis = collect([
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknologi Rekayasa Multimedia',
        'Rekayasa Keamanan Siber',
        'Rekayasa Perangkat Lunak',
        'Teknologi Permainan',
        'Animasi',
    ]);

    return view('pages.admin.dosen_edit', compact('dosen', 'prodis'));
}

public function updateDosen(Request $request, $id)
{
    $dosen = Dosen::findOrFail($id);

    $validated = $request->validate([
        'nik' => 'required|string|max:30|unique:dosen,nik,' . $id,
        'nip' => 'nullable|string|max:30|unique:dosen,nip,' . $id,
        'nama' => 'required|string|max:100',
        'email' => [
            'required',
            'email',
            'max:100',
            Rule::unique('dosen', 'email')->ignore($id),
            Rule::unique('users', 'email')->ignore($dosen->user_id),
        ],
        'tipe_dosen' => 'required|string',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:20',
        'password' => 'nullable|string|min:4',
    ]);

    try {
        if ($dosen->user_id) {
            $user = User::find($dosen->user_id);

            if ($user) {
                $updateUser = [
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                ];

                if ($request->filled('password')) {
                    $updateUser['password'] = Hash::make($validated['password']);
                }

                if (Schema::hasColumn('users', 'nik')) {
                    $updateUser['nik'] = $validated['nik'];
                }

                if (Schema::hasColumn('users', 'role')) {
                    $role = 'dosen_mk';
                    $tipe = strtolower($validated['tipe_dosen']);

                    if (
                        str_contains($tipe, 'wali') &&
                        (str_contains($tipe, 'mata kuliah') || str_contains($tipe, 'mk') || str_contains($tipe, 'keduanya'))
                    ) {
                        $role = 'dosen';
                    } elseif (str_contains($tipe, 'wali')) {
                        $role = 'dosen_wali';
                    }

                    $updateUser['role'] = $role;
                }

                $user->update($updateUser);
            }
        }

        $dosen->update([
            'nik' => $validated['nik'],
            'nip' => $validated['nip'] ?? null,
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'tipe_dosen' => $validated['tipe_dosen'],
        ]);

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('success', 'Data dosen berhasil diupdate!');
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'Gagal update data: ' . $e->getMessage())
            ->withInput();
    }
}

public function destroyDosen($id)
{
    try {
        $dosen = Dosen::findOrFail($id);

        $dosen->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Data dosen berhasil dihapus dari tampilan admin.']);
        }

        return redirect()
            ->route('pages.admin.dosen.index')
            ->with('success', 'Data dosen berhasil dihapus dari tampilan admin!');
    } catch (\Exception $e) {
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }

        return redirect()
            ->back()
            ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}

// ==========================================
// 4. MATA KULIAH CRUD
// ==========================================

public function indexMatakuliah()
{
    $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('nama', 'asc')
            ->get()
        : collect();

    $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

    $matakuliah = Schema::hasTable('mata_kuliah')
        ? DB::table('mata_kuliah')
            ->leftJoin('dosen', 'mata_kuliah.dosen_id', '=', 'dosen.id')
            ->select(
                'mata_kuliah.id',
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama',
                'mata_kuliah.sks',
                'mata_kuliah.semester_ke',
                'mata_kuliah.dosen_id',
                'mata_kuliah.created_at',
                'mata_kuliah.updated_at',
                'dosen.nama as dosen_pengampu'
            )
            ->when(
                Schema::hasColumn('mata_kuliah', 'deleted_at'),
                fn($query) => $query->whereNull('mata_kuliah.deleted_at')
            )
            ->orderBy('mata_kuliah.created_at', 'desc')
            ->get()
        : collect();

    return view('pages.admin.data_matakuliah', compact(
        'matakuliah',
        'dosens',
        'semesters'
    ));
}

public function createMatakuliah()
{
    $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')
            ->when(
                Schema::hasColumn('dosen', 'deleted_at'),
                fn($query) => $query->whereNull('deleted_at')
            )
            ->orderBy('nama', 'asc')
            ->get()
        : collect();

    $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

    return view('pages.admin.matakuliah_create', compact(
        'dosens',
        'semesters'
    ));
}

public function storeMatakuliah(Request $request)
{
    $validated = $request->validate([
        'kode_mk' => 'required|string|max:30|unique:mata_kuliah,kode_mk',
        'nama' => 'required|string|max:100',
        'sks' => 'required|integer|min:1|max:6',
        'semester_ke' => 'required|integer|min:1|max:8',
        'dosen_id' => 'nullable|exists:dosen,id',
    ], [
        'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
        'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
        'nama.required' => 'Nama mata kuliah wajib diisi.',
        'sks.required' => 'SKS wajib diisi.',
        'semester_ke.required' => 'Semester wajib dipilih.',
    ]);

    try {
        DB::table('mata_kuliah')->insert([
            'dosen_id' => $validated['dosen_id'] ?? null,
            'kode_mk' => $validated['kode_mk'],
            'nama' => $validated['nama'],
            'sks' => $validated['sks'],
            'semester_ke' => $validated['semester_ke'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('pages.admin.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()
            ->with('error', 'Gagal menyimpan mata kuliah: ' . $e->getMessage())
            ->withInput();
    }
}

public function editMatakuliah($id)
{
    $matakuliah = DB::table('mata_kuliah')->where('id', $id)->first();

    if (!$matakuliah) {
        abort(404, 'Data mata kuliah tidak ditemukan.');
    }

    $dosens = Schema::hasTable('dosen')
        ? DB::table('dosen')->orderBy('nama', 'asc')->get()
        : collect();

    $semesters = collect(['1', '2', '3', '4', '5', '6', '7', '8']);

    return view('pages.admin.matakuliah_edit', compact(
        'matakuliah',
        'dosens',
        'semesters'
    ));
}

public function updateMatakuliah(Request $request, $id)
{
    $validated = $request->validate([
        'kode_mk' => 'required|string|max:30|unique:mata_kuliah,kode_mk,' . $id,
        'nama' => 'required|string|max:100',
        'sks' => 'required|integer|min:1|max:6',
        'semester_ke' => 'required|integer|min:1|max:8',
        'dosen_id' => 'nullable|exists:dosen,id',
    ], [
        'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
        'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
        'nama.required' => 'Nama mata kuliah wajib diisi.',
        'sks.required' => 'SKS wajib diisi.',
        'semester_ke.required' => 'Semester wajib dipilih.',
    ]);

    try {
        DB::table('mata_kuliah')
            ->where('id', $id)
            ->update([
                'dosen_id' => $validated['dosen_id'] ?? null,
                'kode_mk' => $validated['kode_mk'],
                'nama' => $validated['nama'],
                'sks' => $validated['sks'],
                'semester_ke' => $validated['semester_ke'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('pages.admin.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui!');
    } catch (\Exception $e) {
        return back()
            ->with('error', 'Gagal memperbarui mata kuliah: ' . $e->getMessage())
            ->withInput();
    }
}

public function destroyMatakuliah(Request $request, $id)
{
    try {
        if (!Schema::hasTable('mata_kuliah')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tabel mata kuliah tidak ditemukan.'], 404);
            }

            return back()->with('error', 'Tabel mata kuliah tidak ditemukan.');
        }

        $matakuliah = DB::table('mata_kuliah')->where('id', $id)->first();

        if (!$matakuliah) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Data mata kuliah tidak ditemukan.'], 404);
            }

            return back()->with('error', 'Data mata kuliah tidak ditemukan.');
        }

        if (!Schema::hasColumn('mata_kuliah', 'deleted_at')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.'], 500);
            }

            return back()->with('error', 'Kolom soft delete belum tersedia. Jalankan migration terlebih dahulu.');
        }

        $updateData = [
            'deleted_at' => now(),
        ];

        if (Schema::hasColumn('mata_kuliah', 'updated_at')) {
            $updateData['updated_at'] = now();
        }

        DB::table('mata_kuliah')->where('id', $id)->update($updateData);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data mata kuliah berhasil dihapus dari tampilan admin!']);
        }

        return redirect()
            ->route('pages.admin.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus dari tampilan admin!');
    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Gagal menghapus mata kuliah: ' . $e->getMessage()], 500);
        }

        return back()
            ->with('error', 'Gagal menghapus mata kuliah: ' . $e->getMessage());
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
                (object)['id' => 2, 'semester' => 'Genap', 'tahun_ajaran' => '2025/2026', 'status' => 'Aktif'],
                (object)['id' => 3, 'semester' => 'Ganjil', 'tahun_ajaran' => '2026/2027', 'status' => 'Nonaktif'],
                (object)['id' => 4, 'semester' => 'Genap', 'tahun_ajaran' => '2027/2028', 'status' => 'Nonaktif'],
            ]);
        }

        return view('pages.admin.data_tahunajaran', compact('tahunAjaran', 'tahunOptions'));
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
            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
        }
    }

    public function destroyTahunAjaran($id)
    {
        try {
            if (Schema::hasTable('tahun_ajarans')) {
                $tahunAjaran = TahunAjaran::findOrFail($id);
                $tahunAjaran->delete();
            }
            return redirect()->route('pages.admin.tahunajaran.index')->with('success', 'Data tahun ajaran berhasil dihapus!');
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

        if (Schema::hasTable('paket_mata_kuliah')) {
            $paketMK = PaketMataKuliah::orderBy('created_at', 'desc')->get();
        } else {
            $paketMK = collect([
                (object)['id' => 1, 'nama_paket' => 'Paket Semester 1', 'semester' => '1', 'prodi' => 'Teknik Informatika', 'total_sks' => 20, 'jumlah_mk' => 7, 'deskripsi' => 'Mata kuliah dasar'],
            ]);
        }

        return view('pages.admin.data_paketmk', compact('paketMK', 'allMataKuliah', 'prodis', 'semesters'));
    }

    public function storePaketMK(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'semester' => 'required|string',
            'prodi' => 'required|string',
            'deskripsi' => 'nullable|string',
            'mata_kuliah' => 'required|array', // Array ID mata kuliah yang dipilih
        ]);

        try {
            
            $selectedIds = $request->mata_kuliah;
            $totalSks = 0;
            
            $dummyMk = collect([
                1 => 4, 2 => 2, 3 => 3, 4 => 3, 5 => 3, 6 => 3, 7 => 3
            ]);

            foreach ($selectedIds as $id) {
                $totalSks += $dummyMk[$id] ?? 0;
            }

            $data = $request->except('mata_kuliah');
            $data['total_sks'] = $totalSks;
            $data['jumlah_mk'] = count($selectedIds);

            if (Schema::hasTable('paket_mata_kuliah')) {
                $paket = PaketMataKuliah::create($data);
            }

            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.')->withInput();
        }
    }

    public function destroyPaketMK($id)
    {
        try {
            if (Schema::hasTable('paket_mata_kuliah')) {
                $paket = PaketMataKuliah::findOrFail($id);
                $paket->delete();
            }
            return redirect()->route('pages.admin.paketmk.index')->with('success', 'Data paket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
