<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaDosenMataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedAdmin();

            $prodiIds = $this->seedProdi();
            $semesterIds = $this->seedSemesters();
            $this->seedTahunAjaran();
            $kelasIds = $this->seedKelas();
            $dosenIds = $this->seedDosen();
            $mahasiswaIds = $this->seedMahasiswa($prodiIds['IF'], $dosenIds['11111111']);
            $this->seedMahasiswaSemester($mahasiswaIds, $semesterIds);
            $mataKuliahIds = $this->seedMataKuliah($semesterIds, $dosenIds);

            $this->seedDosenMataKuliah($mataKuliahIds, $kelasIds, $dosenIds);
            $this->seedKrs($mahasiswaIds, $semesterIds, $mataKuliahIds);
            $this->seedNilai($mahasiswaIds, $semesterIds, $mataKuliahIds);
            $this->seedPaketMataKuliah($prodiIds['IF'], $semesterIds['2025/2026-2'], $mataKuliahIds);
        });

        $this->command?->info('Seeder demo selesai.');
        $this->command?->info('Admin: admin123 / admin123');
        $this->command?->info('Mahasiswa: 3312501017 / 3312501017');
        $this->command?->info('Mahasiswa: 3312501007 / 3312501007');
        $this->command?->info('Mahasiswa: 3312501022 / 3312501022');
        $this->command?->info('Dosen: 11111111 / password123');
        $this->command?->info('Dosen MK: 87654321 / password123');
    }

    private function seedAdmin(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@krs.com'],
            [
                'name' => 'Admin',
                'username' => 'admin123',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
                'deleted_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function seedProdi(): array
    {
        $data = [
            'IF' => 'Teknik Informatika',
            'SI' => 'Sistem Informasi',
        ];

        $ids = [];

        foreach ($data as $kode => $nama) {
            DB::table('prodi')->updateOrInsert(
                ['kode_prodi' => $kode],
                [
                    'nama_prodi' => $nama,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $ids[$kode] = (int) DB::table('prodi')->where('kode_prodi', $kode)->value('id');
        }

        return $ids;
    }

    private function seedSemesters(): array
    {
        $semesters = [
            '2024/2025-1' => [
                'nama' => 'Semester Ganjil 2024/2025',
                'tahun_ajaran' => '2024/2025',
                'semester' => 'Ganjil',
                'semester_ke' => 1,
                'tanggal_mulai' => '2024-08-01',
                'tanggal_selesai' => '2025-01-31',
                'is_active' => false,
            ],
            '2025/2026-1' => [
                'nama' => 'Semester Ganjil 2025/2026',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Ganjil',
                'semester_ke' => 1,
                'tanggal_mulai' => '2025-08-01',
                'tanggal_selesai' => '2026-01-31',
                'is_active' => false,
            ],
            '2025/2026-2' => [
                'nama' => 'Semester Genap 2025/2026',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'semester_ke' => 2,
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-07-31',
                'is_active' => true,
            ],
            '2026/2027-1' => [
                'nama' => 'Semester Ganjil 2026/2027',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'semester_ke' => 1,
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2027-01-31',
                'is_active' => false,
            ],
        ];

        DB::table('semesters')->update(['is_active' => false]);

        $ids = [];

        foreach ($semesters as $key => $semester) {
            DB::table('semesters')->updateOrInsert(
                [
                    'tahun_ajaran' => $semester['tahun_ajaran'],
                    'semester_ke' => $semester['semester_ke'],
                ],
                array_merge($semester, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );

            $ids[$key] = (int) DB::table('semesters')
                ->where('tahun_ajaran', $semester['tahun_ajaran'])
                ->where('semester_ke', $semester['semester_ke'])
                ->value('id');
        }

        return $ids;
    }

    private function seedTahunAjaran(): void
    {
        $data = [
            ['tahun_ajaran' => '2025/2026', 'semester' => 'Genap', 'status' => 'Aktif'],
            ['tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'Nonaktif'],
            ['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'status' => 'Nonaktif'],
        ];

        foreach ($data as $row) {
            DB::table('tahun_ajarans')->updateOrInsert(
                [
                    'tahun_ajaran' => $row['tahun_ajaran'],
                    'semester' => $row['semester'],
                ],
                array_merge($row, [
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }

    private function seedKelas(): array
    {
        $kelas = ['IF2A-PAGI', 'IF2B-PAGI'];
        $ids = [];

        foreach ($kelas as $namaKelas) {
            DB::table('kelas')->updateOrInsert(
                ['nama_kelas' => $namaKelas],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $ids[$namaKelas] = (int) DB::table('kelas')->where('nama_kelas', $namaKelas)->value('id');
        }

        return $ids;
    }

    private function seedDosen(): array
    {
        $dosenData = [
            [
                'nik' => '11111111',
                'nip' => '198001012005011001',
                'nama' => 'Metta Santiputri, S.T., M.Sc, Ph.D',
                'email' => 'metta@gmail.com',
                'role' => 'dosen',
                'tipe_dosen' => 'keduanya',
                'fakultas' => 'Teknik Informatika',
            ],
            [
                'nik' => '87654321',
                'nip' => '198002022006021002',
                'nama' => 'Dr. MK Only',
                'email' => 'dosenmk@krs.com',
                'role' => 'dosen_mk',
                'tipe_dosen' => 'Dosen Mata Kuliah',
                'fakultas' => 'Teknik Informatika',
            ],
            [
                'nik' => '1988000001',
                'nip' => '1988000001202601001',
                'nama' => 'Dr. Budi Santoso, M.Kom',
                'email' => 'budi.santoso@kampus.test',
                'role' => 'dosen',
                'tipe_dosen' => 'keduanya',
                'fakultas' => 'Teknik Informatika',
            ],
            [
                'nik' => '1989000002',
                'nip' => '1989000002202601002',
                'nama' => 'Nabila Fatin, M.Cs',
                'email' => 'nabila.fatin@kampus.test',
                'role' => 'dosen_mk',
                'tipe_dosen' => 'Dosen Mata Kuliah',
                'fakultas' => 'Teknik Informatika',
            ],
        ];

        $ids = [];

        foreach ($dosenData as $dosen) {
            DB::table('users')->updateOrInsert(
                ['nik' => $dosen['nik']],
                [
                    'name' => $dosen['nama'],
                    'email' => $dosen['email'],
                    'nik' => $dosen['nik'],
                    'username' => $dosen['nik'],
                    'role' => $dosen['role'],
                    'password' => Hash::make('password123'),
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $userId = (int) DB::table('users')->where('nik', $dosen['nik'])->value('id');

            DB::table('dosen')->updateOrInsert(
                ['nik' => $dosen['nik']],
                [
                    'user_id' => $userId,
                    'nip' => $dosen['nip'],
                    'nama' => $dosen['nama'],
                    'email' => $dosen['email'],
                    'no_hp' => '0812' . substr($dosen['nik'], -8),
                    'alamat' => 'Kampus Polibatam',
                    'tipe_dosen' => $dosen['tipe_dosen'],
                    'fakultas' => $dosen['fakultas'],
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $ids[$dosen['nik']] = (int) DB::table('dosen')->where('nik', $dosen['nik'])->value('id');
        }

        return $ids;
    }

    private function seedMahasiswa(int $prodiId, int $dosenWaliId): array
    {
        $mahasiswaData = [
            [
                'nim' => '3312501017',
                'nama' => 'Irenessa Rosidin',
                'email' => 'irene@krs.com',
                'kelas' => 'IF2A-PAGI',
            ],
            [
                'nim' => '3312501007',
                'nama' => 'Nabila Fatin',
                'email' => 'nabila@krs.com',
                'kelas' => 'IF2A-PAGI',
            ],
            [
                'nim' => '3312501022',
                'nama' => 'Reyhan',
                'email' => 'reyhan@krs.com',
                'kelas' => 'IF2B-PAGI',
            ],
            [
                'nim' => '3312601001',
                'nama' => 'Irenessa Rosdin',
                'email' => 'irenessa.rosdin@student.test',
                'kelas' => 'IF2A-PAGI',
            ],
            [
                'nim' => '3312601002',
                'nama' => 'Reyhan Pratama',
                'email' => 'reyhan.pratama@student.test',
                'kelas' => 'IF2A-PAGI',
            ],
            [
                'nim' => '3312601003',
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.test',
                'kelas' => 'IF2B-PAGI',
            ],
        ];

        $this->cleanupDuplicateMahasiswaData($mahasiswaData);

        $ids = [];

        foreach ($mahasiswaData as $mahasiswa) {
            DB::table('users')->updateOrInsert(
                ['nim' => $mahasiswa['nim']],
                [
                    'name' => $mahasiswa['nama'],
                    'email' => $mahasiswa['email'],
                    'nim' => $mahasiswa['nim'],
                    'username' => $mahasiswa['nim'],
                    'role' => 'mahasiswa',
                    'password' => Hash::make($mahasiswa['nim']),
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $userId = (int) DB::table('users')->where('nim', $mahasiswa['nim'])->value('id');

            DB::table('mahasiswa')->updateOrInsert(
                ['nim' => $mahasiswa['nim']],
                [
                    'user_id' => $userId,
                    'dosen_wali_id' => $dosenWaliId,
                    'prodi_id' => $prodiId,
                    'nama' => $mahasiswa['nama'],
                    'email' => $mahasiswa['email'],
                    'no_hp' => '0813' . substr($mahasiswa['nim'], -8),
                    'alamat' => 'Alamat Mahasiswa Demo',
                    'angkatan' => substr($mahasiswa['nim'], 2, 2) === '12' ? '2026' : '2025',
                    'kelas' => $mahasiswa['kelas'],
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $ids[$mahasiswa['nim']] = (int) DB::table('mahasiswa')->where('nim', $mahasiswa['nim'])->value('id');
        }

        return $ids;
    }

    private function cleanupDuplicateMahasiswaData(array $mahasiswaData): void
    {
        $targetNims = array_column($mahasiswaData, 'nim');
        $targetEmails = array_column($mahasiswaData, 'email');

        $duplicateMahasiswaIds = DB::table('mahasiswa')
            ->whereIn('email', $targetEmails)
            ->whereNotIn('nim', $targetNims)
            ->pluck('id');

        if ($duplicateMahasiswaIds->isNotEmpty()) {
            $krsIds = DB::table('krs_mahasiswa')
                ->whereIn('mahasiswa_id', $duplicateMahasiswaIds)
                ->pluck('id');

            if ($krsIds->isNotEmpty()) {
                DB::table('krs_detail')
                    ->whereIn('krs_mahasiswa_id', $krsIds)
                    ->delete();
            }

            DB::table('krs_mahasiswa')
                ->whereIn('mahasiswa_id', $duplicateMahasiswaIds)
                ->delete();

            DB::table('nilai')
                ->whereIn('mahasiswa_id', $duplicateMahasiswaIds)
                ->delete();

            DB::table('mahasiswa_semester')
                ->whereIn('mahasiswa_id', $duplicateMahasiswaIds)
                ->delete();

            DB::table('mahasiswa')
                ->whereIn('id', $duplicateMahasiswaIds)
                ->delete();
        }

        DB::table('users')
            ->whereIn('email', $targetEmails)
            ->where(function ($query) use ($targetNims) {
                $query->whereNotIn('nim', $targetNims)
                    ->orWhereNull('nim');
            })
            ->delete();
    }

    private function seedMataKuliah(array $semesterIds, array $dosenIds): array
    {
        $mataKuliahData = [
            ['kode_mk' => 'IF207', 'nama' => 'Proyek Pembuatan Prototipe', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '11111111'],
            ['kode_mk' => 'IF208', 'nama' => 'Dasar Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '11111111'],
            ['kode_mk' => 'IF212', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '11111111'],
            ['kode_mk' => 'IF209', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF210', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF211', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF209', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2B-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF210', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2B-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF211', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2B-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF213', 'nama' => 'Bahasa Inggris untuk Komunikasi', 'sks' => 2, 'semester' => '2025/2026-2', 'semester_ke' => 2, 'kelas' => 'IF2B-PAGI', 'dosen_nik' => '87654321'],
            ['kode_mk' => 'IF101', 'nama' => 'Pemrograman Dasar', 'sks' => 3, 'semester' => '2026/2027-1', 'semester_ke' => 1, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '1988000001'],
            ['kode_mk' => 'IF102', 'nama' => 'Basis Data', 'sks' => 3, 'semester' => '2026/2027-1', 'semester_ke' => 1, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '1989000002'],
            ['kode_mk' => 'IF103', 'nama' => 'Algoritma dan Struktur Data', 'sks' => 3, 'semester' => '2026/2027-1', 'semester_ke' => 1, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '1988000001'],
            ['kode_mk' => 'IF104', 'nama' => 'Matematika Diskrit', 'sks' => 2, 'semester' => '2026/2027-1', 'semester_ke' => 1, 'kelas' => 'IF2A-PAGI', 'dosen_nik' => '1989000002'],
        ];

        $ids = [];

        foreach ($mataKuliahData as $mk) {
            $semester = DB::table('semesters')->where('id', $semesterIds[$mk['semester']])->first();
            $dosenId = $dosenIds[$mk['dosen_nik']];

            DB::table('mata_kuliah')->updateOrInsert(
                [
                    'kode_mk' => $mk['kode_mk'],
                    'tahun_ajaran' => $semester->tahun_ajaran,
                    'semester_ke' => $mk['semester_ke'],
                    'kelas' => $mk['kelas'],
                ],
                [
                    'nama' => $mk['nama'],
                    'sks' => $mk['sks'],
                    'semester_id' => $semester->id,
                    'dosen_id' => $dosenId,
                    'dosen_nik' => $mk['dosen_nik'],
                    'prasyarat' => null,
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $ids[$mk['kode_mk'] . '|' . $mk['kelas'] . '|' . $semester->tahun_ajaran] = (int) DB::table('mata_kuliah')
                ->where('kode_mk', $mk['kode_mk'])
                ->where('tahun_ajaran', $semester->tahun_ajaran)
                ->where('semester_ke', $mk['semester_ke'])
                ->where('kelas', $mk['kelas'])
                ->value('id');
        }

        return $ids;
    }

    private function seedMahasiswaSemester(array $mahasiswaIds, array $semesterIds): void
    {
        $data = [
            ['nim' => '3312501017', 'semester' => '2025/2026-2', 'semester_ke' => 2, 'status' => 'aktif'],
            ['nim' => '3312501007', 'semester' => '2025/2026-2', 'semester_ke' => 2, 'status' => 'aktif'],
            ['nim' => '3312501022', 'semester' => '2025/2026-2', 'semester_ke' => 2, 'status' => 'aktif'],
            ['nim' => '3312601001', 'semester' => '2025/2026-2', 'semester_ke' => 1, 'status' => 'aktif'],
            ['nim' => '3312601002', 'semester' => '2025/2026-2', 'semester_ke' => 1, 'status' => 'cuti'],
            ['nim' => '3312601003', 'semester' => '2025/2026-2', 'semester_ke' => 1, 'status' => 'mengulang'],
        ];

        foreach ($data as $row) {
            DB::table('mahasiswa_semester')->updateOrInsert(
                [
                    'mahasiswa_id' => $mahasiswaIds[$row['nim']],
                    'semester_id' => $semesterIds[$row['semester']],
                ],
                [
                    'semester_ke' => $row['semester_ke'],
                    'status' => $row['status'],
                    'catatan' => $row['status'] === 'aktif' ? null : ucfirst($row['status']) . ' dari data demo.',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function seedDosenMataKuliah(array $mataKuliahIds, array $kelasIds, array $dosenIds): void
    {
        foreach ($mataKuliahIds as $key => $mataKuliahId) {
            [$kodeMk, $kelas] = explode('|', $key);
            $dosenNik = in_array($kodeMk, ['IF207', 'IF208', 'IF212', 'IF101', 'IF103'], true)
                ? (str_starts_with($kodeMk, 'IF1') ? '1988000001' : '11111111')
                : (str_starts_with($kodeMk, 'IF1') ? '1989000002' : '87654321');

            DB::table('dosen_matakuliah')->updateOrInsert(
                [
                    'dosen_id' => $dosenIds[$dosenNik],
                    'mata_kuliah_id' => $mataKuliahId,
                    'kelas_id' => $kelasIds[$kelas] ?? null,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function seedKrs(array $mahasiswaIds, array $semesterIds, array $mataKuliahIds): void
    {
        $semesterAktif = DB::table('semesters')->where('id', $semesterIds['2025/2026-2'])->first();

        $krsData = [
            [
                'nim' => '3312501017',
                'kelas' => 'IF2A-PAGI',
                'status' => 'menunggu',
                'mata_kuliah' => ['IF207', 'IF208', 'IF212', 'IF209', 'IF210', 'IF211'],
            ],
            [
                'nim' => '3312501007',
                'kelas' => 'IF2A-PAGI',
                'status' => 'disetujui',
                'mata_kuliah' => ['IF207', 'IF208', 'IF212', 'IF209', 'IF210', 'IF211'],
            ],
            [
                'nim' => '3312501022',
                'kelas' => 'IF2B-PAGI',
                'status' => 'disetujui',
                'mata_kuliah' => ['IF209', 'IF210', 'IF211', 'IF213'],
            ],
        ];

        foreach ($krsData as $krs) {
            $mataKuliahIdsForKrs = collect($krs['mata_kuliah'])
                ->map(fn ($kodeMk) => $mataKuliahIds[$kodeMk . '|' . $krs['kelas'] . '|' . $semesterAktif->tahun_ajaran])
                ->values();

            $totalSks = (int) DB::table('mata_kuliah')
                ->whereIn('id', $mataKuliahIdsForKrs)
                ->sum('sks');

            DB::table('krs_mahasiswa')->updateOrInsert(
                [
                    'mahasiswa_id' => $mahasiswaIds[$krs['nim']],
                    'semester_id' => $semesterAktif->id,
                ],
                [
                    'kelas' => $krs['kelas'],
                    'semester_ke' => $semesterAktif->semester_ke,
                    'tahun_ajaran' => $semesterAktif->tahun_ajaran,
                    'semester' => $semesterAktif->semester,
                    'status' => $krs['status'],
                    'total_sks' => $totalSks,
                    'catatan' => null,
                    'tanggal_disetujui' => $krs['status'] === 'disetujui' ? now() : null,
                    'disetujui_oleh' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $krsId = (int) DB::table('krs_mahasiswa')
                ->where('mahasiswa_id', $mahasiswaIds[$krs['nim']])
                ->where('semester_id', $semesterAktif->id)
                ->value('id');

            DB::table('krs_detail')->where('krs_mahasiswa_id', $krsId)->delete();

            foreach ($mataKuliahIdsForKrs as $mataKuliahId) {
                DB::table('krs_detail')->insert([
                    'krs_mahasiswa_id' => $krsId,
                    'mata_kuliah_id' => $mataKuliahId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedNilai(array $mahasiswaIds, array $semesterIds, array $mataKuliahIds): void
    {
        $semesterAktif = DB::table('semesters')->where('id', $semesterIds['2025/2026-2'])->first();

        $nilaiData = [
            ['kode_mk' => 'IF209', 'nilai' => 'A', 'nilai_akhir' => 91.40, 'bobot' => 4.00, 'sks' => 3],
            ['kode_mk' => 'IF210', 'nilai' => 'A-', 'nilai_akhir' => 85.05, 'bobot' => 3.75, 'sks' => 3],
            ['kode_mk' => 'IF211', 'nilai' => 'B+', 'nilai_akhir' => 77.55, 'bobot' => 3.50, 'sks' => 3],
            ['kode_mk' => 'IF213', 'nilai' => 'A', 'nilai_akhir' => 90.45, 'bobot' => 4.00, 'sks' => 2],
        ];

        foreach ($nilaiData as $nilai) {
            $mataKuliahId = $mataKuliahIds[$nilai['kode_mk'] . '|IF2B-PAGI|' . $semesterAktif->tahun_ajaran];

            DB::table('nilai')->updateOrInsert(
                [
                    'mahasiswa_id' => $mahasiswaIds['3312501022'],
                    'mata_kuliah_id' => $mataKuliahId,
                    'semester_id' => $semesterAktif->id,
                ],
                [
                    'nilai' => $nilai['nilai'],
                    'nilai_tugas' => $nilai['nilai_akhir'] - 2,
                    'nilai_praktikum' => $nilai['nilai_akhir'] - 1,
                    'nilai_uts' => $nilai['nilai_akhir'],
                    'nilai_uas' => $nilai['nilai_akhir'] + 1,
                    'nilai_kehadiran' => 100,
                    'nilai_akhir' => $nilai['nilai_akhir'],
                    'kelas' => 'IF2B-PAGI',
                    'bobot_tugas' => 20,
                    'bobot_praktikum' => 15,
                    'bobot_uts' => 30,
                    'bobot_uas' => 30,
                    'bobot_kehadiran' => 5,
                    'dosen_nik' => '87654321',
                    'bobot' => $nilai['bobot'],
                    'sks' => $nilai['sks'],
                    'semester' => $semesterAktif->semester_ke,
                    'tahun_ajaran' => $semesterAktif->tahun_ajaran,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    private function seedPaketMataKuliah(int $prodiId, int $semesterId, array $mataKuliahIds): void
    {
        DB::table('paket_mata_kuliahs')->updateOrInsert(
            ['nama_paket' => 'Paket Semester 2 Teknik Informatika'],
            [
                'semester_id' => $semesterId,
                'prodi_id' => $prodiId,
                'deskripsi' => 'Paket mata kuliah demo untuk mahasiswa IF2A semester 2.',
                'deleted_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $paketId = (int) DB::table('paket_mata_kuliahs')
            ->where('nama_paket', 'Paket Semester 2 Teknik Informatika')
            ->value('id');

        DB::table('paket_mata_kuliah_details')
            ->where('paket_mata_kuliah_id', $paketId)
            ->delete();

        foreach (['IF207', 'IF208', 'IF212', 'IF209', 'IF210', 'IF211'] as $kodeMk) {
            DB::table('paket_mata_kuliah_details')->insert([
                'paket_mata_kuliah_id' => $paketId,
                'mata_kuliah_id' => $mataKuliahIds[$kodeMk . '|IF2A-PAGI|2025/2026'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
