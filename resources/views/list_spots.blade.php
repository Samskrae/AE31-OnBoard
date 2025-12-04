@extends('layouts.cuerpo')

@section('content')
<div class="p-15" id="bordeSuperior"></div>

<div class="min-h-screen bg-gray-50 text-gray-900 rounded-lg shadow">
    <!-- Encabezado -->
    <div class="bg-white border-b border-gray-200 p-6 rounded-2xl">
        <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-4">
            <h1 class="text-4xl font-bold">🛹 Spots de Skateboarding</h1>
            <a href="{{ route('spots.create') }}" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-bold transition transform hover:scale-105">
                ➕ Nuevo Spot
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 m-6 rounded">
            <p class="text-green-700 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="max-w-7xl mx-auto p-6">
        <div class="bg-white rounded-xl p-6 mb-6 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Búsqueda -->
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="🔍 Buscar por nombre..." 
                        class="w-full px-4 py-3 bg-gray-100 text-gray-900 rounded-lg border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-200 outline-none transition"
                    >
                </div>

                <!-- Filtro por Nivel -->
                <select 
                    id="filterLevel" 
                    class="px-4 py-3 bg-gray-100 text-gray-900 rounded-lg border border-gray-200 focus:border-gray-400 focus:ring-2 focus:ring-gray-200 outline-none transition"
                >
                    <option value="">📊 Todos los niveles</option>
                    <option value="Principiante">🟢 Principiante</option>
                    <option value="Intermedio">🟡 Intermedio</option>
                    <option value="Avanzado">🔴 Avanzado</option>
                </select>

                <!-- Selector de Vista -->
                <div class="flex gap-2 justify-end">
                    <button 
                        id="tableViewBtn" 
                        class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-lg font-bold transition transform hover:scale-105 flex items-center gap-2"
                    >
                        📋 Tabla
                    </button>
                    <button 
                        id="mapViewBtn" 
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-lg font-bold transition transform hover:scale-105 flex items-center gap-2"
                    >
                        🗺️ Mapa
                    </button>
                </div>
            </div>

            <!-- Contador de resultados -->
            <div class="text-gray-600 text-sm">
                Mostrando <span id="resultCount">{{ count($spots) }}</span> de {{ count($spots) }} spots
            </div>
        </div>

        <!-- VISTA: TABLA -->
        <div id="tableView" class="block">
            @if (count($spots) === 0)
                <div class="bg-gray-800 rounded-xl p-12 text-center border-2 border-gray-700">
                    <p class="text-5xl mb-4">😢</p>
                    <h3 class="text-2xl font-bold mb-2">No hay spots aún</h3>
                    <p class="text-gray-400 mb-6">Sé el primero en agregar un spot a la comunidad</p>
                    <a href="{{ route('spots.create') }}" class="bg-pink-600 hover:bg-pink-700 px-6 py-2 rounded-lg font-bold transition">
                        ➕ Crear el primer spot
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-4 text-left font-bold text-gray-800">Nombre</th>
                                    <th class="px-6 py-4 text-left font-bold text-gray-800">Descripción</th>
                                    <th class="px-6 py-4 text-center font-bold text-gray-800">Nivel</th>
                                    <th class="px-6 py-4 text-center font-bold text-gray-800">Imagen</th>
                                    <th class="px-6 py-4 text-center font-bold text-gray-800">Coordenadas</th>
                                    <th class="px-6 py-4 text-center font-bold text-gray-800">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="spotTableBody">
                                @foreach ($spots as $index => $spot)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition spot-row" data-name="{{ strtolower($spot['nombre']) }}" data-level="{{ $spot['nivel'] }}">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $spot['nombre'] }}</td>
                                        <td class="px-6 py-4 text-gray-600 text-sm max-w-xs truncate">{{ $spot['descripcion'] }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full font-bold text-sm
                                                @if ($spot['nivel'] === 'Principiante') bg-green-100 text-green-800
                                                @elseif ($spot['nivel'] === 'Intermedio') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif
                                            ">
                                                @if ($spot['nivel'] === 'Principiante') 🟢
                                                @elseif ($spot['nivel'] === 'Intermedio') 🟡
                                                @else 🔴
                                                @endif
                                                {{ $spot['nivel'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($spot['imagen'])
                                                <button class="text-2xl hover:scale-125 transition image-thumbnail" data-image="/storage/{{ $spot['imagen'] }}" data-name="{{ $spot['nombre'] }}">
                                                    🖼️
                                                </button>
                                            @else
                                                <span class="text-gray-500">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-400">
                                            <span class="cursor-help" title="Lat: {{ $spot['lat'] }}, Lon: {{ $spot['lon'] }}">
                                                📍 {{ number_format($spot['lat'], 4) }}, {{ number_format($spot['lon'], 4) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('spots.destroy', $index) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este spot?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-2xl hover:scale-125 transition text-red-600 hover:text-red-800">
                                                    🗑️
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botón Eliminar Todos -->
                @if (count($spots) > 0)
                    <div class="mt-6 flex justify-end">
                        <form action="{{ route('spots.deleteAll') }}" method="POST" onsubmit="return confirm('⚠️ ADVERTENCIA: Esto eliminará TODOS los spots. ¿Estás completamente seguro?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-6 py-2 rounded-lg font-bold transition flex items-center gap-2 border border-red-200">
                                🗑️ Eliminar Todos
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>

        <!-- VISTA: MAPA -->
        <div id="mapView" class="hidden">
            <div id="map" class="h-screen rounded-xl border border-gray-200 shadow"></div>
        </div>
    </div>
</div>

<!-- Modal de Imagen -->
<div id="imageModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl border border-gray-200 max-w-2xl max-h-[90vh] overflow-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-100">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900"></h3>
            <button id="closeModal" class="text-2xl text-gray-600 hover:text-gray-900">✕</button>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="Spot Image" class="w-full rounded-lg">
        </div>
    </div>
</div>

<script>
    const spots = @json($spots);
    let currentView = 'table';
    let map = null;

    // Búsqueda en tiempo real
    document.getElementById('searchInput').addEventListener('input', filterSpots);
    document.getElementById('filterLevel').addEventListener('change', filterSpots);

    function filterSpots() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const filterLevel = document.getElementById('filterLevel').value;
        const rows = document.querySelectorAll('.spot-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const level = row.getAttribute('data-level');
            
            const matchesSearch = name.includes(searchTerm);
            const matchesFilter = !filterLevel || level === filterLevel;
            
            if (matchesSearch && matchesFilter) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('resultCount').textContent = visibleCount;
    }

    // Cambiar vista
    document.getElementById('tableViewBtn').addEventListener('click', () => switchView('table'));
    document.getElementById('mapViewBtn').addEventListener('click', () => switchView('map'));

    function switchView(view) {
        currentView = view;
        const tableView = document.getElementById('tableView');
        const mapView = document.getElementById('mapView');
        const tableBtn = document.getElementById('tableViewBtn');
        const mapBtn = document.getElementById('mapViewBtn');

        if (view === 'table') {
            tableView.style.display = 'block';
            mapView.style.display = 'none';
            tableBtn.classList.add('bg-pink-600');
            tableBtn.classList.remove('bg-gray-700');
            mapBtn.classList.remove('bg-pink-600');
            mapBtn.classList.add('bg-gray-700');
        } else {
            tableView.style.display = 'none';
            mapView.style.display = 'block';
            mapBtn.classList.add('bg-pink-600');
            mapBtn.classList.remove('bg-gray-700');
            tableBtn.classList.remove('bg-pink-600');
            tableBtn.classList.add('bg-gray-700');
            
            if (!map) {
                initMap();
            } else {
                map.invalidateSize();
            }
        }
    }

    // Inicializar mapa
    function initMap() {
        map = L.map('map', {
            scrollWheelZoom: false,
            doubleClickZoom: true,
            boxZoom: false,
            touchZoom: false,
        }).setView([28.1235, -15.4363], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        spots.forEach(lugar => {
            if (!lugar.lat || !lugar.lon) return;

            const levelIcon = 
                lugar.nivel === 'Principiante' ? '🟢' :
                lugar.nivel === 'Intermedio' ? '🟡' :
                '🔴';

            const contenidoPopup = `
                <div class="text-center">
                    <h2 class="font-bold text-lg text-gray-900">${lugar.nombre}</h2>
                    ${lugar.imagen
                        ? `<img src="/storage/${lugar.imagen}" class="w-40 h-24 object-cover rounded-lg mx-auto mt-2 shadow-md" />`
                        : `<div class="text-gray-500 text-sm italic mt-2">Sin imagen disponible</div>`
                    }
                    <p class="text-sm mt-2 text-gray-700">${lugar.descripcion}</p>
                    <p class="text-sm mt-1 font-semibold">${levelIcon} ${lugar.nivel}</p>
                </div>
            `;

            L.marker([lugar.lat, lugar.lon])
                .addTo(map)
                .bindPopup(contenidoPopup);
        });
    }

    // Modal de imágenes
    const imageModal = document.getElementById('imageModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');

    document.querySelectorAll('.image-thumbnail').forEach(btn => {
        btn.addEventListener('click', () => {
            modalImage.src = btn.getAttribute('data-image');
            modalTitle.textContent = btn.getAttribute('data-name');
            imageModal.style.display = 'flex';
        });
    });

    closeModalBtn.addEventListener('click', () => {
        imageModal.style.display = 'none';
    });

    imageModal.addEventListener('click', (e) => {
        if (e.target === imageModal) {
            imageModal.style.display = 'none';
        }
    });

    // Cargar Leaflet si no está disponible
    if (typeof L === 'undefined') {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css';
        document.head.appendChild(link);

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js';
        document.body.appendChild(script);
    }
</script>

@endsection
