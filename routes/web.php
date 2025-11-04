<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;

Route::get('/', function () {
    return view('index'); // o 'welcome' si así se llama tu vista
})->name('home');

Route::get('/spots/create', [SpotController::class, 'create'])->name('spots.create');
Route::post('/spots', [SpotController::class, 'store'])->name('spots.store');
Route::get('/spots', [SpotController::class, 'index'])->name('spots.index');
