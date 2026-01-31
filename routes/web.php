<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/permission/{id}/approve', [AdminController::class, 'approveRequest'])->name('admin.approve');
        Route::post('/permission/{id}/reject', [AdminController::class, 'rejectRequest'])->name('admin.reject');

        Route::resource('videos', AdminController::class);
        Route::resource('users', AdminUserController::class);
    });

    Route::middleware(['role:customer'])->prefix('customer')->group(function () {
        Route::get('/videos', [CustomerController::class, 'index'])->name('customer.videos');
        Route::post('/request/{id}', [CustomerController::class, 'requestAccess'])->name('customer.request');
        Route::get('/watch/{id}', [CustomerController::class, 'watchVideo'])->name('customer.watch');
    });
});

require __DIR__ . '/auth.php';
