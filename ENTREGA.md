# AE5.3 - ENTREGA

## 📋 Descripción del Proyecto

Sistema de autenticación con tokens JWT implementado con Laravel Sanctum. Incluye:
- ✅ Backend API REST protegido con tokens
- ✅ Cliente web interactivo
- ✅ Gestión completa de Spots y Registros
- ✅ Pruebas documentadas con Postman

---

## 📦 Archivos Entregables

### 1. **Backend** - Repositorio Principal
- **Ubicación:** `c:\Users\thebo\Desktop\clase\segundo\OnBoard-individual\AE31-OnBoard`
- **Descripción:** Aplicación Laravel con autenticación basada en tokens
- **Archivos clave:**
  - `app/Http/Controllers/AuthController.php` - Controlador de autenticación
  - `app/Http/Controllers/SpotController.php` - Métodos API para Spots
  - `app/Http/Controllers/CsvController.php` - Métodos API para Registros
  - `routes/api.php` - Rutas protegidas con auth:sanctum
  - `.env` - Archivo de configuración

### 2. **Frontend** - Cliente Web
- **Ubicación:** `public/client.html`
- **Descripción:** Interfaz web completa para interactuar con la API
- **Características:**
  - Formulario de login interactivo
  - Gestión de tokens con localStorage
  - Listado de spots con CRUD
  - Listado de registros con CRUD
  - Interfaz responsive y amigable

### 3. **Documentación**

#### 📄 README_AE5.3.md
- Instrucciones de instalación
- Guía de uso
- Descripción de endpoints
- Información técnica

#### 📄 Pruebas_AE5.3.html
- 13 pruebas documentadas
- Capturas de resultados
- Validación de seguridad
- Pruebas de funcionalidad

#### 📄 AE5.3_Postman_Collection.json
- Colección completa de Postman
- 4 secciones de pruebas
- Variables de entorno
- Tests automatizados

#### 📄 PRUEBAS_API.md
- Guía rápida de pruebas
- Casos de uso
- Respuestas esperadas

---

## 🚀 Instrucciones de Instalación y Uso

### Paso 1: Instalar Dependencias
```bash
cd AE31-OnBoard
composer install
```

### Paso 2: Configurar Base de Datos
```bash
php artisan migrate:fresh --seed
```
Crea usuario de prueba:
- Email: test@example.com
- Password: password

### Paso 3: Iniciar Servidor
```bash
php artisan serve --port=8000
```

### Paso 4: Acceder al Cliente
Abrir en navegador: http://localhost:8000/client.html

---

## 🔐 Credenciales de Prueba

```
Email:     test@example.com
Password:  password
```

---

## 📊 API Endpoints

### Autenticación (Públicas)

#### Login
```
POST /api/login
Body: { "email": "test@example.com", "password": "password" }
Response: { "token": "...", "user": {...} }
```

### Protegidas (Requieren: `Authorization: Bearer {token}`)

#### Spots
- `GET /api/spots` - Listar
- `POST /api/spots` - Crear
- `GET /api/spots/{id}` - Ver
- `PUT /api/spots/{id}` - Actualizar
- `DELETE /api/spots/{id}` - Eliminar

#### Registros
- `GET /api/registros` - Listar
- `POST /api/registros` - Crear
- `GET /api/registros/{id}` - Ver
- `PUT /api/registros/{id}` - Actualizar
- `DELETE /api/registros/{id}` - Eliminar

#### Usuario
- `GET /api/me` - Obtener usuario autenticado
- `POST /api/logout` - Cerrar sesión

---

## ✅ Funcionalidades Implementadas

### Seguridad
- ✅ Protección de rutas con middleware auth:sanctum
- ✅ Tokens JWT revocables
- ✅ Validación de credenciales
- ✅ Manejo de autenticación fallida (401)
- ✅ Gestión segura de tokens

### API
- ✅ Endpoints RESTful completos
- ✅ Validación de inputs en servidor
- ✅ Manejo de errores con códigos HTTP
- ✅ Respuestas JSON estructuradas
- ✅ Mensajes de error descriptivos

### Frontend
- ✅ Interfaz intuitiva y responsiva
- ✅ Almacenamiento de tokens en localStorage
- ✅ CRUD interactivo (Create, Read, Update, Delete)
- ✅ Validación de formularios
- ✅ Mensajes de estado (éxito, error, info)

---

## 🧪 Pruebas Realizadas

### Pruebas de Seguridad
- ✅ GET /api/spots sin token → 401 Unauthorized
- ✅ GET /api/registros sin token → 401 Unauthorized

### Pruebas de Autenticación
- ✅ Login con credenciales válidas → 200 OK + token
- ✅ Login con credenciales inválidas → 422 Error

### Pruebas de Acceso Protegido
- ✅ GET /api/spots con token → 200 OK
- ✅ POST /api/spots con token → 201 Created
- ✅ GET /api/me con token → 200 OK

### Pruebas de Cliente Web
- ✅ Formulario de login funcional
- ✅ Información de usuario visible
- ✅ Listar spots en tabla
- ✅ Crear nuevo spot
- ✅ Gestionar registros
- ✅ Tokens se almacenan correctamente

### Resultado Total
**13/13 pruebas EXITOSAS** ✓

---

## 🛠️ Tecnologías Utilizadas

- **Backend:** Laravel 11 + Sanctum
- **Frontend:** HTML5 + CSS3 + JavaScript vanilla
- **Base de datos:** SQLite
- **Autenticación:** Token-based (JWT con Sanctum)
- **Herramientas:** Postman, Git

---

## 📝 Estructura de Directorios

```
AE31-OnBoard/
├── app/Http/Controllers/
│   ├── AuthController.php (NUEVO)
│   ├── SpotController.php (MODIFICADO)
│   └── CsvController.php (MODIFICADO)
├── database/
│   ├── database.sqlite (CREADO)
│   └── migrations/
├── public/
│   ├── client.html (NUEVO - Frontend)
│   └── Pruebas_AE5.3.html (NUEVO)
├── routes/
│   └── api.php (MODIFICADO - Protección)
├── .env (CREADO)
├── README_AE5.3.md (NUEVO)
├── AE5.3_Postman_Collection.json (NUEVO)
└── PRUEBAS_API.md (NUEVO)
```

---

## 🎯 Conclusiones

Se ha implementado exitosamente un sistema completo de autenticación con tokens:

1. **Backend:** API REST completamente protegida con Sanctum
2. **Frontend:** Cliente web funcional e intuitivo
3. **Seguridad:** Validación en múltiples niveles
4. **Documentación:** Completa y detallada
5. **Testing:** Pruebas exhaustivas documentadas

El sistema está listo para producción con pequeños ajustes adicionales recomendados (refresh tokens, rate limiting, 2FA).

---

## 📌 URLs Importantes

- **API:** http://localhost:8000/api
- **Cliente:** http://localhost:8000/client.html
- **Documentación:** Pruebas_AE5.3.html (abrir en navegador)
- **Postman:** Importar AE5.3_Postman_Collection.json

---

## 👨‍💻 Autor

Alumno - Tarea AE5.3
Fecha: 21 de Marzo de 2026

---

## 📞 Notas para el Profesor

### Para Evaluar:
1. **Código Backend:** Revisar `app/Http/Controllers/AuthController.php` y protección en `routes/api.php`
2. **Documentación HTML:** Abrir `public/Pruebas_AE5.3.html` en navegador para ver pruebas
3. **Cliente Web:** Acceder a `http://localhost:8000/client.html` (requiere servidor corriendo)
4. **Postman:** Importar colección y ejecutar pruebas con usuario test@example.com/password

### Credenciales para Pruebas:
- Email: test@example.com
- Password: password

### Para Convertir a PDF:
- Pruebas_AE5.3.html → Imprimir desde navegador (Ctrl+P) → Guardar como PDF
- Documentación completa disponible en README_AE5.3.md
