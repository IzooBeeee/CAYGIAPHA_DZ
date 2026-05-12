<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\TreeController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\MarriageController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('families', FamilyController::class);
    Route::get('/families/{familyId}/marriages', [FamilyController::class, 'marriages']);

    Route::get('/families/{familyId}/people', [PersonController::class, 'index']);
    Route::post('/families/{familyId}/people', [PersonController::class, 'store']);

    Route::get('/people/{id}', [PersonController::class, 'show']);
    Route::put('/people/{id}', [PersonController::class, 'update']);
    Route::delete('/people/{id}', [PersonController::class, 'destroy']);

    Route::post('/families/{familyId}/marriages', [MarriageController::class, 'store']);
    Route::delete('/marriages/{id}', [MarriageController::class, 'destroy']);

    Route::get('/families/{familyId}/tree', [TreeController::class, 'show']);

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::patch('/users/{id}', [AdminUserController::class, 'update']);
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
    });
});
