<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// User Registration API Route
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Post API Routes
Route::apiResource('posts', PostController::class);

//Task API Routes
Route::apiResource('tasks', TaskController::class)->only(['store', 'update', 'show', 'destroy']);
Route::get('/pending-list', [TaskController::class, 'pendingList']);
