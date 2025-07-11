<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para la página de Términos y Condiciones
 * Maneja la visualización de términos legales y descarga de documentos
 */
class TermsController extends Controller {
    
    /**
     * Constructor del controlador
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Muestra la página principal de términos y condiciones
     */
    public function index() {
        // Datos para la vista
        $data = [
            'titulo' => 'Términos y Condiciones - PropEasy',
            'descripcion' => 'Conoce las condiciones de uso de nuestros servicios y las reglas que rigen nuestra plataforma inmobiliaria.',
            'palabras_clave' => 'términos, condiciones, uso, legal, inmobiliaria, PropEasy',
            'version_actual' => '2.1',
            'fecha_actualizacion' => '15 de Enero, 2024',
            'secciones' => [
                'aceptacion' => 'Aceptación de Términos',
                'servicios' => 'Descripción de Servicios',
                'registro' => 'Registro de Usuario',
                'uso' => 'Uso de la Plataforma',
                'propiedad' => 'Propiedad Intelectual',
                'responsabilidad' => 'Limitaciones de Responsabilidad',
                'terminacion' => 'Terminación',
                'contacto' => 'Contacto'
            ],
            'contacto_legal' => [
                'email' => 'legal@propeasy.cl',
                'telefono' => '+56 2 2345 6789',
                'direccion' => 'Av. Apoquindo 1234, Las Condes, Santiago, Chile',
                'horario' => 'Lun-Vie: 9:00-18:00, Sáb: 9:00-14:00'
            ],
            'historial_versiones' => [
                [
                    'version' => '2.1',
                    'fecha' => '15 Enero 2024',
                    'descripcion' => 'Actualización de políticas de uso y nuevas funcionalidades'
                ],
                [
                    'version' => '2.0',
                    'fecha' => '1 Diciembre 2023',
                    'descripcion' => 'Revisión completa de términos y nuevas secciones legales'
                ],
                [
                    'version' => '1.9',
                    'fecha' => '1 Noviembre 2023',
                    'descripcion' => 'Cumplimiento con nuevas regulaciones y mejoras en claridad'
                ]
            ]
        ];
        
        // Cargar la vista
        $this->render('terms/index', $data);
    }
    
    /**
     * Genera y descarga el PDF de términos y condiciones
     */
    public function downloadPDF() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        try {
            // Simular generación de PDF (en producción usar librería como TCPDF o FPDF)
            $pdfContent = $this->generatePDFContent();
            
            // Configurar headers para descarga
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="terminos-condiciones-propeasy.pdf"');
            header('Content-Length: ' . strlen($pdfContent));
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            
            // Enviar contenido del PDF
            echo $pdfContent;
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al generar el PDF: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Genera el contenido del PDF de términos y condiciones
     * @return string Contenido del PDF
     */
    private function generatePDFContent() {
        // En producción, usar una librería real de PDF
        // Por ahora, simulamos el contenido
        $content = "
        TÉRMINOS Y CONDICIONES - PROPEASY
        
        Fecha de actualización: 15 de Enero, 2024
        Versión: 2.1
        
        1. ACEPTACIÓN DE TÉRMINOS
        Al acceder y utilizar la plataforma PropEasy, aceptas estar sujeto a estos términos y condiciones de uso.
        
        2. DESCRIPCIÓN DE SERVICIOS
        PropEasy es una plataforma inmobiliaria que conecta compradores, vendedores, arrendadores y arrendatarios.
        
        3. REGISTRO DE USUARIO
        Para utilizar ciertos servicios, es necesario crear una cuenta en nuestra plataforma.
        
        4. USO DE LA PLATAFORMA
        Establecemos reglas claras para el uso adecuado de nuestra plataforma.
        
        5. PROPIEDAD INTELECTUAL
        Todos los derechos de propiedad intelectual de la plataforma pertenecen a PropEasy.
        
        6. LIMITACIONES DE RESPONSABILIDAD
        Establecemos límites claros sobre nuestra responsabilidad en el uso de la plataforma.
        
        7. TERMINACIÓN
        Establecemos las condiciones bajo las cuales se puede terminar el uso de nuestros servicios.
        
        8. CONTACTO
        Para consultas sobre estos términos y condiciones, contáctanos a través de los siguientes canales:
        
        Email Legal: legal@propeasy.cl
        Teléfono: +56 2 2345 6789
        Dirección: Av. Apoquindo 1234, Las Condes, Santiago, Chile
        
        Este documento es una simulación. En producción, se generaría un PDF real con formato apropiado.
        ";
        
        return $content;
    }
    
    /**
     * API para obtener información de términos y condiciones
     */
    public function getTermsInfo() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $data = [
            'version_actual' => '2.1',
            'fecha_actualizacion' => '15 de Enero, 2024',
            'secciones' => [
                'aceptacion' => 'Aceptación de Términos',
                'servicios' => 'Descripción de Servicios',
                'registro' => 'Registro de Usuario',
                'uso' => 'Uso de la Plataforma',
                'propiedad' => 'Propiedad Intelectual',
                'responsabilidad' => 'Limitaciones de Responsabilidad',
                'terminacion' => 'Terminación',
                'contacto' => 'Contacto'
            ],
            'contacto_legal' => [
                'email' => 'legal@propeasy.cl',
                'telefono' => '+56 2 2345 6789',
                'direccion' => 'Av. Apoquindo 1234, Las Condes, Santiago, Chile',
                'horario' => 'Lun-Vie: 9:00-18:00, Sáb: 9:00-14:00'
            ],
            'historial_versiones' => [
                [
                    'version' => '2.1',
                    'fecha' => '15 Enero 2024',
                    'descripcion' => 'Actualización de políticas de uso y nuevas funcionalidades'
                ],
                [
                    'version' => '2.0',
                    'fecha' => '1 Diciembre 2023',
                    'descripcion' => 'Revisión completa de términos y nuevas secciones legales'
                ],
                [
                    'version' => '1.9',
                    'fecha' => '1 Noviembre 2023',
                    'descripcion' => 'Cumplimiento con nuevas regulaciones y mejoras en claridad'
                ]
            ]
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: Content-Type');
        
        echo json_encode($data);
    }
    
    /**
     * API para verificar si el usuario ha aceptado los términos
     */
    public function checkAcceptance() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input['user_id'] ?? null;
        
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de usuario requerido']);
            return;
        }
        
        // Simular verificación en base de datos
        $accepted = $this->checkUserAcceptance($userId);
        
        $response = [
            'user_id' => $userId,
            'accepted' => $accepted,
            'version_accepted' => $accepted ? '2.1' : null,
            'date_accepted' => $accepted ? '2024-01-15' : null
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para registrar la aceptación de términos por parte del usuario
     */
    public function acceptTerms() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $input['user_id'] ?? null;
        $version = $input['version'] ?? '2.1';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de usuario requerido']);
            return;
        }
        
        // Simular registro en base de datos
        $success = $this->registerUserAcceptance($userId, $version, $ipAddress, $userAgent);
        
        if ($success) {
            $response = [
                'success' => true,
                'message' => 'Términos aceptados correctamente',
                'user_id' => $userId,
                'version' => $version,
                'date_accepted' => date('Y-m-d H:i:s')
            ];
        } else {
            http_response_code(500);
            $response = [
                'success' => false,
                'error' => 'Error al registrar la aceptación'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Simula la verificación de aceptación de términos en base de datos
     * @param int $userId ID del usuario
     * @return bool True si el usuario ha aceptado los términos
     */
    private function checkUserAcceptance($userId) {
        // En producción, consultar la base de datos
        // Por ahora, simulamos que algunos usuarios han aceptado
        $acceptedUsers = [1, 3, 5, 7, 9]; // IDs de usuarios que han aceptado
        return in_array($userId, $acceptedUsers);
    }
    
    /**
     * Simula el registro de aceptación de términos en base de datos
     * @param int $userId ID del usuario
     * @param string $version Versión de términos aceptada
     * @param string $ipAddress Dirección IP del usuario
     * @param string $userAgent User agent del navegador
     * @return bool True si se registró correctamente
     */
    private function registerUserAcceptance($userId, $version, $ipAddress, $userAgent) {
        // En producción, insertar en la base de datos
        // Por ahora, simulamos éxito
        return true;
    }
    
    /**
     * Obtiene estadísticas de aceptación de términos
     */
    public function getAcceptanceStats() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Simular estadísticas
        $stats = [
            'total_users' => 1250,
            'accepted_users' => 1180,
            'pending_users' => 70,
            'acceptance_rate' => 94.4,
            'version_distribution' => [
                '2.1' => 850,
                '2.0' => 280,
                '1.9' => 50
            ],
            'monthly_acceptances' => [
                'Enero 2024' => 45,
                'Diciembre 2023' => 38,
                'Noviembre 2023' => 42
            ]
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($stats);
    }
} 