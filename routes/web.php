<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Dosen\AuthController::class, 'index'])->name('dosen.login');
Route::post('/login-dosen', [\App\Http\Controllers\Dosen\AuthController::class, 'login'])->name('dosen.login.submit');

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/setup-password', [\App\Http\Controllers\Dosen\PasswordController::class, 'setup'])->name('password.setup');
    Route::post('/setup-password', [\App\Http\Controllers\Dosen\PasswordController::class, 'update'])->name('password.update');
    Route::post('/nilai/{nilai_sidang_id}', [\App\Http\Controllers\Dosen\NilaiController::class, 'store'])->name('nilai.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/import', [\App\Http\Controllers\Admin\DashboardController::class, 'importJadwal'])->name('dashboard.import');
    
    Route::post('/dosen/{dosen}/reset-password', [\App\Http\Controllers\Admin\DosenController::class, 'resetPassword'])->name('dosen.reset-password');
    Route::get('/dosen/download-passwords', [\App\Http\Controllers\Admin\DosenController::class, 'downloadPasswords'])->name('dosen.download-passwords');
    
    Route::post('mahasiswa/import', [\App\Http\Controllers\Admin\MahasiswaController::class, 'import'])->name('mahasiswa.import');
    Route::resource('mahasiswa', \App\Http\Controllers\Admin\MahasiswaController::class);
    Route::resource('dosen', \App\Http\Controllers\Admin\DosenController::class);
    
    Route::get('/jadwal/rekap', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'rekap'])->name('jadwal.rekap');
    Route::get('/jadwal/laporan/download', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'downloadLaporan'])->name('jadwal.laporan');
    Route::get('/jadwal/pengumuman', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'pengumuman'])->name('jadwal.pengumuman');
    Route::get('/jadwal/{jadwal}/cetak-yudisium', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'cetakYudisium'])->name('jadwal.cetak-yudisium');
    
    Route::get('/jadwal/{jadwal}/nilai', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'editNilai'])->name('jadwal.nilai.edit');
    Route::post('/jadwal/{jadwal}/nilai', [\App\Http\Controllers\Admin\JadwalSidangController::class, 'updateNilai'])->name('jadwal.nilai.update');
    
    Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalSidangController::class);
});

// Route untuk Mahasiswa (Publik)
Route::get('/pengumuman-yudisium', [\App\Http\Controllers\Mahasiswa\PengumumanController::class, 'index'])->name('mahasiswa.pengumuman.index');
Route::post('/pengumuman-yudisium', [\App\Http\Controllers\Mahasiswa\PengumumanController::class, 'cari'])->name('mahasiswa.pengumuman.cari');
Route::get('/pengumuman-yudisium/download/{id}', [\App\Http\Controllers\Mahasiswa\PengumumanController::class, 'download'])->name('mahasiswa.pengumuman.download');

require __DIR__.'/auth.php';
