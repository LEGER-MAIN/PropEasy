<?php
// Configuración de ejemplo para la base de datos
// Copiar este archivo como config.php y ajustar las credenciales

// Configuración de error reporting para desarrollo
// En producción cambiar a: error_reporting(0); ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');

class Database {
    private static $instance = null;
    private $conn;

    // Configuración de la base de datos
    private $host = 'localhost';        // Host de la base de datos
    private $user = 'tu_usuario';       // Usuario de la base de datos
    private $pass = 'tu_contraseña';    // Contraseña de la base de datos
    private $name = 'propeasy';         // Nombre de la base de datos

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if ($this->conn->connect_error) {
            die('Error de conexión: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}

// Configuración adicional del sistema
define('SITE_URL', 'http://localhost/propeasy');
define('SITE_NAME', 'PropEasy');
define('ADMIN_EMAIL', 'admin@propeasy.com');

// Configuración de correo (para PHPMailer)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'tu_email@gmail.com');
define('MAIL_PASSWORD', 'tu_contraseña_de_aplicacion');
define('MAIL_FROM_NAME', 'PropEasy');

// Configuración de seguridad
define('SESSION_TIMEOUT', 3600); // 1 hora
define('PASSWORD_MIN_LENGTH', 8);
define('TOKEN_EXPIRY', 3600); // 1 hora para tokens de recuperación

// Configuración de archivos
define('UPLOAD_DIR', __DIR__ . '/../public/assets/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
?> 