<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ModelProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ModelSelectController;
use App\Http\Controllers\DesignSubmissionController;
use App\Http\Controllers\Admin\DesignSubmissionController as AdminDesignCtrl;

// ========================
// 🏠 Halaman Utama
// ========================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ========================
// 👤 ROUTE USER (auth + verified)
// ========================
Route::middleware(['auth', 'verified'])->group(function () {

   // Semua model
    Route::get('/models', [ModelSelectController::class, 'index'])->name('models.index');

    // Pilih model
    Route::get('/models/{model}', [ModelSelectController::class, 'select'])->name('models.select');
    Route::get('/models/search', [App\Http\Controllers\ModelSelectController::class, 'search'])->name('models.search');

    // 📊 Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🎨 Editor Desain
    Route::get('/editor/{id?}', [DesignController::class, 'editor'])->name('editor');
    Route::post('/design/save', [DesignController::class, 'save'])->name('design.save');
    Route::post('/design/upload', [DesignController::class, 'upload'])->name('design.upload');

    // Kirim desain
    Route::post('/designs/send-to-admin', [DesignSubmissionController::class, 'store'])->name('designs.send_to_admin');

    // 🧱 Preview 3D
    Route::get('/design/{design}/preview-3d/{model}', [DesignController::class, 'preview3D'])
        ->name('design.preview3d');

    // 🗑️ Hapus design
    Route::delete('/designs/{design}', [DashboardController::class, 'destroy'])->name('designs.destroy');
});

// ========================
// 🛠️ ROUTE ADMIN (prefix + middleware admin)
// ========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('models', ModelProductController::class);
     Route::get('/design-submissions', [AdminDesignCtrl::class, 'index'])->name('design_submissions.index');
    Route::get('/design-submissions/{id}', [AdminDesignCtrl::class, 'show'])->name('design_submissions.show');
    Route::post('/design-submissions/{id}/status', [AdminDesignCtrl::class, 'updateStatus'])->name('design_submissions.update_status');
    Route::delete('/design-submissions/{id}', [AdminDesignCtrl::class, 'destroy'])->name('design_submissions.destroy');

});

// ========================
// 🔐 Auth bawaan Laravel
// ========================
Route::get('/check', function () {
    return 'Laravel OK';
});
Route::get('/models-select', [App\Http\Controllers\ModelSelectController::class, 'index']);

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

require __DIR__ . '/auth.php';
