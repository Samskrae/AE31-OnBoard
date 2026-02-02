# 🚀 GUÍA RÁPIDA - API v1 OnBoard

## ⚡ Start Rápido (5 minutos)

### 1. Preparar el proyecto
```bash
composer install
php artisan migrate
php artisan serve
```

### 2. Primera petición
```bash
curl http://localhost:8000/api/v1/spots
```

### 3. Crear un spot
```bash
curl -X POST http://localhost:8000/api/v1/spots \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Mi Spot","lat":41.0,"lon":1.0,"descripcion":"Test","nivel":"beginner"}'
```

---

## 📚 Documentación Disponible

### Para entender qué es la API
👉 **Leer:** `API_V1_IMPLEMENTATION_README.md`
- Resumen ejecutivo
- Requisitos cumplidos
- Estructura de archivos
- Notas técnicas

### Para usar todos los endpoints
👉 **Leer:** `API_V1_DOCUMENTATION.md`
- Todos los endpoints detallados
- Parámetros y validaciones
- Ejemplos de requests/responses
- Guía de errores

### Para probar en Postman/Thunder Client
👉 **Usar:** `Postman_Collection_API_v1.json`
1. Abre Postman
2. File → Import → Selecciona archivo
3. Configura variable `base_url`
4. ¡A probar!

### Para usar curl desde terminal
👉 **Ver:** `api_examples.sh`
- 7 ejemplos listos para usar
- Copia y pega en tu terminal

### Para entender la estructura
👉 **Leer:** `API_STRUCTURE_SUMMARY.md`
- Diagrama completo de archivos
- Detalles técnicos
- Características destacadas

---

## 🔗 Endpoints en 30 segundos

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/api/v1/spots` | Listar todos |
| GET | `/api/v1/spots/1` | Obtener uno |
| POST | `/api/v1/spots` | Crear |
| PUT | `/api/v1/spots/1` | Actualizar |
| DELETE | `/api/v1/spots/1` | Eliminar |

---

## ✅ Checklist para Otros Grupos

Al probar la API, verifica:

- [ ] **GET todos**: Retorna 200 con lista de spots
- [ ] **GET uno**: Retorna 200 con un spot específico  
  - Verifica que los campos estén transformados (`name`, `latitude`, `longitude`, etc.)
- [ ] **POST crear**: Retorna 201 con nuevo spot creado
  - Usa JSON con `nombre`, `lat`, `lon`, `descripcion`, `nivel`
- [ ] **PUT actualizar**: Retorna 200 con datos actualizados
- [ ] **PATCH actualizar parcial**: Retorna 200 actualizando solo campos enviados
- [ ] **DELETE**: Retorna 204 (sin contenido)
- [ ] **Validación fallida**: 
  - POST con `nivel` inválido → Retorna 422 con errores
  - POST sin campo `nombre` → Retorna 422 con errores
  - Verifica que los mensajes sean claros en español
- [ ] **Recurso no encontrado**: GET/PUT/DELETE con ID inexistente → 404

---

## 🐛 Si Algo No Funciona

### Error: "No encontrado" / "Not found" (404)
**Solución:** Asegúrate de que:
- El servidor Laravel está corriendo (`php artisan serve`)
- Usas la URL correcta (`http://localhost:8000/api/v1/spots`)
- El ID existe

### Error de validación (422)
**Solución:** Lee el JSON de error, te dice exactamente qué está mal:
```json
{
  "errors": {
    "nivel": ["El nivel debe ser uno de: beginner, intermediate, advanced, expert."]
  }
}
```

### Error de conexión
**Solución:**
```bash
php artisan serve  # Asegúrate que el servidor está activo
```

---

## 🎯 Casos de Prueba Recomendados

### Test 1: Flujo completo
1. GET `/api/v1/spots` → Obtener lista
2. POST `/api/v1/spots` → Crear uno nuevo
3. GET `/api/v1/spots/{id}` → Verificar que aparece
4. PUT `/api/v1/spots/{id}` → Cambiar nivel a "expert"
5. DELETE `/api/v1/spots/{id}` → Eliminarlo
6. GET `/api/v1/spots/{id}` → Verificar 404

### Test 2: Validaciones
1. POST con `nivel: "invalid"` → Debe retornar 422
2. POST sin campo `nombre` → Debe retornar 422
3. POST con `lat: 91` (fuera de rango) → Debe retornar 422
4. POST con `imagen: "texto sin url"` → Debe retornar 422

### Test 3: Edge cases
1. POST dos spots con mismo nombre → Ambos se crean (OK)
2. PUT solo con `{"nivel":"beginner"}` → Actualiza solo ese campo
3. DELETE un spot inexistente → 404

---

## 💾 Campos de Spot

**Para crear/editar, envía estos campos:**
```json
{
  "nombre": "string (max:255)",
  "lat": "número entre -90 y 90",
  "lon": "número entre -180 y 180",
  "descripcion": "string (max:1000)",
  "nivel": "beginner|intermediate|advanced|expert",
  "imagen": "url válida (opcional)"
}
```

**Recibirás en la respuesta:**
```json
{
  "id": "número",
  "name": "string",
  "latitude": "número",
  "longitude": "número",
  "description": "string",
  "level": "string",
  "image": "url",
  "created_at": "timestamp ISO",
  "updated_at": "timestamp ISO"
}
```

---

## 🌳 Info de Git

**Rama:** `feature/api-v1`

Ver cambios:
```bash
git log feature/api-v1
git diff main feature/api-v1
```

---

## 👥 ¿Necesitas Ayuda?

1. **Revisar ejemplo:** Ver `API_V1_DOCUMENTATION.md` sección de ejemplos
2. **Entender validación:** Revisar `StoreSpotRequest.php` en el código
3. **Ver estructura:** Leer `API_STRUCTURE_SUMMARY.md`
4. **Código fuente:** Revisar `app/Http/Controllers/Api/V1/SpotController.php`

---

## 📊 Resumen de Implementación

✅ **6 endpoints REST** completamente funcionales  
✅ **Validación robusta** en español  
✅ **Transformación de datos** con Resources  
✅ **Documentación profesional** con ejemplos  
✅ **Colección Postman** lista para importar  
✅ **Ejemplos curl** listos para copiar/pegar  
✅ **Códigos HTTP correctos** (200, 201, 204, 422, 404)  

---

**¡La API está lista para usar!** 🎉

Siguiente paso: Importa `Postman_Collection_API_v1.json` en Postman o Thunder Client y ¡comienza a probar!
