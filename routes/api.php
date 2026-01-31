<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\FavoriteController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/properties', [PropertyController::class, 'store']);
    Route::post('/properties/{id}/images', [PropertyController::class, 'uploadImage']);
    Route::put('/properties/{id}', [PropertyController::class, 'update']);
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);
    Route::get('/me/properties', function(Request $request) {
        return app(PropertyController::class)->index($request->merge(['user_id' => $request->user()->id]));
    });

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{id}', [FavoriteController::class, 'toggle']);
});
