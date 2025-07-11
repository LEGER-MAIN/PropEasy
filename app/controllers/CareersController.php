<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para la página de Carreras
 * Maneja la visualización de vacantes y postulaciones
 */
class CareersController extends Controller {
    
    /**
     * Constructor del controlador
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Muestra la página principal de carreras
     */
    public function index() {
        // Datos para la vista
        $data = [
            'titulo' => 'Carreras - PropEasy',
            'descripcion' => 'Forma parte de una empresa líder en el sector inmobiliario y desarrolla tu carrera profesional con nosotros.',
            'palabras_clave' => 'carreras, empleo, trabajo, vacantes, PropEasy, inmobiliaria',
            'stats' => [
                'total_vacantes' => 12,
                'empleados' => 150,
                'oficinas' => 8,
                'anos_experiencia' => 10,
                'premios' => 25
            ],
            'vacantes' => [
                [
                    'id' => 1,
                    'titulo' => 'Agente Inmobiliario Senior',
                    'descripcion' => 'Buscamos un agente experimentado para liderar ventas en el sector residencial.',
                    'departamento' => 'Ventas',
                    'departamento_slug' => 'ventas',
                    'ubicacion' => 'Santiago',
                    'ubicacion_slug' => 'santiago',
                    'tipo' => 'Tiempo Completo',
                    'tipo_color' => 'primary',
                    'tipo_contrato' => 'Indefinido',
                    'salario' => '$800.000 - $1.200.000',
                    'fecha_publicacion' => '15 Enero, 2024',
                    'habilidades' => ['Ventas', 'Negociación', 'Atención al Cliente', 'Manejo de CRM']
                ],
                [
                    'id' => 2,
                    'titulo' => 'Desarrollador Full Stack',
                    'descripcion' => 'Desarrollador para crear y mantener aplicaciones web y móviles.',
                    'departamento' => 'Tecnología',
                    'departamento_slug' => 'tecnologia',
                    'ubicacion' => 'Remoto',
                    'ubicacion_slug' => 'remoto',
                    'tipo' => 'Tiempo Completo',
                    'tipo_color' => 'success',
                    'tipo_contrato' => 'Indefinido',
                    'salario' => '$1.500.000 - $2.500.000',
                    'fecha_publicacion' => '12 Enero, 2024',
                    'habilidades' => ['PHP', 'JavaScript', 'React', 'MySQL', 'Git']
                ],
                [
                    'id' => 3,
                    'titulo' => 'Marketing Digital',
                    'descripcion' => 'Especialista en marketing digital para estrategias online.',
                    'departamento' => 'Marketing',
                    'departamento_slug' => 'marketing',
                    'ubicacion' => 'Santiago',
                    'ubicacion_slug' => 'santiago',
                    'tipo' => 'Tiempo Completo',
                    'tipo_color' => 'info',
                    'tipo_contrato' => 'Indefinido',
                    'salario' => '$900.000 - $1.400.000',
                    'fecha_publicacion' => '10 Enero, 2024',
                    'habilidades' => ['Google Ads', 'Facebook Ads', 'SEO', 'Analytics']
                ]
            ],
            'departamentos' => [
                [
                    'nombre' => 'Ventas',
                    'slug' => 'ventas',
                    'icono' => 'handshake',
                    'vacantes' => 5
                ],
                [
                    'nombre' => 'Marketing',
                    'slug' => 'marketing',
                    'icono' => 'bullhorn',
                    'vacantes' => 3
                ],
                [
                    'nombre' => 'Tecnología',
                    'slug' => 'tecnologia',
                    'icono' => 'laptop-code',
                    'vacantes' => 2
                ],
                [
                    'nombre' => 'Administración',
                    'slug' => 'administracion',
                    'icono' => 'cogs',
                    'vacantes' => 2
                ]
            ],
            'ubicaciones' => [
                [
                    'nombre' => 'Santiago',
                    'slug' => 'santiago',
                    'vacantes' => 8
                ],
                [
                    'nombre' => 'Valparaíso',
                    'slug' => 'valparaiso',
                    'vacantes' => 2
                ],
                [
                    'nombre' => 'Concepción',
                    'slug' => 'concepcion',
                    'vacantes' => 1
                ],
                [
                    'nombre' => 'Remoto',
                    'slug' => 'remoto',
                    'vacantes' => 1
                ]
            ]
        ];
        
        // Cargar la vista
        $this->render('careers/index', $data);
    }
    
    /**
     * API para buscar empleos
     */
    public function search() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener criterios de búsqueda
        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'] ?? '';
        $departamento = $input['departamento'] ?? '';
        $ubicacion = $input['ubicacion'] ?? '';
        
        // Simular búsqueda en base de datos
        $resultados = $this->buscarEmpleos($query, $departamento, $ubicacion);
        
        $response = [
            'query' => $query,
            'departamento' => $departamento,
            'ubicacion' => $ubicacion,
            'total_resultados' => count($resultados),
            'resultados' => $resultados
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para postular a un empleo
     */
    public function apply() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $empleoId = $input['empleo_id'] ?? '';
        $nombre = $input['nombre'] ?? '';
        $email = $input['email'] ?? '';
        $telefono = $input['telefono'] ?? '';
        $cv = $input['cv'] ?? '';
        $mensaje = $input['mensaje'] ?? '';
        
        if (empty($empleoId) || empty($nombre) || empty($email)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos requeridos incompletos']);
            return;
        }
        
        // Simular registro de postulación
        $success = $this->registrarPostulacion($empleoId, $nombre, $email, $telefono, $cv, $mensaje);
        
        if ($success) {
            $response = [
                'success' => true,
                'message' => 'Postulación enviada correctamente. Te contactaremos pronto.',
                'empleo_id' => $empleoId
            ];
        } else {
            http_response_code(500);
            $response = [
                'success' => false,
                'error' => 'Error al enviar la postulación'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para obtener detalles de un empleo
     */
    public function getJobDetails() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $empleoId = $_GET['id'] ?? '';
        
        if (empty($empleoId)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de empleo requerido']);
            return;
        }
        
        // Simular obtención de detalles
        $detalles = $this->getDetallesEmpleo($empleoId);
        
        if ($detalles) {
            $response = [
                'success' => true,
                'empleo' => $detalles
            ];
        } else {
            http_response_code(404);
            $response = [
                'success' => false,
                'error' => 'Empleo no encontrado'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para obtener estadísticas de empleos
     */
    public function getStats() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $stats = [
            'total_vacantes' => 12,
            'vacantes_por_departamento' => [
                'ventas' => 5,
                'marketing' => 3,
                'tecnologia' => 2,
                'administracion' => 2
            ],
            'vacantes_por_ubicacion' => [
                'santiago' => 8,
                'valparaiso' => 2,
                'concepcion' => 1,
                'remoto' => 1
            ],
            'postulaciones_recientes' => 45,
            'empleados_actuales' => 150
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($stats);
    }
    
    /**
     * Simula la búsqueda de empleos
     * @param string $query Término de búsqueda
     * @param string $departamento Departamento específico
     * @param string $ubicacion Ubicación específica
     * @return array Resultados de búsqueda
     */
    private function buscarEmpleos($query, $departamento = '', $ubicacion = '') {
        // Base de datos simulada de empleos
        $empleos = [
            [
                'id' => 1,
                'titulo' => 'Agente Inmobiliario Senior',
                'descripcion' => 'Buscamos un agente experimentado para liderar ventas en el sector residencial.',
                'departamento_slug' => 'ventas',
                'ubicacion_slug' => 'santiago',
                'tipo' => 'Tiempo Completo',
                'salario' => '$800.000 - $1.200.000'
            ],
            [
                'id' => 2,
                'titulo' => 'Desarrollador Full Stack',
                'descripcion' => 'Desarrollador para crear y mantener aplicaciones web y móviles.',
                'departamento_slug' => 'tecnologia',
                'ubicacion_slug' => 'remoto',
                'tipo' => 'Tiempo Completo',
                'salario' => '$1.500.000 - $2.500.000'
            ]
        ];
        
        $resultados = [];
        $query = strtolower($query);
        
        foreach ($empleos as $empleo) {
            // Filtrar por departamento si se especifica
            if (!empty($departamento) && $empleo['departamento_slug'] !== $departamento) {
                continue;
            }
            
            // Filtrar por ubicación si se especifica
            if (!empty($ubicacion) && $empleo['ubicacion_slug'] !== $ubicacion) {
                continue;
            }
            
            // Buscar en título y descripción
            if (empty($query) || 
                strpos(strtolower($empleo['titulo']), $query) !== false ||
                strpos(strtolower($empleo['descripcion']), $query) !== false) {
                $resultados[] = $empleo;
            }
        }
        
        return $resultados;
    }
    
    /**
     * Simula el registro de una postulación
     * @param int $empleoId ID del empleo
     * @param string $nombre Nombre del postulante
     * @param string $email Email del postulante
     * @param string $telefono Teléfono del postulante
     * @param string $cv CV del postulante
     * @param string $mensaje Mensaje del postulante
     * @return bool True si se registró correctamente
     */
    private function registrarPostulacion($empleoId, $nombre, $email, $telefono, $cv, $mensaje) {
        // En producción, insertar en la base de datos y enviar email
        // Por ahora, simulamos éxito
        return true;
    }
    
    /**
     * Simula la obtención de detalles de un empleo
     * @param int $empleoId ID del empleo
     * @return array|null Detalles del empleo
     */
    private function getDetallesEmpleo($empleoId) {
        // Base de datos simulada de detalles
        $detalles = [
            1 => [
                'id' => 1,
                'titulo' => 'Agente Inmobiliario Senior',
                'descripcion' => 'Buscamos un agente experimentado para liderar ventas en el sector residencial.',
                'departamento' => 'Ventas',
                'ubicacion' => 'Santiago',
                'tipo' => 'Tiempo Completo',
                'tipo_contrato' => 'Indefinido',
                'salario' => '$800.000 - $1.200.000',
                'requisitos' => [
                    'Mínimo 3 años de experiencia en ventas inmobiliarias',
                    'Licencia de corredor de propiedades vigente',
                    'Excelente capacidad de comunicación y negociación',
                    'Manejo de CRM y herramientas digitales'
                ],
                'responsabilidades' => [
                    'Gestionar cartera de clientes',
                    'Realizar visitas a propiedades',
                    'Negociar y cerrar ventas',
                    'Mantener relaciones con clientes'
                ],
                'beneficios' => [
                    'Comisión atractiva por venta',
                    'Seguro de salud privado',
                    'Capacitación continua',
                    'Horario flexible'
                ]
            ]
        ];
        
        return $detalles[$empleoId] ?? null;
    }
} 