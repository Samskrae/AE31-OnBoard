@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1>🛹 Bienvenido a OnBoard</h1>
    <p>Tu comunidad skater online: comparte spots y vive el skate.</p>
    <a href="{{ route('spots.create') }}">➕ Crear nuevo Spot</a> |
    <a href="{{ route('spots.index') }}">📍 Ver Spots</a>
</div>
@endsection
