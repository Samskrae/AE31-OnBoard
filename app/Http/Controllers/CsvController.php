<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Illuminate\Support\Facades\Log;

/**
 * CsvController
 * 
 * Controlador para gestionar el registro de usuarios
 * y exportar datos.
 */
class CsvController extends Controller
{
    /**
     * Guardar usuario en archivo CSV con validaciones robustas
     * 
     * Validaciones:
     * - Nombre: Requerido, 3-100 caracteres, solo letras y acentos
     * - Email: Requerido, formato válido de email, único (no repetido)
     * - Contraseña: Requerida, mínimo 6 caracteres
     * - Fecha: Requerida, formato válido
     * - Biografía: Opcional, máximo 500 caracteres
     * 
     */
    public function guardar(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'name' => [
                        'required',
                        'string',
                        'min:3',
                        'max:100',
                        'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/' // Solo letras y acentos
                    ],
                    'email' => [
                        'required',
                        'email',
                        'max:255'
                    ],
                    'password' => [
                        'required',
                        'string',
                        'min:6',
                        'max:255'
                    ],
                    'date' => [
                        'required',
                        'date',
                        'before_or_equal:' . date('Y-m-d') // No puede ser fecha futura
                    ],
                    'bio' => [
                        'nullable',
                        'string',
                        'max:500'
                    ]
                ],
                [
                    // Mensajes personalizados de error
                    'name.required' => 'El nombre es obligatorio.',
                    'name.string' => 'El nombre debe ser texto válido.',
                    'name.min' => 'El nombre debe tener al menos 3 caracteres.',
                    'name.max' => 'El nombre no puede exceder 100 caracteres.',
                    'name.regex' => 'El nombre solo puede contener letras y acentos.',
                    
                    'email.required' => 'El correo electrónico es obligatorio.',
                    'email.email' => 'El correo debe ser un email válido.',
                    'email.max' => 'El correo no puede exceder 255 caracteres.',
                    
                    'password.required' => 'La contraseña es obligatoria.',
                    'password.string' => 'La contraseña debe ser texto válido.',
                    'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                    'password.max' => 'La contraseña no puede exceder 255 caracteres.',
                    
                    'date.required' => 'La fecha de nacimiento es obligatoria.',
                    'date.date' => 'La fecha debe ser un formato válido.',
                    'date.before_or_equal' => 'La fecha no puede ser futura.',
                    
                    'bio.string' => 'La biografía debe ser texto válido.',
                    'bio.max' => 'La biografía no puede exceder 500 caracteres.'
                ]
            );

            // Sanitizar datos
            $validated['name'] = $this->sanitizeInput($validated['name']);
            $validated['email'] = strtolower(trim($validated['email']));
            if (!empty($validated['bio'])) {
                $validated['bio'] = $this->sanitizeInput($validated['bio']);
            }

            // Verificar si el email ya existe
            if ($this->emailExists($validated['email'])) {
                return back()
                    ->withErrors(['email' => 'Este correo ya está registrado.'])
                    ->withInput();
            }

            // Guardar en la tabla 'registros'
            Registro::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'date_of_birth' => $validated['date'],
                'bio' => $validated['bio'] ?? null,
            ]);

            return back()->with('success', '✅ Usuario registrado correctamente en la base de datos');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al registrar usuario: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Error al registrar: ' . $e->getMessage()])->withInput();
        }
    }
    /**
     * Verificar si el email ya existe en la tabla 'registros'
     */
    private function emailExists($email)
    {
        try {
            return Registro::whereRaw('lower(email) = ?', [strtolower($email)])->exists();
        } catch (\Exception $e) {
            Log::error('Error checking email existence: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sanitizar entrada del usuario
     */
    private function sanitizeInput($input)
    {
        // Eliminar caracteres de control
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Escapar caracteres especiales para CSV
        $input = str_replace(['"', "'"], ['\\"', "\\'"], $input);
        
        return trim($input);
    }

    // ===================== API METHODS =====================

    /**
     * API - Listar todos los registros
     */
    public function apiIndex()
    {
        try {
            $registros = Registro::select('id', 'name', 'email', 'date_of_birth as fecha', 'bio')
                ->get();
            return response()->json([
                'data' => $registros,
                'count' => $registros->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al listar registros: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Ver un registro específico
     */
    public function apiShow($id)
    {
        try {
            $registro = Registro::select('id', 'name', 'email', 'date_of_birth as fecha', 'bio')
                ->findOrFail($id);
            return response()->json($registro, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Crear un nuevo registro
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'email' => [
                    'required',
                    'email',
                    'unique:registros,email',
                    'max:255'
                ],
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'max:255'
                ],
                'fecha' => [
                    'required',
                    'date',
                    'before_or_equal:' . date('Y-m-d')
                ],
                'biografia' => [
                    'nullable',
                    'string',
                    'max:500'
                ]
            ]);

            $registro = Registro::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'date_of_birth' => $validated['fecha'],
                'bio' => $validated['biografia'] ?? null
            ]);

            return response()->json([
                'message' => 'Registro creado exitosamente',
                'data' => [
                    'id' => $registro->id,
                    'name' => $registro->name,
                    'email' => $registro->email,
                    'fecha' => $registro->date_of_birth
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validación fallida',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Actualizar un registro
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $registro = Registro::findOrFail($id);

            $validated = $request->validate([
                'name' => [
                    'sometimes',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
                ],
                'email' => [
                    'sometimes',
                    'email',
                    'unique:registros,email,' . $id,
                    'max:255'
                ],
                'password' => [
                    'sometimes',
                    'string',
                    'min:6',
                    'max:255'
                ],
                'fecha' => [
                    'sometimes',
                    'date',
                    'before_or_equal:' . date('Y-m-d')
                ],
                'biografia' => [
                    'nullable',
                    'string',
                    'max:500'
                ]
            ]);

            if (isset($validated['name'])) $registro->name = $validated['name'];
            if (isset($validated['email'])) $registro->email = $validated['email'];
            if (isset($validated['password'])) $registro->password = bcrypt($validated['password']);
            if (isset($validated['fecha'])) $registro->date_of_birth = $validated['fecha'];
            if (isset($validated['biografia'])) $registro->bio = $validated['biografia'];

            $registro->save();

            return response()->json([
                'message' => 'Registro actualizado exitosamente',
                'data' => [
                    'id' => $registro->id,
                    'name' => $registro->name,
                    'email' => $registro->email
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validación fallida',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API - Eliminar un registro
     */
    public function apiDestroy($id)
    {
        try {
            $registro = Registro::findOrFail($id);
            $registro->delete();

            return response()->json([
                'message' => 'Registro eliminado exitosamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Registro no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
