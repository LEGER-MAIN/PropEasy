<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para la página de Preguntas Frecuentes (FAQ)
 * Maneja la visualización de preguntas, búsqueda y estadísticas
 */
class FaqController extends Controller {
    
    /**
     * Constructor del controlador
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Muestra la página principal de FAQ
     */
    public function index() {
        // Datos para la vista
        $data = [
            'titulo' => 'Preguntas Frecuentes - PropEasy',
            'descripcion' => 'Encuentra respuestas rápidas a las preguntas más comunes sobre nuestros servicios inmobiliarios.',
            'palabras_clave' => 'preguntas frecuentes, FAQ, ayuda, soporte, inmobiliaria, PropEasy',
            'stats' => [
                'total' => 45,
                'resueltas' => 98,
                'categorias' => 4,
                'tiempo_respuesta' => 15,
                'propiedades' => 12,
                'financiamiento' => 15,
                'servicios' => 10,
                'cuenta' => 8
            ],
            'preguntas_populares' => [
                [
                    'id' => 'prop1',
                    'pregunta' => '¿Cómo puedo buscar propiedades en PropEasy?',
                    'categoria' => 'Propiedades',
                    'vistas' => 1200
                ],
                [
                    'id' => 'fin1',
                    'pregunta' => '¿Cómo funciona la calculadora de crédito hipotecario?',
                    'categoria' => 'Financiamiento',
                    'vistas' => 856
                ],
                [
                    'id' => 'ser1',
                    'pregunta' => '¿Qué servicios de tasación ofrecen?',
                    'categoria' => 'Servicios',
                    'vistas' => 543
                ],
                [
                    'id' => 'cue1',
                    'pregunta' => '¿Cómo puedo crear una cuenta en PropEasy?',
                    'categoria' => 'Mi Cuenta',
                    'vistas' => 432
                ]
            ]
        ];
        
        // Cargar la vista
        $this->render('faq/index', $data);
    }
    
    /**
     * API para buscar preguntas frecuentes
     */
    public function search() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener término de búsqueda
        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'] ?? '';
        $categoria = $input['categoria'] ?? '';
        
        if (empty($query)) {
            http_response_code(400);
            echo json_encode(['error' => 'Término de búsqueda requerido']);
            return;
        }
        
        // Simular búsqueda en base de datos
        $resultados = $this->buscarPreguntas($query, $categoria);
        
        $response = [
            'query' => $query,
            'categoria' => $categoria,
            'total_resultados' => count($resultados),
            'resultados' => $resultados
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para obtener estadísticas de FAQ
     */
    public function getStats() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $stats = [
            'total_preguntas' => 45,
            'categorias' => 4,
            'preguntas_por_categoria' => [
                'propiedades' => 12,
                'financiamiento' => 15,
                'servicios' => 10,
                'cuenta' => 8
            ],
            'preguntas_populares' => [
                [
                    'id' => 'prop1',
                    'pregunta' => '¿Cómo puedo buscar propiedades?',
                    'vistas' => 1200,
                    'categoria' => 'propiedades'
                ],
                [
                    'id' => 'fin1',
                    'pregunta' => 'Calculadora de crédito hipotecario',
                    'vistas' => 856,
                    'categoria' => 'financiamiento'
                ],
                [
                    'id' => 'ser1',
                    'pregunta' => 'Servicios de tasación',
                    'vistas' => 543,
                    'categoria' => 'servicios'
                ],
                [
                    'id' => 'cue1',
                    'pregunta' => 'Crear cuenta',
                    'vistas' => 432,
                    'categoria' => 'cuenta'
                ]
            ],
            'búsquedas_recientes' => [
                'crédito hipotecario',
                'tasación de propiedades',
                'agendar visita',
                'crear cuenta',
                'recuperar contraseña'
            ]
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($stats);
    }
    
    /**
     * API para obtener preguntas por categoría
     */
    public function getByCategory() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $categoria = $_GET['categoria'] ?? '';
        
        if (empty($categoria)) {
            http_response_code(400);
            echo json_encode(['error' => 'Categoría requerida']);
            return;
        }
        
        // Simular obtención de preguntas por categoría
        $preguntas = $this->getPreguntasPorCategoria($categoria);
        
        $response = [
            'categoria' => $categoria,
            'total_preguntas' => count($preguntas),
            'preguntas' => $preguntas
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para registrar una vista de pregunta
     */
    public function registerView() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $preguntaId = $input['pregunta_id'] ?? '';
        $userId = $input['user_id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        
        if (empty($preguntaId)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de pregunta requerido']);
            return;
        }
        
        // Simular registro de vista
        $success = $this->registrarVista($preguntaId, $userId, $ipAddress);
        
        if ($success) {
            $response = [
                'success' => true,
                'message' => 'Vista registrada correctamente',
                'pregunta_id' => $preguntaId
            ];
        } else {
            http_response_code(500);
            $response = [
                'success' => false,
                'error' => 'Error al registrar la vista'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para sugerir una nueva pregunta
     */
    public function suggestQuestion() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $pregunta = $input['pregunta'] ?? '';
        $categoria = $input['categoria'] ?? '';
        $email = $input['email'] ?? '';
        $nombre = $input['nombre'] ?? '';
        
        if (empty($pregunta)) {
            http_response_code(400);
            echo json_encode(['error' => 'Pregunta requerida']);
            return;
        }
        
        // Simular registro de sugerencia
        $success = $this->registrarSugerencia($pregunta, $categoria, $email, $nombre);
        
        if ($success) {
            $response = [
                'success' => true,
                'message' => 'Sugerencia enviada correctamente. Te notificaremos cuando agreguemos la respuesta.',
                'pregunta' => $pregunta
            ];
        } else {
            http_response_code(500);
            $response = [
                'success' => false,
                'error' => 'Error al enviar la sugerencia'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Simula la búsqueda de preguntas
     * @param string $query Término de búsqueda
     * @param string $categoria Categoría específica
     * @return array Resultados de búsqueda
     */
    private function buscarPreguntas($query, $categoria = '') {
        // Base de datos simulada de preguntas
        $preguntas = [
            [
                'id' => 'prop1',
                'pregunta' => '¿Cómo puedo buscar propiedades en PropEasy?',
                'respuesta' => 'Puedes buscar propiedades usando la barra de búsqueda, filtros avanzados o el mapa interactivo.',
                'categoria' => 'propiedades',
                'vistas' => 1200,
                'relevancia' => 0.95
            ],
            [
                'id' => 'prop2',
                'pregunta' => '¿Qué información incluye cada ficha de propiedad?',
                'respuesta' => 'Cada ficha incluye precio, ubicación, características, fotos, video tour e información del agente.',
                'categoria' => 'propiedades',
                'vistas' => 856,
                'relevancia' => 0.88
            ],
            [
                'id' => 'fin1',
                'pregunta' => '¿Cómo funciona la calculadora de crédito hipotecario?',
                'respuesta' => 'Ingresa el precio de la propiedad, pie inicial, plazo e ingresos para obtener el monto máximo de crédito.',
                'categoria' => 'financiamiento',
                'vistas' => 856,
                'relevancia' => 0.92
            ],
            [
                'id' => 'ser1',
                'pregunta' => '¿Qué servicios de tasación ofrecen?',
                'respuesta' => 'Ofrecemos tasación comercial, residencial, industrial y de terrenos con reporte detallado.',
                'categoria' => 'servicios',
                'vistas' => 543,
                'relevancia' => 0.85
            ]
        ];
        
        $resultados = [];
        $query = strtolower($query);
        
        foreach ($preguntas as $pregunta) {
            // Filtrar por categoría si se especifica
            if (!empty($categoria) && $pregunta['categoria'] !== $categoria) {
                continue;
            }
            
            // Buscar en pregunta y respuesta
            if (strpos(strtolower($pregunta['pregunta']), $query) !== false ||
                strpos(strtolower($pregunta['respuesta']), $query) !== false) {
                $resultados[] = $pregunta;
            }
        }
        
        // Ordenar por relevancia
        usort($resultados, function($a, $b) {
            return $b['relevancia'] <=> $a['relevancia'];
        });
        
        return $resultados;
    }
    
    /**
     * Simula la obtención de preguntas por categoría
     * @param string $categoria Categoría de preguntas
     * @return array Preguntas de la categoría
     */
    private function getPreguntasPorCategoria($categoria) {
        $preguntas = [
            'propiedades' => [
                [
                    'id' => 'prop1',
                    'pregunta' => '¿Cómo puedo buscar propiedades en PropEasy?',
                    'respuesta' => 'Puedes buscar propiedades usando la barra de búsqueda, filtros avanzados o el mapa interactivo.',
                    'vistas' => 1200
                ],
                [
                    'id' => 'prop2',
                    'pregunta' => '¿Qué información incluye cada ficha de propiedad?',
                    'respuesta' => 'Cada ficha incluye precio, ubicación, características, fotos, video tour e información del agente.',
                    'vistas' => 856
                ],
                [
                    'id' => 'prop3',
                    'pregunta' => '¿Cómo puedo agendar una visita a una propiedad?',
                    'respuesta' => 'Ve a la ficha de la propiedad, haz clic en "Agendar Visita", selecciona fecha y hora, y completa tus datos.',
                    'vistas' => 654
                ]
            ],
            'financiamiento' => [
                [
                    'id' => 'fin1',
                    'pregunta' => '¿Cómo funciona la calculadora de crédito hipotecario?',
                    'respuesta' => 'Ingresa el precio de la propiedad, pie inicial, plazo e ingresos para obtener el monto máximo de crédito.',
                    'vistas' => 856
                ],
                [
                    'id' => 'fin2',
                    'pregunta' => '¿Qué documentos necesito para solicitar un crédito hipotecario?',
                    'respuesta' => 'Necesitas cédula, certificado de trabajo, liquidaciones de sueldo y certificado de cotizaciones previsionales.',
                    'vistas' => 743
                ],
                [
                    'id' => 'fin3',
                    'pregunta' => '¿Cuál es el pie mínimo requerido para comprar una propiedad?',
                    'respuesta' => 'Varía entre 10% para vivienda nueva con subsidio hasta 40% para propiedad comercial.',
                    'vistas' => 632
                ]
            ],
            'servicios' => [
                [
                    'id' => 'ser1',
                    'pregunta' => '¿Qué servicios de tasación ofrecen?',
                    'respuesta' => 'Ofrecemos tasación comercial, residencial, industrial y de terrenos con reporte detallado.',
                    'vistas' => 543
                ],
                [
                    'id' => 'ser2',
                    'pregunta' => '¿Cómo funciona el servicio de asesoría legal?',
                    'respuesta' => 'Incluye evaluación inicial gratuita, revisión de documentos, asesoría en trámites y acompañamiento.',
                    'vistas' => 432
                ]
            ],
            'cuenta' => [
                [
                    'id' => 'cue1',
                    'pregunta' => '¿Cómo puedo crear una cuenta en PropEasy?',
                    'respuesta' => 'Haz clic en "Iniciar Sesión", selecciona "Crear Cuenta", completa el formulario y verifica tu email.',
                    'vistas' => 432
                ],
                [
                    'id' => 'cue2',
                    'pregunta' => '¿Cómo puedo recuperar mi contraseña?',
                    'respuesta' => 'Ve a la página de inicio de sesión, haz clic en "¿Olvidaste tu contraseña?" e ingresa tu email.',
                    'vistas' => 321
                ],
                [
                    'id' => 'cue3',
                    'pregunta' => '¿Cómo puedo actualizar mi información personal?',
                    'respuesta' => 'Inicia sesión, ve a "Mi Perfil", haz clic en "Editar Información" y actualiza los campos necesarios.',
                    'vistas' => 298
                ]
            ]
        ];
        
        return $preguntas[$categoria] ?? [];
    }
    
    /**
     * Simula el registro de una vista
     * @param string $preguntaId ID de la pregunta
     * @param int|null $userId ID del usuario
     * @param string $ipAddress Dirección IP
     * @return bool True si se registró correctamente
     */
    private function registrarVista($preguntaId, $userId, $ipAddress) {
        // En producción, insertar en la base de datos
        // Por ahora, simulamos éxito
        return true;
    }
    
    /**
     * Simula el registro de una sugerencia
     * @param string $pregunta Pregunta sugerida
     * @param string $categoria Categoría sugerida
     * @param string $email Email del usuario
     * @param string $nombre Nombre del usuario
     * @return bool True si se registró correctamente
     */
    private function registrarSugerencia($pregunta, $categoria, $email, $nombre) {
        // En producción, insertar en la base de datos y enviar email
        // Por ahora, simulamos éxito
        return true;
    }
} 