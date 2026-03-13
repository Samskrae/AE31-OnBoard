    <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\CsvController;

Route::get('/', function () {
    return response()->json([
        'api' => 'AE31-OnBoard v1.0',
        'endpoints' => [
            'spots' => [
                'GET /api/spots' => 'Listar spots',
                'POST /api/spots' => 'Crear spot',
                'GET /api/spots/{id}' => 'Ver spot',
                'PUT /api/spots/{id}' => 'Actualizar spot',
                'DELETE /api/spots/{id}' => 'Eliminar spot'
            ],
            'registros' => [
                'GET /api/registros' => 'Listar registros',
                'POST /api/registros' => 'Crear registro',
                'GET /api/registros/{id}' => 'Ver registro',
                'PUT /api/registros/{id}' => 'Actualizar registro',
                'DELETE /api/registros/{id}' => 'Eliminar registro'
            ],
            'user' => [
                'GET /api/user' => 'Usuario autenticado'
            ]
        ]
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API endpoints para Spots
Route::get('/spots', [SpotController::class, 'apiIndex']);
Route::post('/spots', [SpotController::class, 'apiStore']);
Route::get('/spots/{id}', [SpotController::class, 'apiShow']);
Route::put('/spots/{id}', [SpotController::class, 'apiUpdate']);
Route::delete('/spots/{id}', [SpotController::class, 'apiDestroy']);

// API endpoints para Registros
Route::get('/registros', [CsvController::class, 'apiIndex']);
Route::post('/registros', [CsvController::class, 'apiStore']);
Route::get('/registros/{id}', [CsvController::class, 'apiShow']);
Route::put('/registros/{id}', [CsvController::class, 'apiUpdate']);
Route::delete('/registros/{id}', [CsvController::class, 'apiDestroy']);
