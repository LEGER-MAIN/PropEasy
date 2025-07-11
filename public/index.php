<?php
// Configuración de errores - Ocultar warnings en desarrollo para mejor UX
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');

// Punto de entrada principal del sistema PropEasy
// Inicializa el autoload y el router

require_once '../core/Router.php';

$router = new Router();
$router->run(); 