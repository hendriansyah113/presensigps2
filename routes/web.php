<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:karyawan'])->group(function() {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function() {
    Route::get('/panel', function(){
        return view('auth.loginadmin');
    });
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:karyawan,user'])->group(function(){
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);
});

Route::middleware(['auth:karyawan'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Data Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/presensi/gethistori', [PresensiController::class, 'gethistori']);
    Route::get('/presensi/izin', [PresensiController::class, 'show_izin']);
    Route::post('/presensi/store_izin', [PresensiController::class, 'store_izin']);
    Route::get('/presensi/create_izin', [PresensiController::class, 'create_izin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
    
    // Data Karyawan
    Route::get('/profile', [KaryawanController::class, 'editmobile']);
    Route::post('/karyawan/{nip}/update', [KaryawanController::class, 'update']);
});

Route::middleware(['auth:user'])->group(function() {
    // Presensi
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin']);
    Route::get('/monitoring', [PresensiController::class, 'index']);
    Route::post('/loadmap', [PresensiController::class, 'loadmap']);
    Route::post('/monitoring/show', [PresensiController::class, 'show']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);

    Route::get('/lokasi/create', [LokasiController::class, 'create']);
    Route::post('/lokasi/store', [LokasiController::class, 'store']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);

    // karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nip}/updatepanel', [KaryawanController::class, 'updatepanel']);
    Route::post('/karyawan/{nip}/delete', [KaryawanController::class, 'delete']);

    // Departemen
    Route::get('/departemen', [DepartemenController::class, 'index']);
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    // Konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
});

// ubah password
// Tampilkan halaman lupa password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');

// Kirim email reset password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Tampilkan halaman reset password dengan token
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Proses reset password
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');