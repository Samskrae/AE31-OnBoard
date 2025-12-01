<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Illuminate\Support\Facades\Log;

/**
 * CsvController
 * 
 * Controlador para gestionar el registro de usuarios
 * y exportar datos a CSV de forma segura.
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guardar(Request $request)
    {
        try {
            // Validaciones robustas con mensajes personalizados en español
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
                    // Mensajes personalizados en español
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

            // Guardar en la tabla 'registros' mediante Eloquent
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
     * Crear archivo CSV con encabezados
     * 
     * @param string $filePath
     * @return void
     */
    /**
     * Verificar si el email ya existe en la tabla 'registros'
     *
     * @param string $email
     * @return bool
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
     * 
     * @param string $input
     * @return string
     */
    private function sanitizeInput($input)
    {
        // Eliminar caracteres de control
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Escapar caracteres especiales para CSV
        $input = str_replace(['"', "'"], ['\\"', "\\'"], $input);
        
        return trim($input);
    }
}
