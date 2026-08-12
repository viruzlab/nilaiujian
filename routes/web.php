<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Dosen\AuthController::class, 'index'])->name('dosen.login');
Route::post('/login-dosen', [\App\Http\Controllers\Dosen\AuthController::class, 'login'])->name('dosen.login.submit');

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/nilai/{nilai_sidang_id}', [\App\Http\Controllers\Dosen\NilaiController::class, 'store'])->name('nilai.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/import', [\App\Http\Controllers\Admin\DashboardController::class, 'importJadwal'])->name('dashboard.import');
    
    Route::post('/dosen/generate-passwords', [\App\Http\Controllers\Admin\DosenController::class, 'generatePasswords'])->name('dosen.generate-passwords');
    Route::get('/dosen/download-passwords', [\App\Http\Controllers\Admin\DosenController::class, 'downloadPasswords'])->name('dosen.download-passwords');
    
    Route::resource('mahasiswa', \App\Http\Controllers\Admin\MahasiswaController::class);
    Route::resource('dosen', \App\Http\Controllers\Admin\DosenController::class);
    Route::get('/jadwal/laporan/download', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'downloadLaporan'])->name('jadwal.laporan');
    Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalSidangController::class);
});

require __DIR__.'/auth.php';
