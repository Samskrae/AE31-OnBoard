<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    public function create()
    {
        return view('create_spot');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|min:3',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'descripcion' => 'required|min:10',
            'nivel' => 'required|in:Principiante,Intermedio,Avanzado',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // Guardar imagen si existe
        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('spots', 'public');
        }

        // Guardar línea CSV
        $line = implode(',', [
            $validated['nombre'],
            $validated['lat'],
            $validated['lon'],
            str_replace(',', ' ', $validated['descripcion']),
            $validated['nivel'],
            $imagenPath ?? ''
        ]) . "\n";

        Storage::append('spots.csv', $line);

        return redirect()->route('spots.index')->with('success', 'Spot guardado correctamente');
    }

    public function index()
    {
        $spots = [];

        if (Storage::exists('spots.csv')) {
            $lines = explode("\n", trim(Storage::get('spots.csv')));
            foreach ($lines as $line) {
                if (empty($line)) continue;
                $data = str_getcsv($line);

                // Mínimo 5 columnas
                if (count($data) >= 5) {
                    $spots[] = [
                        'nombre' => $data[0],
                        'lat' => (float) $data[1],
                        'lon' => (float) $data[2],
                        'descripcion' => $data[3],
                        'nivel' => $data[4],
                        'imagen' => $data[5] ?? null,
                    ];
                }
            }
        }

        return view('list_spots', compact('spots'));
    }

    public function deleteAll()
    {
        Storage::delete('spots.csv');
        return redirect()->route('spots.index')->with('success', 'Todos los spots fueron eliminados.');
    }
}
