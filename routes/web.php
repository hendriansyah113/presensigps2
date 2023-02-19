<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;

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

Route::middleware(['auth:user'])->group(function(){
    Route::get('/dashboardadmin', [DashboardController::class, 'dashboardadmin']);
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

    // Data Karyawan
    Route::get('/profile', [KaryawanController::class, 'editmobile']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
});

Route::middleware(['auth:user'])->group(function() {
    Route::get('/monitoring', [PresensiController::class, 'index']);
    Route::post('/monitoring/show', [PresensiController::class, 'show']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/laporan/cetak', [PresensiController::class, 'cetak']);
});