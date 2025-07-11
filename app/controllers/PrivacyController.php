<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para Política de Privacidad
 * Maneja la información legal y términos de privacidad
 */
class PrivacyController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestra la página de política de privacidad
     */
    public function index()
    {
        $data = [
            'titulo' => 'Política de Privacidad - PropEasy',
            'privacyInfo' => $this->getPrivacyInfo(),
            'legalInfo' => $this->getLegalInfo(),
            'contactInfo' => $this->getContactInfo()
        ];

        $this->render('privacy/index', $data);
    }

    /**
     * Obtiene información de privacidad
     */
    private function getPrivacyInfo()
    {
        return [
            'lastUpdated' => '15 de Enero, 2024',
            'version' => '2.1',
            'effectiveDate' => '2024-01-15',
            'company' => [
                'name' => 'PropEasy SPA',
                'address' => 'Av. Apoquindo 1234, Las Condes, Santiago, Chile',
                'email' => 'privacy@propeasy.cl',
                'phone' => '+56 2 2345 6789',
                'website' => 'https://propeasy.cl'
            ],
            'dataController' => [
                'name' => 'PropEasy SPA',
                'contact' => 'privacy@propeasy.cl',
                'address' => 'Av. Apoquindo 1234, Las Condes, Santiago, Chile'
            ],
            'dataProtectionOfficer' => [
                'name' => 'María González',
                'email' => 'dpo@propeasy.cl',
                'phone' => '+56 2 2345 6789'
            ]
        ];
    }

    /**
     * Obtiene información legal
     */
    private function getLegalInfo()
    {
        return [
            'regulations' => [
                'chile' => 'Ley 19.628 sobre Protección de la Vida Privada',
                'gdpr' => 'Reglamento General de Protección de Datos (GDPR)',
                'ccpa' => 'California Consumer Privacy Act (CCPA)'
            ],
            'compliance' => [
                'iso27001' => 'Certificación ISO 27001 - Gestión de Seguridad de la Información',
                'soc2' => 'Certificación SOC 2 - Controles de Seguridad',
                'gdpr_compliance' => 'Cumplimiento GDPR para usuarios europeos'
            ],
            'rights' => [
                'access' => 'Derecho de acceso a datos personales',
                'rectification' => 'Derecho de rectificación de datos inexactos',
                'erasure' => 'Derecho de eliminación de datos',
                'portability' => 'Derecho de portabilidad de datos',
                'objection' => 'Derecho de oposición al procesamiento',
                'restriction' => 'Derecho de limitación del procesamiento'
            ],
            'dataRetention' => [
                'account_data' => '7 años desde la última actividad',
                'transaction_data' => '10 años por requerimientos legales',
                'marketing_data' => '3 años desde el último consentimiento',
                'analytics_data' => '2 años desde la recolección'
            ]
        ];
    }

    /**
     * Obtiene información de contacto
     */
    private function getContactInfo()
    {
        return [
            'privacy_email' => 'privacy@propeasy.cl',
            'dpo_email' => 'dpo@propeasy.cl',
            'legal_email' => 'legal@propeasy.cl',
            'phone' => '+56 2 2345 6789',
            'address' => [
                'street' => 'Av. Apoquindo 1234',
                'commune' => 'Las Condes',
                'city' => 'Santiago',
                'country' => 'Chile',
                'postal_code' => '8320000'
            ],
            'hours' => [
                'weekdays' => 'Lun-Vie 9:00-18:00',
                'saturday' => 'Sáb 9:00-14:00',
                'sunday' => 'Dom Cerrado'
            ],
            'response_time' => '30 días hábiles'
        ];
    }

    /**
     * API para obtener información de privacidad
     */
    public function info()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getPrivacyInfo()
        ]);
    }

    /**
     * API para obtener información legal
     */
    public function legal()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getLegalInfo()
        ]);
    }

    /**
     * API para obtener información de contacto
     */
    public function contact()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getContactInfo()
        ]);
    }

    /**
     * API para solicitar acceso a datos personales
     */
    public function dataAccess()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $identification = $_POST['identification'] ?? '';
        $requestType = $_POST['request_type'] ?? 'access';

        if (!$name || !$email || !$identification) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
            return;
        }

        // Simulación de solicitud de acceso a datos
        $requestData = [
            'name' => $name,
            'email' => $email,
            'identification' => $identification,
            'request_type' => $requestType,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'reference' => 'REQ-' . date('Ymd') . '-' . rand(1000, 9999)
        ];

        // Simular guardado
        error_log("Data access request: " . json_encode($requestData));

        // Simular envío de email de confirmación
        $this->sendDataAccessConfirmation($requestData);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud enviada exitosamente. Te contactaremos dentro de los 30 días hábiles.',
            'reference' => $requestData['reference']
        ]);
    }

    /**
     * API para solicitar eliminación de datos
     */
    public function dataDeletion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $identification = $_POST['identification'] ?? '';
        $reason = $_POST['reason'] ?? '';

        if (!$name || !$email || !$identification) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
            return;
        }

        // Simulación de solicitud de eliminación
        $requestData = [
            'name' => $name,
            'email' => $email,
            'identification' => $identification,
            'reason' => $reason,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'reference' => 'DEL-' . date('Ymd') . '-' . rand(1000, 9999)
        ];

        // Simular guardado
        error_log("Data deletion request: " . json_encode($requestData));

        // Simular envío de email de confirmación
        $this->sendDataDeletionConfirmation($requestData);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud de eliminación enviada. Te contactaremos para confirmar la acción.',
            'reference' => $requestData['reference']
        ]);
    }

    /**
     * API para solicitar rectificación de datos
     */
    public function dataRectification()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $identification = $_POST['identification'] ?? '';
        $currentData = $_POST['current_data'] ?? '';
        $correctedData = $_POST['corrected_data'] ?? '';

        if (!$name || !$email || !$identification || !$currentData || !$correctedData) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
            return;
        }

        // Simulación de solicitud de rectificación
        $requestData = [
            'name' => $name,
            'email' => $email,
            'identification' => $identification,
            'current_data' => $currentData,
            'corrected_data' => $correctedData,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'reference' => 'REC-' . date('Ymd') . '-' . rand(1000, 9999)
        ];

        // Simular guardado
        error_log("Data rectification request: " . json_encode($requestData));

        // Simular envío de email de confirmación
        $this->sendDataRectificationConfirmation($requestData);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud de rectificación enviada. Te contactaremos para verificar los cambios.',
            'reference' => $requestData['reference']
        ]);
    }

    /**
     * API para descargar política en PDF
     */
    public function download()
    {
        // En producción, aquí se generaría un PDF real
        // Por ahora, simulamos la descarga
        
        $filename = 'politica-privacidad-propeasy-' . date('Y-m-d') . '.pdf';
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Simular contenido PDF
        echo "%PDF-1.4\n";
        echo "1 0 obj\n";
        echo "<<\n";
        echo "/Type /Catalog\n";
        echo "/Pages 2 0 R\n";
        echo ">>\n";
        echo "endobj\n";
        echo "2 0 obj\n";
        echo "<<\n";
        echo "/Type /Pages\n";
        echo "/Kids [3 0 R]\n";
        echo "/Count 1\n";
        echo ">>\n";
        echo "endobj\n";
        echo "3 0 obj\n";
        echo "<<\n";
        echo "/Type /Page\n";
        echo "/Parent 2 0 R\n";
        echo "/MediaBox [0 0 612 792]\n";
        echo "/Contents 4 0 R\n";
        echo ">>\n";
        echo "endobj\n";
        echo "4 0 obj\n";
        echo "<<\n";
        echo "/Length 44\n";
        echo ">>\n";
        echo "stream\n";
        echo "BT\n";
        echo "/F1 12 Tf\n";
        echo "72 720 Td\n";
        echo "(Politica de Privacidad - PropEasy) Tj\n";
        echo "ET\n";
        echo "endstream\n";
        echo "endobj\n";
        echo "xref\n";
        echo "0 5\n";
        echo "0000000000 65535 f \n";
        echo "0000000009 00000 n \n";
        echo "0000000058 00000 n \n";
        echo "0000000115 00000 n \n";
        echo "0000000204 00000 n \n";
        echo "trailer\n";
        echo "<<\n";
        echo "/Size 5\n";
        echo "/Root 1 0 R\n";
        echo ">>\n";
        echo "startxref\n";
        echo "297\n";
        echo "%%EOF\n";
    }

    /**
     * API para obtener historial de cambios
     */
    public function changelog()
    {
        $changelog = [
            [
                'date' => '2024-01-15',
                'version' => '2.1',
                'changes' => [
                    'Actualización de políticas de cookies',
                    'Nuevas funcionalidades de control de datos',
                    'Mejoras en la transparencia de procesamiento'
                ]
            ],
            [
                'date' => '2023-12-01',
                'version' => '2.0',
                'changes' => [
                    'Mejoras en la seguridad de datos',
                    'Nuevos derechos de usuario',
                    'Cumplimiento con nuevas regulaciones'
                ]
            ],
            [
                'date' => '2023-11-01',
                'version' => '1.9',
                'changes' => [
                    'Cumplimiento GDPR',
                    'Actualización de términos legales',
                    'Nuevas opciones de consentimiento'
                ]
            ]
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $changelog
        ]);
    }

    /**
     * Envía confirmación de solicitud de acceso a datos
     */
    private function sendDataAccessConfirmation($data)
    {
        // En producción, aquí se enviaría el email real
        $to = $data['email'];
        $subject = 'Confirmación de Solicitud de Acceso a Datos - ' . $data['reference'];
        $message = "
        Estimado/a {$data['name']},

        Hemos recibido tu solicitud de acceso a datos personales con la referencia: {$data['reference']}

        Tipo de solicitud: {$data['request_type']}
        Fecha de solicitud: {$data['date']}

        Nos pondremos en contacto contigo dentro de los 30 días hábiles para procesar tu solicitud.

        Si tienes alguna pregunta, contáctanos a privacy@propeasy.cl

        Saludos cordiales,
        Equipo de Privacidad - PropEasy
        ";

        // Simular envío
        error_log("Data access confirmation email sent to: $to");
    }

    /**
     * Envía confirmación de solicitud de eliminación
     */
    private function sendDataDeletionConfirmation($data)
    {
        // En producción, aquí se enviaría el email real
        $to = $data['email'];
        $subject = 'Confirmación de Solicitud de Eliminación - ' . $data['reference'];
        $message = "
        Estimado/a {$data['name']},

        Hemos recibido tu solicitud de eliminación de datos personales con la referencia: {$data['reference']}

        Fecha de solicitud: {$data['date']}
        Motivo: {$data['reason']}

        Te contactaremos para confirmar esta acción y explicarte las implicaciones.

        Si tienes alguna pregunta, contáctanos a privacy@propeasy.cl

        Saludos cordiales,
        Equipo de Privacidad - PropEasy
        ";

        // Simular envío
        error_log("Data deletion confirmation email sent to: $to");
    }

    /**
     * Envía confirmación de solicitud de rectificación
     */
    private function sendDataRectificationConfirmation($data)
    {
        // En producción, aquí se enviaría el email real
        $to = $data['email'];
        $subject = 'Confirmación de Solicitud de Rectificación - ' . $data['reference'];
        $message = "
        Estimado/a {$data['name']},

        Hemos recibido tu solicitud de rectificación de datos personales con la referencia: {$data['reference']}

        Fecha de solicitud: {$data['date']}
        Datos actuales: {$data['current_data']}
        Datos corregidos: {$data['corrected_data']}

        Te contactaremos para verificar los cambios solicitados.

        Si tienes alguna pregunta, contáctanos a privacy@propeasy.cl

        Saludos cordiales,
        Equipo de Privacidad - PropEasy
        ";

        // Simular envío
        error_log("Data rectification confirmation email sent to: $to");
    }
} 