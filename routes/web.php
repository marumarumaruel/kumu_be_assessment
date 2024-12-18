<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GithubController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/maru', function () {
//     Route::get('/profile', [GithubController::class, 'getUser'])->name('github.get');
// })->middleware(['auth', 'verified'])->name('maru');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/github_users', [GithubController::class, 'getUsers'])->name('github.getusers');
    // Route::get('/github_users', [GithubController::class, 'getUsers'])->name('github.getusers');
});

require __DIR__.'/auth.php';
