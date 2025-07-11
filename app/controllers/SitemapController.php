<?php
require_once __DIR__ . '/../../core/Controller.php';

/**
 * Controlador para el Sitemap
 * Maneja la visualización de la estructura del sitio y generación de XML
 */
class SitemapController extends Controller {
    
    /**
     * Constructor del controlador
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Muestra la página principal del sitemap
     */
    public function index() {
        // Datos para la vista
        $data = [
            'titulo' => 'Mapa del Sitio - PropEasy',
            'descripcion' => 'Navega fácilmente por todas las secciones y páginas de PropEasy.',
            'palabras_clave' => 'sitemap, mapa del sitio, navegación, PropEasy',
            'stats' => [
                'total_paginas' => 45,
                'categorias' => 8,
                'propiedades' => 250,
                'articulos' => 156
            ]
        ];
        
        // Cargar la vista
        $this->render('sitemap/index', $data);
    }
    
    /**
     * Genera el sitemap en formato XML
     */
    public function generateXML() {
        // Configurar headers para XML
        header('Content-Type: application/xml; charset=utf-8');
        
        // Generar XML del sitemap
        $xml = $this->generateSitemapXML();
        
        echo $xml;
    }
    
    /**
     * Genera el sitemap en formato TXT
     */
    public function generateTXT() {
        // Configurar headers para descarga
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="sitemap.txt"');
        
        // Generar TXT del sitemap
        $txt = $this->generateSitemapTXT();
        
        echo $txt;
    }
    
    /**
     * API para obtener la estructura del sitio
     */
    public function getStructure() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $structure = [
            'paginas_principales' => [
                [
                    'titulo' => 'Página Principal',
                    'url' => '/propeasy/public/',
                    'descripcion' => 'Inicio del sitio web',
                    'prioridad' => 1.0,
                    'frecuencia' => 'daily'
                ],
                [
                    'titulo' => 'Propiedades',
                    'url' => '/propeasy/public/properties',
                    'descripcion' => 'Listado de propiedades',
                    'prioridad' => 0.9,
                    'frecuencia' => 'daily'
                ],
                [
                    'titulo' => 'Nosotros',
                    'url' => '/propeasy/public/about',
                    'descripcion' => 'Información sobre la empresa',
                    'prioridad' => 0.7,
                    'frecuencia' => 'weekly'
                ],
                [
                    'titulo' => 'Contacto',
                    'url' => '/propeasy/public/contact',
                    'descripcion' => 'Información de contacto',
                    'prioridad' => 0.8,
                    'frecuencia' => 'monthly'
                ]
            ],
            'propiedades' => [
                [
                    'titulo' => 'Detalle de Propiedad',
                    'url' => '/propeasy/public/properties/detail/{id}',
                    'descripcion' => 'Páginas de detalle de propiedades',
                    'prioridad' => 0.8,
                    'frecuencia' => 'weekly'
                ]
            ],
            'servicios' => [
                [
                    'titulo' => 'Tasaciones',
                    'url' => '/propeasy/public/services/tasacion',
                    'descripcion' => 'Servicio de tasación',
                    'prioridad' => 0.7,
                    'frecuencia' => 'monthly'
                ],
                [
                    'titulo' => 'Asesoría Legal',
                    'url' => '/propeasy/public/services/asesoria',
                    'descripcion' => 'Servicio de asesoría legal',
                    'prioridad' => 0.7,
                    'frecuencia' => 'monthly'
                ],
                [
                    'titulo' => 'Financiamiento',
                    'url' => '/propeasy/public/services/financiamiento',
                    'descripcion' => 'Servicios de financiamiento',
                    'prioridad' => 0.7,
                    'frecuencia' => 'monthly'
                ]
            ],
            'herramientas' => [
                [
                    'titulo' => 'Calculadora de Crédito',
                    'url' => '/propeasy/public/tools/calculator',
                    'descripcion' => 'Calculadora de crédito hipotecario',
                    'prioridad' => 0.6,
                    'frecuencia' => 'monthly'
                ],
                [
                    'titulo' => 'Avalúo Online',
                    'url' => '/propeasy/public/tools/valuation',
                    'descripcion' => 'Herramienta de avalúo online',
                    'prioridad' => 0.6,
                    'frecuencia' => 'monthly'
                ]
            ],
            'contenido' => [
                [
                    'titulo' => 'Blog',
                    'url' => '/propeasy/public/blog',
                    'descripcion' => 'Blog inmobiliario',
                    'prioridad' => 0.6,
                    'frecuencia' => 'weekly'
                ],
                [
                    'titulo' => 'FAQ',
                    'url' => '/propeasy/public/faq',
                    'descripcion' => 'Preguntas frecuentes',
                    'prioridad' => 0.5,
                    'frecuencia' => 'monthly'
                ]
            ],
            'legal' => [
                [
                    'titulo' => 'Términos y Condiciones',
                    'url' => '/propeasy/public/terms',
                    'descripcion' => 'Términos y condiciones de uso',
                    'prioridad' => 0.3,
                    'frecuencia' => 'yearly'
                ],
                [
                    'titulo' => 'Política de Privacidad',
                    'url' => '/propeasy/public/privacy',
                    'descripcion' => 'Política de privacidad',
                    'prioridad' => 0.3,
                    'frecuencia' => 'yearly'
                ]
            ]
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($structure);
    }
    
    /**
     * API para obtener estadísticas del sitemap
     */
    public function getStats() {
        // Verificar si la solicitud es GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $stats = [
            'total_paginas' => 45,
            'paginas_por_categoria' => [
                'principales' => 4,
                'propiedades' => 250,
                'servicios' => 3,
                'herramientas' => 2,
                'contenido' => 156,
                'legal' => 2
            ],
            'ultima_actualizacion' => date('Y-m-d H:i:s'),
            'version' => '2.1.0',
            'formato_xml' => true,
            'formato_txt' => true
        ];
        
        // Configurar headers para JSON
        header('Content-Type: application/json');
        echo json_encode($stats);
    }
    
    /**
     * Genera el XML del sitemap
     * @return string XML del sitemap
     */
    private function generateSitemapXML() {
        $baseUrl = 'https://propeasy.cl';
        $currentDate = date('Y-m-d');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Páginas principales
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/properties', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/about', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => '/contact', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/blog', 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['url' => '/faq', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/careers', 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['url' => '/terms', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/privacy', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/sitemap', 'priority' => '0.4', 'changefreq' => 'monthly']
        ];
        
        foreach ($pages as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . $baseUrl . $page['url'] . "</loc>\n";
            $xml .= "    <lastmod>" . $currentDate . "</lastmod>\n";
            $xml .= "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $page['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Genera el TXT del sitemap
     * @return string TXT del sitemap
     */
    private function generateSitemapTXT() {
        $baseUrl = 'https://propeasy.cl';
        
        $txt = "# Sitemap de PropEasy\n";
        $txt .= "# Generado el: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Páginas principales
        $pages = [
            '/',
            '/properties',
            '/about',
            '/contact',
            '/blog',
            '/faq',
            '/careers',
            '/terms',
            '/privacy',
            '/sitemap'
        ];
        
        foreach ($pages as $page) {
            $txt .= $baseUrl . $page . "\n";
        }
        
        return $txt;
    }
} 