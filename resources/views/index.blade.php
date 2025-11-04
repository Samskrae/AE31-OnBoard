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
            class="absolute top-0 left-0 w-full h-full object-cover"
        >
            <source src="{{ asset('imagenes/videofondo.mp4') }}" type="video/mp4">
            Tu navegador no soporta videos en HTML5.
        </video>

        <!-- Capa oscura sobre el video -->
        <div class="absolute top-0 left-0 w-full h-full bg-black/50"></div>

        <!-- Contenido principal -->
        <div class="relative z-10 text-white">
            <h1 class="text-5xl font-bold mb-4">🛹 Bienvenido a OnBoard</h1>
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

    @endsection
</body>
</html>
