<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index')
    ->middleware(['auth', 'verified']);
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')
    ->middleware(['auth', 'verified']);
Route::post('/products', [ProductController::class, 'store'])->name('products.store')
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
