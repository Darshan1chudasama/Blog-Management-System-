<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
    Route::post('/blogs/{id}/toggle-like', [BlogController::class, 'toggleLike']);

    Route::get('/get-blogs', [BlogController::class, 'getBlog']);

    Route::get('/get-blogs/{id}', [BlogController::class, 'show']);
});
