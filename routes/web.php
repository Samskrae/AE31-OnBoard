<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\CsvController;

/**
 * Rutas principales de la aplicación OnBoard
 */

// Página de inicio
Route::get('/', function () {
    return view('index');
})->name('home');

// Formulario para crear nuevo spot
Route::get('/spots/create', [SpotController::class, 'create'])->name('spots.create');

// Guardar nuevo spot (POST)
Route::post('/spots', [SpotController::class, 'store'])->name('spots.store');

// Listar todos los spots (Tabla + Mapa)
Route::get('/spots', [SpotController::class, 'index'])->name('spots.index');

// Eliminar un spot específico (DELETE)
Route::delete('/spots/{id}', [SpotController::class, 'destroy'])->name('spots.destroy');

// Eliminar todos los spots (DELETE)
Route::delete('/spots-all', [SpotController::class, 'deleteAll'])->name('spots.deleteAll');

// Formulario de registro
Route::get('/registro', function () {
    return view('registro');
})->name('registro');

// Guardar usuario en CSV
Route::post('/guardar-csv', [CsvController::class, 'guardar'])->name('guardar.csv');

// Dashboard 
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Vista de bienvenida AdminLTE
Route::get('/adminlte/bienvenida', function () {
    return view('adminlte.bienvenida');
})->middleware('auth')->name('adminlte.bienvenida');

// Vista del formulario
Route::get('/adminlte/crear-usuario', function () {
    return view('adminlte.crear_usuario');
})->middleware('auth')->name('adminlte.usuario.create');

// Acción del formulario
Route::post('/adminlte/crear-usuario', function () {
    request()->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required'
    ]);

    \App\Models\User::create([
        'name' => request('name'),
        'email' => request('email'),
        'password' => bcrypt(request('password')),
    ]);

    return redirect()->back()->with('message', 'Usuario creado correctamente');
})->middleware('auth')->name('adminlte.usuario.store');
