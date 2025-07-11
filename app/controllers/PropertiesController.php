<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para Propiedades
 * Maneja la lógica de propiedades inmobiliarias
 */
class PropertiesController extends Controller {
    
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestra el listado de propiedades
     */
    public function index() {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Paginación
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 12; // 12 propiedades por página
        $offset = ($page - 1) * $limit;
        
        // Recoger filtros de la URL (GET)
        $filtros = [
            'tipo' => $_GET['tipo'] ?? '',
            'ubicacion' => $_GET['ubicacion'] ?? '',
            'precio_min' => $_GET['precio_min'] ?? '',
            'precio_max' => $_GET['precio_max'] ?? '',
            'habitaciones' => $_GET['habitaciones'] ?? '',
            'baños' => $_GET['baños'] ?? '',
            'orden' => $_GET['orden'] ?? 'reciente',
            'solo_favoritos' => isset($_GET['solo_favoritos']) && $_GET['solo_favoritos'] === '1',
            'limit' => $limit,
            'offset' => $offset
        ];
        
        // Obtener favoritos del usuario si está autenticado
        $userFavorites = [];
        if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] && isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../models/FavoriteModel.php';
            $favoriteModel = new FavoriteModel();
            $userId = $_SESSION['user_id'];
            $userFavorites = $favoriteModel->getUserFavoriteIds($userId);
        }

        // Si se solicita solo favoritos, filtrar por favoritos del usuario
        if ($filtros['solo_favoritos'] && !empty($userFavorites)) {
            $filtros['favorites_ids'] = $userFavorites;
        } elseif ($filtros['solo_favoritos'] && empty($userFavorites)) {
            // Si pide favoritos pero no tiene ninguno, devolver array vacío
            $properties = [];
            $totalProperties = 0;
        }
        
        if (!isset($properties)) {
            // Obtener propiedades filtradas con paginación
            $properties = $propertyModel->getFilteredProperties($filtros);
            // Obtener total de propiedades (sin limit/offset) para calcular páginas
            $filtrosSinPaginacion = $filtros;
            unset($filtrosSinPaginacion['limit'], $filtrosSinPaginacion['offset']);
            $totalProperties = $propertyModel->countFilteredProperties($filtrosSinPaginacion);
        }
        
        // Calcular datos de paginación
        $totalPages = ceil($totalProperties / $limit);
        
        // Formateo de datos para la vista
        foreach ($properties as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);
        
        $data = [
            'titulo' => 'Propiedades - PropEasy',
            'propiedades' => $properties,
            'filtros' => $filtros,
            'userFavorites' => $userFavorites,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_properties' => $totalProperties,
                'per_page' => $limit,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages
            ]
        ];
        $this->render('properties/index', $data);
    }
    
    /**
     * Muestra el detalle de una propiedad específica
     */
    public function detail($id = null)
    {
        if (!$id) {
            header('Location: /properties');
            exit;
        }

        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        $property = $propertyModel->getPropertyById($id);
        
        if (!$property) {
            // Propiedad no encontrada
            $this->render('error/404', ['titulo' => 'Propiedad no encontrada']);
            return;
        }

        // El PropertyModel ya procesa las imágenes y características
        // No necesitamos procesamiento adicional aquí
        $property['title'] = $property['titulo'];
        $property['price'] = $property['precio'];
        $property['bedrooms'] = $property['habitaciones'];
        $property['bathrooms'] = $property['banos'];
        $property['type'] = $property['tipo'];
        $property['commune'] = $property['ciudad'];
        $property['description'] = $property['descripcion'];
        $property['address'] = $property['ubicacion'] ?? '';
        $property['year_built'] = date('Y', strtotime($property['fecha_creacion']));
        $property['condition'] = 'Excelente';
        
        // Obtener propiedades similares
        $similarProperties = $propertyModel->getSimilarProperties(
            $property['id'], 
            $property['tipo'], 
            $property['ciudad'], 
            $property['precio']
        );
        
        // Saber si es favorito
        $isFavorite = false;
        if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] && isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../models/FavoriteModel.php';
            $favoriteModel = new FavoriteModel();
            $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $property['id']);
        }

        $data = [
            'titulo' => $property['titulo'] . ' - PropEasy',
            'property' => $property,
            'similarProperties' => $similarProperties,
            'isFavorite' => $isFavorite
        ];

        $this->render('properties/property/detail', $data);
    }
    


    /**
     * API para enviar formulario de contacto
     */
    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';
        $propertyId = $_POST['property_id'] ?? '';
        $newsletter = isset($_POST['newsletter']);

        if (!$name || !$email || !$message) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        // Simulación de envío de mensaje
        // En producción, aquí se guardaría en la base de datos y se enviaría email
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Mensaje enviado exitosamente. El agente te contactará pronto.'
        ]);
    }

    /**
     * API para agendar visita
     */
    public function appointment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $propertyId = $_POST['property_id'] ?? '';

        if (!$name || !$email || !$phone || !$date || !$time) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        // Simulación de agendamiento
        // En producción, aquí se guardaría en la base de datos
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Visita agendada exitosamente. Recibirás una confirmación por email.'
        ]);
    }

    /**
     * API para agregar/quitar de favoritos
     */
    public function favorite()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated'] || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para usar favoritos', 'require_login' => true]);
            return;
        }

        $userId = $_SESSION['user_id'];
        $propertyId = $_POST['property_id'] ?? '';

        if (!$propertyId || !is_numeric($propertyId)) {
            echo json_encode(['success' => false, 'message' => 'ID de propiedad requerido']);
            return;
        }

        try {
            require_once __DIR__ . '/../models/FavoriteModel.php';
            $favoriteModel = new FavoriteModel();
            
            // Usar el método toggleFavorite para cambiar el estado
            $result = $favoriteModel->toggleFavorite($userId, $propertyId);
            
            if ($result['success']) {
                $message = $result['action'] === 'added' ? 'Propiedad agregada a favoritos' : 'Propiedad removida de favoritos';
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'action' => $result['action'],
                    'is_favorite' => $result['action'] === 'added',
                    'count' => $result['count']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al procesar favorito'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Muestra la página de favoritos del usuario
     */
    public function favorites() {
        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
            header('Location: /auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        require_once __DIR__ . '/../models/FavoriteModel.php';
        $favoriteModel = new FavoriteModel();
        
        $favorites = $favoriteModel->getUserFavorites($userId, $limit, $offset);
        $totalFavorites = $favoriteModel->countUserFavorites($userId);
        $totalPages = ceil($totalFavorites / $limit);
        $stats = $favoriteModel->getFavoriteStats($userId);

        // Formatear datos para compatibilidad con _property_cards.php
        foreach ($favorites as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);

        // Crear array de IDs favoritos para compatibilidad
        $userFavorites = array_column($favorites, 'id');

        $this->render('properties/favorites', [
            'titulo' => 'Mis Favoritos - PropEasy',
            'favorites' => $favorites,
            'properties' => $favorites, // Alias para _property_cards.php
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalFavorites' => $totalFavorites,
            'stats' => $stats,
            'userFavorites' => $userFavorites
        ]);
    }

    /**
     * API para obtener estadísticas de la propiedad
     */
    public function stats($propertyId)
    {
        if (!$propertyId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de propiedad requerido']);
            return;
        }

        // Simulación de estadísticas
        $stats = [
            'views' => rand(50, 200),
            'favorites' => rand(5, 30),
            'inquiries' => rand(2, 15),
            'days_on_market' => rand(10, 90)
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * API para buscar propiedades
     */
    public function search()
    {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Obtener parámetros de búsqueda
        $searchParams = [
            'tipo' => $_GET['tipo'] ?? '',
            'ubicacion' => $_GET['ubicacion'] ?? '',
            'precio_min' => $_GET['precio_min'] ?? '',
            'precio_max' => $_GET['precio_max'] ?? '',
            'habitaciones' => $_GET['habitaciones'] ?? '',
            'banos' => $_GET['banos'] ?? ''
        ];
        
        // Buscar propiedades
        $properties = $propertyModel->searchProperties($searchParams);
        
        // Obtener favoritos del usuario si está autenticado
        $userFavorites = [];
        if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] && isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../models/FavoriteModel.php';
            $favoriteModel = new FavoriteModel();
            $userId = $_SESSION['user_id'];
            foreach ($properties as $p) {
                if ($favoriteModel->isFavorite($userId, $p['id'])) {
                    $userFavorites[] = $p['id'];
                }
            }
        }
        
        // Preparar datos para la vista
        $data = [
            'titulo' => 'Resultados de Búsqueda - PropEasy',
            'propiedades' => $properties,
            'searchParams' => $searchParams,
            'userFavorites' => $userFavorites,
            'totalResults' => count($properties)
        ];
        
        $this->render('properties/search_results', $data);
    }

    /**
     * Muestra el formulario para publicar una nueva propiedad (solo autenticados)
     */
    public function publish() {
        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
            header('Location: /auth/login');
            exit;
        }
        $data = [
            'titulo' => 'Publicar Propiedad - PropEasy'
        ];
        $this->render('properties/publish', $data);
    }

    /**
     * Procesa el formulario de publicación de propiedad por cliente
     */
    public function processPublish() {
        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
            header('Location: /auth/login');
            exit;
        }
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        $clienteId = $_SESSION['user_id'];
        $data = [
            'titulo' => $_POST['titulo'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'tipo' => $_POST['tipo'] ?? '',
            'precio' => $_POST['precio'] ?? 0,
            'ubicacion' => $_POST['ubicacion'] ?? '',
            'ciudad' => $_POST['ciudad'] ?? '',
            'sector' => $_POST['sector'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'area' => $_POST['area'] ?? 0,
            'superficie_construida' => $_POST['superficie_construida'] ?? 0,
            'habitaciones' => $_POST['habitaciones'] ?? 0,
            'banos' => $_POST['banos'] ?? 0,
            'parqueos' => $_POST['parqueos'] ?? 0,
            'caracteristicas' => isset($_POST['caracteristicas']) ? json_encode($_POST['caracteristicas']) : null,
            'video_url' => $_POST['video_url'] ?? null,
            'plano_url' => $_POST['plano_url'] ?? null,
            'latitud' => $_POST['latitud'] ?? null,
            'longitud' => $_POST['longitud'] ?? null,
            'agente_id' => (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_POST['agente_id']) && $_POST['agente_id']) ? $_POST['agente_id'] : null
        ];
        $imagenes = $_FILES['imagenes'] ?? [];
        $result = $propertyModel->savePendingProperty($data, $clienteId, $imagenes);
        $this->render('properties/publish_confirmation', [
            'titulo' => 'Propuesta Enviada',
            'success' => $result['success'],
            'message' => $result['message'],
            'token' => $result['token'] ?? null
        ]);
    }

    /**
     * Muestra el listado de propiedades pendientes para validación de agente
     */
    public function pending() {
        if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_role'] !== 'agent') {
            header('Location: /auth/login');
            exit;
        }
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        $pendientes = $propertyModel->getPendingProperties();
        $this->render('properties/pending', [
            'titulo' => 'Propiedades Pendientes de Validación',
            'pendientes' => $pendientes
        ]);
    }

    /**
     * Muestra el formulario para validar una propiedad pendiente
     */
    public function validate($id = null) {
        if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_role'] !== 'agent') {
            header('Location: /auth/login');
            exit;
        }
        if (!$id) {
            header('Location: /properties/pending');
            exit;
        }
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        $propiedad = $propertyModel->getPropertyById($id);
        if (!$propiedad || $propiedad['estado'] !== 'pendiente') {
            $this->render('properties/validate_result', [
                'titulo' => 'Validación de Propiedad',
                'success' => false,
                'message' => 'Propiedad no encontrada o ya validada.'
            ]);
            return;
        }
        $this->render('properties/validate', [
            'titulo' => 'Validar Propiedad',
            'propiedad' => $propiedad
        ]);
    }

    /**
     * Procesa la validación de la propiedad por el agente
     */
    public function processValidate() {
        if (!isset($_SESSION['user_authenticated']) || $_SESSION['user_role'] !== 'agent') {
            header('Location: /auth/login');
            exit;
        }
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        $id = $_POST['id'] ?? null;
        $token = $_POST['token'] ?? '';
        $agenteId = $_SESSION['user_id'];
        if (!$id || !$token) {
            $this->render('properties/validate_result', [
                'titulo' => 'Validación de Propiedad',
                'success' => false,
                'message' => 'Faltan datos para validar.'
            ]);
            return;
        }
        $result = $propertyModel->validateProperty($id, $token, $agenteId);
        $this->render('properties/validate_result', [
            'titulo' => 'Validación de Propiedad',
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }

    /**
     * Devuelve solo el HTML de las propiedades filtradas (para AJAX)
     */
    public function filter() {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Paginación para filtros
        $page = isset($_POST['page']) && is_numeric($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
        $limit = 12; // 12 propiedades por página
        $offset = ($page - 1) * $limit;
        
        $filtros = [
            'tipo' => $_POST['tipo'] ?? '',
            'ubicacion' => $_POST['ubicacion'] ?? '',
            'precio_min' => $_POST['precio_min'] ?? '',
            'precio_max' => $_POST['precio_max'] ?? '',
            'habitaciones' => $_POST['habitaciones'] ?? '',
            'baños' => $_POST['baños'] ?? '',
            'orden' => $_POST['orden'] ?? 'reciente',
            'solo_favoritos' => isset($_POST['solo_favoritos']) && $_POST['solo_favoritos'] === '1',
            'limit' => $limit,
            'offset' => $offset
        ];
        
        // Obtener favoritos del usuario si está autenticado
        $userFavorites = [];
        if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] && isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../models/FavoriteModel.php';
            $favoriteModel = new FavoriteModel();
            $userId = $_SESSION['user_id'];
            $userFavorites = $favoriteModel->getUserFavoriteIds($userId);
        }

        // Si se solicita solo favoritos, filtrar por favoritos del usuario
        if ($filtros['solo_favoritos']) {
            if (!empty($userFavorites)) {
                $filtros['favorites_ids'] = $userFavorites;
                $properties = $propertyModel->getFilteredProperties($filtros);
                // Obtener total sin paginación
                $filtrosSinPaginacion = $filtros;
                unset($filtrosSinPaginacion['limit'], $filtrosSinPaginacion['offset']);
                $totalProperties = $propertyModel->countFilteredProperties($filtrosSinPaginacion);
            } else {
                // Si pide favoritos pero no tiene ninguno, devolver array vacío
                $properties = [];
                $totalProperties = 0;
            }
        } else {
            // Obtener propiedades filtradas normalmente
            $properties = $propertyModel->getFilteredProperties($filtros);
            // Obtener total sin paginación
            $filtrosSinPaginacion = $filtros;
            unset($filtrosSinPaginacion['limit'], $filtrosSinPaginacion['offset']);
            $totalProperties = $propertyModel->countFilteredProperties($filtrosSinPaginacion);
        }
        
        // Calcular datos de paginación
        $totalPages = ceil($totalProperties / $limit);
        
        // Formateo de datos para la vista
        foreach ($properties as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);
        
        // Renderizar el fragmento de tarjetas de propiedades
        ob_start();
        echo '<div class="row g-3" id="propertiesContainer">';
        
        if (empty($properties)) {
            echo '<div class="col-12 text-center py-5">';
            echo '<i class="fas fa-search fa-3x text-muted mb-3"></i>';
            echo '<h4>No se encontraron propiedades</h4>';
            echo '<p class="text-muted">Intenta ajustar los filtros de búsqueda</p>';
            echo '</div>';
        } else {
            foreach ($properties as $prop) {
                $isFavorite = in_array($prop['id'], $userFavorites);
                echo '<div class="col-lg-4 col-md-6 col-12 mb-4 property-item" data-prop-id="' . $prop['id'] . '">';
                echo '<div class="card property-card h-100 position-relative">';
                echo '<div class="position-relative">';
                echo '<img src="' . htmlspecialchars($prop['imagen_principal']) . '" class="card-img-top" alt="' . htmlspecialchars($prop['titulo']) . '" onerror="this.src=\'assets/images/placeholder.jpg\'">';
                echo '<div class="position-absolute top-0 end-0 m-2">';
                echo '<span class="badge bg-primary">' . htmlspecialchars(ucfirst($prop['tipo'])) . '</span>';
                echo '</div>';
                echo '<div class="position-absolute top-0 start-0 m-2">';
                echo '<span class="badge bg-success">' . ucfirst($prop['estado'] ?? 'activa') . '</span>';
                echo '</div>';
                echo '</div>';
                echo '<div class="d-flex justify-content-end align-items-center gap-2 mt-2 mb-2 me-3">';
                echo '<button class="favorite-btn p-0 border-0 bg-transparent' . ($isFavorite ? ' active' : '') . '" data-id="' . $prop['id'] . '" style="z-index:2;">';
                echo '<i class="fa-heart ' . ($isFavorite ? 'fas text-danger' : 'far') . '" id="favoriteIcon-' . $prop['id'] . '" style="font-size: 2.2rem; color: #dc3545;"></i>';
                echo '</button>';
                echo '<span class="small text-muted" id="favoriteCount-' . $prop['id'] . '">' . ($prop['favorites_count'] ?? 0) . '</span>';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($prop['titulo']) . '</h5>';
                echo '<p class="property-price mb-2">' . $prop['precio_formateado'] . '</p>';
                echo '<p class="property-location mb-3">';
                echo '<i class="fas fa-map-marker-alt me-1"></i>';
                echo htmlspecialchars($prop['ubicacion'] ?? 'Ubicación no especificada');
                echo '</p>';
                echo '<div class="row text-center mb-3 align-items-end" style="min-height: 70px;">';
                echo '<div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">';
                echo '<div><i class="fas fa-bed fa-lg mb-1"></i><span class="fw-bold ms-1">' . $prop['habitaciones'] . '</span></div>';
                echo '<small class="text-muted">Habitaciones</small>';
                echo '</div>';
                echo '<div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">';
                echo '<div><i class="fas fa-bath fa-lg mb-1"></i><span class="fw-bold ms-1">' . ($prop['banos'] ?? 'N/A') . '</span></div>';
                echo '<small class="text-muted">Baños</small>';
                echo '</div>';
                echo '<div class="col-4 d-flex flex-column justify-content-end align-items-center">';
                echo '<div><i class="fas fa-ruler-combined fa-lg mb-1"></i><span class="fw-bold ms-1">' . $prop['area'] . 'm²</span></div>';
                echo '<small class="text-muted">Área</small>';
                echo '</div>';
                echo '</div>';
                echo '<div class="d-grid">';
                echo '<a href="/properties/detail/' . $prop['id'] . '" class="btn btn-outline-primary">Ver Detalles</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        
        echo '</div>';
        
        // Agregar controles de paginación si hay más de una página
        if ($totalPages > 1) {
            echo '<div class="row mt-4">';
            echo '<div class="col-12 d-flex justify-content-center">';
            echo '<nav aria-label="Navegación de páginas">';
            echo '<ul class="pagination">';
            
            // Botón anterior
            if ($page > 1) {
                echo '<li class="page-item">';
                echo '<button class="page-link" data-page="' . ($page - 1) . '">Anterior</button>';
                echo '</li>';
            }
            
            // Números de página
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1) {
                echo '<li class="page-item">';
                echo '<button class="page-link" data-page="1">1</button>';
                echo '</li>';
                if ($startPage > 2) {
                    echo '<li class="page-item disabled">';
                    echo '<span class="page-link">...</span>';
                    echo '</li>';
                }
            }
            
            for ($i = $startPage; $i <= $endPage; $i++) {
                echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
                echo '<button class="page-link" data-page="' . $i . '">' . $i . '</button>';
                echo '</li>';
            }
            
            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    echo '<li class="page-item disabled">';
                    echo '<span class="page-link">...</span>';
                    echo '</li>';
                }
                echo '<li class="page-item">';
                echo '<button class="page-link" data-page="' . $totalPages . '">' . $totalPages . '</button>';
                echo '</li>';
            }
            
            // Botón siguiente
            if ($page < $totalPages) {
                echo '<li class="page-item">';
                echo '<button class="page-link" data-page="' . ($page + 1) . '">Siguiente</button>';
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
            echo '</div>';
            echo '</div>';
            
            // Información adicional
            echo '<div class="row mt-2">';
            echo '<div class="col-12 text-center text-muted">';
            $start = ($page - 1) * $limit + 1;
            $end = min($page * $limit, $totalProperties);
            echo "Mostrando $start - $end de $totalProperties propiedades";
            echo '</div>';
            echo '</div>';
        }
        
        $html = ob_get_clean();
        echo $html;
        exit;
    }

    /**
     * Procesa el formulario de contacto
     */
    public function processContact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties');
            exit;
        }

        $propertyId = $_POST['property_id'] ?? 0;
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'message' => $_POST['message'] ?? '',
            'newsletter' => isset($_POST['newsletter'])
        ];

        // Validaciones básicas
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email inválido']);
            return;
        }

        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        if ($propertyModel->saveContact($propertyId, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Mensaje enviado correctamente. Te contactaremos pronto.']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al enviar el mensaje. Intenta nuevamente.']);
        }
    }

    /**
     * Procesa el formulario de cita
     */
    public function processAppointment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties');
            exit;
        }

        $propertyId = $_POST['property_id'] ?? 0;
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'appointment_date' => $_POST['appointment_date'] ?? '',
            'appointment_time' => $_POST['appointment_time'] ?? '',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Validaciones básicas
        if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || 
            empty($data['appointment_date']) || empty($data['appointment_time'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email inválido']);
            return;
        }

        // Validar que la fecha sea futura
        if (strtotime($data['appointment_date']) < strtotime('today')) {
            $this->jsonResponse(['success' => false, 'message' => 'La fecha debe ser futura']);
            return;
        }

        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        if ($propertyModel->saveAppointment($propertyId, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Cita agendada correctamente. Te contactaremos para confirmar.']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al agendar la cita. Intenta nuevamente.']);
        }
    }

    /**
     * Procesa el formulario de reporte
     */
    public function processReport()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties');
            exit;
        }

        $propertyId = $_POST['property_id'] ?? 0;
        $data = [
            'email' => $_POST['email'] ?? '',
            'reason' => $_POST['reason'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validaciones básicas
        if (empty($data['reason'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Debes seleccionar una razón']);
            return;
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Email inválido']);
            return;
        }

        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        if ($propertyModel->saveReport($propertyId, $data)) {
            $this->jsonResponse(['success' => true, 'message' => 'Reporte enviado correctamente. Revisaremos la información.']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al enviar el reporte. Intenta nuevamente.']);
        }
    }

    /**
     * Respuesta JSON
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 