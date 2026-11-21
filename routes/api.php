<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GedungController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::post('/users', [AdminController::class, 'storeUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

    Route::apiResource('gedungs', BuildingController::class);
    Route::get('/logs', [LogController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:pegawai'])->group(function () {
    Route::get('/rekap-absensi', [EmployeeController::class, 'rekapAbsensi']);
    Route::get('/rekap-absensi/export', [EmployeeController::class, 'exportRekap']);
});

Route::middleware(['auth:sanctum', 'role:siswa'])->group(function () {
    Route::post('/absen/masuk', [StudentController::class, 'absenMasuk']);
    Route::post('/absen/keluar', [StudentController::class, 'absenKeluar']);
    Route::get('/riwayat', [StudentController::class, 'getRiwayat']);
});
