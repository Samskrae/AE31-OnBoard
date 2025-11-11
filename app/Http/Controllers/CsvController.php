<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CsvController extends Controller
{
    public function guardar(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'date' => 'required|date',
            'bio' => 'nullable|string',
        ]);

        // Ruta del archivo CSV en public/
        $filePath = public_path('registros.csv');

        // Si no existe, crear el archivo con encabezados
        if (!File::exists($filePath)) {
            $header = ['Nombre', 'Correo', 'Contraseña (hash)', 'Fecha de Nacimiento', 'Biografía'];
            $file = fopen($filePath, 'w');
            fputcsv($file, $header, ',', '"'); // ← separador coma, comillas dobles
            fclose($file);
        }

        // Datos del usuario (todos en comillas)
        $data = [
            $validated['name'],
            $validated['email'],
            bcrypt($validated['password']),
            $validated['date'],
            $validated['bio'] ?? '',
        ];

        // Abrir el archivo en modo append y escribir datos
        $file = fopen($filePath, 'a');
        fputcsv($file, $data, ',', '"'); // ← tercer parámetro: separador, cuarto: comillas
        fclose($file);

        return back()->with('success', 'Usuario guardado correctamente en registros.csv');
    }
}
