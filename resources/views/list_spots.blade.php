@extends('layouts.app')

@section('content')
<!-- El siguiente div servirá para que no se cree nada debajo del nav -->
<div class="p-15 bg-red-300" id="bordeSuperior"></div>
<div>
    <div id="map" class="h-screen"></div>
    <script>
        var map = L.map('map',{
            scrollWheelZoom: false,  // ❌ Desactiva el zoom con la rueda del ratón
            doubleClickZoom: true,  // ❌ Desactiva el zoom con doble clic
            boxZoom: false,          // ❌ Desactiva el zoom de selección
            touchZoom: false,        // ❌ Desactiva zoom táctil (pinch en móvil)
        }

        ).setView([28.1235, -15.4363], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Lista de marcadores (algunos con imagen, otros no)
        const lugares = [
            { 
                nombre: 'Plaza del Pilar', 
                lat: 28.129260, 
                lon: -15.444640,
                imagen: '/imagenes/pilar.jpg',
                descripcion: 'Una plaza muy concurrida con cafeterías y tiendas alrededor.',
                dificultad: 'Principiante'
            },
            { 
                nombre: 'Parque de Santa Catalina', 
                lat: 28.141836332219864, 
                lon: -15.429798600959824,
                // Sin imagen
                descripcion: 'Uno de los parques más emblemáticos de Las Palmas 🌴',
                dificultad: 'Principiante'
            },
            { 
                nombre: 'Paseo de Las Canteras', 
                lat: 28.142656704250204, 
                lon: -15.433298108313407,
                descripcion: 'Famoso paseo marítimo con vistas al mar y restaurantes cercanos.',
                dificultad: 'Principiante'
            }
        ];

        // Crear marcadores dinámicamente
        lugares.forEach(lugar => {
            // Si tiene imagen, la mostramos; si no, dejamos el espacio vacío
            const contenidoPopup = `
                <div class="text-center">
                    <h2 class="font-bold text-lg">${lugar.nombre}</h2>
                    ${lugar.imagen 
                        ? `<img src="${lugar.imagen}" class="w-40 h-24 object-cover rounded-lg mx-auto mt-2 shadow-md" />`
                        : `<div class="text-gray-500 text-sm italic mt-2">Sin imagen disponible</div>`
                    }
                    <p class="text-sm mt-2 text-gray-700">${lugar.descripcion}</p>
                    <p class="text-sm mt-1 font-semibold">Nivel: ${lugar.dificultad}</p>
                </div>
            `;

            L.marker([lugar.lat, lugar.lon])
                .addTo(map)
                .bindPopup(contenidoPopup);
        });
    </script>
</div>
@endsection
