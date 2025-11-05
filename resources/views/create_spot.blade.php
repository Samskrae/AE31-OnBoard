@extends('layouts.app')

@section('content')
<!-- El siguiente div servirá para que no se cree nada debajo del nav -->
<div class="p-15 bg-red-300" id="bordeSuperior"></div>
<h2>Registrar nuevo Spot</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('spots.store') }}" method="POST" class="flex flex-col gap-1 max-w-md mx-auto">
    @csrf
    <label>Nombre:</label>
    <input type="text" name="nombre" value="{{ old('nombre') }}" class="border-2 border-gray-700 focus:border-pink-600 rounded-2xl"><br>

    <label>Ubicación:</label>
    <input type="text" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="  Formato: lat, lon" class="border-2 border-gray-700 focus:border-pink-600 rounded-2xl"><br>

    <label>Descripción:</label>
    <textarea name="descripcion" class="border-2 border-gray-700 focus:border-pink-600 rounded-2xl">{{ old('descripcion') }}</textarea><br>
    <label>Nivel recomendado:</label>
    <select name="nivel" class="border-2 border-gray-700 focus:border-pink-600 rounded-2xl">
        <option>Principiante</option>
        <option>Intermedio</option>
        <option>Avanzado</option>
    </select><br>
    <div class="flex flex-col items-center space-y-2">
        <label for="imagen" class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            📸 Subir imagen
        </label>
        <input type="file" id="imagen" name="imagen" class="hidden" accept="image/*">
        <p id="nombre-archivo" class="text-gray-600 text-sm italic">Ningún archivo seleccionado</p>
    </div><br>

    <script>
        const input = document.getElementById('imagen');
        const texto = document.getElementById('nombre-archivo');

        input.addEventListener('change', function() {
            if (this.files.length > 0) {
                texto.textContent = `📁 ${this.files[0].name}`;
            } else {
                texto.textContent = 'Ningún archivo seleccionado';
            }
        });
    </script>

    <button type="submit" class="transition duration-500 ease-in-out bg-gray-300 hover:bg-blue-600 rounded-2xl p-2 w-50 m-auto">Guardar Spot</button>
</form>
@endsection
