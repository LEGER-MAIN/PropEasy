# PropEasy - Sistema de Gestión Inmobiliaria

## 📋 Descripción General

PropEasy es una plataforma web completa para la gestión inmobiliaria desarrollada en PHP con arquitectura MVC. El sistema permite a agentes inmobiliarios gestionar propiedades, clientes, citas y comunicaciones de manera eficiente.

## 🏗️ Arquitectura del Proyecto

```
propeasy/
├── app/                    # Aplicación MVC
│   ├── controllers/        # Controladores
│   ├── models/            # Modelos de datos
│   └── views/             # Vistas
├── config/                # Configuración
├── core/                  # Núcleo del framework
├── public/                # Documento raíz web
│   ├── assets/            # Recursos estáticos
│   └── index.php          # Punto de entrada
├── tests/                 # Archivos de prueba
└── wireframes/            # Diseños y mockups
```

## 🚀 Características Principales

### 👥 Gestión de Usuarios
- **Registro y Login** de usuarios
- **Perfiles diferenciados**: Administradores, Agentes, Clientes
- **Sistema de autenticación** seguro
- **Gestión de permisos** por roles

### 🏠 Gestión de Propiedades
- **Publicación de propiedades** con imágenes
- **Búsqueda avanzada** con filtros
- **Detalles completos** de cada propiedad
- **Sistema de favoritos** para clientes
- **Estados de propiedad** (Disponible, Vendida, Alquilada)

### 📋 Solicitudes de Compra
- **Formulario de solicitud** para clientes interesados
- **Seguimiento de estado** (Nuevo, En Revisión, Cita Agendada, Cerrado)
- **Gestión por agentes** con notas y actualizaciones
- **Panel de cliente** para ver sus solicitudes
- **Estadísticas y reportes** de solicitudes

### 👨‍💼 Panel de Agentes
- **Dashboard personalizado** con estadísticas
- **Gestión de clientes** y leads
- **Agenda de citas** integrada
- **Sistema de mensajería** interno
- **Reportes de ventas** y rendimiento

### 🎯 Panel de Administración
- **Gestión de usuarios** del sistema
- **Configuración general** de la plataforma
- **Reportes y estadísticas** detalladas
- **Validación de solicitudes** de venta
- **Sistema de notificaciones**

### 💬 Comunicación
- **Chat interno** entre agentes y clientes
- **Sistema de notificaciones** en tiempo real
- **Email automático** para confirmaciones
- **Mensajería push** para alertas importantes

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8.3+ con arquitectura MVC
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Base de Datos**: MySQL 8.0+
- **Servidor Web**: Apache 2.4+
- **Framework CSS**: Bootstrap 5.3
- **Iconos**: Font Awesome 6.4
- **Desarrollo Local**: Laragon

## 📦 Instalación y Configuración

### Requisitos Previos
- PHP 8.3 o superior
- MySQL 8.0 o superior
- Apache 2.4 o superior
- Laragon (recomendado para desarrollo)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [url-del-repositorio]
   cd propeasy
   ```

2. **Configurar el servidor web**
   - Configurar el documento raíz en `public/`
   - Habilitar mod_rewrite en Apache
   - Configurar dominio virtual: `propeasy.test`

3. **Configurar la base de datos**
   - Crear base de datos MySQL
   - Importar esquema inicial
   - Configurar credenciales en `config/config.php`

4. **Configurar archivo hosts**
   ```
   127.0.0.1 propeasy.test
   ```

5. **Verificar permisos**
   - Asegurar permisos de escritura en carpetas necesarias
   - Configurar permisos de archivos de configuración

## 🌐 URLs de Acceso

### Desarrollo Local
- **Página Principal**: `http://propeasy.test/`
- **Panel de Agente**: `http://propeasy.test/agent/dashboard`
- **Panel de Admin**: `http://propeasy.test/admin/dashboard`
- **Propiedades**: `http://propeasy.test/properties`
- **Blog**: `http://propeasy.test/blog`

### Archivos de Prueba
- **Prueba General**: `http://propeasy.test/tests/test_all_pages.php`
- **Prueba Sitemap**: `http://propeasy.test/tests/test_sitemap.php`
- **Prueba Blog**: `http://propeasy.test/tests/test_blog.php`
- **Prueba Careers**: `http://propeasy.test/tests/test_careers.php`
- **Prueba FAQ**: `http://propeasy.test/tests/test_faq.php`

## 📱 Páginas y Funcionalidades

### Páginas Públicas
- **Home**: Página principal con propiedades destacadas
- **About**: Información sobre la empresa
- **Properties**: Listado y búsqueda de propiedades
- **Property Detail**: Detalles completos de una propiedad
- **Blog**: Artículos y noticias del sector
- **Contact**: Formulario de contacto
- **FAQ**: Preguntas frecuentes
- **Careers**: Oportunidades laborales
- **Terms**: Términos y condiciones
- **Privacy**: Política de privacidad
- **Sitemap**: Mapa del sitio

### Páginas de Autenticación
- **Login**: Inicio de sesión
- **Register**: Registro de usuarios
- **Password Recovery**: Recuperación de contraseña

### Paneles de Usuario
- **Dashboard Admin**: Panel de administración
- **Dashboard Agent**: Panel de agente inmobiliario
- **Agent Properties**: Gestión de propiedades del agente
- **Agent Clients**: Gestión de clientes
- **Agent Appointments**: Agenda de citas
- **Agent Messages**: Sistema de mensajería
- **Solicitudes de Compra**: Gestión de solicitudes de clientes

## 🔧 Configuración del Sistema

### Variables de Entorno
```php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'propeasy');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_URL', 'http://propeasy.test');
define('SITE_NAME', 'PropEasy');
```

### Configuración de Apache
```apache
<VirtualHost *:80>
    DocumentRoot "C:/laragon/www/propeasy/public"
    ServerName propeasy.test
    <Directory "C:/laragon/www/propeasy/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## 🧪 Testing

El proyecto incluye archivos de prueba completos en la carpeta `tests/`:

- **test_all_pages.php**: Prueba general de todas las páginas
- **test_sitemap.php**: Prueba del sistema de sitemap
- **test_blog.php**: Prueba del sistema de blog
- **test_careers.php**: Prueba de la página de carreras
- **test_faq.php**: Prueba de la página FAQ

Para ejecutar las pruebas:
1. Acceder a `http://propeasy.test/tests/`
2. Seleccionar el archivo de prueba deseado
3. Verificar que todas las funcionalidades estén operativas

## 📊 Estructura de Base de Datos

### Tablas Principales
- **users**: Usuarios del sistema
- **properties**: Propiedades inmobiliarias
- **solicitudes_compra**: Solicitudes de compra de clientes
- **clients**: Clientes registrados
- **appointments**: Citas programadas
- **messages**: Mensajes del sistema
- **blog_posts**: Artículos del blog
- **categories**: Categorías de propiedades

## 🔒 Seguridad

- **Autenticación segura** con hash de contraseñas
- **Validación de entrada** en todos los formularios
- **Protección CSRF** en formularios críticos
- **Sanitización de datos** antes de almacenar
- **Control de acceso** basado en roles
- **Logs de auditoría** para acciones importantes

## 📈 Optimización

- **Caché de consultas** para mejorar rendimiento
- **Compresión de imágenes** automática
- **Minificación de CSS/JS** en producción
- **Lazy loading** para imágenes
- **Paginación** en listados grandes
- **Índices de base de datos** optimizados

## 🤝 Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Para soporte técnico o consultas:
- **Email**: soporte@propeasy.cl
- **Documentación**: [docs.propeasy.cl](https://docs.propeasy.cl)
- **Issues**: [GitHub Issues](https://github.com/propeasy/issues)

---

**PropEasy** - Simplificando la gestión inmobiliaria desde 2024 "# PropEasy" 
"# PropEasy" 
"# PropEasy" 
"# PropEasy" 
"# PropEasy" 
