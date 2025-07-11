<?php
require_once __DIR__ . '/Model.php';

// Clase base para todos los controladores
class Controller {
    
    protected $db;
    
    /**
     * Constructor de la clase base
     */
    public function __construct() {
        // Inicialización común para todos los controladores
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Inicializar conexión a la base de datos
        $this->initializeDatabase();
    }
    
    /**
     * Inicializa la conexión a la base de datos
     */
    private function initializeDatabase() {
        try {
            // Usar la clase Database existente
            require_once __DIR__ . '/../config/config.php';
            
            $this->db = Database::getInstance();
            
        } catch (Exception $e) {
            // En caso de error, usar null para que los controladores puedan manejar el error
            $this->db = null;
            error_log("Error de conexión a base de datos en Controller: " . $e->getMessage());
        }
    }
    
    // Método para cargar una vista
    public function view($view, $data = []) {
        extract($data);
        require_once '../app/views/' . $view . '.php';
    }
    
    /**
     * Método render para compatibilidad con controladores existentes
     */
    public function render($view, $data = []) {
        $this->view($view, $data);
    }
} 