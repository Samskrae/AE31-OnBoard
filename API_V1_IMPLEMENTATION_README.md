# API v1 - Resumen de Implementación

## 🎯 Objetivo
Implementar una API REST completa para la entidad **Spot** (ubicaciones de escalada) del proyecto OnBoard, permitiendo que aplicaciones externas consulten, creen, modifiquen y eliminen datos a través de endpoints HTTP.

## 📋 Requisitos Cumplidos

### ✅ Estructura de archivos
- [x] Controlador en `app/Http/Controllers/Api/V1/SpotController.php`
- [x] Rutas definidas en `routes/api.php` bajo prefijo `v1`
- [x] Uso correcto de Namespaces (`App\Http\Controllers\Api\V1`)

### ✅ Desarrollo del CRUD
- [x] **Listado (index):** GET `/api/v1/spots` - Retorna todos los spots
- [x] **Individual (show):** GET `/api/v1/spots/{id}` - Retorna un spot específico
- [x] **Creación (store):** POST `/api/v1/spots` - Crea un nuevo spot
- [x] **Edición (update):** PUT/PATCH `/api/v1/spots/{id}` - Actualiza un spot
- [x] **Eliminación (destroy):** DELETE `/api/v1/spots/{id}` - Elimina un spot (204 No Content)

### ✅ Transformación con Eloquent Resource
- [x] Resource en `app/Http/Resources/SpotResource.php`
- [x] Transformación de campos (ej: `nombre` → `name`, `lat` → `latitude`)
- [x] Exclusión de datos sensibles
- [x] Formato profesional en las respuestas JSON

### ✅ Validación robusta con Form Requests
- [x] `StoreSpotRequest` en `app/Http/Requests/Api/V1/StoreSpotRequest.php`
- [x] `UpdateSpotRequest` en `app/Http/Requests/Api/V1/UpdateSpotRequest.php`
- [x] Validaciones implementadas:
  - Campos requeridos (nombre, lat, lon, descripcion, nivel)
  - Tipos de datos (string, numeric)
  - Rangos de valores (lat: -90 a 90, lon: -180 a 180)
  - Longitud máxima de strings
  - Enumeración de niveles (beginner, intermediate, advanced, expert)
  - URLs válidas para imagen
- [x] Mensajes de error personalizados en español

### ✅ Respuestas HTTP correctas
- [x] **200 OK** para GET, PUT, PATCH exitosos
- [x] **201 Created** para POST exitoso
- [x] **204 No Content** para DELETE exitoso
- [x] **422 Unprocessable Entity** para validaciones fallidas
- [x] **404 Not Found** para recursos no encontrados

### ✅ Errores de validación legibles
- [x] Formato JSON claro con estructura `{ message, errors }`
- [x] Mensajes en español descritos
- [x] Campo `errors` con detalles por campo

## 📁 Archivos Creados/Modificados

### Nuevos Archivos
```
app/Http/Controllers/Api/V1/
└── SpotController.php          (Controlador API con métodos CRUD)

app/Http/Requests/Api/V1/
├── StoreSpotRequest.php        (Validación para crear spots)
└── UpdateSpotRequest.php       (Validación para actualizar spots)

app/Http/Resources/
└── SpotResource.php            (Transformación de datos)

API_V1_DOCUMENTATION.md         (Documentación completa de la API)
api_examples.sh                 (Ejemplos con curl)
Postman_Collection_API_v1.json  (Colección para Postman)
```

### Archivos Modificados
```
routes/api.php                  (Agregadas rutas v1 para spots)
```

## 🚀 Cómo Usar

### 1. Clonar y configurar el proyecto
```bash
git clone https://github.com/Samskrae/AE31-OnBoard.git
cd AE31-OnBoard
git checkout feature/api-v1
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 2. Iniciar el servidor
```bash
php artisan serve
```

### 3. Probar la API
- **Opción 1:** Usar Thunder Client/Postman con la colección `Postman_Collection_API_v1.json`
- **Opción 2:** Usar curl con los ejemplos en `api_examples.sh`
- **Opción 3:** Leer la documentación completa en `API_V1_DOCUMENTATION.md`

## 📊 Estructura de Datos

### Modelo Spot
```php
// Campos de la base de datos
- id (autoincrement)
- nombre (string, max:255)
- lat (decimal)
- lon (decimal)
- descripcion (text)
- nivel (enum: beginner|intermediate|advanced|expert)
- imagen (string, url, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Respuesta de API (SpotResource)
```json
{
  "data": {
    "id": 1,
    "name": "Siurana",
    "latitude": 41.3801,
    "longitude": 1.1749,
    "description": "Excelente zona de escalada",
    "level": "intermediate",
    "image": "https://example.com/image.jpg",
    "created_at": "2025-12-02T10:30:00Z",
    "updated_at": "2025-12-02T10:30:00Z"
  }
}
```

## 🧪 Endpoints Principales

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/api/v1/spots` | Listar todos los spots |
| GET | `/api/v1/spots/{id}` | Obtener un spot |
| POST | `/api/v1/spots` | Crear un spot |
| PUT | `/api/v1/spots/{id}` | Actualizar un spot (todos los campos) |
| PATCH | `/api/v1/spots/{id}` | Actualizar un spot (campos parciales) |
| DELETE | `/api/v1/spots/{id}` | Eliminar un spot |

## 📝 Notas Importantes

### Transformación de campos
⚠️ **El controlador recibe y devuelve campos diferentes:**
- **Entrada (POST/PUT):** `nombre`, `lat`, `lon`, `descripcion`, `nivel`, `imagen`
- **Salida (GET):** `name`, `latitude`, `longitude`, `description`, `level`, `image`

Esto se realiza en el `SpotResource` para mantener un estándar profesional en la API.

### Validaciones
- La validación ocurre automáticamente en el Form Request
- Si hay errores, se retorna **422** con detalles en JSON
- Los mensajes están en español para mejor comprensión

### Métodos HTTP
- Use **PUT** para reemplazar todos los campos
- Use **PATCH** para actualizar solo campos específicos
- Ambos validarán los campos proporcionados

## 🔍 Pruebas Recomendadas

1. **Test de creación:** POST con datos válidos → Esperar 201
2. **Test de validación:** POST con datos inválidos → Esperar 422 con errores
3. **Test de lectura:** GET para listar y obtener individual → Esperar 200
4. **Test de actualización:** PUT/PATCH → Esperar 200 con datos actualizados
5. **Test de eliminación:** DELETE → Esperar 204 sin contenido
6. **Test de recurso no encontrado:** GET/PUT/DELETE con ID inexistente → Esperar 404

## 📚 Documentación

Para documentación completa, consulta [API_V1_DOCUMENTATION.md](API_V1_DOCUMENTATION.md)

## 🌳 Rama Git
La implementación está en la rama: `feature/api-v1`

Para hacer checkout:
```bash
git checkout feature/api-v1
```

## ✨ Características Especiales

- ✅ Documentación profesional y completa
- ✅ Ejemplos de curl listos para usar
- ✅ Colección de Postman importable
- ✅ Mensajes de error claros y descriptivos
- ✅ Validaciones robustas con reglas profesionales
- ✅ Códigos HTTP correctos según estándares REST
- ✅ Uso de Namespaces y estructura moderna de Laravel

---

**Fecha de creación:** Febrero 2026  
**Versión de API:** v1  
**Framework:** Laravel 11  
**Entidad:** Spot (Ubicaciones de escalada)
