<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonnelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'permission:manage_personnel_accounts'])->name('dashboard');


Route::middleware(['auth', 'permission:manage_personnel_accounts'])
    ->group(function () {

        Route::resource('personnel', PersonnelController::class);

    });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';