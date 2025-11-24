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
<header class="bg-gray-300 shadow-xl fixed w-full z-[9999]">
  <nav class="relative flex items-center justify-center p-4 h-32">
    <!-- Logo -->
    <div class="absolute left-5 flex items-center">
      <img 
        src="{{ asset('imagenes/logo.png') }}" 
        alt="logo" 
        class="h-24 object-contain"
      />
    </div>

    <!-- Enlaces centrados -->
    <div class="flex justify-center gap-8 text-lg font-semibold relative">
      <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>

      <!-- Menú desplegable Spots -->
      <div class="relative" id="spots-menu">
        <button id="spots-button"class="hover:text-blue-600 focus:outline-none flex items-center gap-1 cursor-pointer">
          Spots <i class="fa-solid fa-caret-down"></i>
        </button>

        <!-- Dropdown con animación tipo "desplegar" -->
        <div id="spots-dropdown" class="absolute left-0 top-full mt-2 bg-gray-200 shadow-lg rounded-md w-40 overflow-hidden max-h-0 opacity-0 transition-all duration-500 ease-out">
          <a href="{{ route('spots.create') }}" class="block px-4 py-2 hover:bg-gray-300">Nuevo Spot</a>
          <a href="{{ route('spots.index') }}" class="block px-4 py-2 hover:bg-gray-300">Ver Spots</a>
        </div>
      </div>
    </div>

    <!-- Icono de usuario -->
    <div class="absolute right-6 flex items-center text-2xl">
      <a href="{{ route('dashboard') }}">
        <i class="fa-solid fa-user"></i>
      </a>
      
    </div>
  </nav>
</header>
    <main class="min-h-screen p-6 text-center bg-gray-100">
        @yield('content')
    </main>

    <footer>
        <p>© {{ date('Y') }} OnBoard - Proyecto Laravel</p>
    </footer>


    <!-- Desplegable -->
    <script>
      const menu = document.getElementById('spots-menu');
      const dropdown = document.getElementById('spots-dropdown');
      let hideTimeout;

      // Función para abrir el desplegable
      function showDropdown() {
        clearTimeout(hideTimeout);
        dropdown.classList.remove('opacity-0');
        dropdown.style.maxHeight = dropdown.scrollHeight + 'px'; // se expande dinámicamente
        dropdown.style.opacity = '1';
      }

      // Función para cerrar el desplegable con animación
      function hideDropdown() {
        dropdown.style.maxHeight = '0';
        dropdown.style.opacity = '0';
      }

      menu.addEventListener('mouseenter', showDropdown);

      menu.addEventListener('mouseleave', () => {
        clearTimeout(hideTimeout);
        hideTimeout = setTimeout(hideDropdown, 1000); // 1 segundo antes de cerrarse
      });
    </script>
</body>
</html>
