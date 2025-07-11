<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

/**
 * Controlador para la gestión de autenticación y seguridad
 * Maneja login, registro, validación de cuentas y recuperación de contraseñas
 */
class AuthController extends Controller {
    
    private $userModel;
    
    /**
     * Constructor del controlador
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Muestra la página de login
     */
    public function login() {
        // Si ya está autenticado, redirigir según su rol
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['user_role']);
            return;
        }
        
        $data = [
            'titulo' => 'Iniciar Sesión - PropEasy',
            'error' => $_SESSION['auth_error'] ?? null,
            'success' => $_SESSION['auth_success'] ?? null
        ];
        
        // Limpiar mensajes de sesión
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        
        $this->view('auth/login', $data);
    }
    
    /**
     * Muestra la página de registro
     */
    public function register() {
        // Si ya está autenticado, redirigir según su rol
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['user_role']);
            return;
        }
        
        $data = [
            'titulo' => 'Registrarse - PropEasy',
            'error' => $_SESSION['auth_error'] ?? null,
            'success' => $_SESSION['auth_success'] ?? null
        ];
        
        // Limpiar mensajes de sesión
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        
        $this->view('auth/register', $data);
    }
    
    /**
     * Procesa el formulario de login
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/login');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validación básica
            if (empty($email) || empty($password)) {
            $_SESSION['auth_error'] = 'Por favor completa todos los campos';
            header('Location: /auth/login');
            exit;
        }
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Formato de email inválido';
            header('Location: /auth/login');
            exit;
        }
        
        // Intentar autenticar
        $result = $this->userModel->authenticate($email, $password);
        
        if ($result['success']) {
            // Login exitoso - crear sesión
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['name'];
            $_SESSION['user_email'] = $result['user']['email'];
            $_SESSION['user_role'] = $result['user']['role'];
            $_SESSION['user_authenticated'] = true;
            
            // Redirigir según el rol
            $this->redirectByRole($result['user']['role']);
        } else {
            $_SESSION['auth_error'] = $result['message'];
            header('Location: /auth/login');
                exit;
        }
    }
    
    /**
     * Procesa el formulario de registro
     */
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/register');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';
            
            // Validación básica
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $_SESSION['auth_error'] = 'Por favor completa todos los campos';
            header('Location: /auth/register');
            exit;
        }
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Formato de email inválido';
            header('Location: /auth/register');
            exit;
        }
        
        // Validar contraseñas
        if ($password !== $confirmPassword) {
            $_SESSION['auth_error'] = 'Las contraseñas no coinciden';
            header('Location: /auth/register');
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['auth_error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: /auth/register');
            exit;
        }
        
        // Validar rol
        $allowedRoles = ['client', 'agent'];
        if (!in_array($role, $allowedRoles)) {
            $_SESSION['auth_error'] = 'Rol de usuario inválido';
            header('Location: /auth/register');
            exit;
        }
        
        // Intentar registrar usuario
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ];
        
        $result = $this->userModel->registerUser($userData);
        
        if ($result['success']) {
            $_SESSION['auth_success'] = $result['message'];
            header('Location: /auth/login');
            exit;
        } else {
            $_SESSION['auth_error'] = $result['message'];
            header('Location: /auth/register');
            exit;
        }
    }
    
    /**
     * Valida la cuenta de usuario mediante token
     */
    public function validate() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['auth_error'] = 'Token de validación no proporcionado';
            header('Location: /auth/login');
            exit;
        }
        
        $result = $this->userModel->validateAccount($token);
        
        if ($result['success']) {
            $_SESSION['auth_success'] = $result['message'];
        } else {
            $_SESSION['auth_error'] = $result['message'];
        }
        
        header('Location: /auth/login');
        exit;
    }
    
    /**
     * Muestra la página de recuperación de contraseña
     */
    public function forgotPassword() {
        $data = [
            'titulo' => 'Recuperar Contraseña - PropEasy',
            'error' => $_SESSION['auth_error'] ?? null,
            'success' => $_SESSION['auth_success'] ?? null
        ];
        
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        
        $this->view('auth/forgot-password', $data);
    }
    
    /**
     * Procesa la solicitud de recuperación de contraseña
     */
    public function processForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/forgot-password');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $_SESSION['auth_error'] = 'Por favor ingresa tu email';
            header('Location: /auth/forgot-password');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Formato de email inválido';
            header('Location: /auth/forgot-password');
            exit;
        }
        
        $result = $this->userModel->initiatePasswordRecovery($email);
        
        if ($result['success']) {
            $_SESSION['auth_success'] = $result['message'];
        } else {
            $_SESSION['auth_error'] = $result['message'];
        }
        
        header('Location: /auth/forgot-password');
        exit;
    }
    
    /**
     * Muestra la página para resetear contraseña
     */
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['auth_error'] = 'Token de recuperación no proporcionado';
            header('Location: /auth/login');
            exit;
        }
        
        $data = [
            'titulo' => 'Restablecer Contraseña - PropEasy',
            'token' => $token,
            'error' => $_SESSION['auth_error'] ?? null,
            'success' => $_SESSION['auth_success'] ?? null
        ];
        
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        
        $this->view('auth/reset-password', $data);
    }
    
    /**
     * Procesa el reseteo de contraseña
     */
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/login');
            exit;
        }
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($token) || empty($password)) {
            $_SESSION['auth_error'] = 'Todos los campos son obligatorios';
            header('Location: /auth/reset-password?token=' . $token);
            exit;
        }
        
        if ($password !== $confirmPassword) {
            $_SESSION['auth_error'] = 'Las contraseñas no coinciden';
            header('Location: /auth/reset-password?token=' . $token);
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['auth_error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: /auth/reset-password?token=' . $token);
            exit;
        }
        
        $result = $this->userModel->resetPassword($token, $password);
        
        if ($result['success']) {
            $_SESSION['auth_success'] = $result['message'];
            header('Location: /auth/login');
        } else {
            $_SESSION['auth_error'] = $result['message'];
            header('Location: /auth/reset-password?token=' . $token);
        }
        exit;
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir al login
        header('Location: /auth/login');
        exit;
    }
    
    /**
     * Middleware para verificar autenticación
     * @param string $requiredRole Rol requerido (opcional)
     * @return bool True si está autenticado y tiene el rol requerido
     */
    public static function requireAuth($requiredRole = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si está autenticado
        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
            $_SESSION['auth_error'] = 'Debes iniciar sesión para acceder a esta página';
            header('Location: /auth/login');
            exit;
        }
        
        // Verificar rol si se especifica
        if ($requiredRole && $_SESSION['user_role'] !== $requiredRole) {
            $_SESSION['auth_error'] = 'No tienes permisos para acceder a esta página';
            header('Location: /dashboard');
            exit;
        }
        
        return true;
    }
    
    /**
     * Middleware para verificar si es administrador
     */
    public static function requireAdmin() {
        return self::requireAuth('admin');
    }
    
    /**
     * Middleware para verificar si es agente
     */
    public static function requireAgent() {
        return self::requireAuth('agent');
    }
    
    /**
     * Middleware para verificar si es cliente
     */
    public static function requireClient() {
        return self::requireAuth('client');
    }
    
    /**
     * Redirige al usuario según su rol
     * @param string $role Rol del usuario
     */
    private function redirectByRole($role) {
        switch ($role) {
            case 'admin':
                header('Location: /admin/dashboard');
                break;
            case 'agent':
                header('Location: /agent/dashboard');
                break;
            case 'client':
                header('Location: /properties');
                break;
            default:
                header('Location: /');
        }
        exit;
    }
    
    /**
     * Obtiene información del usuario actual
     * @return array|null Datos del usuario o null si no está autenticado
     */
    public static function getCurrentUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
} 