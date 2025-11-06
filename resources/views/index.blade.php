<body class="bg-gray-100 text-gray-900">
    @extends('layouts.app')
    @section('content')

    <div class="relative min-h-screen flex items-center justify-center text-center overflow-hidden">
        <!-- Video de fondo -->
        <video 
            autoplay 
            loop 
            muted 
            playsinline 
            class="absolute top-0 left-0 w-full h-full object-cover rounded-2xl"
        >
            <source src="{{ asset('imagenes/videofondo.mp4') }}" type="video/mp4">
            Tu navegador no soporta videos en HTML5.
        </video>

        <!-- Capa oscura sobre el video -->
        <div class="absolute top-0 left-0 w-full h-full bg-black/50 rounded-2xl"></div>

        <!-- Contenido principal -->
        <div class="relative z-10 text-white">
            <h1 class="text-5xl font-bold mb-4">Bienvenido a OnBoard</h1>
            <p class="text-xl mb-6">Tu comunidad skater online: comparte spots y vive el skate.</p>
            <div class="space-x-4">
                <a href="{{ route('spots.create') }}" class="bg-white/80 text-black px-4 py-2 rounded-lg font-semibold hover:bg-white transition">
                    ➕ Crear nuevo Spot
                </a>
                <a href="{{ route('spots.index') }}" class="bg-white/80 text-black px-4 py-2 rounded-lg font-semibold hover:bg-white transition">
                    📍 Ver Spots
                </a>
            </div>
        </div>
    </div>
<div class="mt-10">
  <div class="bg-gray-200 p-10 justify-left align-left rounded-2xl shadow-lg mx-6">
    <h1 class="text-4xl border-b-4 border-gray-400 w-full pb-2 text-left font-bold">¿Quiénes somos?</h1>
    <div class="flex flex-row items-center mt-6 ">
        <p class="mt-6 text-lg text-justify w-2/4 mx-auto">
            En OnBoard, somos una comunidad apasionada por el skateboarding. Nuestra misión es conectar a skaters de todos los niveles para compartir spots, experiencias y fomentar el crecimiento del skate en nuestra ciudad. Ya seas un principiante buscando tu primer spot o un veterano explorando nuevos desafíos, OnBoard es tu lugar.
        </p>
        <img src="{{ asset('imagenes/skate.avif') }}" alt="skateboarding" class="w-1/4 mx-auto mt-6 rounded-2xl shadow-lg transition-transform duration-700 ease-[cubic-bezier(0.68,-0.55,0.27,1.55)] hover:rotate-3 hover:scale-105"/>


    </div>
  </div>
</div>

    @endsection
</body>
</html>
