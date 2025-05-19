<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');
});

//route super admin calon penerima
use App\Http\Controllers\Superadmin\CalonPenerimaController;

Route::middleware(['auth'])->group(function () {
    Route::resource('calon-penerima', CalonPenerimaController::class);
});

//route super admin kriteria dan bobot
Route::prefix('superadmin')->middleware('auth')->group(function () {
    Route::resource('kriteria', \App\Http\Controllers\Superadmin\KriteriaController::class);
});

//route super admin subkriteria
use App\Http\Controllers\Superadmin\SubkriteriaController;

Route::prefix('superadmin')->middleware('auth')->group(function () {
    Route::resource('subkriteria', SubkriteriaController::class);
});

//route super admin perhitungan smart
use App\Http\Controllers\Superadmin\PerhitunganSmartController;

Route::prefix('superadmin')->middleware(['auth'])->group(function () {
    Route::get('/perhitungan-smart', [PerhitunganSmartController::class, 'index'])->name('perhitungan-smart.index');
    Route::post('/perhitungan-smart/hitung', [PerhitunganSmartController::class, 'hitung'])->name('perhitungan-smart.hitung');
});

//route super admin hasil seleksi
use App\Http\Controllers\Superadmin\HasilSeleksiController;

Route::get('/hasil-seleksi', [HasilSeleksiController::class, 'index'])->name('hasil-seleksi.index');

//route super admin manajemen akun 
use App\Http\Controllers\Superadmin\AdminController;

Route::resource('manajemen_admin', AdminController::class)->except(['create', 'edit', 'update', 'show']);
Route::resource('manajemen_admin', AdminController::class)->except(['edit', 'update', 'show']);


//route admin calon penerima

use App\Http\Controllers\Admin\CalonPenerimaAdminController;

Route::middleware(['auth'])->group(function () {
    Route::get('admin/calon-penerima', [CalonPenerimaAdminController::class, 'index'])->name('admin.calon_penerima.index');
});

//route admin kriteria

use App\Http\Controllers\Admin\KriteriaAdminController;
Route::middleware(['auth'])->group(function () {
    Route::get('admin/kriteria', [KriteriaAdminController::class, 'index'])->name('admin.kriteria.index');
});

//route admin subkriteria

use App\Http\Controllers\Admin\SubKriteriaAdminController;
Route::middleware(['auth'])->group(function () {
    Route::get('admin/subkriteria', [SubKriteriaAdminController::class, 'index'])->name('admin.subkriteria.index');
});

//route admin Hasil Seleksi
use App\Http\Controllers\Admin\HasilSeleksiAdminController;

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('admin/Hasil_Seleksi', [HasilSeleksiAdminController::class, 'index'])->name('admin.Hasil_Seleksi.index');
    
});




