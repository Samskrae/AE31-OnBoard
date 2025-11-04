@extends('layouts.app')

@section('content')
<h2>Listado de Spots</h2>

@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

@if (count($spots) > 0)
<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Ubicación</th>
        <th>Descripción</th>
        <th>Nivel</th>
    </tr>
    @foreach ($spots as $spot)
        <tr>
            <td>{{ $spot[0] }}</td>
            <td>{{ $spot[1] }}</td>
            <td>{{ $spot[2] }}</td>
            <td>{{ $spot[3] }}</td>
        </tr>
    @endforeach
</table>
@else
<p>No hay spots registrados aún.</p>
@endif

<a href="{{ route('spots.create') }}">➕ Añadir otro Spot</a>
@endsection
