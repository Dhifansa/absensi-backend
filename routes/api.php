<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\PresensiController;

// ─── Auth (tanpa middleware) ──────────────────────────────────────────────────
Route::post('/auth/peserta/login',  [AuthController::class, 'loginPeserta']);
Route::post('/auth/admin/login',    [AuthController::class, 'loginAdmin']);
Route::post('/auth/logout',         [AuthController::class, 'logout']);

// ─── QR (admin only) ─────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/qr/generate',     [QrController::class, 'generate']);
    Route::get('/qr/active',        [QrController::class, 'active']);
    Route::get('/presensi',         [PresensiController::class, 'index']);
    Route::get('/presensi/export',  [PresensiController::class, 'export']);
});

// ─── Scan QR (peserta, tanpa sanctum) ────────────────────────────────────────
Route::post('/presensi/scan', [PresensiController::class, 'scan']);
