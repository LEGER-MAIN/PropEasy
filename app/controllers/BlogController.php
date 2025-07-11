<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para el Blog Inmobiliario
 * Maneja la visualización de artículos, búsqueda y filtros
 */
class BlogController extends Controller {
    
    /**
     * Constructor del controlador
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Muestra la página principal del blog
     */
    public function index() {
        // Datos para la vista
        $data = [
            'titulo' => 'Blog Inmobiliario - PropEasy',
            'descripcion' => 'Descubre las últimas tendencias, consejos y noticias del mercado inmobiliario chileno.',
            'palabras_clave' => 'blog, inmobiliaria, noticias, consejos, mercado, PropEasy',
            'stats' => [
                'total_articulos' => 156,
                'total_autores' => 12,
                'total_categorias' => 5,
                'total_vistas' => 45000
            ],
            'articulos_destacados' => [
                [
                    'id' => 1,
                    'titulo' => 'Tendencias del Mercado Inmobiliario 2024',
                    'resumen' => 'Análisis completo de las tendencias que marcarán el mercado inmobiliario este año.',
                    'imagen' => 'assets/img/blog/trends-2024.jpg',
                    'categoria' => 'Mercado Inmobiliario',
                    'categoria_slug' => 'mercado',
                    'categoria_color' => 'primary',
                    'autor' => 'María González',
                    'autor_avatar' => 'assets/img/authors/maria.jpg',
                    'fecha' => '15 Enero, 2024',
                    'vistas' => 2450,
                    'likes' => 128
                ],
                [
                    'id' => 2,
                    'titulo' => 'Guía Completa para Invertir en Propiedades',
                    'resumen' => 'Todo lo que necesitas saber para hacer una inversión inmobiliaria exitosa.',
                    'imagen' => 'assets/img/blog/investment-guide.jpg',
                    'categoria' => 'Inversión',
                    'categoria_slug' => 'inversion',
                    'categoria_color' => 'success',
                    'autor' => 'Carlos Rodríguez',
                    'autor_avatar' => 'assets/img/authors/carlos.jpg',
                    'fecha' => '12 Enero, 2024',
                    'vistas' => 1890,
                    'likes' => 95
                ],
                [
                    'id' => 3,
                    'titulo' => 'Cómo Elegir el Crédito Hipotecario Ideal',
                    'resumen' => 'Consejos prácticos para seleccionar la mejor opción de financiamiento.',
                    'imagen' => 'assets/img/blog/mortgage-guide.jpg',
                    'categoria' => 'Financiamiento',
                    'categoria_slug' => 'financiamiento',
                    'categoria_color' => 'info',
                    'autor' => 'Ana Martínez',
                    'autor_avatar' => 'assets/img/authors/ana.jpg',
                    'fecha' => '10 Enero, 2024',
                    'vistas' => 1650,
                    'likes' => 87
                ]
            ],
            'articulos' => [
                [
                    'id' => 4,
                    'titulo' => 'Los Mejores Barrios para Invertir en Santiago',
                    'resumen' => 'Análisis detallado de las zonas con mayor potencial de crecimiento en la capital.',
                    'imagen' => 'assets/img/blog/santiago-neighborhoods.jpg',
                    'categoria' => 'Inversión',
                    'categoria_slug' => 'inversion',
                    'categoria_color' => 'success',
                    'autor' => 'Roberto Silva',
                    'autor_avatar' => 'assets/img/authors/roberto.jpg',
                    'fecha' => '8 Enero, 2024',
                    'vistas' => 1420,
                    'likes' => 76
                ],
                [
                    'id' => 5,
                    'titulo' => 'Consejos para Vender tu Propiedad Rápidamente',
                    'resumen' => 'Estrategias efectivas para vender tu casa o departamento en el menor tiempo posible.',
                    'imagen' => 'assets/img/blog/sell-fast.jpg',
                    'categoria' => 'Consejos',
                    'categoria_slug' => 'consejos',
                    'categoria_color' => 'warning',
                    'autor' => 'Patricia López',
                    'autor_avatar' => 'assets/img/authors/patricia.jpg',
                    'fecha' => '5 Enero, 2024',
                    'vistas' => 1180,
                    'likes' => 64
                ],
                [
                    'id' => 6,
                    'titulo' => 'Nuevas Tecnologías en el Sector Inmobiliario',
                    'resumen' => 'Descubre cómo la tecnología está transformando la industria inmobiliaria.',
                    'imagen' => 'assets/img/blog/tech-real-estate.jpg',
                    'categoria' => 'Tendencias',
                    'categoria_slug' => 'tendencias',
                    'categoria_color' => 'danger',
                    'autor' => 'Diego Herrera',
                    'autor_avatar' => 'assets/img/authors/diego.jpg',
                    'fecha' => '3 Enero, 2024',
                    'vistas' => 980,
                    'likes' => 52
                ]
            ],
            'articulos_populares' => [
                [
                    'id' => 1,
                    'titulo' => 'Tendencias del Mercado Inmobiliario 2024',
                    'imagen' => 'assets/img/blog/trends-2024.jpg',
                    'vistas' => 2450
                ],
                [
                    'id' => 2,
                    'titulo' => 'Guía Completa para Invertir en Propiedades',
                    'imagen' => 'assets/img/blog/investment-guide.jpg',
                    'vistas' => 1890
                ],
                [
                    'id' => 3,
                    'titulo' => 'Cómo Elegir el Crédito Hipotecario Ideal',
                    'imagen' => 'assets/img/blog/mortgage-guide.jpg',
                    'vistas' => 1650
                ]
            ],
            'categorias' => [
                [
                    'nombre' => 'Mercado Inmobiliario',
                    'slug' => 'mercado',
                    'icono' => 'chart-line',
                    'cantidad' => 45
                ],
                [
                    'nombre' => 'Inversión',
                    'slug' => 'inversion',
                    'icono' => 'dollar-sign',
                    'cantidad' => 38
                ],
                [
                    'nombre' => 'Financiamiento',
                    'slug' => 'financiamiento',
                    'icono' => 'calculator',
                    'cantidad' => 32
                ],
                [
                    'nombre' => 'Consejos',
                    'slug' => 'consejos',
                    'icono' => 'lightbulb',
                    'cantidad' => 28
                ],
                [
                    'nombre' => 'Tendencias',
                    'slug' => 'tendencias',
                    'icono' => 'trending-up',
                    'cantidad' => 13
                ]
            ],
            'tags' => [
                'inversión', 'mercado', 'crédito', 'hipoteca', 'santiago', 'propiedades', 
                'financiamiento', 'consejos', 'tendencias', 'tecnología', 'barrios', 'venta'
            ]
        ];
        
        // Cargar la vista
        $this->render('blog/index', $data);
    }
    
    /**
     * Muestra un artículo específico
     */
    public function article($id) {
        // Simular obtención de artículo por ID
        $articulo = $this->getArticulo($id);
        
        if (!$articulo) {
            // Redirigir a 404 si no existe
            header('Location: /propeasy/public/404');
            exit;
        }
        
        // Incrementar vistas
        $this->incrementarVistas($id);
        
        // Obtener artículos relacionados
        $articulos_relacionados = $this->getArticulosRelacionados($id, $articulo['categoria_slug']);
        
        $data = [
            'titulo' => $articulo['titulo'] . ' - Blog PropEasy',
            'descripcion' => $articulo['resumen'],
            'articulo' => $articulo,
            'articulos_relacionados' => $articulos_relacionados
        ];
        
        // Cargar la vista del artículo
        require_once 'app/views/blog/article.php';
    }
    
    /**
     * API para buscar artículos
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
        $tag = $input['tag'] ?? '';
        
        if (empty($query) && empty($categoria) && empty($tag)) {
            http_response_code(400);
            echo json_encode(['error' => 'Se requiere al menos un criterio de búsqueda']);
            return;
        }
        
        // Simular búsqueda en base de datos
        $resultados = $this->buscarArticulos($query, $categoria, $tag);
        
        $response = [
            'query' => $query,
            'categoria' => $categoria,
            'tag' => $tag,
            'total_resultados' => count($resultados),
            'resultados' => $resultados
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para obtener artículos por categoría
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
        
        // Simular obtención de artículos por categoría
        $articulos = $this->getArticulosPorCategoria($categoria);
        
        $response = [
            'categoria' => $categoria,
            'total_articulos' => count($articulos),
            'articulos' => $articulos
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para registrar like en un artículo
     */
    public function like() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $articuloId = $input['articulo_id'] ?? '';
        $userId = $input['user_id'] ?? null;
        
        if (empty($articuloId)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de artículo requerido']);
            return;
        }
        
        // Simular registro de like
        $success = $this->registrarLike($articuloId, $userId);
        
        if ($success) {
            $response = [
                'success' => true,
                'message' => 'Like registrado correctamente',
                'articulo_id' => $articuloId
            ];
        } else {
            http_response_code(500);
            $response = [
                'success' => false,
                'error' => 'Error al registrar el like'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * API para suscribirse al newsletter
     */
    public function subscribe() {
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        // Obtener datos del POST
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $nombre = $input['nombre'] ?? '';
        
        if (empty($email)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email requerido']);
            return;
        }
        
        // Simular suscripción
        $success = $this->registrarSuscripcion($email, $nombre);
        
        if ($success) {
            $response = [
                'success' => true,
                'message' => 'Te has suscrito correctamente al newsletter. Recibirás nuestras últimas noticias.',
                'email' => $email
            ];
        } else {
            http_response_code(500);
            $response = [
                'success' => false,
                'error' => 'Error al procesar la suscripción'
            ];
        }
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Simula la obtención de un artículo por ID
     * @param int $id ID del artículo
     * @return array|null Datos del artículo
     */
    private function getArticulo($id) {
        // Base de datos simulada de artículos
        $articulos = [
            1 => [
                'id' => 1,
                'titulo' => 'Tendencias del Mercado Inmobiliario 2024',
                'contenido' => '<p>El mercado inmobiliario chileno está experimentando cambios significativos en 2024...</p>',
                'resumen' => 'Análisis completo de las tendencias que marcarán el mercado inmobiliario este año.',
                'imagen' => 'assets/img/blog/trends-2024.jpg',
                'categoria' => 'Mercado Inmobiliario',
                'categoria_slug' => 'mercado',
                'categoria_color' => 'primary',
                'autor' => 'María González',
                'autor_avatar' => 'assets/img/authors/maria.jpg',
                'fecha' => '15 Enero, 2024',
                'vistas' => 2450,
                'likes' => 128,
                'tags' => ['mercado', 'tendencias', '2024', 'análisis']
            ]
        ];
        
        return $articulos[$id] ?? null;
    }
    
    /**
     * Simula la búsqueda de artículos
     * @param string $query Término de búsqueda
     * @param string $categoria Categoría específica
     * @param string $tag Tag específico
     * @return array Resultados de búsqueda
     */
    private function buscarArticulos($query, $categoria = '', $tag = '') {
        // Base de datos simulada de artículos
        $articulos = [
            [
                'id' => 1,
                'titulo' => 'Tendencias del Mercado Inmobiliario 2024',
                'resumen' => 'Análisis completo de las tendencias que marcarán el mercado inmobiliario este año.',
                'categoria_slug' => 'mercado',
                'tags' => ['mercado', 'tendencias', '2024']
            ],
            [
                'id' => 2,
                'titulo' => 'Guía Completa para Invertir en Propiedades',
                'resumen' => 'Todo lo que necesitas saber para hacer una inversión inmobiliaria exitosa.',
                'categoria_slug' => 'inversion',
                'tags' => ['inversión', 'propiedades', 'guía']
            ]
        ];
        
        $resultados = [];
        $query = strtolower($query);
        
        foreach ($articulos as $articulo) {
            // Filtrar por categoría si se especifica
            if (!empty($categoria) && $articulo['categoria_slug'] !== $categoria) {
                continue;
            }
            
            // Filtrar por tag si se especifica
            if (!empty($tag) && !in_array($tag, $articulo['tags'])) {
                continue;
            }
            
            // Buscar en título y resumen
            if (empty($query) || 
                strpos(strtolower($articulo['titulo']), $query) !== false ||
                strpos(strtolower($articulo['resumen']), $query) !== false) {
                $resultados[] = $articulo;
            }
        }
        
        return $resultados;
    }
    
    /**
     * Simula la obtención de artículos por categoría
     * @param string $categoria Categoría de artículos
     * @return array Artículos de la categoría
     */
    private function getArticulosPorCategoria($categoria) {
        // Simular artículos por categoría
        $articulos = [
            'mercado' => [
                [
                    'id' => 1,
                    'titulo' => 'Tendencias del Mercado Inmobiliario 2024',
                    'resumen' => 'Análisis completo de las tendencias que marcarán el mercado inmobiliario este año.',
                    'imagen' => 'assets/img/blog/trends-2024.jpg',
                    'fecha' => '15 Enero, 2024',
                    'vistas' => 2450
                ]
            ],
            'inversion' => [
                [
                    'id' => 2,
                    'titulo' => 'Guía Completa para Invertir en Propiedades',
                    'resumen' => 'Todo lo que necesitas saber para hacer una inversión inmobiliaria exitosa.',
                    'imagen' => 'assets/img/blog/investment-guide.jpg',
                    'fecha' => '12 Enero, 2024',
                    'vistas' => 1890
                ]
            ]
        ];
        
        return $articulos[$categoria] ?? [];
    }
    
    /**
     * Simula la obtención de artículos relacionados
     * @param int $articuloId ID del artículo actual
     * @param string $categoria Categoría del artículo
     * @return array Artículos relacionados
     */
    private function getArticulosRelacionados($articuloId, $categoria) {
        // Simular artículos relacionados
        return [
            [
                'id' => 2,
                'titulo' => 'Guía Completa para Invertir en Propiedades',
                'resumen' => 'Todo lo que necesitas saber para hacer una inversión inmobiliaria exitosa.',
                'imagen' => 'assets/img/blog/investment-guide.jpg',
                'fecha' => '12 Enero, 2024'
            ]
        ];
    }
    
    /**
     * Simula el incremento de vistas
     * @param int $articuloId ID del artículo
     */
    private function incrementarVistas($articuloId) {
        // En producción, actualizar en la base de datos
        return true;
    }
    
    /**
     * Simula el registro de un like
     * @param int $articuloId ID del artículo
     * @param int|null $userId ID del usuario
     * @return bool True si se registró correctamente
     */
    private function registrarLike($articuloId, $userId) {
        // En producción, insertar en la base de datos
        return true;
    }
    
    /**
     * Simula el registro de suscripción al newsletter
     * @param string $email Email del suscriptor
     * @param string $nombre Nombre del suscriptor
     * @return bool True si se registró correctamente
     */
    private function registrarSuscripcion($email, $nombre) {
        // En producción, insertar en la base de datos y enviar email de confirmación
        return true;
    }
} 