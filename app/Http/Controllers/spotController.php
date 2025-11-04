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
            'ubicacion' => 'required|min:3',
            'descripcion' => 'required|min:10',
            'nivel' => 'required|in:Principiante,Intermedio,Avanzado',
        ]);

        $line = implode(',', $validated) . "\n";
        Storage::append('spots.csv', $line);

        return redirect()->route('spots.index')->with('success', 'Spot guardado correctamente');
    }

    public function index()
    {
        $spots = [];

        if (Storage::exists('spots.csv')) {
            $lines = explode("\n", trim(Storage::get('spots.csv')));
            foreach ($lines as $line) {
                $spots[] = str_getcsv($line);
            }
        }

        return view('list_spots', ['spots' => $spots]);
    }
}
