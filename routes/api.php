    <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json([
        'api' => 'AE31-OnBoard v1.0 - Autenticación con Tokens',
        'auth' => [
            'POST /api/login' => 'Login con email y password',
            'POST /api/logout' => 'Logout (requiere token)',
            'GET /api/me' => 'Obtener usuario autenticado (requiere token)'
        ],
        'endpoints' => [
            'spots' => [
                'GET /api/spots' => 'Listar spots (requiere token)',
                'POST /api/spots' => 'Crear spot (requiere token)',
                'GET /api/spots/{id}' => 'Ver spot (requiere token)',
                'PUT /api/spots/{id}' => 'Actualizar spot (requiere token)',
                'DELETE /api/spots/{id}' => 'Eliminar spot (requiere token)'
            ],
            'registros' => [
                'GET /api/registros' => 'Listar registros (requiere token)',
                'POST /api/registros' => 'Crear registro (requiere token)',
                'GET /api/registros/{id}' => 'Ver registro (requiere token)',
                'PUT /api/registros/{id}' => 'Actualizar registro (requiere token)',
                'DELETE /api/registros/{id}' => 'Eliminar registro (requiere token)'
            ]
        ]
    ]);
});

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con token
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
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
});
