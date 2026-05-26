<?php

use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenWaliController;
use App\Http\Controllers\KrsVerifikasiController;
use App\Http\Controllers\KhsMahasiswaController;
use App\Http\Controllers\ProfilDosenWaliController;
use App\Http\Controllers\DosenMKController;
use App\Http\Controllers\MhsPdfController;
use Illuminate\Support\Facades\Route;

// PUBLIC
Route::get('/', fn() => view('landing'))->name('landing');
Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// PROTECTED
Route::middleware('check.simple.auth')->group(function () {

    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');

    // ADMIN
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardAdmin'])->name('pages.admin.dashboard');

    Route::prefix('admin/mahasiswa')->name('pages.admin.mahasiswa.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMahasiswa'])->name('index');
        Route::get('/create', [AdminController::class, 'createMahasiswa'])->name('create');
        Route::post('/', [AdminController::class, 'storeMahasiswa'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMahasiswa'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMahasiswa'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMahasiswa'])->name('destroy');
    });

    Route::prefix('admin/dosen')->name('pages.admin.dosen.')->group(function () {
        Route::get('/', [AdminController::class, 'indexDosen'])->name('index');
        Route::get('/create', [AdminController::class, 'createDosen'])->name('create');
        Route::post('/', [AdminController::class, 'storeDosen'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editDosen'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateDosen'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyDosen'])->name('destroy');
    });

    Route::prefix('admin/matakuliah')->name('pages.admin.matakuliah.')->group(function () {
        Route::get('/', [AdminController::class, 'indexMatakuliah'])->name('index');
        Route::get('/create', [AdminController::class, 'createMatakuliah'])->name('create');
        Route::post('/', [AdminController::class, 'storeMatakuliah'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editMatakuliah'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateMatakuliah'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyMatakuliah'])->name('destroy');
    });

    Route::prefix('admin/tahun-ajaran')->name('pages.admin.tahunajaran.')->group(function () {
        Route::get('/', [AdminController::class, 'indexTahunAjaran'])->name('index');
        Route::get('/create', [AdminController::class, 'createTahunAjaran'])->name('create');
        Route::post('/', [AdminController::class, 'storeTahunAjaran'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editTahunAjaran'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateTahunAjaran'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyTahunAjaran'])->name('destroy');
    });

    Route::prefix('admin/paket-mk')->name('pages.admin.paketmk.')->group(function () {
        Route::get('/', [AdminController::class, 'indexPaketMK'])->name('index');
        Route::get('/create', [AdminController::class, 'createPaketMK'])->name('create');
        Route::post('/', [AdminController::class, 'storePaketMK'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editPaketMK'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updatePaketMK'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyPaketMK'])->name('destroy');
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
        Route::get('/beranda',                   [DosenWaliController::class,     'index'])->name('beranda');
        Route::get('/khs',                       [DosenWaliController::class,     'khs'])->name('khs');
        Route::get('/krs-verifikasi',            [KrsVerifikasiController::class, 'index'])->name('krs-verifikasi');
        Route::get('/krs/{krsId}/detail',        [KrsVerifikasiController::class, 'detail'])->name('krs.detail');
        Route::patch('/krs/{krsId}/approve',     [KrsVerifikasiController::class, 'approve'])->name('krs.approve');
        Route::post('/krs/{krsId}/reject',       [KrsVerifikasiController::class, 'reject'])->name('krs.reject');
        Route::get('/profil',                    [ProfilDosenWaliController::class,'index'])->name('profil');
        Route::put('/profil',                    [ProfilDosenWaliController::class,'update'])->name('profil.update');
        Route::put('/profil/password',           [ProfilDosenWaliController::class,'updatePassword'])->name('profil.password');
    });

    // ═══════════════════════════════════════════════════════
    //  DOSEN MATA KULIAH
    // ═══════════════════════════════════════════════════════
    Route::prefix('dosen/mk')->middleware('check.role:dosen_mk')->name('dosen.mk.')->group(function () {
        Route::get('/beranda',         [DosenMKController::class, 'index'])->name('beranda');
        Route::get('/input-nilai',     [DosenMKController::class, 'inputNilai'])->name('input-nilai');
        Route::post('/simpan-nilai',   [DosenMKController::class, 'simpanNilai'])->name('simpan-nilai');
        Route::get('/lihat-nilai',     [DosenMKController::class, 'lihatNilai'])->name('lihat-nilai');
        Route::get('/kelas-by-mk',     [DosenMKController::class, 'getKelasByMK'])->name('kelas-by-mk');
        Route::get('/profil',          [DosenMKController::class, 'profil'])->name('profil');
        Route::put('/profil',          [DosenMKController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [DosenMKController::class, 'updatePassword'])->name('profil.password');
    });

    // Shared profil route (fallback)
    Route::get('/dosen/profil',          [ProfilDosenWaliController::class,'index'])->name('dosen.profil');
    Route::put('/dosen/profil',          [ProfilDosenWaliController::class,'update'])->name('dosen.profil.update');
    Route::put('/dosen/profil/password', [ProfilDosenWaliController::class,'updatePassword'])->name('dosen.profil.password');
});
