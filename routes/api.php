<?php

use App\Http\Controllers\Api\AboutMeController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
Route::put('update', [AuthController::class, 'update'])->middleware('auth:api')->name('update');
Route::middleware('auth:api')->get('me', [AuthController::class, 'me'])->name('me');

Route::apiResource('contact', ContactController::class);
Route::apiResource('project', ProjectController::class);
Route::apiResource('about-me', AboutMeController::class);
Route::apiResource('skills', SkillController::class);

Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    // Admin routes
    Route::apiResource('users', UserController::class);
    Route::apiResource('contact', ContactController::class);
    Route::apiResource('project', ProjectController::class);
Route::apiResource('about-me', AboutMeController::class);
    Route::apiResource('skills', SkillController::class);
});
