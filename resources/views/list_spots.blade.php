@extends('layouts.app')

@section('content')
<div class="p-15" id="bordeSuperior"></div>
<div>
    <div id="map" class="h-screen"></div>

    <script>
        var map = L.map('map', {
            scrollWheelZoom: false,
            doubleClickZoom: true,
            boxZoom: false,
            touchZoom: false,
        }).setView([28.1235, -15.4363], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // ✅ Spots desde Laravel
        const lugares = @json($spots);

        if (!lugares || lugares.length === 0) {
            console.log("No hay spots aún 😢");
        }
        
        lugares.forEach(lugar => {
            if (!lugar.lat || !lugar.lon) return;

            const contenidoPopup = `
                <div class="text-center">
                    <h2 class="font-bold text-lg">${lugar.nombre}</h2>
                    ${lugar.imagen
                        ? `<img src="/storage/${lugar.imagen}" class="w-40 h-24 object-cover rounded-lg mx-auto mt-2 shadow-md" />`
                        : `<div class="text-gray-500 text-sm italic mt-2">Sin imagen disponible</div>`
                    }
                    <p class="text-sm mt-2 text-gray-700">${lugar.descripcion}</p>
                    <p class="text-sm mt-1 font-semibold">Nivel: ${lugar.nivel}</p>
                </div>
            `;

            L.marker([lugar.lat, lugar.lon])
                .addTo(map)
                .bindPopup(contenidoPopup);
        });
    </script>
</div>
@endsection
