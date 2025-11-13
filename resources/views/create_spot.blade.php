@extends('layouts.app')

@section('content')
<div class="p-15" id="bordeSuperior"></div>

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8 text-center">
            <h1 class="text-5xl font-bold text-white mb-2">🛹 Nuevo Spot</h1>
            <p class="text-gray-400 text-lg">Comparte tu spot de skateboarding favorito con la comunidad</p>
        </div>

        <!-- Alertas de error -->
        @if ($errors->any())
            <div class="mb-6 bg-red-900/30 border-2 border-red-500 rounded-xl p-4 backdrop-blur-sm">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <h3 class="text-red-300 font-bold mb-2">Errores en el formulario:</h3>
                        <ul class="space-y-1 text-red-200">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <span>→</span> {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tarjeta de formulario -->
        <form action="{{ route('spots.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-800/50 backdrop-blur-md border-2 border-gray-700 rounded-2xl p-8 shadow-2xl">
            @csrf

            <!-- Campo: Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-white font-bold text-lg mb-2">
                    📍 Nombre del Spot
                </label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    value="{{ old('nombre') }}"
                    placeholder="Ej: Plaza Central del Parque"
                    class="w-full px-4 py-3 bg-gray-700 border-2 @error('nombre') border-red-500 @else border-gray-600 @enderror rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/50 transition"
                >
                @error('nombre')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs mt-1">Mínimo 3 caracteres, máximo 100</p>
            </div>

            <!-- Campos de Ubicación -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Latitud -->
                <div>
                    <label for="lat" class="block text-white font-bold text-lg mb-2">
                        🧭 Latitud
                    </label>
                    <input 
                        type="number" 
                        id="lat" 
                        name="lat" 
                        step="any"
                        value="{{ old('lat') }}"
                        placeholder="Ej: 28.1235"
                        class="w-full px-4 py-3 bg-gray-700 border-2 @error('lat') border-red-500 @else border-gray-600 @enderror rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/50 transition"
                    >
                    @error('lat')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 text-xs mt-1">Entre -90 y 90</p>
                </div>

                <!-- Longitud -->
                <div>
                    <label for="lon" class="block text-white font-bold text-lg mb-2">
                        🧭 Longitud
                    </label>
                    <input 
                        type="number" 
                        id="lon" 
                        name="lon" 
                        step="any"
                        value="{{ old('lon') }}"
                        placeholder="Ej: -15.4363"
                        class="w-full px-4 py-3 bg-gray-700 border-2 @error('lon') border-red-500 @else border-gray-600 @enderror rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/50 transition"
                    >
                    @error('lon')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 text-xs mt-1">Entre -180 y 180</p>
                </div>
            </div>

            <!-- Campo: Descripción -->
            <div class="mb-6">
                <label for="descripcion" class="block text-white font-bold text-lg mb-2">
                    📝 Descripción
                </label>
                <textarea 
                    id="descripcion" 
                    name="descripcion"
                    rows="5"
                    placeholder="Describe el spot: ubicación exacta, características, dificultad, etc."
                    class="w-full px-4 py-3 bg-gray-700 border-2 @error('descripcion') border-red-500 @else border-gray-600 @enderror rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/50 transition resize-none"
                >{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
                <div class="flex justify-between items-center mt-1">
                    <p class="text-gray-400 text-xs">Mínimo 10 caracteres</p>
                    <span id="charCount" class="text-gray-400 text-xs">0/500</span>
                </div>
            </div>

            <!-- Campo: Nivel de Dificultad -->
            <div class="mb-6">
                <label for="nivel" class="block text-white font-bold text-lg mb-2">
                    ⚡ Nivel de Dificultad
                </label>
                <select 
                    id="nivel" 
                    name="nivel"
                    class="w-full px-4 py-3 bg-gray-700 border-2 @error('nivel') border-red-500 @else border-gray-600 @enderror rounded-lg text-white focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/50 transition"
                >
                    <option value="">-- Selecciona un nivel --</option>
                    <option value="Principiante" @selected(old('nivel') === 'Principiante')>🟢 Principiante</option>
                    <option value="Intermedio" @selected(old('nivel') === 'Intermedio')>🟡 Intermedio</option>
                    <option value="Avanzado" @selected(old('nivel') === 'Avanzado')>🔴 Avanzado</option>
                </select>
                @error('nivel')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Imagen (Drag & Drop) -->
            <div class="mb-8">
                <label for="imagen" class="block text-white font-bold text-lg mb-2">
                    📸 Imagen del Spot
                </label>
                
                <div id="dropZone" class="border-3 border-dashed @error('imagen') border-red-500 @else border-gray-600 @enderror rounded-xl p-8 text-center cursor-pointer transition hover:border-pink-500 hover:bg-gray-700/50 bg-gray-700/30">
                    <input 
                        type="file" 
                        id="imagen" 
                        name="imagen"
                        accept="image/*"
                        class="hidden"
                    >
                    
                    <div id="dropContent" class="space-y-2">
                        <p class="text-4xl">🖼️</p>
                        <p class="text-white font-bold">Arrastra una imagen aquí</p>
                        <p class="text-gray-400 text-sm">o haz clic para seleccionar</p>
                        <p class="text-gray-500 text-xs mt-2">Formatos: JPEG, PNG, GIF, WebP, AVIF (máx. 2MB)</p>
                    </div>
                    
                    <div id="fileInfo" class="hidden space-y-2">
                        <p class="text-4xl">✅</p>
                        <p id="fileName" class="text-white font-bold text-sm"></p>
                    </div>
                </div>
                
                @error('imagen')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs mt-2">Las imágenes son opcionales</p>
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-4 justify-center md:justify-end">
                <a 
                    href="{{ route('spots.index') }}"
                    class="px-8 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-600 transition transform hover:scale-105"
                >
                    ❌ Cancelar
                </a>
                <button 
                    type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-pink-600 to-pink-500 text-white font-bold rounded-lg hover:from-pink-700 hover:to-pink-600 transition transform hover:scale-105 shadow-lg"
                >
                    ✅ Guardar Spot
                </button>
            </div>
        </form>

        <!-- Enlace de ayuda -->
        <div class="mt-8 text-center">
            <a href="https://maps.google.com" target="_blank" class="text-pink-400 hover:text-pink-300 text-sm font-semibold flex items-center justify-center gap-2">
                🗺️ Necesitas las coordenadas? Usa Google Maps
            </a>
        </div>
    </div>
</div>

<script>
    // Contador de caracteres
    const textarea = document.getElementById('descripcion');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length + '/500';
    });

    // Drag & Drop de imágenes
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('imagen');
    const dropContent = document.getElementById('dropContent');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-pink-500', 'bg-gray-700/50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-pink-500', 'bg-gray-700/50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-pink-500', 'bg-gray-700/50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateFileInfo(files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            updateFileInfo(e.target.files[0]);
        }
    });

    function updateFileInfo(file) {
        fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + 'KB)';
        dropContent.classList.add('hidden');
        fileInfo.classList.remove('hidden');
    }
</script>
@endsection
