<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\ParameterController;
use Illuminate\Support\Facades\Route;

// Authentication Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected App Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kecamatan/{kecamatan}', [DashboardController::class, 'kecamatan'])->name('kecamatan.detail');

    // Input Checklist
    Route::get('/input/progress', [InputController::class, 'progress'])->name('input.progress');
    Route::get('/input/{kuesioner}/{kecamatan}', [InputController::class, 'show'])->name('input.show');
    Route::post('/input', [InputController::class, 'store'])->name('input.store');

    // Parameter & Panduan
    Route::get('/parameter', [ParameterController::class, 'index'])->name('parameter.index');
    Route::put('/parameter', [ParameterController::class, 'update'])->name('parameter.update');
    Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan.index');

    // Admin Control (Khusus Administrator)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
    });
});
