# AE5.3 - Autenticación mediante Tokens

## Descripción
Sistema de autenticación con tokens (JWT) usando Laravel Sanctum. Incluye:
- ✅ Endpoint de login para obtener token
- ✅ Rutas protegidas que requieren token válido
- ✅ Cliente web para interactuar con la API
- ✅ Gestión de Spots y Registros

## Instrucciones de Uso

### Backend

1. **Instalar dependencias**
   ```bash
   composer install
   ```

2. **Configurar base de datos**
   ```bash
   php artisan migrate:fresh --seed
   ```
   Esto crea un usuario de prueba:
   - Email: test@example.com
   - Password: password

3. **Iniciar servidor**
   ```bash
   php artisan serve --port=8000
   ```

### Frontend

El cliente web está disponible en: http://localhost:8000/client.html

#### Características:
- Formulario de login
- Listado de spots
- Listado de registros
- Crear, actualizar y eliminar elementos
- Almacenamiento de tokens en localStorage

### API Endpoints

#### Autenticación (Públicas)
- `POST /api/login` - Obtener token
  ```json
  {
    "email": "test@example.com",
    "password": "password"
  }
  ```

#### Protegidas (Requieren header `Authorization: Bearer {token}`)

**Spots:**
- `GET /api/spots` - Listar todos
- `POST /api/spots` - Crear
- `GET /api/spots/{id}` - Ver uno
- `PUT /api/spots/{id}` - Actualizar
- `DELETE /api/spots/{id}` - Eliminar

**Registros:**
- `GET /api/registros` - Listar todos
- `POST /api/registros` - Crear
- `GET /api/registros/{id}` - Ver uno
- `PUT /api/registros/{id}` - Actualizar
- `DELETE /api/registros/{id}` - Eliminar

**Usuario:**
- `GET /api/me` - Obtener datos del usuario autenticado
- `POST /api/logout` - Cerrar sesión

## Pruebas con Postman

### 1. Test sin Token (Unauthorized)
- **Método:** GET
- **URL:** http://localhost:8000/api/spots
- **Resultado esperado:** 401 Unauthorized

### 2. Login
- **Método:** POST
- **URL:** http://localhost:8000/api/login
- **Body (JSON):**
  ```json
  {
    "email": "test@example.com",
    "password": "password"
  }
  ```
- **Resultado esperado:** 200 OK con token

### 3. Acceso con Token
- **Método:** GET
- **URL:** http://localhost:8000/api/spots
- **Headers:**
  ```
  Authorization: Bearer {token_obtenido_en_login}
  ```
- **Resultado esperado:** 200 OK con lista de spots

## Estructura de Directorios

```
AE31-OnBoard/
├── app/Http/Controllers/
│   ├── AuthController.php          (Nuevo)
│   ├── SpotController.php          (Métodos API agregados)
│   └── CsvController.php           (Métodos API agregados)
├── database/
│   ├── migrations/
│   └── database.sqlite
├── public/
│   └── client.html                 (Nuevo - Frontend)
├── routes/
│   └── api.php                     (Protección con auth:sanctum)
└── .env
```

## Tecnologías

- **Backend:** Laravel 11 + Sanctum (Gestión de Tokens)
- **Frontend:** HTML + CSS + JavaScript vanilla
- **Base de datos:** SQLite
- **Autenticación:** Token-based (Sanctum)

## Seguridad

- ✅ Contraseñas hasheadas con bcrypt
- ✅ Tokens revocables
- ✅ Validación de entrada en servidor
- ✅ Protección CSRF
- ✅ Endpoints públicos limitados

## Notas

- El cliente almacena el token en localStorage
- Los tokens son revocables mediante logout
- Cada usuario puede tener múltiples tokens activos
- Los datos en la API responden en JSON

## Autor
Alumno - Actividad AE5.3
