<?php
require_once __DIR__ . '/../../core/Model.php';
// Agrego los imports de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Modelo para la gestión de usuarios del sistema PropEasy
 * Maneja autenticación, registro, validación por token y gestión de roles
 */
class UserModel extends Model {
    
    /**
     * Constructor del modelo
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Registra un nuevo usuario en el sistema
     * @param array $userData Datos del usuario
     * @return array Resultado de la operación
     */
    public function registerUser($userData) {
        try {
            // Validar datos requeridos
            if (empty($userData['email']) || empty($userData['password']) || empty($userData['role'])) {
                return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
            }
            
            // Verificar si el email ya existe
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $userData['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return ['success' => false, 'message' => 'El email ya está registrado'];
            }
            
            // Generar token de validación
            $validationToken = $this->generateToken();
            
            // Hash de la contraseña
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            // Insertar usuario
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, validation_token, is_active, created_at) 
                VALUES (?, ?, ?, ?, ?, 0, NOW())
            ");
            
            $stmt->bind_param("sssss", 
                $userData['name'], 
                $userData['email'], 
                $hashedPassword, 
                $userData['role'], 
                $validationToken
            );
            
            if ($stmt->execute()) {
                $userId = $this->db->insert_id;
                
                // Enviar email de validación
                $this->sendValidationEmail($userData['email'], $validationToken, $userData['name']);
                
                return [
                    'success' => true, 
                    'message' => 'Usuario registrado exitosamente. Revisa tu email para activar tu cuenta.',
                    'user_id' => $userId,
                    'validation_token' => $validationToken
                ];
            } else {
                return ['success' => false, 'message' => 'Error al registrar usuario'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Valida la cuenta de usuario mediante token
     * @param string $token Token de validación
     * @return array Resultado de la validación
     */
    public function validateAccount($token) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, email, name, role 
                FROM users 
                WHERE validation_token = ? AND is_active = 0
            ");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Token inválido o cuenta ya activada'];
            }
            
            $user = $result->fetch_assoc();
            
            // Activar cuenta
            $stmt = $this->db->prepare("
                UPDATE users 
                SET is_active = 1, validation_token = NULL, email_verified_at = NOW() 
                WHERE id = ?
            ");
            $stmt->bind_param("i", $user['id']);
            
            if ($stmt->execute()) {
                return [
                    'success' => true, 
                    'message' => 'Cuenta activada exitosamente',
                    'user' => $user
                ];
            } else {
                return ['success' => false, 'message' => 'Error al activar cuenta'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Autentica un usuario en el sistema
     * @param string $email Email del usuario
     * @param string $password Contraseña del usuario
     * @return array Resultado de la autenticación
     */
    public function authenticate($email, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, password, role, is_active, email_verified_at
                FROM users 
                WHERE email = ?
            ");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Credenciales inválidas'];
            }
            
            $user = $result->fetch_assoc();
            
            // Verificar si la cuenta está activa
            if (!$user['is_active']) {
                return ['success' => false, 'message' => 'Cuenta no activada. Revisa tu email para activarla.'];
            }
            
            // Verificar contraseña
            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Credenciales inválidas'];
            }
            
            // Actualizar último login
            $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();
            
            // Remover contraseña del array de respuesta
            unset($user['password']);
            
            return [
                'success' => true, 
                'message' => 'Autenticación exitosa',
                'user' => $user
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Inicia el proceso de recuperación de contraseña
     * @param string $email Email del usuario
     * @return array Resultado de la operación
     */
    public function initiatePasswordRecovery($email) {
        try {
            $stmt = $this->db->prepare("SELECT id, name FROM users WHERE email = ? AND is_active = 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Email no encontrado o cuenta inactiva'];
            }
            
            $user = $result->fetch_assoc();
            
            // Generar token de recuperación
            $recoveryToken = $this->generateToken();
            $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Guardar token de recuperación
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password_reset_token = ?, password_reset_expires = ? 
                WHERE id = ?
            ");
            $stmt->bind_param("ssi", $recoveryToken, $tokenExpiry, $user['id']);
            
            if ($stmt->execute()) {
                // Enviar email de recuperación
                $this->sendPasswordRecoveryEmail($email, $recoveryToken, $user['name']);
                
                return [
                    'success' => true, 
                    'message' => 'Se ha enviado un enlace de recuperación a tu email'
                ];
            } else {
                return ['success' => false, 'message' => 'Error al procesar la solicitud'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Resetea la contraseña del usuario
     * @param string $token Token de recuperación
     * @param string $newPassword Nueva contraseña
     * @return array Resultado de la operación
     */
    public function resetPassword($token, $newPassword) {
        try {
            // Verificar token válido y no expirado
            $stmt = $this->db->prepare("
                SELECT id, email 
                FROM users 
                WHERE password_reset_token = ? 
                AND password_reset_expires > NOW() 
                AND is_active = 1
            ");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Token inválido o expirado'];
            }
            
            $user = $result->fetch_assoc();
            
            // Hash de la nueva contraseña
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Actualizar contraseña y limpiar token
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = ?, password_reset_token = NULL, password_reset_expires = NULL 
                WHERE id = ?
            ");
            $stmt->bind_param("si", $hashedPassword, $user['id']);
            
            if ($stmt->execute()) {
                return [
                    'success' => true, 
                    'message' => 'Contraseña actualizada exitosamente'
                ];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar contraseña'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Obtiene información de un usuario por ID
     * @param int $userId ID del usuario
     * @return array Datos del usuario
     */
    public function getUserById($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, role, is_active, created_at, last_login
                FROM users 
                WHERE id = ?
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            return $result->fetch_assoc();
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Obtiene todos los usuarios con filtros opcionales
     * @param string $role Filtro por rol
     * @param int $limit Límite de resultados
     * @param int $offset Offset para paginación
     * @return array Lista de usuarios
     */
    public function getAllUsers($role = null, $limit = 50, $offset = 0) {
        try {
            $sql = "SELECT id, name, email, role, is_active, created_at, last_login FROM users";
            $params = [];
            $types = "";
            
            if ($role) {
                $sql .= " WHERE role = ?";
                $params[] = $role;
                $types .= "s";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= "ii";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Actualiza el perfil de un usuario
     * @param int $userId ID del usuario
     * @param array $userData Datos a actualizar
     * @return array Resultado de la operación
     */
    public function updateUserProfile($userId, $userData) {
        try {
            $allowedFields = ['name', 'email'];
            $updateFields = [];
            $params = [];
            $types = "";
            
            foreach ($userData as $field => $value) {
                if (in_array($field, $allowedFields) && !empty($value)) {
                    $updateFields[] = "$field = ?";
                    $params[] = $value;
                    $types .= "s";
                }
            }
            
            if (empty($updateFields)) {
                return ['success' => false, 'message' => 'No hay datos válidos para actualizar'];
            }
            
            $params[] = $userId;
            $types .= "i";
            
            $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Perfil actualizado exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar perfil'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Cambia la contraseña de un usuario
     * @param int $userId ID del usuario
     * @param string $currentPassword Contraseña actual
     * @param string $newPassword Nueva contraseña
     * @return array Resultado de la operación
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Verificar contraseña actual
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }
            
            $user = $result->fetch_assoc();
            
            if (!password_verify($currentPassword, $user['password'])) {
                return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
            }
            
            // Actualizar contraseña
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Contraseña cambiada exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al cambiar contraseña'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error interno del sistema'];
        }
    }
    
    /**
     * Genera un token único para validación
     * @return string Token generado
     */
    private function generateToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Envía email de validación de cuenta usando PHPMailer y Gmail
     * @param string $email Email del usuario
     * @param string $token Token de validación
     * @param string $name Nombre del usuario
     */
    private function sendValidationEmail($email, $token, $name) {
        require_once __DIR__ . '/../../vendor/autoload.php';
        $mail = new PHPMailer(true);
        try {
            // Configuración SMTP para Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'propeasycorp@gmail.com'; // Correo real
            $mail->Password = 'gfwx plhz cvyl karz'; // Contraseña de aplicación proporcionada
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('propeasycorp@gmail.com', 'PropEasy'); // Correo real
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Activa tu cuenta en PropEasy';
            $validationUrl = "http://propeasy.test/auth/validate?token=" . $token;
            $mail->Body = "
                <h2>¡Bienvenido a PropEasy, {$name}!</h2>
                <p>Gracias por registrarte en nuestra plataforma de gestión inmobiliaria.</p>
                <p>Para activar tu cuenta, haz clic en el siguiente enlace:</p>
                <p><a href='{$validationUrl}' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Activar Cuenta</a></p>
                <p>O copia y pega este enlace en tu navegador:</p>
                <p>{$validationUrl}</p>
                <p>Este enlace expirará en 24 horas.</p>
                <p>Saludos,<br>El equipo de PropEasy</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Puedes loguear el error si quieres
            // error_log('Mailer Error: ' . $mail->ErrorInfo);
        }
    }
    
    /**
     * Envía email de recuperación de contraseña usando PHPMailer y Gmail
     * @param string $email Email del usuario
     * @param string $token Token de recuperación
     * @param string $name Nombre del usuario
     */
    private function sendPasswordRecoveryEmail($email, $token, $name) {
        require_once __DIR__ . '/../../vendor/autoload.php';
        $mail = new PHPMailer(true);
        try {
            // Configuración SMTP para Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'propeasycorp@gmail.com'; // Correo real
            $mail->Password = 'gfwx plhz cvyl karz'; // Contraseña de aplicación proporcionada
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('propeasycorp@gmail.com', 'PropEasy'); // Correo real
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña - PropEasy';
            $recoveryUrl = "http://propeasy.test/auth/reset-password?token=" . $token;
            $mail->Body = "
                <h2>Recuperación de contraseña</h2>
                <p>Hola {$name},</p>
                <p>Has solicitado recuperar tu contraseña en PropEasy.</p>
                <p>Para establecer una nueva contraseña, haz clic en el siguiente enlace:</p>
                <p><a href='{$recoveryUrl}' style='background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Restablecer Contraseña</a></p>
                <p>O copia y pega este enlace en tu navegador:</p>
                <p>{$recoveryUrl}</p>
                <p>Este enlace expirará en 1 hora.</p>
                <p>Si no solicitaste este cambio, puedes ignorar este email.</p>
                <p>Saludos,<br>El equipo de PropEasy</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Puedes loguear el error si quieres
            // error_log('Mailer Error: ' . $mail->ErrorInfo);
        }
    }
} 