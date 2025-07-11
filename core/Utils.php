<?php

/**
 * Clase de utilidades para la aplicación PropEasy
 */
class Utils
{
    /**
     * Formatea un precio en pesos chilenos
     */
    public static function formatPrice($price)
    {
        if (empty($price) || $price == 0) {
            return 'Consultar precio';
        }
        
        $price = is_numeric($price) ? (float)$price : 0;
        return '$' . number_format($price, 0, ',', '.');
    }
    
    /**
     * Formatea números de forma segura
     */
    public static function safeNumberFormat($number, $decimals = 0, $dec_point = '.', $thousands_sep = ',')
    {
        $number = is_numeric($number) ? (float)$number : 0;
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }
    
    /**
     * Obtiene un valor de array de forma segura
     */
    public static function safeArrayGet($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
    
    /**
     * Formatea precio de forma segura con símbolo de peso
     */
    public static function safePriceFormat($price, $showCurrency = true)
    {
        $price = is_numeric($price) ? (float)$price : 0;
        $formatted = number_format($price, 0, ',', '.');
        return $showCurrency ? '$' . $formatted : $formatted;
    }
    
    /**
     * Formatea una fecha en formato chileno
     */
    public static function formatDate($date)
    {
        if (empty($date)) {
            return '';
        }
        
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return date('d/m/Y', $timestamp);
    }
    
    /**
     * Formatea una fecha y hora en formato chileno
     */
    public static function formatDateTime($datetime)
    {
        if (empty($datetime)) {
            return '';
        }
        
        $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
        return date('d/m/Y H:i', $timestamp);
    }
    
    /**
     * Calcula el tiempo transcurrido desde una fecha
     */
    public static function timeAgo($datetime)
    {
        if (empty($datetime)) {
            return '';
        }
        
        $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'Hace ' . $diff . ' segundos';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return 'Hace ' . $minutes . ' minuto' . ($minutes > 1 ? 's' : '');
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return 'Hace ' . $hours . ' hora' . ($hours > 1 ? 's' : '');
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return 'Hace ' . $days . ' día' . ($days > 1 ? 's' : '');
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return 'Hace ' . $months . ' mes' . ($months > 1 ? 'es' : '');
        } else {
            $years = floor($diff / 31536000);
            return 'Hace ' . $years . ' año' . ($years > 1 ? 's' : '');
        }
    }
    
    /**
     * Limpia y valida un string
     */
    public static function cleanString($string)
    {
        if (empty($string)) {
            return '';
        }
        
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Valida un email
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Valida un teléfono chileno
     */
    public static function validatePhone($phone)
    {
        // Remover espacios y caracteres especiales
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Formatos válidos:
        // +56912345678
        // 56912345678
        // 912345678
        // 223456789 (fijo)
        
        if (preg_match('/^\+?56[2-9]\d{8}$/', $phone)) {
            return true; // Celular con código país
        }
        
        if (preg_match('/^[2-9]\d{8}$/', $phone)) {
            return true; // Celular sin código país
        }
        
        if (preg_match('/^\+?56[2-9]\d{7}$/', $phone)) {
            return true; // Fijo con código país
        }
        
        if (preg_match('/^[2-9]\d{7}$/', $phone)) {
            return true; // Fijo sin código país
        }
        
        return false;
    }
    
    /**
     * Genera un token aleatorio
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Encripta una contraseña
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Verifica una contraseña
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Genera un slug a partir de un string
     */
    public static function generateSlug($string)
    {
        // Convertir a minúsculas
        $string = strtolower($string);
        
        // Reemplazar caracteres especiales
        $string = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $string);
        
        // Remover caracteres no alfanuméricos
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        
        // Reemplazar espacios y guiones múltiples con un solo guión
        $string = preg_replace('/[\s-]+/', '-', $string);
        
        // Remover guiones al inicio y final
        return trim($string, '-');
    }
    
    /**
     * Redimensiona una imagen
     */
    public static function resizeImage($source, $destination, $width, $height, $quality = 85)
    {
        $imageInfo = getimagesize($source);
        if (!$imageInfo) {
            return false;
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Crear imagen desde el archivo fuente
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($source);
                break;
            default:
                return false;
        }
        
        // Calcular dimensiones manteniendo proporción
        $ratio = min($width / $sourceWidth, $height / $sourceHeight);
        $newWidth = intval($sourceWidth * $ratio);
        $newHeight = intval($sourceHeight * $ratio);
        
        // Crear nueva imagen
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia para PNG
        if ($mimeType === 'image/png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }
        
        // Redimensionar
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
        
        // Guardar imagen
        $result = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $result = imagejpeg($newImage, $destination, $quality);
                break;
            case 'image/png':
                $result = imagepng($newImage, $destination);
                break;
            case 'image/gif':
                $result = imagegif($newImage, $destination);
                break;
        }
        
        // Liberar memoria
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $result;
    }
    
    /**
     * Sube un archivo
     */
    public static function uploadFile($file, $directory, $allowedTypes = [], $maxSize = 0)
    {
        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error al subir archivo'];
        }
        
        $filename = $file['name'];
        $tmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validar tipo de archivo
        if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
        }
        
        // Validar tamaño
        if ($maxSize > 0 && $fileSize > $maxSize) {
            return ['success' => false, 'message' => 'Archivo demasiado grande'];
        }
        
        // Crear directorio si no existe
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Generar nombre único
        $newFilename = uniqid() . '.' . $fileType;
        $destination = $directory . '/' . $newFilename;
        
        // Mover archivo
        if (move_uploaded_file($tmpName, $destination)) {
            return ['success' => true, 'filename' => $newFilename, 'path' => $destination];
        }
        
        return ['success' => false, 'message' => 'Error al guardar archivo'];
    }
    
    /**
     * Elimina un archivo
     */
    public static function deleteFile($path)
    {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
    
    /**
     * Obtiene el tamaño de un archivo en formato legible
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        
        return $bytes;
    }
    
    /**
     * Trunca un texto
     */
    public static function truncateText($text, $length = 100, $suffix = '...')
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $suffix;
    }
    
    /**
     * Convierte un array a JSON de forma segura
     */
    public static function jsonEncode($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    /**
     * Decodifica JSON de forma segura
     */
    public static function jsonDecode($json, $assoc = true)
    {
        return json_decode($json, $assoc);
    }
    
    /**
     * Registra un log
     */
    public static function log($message, $level = 'INFO', $file = 'app.log')
    {
        $logPath = __DIR__ . '/../storage/logs/';
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        file_put_contents($logPath . $file, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Obtiene la IP del cliente
     */
    public static function getClientIP()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Obtiene información del navegador
     */
    public static function getBrowserInfo()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $browser = 'Desconocido';
        $version = '';
        
        if (preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        } elseif (preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Edge';
            $version = $matches[1];
        }
        
        return [
            'browser' => $browser,
            'version' => $version,
            'user_agent' => $userAgent
        ];
    }
    
    /**
     * Genera una URL amigable
     */
    public static function generateFriendlyUrl($title, $id = null)
    {
        $slug = self::generateSlug($title);
        return $id ? $slug . '-' . $id : $slug;
    }
    
    /**
     * Valida un RUT chileno
     */
    public static function validateRut($rut)
    {
        $rut = preg_replace('/[^0-9kK]/', '', $rut);
        
        if (strlen($rut) < 2) {
            return false;
        }
        
        $dv = substr($rut, -1);
        $number = substr($rut, 0, -1);
        
        $sum = 0;
        $multiplier = 2;
        
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $sum += $number[$i] * $multiplier;
            $multiplier = $multiplier == 7 ? 2 : $multiplier + 1;
        }
        
        $calculatedDv = 11 - ($sum % 11);
        
        if ($calculatedDv == 11) {
            $calculatedDv = '0';
        } elseif ($calculatedDv == 10) {
            $calculatedDv = 'K';
        }
        
        return strtoupper($dv) == $calculatedDv;
    }
    
    /**
     * Formatea un RUT
     */
    public static function formatRut($rut)
    {
        $rut = preg_replace('/[^0-9kK]/', '', $rut);
        
        if (strlen($rut) < 2) {
            return $rut;
        }
        
        $dv = substr($rut, -1);
        $number = substr($rut, 0, -1);
        
        return number_format($number, 0, '', '.') . '-' . strtoupper($dv);
    }
    
    /**
     * Envía una respuesta JSON
     */
    public static function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo self::jsonEncode($data);
        exit;
    }
    
    /**
     * Redirige a una URL
     */
    public static function redirect($url, $statusCode = 302)
    {
        header("Location: $url", true, $statusCode);
        exit;
    }
    
    /**
     * Obtiene el dominio actual
     */
    public static function getCurrentDomain()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
    
    /**
     * Obtiene la URL actual
     */
    public static function getCurrentUrl()
    {
        return self::getCurrentDomain() . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Verifica si es una petición AJAX
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Verifica si es una petición POST
     */
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Verifica si es una petición GET
     */
    public static function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}
?> 