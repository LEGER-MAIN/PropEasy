<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para el Dashboard del Agente
 * Maneja la lógica específica para agentes inmobiliarios
 */
class AgentController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        parent::__construct();
        // Verificar que el usuario sea un agente
        $this->checkAgentRole();
    }

    /**
     * Verifica que el usuario tenga rol de agente
     */
    private function checkAgentRole()
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está autenticado y es agente
        if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated'] || 
            !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'agent') {
            header('Location: /auth/login');
            exit;
        }
        
        // Obtener y guardar el agent_id basado en el user_id
        if (!isset($_SESSION['agent_id'])) {
            $this->setAgentId();
        }
    }
    
    /**
     * Obtiene y establece el agent_id basado en el user_id
     */
    private function setAgentId()
    {
        if (!isset($_SESSION['user_id'])) {
            return;
        }
        
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Buscar agente por user_id
        $agent = $agentModel->getAgentByUserId($_SESSION['user_id']);
        
        if ($agent) {
            $_SESSION['agent_id'] = $agent['id'];
        } else {
            // Si no hay agent_id, usar agente por defecto (ID 1)
            $_SESSION['agent_id'] = 1;
        }
    }

    /**
     * Muestra el dashboard principal del agente
     */
    public function dashboard()
    {
        // Obtener estadísticas del agente
        $stats = $this->getAgentStats();
        
        // Obtener propiedades del agente
        $myProperties = $this->getMyProperties();
        
        // Obtener citas recientes
        $recentAppointments = $this->getRecentAppointments();
        
        // Obtener mensajes recientes
        $recentMessages = $this->getRecentMessages();

        // Preparar datos para la vista
        $data = [
            'titulo' => 'Dashboard Agente - PropEasy',
            'stats' => $stats,
            'myProperties' => $myProperties,
            'recentAppointments' => $recentAppointments,
            'recentMessages' => $recentMessages
        ];

        // Renderizar vista
        $this->render('dashboard/agent', $data);
    }

    /**
     * Método index - redirige al dashboard
     */
    public function index()
    {
        $this->dashboard();
    }

    /**
     * Obtiene las estadísticas del agente
     */
    private function getAgentStats()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Obtener ID del agente de la sesión (por ahora usar agente por defecto)
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        $stats = $agentModel->getAgentStats($agentId);
        
        // Asegurar que todas las variables básicas existen con valores por defecto
        $stats['active_properties'] = (int)($stats['active_properties'] ?? 0);
        $stats['sold_properties'] = (int)($stats['sold_properties'] ?? 0);
        $stats['total_appointments'] = (int)($stats['total_appointments'] ?? 0);
        $stats['pending_appointments'] = (int)($stats['pending_appointments'] ?? 0);
        $stats['total_contacts'] = (int)($stats['total_contacts'] ?? 0);
        $stats['unread_contacts'] = (int)($stats['unread_contacts'] ?? 0);
        
        // Agregar estadísticas adicionales calculadas
        $stats['total_properties'] = $stats['active_properties'] + $stats['sold_properties'];
        $stats['conversion_rate'] = $stats['total_properties'] > 0 ? 
            round(($stats['sold_properties'] / $stats['total_properties']) * 100, 1) : 0;
        $stats['response_rate'] = 95; // Simulado
        $stats['client_satisfaction'] = 4.8; // Simulado
        
        // Asegurar que todas las variables necesarias existen
        $stats['my_properties'] = $stats['total_properties'];
        $stats['active_clients'] = $stats['total_contacts']; // Simplificado
        $stats['total_clients'] = $stats['total_contacts'];
        $stats['completed_appointments'] = max(0, $stats['total_appointments'] - $stats['pending_appointments']);
        $stats['total_commission'] = $stats['sold_properties'] * 50000; // Simulado
        $stats['commission_this_month'] = $stats['total_commission'] * 0.1; // Simulado
        
        return $stats;
    }

    /**
     * Obtiene las propiedades del agente con filtros avanzados
     */
    private function getMyProperties($limit = 10, $filters = [])
    {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Obtener ID del agente de la sesión
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        // Obtener propiedades del agente con filtros
        $properties = $this->getFilteredAgentProperties($agentId, $filters, $limit);
        
        // Formatear las propiedades para la vista
        $formattedProperties = [];
        foreach ($properties as $property) {
            // Procesar imágenes
            $images = $this->processPropertyImages($property['imagenes'] ?? '');
            
            $formattedProperties[] = [
                'id' => $property['id'],
                'title' => $property['titulo'],
                'price' => $property['precio'],
                'status' => ucfirst($property['estado']),
                'views' => $property['vistas'] ?? 0,
                'inquiries' => $this->getPropertyInquiries($property['id']),
                'tipo' => $property['tipo'],
                'ubicacion' => $property['ubicacion'],
                'fecha_creacion' => $property['fecha_creacion'],
                'fecha_actualizacion' => $property['fecha_actualizacion'] ?? $property['fecha_creacion'],
                'images' => $images,
                'image' => $images[0] ?? '/assets/img/property-default.svg',
                'days_on_market' => $this->calculateDaysOnMarket($property['fecha_creacion']),
                'area' => $property['area'],
                'habitaciones' => $property['habitaciones'],
                'banos' => $property['banos']
            ];
        }
        
        return $formattedProperties;
    }

    /**
     * Obtiene propiedades filtradas del agente
     */
    private function getFilteredAgentProperties($agentId, $filters = [], $limit = 10)
    {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Usar el método existente del PropertyModel y luego filtrar
        $allProperties = $propertyModel->getPropertiesByAgent($agentId, 1000); // Obtener todas primero
        
        $filteredProperties = [];
        
        foreach ($allProperties as $property) {
            $include = true;
            
            // Aplicar filtros
            if (!empty($filters['status']) && strtolower($property['estado']) !== strtolower($filters['status'])) {
                $include = false;
            }
            
            if (!empty($filters['tipo']) && strtolower($property['tipo']) !== strtolower($filters['tipo'])) {
                $include = false;
            }
            
            if (!empty($filters['search'])) {
                $searchTerm = strtolower($filters['search']);
                $searchableText = strtolower($property['titulo'] . ' ' . $property['ubicacion'] . ' ' . $property['descripcion']);
                if (strpos($searchableText, $searchTerm) === false) {
                    $include = false;
                }
            }
            
            if (!empty($filters['price_min']) && $property['precio'] < $filters['price_min']) {
                $include = false;
            }
            
            if (!empty($filters['price_max']) && $property['precio'] > $filters['price_max']) {
                $include = false;
            }
            
            if ($include) {
                // Agregar información adicional
                $property['vistas'] = $this->getPropertyViewCount($property['id']);
                $property['fecha_actualizacion'] = $property['fecha_actualizacion'] ?? $property['fecha_creacion'];
                $filteredProperties[] = $property;
            }
        }
        
        // Ordenamiento
        $orderBy = $filters['order_by'] ?? 'fecha_creacion';
        $orderDir = $filters['order_dir'] ?? 'DESC';
        
        usort($filteredProperties, function($a, $b) use ($orderBy, $orderDir) {
            $aValue = $a[$orderBy] ?? '';
            $bValue = $b[$orderBy] ?? '';
            
            $result = strcmp($aValue, $bValue);
            return $orderDir === 'ASC' ? $result : -$result;
        });
        
        // Aplicar límite
        return array_slice($filteredProperties, 0, $limit);
    }
    
    /**
     * Obtiene el número de vistas de una propiedad
     */
    private function getPropertyViewCount($propertyId)
    {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Crear una consulta simple usando el modelo
        try {
            // Por ahora retornar un valor simulado, ya que no tenemos acceso directo a la DB
            return rand(10, 100);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula días en el mercado
     */
    private function calculateDaysOnMarket($fechaCreacion)
    {
        $now = new DateTime();
        $created = new DateTime($fechaCreacion);
        $diff = $now->diff($created);
        
        return $diff->days;
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
     * Obtiene el número de consultas de una propiedad
     */
    private function getPropertyInquiries($propertyId)
    {
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        // Contar contactos + citas para la propiedad
        $contacts = $propertyModel->getPropertyContacts($propertyId);
        $appointments = $propertyModel->getAllPropertyAppointments($propertyId);
        
        return count($contacts) + count($appointments);
    }

    /**
     * Obtiene las citas recientes del agente
     */
    private function getRecentAppointments()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Obtener ID del agente de la sesión
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        $appointments = $agentModel->getAgentAppointments($agentId, null, 5);
        
        // Formatear las citas para la vista
        $formattedAppointments = [];
        foreach ($appointments as $appointment) {
            $formattedAppointments[] = [
                'id' => $appointment['id'],
                'client' => $appointment['name'],
                'property' => $appointment['property_title'],
                'date' => date('d/m/Y', strtotime($appointment['appointment_date'])),
                'time' => date('H:i', strtotime($appointment['appointment_time'])),
                'status' => ucfirst($appointment['status']),
                'phone' => $appointment['phone'],
                'email' => $appointment['email'],
                'notes' => $appointment['notes']
            ];
        }
        
        return $formattedAppointments;
    }

    /**
     * Obtiene los mensajes recientes del agente
     */
    private function getRecentMessages()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Obtener ID del agente de la sesión
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        $contacts = $agentModel->getAgentContacts($agentId, 5);
        
        // Formatear los mensajes para la vista
        $formattedMessages = [];
        foreach ($contacts as $contact) {
            $timeDiff = $this->getTimeDifference($contact['fecha_creacion']);
            
            $formattedMessages[] = [
                'id' => $contact['id'],
                'client' => $contact['name'],
                'property' => $contact['property_title'],
                'message' => substr($contact['message'], 0, 100) . (strlen($contact['message']) > 100 ? '...' : ''),
                'date' => $timeDiff,
                'unread' => $contact['status'] === 'nuevo',
                'phone' => $contact['phone'],
                'email' => $contact['email'],
                'full_message' => $contact['message']
            ];
        }
        
        return $formattedMessages;
    }
    
    /**
     * Calcula la diferencia de tiempo en formato legible
     */
    private function getTimeDifference($datetime)
    {
        $now = new DateTime();
        $past = new DateTime($datetime);
        $diff = $now->diff($past);
        
        if ($diff->days > 0) {
            return $diff->days === 1 ? 'Hace 1 día' : "Hace {$diff->days} días";
        } elseif ($diff->h > 0) {
            return $diff->h === 1 ? 'Hace 1 hora' : "Hace {$diff->h} horas";
        } elseif ($diff->i > 0) {
            return $diff->i === 1 ? 'Hace 1 minuto' : "Hace {$diff->i} minutos";
        } else {
            return 'Hace unos segundos';
        }
    }

    /**
     * Muestra la lista de propiedades del agente
     */
    public function myProperties()
    {
        $properties = $this->getMyProperties();
        
        $data = [
            'titulo' => 'Mis Propiedades - PropEasy',
            'properties' => $properties
        ];

        $this->render('agent/properties', $data);
    }

    /**
     * Muestra la lista de clientes del agente
     */
    public function myClients()
    {
        $clients = $this->getMyClients();
        
        $data = [
            'titulo' => 'Mis Clientes - PropEasy',
            'clients' => $clients
        ];

        $this->render('agent/clients', $data);
    }

    /**
     * Obtiene los clientes del agente
     */
    private function getMyClients()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Obtener ID del agente de la sesión
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        // Obtener contactos únicos del agente (clientes)
        $contacts = $agentModel->getAgentContacts($agentId, 50);
        
        // Agrupar por email para obtener clientes únicos
        $clientsMap = [];
        foreach ($contacts as $contact) {
            $email = $contact['email'];
            if (!isset($clientsMap[$email])) {
                $clientsMap[$email] = [
                    'id' => $contact['id'],
                    'name' => $contact['name'],
                    'email' => $contact['email'],
                    'phone' => $contact['phone'],
                    'status' => 'Activo',
                    'properties_viewed' => 1,
                    'last_contact' => $this->getTimeDifference($contact['fecha_creacion']),
                    'first_contact' => $contact['fecha_creacion']
                ];
            } else {
                // Actualizar datos si es un contacto más reciente
                if (strtotime($contact['fecha_creacion']) > strtotime($clientsMap[$email]['first_contact'])) {
                    $clientsMap[$email]['last_contact'] = $this->getTimeDifference($contact['fecha_creacion']);
                }
                $clientsMap[$email]['properties_viewed']++;
            }
        }
        
        // Convertir a array y ordenar por último contacto
        $clients = array_values($clientsMap);
        usort($clients, function($a, $b) {
            return strtotime($b['first_contact']) - strtotime($a['first_contact']);
        });
        
        return $clients;
    }

    /**
     * Muestra la agenda del agente
     */
    public function appointments()
    {
        $appointments = $this->getAllAppointments();
        
        $data = [
            'titulo' => 'Mi Agenda - PropEasy',
            'appointments' => $appointments
        ];

        $this->render('agent/appointments', $data);
    }

    /**
     * Obtiene todas las citas del agente
     */
    private function getAllAppointments()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Obtener ID del agente de la sesión
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        // Obtener citas del agente
        $appointments = $agentModel->getAgentAppointments($agentId, null, 50);
        
        // Formatear las citas para la vista
        $formattedAppointments = [];
        foreach ($appointments as $appointment) {
            $formattedAppointments[] = [
                'id' => $appointment['id'],
                'client' => $appointment['name'],
                'property' => $appointment['property_title'],
                'date' => date('d/m/Y', strtotime($appointment['appointment_date'])),
                'time' => date('H:i', strtotime($appointment['appointment_time'])),
                'status' => ucfirst($appointment['status']),
                'notes' => $appointment['notes'] ?? '',
                'phone' => $appointment['phone'],
                'email' => $appointment['email']
            ];
        }
        
        return $formattedAppointments;
    }

    /**
     * Muestra los mensajes del agente
     */
    public function messages()
    {
        $messages = $this->getAllMessages();
        
        $data = [
            'titulo' => 'Mis Mensajes - PropEasy',
            'messages' => $messages
        ];

        $this->render('agent/messages', $data);
    }

    /**
     * Obtiene todos los mensajes del agente
     */
    private function getAllMessages()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        // Obtener ID del agente de la sesión
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        // Obtener contactos del agente
        $contacts = $agentModel->getAgentContacts($agentId, 50);
        
        // Formatear los mensajes para la vista
        $formattedMessages = [];
        foreach ($contacts as $contact) {
            $timeDiff = $this->getTimeDifference($contact['fecha_creacion']);
            
            $formattedMessages[] = [
                'id' => $contact['id'],
                'client' => $contact['name'],
                'property' => $contact['property_title'],
                'message' => $contact['message'],
                'date' => $timeDiff,
                'unread' => $contact['status'] === 'nuevo',
                'email' => $contact['email'],
                'phone' => $contact['phone'],
                'full_message' => $contact['message']
            ];
        }
        
        return $formattedMessages;
    }

    /**
     * API para obtener estadísticas en tiempo real
     */
    public function getStats()
    {
        header('Content-Type: application/json');
        
        $stats = $this->getAgentStats();
        
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * API para marcar mensaje como leído
     */
    public function markMessageAsRead()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $messageId = $_POST['message_id'] ?? null;
        
        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'ID de mensaje requerido']);
            return;
        }

        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        $success = $agentModel->markContactAsRead($messageId);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Mensaje marcado como leído' : 'Error al marcar mensaje'
        ]);
    }

    /**
     * API para confirmar cita
     */
    public function confirmAppointment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $appointmentId = $_POST['appointment_id'] ?? null;
        $status = $_POST['status'] ?? 'confirmada';
        
        if (!$appointmentId) {
            echo json_encode(['success' => false, 'message' => 'ID de cita requerido']);
            return;
        }

        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        
        $success = $agentModel->updateAppointmentStatus($appointmentId, $status);
        
        $statusMessages = [
            'confirmada' => 'Cita confirmada exitosamente',
            'completada' => 'Cita marcada como completada',
            'cancelada' => 'Cita cancelada'
        ];
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $success ? ($statusMessages[$status] ?? 'Estado actualizado') : 'Error al actualizar cita'
        ]);
    }

    /**
     * Alias para myProperties - compatibilidad con rutas
     */
    public function properties()
    {
        $this->myProperties();
    }
    
    /**
     * Alias para myClients - compatibilidad con rutas
     */
    public function clients()
    {
        $this->myClients();
    }

    /**
     * Muestra estadísticas detalladas del agente
     */
    public function stats()
    {
        // Obtener estadísticas del agente
        $stats = $this->getAgentStats();
        
        // Obtener estadísticas adicionales
        require_once __DIR__ . '/../models/AgentModel.php';
        require_once __DIR__ . '/../models/PropertyModel.php';
        
        $agentModel = new AgentModel();
        $propertyModel = new PropertyModel();
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        // Estadísticas mensuales (últimos 6 meses)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));
            
            // Simular datos mensuales (en producción vendrían de la BD)
            $monthlyStats[] = [
                'month' => $monthName,
                'properties_sold' => rand(1, 5),
                'appointments' => rand(8, 25),
                'contacts' => rand(15, 40),
                'revenue' => rand(50000, 200000)
            ];
        }
        
        // Top propiedades más vistas
        $topProperties = $propertyModel->getPropertiesByAgent($agentId, 5);
        
        // Datos para gráficos
        $chartData = [
            'monthly_sales' => array_column($monthlyStats, 'properties_sold'),
            'monthly_labels' => array_column($monthlyStats, 'month'),
            'property_types' => [
                'Casa' => rand(20, 40),
                'Departamento' => rand(15, 35), 
                'Oficina' => rand(5, 15),
                'Terreno' => rand(3, 10)
            ]
        ];
        
        $data = [
            'titulo' => 'Estadísticas Detalladas - PropEasy',
            'stats' => $stats,
            'monthlyStats' => $monthlyStats,
            'topProperties' => $topProperties,
            'chartData' => $chartData
        ];
        
        $this->render('agent/stats', $data);
    }

    /**
     * Muestra la configuración del agente
     */
    public function settings()
    {
        $data = [
            'titulo' => 'Configuración - PropEasy',
            'stats' => $this->getAgentStats() // Para notificaciones en nav
        ];
        
        $this->render('agent/settings', $data);
    }

    /**
     * Muestra el perfil del agente
     */
    public function profile()
    {
        require_once __DIR__ . '/../models/AgentModel.php';
        $agentModel = new AgentModel();
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        $agent = $agentModel->getAgentById($agentId);
        
        $data = [
            'titulo' => 'Mi Perfil - PropEasy',
            'agent' => $agent,
            'stats' => $this->getAgentStats() // Para notificaciones en nav
        ];
        
        $this->render('agent/profile', $data);
    }

    /**
     * API para gestión avanzada de propiedades
     */
    public function propertiesApi()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $action = $_POST['action'] ?? '';
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        switch ($action) {
            case 'filter':
                $this->filterProperties();
                break;
            case 'change_status':
                $this->changePropertyStatus();
                break;
            case 'bulk_action':
                $this->bulkPropertyAction();
                break;
            case 'assign_property':
                $this->assignProperty();
                break;
            case 'unassign_property':
                $this->unassignProperty();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
    }

    /**
     * Filtra propiedades del agente
     */
    private function filterProperties()
    {
        $filters = [
            'status' => $_POST['status'] ?? '',
            'tipo' => $_POST['tipo'] ?? '',
            'search' => $_POST['search'] ?? '',
            'price_min' => $_POST['price_min'] ?? '',
            'price_max' => $_POST['price_max'] ?? '',
            'order_by' => $_POST['order_by'] ?? 'fecha_creacion',
            'order_dir' => $_POST['order_dir'] ?? 'DESC'
        ];
        
        $limit = (int)($_POST['limit'] ?? 20);
        $properties = $this->getMyProperties($limit, $filters);
        
        echo json_encode([
            'success' => true,
            'data' => $properties,
            'count' => count($properties)
        ]);
    }

    /**
     * Cambia el estado de una propiedad
     */
    private function changePropertyStatus()
    {
        $propertyId = (int)($_POST['property_id'] ?? 0);
        $newStatus = $_POST['new_status'] ?? '';
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        if (!$propertyId || !$newStatus) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
        
        // Verificar que la propiedad pertenece al agente
        if (!$this->verifyPropertyOwnership($propertyId, $agentId)) {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para esta propiedad']);
            return;
        }
        
        // Validar estado
        $validStatuses = ['pendiente', 'activa', 'vendida', 'rentada', 'retirada'];
        if (!in_array($newStatus, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Estado no válido']);
            return;
        }
        
        require_once __DIR__ . '/../models/PropertyModel.php';
        $propertyModel = new PropertyModel();
        
        $sql = "UPDATE properties SET estado = ?, fecha_actualizacion = NOW() WHERE id = ? AND agente_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $newStatus, $propertyId, $agentId);
        
        if ($stmt->execute()) {
            $this->logPropertyAction($propertyId, "Estado cambiado a: {$newStatus}");
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
        }
    }

    /**
     * Ejecuta acciones masivas en propiedades
     */
    private function bulkPropertyAction()
    {
        $propertyIds = $_POST['property_ids'] ?? [];
        $action = $_POST['bulk_action'] ?? '';
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        if (empty($propertyIds) || !$action) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($propertyIds as $propertyId) {
            if (!$this->verifyPropertyOwnership($propertyId, $agentId)) {
                $errorCount++;
                continue;
            }
            
            switch ($action) {
                case 'activate':
                    if ($this->updatePropertyStatus($propertyId, 'activa', $agentId)) {
                        $successCount++;
                        $this->logPropertyAction($propertyId, "Activada masivamente");
                    } else {
                        $errorCount++;
                    }
                    break;
                case 'deactivate':
                    if ($this->updatePropertyStatus($propertyId, 'retirada', $agentId)) {
                        $successCount++;
                        $this->logPropertyAction($propertyId, "Retirada masivamente");
                    } else {
                        $errorCount++;
                    }
                    break;
                case 'mark_sold':
                    if ($this->updatePropertyStatus($propertyId, 'vendida', $agentId)) {
                        $successCount++;
                        $this->logPropertyAction($propertyId, "Marcada como vendida");
                    } else {
                        $errorCount++;
                    }
                    break;
                default:
                    $errorCount++;
            }
        }
        
        echo json_encode([
            'success' => $successCount > 0,
            'message' => "Acción completada. {$successCount} exitosas, {$errorCount} con errores",
            'success_count' => $successCount,
            'error_count' => $errorCount
        ]);
    }

    /**
     * Asigna una propiedad al agente (si es admin)
     */
    private function assignProperty()
    {
        // Solo administradores pueden asignar propiedades
        if ($_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para esta acción']);
            return;
        }
        
        $propertyId = (int)($_POST['property_id'] ?? 0);
        $agentId = (int)($_POST['agent_id'] ?? 0);
        
        if (!$propertyId || !$agentId) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
        
        $sql = "UPDATE properties SET agente_id = ?, fecha_actualizacion = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $agentId, $propertyId);
        
        if ($stmt->execute()) {
            $this->logPropertyAction($propertyId, "Asignada al agente ID: {$agentId}");
            echo json_encode(['success' => true, 'message' => 'Propiedad asignada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al asignar propiedad']);
        }
    }

    /**
     * Desasigna una propiedad del agente
     */
    private function unassignProperty()
    {
        $propertyId = (int)($_POST['property_id'] ?? 0);
        $agentId = $_SESSION['agent_id'] ?? 1;
        
        if (!$propertyId) {
            echo json_encode(['success' => false, 'message' => 'ID de propiedad requerido']);
            return;
        }
        
        // Verificar que la propiedad pertenece al agente
        if (!$this->verifyPropertyOwnership($propertyId, $agentId)) {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para esta propiedad']);
            return;
        }
        
        $sql = "UPDATE properties SET agente_id = NULL, fecha_actualizacion = NOW() WHERE id = ? AND agente_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $propertyId, $agentId);
        
        if ($stmt->execute()) {
            $this->logPropertyAction($propertyId, "Desasignada del agente");
            echo json_encode(['success' => true, 'message' => 'Propiedad desasignada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al desasignar propiedad']);
        }
    }

    /**
     * Verifica que una propiedad pertenece al agente
     */
    private function verifyPropertyOwnership($propertyId, $agentId)
    {
        $stmt = $this->db->prepare("SELECT id FROM properties WHERE id = ? AND agente_id = ?");
        $stmt->bind_param("ii", $propertyId, $agentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }

    /**
     * Actualiza el estado de una propiedad
     */
    private function updatePropertyStatus($propertyId, $status, $agentId)
    {
        $stmt = $this->db->prepare("UPDATE properties SET estado = ?, fecha_actualizacion = NOW() WHERE id = ? AND agente_id = ?");
        $stmt->bind_param("sii", $status, $propertyId, $agentId);
        
        return $stmt->execute();
    }

    /**
     * Registra una acción realizada en una propiedad (log)
     */
    private function logPropertyAction($propertyId, $action)
    {
        $agentId = $_SESSION['agent_id'] ?? 1;
        $userId = $_SESSION['user_id'] ?? 1;
        
        // Crear tabla de logs si no existe
        $createLogTable = "CREATE TABLE IF NOT EXISTS property_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            property_id INT NOT NULL,
            agent_id INT NULL,
            user_id INT NULL,
            action VARCHAR(255) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->query($createLogTable);
        
        $stmt = $this->db->prepare("INSERT INTO property_logs (property_id, agent_id, user_id, action) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $propertyId, $agentId, $userId, $action);
        $stmt->execute();
    }

    /**
     * Obtiene propiedades no asignadas (solo para admins)
     */
    public function unassignedProperties()
    {
        if ($_SESSION['user_role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            return;
        }
        
        header('Content-Type: application/json');
        
        $sql = "SELECT p.*, 
                       (SELECT COUNT(*) FROM property_views pv WHERE pv.property_id = p.id) as vistas
                FROM properties p 
                WHERE p.agente_id IS NULL AND p.estado != 'rechazada'
                ORDER BY p.fecha_creacion DESC";
        
        $result = $this->db->query($sql);
        $properties = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        
        echo json_encode([
            'success' => true,
            'data' => $properties,
            'count' => count($properties)
        ]);
    }
} 