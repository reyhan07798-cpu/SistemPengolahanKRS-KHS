<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\PaketMataKuliahController as AdminPaketMataKuliahController;
use App\Http\Controllers\Admin\SemesterMahasiswaController as AdminSemesterMahasiswaController;
use App\Http\Controllers\Admin\TahunAjaranController as AdminTahunAjaranController;
use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\DosenMKController;
use App\Http\Controllers\DosenWaliController;
use App\Http\Controllers\KhsMahasiswaController;
use App\Http\Controllers\KrsVerifikasiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MhsPdfController;
use App\Http\Controllers\ProfilDosenWaliController;
use Illuminate\Support\Facades\Route;

// PUBLIC
Route::get('/', fn () => view('landing'))->name('landing');
Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// PROTECTED
Route::middleware('check.simple.auth')->group(function () {

    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');

    // ADMIN
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboardAdmin'])->name('pages.admin.dashboard');

    Route::prefix('admin/mahasiswa')->name('pages.admin.mahasiswa.')->group(function () {
        Route::get('/', [AdminMahasiswaController::class, 'indexMahasiswa'])->name('index');
        Route::get('/create', [AdminMahasiswaController::class, 'createMahasiswa'])->name('create');
        Route::post('/', [AdminMahasiswaController::class, 'storeMahasiswa'])->name('store');
        Route::get('/{id}/edit', [AdminMahasiswaController::class, 'editMahasiswa'])->name('edit');
        Route::put('/{id}', [AdminMahasiswaController::class, 'updateMahasiswa'])->name('update');
        Route::delete('/{id}', [AdminMahasiswaController::class, 'destroyMahasiswa'])->name('destroy');
    });

    Route::prefix('admin/semester-mahasiswa')->name('pages.admin.semester-mahasiswa.')->group(function () {
        Route::get('/', [AdminSemesterMahasiswaController::class, 'indexSemesterMahasiswa'])->name('index');
        Route::post('/naik-semester', [AdminSemesterMahasiswaController::class, 'promoteSemesterMahasiswa'])->name('promote');
        Route::put('/{mahasiswaId}', [AdminSemesterMahasiswaController::class, 'updateSemesterMahasiswa'])->name('update');
    });

    Route::prefix('admin/dosen')->name('pages.admin.dosen.')->group(function () {
        Route::get('/', [AdminDosenController::class, 'indexDosen'])->name('index');
        Route::get('/create', [AdminDosenController::class, 'createDosen'])->name('create');
        Route::post('/', [AdminDosenController::class, 'storeDosen'])->name('store');
        Route::get('/{id}/edit', [AdminDosenController::class, 'editDosen'])->name('edit');
        Route::put('/{id}', [AdminDosenController::class, 'updateDosen'])->name('update');
        Route::delete('/{id}', [AdminDosenController::class, 'destroyDosen'])->name('destroy');
    });

    Route::prefix('admin/matakuliah')->name('pages.admin.matakuliah.')->group(function () {
        Route::get('/', [AdminMataKuliahController::class, 'indexMatakuliah'])->name('index');
        Route::post('/', [AdminMataKuliahController::class, 'storeMatakuliah'])->name('store');
        Route::get('/{id}/edit', [AdminMataKuliahController::class, 'editMatakuliah'])->name('edit');
        Route::put('/{id}', [AdminMataKuliahController::class, 'updateMatakuliah'])->name('update');
        Route::delete('/{id}', [AdminMataKuliahController::class, 'destroyMatakuliah'])->name('destroy');
    });

    Route::prefix('admin/tahun-ajaran')->name('pages.admin.tahunajaran.')->group(function () {
        Route::get('/', [AdminTahunAjaranController::class, 'indexTahunAjaran'])->name('index');
        Route::get('/create', [AdminTahunAjaranController::class, 'createTahunAjaran'])->name('create');
        Route::post('/', [AdminTahunAjaranController::class, 'storeTahunAjaran'])->name('store');
        Route::get('/{id}/edit', [AdminTahunAjaranController::class, 'editTahunAjaran'])->name('edit');
        Route::put('/{id}', [AdminTahunAjaranController::class, 'updateTahunAjaran'])->name('update');
        Route::delete('/{id}', [AdminTahunAjaranController::class, 'destroyTahunAjaran'])->name('destroy');
    });

    Route::prefix('admin/paket-mk')->name('pages.admin.paketmk.')->group(function () {
        Route::get('/', [AdminPaketMataKuliahController::class, 'indexPaketMK'])->name('index');
        Route::get('/create', [AdminPaketMataKuliahController::class, 'createPaketMK'])->name('create');
        Route::post('/', [AdminPaketMataKuliahController::class, 'storePaketMK'])->name('store');
        Route::get('/{id}/edit', [AdminPaketMataKuliahController::class, 'editPaketMK'])->name('edit');
        Route::put('/{id}', [AdminPaketMataKuliahController::class, 'updatePaketMK'])->name('update');
        Route::delete('/{id}', [AdminPaketMataKuliahController::class, 'destroyPaketMK'])->name('destroy');
    });

    // MAHASISWA
    Route::prefix('mahasiswa')->name('pages.mahasiswa.')->group(function () {
        Route::get('/beranda', [MahasiswaController::class, 'index'])->name('beranda');

        Route::get('/ambil-krs', [MahasiswaController::class, 'ambilKrs'])->name('ambil-krs');
        Route::post('/ambil-krs', [MahasiswaController::class, 'storeKrs'])->name('store-krs');
        Route::get('/lihat-krs', [MahasiswaController::class, 'viewKrs'])->name('lihat-krs');

        Route::get('/lihat-khs', [KhsMahasiswaController::class, 'index'])->name('lihat-khs');

        Route::get('/profil', [MahasiswaController::class, 'profil'])->name('profil');
        Route::put('/profil', [MahasiswaController::class, 'updateProfil'])->name('profil.update');
        Route::put('/profil/password', [MahasiswaController::class, 'updatePassword'])->name('profil.password');

        Route::get('/api/paket-semester', [MahasiswaController::class, 'getPaketSemester'])->name('api.paket-semester');

        Route::get('/khs/pdf', [MhsPdfController::class, 'exportKhsPdf'])->name('khs.pdf');
    });

    // DOSEN WALI
    Route::prefix('dosen-wali')->name('pages.dosen_wali.')->group(function () {
        Route::get('/beranda', [DosenWaliController::class, 'index'])->name('beranda');
        Route::get('/krs-verifikasi', [KrsVerifikasiController::class, 'index'])->name('krs.verifikasi');
        Route::patch('/krs/approve/{nim}', [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
        Route::delete('/krs/reject/{nim}', [KrsVerifikasiController::class, 'reject'])->name('krs.reject');
        Route::get('/khs', [DosenWaliController::class, 'khs'])->name('khs');
        Route::get('/profil', [ProfilDosenWaliController::class, 'index'])->name('profil');
        Route::put('/profil', [ProfilDosenWaliController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('profil.password');
    });

    // DOSEN MATA KULIAH
    Route::prefix('dosen-matkul')->name('pages.dosen_matkul.')->group(function () {
        Route::get('/beranda', [DosenMKController::class, 'index'])->name('beranda');
        Route::get('/input-nilai', [DosenMKController::class, 'inputNilai'])->name('input-nilai');
        Route::post('/simpan-nilai', [DosenMKController::class, 'simpanNilai'])->name('simpan-nilai');
        Route::post('/finalisasi-nilai', [DosenMKController::class, 'finalisasiNilai'])->name('finalisasi-nilai');
        Route::get('/lihat-nilai', [DosenMKController::class, 'lihatNilai'])->name('lihat-nilai');
        Route::get('/profil', [ProfilDosenWaliController::class, 'index'])->name('profil');
        Route::put('/profil', [ProfilDosenWaliController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('profil.password');
    });

    // ROUTE LAMA DOSEN WALI
    // ═══════════════════════════════════════════════════════
    //  DOSEN WALI
    // ═══════════════════════════════════════════════════════
    Route::prefix('dosen/wali')->middleware('check.role:dosen_wali')->name('dosen.wali.')->group(function () {
        Route::get('/beranda', [DosenWaliController::class,     'index'])->name('beranda');
        Route::get('/khs', [DosenWaliController::class,     'khs'])->name('khs');
        Route::get('/krs-verifikasi', [KrsVerifikasiController::class, 'index'])->name('krs-verifikasi');
        Route::get('/krs/{krsId}/detail', [KrsVerifikasiController::class, 'detail'])->name('krs.detail');
        Route::patch('/krs/{krsId}/approve', [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
        Route::post('/krs/{krsId}/reject', [KrsVerifikasiController::class, 'reject'])->name('krs.reject');
        Route::get('/profil', [ProfilDosenWaliController::class, 'index'])->name('profil');
        Route::put('/profil', [ProfilDosenWaliController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('profil.password');
    });

    // ═══════════════════════════════════════════════════════
    //  DOSEN MATA KULIAH
    // ═══════════════════════════════════════════════════════
    Route::prefix('dosen/mk')->middleware('check.role:dosen_mk')->name('dosen.mk.')->group(function () {
        Route::get('/beranda', [DosenMKController::class, 'index'])->name('beranda');
        Route::get('/input-nilai', [DosenMKController::class, 'inputNilai'])->name('input-nilai');
        Route::post('/simpan-nilai', [DosenMKController::class, 'simpanNilai'])->name('simpan-nilai');
        Route::post('/finalisasi-nilai', [DosenMKController::class, 'finalisasiNilai'])->name('finalisasi-nilai');
        Route::get('/lihat-nilai', [DosenMKController::class, 'lihatNilai'])->name('lihat-nilai');
        Route::get('/kelas-by-mk', [DosenMKController::class, 'getKelasByMK'])->name('kelas-by-mk');
        Route::get('/profil', [DosenMKController::class, 'profil'])->name('profil');
        Route::put('/profil', [DosenMKController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [DosenMKController::class, 'updatePassword'])->name('profil.password');
    });

    // Shared profil route (fallback)
    Route::get('/dosen/profil', [ProfilDosenWaliController::class, 'index'])->name('dosen.profil');
    Route::put('/dosen/profil', [ProfilDosenWaliController::class, 'update'])->name('dosen.profil.update');
    Route::put('/dosen/profil/password', [ProfilDosenWaliController::class, 'updatePassword'])->name('dosen.profil.password');
});
