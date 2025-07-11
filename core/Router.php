<?php
// Clase Router para gestionar las rutas del sistema
class Router {
    public function run() {
        // Ruta por defecto: HomeController@index
        $controller = 'HomeController';
        $method = 'index';
        $params = [];

        // Parseo de la URL
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            if (isset($url[0]) && $url[0] != '') {
                $controller = ucfirst($url[0]) . 'Controller';
                unset($url[0]);
            }
            if (isset($url[1])) {
                $method = $url[1];
                unset($url[1]);
                // Forzar coincidencia exacta con el método del controlador (case-insensitive)
                $controllerClass = isset($controller) ? $controller : '';
                $controllerFile = '../app/controllers/' . $controllerClass . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    if (class_exists($controllerClass)) {
                        foreach (get_class_methods($controllerClass) as $m) {
                            if (strcasecmp($m, $method) === 0) {
                                $method = $m;
                                break;
                            }
                        }
                    }
                }
            }
            $params = $url ? array_values($url) : [];
        }

        // Alias para rutas de autenticación
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
            if ($url === 'login') {
                $controller = 'AuthController';
                $method = 'login';
                $params = [];
            } elseif ($url === 'register') {
                $controller = 'AuthController';
                $method = 'register';
                $params = [];
            } elseif ($url === 'logout') {
                $controller = 'AuthController';
                $method = 'logout';
                $params = [];
            } elseif ($url === 'forgot-password') {
                $controller = 'AuthController';
                $method = 'forgotPassword';
                $params = [];
            } elseif ($url === 'reset-password') {
                $controller = 'AuthController';
                $method = 'resetPassword';
                $params = [];
            } elseif ($url === 'validate') {
                $controller = 'AuthController';
                $method = 'validate';
                $params = [];
            }
        }

        // Mapeo de rutas específicas para agentes
        $this->mapAgentRoutes($controller, $method, $params);

        $controllerPath = '../app/controllers/' . $controller . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            if (class_exists($controller)) {
                $obj = new $controller();
                if (method_exists($obj, $method)) {
                    call_user_func_array([$obj, $method], $params);
                    return;
                }
            }
        }
        // Si no existe la ruta, mostrar error simple
        echo 'Página no encontrada.';
    }

    /**
     * Mapea rutas específicas para agentes y propiedades
     */
    private function mapAgentRoutes(&$controller, &$method, &$params) {
        // Rutas de autenticación
        if ($controller === 'AuthController') {
            // Rutas específicas de autenticación
            switch ($method) {
                case 'login':
                case 'register':
                case 'logout':
                case 'processLogin':
                case 'processRegister':
                case 'forgotPassword':
                case 'processForgotPassword':
                case 'resetPassword':
                case 'processResetPassword':
                case 'validate':
                    // Métodos válidos de autenticación
                    break;
                default:
                    // Si no es un método válido, redirigir al login
                    $method = 'login';
                    break;
            }
        }

        // Rutas del dashboard del agente
        if ($controller === 'AgentController') {
            // Rutas específicas del agente
            switch ($method) {
                case 'dashboard':
                case 'properties':
                case 'clients':
                case 'appointments':
                case 'messages':
                case 'stats':
                case 'markMessageAsRead':
                case 'confirmAppointment':
                    // Métodos válidos del agente
                    break;
                default:
                    // Si no es un método válido, redirigir al dashboard
                    $method = 'dashboard';
                    break;
            }
        }

        // Rutas de propiedades
        if ($controller === 'PropertiesController') {
            // Rutas específicas de propiedades
            switch ($method) {
                case 'index':
                case 'detail':
                case 'contact':
                case 'processContact':
                case 'appointment':
                case 'processAppointment':
                case 'report':
                case 'processReport':
                case 'favorite':
                case 'stats':
                case 'search':
                case 'pending':
                case 'validate':
                case 'processValidate':
                case 'publish':
                case 'processPublish':
                case 'filter':
                    // Métodos válidos de propiedades
                    break;
                default:
                    // Si no es un método válido, redirigir al listado
                    $method = 'index';
                    break;
            }
        }

        // Rutas de contacto
        if ($controller === 'ContactController') {
            // Rutas específicas de contacto
            switch ($method) {
                case 'index':
                case 'send':
                case 'info':
                case 'faqs':
                case 'newsletter':
                case 'report':
                case 'callback':
                    // Métodos válidos de contacto
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }

        // Rutas de "Nosotros"
        if ($controller === 'AboutController') {
            // Rutas específicas de "Nosotros"
            switch ($method) {
                case 'index':
                case 'info':
                case 'stats':
                case 'testimonials':
                case 'team':
                case 'awards':
                case 'values':
                case 'all':
                case 'addTestimonial':
                case 'corporateContact':
                    // Métodos válidos de "Nosotros"
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }

        // Rutas de Política de Privacidad
        if ($controller === 'PrivacyController') {
            // Rutas específicas de Política de Privacidad
            switch ($method) {
                case 'index':
                case 'info':
                case 'legal':
                case 'contact':
                case 'dataAccess':
                case 'dataDeletion':
                case 'dataRectification':
                case 'download':
                case 'changelog':
                    // Métodos válidos de Política de Privacidad
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }

        // Rutas de Términos y Condiciones
        if ($controller === 'TermsController') {
            // Rutas específicas de Términos y Condiciones
            switch ($method) {
                case 'index':
                case 'downloadPDF':
                case 'getTermsInfo':
                case 'checkAcceptance':
                case 'acceptTerms':
                case 'getAcceptanceStats':
                    // Métodos válidos de Términos y Condiciones
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }
        
        // Rutas de FAQ
        if ($controller === 'FaqController') {
            // Rutas específicas de FAQ
            switch ($method) {
                case 'index':
                case 'search':
                case 'getStats':
                case 'getByCategory':
                case 'registerView':
                case 'suggestQuestion':
                    // Métodos válidos de FAQ
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }
        
        // Rutas de Blog
        if ($controller === 'BlogController') {
            // Rutas específicas de Blog
            switch ($method) {
                case 'index':
                case 'article':
                case 'search':
                case 'getByCategory':
                case 'like':
                case 'subscribe':
                    // Métodos válidos de Blog
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }
        
        // Rutas de Carreras
        if ($controller === 'CareersController') {
            // Rutas específicas de Carreras
            switch ($method) {
                case 'index':
                case 'search':
                case 'apply':
                case 'getJobDetails':
                case 'getStats':
                    // Métodos válidos de Carreras
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }
        
        // Rutas de Sitemap
        if ($controller === 'SitemapController') {
            // Rutas específicas de Sitemap
            switch ($method) {
                case 'index':
                case 'generateXML':
                case 'generateTXT':
                case 'getStructure':
                case 'getStats':
                    // Métodos válidos de Sitemap
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }
        
        // Rutas de Solicitudes de Compra
        if ($controller === 'SolicitudCompraController') {
            // Rutas específicas de Solicitudes de Compra
            switch ($method) {
                case 'index':
                case 'crear':
                case 'misSolicitudes':
                case 'solicitudesAgente':
                case 'ver':
                case 'actualizarEstado':
                case 'asignarAgente':
                case 'estadisticas':
                case 'buscar':
                case 'cancelar':
                    // Métodos válidos de Solicitudes de Compra
                    break;
                default:
                    // Si no es un método válido, redirigir al index
                    $method = 'index';
                    break;
            }
        }
    }
} 