<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('dashboard');
    Route::post('/documents', [DocumentController::class, 'create'])->name('documents.create');
    Route::get('/documents/{uuid}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('/documents/{uuid}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{uuid}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{uuid}/versions', [DocumentController::class, 'versions'])->name('documents.versions');
    Route::post('/documents/{uuid}/versions', [DocumentController::class, 'saveVersion'])->name('documents.saveVersion');
    Route::post('/documents/{uuid}/versions/{version}/restore', [DocumentController::class, 'restoreVersion'])->name('documents.versions.restore');
    Route::post('/documents/{uuid}/save', [DocumentController::class, 'save']);
});

Route::post('/api/documents/{uuid}/snapshot', [DocumentController::class, 'saveSnapshot']);
