<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChucVuController;
use App\Http\Controllers\Api\ChucNangController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ThanhVienController;
use App\Http\Controllers\Api\ChiNhanhController;
use App\Http\Controllers\Api\VoChongController;
use App\Http\Controllers\Api\ConNuoiController;
use App\Http\Controllers\Api\DoiTocHoController;
use App\Http\Controllers\Api\DongGopController;
use App\Http\Controllers\Api\HinhAnhController;
use App\Http\Controllers\Api\MoPhanController;
use App\Http\Controllers\Api\NguoiDungController;
use App\Http\Controllers\Api\NhaThoHoController;
use App\Http\Controllers\Api\NhatKyHoatDongController;
use App\Http\Controllers\Api\SuKienController;
use App\Http\Controllers\Api\TaiLieuController;
use App\Http\Controllers\Api\ThamGiaSuKienController;
use App\Http\Controllers\Api\ThongBaoController;

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Resources Routes
Route::prefix('/chuc-vu')->group(function () {
    Route::get('/get-data', [ChucVuController::class, 'getData']);
    Route::post('/create', [ChucVuController::class, 'create']);
    Route::post('/update', [ChucVuController::class, 'update']);
    Route::post('/delete', [ChucVuController::class, 'delete']);
    Route::post('/status', [ChucVuController::class, 'status']);
    Route::post('/search', [ChucVuController::class, 'search']);
});

Route::prefix('/chuc-nang')->group(function () {
    Route::get('/get-data', [ChucNangController::class, 'getData']);
    Route::post('/create', [ChucNangController::class, 'create']);
    Route::post('/update', [ChucNangController::class, 'update']);
    Route::post('/delete', [ChucNangController::class, 'delete']);
    Route::post('/status', [ChucNangController::class, 'status']);
});

// Thêm các prefix route cho 15 models khác tương tự...
Route::prefix('/thanh-vien')->group(function () {
    Route::get('/get-data', [ThanhVienController::class, 'getData']);
    Route::post('/create', [ThanhVienController::class, 'create']);
    Route::post('/update', [ThanhVienController::class, 'update']);
    Route::post('/delete', [ThanhVienController::class, 'delete']);
    Route::post('/status', [ThanhVienController::class, 'status']);
});

Route::prefix('/nguoi-dung')->group(function () {
    Route::get('/get-data', [NguoiDungController::class, 'getData']);
    Route::post('/create', [NguoiDungController::class, 'create']);
    Route::post('/update', [NguoiDungController::class, 'update']);
    Route::post('/delete', [NguoiDungController::class, 'delete']);
    Route::post('/status', [NguoiDungController::class, 'status']);
});
// (Các route khác tương tự cho các controller còn lại)
