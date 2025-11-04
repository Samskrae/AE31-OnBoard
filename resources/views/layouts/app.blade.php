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
</head>
<body>
<header class="bg-gray-300 shadow-xl">
  <nav class="relative flex items-center justify-center p-4 h-32">
    <!-- Logo (posición absoluta para que no mueva el centro) -->
    <div class="absolute left-5 flex items-center">
      <img 
        src="https://media.discordapp.net/attachments/1078622212652793898/1435252520401375332/Imagen_de_WhatsApp_2025-11-04_a_las_12.46.02_cdd0273f.png?ex=690b4a92&is=6909f912&hm=3a3c302a9c2ff4227986db26c51aea6f1783af1e82447d876fefcc285a2655af&=&format=webp&quality=lossless" 
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



    <main class="container">
        @yield('content')
    </main>

    <footer>
        <p>© {{ date('Y') }} OnBoard - Proyecto Laravel</p>
    </footer>
</body>
</html>
