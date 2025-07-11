<?php
// Clase base para todos los modelos
class Model {
    protected $db;
    public function __construct() {
        require_once __DIR__ . '/../config/config.php';
        $this->db = Database::getInstance();
    }
} 