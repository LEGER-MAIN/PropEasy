# Módulo de Autenticación y Seguridad - PropEasy

## 📋 Descripción General

El módulo de autenticación de PropEasy proporciona un sistema completo de gestión de usuarios con autenticación segura, validación por token, recuperación de contraseñas y control de acceso basado en roles (RBAC).

## 🏗️ Arquitectura del Módulo

### Estructura de Archivos

```
app/
├── controllers/
│   └── AuthController.php          # Controlador principal de autenticación
├── models/
│   └── UserModel.php               # Modelo de gestión de usuarios
└── views/auth/
    ├── login.php                   # Vista de inicio de sesión
    ├── register.php                # Vista de registro
    ├── forgot-password.php         # Vista de recuperación de contraseña
    └── reset-password.php          # Vista de reseteo de contraseña

public/assets/js/
└── auth.js                         # JavaScript para funcionalidades de autenticación

database/
└── users.sql                       # Script de creación de tabla de usuarios

tests/
└── test_auth.php                   # Archivo de pruebas del módulo
```

## 🔧 Funcionalidades Implementadas

### 1. Registro de Usuarios
- **Formulario de registro** con validación completa
- **Validación por email** mediante token único
- **Roles diferenciados**: Cliente, Agente, Administrador
- **Validación de contraseñas** con requisitos de seguridad

### 2. Autenticación de Usuarios
- **Login seguro** con validación de credenciales
- **Verificación de cuenta activa** antes del acceso
- **Gestión de sesiones** con datos de usuario
- **Redirección automática** según el rol del usuario

### 3. Validación de Cuentas
- **Token único** generado automáticamente
- **Email de validación** con enlace de activación
- **Activación de cuenta** mediante token
- **Expiración de tokens** por seguridad

### 4. Recuperación de Contraseñas
- **Solicitud de recuperación** por email
- **Token temporal** con expiración de 1 hora
- **Reseteo seguro** de contraseñas
- **Validación de tokens** antes del reseteo

### 5. Control de Acceso
- **Middleware de autenticación** para proteger rutas
- **Verificación de roles** para acceso específico
- **Redirección automática** para usuarios no autenticados
- **Gestión de permisos** por tipo de usuario

## 🗄️ Estructura de Base de Datos

### Tabla `users`

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','agent','client') NOT NULL DEFAULT 'client',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `validation_token` varchar(64) DEFAULT NULL,
  `password_reset_token` varchar(64) DEFAULT NULL,
  `password_reset_expires` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);
```

## 🚀 Uso del Módulo

### 1. Registro de Usuario

```php
// En el controlador
$userData = [
    'name' => 'Juan Pérez',
    'email' => 'juan@example.com',
    'password' => 'mi_contraseña_segura',
    'role' => 'client'
];

$result = $this->userModel->registerUser($userData);

if ($result['success']) {
    // Usuario registrado exitosamente
    // Se envía email de validación automáticamente
} else {
    // Error en el registro
    $error = $result['message'];
}
```

### 2. Autenticación

```php
// En el controlador
$result = $this->userModel->authenticate($email, $password);

if ($result['success']) {
    // Crear sesión del usuario
    $_SESSION['user_id'] = $result['user']['id'];
    $_SESSION['user_role'] = $result['user']['role'];
    $_SESSION['user_authenticated'] = true;
    
    // Redirigir según el rol
    $this->redirectByRole($result['user']['role']);
} else {
    // Error de autenticación
    $error = $result['message'];
}
```

### 3. Validación de Cuenta

```php
// En el controlador
$token = $_GET['token'];
$result = $this->userModel->validateAccount($token);

if ($result['success']) {
    // Cuenta activada exitosamente
    $success = $result['message'];
} else {
    // Error en la validación
    $error = $result['message'];
}
```

### 4. Recuperación de Contraseña

```php
// Iniciar recuperación
$result = $this->userModel->initiatePasswordRecovery($email);

if ($result['success']) {
    // Email de recuperación enviado
} else {
    // Error en el proceso
}

// Reseteo de contraseña
$result = $this->userModel->resetPassword($token, $newPassword);

if ($result['success']) {
    // Contraseña actualizada
} else {
    // Error en el reseteo
}
```

### 5. Middleware de Autenticación

```php
// Proteger rutas que requieren autenticación
AuthController::requireAuth();

// Proteger rutas específicas por rol
AuthController::requireAdmin();    // Solo administradores
AuthController::requireAgent();    // Solo agentes
AuthController::requireClient();   // Solo clientes

// Obtener usuario actual
$currentUser = AuthController::getCurrentUser();
```

## 🔒 Seguridad Implementada

### 1. Hashing de Contraseñas
- **Algoritmo**: `password_hash()` con `PASSWORD_DEFAULT`
- **Verificación**: `password_verify()` para autenticación
- **Salt automático**: Generado por PHP automáticamente

### 2. Tokens de Seguridad
- **Generación**: `bin2hex(random_bytes(32))` para tokens únicos
- **Expiración**: Tokens de recuperación expiran en 1 hora
- **Limpieza**: Tokens se eliminan después de su uso

### 3. Validación de Entrada
- **Sanitización**: Limpieza de datos de entrada
- **Validación de email**: Verificación de formato
- **Validación de contraseñas**: Requisitos mínimos de seguridad
- **Prevención de SQL Injection**: Uso de prepared statements

### 4. Gestión de Sesiones
- **Inicio seguro**: `session_start()` con configuración segura
- **Datos de sesión**: Almacenamiento de información del usuario
- **Cierre seguro**: Limpieza completa al cerrar sesión
- **Cookies seguras**: Configuración de cookies de sesión

## 📧 Sistema de Emails

### 1. Email de Validación
- **Asunto**: "Activa tu cuenta en PropEasy"
- **Contenido**: HTML con enlace de activación
- **Token**: Incluido en el enlace de validación
- **Expiración**: 24 horas

### 2. Email de Recuperación
- **Asunto**: "Recuperación de contraseña - PropEasy"
- **Contenido**: HTML con enlace de reseteo
- **Token**: Incluido en el enlace de recuperación
- **Expiración**: 1 hora

## 🧪 Pruebas del Módulo

### Ejecutar Pruebas
1. Acceder a: `http://propeasy.test/tests/test_auth.php`
2. Verificar que todas las pruebas pasen
3. Revisar la información del sistema

### Pruebas Incluidas
- ✅ Conexión a base de datos
- ✅ Estructura de tabla de usuarios
- ✅ Instanciación del modelo
- ✅ Usuarios de prueba
- ✅ Autenticación válida e inválida
- ✅ Registro de usuarios
- ✅ Validación de cuentas
- ✅ Recuperación de contraseñas
- ✅ Extensiones PHP requeridas

## 🔧 Configuración

### 1. Configuración de Base de Datos
```php
// config/config.php
class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'propeasy';
}
```

### 2. Configuración de Email
```php
// En UserModel.php - métodos sendValidationEmail() y sendPasswordRecoveryEmail()
// Configurar servidor SMTP o usar mail() de PHP
```

### 3. Configuración de Rutas
```php
// core/Router.php
// Las rutas de autenticación están configuradas automáticamente
```

## 📱 URLs del Módulo

### Rutas Públicas
- **Login**: `/auth/login`
- **Registro**: `/auth/register`
- **Recuperar Contraseña**: `/auth/forgotPassword`
- **Reseteo de Contraseña**: `/auth/resetPassword?token=XXX`
- **Validación de Cuenta**: `/auth/validate?token=XXX`

### Rutas de Procesamiento
- **Procesar Login**: `/auth/processLogin` (POST)
- **Procesar Registro**: `/auth/processRegister` (POST)
- **Procesar Recuperación**: `/auth/processForgotPassword` (POST)
- **Procesar Reseteo**: `/auth/processResetPassword` (POST)
- **Cerrar Sesión**: `/auth/logout`

## 👥 Roles de Usuario

### 1. Cliente (`client`)
- **Acceso**: Panel de cliente
- **Funcionalidades**: Ver propiedades, solicitar compra, chat con agentes
- **Registro**: Público

### 2. Agente (`agent`)
- **Acceso**: Panel de agente
- **Funcionalidades**: Gestionar propiedades, clientes, citas
- **Registro**: Público (con validación)

### 3. Administrador (`admin`)
- **Acceso**: Panel de administración
- **Funcionalidades**: Gestión completa del sistema
- **Registro**: Solo por base de datos

## 🚨 Manejo de Errores

### Errores Comunes
1. **Email ya registrado**: Validación de unicidad
2. **Token inválido**: Verificación de tokens
3. **Cuenta no activada**: Verificación de estado
4. **Credenciales incorrectas**: Validación de autenticación
5. **Token expirado**: Verificación de fechas

### Mensajes de Error
- Todos los errores se muestran en español
- Mensajes claros y específicos
- Redirección automática en caso de error
- Logs de errores para debugging

## 🔄 Flujo de Trabajo

### 1. Registro de Usuario
```
Usuario llena formulario → Validación → Creación en BD → Email de validación → Activación
```

### 2. Autenticación
```
Usuario ingresa credenciales → Validación → Verificación de cuenta → Creación de sesión → Redirección
```

### 3. Recuperación de Contraseña
```
Usuario solicita recuperación → Validación de email → Token temporal → Email → Reseteo
```

## 📈 Métricas y Monitoreo

### Datos Recolectados
- **Usuarios registrados** por rol
- **Intentos de login** (exitosos/fallidos)
- **Validaciones de cuenta** completadas
- **Recuperaciones de contraseña** solicitadas
- **Último acceso** de cada usuario

### Reportes Disponibles
- Dashboard de administrador con estadísticas
- Logs de actividad de usuarios
- Métricas de seguridad

## 🔮 Mejoras Futuras

### Funcionalidades Planificadas
1. **Autenticación de dos factores (2FA)**
2. **Login con redes sociales** (Google, Facebook)
3. **Verificación por SMS**
4. **Sesiones múltiples**
5. **Auditoría de seguridad**

### Optimizaciones
1. **Caché de sesiones**
2. **Rate limiting** para intentos de login
3. **Logs de seguridad** más detallados
4. **Notificaciones push**

## 📞 Soporte

### Documentación Adicional
- [Guía de Instalación](../README.md)
- [API Documentation](../docs/API.md)
- [Troubleshooting](../docs/TROUBLESHOOTING.md)

### Contacto
- **Desarrollador**: Equipo PropEasy
- **Email**: soporte@propeasy.com
- **Documentación**: [GitHub Wiki](https://github.com/propeasy/docs)

---

**Versión**: 1.0.0  
**Última actualización**: Mayo 2025  
**Compatibilidad**: PHP 8.2+, MySQL 8.0+ 