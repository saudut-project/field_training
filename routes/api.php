<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\JobVacancyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/cry', function () {
    return bcrypt('password');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('admins', AdminController::class);
    Route::apiResource('deans', DeanController::class);
    Route::apiResource('representatives', RepresentativeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('institutions', InstitutionController::class);
    Route::apiResource('job-vacancies', JobVacancyController::class);
});
