<?php
use App\Http\Controllers\Auth\SimpleLoginController;

// Landing Page (Homepage) - Public
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes - Public
Route::get('/login', [SimpleLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SimpleLoginController::class, 'login']);

// Protected Routes - Require Auth
Route::middleware('check.simple.auth')->group(function () {
    Route::post('/logout', [SimpleLoginController::class, 'logout'])->name('logout');
    Route::view('/admin/dashboard', 'admin.dashboard');
    Route::view('/mahasiswa/dashboard', 'mahasiswa.dashboard');
    Route::view('/dashboard/dosen-wali', 'dosen_wali.dashboard');
    Route::view('/dashboard/dosen-matkul', 'dosen_matkul.dashboard');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});