@extends('layouts.app')

@section('content')
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

<form action="{{ route('spots.store') }}" method="POST">
    @csrf
    <label>Nombre:</label>
    <input type="text" name="nombre" value="{{ old('nombre') }}"><br>

    <label>Ubicación:</label>
    <input type="text" name="ubicacion" value="{{ old('ubicacion') }}"><br>

    <label>Descripción:</label>
    <textarea name="descripcion">{{ old('descripcion') }}</textarea><br>

    <label>Nivel recomendado:</label>
    <select name="nivel">
        <option>Principiante</option>
        <option>Intermedio</option>
        <option>Avanzado</option>
    </select><br>

    <button type="submit">Guardar Spot</button>
</form>
@endsection
