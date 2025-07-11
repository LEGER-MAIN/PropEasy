<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para Contacto
 * Maneja la lógica de contacto y comunicación con usuarios
 */
class ContactController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestra la página de contacto
     */
    public function index()
    {
        $data = [
            'titulo' => 'Contacto - PropEasy',
            'contactInfo' => $this->getContactInfo(),
            'faqs' => $this->getFAQs()
        ];

        $this->render('contact/index', $data);
    }

    /**
     * Obtiene información de contacto
     */
    private function getContactInfo()
    {
        return [
            'address' => [
                'street' => 'Av. Apoquindo 1234',
                'commune' => 'Las Condes',
                'city' => 'Santiago',
                'country' => 'Chile'
            ],
            'phones' => [
                'office' => '+56 2 2345 6789',
                'whatsapp' => '+56 9 1234 5678',
                'emergency' => '+56 9 8765 4321'
            ],
            'emails' => [
                'general' => 'info@propeasy.cl',
                'sales' => 'ventas@propeasy.cl',
                'support' => 'soporte@propeasy.cl'
            ],
            'hours' => [
                'weekdays' => 'Lun-Vie 9:00-18:00',
                'saturday' => 'Sáb 9:00-14:00',
                'sunday' => 'Dom Cerrado'
            ],
            'social' => [
                'facebook' => 'https://facebook.com/propeasy',
                'twitter' => 'https://twitter.com/propeasy',
                'instagram' => 'https://instagram.com/propeasy',
                'linkedin' => 'https://linkedin.com/company/propeasy',
                'youtube' => 'https://youtube.com/propeasy'
            ]
        ];
    }

    /**
     * Obtiene preguntas frecuentes
     */
    private function getFAQs()
    {
        return [
            [
                'question' => '¿Cómo puedo agendar una visita a una propiedad?',
                'answer' => 'Puedes agendar una visita de varias formas: llamando a nuestra oficina, enviando un WhatsApp, completando el formulario de contacto o directamente desde la página de la propiedad. Nuestro equipo te contactará para confirmar la cita.'
            ],
            [
                'question' => '¿Qué documentos necesito para comprar una propiedad?',
                'answer' => 'Los documentos básicos incluyen: cédula de identidad, certificado de antecedentes, certificado de avalúo fiscal, certificado de dominio vigente, y documentación financiera si requieres un crédito hipotecario.'
            ],
            [
                'question' => '¿Cuánto tiempo toma el proceso de compra?',
                'answer' => 'El tiempo promedio es de 30 a 60 días, dependiendo de si requieres financiamiento y la complejidad de la transacción. Nuestro equipo te guiará en cada paso del proceso.'
            ],
            [
                'question' => '¿Ofrecen servicios de tasación?',
                'answer' => 'Sí, contamos con tasadores certificados que pueden realizar avalúos comerciales y técnicos para propiedades residenciales y comerciales. Contáctanos para más información.'
            ],
            [
                'question' => '¿Trabajan con todas las comunas de Santiago?',
                'answer' => 'Sí, tenemos cobertura en toda la Región Metropolitana y también en otras regiones del país. Nuestro equipo de agentes está especializado en diferentes zonas y tipos de propiedades.'
            ],
            [
                'question' => '¿Cuáles son las comisiones por venta?',
                'answer' => 'Las comisiones varían según el tipo de propiedad y el valor de la transacción. Típicamente oscilan entre el 2% y 3% del valor de venta. Contáctanos para una cotización personalizada.'
            ],
            [
                'question' => '¿Ofrecen financiamiento hipotecario?',
                'answer' => 'Sí, trabajamos con los principales bancos e instituciones financieras del país. Nuestros asesores te ayudarán a encontrar la mejor opción de financiamiento para tu situación.'
            ],
            [
                'question' => '¿Cómo puedo vender mi propiedad con ustedes?',
                'answer' => 'Para vender tu propiedad, puedes contactarnos por teléfono, email o visitar nuestra oficina. Realizaremos una tasación gratuita y te explicaremos todo el proceso de venta.'
            ]
        ];
    }

    /**
     * API para enviar formulario de contacto
     */
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        // Obtener datos del formulario
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        $newsletter = isset($_POST['newsletter']);
        $privacy = isset($_POST['privacy']);

        // Validaciones
        if (!$firstName || !$lastName || !$email || !$subject || !$message) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
            return;
        }

        if (!$privacy) {
            echo json_encode(['success' => false, 'message' => 'Debes aceptar la política de privacidad']);
            return;
        }

        // Simulación de envío de mensaje
        // En producción, aquí se guardaría en la base de datos y se enviaría email
        
        $contactData = [
            'name' => $firstName . ' ' . $lastName,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'newsletter' => $newsletter,
            'date' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];

        // Simular guardado en base de datos
        $this->saveContactMessage($contactData);

        // Simular envío de email
        $this->sendContactEmail($contactData);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Mensaje enviado exitosamente. Te contactaremos pronto.'
        ]);
    }

    /**
     * Guarda el mensaje de contacto (simulación)
     */
    private function saveContactMessage($data)
    {
        // En producción, aquí se guardaría en la base de datos
        // Por ahora, solo simulamos el guardado
        error_log("Contact message saved: " . json_encode($data));
    }

    /**
     * Envía email de contacto (simulación)
     */
    private function sendContactEmail($data)
    {
        // En producción, aquí se enviaría el email real
        // Por ahora, solo simulamos el envío
        
        $to = 'info@propeasy.cl';
        $subject = 'Nuevo mensaje de contacto: ' . $data['subject'];
        $message = "
        Nuevo mensaje de contacto recibido:
        
        Nombre: {$data['name']}
        Email: {$data['email']}
        Teléfono: {$data['phone']}
        Asunto: {$data['subject']}
        Mensaje: {$data['message']}
        Newsletter: " . ($data['newsletter'] ? 'Sí' : 'No') . "
        Fecha: {$data['date']}
        IP: {$data['ip']}
        ";

        // Simular envío
        error_log("Contact email sent to: $to");
    }

    /**
     * API para obtener información de contacto
     */
    public function info()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getContactInfo()
        ]);
    }

    /**
     * API para obtener FAQs
     */
    public function faqs()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getFAQs()
        ]);
    }

    /**
     * API para suscribirse al newsletter
     */
    public function newsletter()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
            return;
        }

        // Simulación de suscripción
        // En producción, aquí se guardaría en la base de datos
        
        $subscriptionData = [
            'email' => $email,
            'name' => $name,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];

        // Simular guardado
        error_log("Newsletter subscription: " . json_encode($subscriptionData));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Te has suscrito exitosamente a nuestro newsletter.'
        ]);
    }

    /**
     * API para reportar un problema
     */
    public function report()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        $email = $_POST['email'] ?? '';
        $url = $_POST['url'] ?? '';

        if (!$type || !$description) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        // Simulación de reporte
        $reportData = [
            'type' => $type,
            'description' => $description,
            'email' => $email,
            'url' => $url,
            'date' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];

        // Simular guardado
        error_log("Bug report: " . json_encode($reportData));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Reporte enviado exitosamente. Gracias por tu feedback.'
        ]);
    }

    /**
     * API para solicitar callback
     */
    public function callback()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $preferredTime = $_POST['preferred_time'] ?? '';
        $subject = $_POST['subject'] ?? '';

        if (!$name || !$phone) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        // Simulación de solicitud de callback
        $callbackData = [
            'name' => $name,
            'phone' => $phone,
            'preferred_time' => $preferredTime,
            'subject' => $subject,
            'date' => date('Y-m-d H:i:s')
        ];

        // Simular guardado
        error_log("Callback request: " . json_encode($callbackData));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud recibida. Te llamaremos en el horario especificado.'
        ]);
    }
} 