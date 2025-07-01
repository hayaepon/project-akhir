<?php

// Import semua class dan controller yang diperlukan
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\Admin\SmartCalculationController;
use App\Http\Controllers\Superadmin\CalonPenerimaController;
use App\Http\Controllers\Superadmin\KriteriaController;
use App\Http\Controllers\Superadmin\SubkriteriaController;
use App\Http\Controllers\Superadmin\PerhitunganSmartController;
use App\Http\Controllers\Superadmin\HasilSeleksiController;
use App\Http\Controllers\Superadmin\AdminController;
use App\Http\Controllers\Admin\CalonPenerimaAdminController;
use App\Http\Controllers\Admin\KriteriaAdminController;
use App\Http\Controllers\Admin\SubKriteriaAdminController;
use App\Http\Controllers\Admin\HasilSeleksiAdminController;

// Route utama untuk halaman login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Super Admin Routes (Prefix 'superadmin' and middleware 'auth')
Route::prefix('superadmin')->middleware('auth')->group(function () {

    // Super Admin Dashboard
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');

    // Super Admin Calon Penerima
    Route::resource('calon-penerima', CalonPenerimaController::class);
    Route::post('/calon-penerima/import', [CalonPenerimaController::class, 'import'])->name('calon-penerima.import');

    // Super Admin Kriteria dan Bobot
    Route::resource('kriteria', KriteriaController::class);

    // Super Admin Subkriteria
    Route::resource('subkriteria', SubkriteriaController::class);
    Route::get('/superadmin/subkriteria/get-kriteria/{id}', [SubkriteriaController::class, 'getKriteriaByBeasiswa']);


    // Super Admin Perhitungan SMART
    Route::get('/perhitungan-smart', [PerhitunganSmartController::class, 'index'])->name('perhitungan-smart.index');

    // Super Admin Hasil Seleksi
    Route::get('/hasil-seleksi', [HasilSeleksiController::class, 'index'])->name('hasil-seleksi.index');
    Route::get('/hasil-seleksi/hitung', [HasilSeleksiController::class, 'hitung'])->name('hasil_seleksi.hitung');
    Route::get('/hasil-seleksi/export', [HasilSeleksiController::class, 'export'])->name('hasil-seleksi.export');

    // Super Admin Manajemen Akun Admin
    Route::resource('manajemen_admin', AdminController::class)->except(['show']);

});

// Admin Routes (middleware 'auth')
Route::middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Calon Penerima
    Route::get('admin/calon-penerima', [CalonPenerimaAdminController::class, 'index'])->name('admin.calon_penerima.index');

    // Admin Kriteria dan Subkriteria
    Route::get('admin/kriteria', [KriteriaAdminController::class, 'index'])->name('admin.kriteria.index');
    Route::get('admin/subkriteria', [SubKriteriaAdminController::class, 'index'])->name('admin.subkriteria.index');

    // Admin Hasil Seleksi
    Route::get('admin/Hasil_Seleksi', [HasilSeleksiAdminController::class, 'index'])->name('admin.Hasil_Seleksi.index');

    // Admin Perhitungan SMART
    Route::get('/admin/perhitungan_smart', [SmartCalculationController::class, 'index'])->name('admin.perhitungan_smart.index');
    Route::get('/perhitungan-smart/{jenis?}', [SmartCalculationController::class, 'index'])->name('admin.perhitungan_smart.index');
    Route::post('/admin/perhitungan_smart', [SmartCalculationController::class, 'store'])->name('admin.perhitungan_smart.store');
    Route::delete('/admin/perhitungan_smart/{id}', [SmartCalculationController::class, 'destroy'])->name('admin.perhitungan_smart.destroy');
    Route::get('/admin/perhitungan-smart/kriteria/{id}', [SmartCalculationController::class, 'getKriteriaByBeasiswa']);
    Route::get('/admin/perhitungan-smart/kriteria/{jenis_beasiswa_id}', [SmartCalculationController::class, 'getKriteriaByBeasiswa'])->name('admin.perhitungan_smart.kriteria');
    Route::get('/perhitungan_smart/{id}/edit', [SmartCalculationController::class, 'edit'])->name('admin.perhitungan_smart.edit');
    Route::put('/perhitungan_smart/{id}', [SmartCalculationController::class, 'update'])->name('admin.perhitungan_smart.update');
    Route::get('/perhitungan-smart/kriteria/{beasiswa}', [SmartCalculationController::class, 'getKriteriaByBeasiswa'])
    ->name('admin.perhitungan_smart.getKriteriaByBeasiswa');
});

use App\Http\Controllers\SuperAdmin\BeasiswaController;

Route::get('/superadmin/calon_penerima', [BeasiswaController::class, 'create'])->name('superadmin.calon.index');

Route::get('/get-kriteria/{beasiswa_id}', [SubkriteriaController::class, 'getKriteriaByBeasiswa']);

