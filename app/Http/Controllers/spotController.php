<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Spot;

/**
 * SpotController
 * 
 * Controlador para gestionar los spots de skateboarding.
 * Implementa validaciones robustas, seguridad contra inyecciones
 * y gestión de imágenes.
 */
class SpotController extends Controller
{
    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('create_spot');
    }

    /**
     * 
     * Validaciones:
     * - Nombre: Requerido, 3-100 caracteres, solo letras, números y acentos
     * - Latitud: Entre -90 y 90
     * - Longitud: Entre -180 y 180
     * - Descripción: Requerida, mínimo 10 caracteres
     * - Nivel: Debe ser uno de los tres niveles permitidos
     * - Imagen: Archivo de imagen, máximo 2MB
     */
    public function store(Request $request)
    {
        try {
            // Validaciones
            $validated = $request->validate(
                [
                    'nombre' => [
                        'required',
                        'string',
                        'min:3',
                        'max:100',
                        'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/'
                    ],
                    'lat' => [
                        'required',
                        'numeric',
                        'between:-90,90'
                    ],
                    'lon' => [
                        'required',
                        'numeric',
                        'between:-180,180'
                    ],
                    'descripcion' => [
                        'required',
                        'string',
                        'min:10',
                        'max:500'
                    ],
                    'nivel' => [
                        'required',
                        'string',
                        'in:Principiante,Intermedio,Avanzado'
                    ],
                    'imagen' => [
                        'nullable',
                        'image',
                        'mimes:jpeg,png,gif,webp,avif',
                        'max:2048'
                    ]
                ],
                [
                    'nombre.required' => 'El nombre del spot es obligatorio.',
                    'nombre.string' => 'El nombre debe ser texto válido.',
                    'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
                    'nombre.max' => 'El nombre no puede exceder 100 caracteres.',
                    'nombre.regex' => 'El nombre contiene caracteres no permitidos.',

                    'lat.required' => 'La latitud es obligatoria.',
                    'lat.numeric' => 'La latitud debe ser un número válido.',
                    'lat.between' => 'La latitud debe estar entre -90 y 90.',

                    'lon.required' => 'La longitud es obligatoria.',
                    'lon.numeric' => 'La longitud debe ser un número válido.',
                    'lon.between' => 'La longitud debe estar entre -180 y 180.',

                    'descripcion.required' => 'La descripción es obligatoria.',
                    'descripcion.string' => 'La descripción debe ser texto válido.',
                    'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
                    'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',

                    'nivel.required' => 'Debes seleccionar un nivel de dificultad.',
                    'nivel.in' => 'El nivel debe ser uno de: Principiante, Intermedio o Avanzado.',

                    'imagen.image' => 'El archivo debe ser una imagen válida.',
                    'imagen.mimes' => 'La imagen debe ser de formato: JPEG, PNG, GIF, WebP o AVIF.',
                    'imagen.max' => 'La imagen no puede pesar más de 2MB.',
                ]
            );

            // Sanitizar datos
            $validated['nombre'] = $this->sanitizeInput($validated['nombre']);
            $validated['descripcion'] = $this->sanitizeInput($validated['descripcion']);

            // Guardar imagen si existe
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                try {
                    $nombreArchivo = time() . '_' . uniqid() . '.' .
                        $request->file('imagen')->getClientOriginalExtension();

                    $imagenPath = $request->file('imagen')->storeAs(
                        'spots',
                        $nombreArchivo,
                        'public'
                    );
                } catch (\Exception $e) {
                    return back()->withErrors([
                        'imagen' => 'Error al guardar la imagen: ' . $e->getMessage()
                    ])->withInput();
                }
            }

            $line = implode(',', [
                $validated['nombre'],
                $validated['lat'],
                $validated['lon'],
                str_replace(',', ' ', $validated['descripcion']),
                $validated['nivel'],
                $imagenPath ?? ''
            ]) . "\n";

            Storage::append('spots.csv', $line);


            Spot::create([
                'nombre'      => $validated['nombre'],
                'lat'         => $validated['lat'],
                'lon'         => $validated['lon'],
                'descripcion' => $validated['descripcion'],
                'nivel'       => $validated['nivel'],
                'imagen'      => $imagenPath,
            ]);

            return redirect()->route('spots.index')
                ->with('success', '✅ ¡Spot guardado correctamente!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'general' => 'Error al guardar el spot: ' . $e->getMessage()
            ])->withInput();
        }
    }


    /**
     * Mostrar listado de spots
     * 
     * Lee el archivo CSV y convierte los datos en un array
     * de spots con estructura consistente.

     */
    public function index()
    {
        $spots = $this->loadSpotsFromCsv();

        return view('list_spots', compact('spots'));
    }

    /**
     * Cargar spots desde el archivo CSV
     */
    private function loadSpotsFromCsv()
    {
        $spots = [];

        if (Storage::exists('spots.csv')) {
            try {
                $lines = explode("\n", trim(Storage::get('spots.csv')));
                
                foreach ($lines as $index => $line) {
                    if (empty($line)) continue;
                    
                    $data = str_getcsv($line);

                    // Mínimo 5 columnas
                    if (count($data) >= 5) {
                        $spots[] = [
                            'id' => $index,
                            'nombre' => $this->sanitizeForDisplay($data[0]),
                            'lat' => (float) $data[1],
                            'lon' => (float) $data[2],
                            'descripcion' => $this->sanitizeForDisplay($data[3]),
                            'nivel' => $data[4],
                            'imagen' => $data[5] ?? null,
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error al cargar spots del CSV: ' . $e->getMessage());
            }
        }

        return $spots;
    }

    /**
     * Eliminar un spot específico
     */
    public function destroy($id)
    {
        try {
            $spots = $this->loadSpotsFromCsv();
            
            // Crear nuevo contenido sin el spot eliminado
            $content = '';
            foreach ($spots as $index => $spot) {
                if ($index != $id) {
                    $line = implode(',', [
                        $spot['nombre'],
                        $spot['lat'],
                        $spot['lon'],
                        str_replace(',', ' ', $spot['descripcion']),
                        $spot['nivel'],
                        $spot['imagen'] ?? ''
                    ]) . "\n";
                    $content .= $line;
                }
            }

            // Guardar nuevo contenido
            if ($content) {
                Storage::put('spots.csv', $content);
            } else {
                Storage::delete('spots.csv');
            }

            return back()->with('success', '✅ Spot eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Error al eliminar el spot: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar todos los spots
     */
    public function deleteAll()
    {
        try {
            Storage::delete('spots.csv');
            return redirect()->route('spots.index')->with('success', '🗑️ Todos los spots fueron eliminados correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Error al eliminar los spots: ' . $e->getMessage()]);
        }
    }

    /**
     * Sanitizar entrada del usuario
     * 
     */
    private function sanitizeInput($input)
    {
        // Eliminar caracteres de control
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Escapar caracteres especiales para CSV
        $input = str_replace(['"', "'"], ['\\"', "\\'"], $input);
        
        return trim($input);
    }

    /**
     * Sanitizar para mostrar en HTML
     * 
     * @param string $input
     * @return string
     */
    private function sanitizeForDisplay($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    // API Methods

    /**
     * API: Listar todos los spots (JSON)
     */
    public function apiIndex()
    {
        $spots = Spot::all();
        return response()->json($spots);
    }

    /**
     * API: Mostrar un spot específico (JSON)
     */
    public function apiShow($id)
    {
        $spot = Spot::find($id);
        if (!$spot) {
            return response()->json(['error' => 'Spot not found'], 404);
        }
        return response()->json($spot);
    }

    /**
     * API: Crear nuevo spot (JSON)
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|min:3|max:100',
                'lat' => 'required|numeric|between:-90,90',
                'lon' => 'required|numeric|between:-180,180',
                'descripcion' => 'required|string|min:10|max:500',
                'nivel' => 'required|string|in:Principiante,Intermedio,Avanzado',
                'imagen' => 'nullable|image|mimes:jpeg,png,gif,webp,avif|max:2048'
            ]);

            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $nombreArchivo = time() . '_' . uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension();
                $imagenPath = $request->file('imagen')->storeAs('spots', $nombreArchivo, 'public');
            }

            $spot = Spot::create([
                'nombre' => $validated['nombre'],
                'lat' => $validated['lat'],
                'lon' => $validated['lon'],
                'descripcion' => $validated['descripcion'],
                'nivel' => $validated['nivel'],
                'imagen' => $imagenPath,
            ]);

            return response()->json($spot, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating spot: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Actualizar spot (JSON)
     */
    public function apiUpdate(Request $request, $id)
    {
        $spot = Spot::find($id);
        if (!$spot) {
            return response()->json(['error' => 'Spot not found'], 404);
        }

        try {
            $validated = $request->validate([
                'nombre' => 'sometimes|required|string|min:3|max:100',
                'lat' => 'sometimes|required|numeric|between:-90,90',
                'lon' => 'sometimes|required|numeric|between:-180,180',
                'descripcion' => 'sometimes|required|string|min:10|max:500',
                'nivel' => 'sometimes|required|string|in:Principiante,Intermedio,Avanzado',
                'imagen' => 'nullable|image|mimes:jpeg,png,gif,webp,avif|max:2048'
            ]);

            if ($request->hasFile('imagen')) {
                // Delete old image if exists
                if ($spot->imagen) {
                    Storage::disk('public')->delete($spot->imagen);
                }
                $nombreArchivo = time() . '_' . uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension();
                $validated['imagen'] = $request->file('imagen')->storeAs('spots', $nombreArchivo, 'public');
            }

            $spot->update($validated);

            return response()->json($spot);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating spot: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Eliminar spot (JSON)
     */
    public function apiDestroy($id)
    {
        $spot = Spot::find($id);
        if (!$spot) {
            return response()->json(['error' => 'Spot not found'], 404);
        }

        try {
            // Delete image if exists
            if ($spot->imagen) {
                Storage::disk('public')->delete($spot->imagen);
            }

            $spot->delete();

            return response()->json(['message' => 'Spot deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting spot: ' . $e->getMessage()], 500);
        }
    }
}
