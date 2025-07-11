-- Tabla de propiedades para PropEasy
CREATE TABLE IF NOT EXISTS `properties` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `precio` DECIMAL(15,2) NOT NULL,
  `ubicacion` VARCHAR(255) NOT NULL,
  `ciudad` VARCHAR(100) NOT NULL,
  `sector` VARCHAR(100) DEFAULT NULL,
  `area` INT(11) DEFAULT NULL,
  `habitaciones` INT(11) DEFAULT NULL,
  `banos` INT(11) DEFAULT NULL,
  `imagenes` TEXT DEFAULT NULL,
  `estado` ENUM('pendiente','activa','vendida','rechazada') NOT NULL DEFAULT 'pendiente',
  `token_validacion` VARCHAR(64) DEFAULT NULL,
  `cliente_id` INT(11) DEFAULT NULL,
  `agente_id` INT(11) DEFAULT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_validacion` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estado` (`estado`),
  KEY `cliente_id` (`cliente_id`),
  KEY `agente_id` (`agente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Propiedades registradas en PropEasy'; 

ALTER TABLE properties
  ADD COLUMN parqueos INT DEFAULT 0 AFTER banos,
  ADD COLUMN caracteristicas TEXT AFTER parqueos,
  MODIFY COLUMN imagenes TEXT, -- para guardar JSON de imágenes
  ADD COLUMN video_url VARCHAR(255) DEFAULT NULL AFTER imagenes,
  ADD COLUMN plano_url VARCHAR(255) DEFAULT NULL AFTER video_url,
  ADD COLUMN latitud DECIMAL(12,8) DEFAULT NULL AFTER plano_url,
  ADD COLUMN longitud DECIMAL(12,8) DEFAULT NULL AFTER latitud,
  ADD COLUMN fecha_publicacion TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER fecha_creacion;

  ALTER TABLE properties
  ADD COLUMN superficie_construida INT DEFAULT NULL AFTER area;

  -- Actualizar precisión de coordenadas geográficas
  ALTER TABLE properties
  MODIFY COLUMN latitud DECIMAL(12,8) DEFAULT NULL,
  MODIFY COLUMN longitud DECIMAL(12,8) DEFAULT NULL;

-- Tabla para consultas de contacto
CREATE TABLE IF NOT EXISTS `property_contacts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `newsletter` BOOLEAN DEFAULT FALSE,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `status` ENUM('nuevo','leido','respondido') DEFAULT 'nuevo',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `status` (`status`),
  KEY `fecha_creacion` (`fecha_creacion`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para citas agendadas
CREATE TABLE IF NOT EXISTS `property_appointments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `status` ENUM('pendiente','confirmada','completada','cancelada') DEFAULT 'pendiente',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `appointment_date` (`appointment_date`),
  KEY `status` (`status`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para estadísticas de propiedades
CREATE TABLE IF NOT EXISTS `property_views` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `referer` VARCHAR(255) DEFAULT NULL,
  `session_id` VARCHAR(255) DEFAULT NULL,
  `fecha_vista` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `ip_address` (`ip_address`),
  KEY `fecha_vista` (`fecha_vista`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para reportes de propiedades
CREATE TABLE IF NOT EXISTS `property_reports` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `reporter_email` VARCHAR(255) DEFAULT NULL,
  `reason` ENUM('informacion_incorrecta','spam','contenido_inapropiado','precio_sospechoso','otro') NOT NULL,
  `description` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `status` ENUM('pendiente','revisado','resuelto') DEFAULT 'pendiente',
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `status` (`status`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para información de agentes
CREATE TABLE IF NOT EXISTS `agents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `title` VARCHAR(100) DEFAULT 'Agente Inmobiliario',
  `description` TEXT DEFAULT NULL,
  `specialties` TEXT DEFAULT NULL,
  `availability` VARCHAR(100) DEFAULT 'Lun-Vie 9:00-18:00',
  `whatsapp` VARCHAR(20) DEFAULT NULL,
  `rating` DECIMAL(3,2) DEFAULT 0.00,
  `total_reviews` INT DEFAULT 0,
  `total_sales` INT DEFAULT 0,
  `active` BOOLEAN DEFAULT TRUE,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user_id` (`user_id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar agente por defecto
INSERT INTO `agents` (`name`, `email`, `phone`, `title`, `description`, `availability`, `whatsapp`, `rating`, `total_reviews`, `total_sales`) 
VALUES 
('Juan Pérez', 'juan.perez@propeasy.com', '+56 9 1234 5678', 'Agente Inmobiliario Senior', 'Especialista en propiedades residenciales con más de 10 años de experiencia', 'Lun-Vie 9:00-18:00', '+56912345678', 4.5, 28, 45),
('María González', 'maria.gonzalez@propeasy.com', '+56 9 8765 4321', 'Agente Inmobiliario', 'Experta en propiedades comerciales y departamentos de lujo', 'Lun-Sab 8:00-19:00', '+56987654321', 4.8, 35, 62)
ON DUPLICATE KEY UPDATE name=VALUES(name);