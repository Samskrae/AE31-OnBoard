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

    // API Methods

    /**
     * API: Listar todos los registros (JSON)
     */
    public function apiIndex()
    {
        $registros = Registro::all();
        return response()->json($registros);
    }

    /**
     * API: Mostrar un registro específico (JSON)
     */
    public function apiShow($id)
    {
        $registro = Registro::find($id);
        if (!$registro) {
            return response()->json(['error' => 'Registro not found'], 404);
        }
        return response()->json($registro);
    }

    /**
     * API: Crear nuevo registro (JSON)
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:6|max:255',
                'date_of_birth' => 'required|date|before_or_equal:today',
                'bio' => 'nullable|string|max:500'
            ]);

            $registro = Registro::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'date_of_birth' => $validated['date_of_birth'],
                'bio' => $validated['bio'] ?? null,
            ]);

            return response()->json($registro, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating registro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Actualizar registro (JSON)
     */
    public function apiUpdate(Request $request, $id)
    {
        $registro = Registro::find($id);
        if (!$registro) {
            return response()->json(['error' => 'Registro not found'], 404);
        }

        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|min:3|max:100',
                'email' => 'sometimes|required|email|max:255',
                'password' => 'sometimes|required|string|min:6|max:255',
                'date_of_birth' => 'sometimes|required|date|before_or_equal:today',
                'bio' => 'sometimes|nullable|string|max:500'
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            $registro->update($validated);

            return response()->json($registro);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating registro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Eliminar registro (JSON)
     */
    public function apiDestroy($id)
    {
        $registro = Registro::find($id);
        if (!$registro) {
            return response()->json(['error' => 'Registro not found'], 404);
        }

        try {
            $registro->delete();
            return response()->json(['message' => 'Registro deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting registro: ' . $e->getMessage()], 500);
        }
    }
}
