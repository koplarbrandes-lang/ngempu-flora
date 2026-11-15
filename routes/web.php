<?php

use App\Http\Controllers\Admin\AdminAdopterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Adopter\AdopterDashboardController;
use App\Http\Controllers\Adopter\TreeTrackerController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Direct static storage file serving (guarantees image display regardless of symlink or Windows webserver config)
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    $mime = mime_content_type($filePath) ?: 'image/jpeg';
    return response(file_get_contents($filePath), 200, [
        'Content-Type' => $mime,
        'Content-Length' => filesize($filePath),
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*');

// Official Public Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Public Certificate Verification (Scan barcode / QR on certificate)
Route::get('/verify/certificate/{codeAndToken?}', [TreeTrackerController::class, 'verifyCertificate'])->name('certificate.verify');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Tree Monitoring & Tracker (Requires Login)
Route::middleware('auth')->group(function () {
    Route::get('/trees/track', [TreeTrackerController::class, 'show'])->name('tracker.index');
    Route::get('/tree/{codeAndToken}', [TreeTrackerController::class, 'show'])->name('tracker.show');
});

// Adopter Dashboard (Protected for logged in adopters)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [AdopterDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/certificate/{tree}', [AdopterDashboardController::class, 'certificate'])->name('certificate');
});

// Admin Panel Routes (Protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // Tree Management
    Route::get('/trees', [AdminDashboardController::class, 'trees'])->name('trees');
    Route::get('/trees/create', [AdminDashboardController::class, 'createTree'])->name('trees.create');
    Route::post('/trees', [AdminDashboardController::class, 'storeTree'])->name('trees.store');
    Route::post('/trees/bulk-destroy', [AdminDashboardController::class, 'bulkDestroyTrees'])->name('trees.bulk_destroy');
    Route::get('/trees/{tree}/edit', [AdminDashboardController::class, 'editTree'])->name('trees.edit');
    Route::put('/trees/{tree}', [AdminDashboardController::class, 'updateTree'])->name('trees.update');
    Route::delete('/trees/{tree}', [AdminDashboardController::class, 'destroyTree'])->name('trees.destroy');
    Route::post('/trees/{tree}/adjust-weeks', [AdminDashboardController::class, 'adjustWeeks'])->name('trees.adjust_weeks');

    // Adopter Management
    Route::get('/adopters', [AdminAdopterController::class, 'index'])->name('adopters.index');
    Route::get('/adopters/create', [AdminAdopterController::class, 'create'])->name('adopters.create');
    Route::post('/adopters', [AdminAdopterController::class, 'store'])->name('adopters.store');
    Route::post('/adopters/bulk-destroy', [AdminAdopterController::class, 'bulkDestroy'])->name('adopters.bulk_destroy');
    Route::get('/adopters/{adopter}/edit', [AdminAdopterController::class, 'edit'])->name('adopters.edit');
    Route::put('/adopters/{adopter}', [AdminAdopterController::class, 'update'])->name('adopters.update');
    Route::delete('/adopters/{adopter}', [AdminAdopterController::class, 'destroy'])->name('adopters.destroy');

    // Weekly Updates
    Route::get('/update', [AdminDashboardController::class, 'updateForm'])->name('update');
    Route::post('/update', [AdminDashboardController::class, 'storeUpdate'])->name('update.store');
    Route::post('/api/preview-carbon', [AdminDashboardController::class, 'previewCarbon'])->name('preview_carbon');

    // QR Codes & Certificates
    Route::get('/qr-codes', [AdminDashboardController::class, 'qrCodes'])->name('qr_codes');
    Route::get('/certificates', [AdminDashboardController::class, 'certificates'])->name('certificates');
});
