<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnBoard</title>
    <!-- Estilos -->
    <!-- <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/f750d98763.js" crossorigin="anonymous"></script>   
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
<header class="bg-gray-300 shadow-xl z-1 fixed w-full z-[9999]">
  <nav class="relative flex items-center justify-center p-4 h-32 z-100">
    <!-- Logo (posición absoluta para que no mueva el centro) -->
    <div class="absolute left-5 flex items-center">
      <img 
        src="{{ asset('imagenes/logo.png') }}" 
        alt="logo" 
        class="h-24 object-contain"
      />
    </div>

    <!-- Enlaces centrados -->
    <div class="flex justify-center gap-8 text-lg font-semibold">
      <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
      <a href="{{ route('spots.create') }}" class="hover:text-blue-600">Nuevo Spot</a>
      <a href="{{ route('spots.index') }}" class="hover:text-blue-600">Ver Spots</a>
    </div>

    <!-- Icono de usuario -->
    <div class="absolute right-6 flex items-center text-2xl">
      <i class="fa-solid fa-user"></i>
    </div>
  </nav>
</header>



    <main class="min-h-screen p-6 text-center bg-gray-100">
        @yield('content')
    </main>

    <footer>
        <p>© {{ date('Y') }} OnBoard - Proyecto Laravel</p>
    </footer>
</body>
</html>
