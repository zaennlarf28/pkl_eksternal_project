<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Editor
    Route::get('/editor/{id?}', [DesignController::class, 'editor'])->name('editor');
    Route::post('/design/save', [DesignController::class, 'save'])->name('design.save');
    Route::post('/design/upload', [DesignController::class, 'upload'])->name('design.upload');

    // Pilih model & preview 3D
    Route::get('/design/{id}/choose-model', [DesignController::class, 'chooseModel'])->name('design.chooseModel');
    Route::get('/design/{design}/preview-3d/{model}', [DesignController::class, 'preview3D'])->name('design.preview3d');

    // Delete
    Route::delete('/designs/{design}', [DashboardController::class, 'destroy'])->name('designs.destroy');
});

require __DIR__ . '/auth.php';
