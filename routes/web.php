<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\ParameterController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/kecamatan/{kecamatan}', [DashboardController::class, 'kecamatan'])->name('kecamatan.detail');

// Input
Route::get('/input/progress', [InputController::class, 'progress'])->name('input.progress');
Route::get('/input/{kuesioner}/{kecamatan}', [InputController::class, 'show'])->name('input.show');
Route::post('/input', [InputController::class, 'store'])->name('input.store');

// Parameter
Route::get('/parameter', [ParameterController::class, 'index'])->name('parameter.index');
Route::put('/parameter', [ParameterController::class, 'update'])->name('parameter.update');

// Panduan
Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan.index');
