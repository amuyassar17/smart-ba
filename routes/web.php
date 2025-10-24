<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/fasilitas', [HomeController::class, 'fasilitas'])->name('fasilitas');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Mahasiswa Routes (Protected)
Route::middleware('mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [MahasiswaController::class, 'profil'])->name('profil');
    Route::get('/riwayat', [MahasiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/lengkapi', [MahasiswaController::class, 'lengkapiRiwayat'])->name('riwayat.lengkapi');
    Route::post('/riwayat/simpan', [MahasiswaController::class, 'simpanRiwayat'])->name('riwayat.simpan');
    Route::post('/logbook', [MahasiswaController::class, 'storeLogbook'])->name('logbook.store');
    Route::post('/evaluasi-dosen', [MahasiswaController::class, 'storeEvaluasiDosen'])->name('evaluasi.store');
    Route::post('/dokumen', [MahasiswaController::class, 'uploadDokumen'])->name('dokumen.upload');
});

// Dosen Routes (Protected)
Route::middleware('dosen')->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('/mahasiswa/{nim}', [DosenController::class, 'detailMahasiswa'])->name('mahasiswa.detail');
    Route::post('/mahasiswa/{nim}/approve-krs', [DosenController::class, 'approveKRS'])->name('mahasiswa.approve-krs');
    Route::post('/mahasiswa/{nim}/reject-krs', [DosenController::class, 'rejectKRS'])->name('mahasiswa.reject-krs');
    Route::post('/mahasiswa/{nim}/toggle-status', [DosenController::class, 'toggleStatus'])->name('mahasiswa.toggle-status');
    Route::post('/mahasiswa/{nim}/logbook', [DosenController::class, 'storeLogbook'])->name('mahasiswa.logbook.store');
    Route::post('/mahasiswa/{nim}/nilai-bermasalah', [DosenController::class, 'storeNilaiBermasalah'])->name('mahasiswa.nilai-bermasalah.store');
    Route::post('/mahasiswa/{nim}/evaluasi-softskill', [DosenController::class, 'storeEvaluasiSoftskill'])->name('mahasiswa.evaluasi-softskill.store');
});
