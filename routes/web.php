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

    // Super Admin Kriteria dan Bobot
    Route::resource('kriteria', KriteriaController::class);

    // Super Admin Subkriteria
    Route::resource('subkriteria', SubkriteriaController::class);

    // Super Admin Perhitungan SMART
    Route::get('/perhitungan-smart', [PerhitunganSmartController::class, 'index'])->name('perhitungan-smart.index');
    Route::post('/perhitungan-smart/hitung', [PerhitunganSmartController::class, 'hitung'])->name('perhitungan-smart.hitung');

    // Super Admin Hasil Seleksi
    Route::get('/hasil-seleksi', [HasilSeleksiController::class, 'index'])->name('hasil-seleksi.index');

    // Super Admin Manajemen Akun Admin
    Route::resource('manajemen_admin', AdminController::class)->except(['create', 'edit', 'update', 'show']);
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
});
