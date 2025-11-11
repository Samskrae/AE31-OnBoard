<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\CsvController;

Route::get('/', function () {
    return view('index'); // o 'welcome' si así se llama tu vista
})->name('home');

Route::get('/spots/create', [SpotController::class, 'create'])->name('spots.create');
Route::post('/spots', [SpotController::class, 'store'])->name('spots.store');
Route::get('/spots', [SpotController::class, 'index'])->name('spots.index');


Route::get('/registro', function () {
    return view('registro');
})->name('registro');

Route::post('/guardar-csv', [CsvController::class, 'guardar'])->name('guardar.csv');
