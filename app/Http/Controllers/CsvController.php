<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

            // Ruta del archivo CSV en public/
            $filePath = public_path('registros.csv');

            // Si no existe, crear el archivo con encabezados
            if (!File::exists($filePath)) {
                $this->createCsvWithHeaders($filePath);
            }

            // Preparar datos para guardar
            $data = [
                $validated['name'],
                $validated['email'],
                bcrypt($validated['password']),
                $validated['date'],
                $validated['bio'] ?? '',
            ];

            // Abrir el archivo en modo append y escribir datos
            $file = fopen($filePath, 'a');
            if ($file) {
                fputcsv($file, $data, ',', '"'); // Separador coma, comillas dobles
                fclose($file);
            } else {
                throw new \Exception('No se puede abrir el archivo CSV para escribir.');
            }

            return back()->with('success', '✅ Usuario registrado correctamente en registros.csv');

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
    private function createCsvWithHeaders($filePath)
    {
        $header = [
            'Nombre',
            'Correo',
            'Contraseña (hash)',
            'Fecha de Nacimiento',
            'Biografía'
        ];

        $file = fopen($filePath, 'w');
        if ($file) {
            fputcsv($file, $header, ',', '"');
            fclose($file);
        }
    }

    /**
     * Verificar si el email ya existe en el CSV
     * 
     * @param string $email
     * @return bool
     */
    private function emailExists($email)
    {
        $filePath = public_path('registros.csv');

        if (!File::exists($filePath)) {
            return false;
        }

        try {
            $file = fopen($filePath, 'r');
            if (!$file) {
                return false;
            }

            // Saltar encabezado
            fgetcsv($file);

            while (($data = fgetcsv($file)) !== false) {
                if (isset($data[1]) && strtolower($data[1]) === strtolower($email)) {
                    fclose($file);
                    return true;
                }
            }

            fclose($file);
            return false;
        } catch (\Exception $e) {
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
