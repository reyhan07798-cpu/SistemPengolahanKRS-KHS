<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAdminData;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class SemesterMahasiswaController extends Controller
{
    use HandlesAdminData;

    // Menampilkan status semester mahasiswa berdasarkan filter semester, prodi, dan kelas.
    public function indexSemesterMahasiswa(Request $request)
    {
        $semesters = DB::table('semesters')
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('semester_ke')
            ->get()
            ->map(function ($semester) {
                $semester->period_order = $this->semesterPeriodOrder($semester);

                return $semester;
            });
        $activeSemester = $semesters->firstWhere('is_active', 1) ?? $semesters->first();
        $selectedSemesterId = (int) ($request->input('semester_id') ?: ($activeSemester->id ?? 0));
        $selectedSemester = $semesters->firstWhere('id', $selectedSemesterId) ?? $activeSemester;
        $selectedProdi = $request->input('prodi', '');
        $selectedKelas = $request->input('kelas', '');
        $prodis = $this->getProdiOptions();
        $kelasList = $this->getKelasOptions();
        $mahasiswa = collect();
        if ($selectedSemester && Schema::hasTable('mahasiswa_semester')) {
            $maxSemesterMahasiswa = DB::table('mahasiswa_semester')
                ->select('mahasiswa_id', DB::raw('MAX(semester_ke) as max_semester_ke'))
                ->groupBy('mahasiswa_id');

            // leftJoin dipakai agar mahasiswa yang belum punya status semester tetap terlihat.
            $mahasiswa = DB::table('mahasiswa')
                ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                ->leftJoin('mahasiswa_semester', function ($join) use ($selectedSemester) {
                    $join->on('mahasiswa.id', '=', 'mahasiswa_semester.mahasiswa_id')
                        ->where('mahasiswa_semester.semester_id', $selectedSemester->id);
                })
                ->leftJoinSub($maxSemesterMahasiswa, 'max_semester_mahasiswa', function ($join) {
                    $join->on('mahasiswa.id', '=', 'max_semester_mahasiswa.mahasiswa_id');
                })
                ->select(
                    'mahasiswa.id',
                    'mahasiswa.nim',
                    'mahasiswa.nama',
                    'mahasiswa.kelas',
                    'mahasiswa.angkatan',
                    DB::raw("COALESCE(prodi.nama_prodi, '-') as prodi"),
                    'mahasiswa_semester.id as progress_id',
                    'mahasiswa_semester.semester_ke',
                    'mahasiswa_semester.status',
                    'mahasiswa_semester.catatan',
                    DB::raw('COALESCE(max_semester_mahasiswa.max_semester_ke, mahasiswa_semester.semester_ke, 1) as max_semester_ke')
                )
                ->when(
                    Schema::hasColumn('mahasiswa', 'deleted_at'),
                    fn ($query) => $query->whereNull('mahasiswa.deleted_at')
                )
                ->when($selectedProdi !== '', function ($query) use ($selectedProdi) {
                    $query->where('prodi.nama_prodi', $selectedProdi);
                })
                ->when($selectedKelas !== '', function ($query) use ($selectedKelas) {
                    $query->where('mahasiswa.kelas', $selectedKelas);
                })
                ->orderBy('mahasiswa.kelas')
                ->orderBy('mahasiswa.nama')
                ->get();
        }

        // Statistik kecil untuk kartu ringkasan di atas tabel.
        $stats = [
            'total' => $mahasiswa->count(),
            'aktif' => $mahasiswa->where('status', 'aktif')->count(),
            'cuti' => $mahasiswa->where('status', 'cuti')->count(),
            'mengulang' => $mahasiswa->where('status', 'mengulang')->count(),
            'belum_diatur' => $mahasiswa->whereNull('progress_id')->count(),
        ];

        return view('pages.admin.semester_mahasiswa', compact(
            'semesters',
            'selectedSemester',
            'selectedSemesterId',
            'selectedProdi',
            'selectedKelas',
            'prodis',
            'kelasList',
            'mahasiswa',
            'stats'
        ));
    }

    // Mengubah semester ke, status, dan catatan untuk satu mahasiswa.
    public function updateSemesterMahasiswa(Request $request, $mahasiswaId)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'semester_ke' => 'required|integer|min:1|max:14',
            'status' => ['required', Rule::in(['aktif', 'cuti', 'mengulang', 'lulus', 'nonaktif'])],
            'catatan' => 'nullable|string|max:500',
            'prodi' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50',
        ]);
        $mahasiswa = Mahasiswa::with('prodi')->findOrFail($mahasiswaId);

        $maxSemesterKe = (int) MahasiswaSemester::where('mahasiswa_id', $mahasiswaId)
            ->max('semester_ke');

        // Semester mahasiswa tidak boleh diturunkan agar riwayat akademik tetap runtut.
        if ($maxSemesterKe > 0 && (int) $validated['semester_ke'] < $maxSemesterKe) {
            return back()
                ->withErrors([
                    'semester_ke' => 'Semester mahasiswa tidak boleh diturunkan dari Semester '
                        . $maxSemesterKe
                        . ' ke Semester '
                        . $validated['semester_ke']
                        . '.',
                ])
                ->withInput();
        }

        // updateOrCreate: kalau record sudah ada maka update, kalau belum ada maka buat baru.
        MahasiswaSemester::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'semester_id' => $validated['semester_id'],
            ],
            [
                'semester_ke' => $validated['semester_ke'],
                'status' => $validated['status'],
                'catatan' => $validated['catatan'] ?? null,
            ]
        );
        $this->syncMahasiswaKelasForSemester($mahasiswa, (int) $validated['semester_ke']);

        return redirect()
            ->route('pages.admin.semester-mahasiswa.index', [
                'semester_id' => $validated['semester_id'],
                'prodi' => $validated['prodi'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
            ])
            ->with('success', 'Status semester mahasiswa berhasil diperbarui.');
    }

    // Memproses naik semester massal untuk mahasiswa yang masih aktif.
    public function promoteSemesterMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'from_semester_id' => 'required|exists:semesters,id|different:to_semester_id',
            'to_semester_id' => 'required|exists:semesters,id',
            'prodi' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50',
        ]);
        $targetSemesterId = (int) $validated['to_semester_id'];
        $sourceSemester = DB::table('semesters')->where('id', $validated['from_semester_id'])->first();
        $targetSemester = DB::table('semesters')->where('id', $targetSemesterId)->first();

        // Semester tujuan harus periode yang lebih baru dari semester asal.
        if (! $this->isLaterSemesterPeriod($sourceSemester, $targetSemester)) {
            return back()
                ->withErrors([
                    'to_semester_id' => 'Semester tujuan harus lebih baru dari semester asal.',
                ])
                ->withInput();
        }

        $sourceRecords = DB::table('mahasiswa_semester')
            ->join('mahasiswa', 'mahasiswa_semester.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
            ->select('mahasiswa_semester.*')
            ->where('semester_id', $validated['from_semester_id'])
            ->where('status', 'aktif')
            ->when(! empty($validated['prodi']), function ($query) use ($validated) {
                $query->where('prodi.nama_prodi', $validated['prodi']);
            })
            ->when(! empty($validated['kelas']), function ($query) use ($validated) {
                $query->where('mahasiswa.kelas', $validated['kelas']);
            })
            ->get();
        $created = 0;
        $skipped = 0;
        DB::transaction(function () use ($sourceRecords, $targetSemesterId, &$created, &$skipped) {
            // Mahasiswa yang sudah punya record di semester tujuan dilewati.
            foreach ($sourceRecords as $record) {
                $exists = DB::table('mahasiswa_semester')
                    ->where('mahasiswa_id', $record->mahasiswa_id)
                    ->where('semester_id', $targetSemesterId)
                    ->exists();
                if ($exists) {
                    $skipped++;

                    continue;
                }
                DB::table('mahasiswa_semester')->insert([
                    'mahasiswa_id' => $record->mahasiswa_id,
                    'semester_id' => $targetSemesterId,
                    'semester_ke' => min(14, ((int) $record->semester_ke) + 1),
                    'status' => 'aktif',
                    'catatan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $mahasiswa = Mahasiswa::with('prodi')->find($record->mahasiswa_id);
                if ($mahasiswa) {
                    $this->syncMahasiswaKelasForSemester($mahasiswa, min(14, ((int) $record->semester_ke) + 1));
                }
                $created++;
            }
        });

        return redirect()
            ->route('pages.admin.semester-mahasiswa.index', [
                'semester_id' => $targetSemesterId,
                'prodi' => $validated['prodi'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
            ])
            ->with('success', "Naik semester selesai. {$created} mahasiswa dibuat, {$skipped} dilewati karena sudah ada di semester tujuan.");
    }

    private function isLaterSemesterPeriod(?object $sourceSemester, ?object $targetSemester): bool
    {
        if (! $sourceSemester || ! $targetSemester) {
            return false;
        }

        return $this->semesterPeriodOrder($targetSemester) > $this->semesterPeriodOrder($sourceSemester);
    }

    // Mengubah tahun ajaran dan semester menjadi angka agar mudah dibandingkan.
    private function semesterPeriodOrder(object $semester): int
    {
        $startYear = (int) substr((string) $semester->tahun_ajaran, 0, 4);

        return ($startYear * 10) + (int) $semester->semester_ke;
    }
}
