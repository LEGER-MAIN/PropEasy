<?php
require_once __DIR__ . '/../../core/Controller.php';

class HomeController extends Controller {
    public function index() {
        $data = ['titulo' => 'Bienvenido a PropEasy'];
        $this->view('home/index', $data);
    }

    /**
     * API para obtener propiedades más favoritas (paginado)
     */
    public function apiMostFavoritedProperties() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        $properties = $propertyModel->getMostFavoritedProperties($limit, $offset);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $properties]);
    }
} 