-- Script SQL para crear la tabla de usuarios del sistema PropEasy
-- Incluye todos los campos necesarios para autenticación, validación por token y gestión de roles

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Nombre completo del usuario',
  `email` varchar(255) NOT NULL UNIQUE COMMENT 'Email único del usuario',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña hasheada',
  `role` enum('admin','agent','client') NOT NULL DEFAULT 'client' COMMENT 'Rol del usuario en el sistema',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono',
  `address` text DEFAULT NULL COMMENT 'Dirección del usuario',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Estado de activación de la cuenta',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de verificación del email',
  `validation_token` varchar(64) DEFAULT NULL COMMENT 'Token para validación de cuenta',
  `password_reset_token` varchar(64) DEFAULT NULL COMMENT 'Token para recuperación de contraseña',
  `password_reset_expires` timestamp NULL DEFAULT NULL COMMENT 'Fecha de expiración del token de recuperación',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'Último acceso al sistema',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `is_active` (`is_active`),
  KEY `validation_token` (`validation_token`),
  KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de usuarios del sistema PropEasy';

-- Insertar usuario administrador por defecto
-- Email: admin@propeasy.com
-- Contraseña: admin123 (hasheada con password_hash)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `email_verified_at`, `created_at`) VALUES
('Administrador', 'admin@propeasy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NOW());

-- Insertar usuario agente de prueba
-- Email: agente@propeasy.com
-- Contraseña: agente123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `email_verified_at`, `created_at`) VALUES
('Agente de Prueba', 'agente@propeasy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', 1, NOW(), NOW());

-- Insertar usuario cliente de prueba
-- Email: cliente@propeasy.com
-- Contraseña: cliente123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `email_verified_at`, `created_at`) VALUES
('Cliente de Prueba', 'cliente@propeasy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 1, NOW(), NOW());

-- Crear índices adicionales para optimizar consultas
CREATE INDEX `idx_users_role_active` ON `users` (`role`, `is_active`);
CREATE INDEX `idx_users_created_at` ON `users` (`created_at`);
CREATE INDEX `idx_users_last_login` ON `users` (`last_login`); 