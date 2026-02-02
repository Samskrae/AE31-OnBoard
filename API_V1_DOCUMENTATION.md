# API v1 - Documentación

## Base URL
```
http://localhost:8000/api/v1
```

## Descripción General
Esta es la API v1 del proyecto OnBoard. Permite gestionar **Spots** (ubicaciones de escalada) a través de operaciones CRUD completas.

## Endpoints disponibles

### 1. Obtener todos los spots
**GET** `/api/v1/spots`

**Descripción:** Devuelve una lista de todos los spots registrados.

**Response (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Siurana",
      "latitude": 41.3801,
      "longitude": 1.1749,
      "description": "Excelente zona de escalada con vistas al lago",
      "level": "intermediate",
      "image": "https://example.com/image.jpg",
      "created_at": "2025-12-02T10:30:00Z",
      "updated_at": "2025-12-02T10:30:00Z"
    },
    {
      "id": 2,
      "name": "Montserrat",
      "latitude": 41.6031,
      "longitude": 1.8359,
      "description": "Formaciones de piedra arenisca únicas",
      "level": "advanced",
      "image": "https://example.com/image2.jpg",
      "created_at": "2025-12-02T11:00:00Z",
      "updated_at": "2025-12-02T11:00:00Z"
    }
  ]
}
```

---

### 2. Obtener un spot específico
**GET** `/api/v1/spots/{id}`

**Descripción:** Obtiene los detalles de un spot específico por su ID.

**Parámetros:**
- `id` (required): El ID del spot

**Response (200 OK):**
```json
{
  "data": {
    "id": 1,
    "name": "Siurana",
    "latitude": 41.3801,
    "longitude": 1.1749,
    "description": "Excelente zona de escalada con vistas al lago",
    "level": "intermediate",
    "image": "https://example.com/image.jpg",
    "created_at": "2025-12-02T10:30:00Z",
    "updated_at": "2025-12-02T10:30:00Z"
  }
}
```

**Response (404 Not Found):**
```json
{
  "message": "Not found"
}
```

---

### 3. Crear un nuevo spot
**POST** `/api/v1/spots`

**Descripción:** Crea un nuevo spot con los datos proporcionados.

**Headers requeridos:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "nombre": "La Boca",
  "lat": 41.2500,
  "lon": 1.5000,
  "descripcion": "Una zona con múltiples vías de escalada",
  "nivel": "beginner",
  "imagen": "https://example.com/spot.jpg"
}
```

**Validaciones obligatorias:**
- `nombre` - Requerido, string (máx. 255 caracteres)
- `lat` - Requerido, número (entre -90 y 90)
- `lon` - Requerido, número (entre -180 y 180)
- `descripcion` - Requerido, string (máx. 1000 caracteres)
- `nivel` - Requerido, uno de: `beginner`, `intermediate`, `advanced`, `expert`
- `imagen` - Opcional, URL válida (máx. 2048 caracteres)

**Response (201 Created):**
```json
{
  "data": {
    "id": 3,
    "name": "La Boca",
    "latitude": 41.2500,
    "longitude": 1.5000,
    "description": "Una zona con múltiples vías de escalada",
    "level": "beginner",
    "image": "https://example.com/spot.jpg",
    "created_at": "2025-12-02T12:00:00Z",
    "updated_at": "2025-12-02T12:00:00Z"
  }
}
```

**Response (422 Unprocessable Entity) - Validación fallida:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "nombre": [
      "El nombre del spot es obligatorio."
    ],
    "lat": [
      "La latitud debe ser un número."
    ],
    "nivel": [
      "El nivel debe ser uno de: beginner, intermediate, advanced, expert."
    ]
  }
}
```

---

### 4. Actualizar un spot
**PUT/PATCH** `/api/v1/spots/{id}`

**Descripción:** Actualiza los datos de un spot existente. Se pueden actualizar todos o solo algunos campos.

**Parámetros:**
- `id` (required): El ID del spot a actualizar

**Headers requeridos:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON) - Ejemplo (todos los campos son opcionales):**
```json
{
  "nombre": "La Boca Actualizada",
  "lat": 41.2501,
  "lon": 1.5001,
  "descripcion": "Zona mejorada con nuevas vías",
  "nivel": "intermediate",
  "imagen": "https://example.com/new-spot.jpg"
}
```

**Response (200 OK):**
```json
{
  "data": {
    "id": 3,
    "name": "La Boca Actualizada",
    "latitude": 41.2501,
    "longitude": 1.5001,
    "description": "Zona mejorada con nuevas vías",
    "level": "intermediate",
    "image": "https://example.com/new-spot.jpg",
    "created_at": "2025-12-02T12:00:00Z",
    "updated_at": "2025-12-02T13:15:00Z"
  }
}
```

**Response (422 Unprocessable Entity) - Validación fallida:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "nivel": [
      "El nivel debe ser uno de: beginner, intermediate, advanced, expert."
    ]
  }
}
```

**Response (404 Not Found):**
```json
{
  "message": "Not found"
}
```

---

### 5. Eliminar un spot
**DELETE** `/api/v1/spots/{id}`

**Descripción:** Elimina un spot de la base de datos.

**Parámetros:**
- `id` (required): El ID del spot a eliminar

**Response (204 No Content):**
Sin contenido en el body (respuesta vacía)

**Response (404 Not Found):**
```json
{
  "message": "Not found"
}
```

---

## Niveles de dificultad disponibles
- `beginner` - Principiante
- `intermediate` - Intermedio
- `advanced` - Avanzado
- `expert` - Experto

---

## Notas importantes para testing

### Campo de nombre en la API
⚠️ **Importante:** El campo en la base de datos se llama `nombre`, pero la API lo transforma a `name` en la respuesta para mantener estándares profesionales.

- Cuando **CREAS** o **EDITAS**: Usa `"nombre"` en el JSON
- Cuando **CONSULTAS**: Recibirás `"name"` en la respuesta

### Ejemplo de la transformación:
```
Request (POST/PUT):     {"nombre": "Mi Spot"}
Response (GET):         {"name": "Mi Spot"}
```

---

## Códigos de estado HTTP esperados

| Código | Significado |
|--------|-------------|
| 200 | OK - Operación exitosa (GET, PUT, PATCH) |
| 201 | Created - Recurso creado exitosamente (POST) |
| 204 | No Content - Eliminación exitosa (DELETE) |
| 404 | Not Found - Recurso no encontrado |
| 422 | Unprocessable Entity - Errores de validación |
| 500 | Server Error - Error en el servidor |

---

## Ejemplos de uso con Thunder Client/Postman

### Crear un spot
```
POST http://localhost:8000/api/v1/spots

Headers:
Content-Type: application/json
Accept: application/json

Body:
{
  "nombre": "Calders",
  "lat": 41.6000,
  "lon": 1.8000,
  "descripcion": "Excelente zona para principiantes",
  "nivel": "beginner",
  "imagen": "https://example.com/calders.jpg"
}
```

### Obtener todos los spots
```
GET http://localhost:8000/api/v1/spots

Headers:
Accept: application/json
```

### Obtener un spot específico
```
GET http://localhost:8000/api/v1/spots/1

Headers:
Accept: application/json
```

### Actualizar un spot
```
PUT http://localhost:8000/api/v1/spots/1

Headers:
Content-Type: application/json
Accept: application/json

Body:
{
  "nivel": "intermediate",
  "descripcion": "Zona actualizada con nuevas rutas"
}
```

### Eliminar un spot
```
DELETE http://localhost:8000/api/v1/spots/1

Headers:
Accept: application/json
```

---

## Instrucciones para probar la API

1. **Clona el repositorio:** 
   ```bash
   git clone https://github.com/Samskrae/AE31-OnBoard.git
   git checkout feature/api-v1
   ```

2. **Instala las dependencias:**
   ```bash
   composer install
   ```

3. **Configura el archivo .env:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Ejecuta las migraciones:**
   ```bash
   php artisan migrate
   ```

5. **Inicia el servidor de desarrollo:**
   ```bash
   php artisan serve
   ```

6. **Prueba los endpoints** usando Thunder Client o Postman

---

## Errores comunes y soluciones

### Error: "The given data was invalid" (422)
**Causa:** Los datos no cumplen con las validaciones.
**Solución:** Revisa el campo `errors` en la respuesta para ver qué campos tienen problemas.

### Error: "Not found" (404)
**Causa:** El ID del spot no existe.
**Solución:** Verifica que el ID sea correcto usando GET `/api/v1/spots`.

### Error: "SQLSTATE" (500)
**Causa:** Error en la base de datos.
**Solución:** Asegúrate de que la base de datos esté correctamente configurada y las migraciones ejecutadas.

---

## Información del proyecto
- **Framework:** Laravel 11
- **Base de datos:** MySQL/SQLite (según configuración)
- **Entidad principal:** Spot (ubicaciones de escalada)
- **Versión de API:** v1
- **Fecha de creación:** Diciembre 2025

