<?php
require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\ActiviteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    Route::get('/seances', [SeanceController::class, 'index'])->name('seances.index');
    Route::get('/seances/coach', [SeanceController::class, 'coach'])->name('seances.coach');
    Route::get('/seances/mines', [SeanceController::class, 'mines'])->name('seances.mines');
    Route::get('/seances/create', [SeanceController::class, 'create'])->name('seances.create');
    Route::get('/seances/edit/{id}', [SeanceController::class, 'edit'])->name('seances.edit');
    
    Route::post('/seances/done', [SeanceController::class, 'done'])->name('seances.done');
    Route::post('/seances/create', [SeanceController::class, 'store'])->name('seances.store');
    Route::put('/seances/update/{id}', [SeanceController::class, 'update'])->name('seances.update');
    Route::delete('/seances/destroy/{id}', [SeanceController::class, 'destroy'])->name('seances.destroy');
    
    Route::get('/activites', [ActiviteController::class, 'index'])->name('activites.index');
    Route::get('/activites/create', [ActiviteController::class, 'create'])->name('activites.create');
    Route::get('/activites/mines', [ActiviteController::class, 'mines'])->name('activites.mines');
    Route::get('/activites/public', [ActiviteController::class, 'public'])->name('activites.public');

    Route::post('/activites/favorite', [ActiviteController::class, 'favorite'])->name('activites.favorite');
    Route::post('/activites/create', [ActiviteController::class, 'store'])->name('activites.store');
    Route::get('/activites/edit/{id}', [ActiviteController::class, 'edit'])->name('activites.edit');
    Route::put('/activites/update/{id}', [ActiviteController::class, 'update'])->name('activites.update');
    Route::delete('/activites/destroy/{id}', [ActiviteController::class, 'destroy'])->name('activites.destroy');
});

