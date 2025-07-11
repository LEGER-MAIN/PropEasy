<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para la página "Nosotros"
 * Maneja la información corporativa y del equipo
 */
class AboutController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestra la página "Nosotros"
     */
    public function index()
    {
        $data = [
            'titulo' => 'Nosotros - PropEasy',
            'companyInfo' => $this->getCompanyInfo(),
            'team' => $this->getTeam(),
            'stats' => $this->getStats(),
            'testimonials' => $this->getTestimonials(),
            'awards' => $this->getAwards(),
            'values' => $this->getValues()
        ];

        $this->render('about/index', $data);
    }

    /**
     * Obtiene información de la empresa
     */
    private function getCompanyInfo()
    {
        return [
            'name' => 'PropEasy',
            'founded' => '2014',
            'years' => date('Y') - 2014,
            'mission' => 'Facilitar el acceso a la vivienda de calidad para todas las familias chilenas, ofreciendo un servicio personalizado, transparente y profesional que genere confianza y satisfacción en cada transacción.',
            'vision' => 'Ser la empresa inmobiliaria líder en Chile, reconocida por la excelencia en el servicio, la innovación tecnológica y el compromiso con el desarrollo sostenible de las comunidades.',
            'history' => [
                [
                    'year' => '2014',
                    'title' => 'Fundación',
                    'description' => 'Nacimos como una pequeña oficina en Las Condes con 3 agentes inmobiliarios.',
                    'icon' => 'fas fa-rocket',
                    'color' => 'bg-primary'
                ],
                [
                    'year' => '2017',
                    'title' => 'Expansión',
                    'description' => 'Abrimos 5 nuevas oficinas en Santiago y alcanzamos 50 agentes.',
                    'icon' => 'fas fa-chart-line',
                    'color' => 'bg-success'
                ],
                [
                    'year' => '2020',
                    'title' => 'Digitalización',
                    'description' => 'Lanzamos nuestra plataforma digital y herramientas de realidad virtual.',
                    'icon' => 'fas fa-laptop',
                    'color' => 'bg-info'
                ],
                [
                    'year' => '2024',
                    'title' => 'Liderazgo',
                    'description' => 'Somos líderes en el mercado con presencia en 8 regiones y 200+ agentes.',
                    'icon' => 'fas fa-trophy',
                    'color' => 'bg-warning'
                ]
            ]
        ];
    }

    /**
     * Obtiene información del equipo directivo
     */
    private function getTeam()
    {
        return [
            [
                'name' => 'María González',
                'position' => 'CEO & Fundadora',
                'photo' => 'assets/img/team/ceo.jpg',
                'bio' => 'Más de 15 años de experiencia en el sector inmobiliario. Fundó PropEasy con la visión de democratizar el acceso a la vivienda.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/mariagonzalez',
                    'twitter' => 'https://twitter.com/mariagonzalez'
                ]
            ],
            [
                'name' => 'Carlos Rodríguez',
                'position' => 'CTO & Co-Fundador',
                'photo' => 'assets/img/team/cto.jpg',
                'bio' => 'Experto en tecnología con más de 12 años desarrollando soluciones digitales para el sector inmobiliario.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/carlosrodriguez',
                    'github' => 'https://github.com/carlosrodriguez'
                ]
            ],
            [
                'name' => 'Ana Silva',
                'position' => 'CFO & Directora Financiera',
                'photo' => 'assets/img/team/cfo.jpg',
                'bio' => 'Contadora auditora con especialización en finanzas corporativas y más de 10 años en el sector inmobiliario.',
                'social' => [
                    'linkedin' => 'https://linkedin.com/in/anasilva',
                    'twitter' => 'https://twitter.com/anasilva'
                ]
            ]
        ];
    }

    /**
     * Obtiene estadísticas de la empresa
     */
    private function getStats()
    {
        return [
            [
                'number' => 2500,
                'label' => 'Propiedades Vendidas',
                'icon' => 'fas fa-home',
                'color' => 'bg-primary'
            ],
            [
                'number' => 200,
                'label' => 'Agentes Expertos',
                'icon' => 'fas fa-users',
                'color' => 'bg-success'
            ],
            [
                'number' => 98,
                'label' => '% Satisfacción',
                'icon' => 'fas fa-star',
                'color' => 'bg-info'
            ],
            [
                'number' => 8,
                'label' => 'Regiones',
                'icon' => 'fas fa-map-marker-alt',
                'color' => 'bg-warning'
            ]
        ];
    }

    /**
     * Obtiene testimonios de clientes
     */
    private function getTestimonials()
    {
        return [
            [
                'name' => 'Familia Martínez',
                'location' => 'Las Condes, Santiago',
                'text' => 'PropEasy nos ayudó a encontrar nuestra casa ideal en solo 2 semanas. Su equipo fue muy profesional y nos guió en todo el proceso.',
                'rating' => 5,
                'color' => 'bg-primary'
            ],
            [
                'name' => 'Juan Pérez',
                'location' => 'Providencia, Santiago',
                'text' => 'Excelente servicio desde el primer contacto. Vendieron mi departamento en tiempo récord y con un precio superior al esperado.',
                'rating' => 5,
                'color' => 'bg-success'
            ],
            [
                'name' => 'María González',
                'location' => 'Ñuñoa, Santiago',
                'text' => 'Como inversora, PropEasy me ha ayudado a construir un portafolio inmobiliario sólido. Su asesoría financiera es invaluable.',
                'rating' => 5,
                'color' => 'bg-info'
            ],
            [
                'name' => 'Roberto Silva',
                'location' => 'Vitacura, Santiago',
                'text' => 'El proceso de compra fue muy transparente. Me sentí seguro en todo momento y el equipo respondió todas mis dudas.',
                'rating' => 5,
                'color' => 'bg-warning'
            ],
            [
                'name' => 'Carmen López',
                'location' => 'La Florida, Santiago',
                'text' => 'Encontré mi departamento ideal gracias a PropEasy. Su plataforma digital me permitió ver muchas opciones sin salir de casa.',
                'rating' => 5,
                'color' => 'bg-danger'
            ],
            [
                'name' => 'Pedro Ramírez',
                'location' => 'Maipú, Santiago',
                'text' => 'Como vendedor, estoy muy satisfecho con el servicio. Vendieron mi casa en menos tiempo del esperado y con un excelente precio.',
                'rating' => 5,
                'color' => 'bg-secondary'
            ]
        ];
    }

    /**
     * Obtiene certificaciones y premios
     */
    private function getAwards()
    {
        return [
            [
                'name' => 'ISO 9001:2015',
                'description' => 'Certificación de Gestión de Calidad que garantiza nuestros procesos y servicios.',
                'icon' => 'fas fa-certificate',
                'color' => 'bg-primary'
            ],
            [
                'name' => 'Mejor Empresa Inmobiliaria 2023',
                'description' => 'Reconocimiento otorgado por la Cámara Chilena de la Construcción.',
                'icon' => 'fas fa-trophy',
                'color' => 'bg-success'
            ],
            [
                'name' => 'Excelencia en Servicio al Cliente',
                'description' => 'Premio otorgado por la Asociación de Consumidores de Chile.',
                'icon' => 'fas fa-award',
                'color' => 'bg-info'
            ],
            [
                'name' => 'Empresa Digital del Año 2022',
                'description' => 'Reconocimiento por innovación tecnológica en el sector inmobiliario.',
                'icon' => 'fas fa-laptop',
                'color' => 'bg-warning'
            ],
            [
                'name' => 'Responsabilidad Social Empresarial',
                'description' => 'Premio por nuestro compromiso con la comunidad y el medio ambiente.',
                'icon' => 'fas fa-leaf',
                'color' => 'bg-success'
            ],
            [
                'name' => 'Mejor Plataforma Digital 2023',
                'description' => 'Reconocimiento por nuestra plataforma web y aplicaciones móviles.',
                'icon' => 'fas fa-mobile-alt',
                'color' => 'bg-info'
            ]
        ];
    }

    /**
     * Obtiene valores corporativos
     */
    private function getValues()
    {
        return [
            [
                'name' => 'Integridad',
                'description' => 'Actuamos con honestidad y transparencia en todas nuestras transacciones y relaciones comerciales.',
                'icon' => 'fas fa-handshake',
                'color' => 'bg-primary'
            ],
            [
                'name' => 'Innovación',
                'description' => 'Buscamos constantemente nuevas formas de mejorar nuestros servicios y la experiencia del cliente.',
                'icon' => 'fas fa-lightbulb',
                'color' => 'bg-success'
            ],
            [
                'name' => 'Trabajo en Equipo',
                'description' => 'Valoramos la colaboración y el trabajo conjunto para lograr los mejores resultados para nuestros clientes.',
                'icon' => 'fas fa-users',
                'color' => 'bg-info'
            ],
            [
                'name' => 'Compromiso Social',
                'description' => 'Contribuimos al desarrollo de las comunidades donde operamos y promovemos la sostenibilidad.',
                'icon' => 'fas fa-heart',
                'color' => 'bg-warning'
            ],
            [
                'name' => 'Excelencia',
                'description' => 'Nos esforzamos por ofrecer el mejor servicio posible en cada interacción con nuestros clientes.',
                'icon' => 'fas fa-star',
                'color' => 'bg-danger'
            ],
            [
                'name' => 'Transparencia',
                'description' => 'Mantenemos una comunicación clara y honesta con todos nuestros stakeholders.',
                'icon' => 'fas fa-eye',
                'color' => 'bg-secondary'
            ]
        ];
    }

    /**
     * API para obtener información de la empresa
     */
    public function info()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getCompanyInfo()
        ]);
    }

    /**
     * API para obtener estadísticas
     */
    public function stats()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getStats()
        ]);
    }

    /**
     * API para obtener testimonios
     */
    public function testimonials()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getTestimonials()
        ]);
    }

    /**
     * API para obtener equipo
     */
    public function team()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getTeam()
        ]);
    }

    /**
     * API para obtener premios
     */
    public function awards()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getAwards()
        ]);
    }

    /**
     * API para obtener valores
     */
    public function values()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $this->getValues()
        ]);
    }

    /**
     * API para obtener toda la información
     */
    public function all()
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'company' => $this->getCompanyInfo(),
                'team' => $this->getTeam(),
                'stats' => $this->getStats(),
                'testimonials' => $this->getTestimonials(),
                'awards' => $this->getAwards(),
                'values' => $this->getValues()
            ]
        ]);
    }

    /**
     * API para agregar testimonio
     */
    public function addTestimonial()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $location = $_POST['location'] ?? '';
        $text = $_POST['text'] ?? '';
        $rating = $_POST['rating'] ?? 5;

        if (!$name || !$text) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        // Simulación de guardado
        $testimonialData = [
            'name' => $name,
            'location' => $location,
            'text' => $text,
            'rating' => $rating,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];

        // Simular guardado
        error_log("New testimonial: " . json_encode($testimonialData));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Testimonio enviado exitosamente. Será revisado antes de su publicación.'
        ]);
    }

    /**
     * API para contacto corporativo
     */
    public function corporateContact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $company = $_POST['company'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (!$name || !$email || !$subject || !$message) {
            echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Por favor ingresa un email válido']);
            return;
        }

        // Simulación de contacto corporativo
        $contactData = [
            'name' => $name,
            'company' => $company,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'type' => 'corporate',
            'date' => date('Y-m-d H:i:s')
        ];

        // Simular guardado
        error_log("Corporate contact: " . json_encode($contactData));

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Mensaje enviado exitosamente. Te contactaremos pronto.'
        ]);
    }
} 