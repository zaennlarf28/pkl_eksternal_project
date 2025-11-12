<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ModelProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ModelSelectController;

// ========================
// 🏠 Halaman Utama
// ========================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ========================
// 👤 ROUTE USER BIASA
// ========================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Profil
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman pilih model
    Route::get('/pilih-model', [ModelSelectController::class, 'index'])->name('user.chooseModel');

    // Editor (baik edit design lama atau buat baru)
    Route::get('/editor/{id?}', [DesignController::class, 'editor'])->name('editor');
    Route::post('/design/save', [DesignController::class, 'save'])->name('design.save');
    Route::post('/design/upload', [DesignController::class, 'upload'])->name('design.upload');

    // Preview 3D
    Route::get('/design/{design}/preview-3d/{model}', [DesignController::class, 'preview3D'])->name('design.preview3d');

    // Hapus design
    Route::delete('/designs/{design}', [DashboardController::class, 'destroy'])->name('designs.destroy');
});

// ========================
// 🛠️ ROUTE ADMIN
// ========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('models', ModelProductController::class);
});

// ========================
// 🔐 Auth bawaan Laravel
// ========================
require __DIR__ . '/auth.php';
