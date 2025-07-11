<?php
/**
 * Constantes de la aplicación PropEasy
 */

// Información de la aplicación
define('APP_NAME', 'PropEasy');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Plataforma inmobiliaria moderna y eficiente');
define('APP_AUTHOR', 'PropEasy Team');
define('APP_URL', 'https://propeasy.com');

// Configuración de la base de datos
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// Configuración de archivos
define('UPLOAD_PATH', __DIR__ . '/../public/assets/uploads/');
define('UPLOAD_URL', '/assets/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx']);

// Configuración de imágenes
define('IMAGE_QUALITY', 85);
define('THUMBNAIL_WIDTH', 300);
define('THUMBNAIL_HEIGHT', 200);
define('LARGE_IMAGE_WIDTH', 1200);
define('LARGE_IMAGE_HEIGHT', 800);

// Estados de propiedades
define('PROPERTY_STATUS_PENDING', 'pendiente');
define('PROPERTY_STATUS_ACTIVE', 'activa');
define('PROPERTY_STATUS_SOLD', 'vendida');
define('PROPERTY_STATUS_REJECTED', 'rechazada');

// Estados de citas
define('APPOINTMENT_STATUS_PENDING', 'pendiente');
define('APPOINTMENT_STATUS_CONFIRMED', 'confirmada');
define('APPOINTMENT_STATUS_COMPLETED', 'completada');
define('APPOINTMENT_STATUS_CANCELLED', 'cancelada');

// Estados de contactos
define('CONTACT_STATUS_NEW', 'nuevo');
define('CONTACT_STATUS_READ', 'leido');
define('CONTACT_STATUS_REPLIED', 'respondido');

// Estados de reportes
define('REPORT_STATUS_PENDING', 'pendiente');
define('REPORT_STATUS_REVIEWED', 'revisado');
define('REPORT_STATUS_RESOLVED', 'resuelto');

// Estados de solicitudes de compra
define('PURCHASE_REQUEST_STATUS_NEW', 'nuevo');
define('PURCHASE_REQUEST_STATUS_REVIEW', 'en_revision');
define('PURCHASE_REQUEST_STATUS_SCHEDULED', 'cita_agendada');
define('PURCHASE_REQUEST_STATUS_CLOSED', 'cerrado');

// Roles de usuario
define('USER_ROLE_CLIENT', 'client');
define('USER_ROLE_AGENT', 'agent');
define('USER_ROLE_ADMIN', 'admin');

// Tipos de propiedades
define('PROPERTY_TYPES', [
    'casa' => 'Casa',
    'departamento' => 'Departamento',
    'oficina' => 'Oficina',
    'terreno' => 'Terreno'
]);

// Ciudades disponibles
define('CITIES', [
    'santiago' => 'Santiago',
    'valparaiso' => 'Valparaíso',
    'concepcion' => 'Concepción',
    'la_serena' => 'La Serena',
    'antofagasta' => 'Antofagasta',
    'temuco' => 'Temuco',
    'rancagua' => 'Rancagua',
    'talca' => 'Talca',
    'arica' => 'Arica',
    'iquique' => 'Iquique'
]);

// Comunas de Santiago
define('SANTIAGO_COMMUNES', [
    'las_condes' => 'Las Condes',
    'vitacura' => 'Vitacura',
    'providencia' => 'Providencia',
    'nunoa' => 'Ñuñoa',
    'santiago_centro' => 'Santiago Centro',
    'la_reina' => 'La Reina',
    'lo_barnechea' => 'Lo Barnechea',
    'san_miguel' => 'San Miguel',
    'maipu' => 'Maipú',
    'pudahuel' => 'Pudahuel',
    'quilicura' => 'Quilicura',
    'huechuraba' => 'Huechuraba',
    'independencia' => 'Independencia',
    'recoleta' => 'Recoleta',
    'cerro_navia' => 'Cerro Navia',
    'quinta_normal' => 'Quinta Normal',
    'estacion_central' => 'Estación Central',
    'cerrillos' => 'Cerrillos',
    'maipú' => 'Maipú',
    'pedro_aguirre_cerda' => 'Pedro Aguirre Cerda',
    'san_joaquin' => 'San Joaquín',
    'san_ramon' => 'San Ramón',
    'la_cisterna' => 'La Cisterna',
    'el_bosque' => 'El Bosque',
    'san_bernardo' => 'San Bernardo',
    'calera_de_tango' => 'Calera de Tango',
    'puente_alto' => 'Puente Alto',
    'pirque' => 'Pirque',
    'san_jose_de_maipo' => 'San José de Maipo',
    'la_pintana' => 'La Pintana',
    'la_granja' => 'La Granja',
    'macul' => 'Macul',
    'penalolen' => 'Peñalolén',
    'la_florida' => 'La Florida'
]);

// Configuración de paginación
define('ITEMS_PER_PAGE', 12);
define('MAX_PAGINATION_LINKS', 10);

// Configuración de email
define('MAIL_FROM_NAME', APP_NAME);
define('MAIL_FROM_EMAIL', 'noreply@propeasy.com');
define('MAIL_SUPPORT_EMAIL', 'support@propeasy.com');
define('MAIL_CONTACT_EMAIL', 'contact@propeasy.com');

// Configuración de sesiones
define('SESSION_LIFETIME', 3600 * 24); // 24 horas
define('SESSION_NAME', 'PROPEASY_SESSION');

// Configuración de seguridad
define('BCRYPT_ROUNDS', 12);
define('TOKEN_LENGTH', 32);
define('PASSWORD_MIN_LENGTH', 8);

// Configuración de cache
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 hora
define('CACHE_PATH', __DIR__ . '/../storage/cache/');

// Configuración de logs
define('LOG_ENABLED', true);
define('LOG_PATH', __DIR__ . '/../storage/logs/');
define('LOG_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Configuración de API
define('API_VERSION', 'v1');
define('API_RATE_LIMIT', 100); // requests per hour
define('API_TOKEN_EXPIRY', 3600); // 1 hora

// Configuración de notificaciones
define('NOTIFICATION_ENABLED', true);
define('NOTIFICATION_CHANNELS', ['email', 'sms', 'push']);

// Configuración de redes sociales
define('SOCIAL_LINKS', [
    'facebook' => 'https://facebook.com/propeasy',
    'twitter' => 'https://twitter.com/propeasy',
    'instagram' => 'https://instagram.com/propeasy',
    'linkedin' => 'https://linkedin.com/company/propeasy',
    'youtube' => 'https://youtube.com/propeasy'
]);

// Configuración de contacto
define('CONTACT_INFO', [
    'phone' => '+56 2 2123 4567',
    'whatsapp' => '+56 9 1234 5678',
    'email' => 'contact@propeasy.com',
    'address' => 'Av. Providencia 1234, Providencia, Santiago',
    'hours' => 'Lunes a Viernes 9:00 - 18:00'
]);

// Configuración de mapas
define('GOOGLE_MAPS_API_KEY', '');
define('DEFAULT_MAP_ZOOM', 15);
define('DEFAULT_MAP_CENTER', [-33.4489, -70.6693]); // Santiago

// Configuración de moneda
define('CURRENCY_SYMBOL', '$');
define('CURRENCY_CODE', 'CLP');
define('CURRENCY_DECIMALS', 0);

// Configuración de fecha y hora
define('TIMEZONE', 'America/Santiago');
define('DATE_FORMAT', 'd/m/Y');
define('TIME_FORMAT', 'H:i');
define('DATETIME_FORMAT', 'd/m/Y H:i');

// Configuración de mantenimiento
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'Sitio en mantenimiento. Vuelve pronto.');

// Configuración de debug
define('DEBUG_MODE', false);
define('SHOW_ERRORS', false);
define('ERROR_REPORTING_LEVEL', E_ALL);

// Configuración de backup
define('BACKUP_ENABLED', true);
define('BACKUP_PATH', __DIR__ . '/../storage/backups/');
define('BACKUP_RETENTION_DAYS', 30);

// Configuración de SEO
define('SEO_DEFAULT_TITLE', 'PropEasy - Plataforma Inmobiliaria');
define('SEO_DEFAULT_DESCRIPTION', 'Encuentra tu propiedad ideal en PropEasy. Miles de propiedades disponibles con los mejores agentes inmobiliarios.');
define('SEO_DEFAULT_KEYWORDS', 'propiedades, inmobiliaria, casas, departamentos, arriendo, venta');
define('SEO_SITE_NAME', APP_NAME);

// Configuración de analytics
define('GOOGLE_ANALYTICS_ID', '');
define('FACEBOOK_PIXEL_ID', '');

// Configuración de CDN
define('CDN_ENABLED', false);
define('CDN_URL', '');

// Configuración de WebP
define('WEBP_ENABLED', true);
define('WEBP_QUALITY', 80);

// Configuración de compresión
define('GZIP_ENABLED', true);
define('MINIFY_CSS', true);
define('MINIFY_JS', true);

// Configuración de CORS
define('CORS_ENABLED', true);
define('CORS_ORIGINS', ['*']);
define('CORS_METHODS', ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']);
define('CORS_HEADERS', ['Content-Type', 'Authorization', 'X-Requested-With']);

// Configuración de SSL
define('FORCE_SSL', false);
define('SSL_REDIRECT', true);

// Configuración de límites
define('MAX_PROPERTIES_PER_USER', 100);
define('MAX_IMAGES_PER_PROPERTY', 10);
define('MAX_SEARCH_RESULTS', 1000);
define('MAX_EXPORT_RECORDS', 5000);

// Configuración de features
define('FEATURE_FAVORITES', true);
define('FEATURE_COMPARISONS', true);
define('FEATURE_SAVED_SEARCHES', true);
define('FEATURE_PROPERTY_ALERTS', true);
define('FEATURE_VIRTUAL_TOURS', true);
define('FEATURE_MORTGAGE_CALCULATOR', true);
define('FEATURE_PROPERTY_REPORTS', true);
define('FEATURE_AGENT_REVIEWS', true);
define('FEATURE_SOCIAL_SHARING', true);
define('FEATURE_MULTI_LANGUAGE', false);

// Configuración de terceros
define('RECAPTCHA_ENABLED', false);
define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET_KEY', '');

// Configuración de webhooks
define('WEBHOOK_ENABLED', false);
define('WEBHOOK_SECRET', '');
define('WEBHOOK_ENDPOINTS', []);

// Configuración de queue
define('QUEUE_ENABLED', false);
define('QUEUE_DRIVER', 'database');
define('QUEUE_CONNECTION', 'default');

// Configuración de broadcast
define('BROADCAST_ENABLED', false);
define('BROADCAST_DRIVER', 'pusher');

// Configuración de filesystem
define('FILESYSTEM_DRIVER', 'local');
define('FILESYSTEM_ROOT', __DIR__ . '/../storage/');

// Configuración de testing
define('TESTING_ENABLED', false);
define('TESTING_DATABASE', 'propeasy_test');
?> 