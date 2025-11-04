<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <!-- Styles / Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    @extends('layouts.app')
    @section('content')
    <div>
        <h1>🛹 Bienvenido a OnBoard</h1>
        <p>Tu comunidad skater online: comparte spots y vive el skate.</p>
        <a href="{{ route('spots.create') }}">➕ Crear nuevo Spot</a> |
        <a href="{{ route('spots.index') }}">📍 Ver Spots</a>
    </div>
    @endsection
</body>
</html>