<?php
require_once __DIR__ . '/../../core/Model.php';

class PropertyModel extends Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Guarda una nueva propiedad propuesta por un cliente
     * @param array $data Datos del formulario
     * @param int $clienteId ID del cliente
     * @param array $imagenes Archivos de imágenes
     * @return array Resultado
     */
    public function savePendingProperty($data, $clienteId, $imagenes = []) {
        try {
            $token = $this->generateToken();
            $imagenesPaths = $this->saveImages($imagenes);
            $imagenesStr = $imagenesPaths ? json_encode($imagenesPaths) : null;
            $stmt = $this->db->prepare("INSERT INTO properties (
                titulo, descripcion, tipo, precio, ubicacion, ciudad, sector, direccion, area, superficie_construida, habitaciones, banos, parqueos, caracteristicas, imagenes, video_url, plano_url, latitud, longitud, token_validacion, cliente_id, agente_id, fecha_creacion, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pendiente')");
            $stmt->bind_param(
                "ssssssssiiiiiissssssii",
                $data['titulo'],
                $data['descripcion'],
                $data['tipo'],
                $data['precio'],
                $data['ubicacion'],
                $data['ciudad'],
                $data['sector'],
                $data['direccion'],
                $data['area'],
                $data['superficie_construida'],
                $data['habitaciones'],
                $data['banos'],
                $data['parqueos'],
                $data['caracteristicas'],
                $imagenesStr,
                $data['video_url'],
                $data['plano_url'],
                $data['latitud'],
                $data['longitud'],
                $token,
                $clienteId,
                $data['agente_id']
            );
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Propuesta enviada. Un agente validará tu propiedad.', 'token' => $token];
            } else {
                return ['success' => false, 'message' => 'Error SQL: ' . $stmt->error];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Excepción: ' . $e->getMessage()];
        }
    }

    /**
     * Guarda imágenes en el servidor y retorna rutas
     */
    private function saveImages($imagenes) {
        $paths = [];
        if (!empty($imagenes['name'][0])) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($imagenes['tmp_name'] as $i => $tmpName) {
                if ($imagenes['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($imagenes['name'][$i], PATHINFO_EXTENSION);
                    $filename = uniqid('prop_') . '.' . $ext;
                    $dest = $uploadDir . $filename;
                    if (move_uploaded_file($tmpName, $dest)) {
                        $paths[] = 'assets/uploads/' . $filename;
                    }
                }
            }
        }
        return $paths;
    }

    /**
     * Genera un token único
     */
    private function generateToken() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Lista propiedades pendientes para validación de agente
     */
    public function getPendingProperties() {
        $sql = "SELECT * FROM properties WHERE estado = 'pendiente' ORDER BY fecha_creacion DESC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene una propiedad por ID
     */
    public function getPropertyById($id) {
        $stmt = $this->db->prepare("SELECT * FROM properties WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $property = $result->num_rows ? $result->fetch_assoc() : null;
        
        if ($property) {
            // Procesar imágenes
            $property['images'] = $this->processPropertyImages($property['imagenes']);
            
            // Registrar vista de la propiedad
            $this->recordPropertyView($id);
            
            // Obtener estadísticas de la propiedad
            $property['views'] = $this->getPropertyViews($id);
            $property['favorites_count'] = $this->getPropertyFavorites($id);
            $property['inquiries'] = $this->getPropertyInquiries($id);
            $property['appointments'] = $this->getPropertyAppointments($id);
            
            // Calcular días en el mercado
            $property['days_on_market'] = $this->calculateDaysOnMarket($property['fecha_creacion']);
            
            // Obtener información del agente
            if ($property['agente_id']) {
                $property['agent'] = $this->getAgentInfo($property['agente_id']);
            }
            
            // Procesar características
            if (!empty($property['caracteristicas'])) {
                $property['features'] = explode(',', $property['caracteristicas']);
                $property['features'] = array_map('trim', $property['features']);
            } else {
                $property['features'] = [];
            }
        }
        
        return $property;
    }

    /**
     * Procesa las imágenes de una propiedad
     */
    private function processPropertyImages($imagenes) {
        $images = [];
        
        if (empty($imagenes)) {
            // Si no hay imágenes, usar imagen por defecto
            $images[] = '/assets/img/property-default.svg';
        } else {
            // Primero intentar decodificar como JSON
            if (is_string($imagenes) && (strpos($imagenes, '[') === 0 || strpos($imagenes, '{') === 0)) {
                $decoded = json_decode($imagenes, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $images = $decoded;
                } else {
                    // Si no es JSON válido, tratar como string separado por comas
                    $images = explode(',', $imagenes);
                    $images = array_map('trim', $images);
                }
            } else if (is_string($imagenes)) {
                // String simple o separado por comas
                if (strpos($imagenes, ',') !== false) {
                    $images = explode(',', $imagenes);
                    $images = array_map('trim', $images);
                } else {
                    // String simple, una sola imagen
                    $images = [$imagenes];
                }
            } else if (is_array($imagenes)) {
                // Ya es un array
                $images = $imagenes;
            } else {
                // Formato desconocido, usar imagen por defecto
                $images[] = '/assets/img/property-default.svg';
            }
        }
        
        // Filtrar imágenes vacías y procesar rutas
        $processedImages = [];
        foreach ($images as $image) {
            if (!empty($image)) {
                $image = trim($image);
                // Si no empieza con http o /, agregar prefijo
                if (strpos($image, 'http') !== 0 && strpos($image, '/') !== 0) {
                    $image = '/' . $image;
                }
                $processedImages[] = $image;
            }
        }
        
        // Si no hay imágenes válidas, usar imagen por defecto
        if (empty($processedImages)) {
            $processedImages[] = '/assets/img/property-default.svg';
        }
        
        return $processedImages;
    }

    /**
     * Registra una vista de propiedad
     */
    private function recordPropertyView($propertyId) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $sessionId = session_id();
        
        // Verificar si ya se registró una vista en esta sesión
        $stmt = $this->db->prepare("SELECT id FROM property_views WHERE property_id = ? AND session_id = ? AND DATE(fecha_vista) = CURDATE()");
        $stmt->bind_param("is", $propertyId, $sessionId);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows == 0) {
            $stmt = $this->db->prepare("INSERT INTO property_views (property_id, ip_address, user_agent, referer, session_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $propertyId, $ipAddress, $userAgent, $referer, $sessionId);
            $stmt->execute();
        }
    }

    /**
     * Obtiene el número de vistas de una propiedad
     */
    private function getPropertyViews($propertyId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as views FROM property_views WHERE property_id = ?");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['views'] ?? 0;
    }

    /**
     * Obtiene el número de favoritos de una propiedad
     */
    private function getPropertyFavorites($propertyId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as favorites FROM favorites WHERE property_id = ?");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['favorites'] ?? 0;
    }

    /**
     * Obtiene el número de consultas de una propiedad
     */
    private function getPropertyInquiries($propertyId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as inquiries FROM property_contacts WHERE property_id = ?");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['inquiries'] ?? 0;
    }

    /**
     * Obtiene el número de citas de una propiedad
     */
    private function getPropertyAppointments($propertyId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as appointments FROM property_appointments WHERE property_id = ?");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['appointments'] ?? 0;
    }

    /**
     * Calcula los días que lleva una propiedad en el mercado
     */
    private function calculateDaysOnMarket($fechaCreacion) {
        $fechaCreacion = new DateTime($fechaCreacion);
        $fechaActual = new DateTime();
        return $fechaCreacion->diff($fechaActual)->days;
    }

    /**
     * Obtiene información del agente
     */
    private function getAgentInfo($agentId) {
        require_once __DIR__ . '/AgentModel.php';
        $agentModel = new AgentModel();
        
        $agent = $agentModel->getAgentById($agentId);
        
        // Si no se encuentra el agente, usar el agente por defecto
        if (!$agent) {
            $agent = $agentModel->getDefaultAgent();
        }
        
        return $agent;
    }

    /**
     * Guarda una consulta de contacto
     */
    public function saveContact($propertyId, $data) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $this->db->prepare("INSERT INTO property_contacts (property_id, name, email, phone, message, newsletter, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", 
            $propertyId, 
            $data['name'], 
            $data['email'], 
            $data['phone'], 
            $data['message'], 
            $data['newsletter'] ? 1 : 0, 
            $ipAddress, 
            $userAgent
        );
        
        return $stmt->execute();
    }

    /**
     * Guarda una cita agendada
     */
    public function saveAppointment($propertyId, $data) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $this->db->prepare("INSERT INTO property_appointments (property_id, name, email, phone, appointment_date, appointment_time, notes, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", 
            $propertyId, 
            $data['name'], 
            $data['email'], 
            $data['phone'], 
            $data['appointment_date'], 
            $data['appointment_time'], 
            $data['notes'], 
            $ipAddress, 
            $userAgent
        );
        
        return $stmt->execute();
    }

    /**
     * Guarda un reporte de propiedad
     */
    public function saveReport($propertyId, $data) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        
        $stmt = $this->db->prepare("INSERT INTO property_reports (property_id, reporter_email, reason, description, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", 
            $propertyId, 
            $data['email'], 
            $data['reason'], 
            $data['description'], 
            $ipAddress
        );
        
        return $stmt->execute();
    }

    /**
     * Obtiene propiedades similares basadas en tipo, ubicación y precio
     */
    public function getSimilarProperties($propertyId, $type, $city, $price, $limit = 4) {
        $priceRange = $price * 0.3; // 30% de rango de precio
        
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   (SELECT COUNT(*) FROM favorites f WHERE f.property_id = p.id) as favorites_count
            FROM properties p 
            WHERE p.id != ? 
              AND p.estado = 'activa' 
              AND p.tipo = ? 
              AND p.ciudad = ? 
              AND p.precio BETWEEN ? AND ?
            ORDER BY ABS(p.precio - ?) ASC, p.fecha_creacion DESC
            LIMIT ?
        ");
        
        $minPrice = $price - $priceRange;
        $maxPrice = $price + $priceRange;
        
        $stmt->bind_param("issdddi", $propertyId, $type, $city, $minPrice, $maxPrice, $price, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $properties = [];
        while ($row = $result->fetch_assoc()) {
            // Procesar imágenes
            $images = [];
            if (!empty($row['imagenes'])) {
                $imagenesData = json_decode($row['imagenes'], true);
                if (is_array($imagenesData)) {
                    $images = $imagenesData;
                } else {
                    $images = explode(',', $row['imagenes']);
                }
            }
            
            $row['image'] = !empty($images) ? $images[0] : 'assets/img/property-default.svg';
            $row['title'] = $row['titulo'];
            $row['bedrooms'] = $row['habitaciones'];
            $row['bathrooms'] = $row['banos'];
            $row['area'] = $row['area'];
            
            $properties[] = $row;
        }
        
        return $properties;
    }

    /**
     * Valida una propiedad (cambia estado a activa)
     */
    public function validateProperty($id, $token, $agenteId) {
        $stmt = $this->db->prepare("UPDATE properties SET estado = 'activa', agente_id = ?, fecha_validacion = NOW() WHERE id = ? AND token_validacion = ? AND estado = 'pendiente'");
        $stmt->bind_param("iis", $agenteId, $id, $token);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return ['success' => true, 'message' => 'Propiedad validada y publicada.'];
        } else {
            return ['success' => false, 'message' => 'Token inválido o propiedad ya validada.'];
        }
    }

    /**
     * Obtiene todas las propiedades activas
     */
    public function getActiveProperties() {
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM favorites f WHERE f.property_id = p.id) as favorites_count FROM properties p WHERE p.estado = 'activa' ORDER BY p.fecha_creacion DESC";
        $result = $this->db->query($sql);
        $properties = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        foreach ($properties as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);
        return $properties;
    }

    /**
     * Obtiene las propiedades activas más guardadas en favoritos
     */
    public function getMostFavoritedProperties($limit = 6, $offset = 0) {
        $sql = "SELECT p.id, p.titulo, p.descripcion, p.tipo, p.precio, p.ubicacion, p.ciudad, p.sector, p.area, p.habitaciones, p.banos, p.imagenes, p.estado, p.fecha_creacion, COUNT(f.id) as favorites_count
                FROM properties p
                LEFT JOIN favorites f ON p.id = f.property_id
                WHERE p.estado = 'activa'
                GROUP BY p.id, p.titulo, p.descripcion, p.tipo, p.precio, p.ubicacion, p.ciudad, p.sector, p.area, p.habitaciones, p.banos, p.imagenes, p.estado, p.fecha_creacion
                ORDER BY favorites_count DESC, p.fecha_creacion DESC
                LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $properties = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        foreach ($properties as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);
        return $properties;
    }

    /**
     * Busca propiedades usando criterios de búsqueda avanzada
     */
    public function searchProperties($searchParams) {
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM favorites f WHERE f.property_id = p.id) as favorites_count FROM properties p WHERE p.estado = 'activa'";
        $params = [];
        $types = '';
        
        // Búsqueda por tipo
        if (!empty($searchParams['tipo'])) {
            // Mapear frontend a base de datos
            $tipoMap = [
                'apartamento' => 'departamento',
                'local' => 'oficina'
            ];
            $tipoReal = $tipoMap[$searchParams['tipo']] ?? $searchParams['tipo'];
            $sql .= " AND p.tipo = ?";
            $params[] = $tipoReal;
            $types .= 's';
        }
        
        // Búsqueda por ubicación (título, ubicación, ciudad, sector)
        if (!empty($searchParams['ubicacion'])) {
            $sql .= " AND (p.titulo LIKE ? OR p.ubicacion LIKE ? OR p.ciudad LIKE ? OR p.sector LIKE ?)";
            $searchTerm = "%" . $searchParams['ubicacion'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ssss';
        }
        
        // Filtro por precio mínimo
        if (!empty($searchParams['precio_min']) && is_numeric($searchParams['precio_min'])) {
            $sql .= " AND p.precio >= ?";
            $params[] = floatval($searchParams['precio_min']);
            $types .= 'd';
        }
        
        // Filtro por precio máximo
        if (!empty($searchParams['precio_max']) && is_numeric($searchParams['precio_max'])) {
            $sql .= " AND p.precio <= ?";
            $params[] = floatval($searchParams['precio_max']);
            $types .= 'd';
        }
        
        // Filtro por habitaciones
        if (!empty($searchParams['habitaciones']) && is_numeric($searchParams['habitaciones'])) {
            if ($searchParams['habitaciones'] >= 4) {
                $sql .= " AND p.habitaciones >= 4";
            } else {
                $sql .= " AND p.habitaciones = ?";
                $params[] = intval($searchParams['habitaciones']);
                $types .= 'i';
            }
        }
        
        // Filtro por baños
        if (!empty($searchParams['banos']) && is_numeric($searchParams['banos'])) {
            if ($searchParams['banos'] >= 3) {
                $sql .= " AND p.banos >= 3";
            } else {
                $sql .= " AND p.banos = ?";
                $params[] = intval($searchParams['banos']);
                $types .= 'i';
            }
        }
        
        // Ordenamiento por relevancia y fecha
        $sql .= " ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $properties = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        
        // Procesar propiedades para la vista
        foreach ($properties as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);
        
        return $properties;
    }

    /**
     * Obtiene propiedades activas filtradas según los criterios recibidos
     */
    public function getFilteredProperties($filtros) {
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM favorites f WHERE f.property_id = p.id) as favorites_count FROM properties p WHERE p.estado = 'activa'";
        $params = [];
        $types = '';
        
        // Filtro por IDs de favoritos (solo favoritos del usuario)
        if (!empty($filtros['favorites_ids']) && is_array($filtros['favorites_ids'])) {
            $placeholders = str_repeat('?,', count($filtros['favorites_ids']) - 1) . '?';
            $sql .= " AND p.id IN ($placeholders)";
            foreach ($filtros['favorites_ids'] as $id) {
                $params[] = $id;
                $types .= 'i';
            }
        }
        
        // Filtros dinámicos
        if (!empty($filtros['tipo'])) {
            // Mapear frontend a base de datos
            $tipoMap = [
                'apartamento' => 'departamento',
                'local' => 'oficina'
            ];
            $tipoReal = $tipoMap[$filtros['tipo']] ?? $filtros['tipo'];
            $sql .= " AND p.tipo = ?";
            $params[] = $tipoReal;
            $types .= 's';
        }
        if (!empty($filtros['ubicacion'])) {
            $sql .= " AND (p.ubicacion LIKE ? OR p.ciudad LIKE ? OR p.sector LIKE ?)";
            $params[] = "%" . $filtros['ubicacion'] . "%";
            $params[] = "%" . $filtros['ubicacion'] . "%";
            $params[] = "%" . $filtros['ubicacion'] . "%";
            $types .= 'sss';
        }
        if (!empty($filtros['precio_min'])) {
            $sql .= " AND p.precio >= ?";
            $params[] = $filtros['precio_min'];
            $types .= 'd';
        }
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND p.precio <= ?";
            $params[] = $filtros['precio_max'];
            $types .= 'd';
        }
        if (!empty($filtros['habitaciones'])) {
            if ($filtros['habitaciones'] === '4') {
                $sql .= " AND p.habitaciones >= 4";
            } else {
                $sql .= " AND p.habitaciones = ?";
                $params[] = $filtros['habitaciones'];
                $types .= 'i';
            }
        }
        if (!empty($filtros['baños'])) {
            if ($filtros['baños'] === '3') {
                $sql .= " AND p.banos >= 3";
            } else {
                $sql .= " AND p.banos = ?";
                $params[] = $filtros['baños'];
                $types .= 'i';
            }
        }
        // Orden
        switch ($filtros['orden'] ?? 'reciente') {
            case 'precio_asc':
                $sql .= " ORDER BY p.precio ASC";
                break;
            case 'precio_desc':
                $sql .= " ORDER BY p.precio DESC";
                break;
            case 'area':
                $sql .= " ORDER BY p.area DESC";
                break;
            default:
                $sql .= " ORDER BY p.fecha_creacion DESC";
        }
        
        // Paginación
        if (isset($filtros['limit']) && is_numeric($filtros['limit'])) {
            $sql .= " LIMIT ?";
            $params[] = intval($filtros['limit']);
            $types .= 'i';
            
            if (isset($filtros['offset']) && is_numeric($filtros['offset'])) {
                $sql .= " OFFSET ?";
                $params[] = intval($filtros['offset']);
                $types .= 'i';
            }
        }
        
        $stmt = $this->db->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $properties = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        
        foreach ($properties as &$prop) {
            $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
            $imagenes = isset($prop['imagenes']) ? explode(',', $prop['imagenes']) : [];
            $prop['imagen_principal'] = $imagenes[0] ?? 'assets/images/placeholder.jpg';
        }
        unset($prop);
        return $properties;
    }

    /**
     * Cuenta el total de propiedades que coinciden con los filtros (sin limit/offset)
     */
    public function countFilteredProperties($filtros) {
        $sql = "SELECT COUNT(p.id) as total FROM properties p WHERE p.estado = 'activa'";
        $params = [];
        $types = '';
        
        // Aplicar los mismos filtros que en getFilteredProperties
        if (!empty($filtros['favorites_ids']) && is_array($filtros['favorites_ids'])) {
            $placeholders = str_repeat('?,', count($filtros['favorites_ids']) - 1) . '?';
            $sql .= " AND p.id IN ($placeholders)";
            foreach ($filtros['favorites_ids'] as $id) {
                $params[] = $id;
                $types .= 'i';
            }
        }
        
        if (!empty($filtros['tipo'])) {
            $tipoMap = [
                'apartamento' => 'departamento',
                'local' => 'oficina'
            ];
            $tipoReal = $tipoMap[$filtros['tipo']] ?? $filtros['tipo'];
            $sql .= " AND p.tipo = ?";
            $params[] = $tipoReal;
            $types .= 's';
        }
        if (!empty($filtros['ubicacion'])) {
            $sql .= " AND (p.ubicacion LIKE ? OR p.ciudad LIKE ? OR p.sector LIKE ?)";
            $params[] = "%" . $filtros['ubicacion'] . "%";
            $params[] = "%" . $filtros['ubicacion'] . "%";
            $params[] = "%" . $filtros['ubicacion'] . "%";
            $types .= 'sss';
        }
        if (!empty($filtros['precio_min'])) {
            $sql .= " AND p.precio >= ?";
            $params[] = $filtros['precio_min'];
            $types .= 'd';
        }
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND p.precio <= ?";
            $params[] = $filtros['precio_max'];
            $types .= 'd';
        }
        if (!empty($filtros['habitaciones'])) {
            if ($filtros['habitaciones'] === '4') {
                $sql .= " AND p.habitaciones >= 4";
            } else {
                $sql .= " AND p.habitaciones = ?";
                $params[] = $filtros['habitaciones'];
                $types .= 'i';
            }
        }
        if (!empty($filtros['baños'])) {
            if ($filtros['baños'] === '3') {
                $sql .= " AND p.banos >= 3";
            } else {
                $sql .= " AND p.banos = ?";
                $params[] = $filtros['baños'];
                $types .= 'i';
            }
        }
        
        $stmt = $this->db->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return intval($row['total']);
    }

    /**
     * Obtiene propiedades de un agente específico
     */
    public function getPropertiesByAgent($agentId, $limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   COALESCE(v.views, 0) as vistas
            FROM properties p
            LEFT JOIN (
                SELECT property_id, COUNT(*) as views
                FROM property_views
                GROUP BY property_id
            ) v ON p.id = v.property_id
            WHERE p.agente_id = ? 
            ORDER BY p.fecha_creacion DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $agentId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
        /**
     * Obtiene contactos de una propiedad específica
     */
    public function getPropertyContacts($propertyId) {
        $stmt = $this->db->prepare("
            SELECT * FROM property_contacts 
            WHERE property_id = ? 
            ORDER BY fecha_creacion DESC
        ");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    /**
     * Obtiene todas las citas de una propiedad específica
     */
    public function getAllPropertyAppointments($propertyId) {
        $stmt = $this->db->prepare("
            SELECT * FROM property_appointments 
            WHERE property_id = ? 
            ORDER BY appointment_date DESC, appointment_time DESC
        ");
        $stmt->bind_param("i", $propertyId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
 
} 