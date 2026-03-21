# AE5.3 - Pruebas de Autenticación con Tokens

## Configuración
- **URL Base**: http://localhost:8000/api
- **Usuario de prueba**: 
  - Email: test@example.com
  - Password: password

## Pruebas Realizadas

### 1. Test - Acceso sin token (Unauthorized)
**Endpoint**: GET /api/spots
**Método**: GET
**Headers**: Sin Authorization

**Resultado Esperado**: 401 Unauthorized

---

### 2. Test - Login exitoso
**Endpoint**: POST /api/login
**Método**: POST
**Body**:
```json
{
  "email": "test@example.com",
  "password": "password"
}
```

**Resultado Esperado**: 200 OK con token

---

### 3. Test - Acceso con token válido
**Endpoint**: GET /api/spots
**Método**: GET
**Headers**: Authorization: Bearer {token}

**Resultado Esperado**: 200 OK con lista de spots

---

### 4. Test - Crear Spot (Protegido)
**Endpoint**: POST /api/spots
**Método**: POST
**Headers**: Authorization: Bearer {token}
**Body**:
```json
{
  "nombre": "La Bañeza Skate",
  "lat": 42.2833,
  "lng": -5.95,
  "descripcion": "Spot de skateboarding con rampas y obstáculos",
  "nivel": "Intermedio"
}
```

**Resultado Esperado**: 201 Created

---

### 5. Test - Obtener usuario autenticado
**Endpoint**: GET /api/me
**Método**: GET
**Headers**: Authorization: Bearer {token}

**Resultado Esperado**: 200 OK con datos del usuario

---

### 6. Test - Logout
**Endpoint**: POST /api/logout
**Método**: POST
**Headers**: Authorization: Bearer {token}

**Resultado Esperado**: 200 OK - Token revocado

---

## Conclusiones

✓ Sistema de autenticación basado en tokens funcionando correctamente
✓ Rutas protegidas declinan acceso sin token (401)
✓ Rutas protegidas aceptan peticiones con token válido (200)
✓ Gestión de tokens con Laravel Sanctum implementada
