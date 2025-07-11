<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-home me-2"></i>PropEasy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/properties">Propiedades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contacto</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <?php if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']): ?>
                        <a href="/auth/logout" class="btn btn-outline-light ms-2">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-outline-light ms-2">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                        <a href="/auth/register" class="btn btn-outline-light ms-2">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Mapa del Sitio</h1>
                    <p class="lead mb-4">Navega fácilmente por todas las secciones y páginas de PropEasy.</p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sitemap fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0"><?= $stats['total_paginas'] ?> páginas</h5>
                            <small>Organizadas por categorías</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="assets/img/sitemap-hero.jpg" alt="Mapa del Sitio" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Navegación Rápida -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-compass me-2"></i>Navegación Rápida</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="#propiedades" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-home me-2"></i>Propiedades
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#servicios" class="btn btn-outline-success w-100">
                                    <i class="fas fa-tools me-2"></i>Servicios
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#empresa" class="btn btn-outline-info w-100">
                                    <i class="fas fa-building me-2"></i>Empresa
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#ayuda" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-question-circle me-2"></i>Ayuda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estructura del Sitio -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Propiedades -->
                <div class="card border-0 shadow-sm mb-4" id="propiedades">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-home me-2"></i>Propiedades</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary">Páginas Principales</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Página Principal
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/properties" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Listado de Propiedades
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/properties/detail/1" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Detalle de Propiedad
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary">Filtros y Búsqueda</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/properties?tipo=casa" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Casas
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/properties?tipo=departamento" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Departamentos
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/properties?tipo=terreno" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Terrenos
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Servicios -->
                <div class="card border-0 shadow-sm mb-4" id="servicios">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Servicios</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success">Servicios Principales</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/services/tasacion" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Tasaciones
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/services/asesoria" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Asesoría Legal
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/services/financiamiento" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Financiamiento
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success">Herramientas</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/tools/calculator" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Calculadora de Crédito
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/tools/valuation" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Avalúo Online
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/tools/compare" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Comparador
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empresa -->
                <div class="card border-0 shadow-sm mb-4" id="empresa">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-building me-2"></i>Empresa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-info">Información Corporativa</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/about" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Sobre Nosotros
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/team" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Nuestro Equipo
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/careers" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Carreras
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-info">Contenido</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/blog" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Blog
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/news" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Noticias
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/contact" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Contacto
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ayuda -->
                <div class="card border-0 shadow-sm mb-4" id="ayuda">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Ayuda y Soporte</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning">Soporte</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/faq" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Preguntas Frecuentes
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/support" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Centro de Ayuda
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/contact" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Contactar Soporte
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning">Legal</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="/terms" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Términos y Condiciones
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/privacy" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Política de Privacidad
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="/cookies" class="text-decoration-none">
                                            <i class="fas fa-chevron-right me-2"></i>Política de Cookies
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Estadísticas del Sitio -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <h4 class="text-primary fw-bold"><?= $stats['total_paginas'] ?></h4>
                                <small class="text-muted">Páginas</small>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-success fw-bold"><?= $stats['categorias'] ?></h4>
                                <small class="text-muted">Categorías</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info fw-bold"><?= $stats['propiedades'] ?></h4>
                                <small class="text-muted">Propiedades</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning fw-bold"><?= $stats['articulos'] ?></h4>
                                <small class="text-muted">Artículos</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descargar Sitemap -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-download me-2"></i>Descargar Sitemap</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-file-code fa-3x text-success mb-3"></i>
                        <h6 class="fw-bold">Sitemap XML</h6>
                        <p class="text-muted mb-3">Descarga el sitemap en formato XML para motores de búsqueda</p>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" onclick="downloadSitemapXML()">
                                <i class="fas fa-download me-2"></i>Descargar XML
                            </button>
                            <button class="btn btn-outline-success" onclick="downloadSitemapTXT()">
                                <i class="fas fa-file-alt me-2"></i>Descargar TXT
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Enlaces Útiles -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-link me-2"></i>Enlaces Útiles</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="/" class="text-decoration-none">
                                    <i class="fas fa-home me-2"></i>Página Principal
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/properties" class="text-decoration-none">
                                    <i class="fas fa-search me-2"></i>Buscar Propiedades
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/contact" class="text-decoration-none">
                                    <i class="fas fa-envelope me-2"></i>Contacto
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/faq" class="text-decoration-none">
                                    <i class="fas fa-question-circle me-2"></i>FAQ
                                </a>
                            </li>
                            <li>
                                <a href="/blog" class="text-decoration-none">
                                    <i class="fas fa-newspaper me-2"></i>Blog
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Información Técnica -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Información Técnica</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold">Última Actualización</h6>
                            <p class="text-muted mb-0"><?= date('d/m/Y H:i') ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Versión del Sitio</h6>
                            <p class="text-muted mb-0">v2.1.0</p>
                        </div>
                        <div>
                            <h6 class="fw-bold">Formato XML</h6>
                            <p class="text-muted mb-0">Disponible en /sitemap.xml</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">PropEasy</h5>
                    <p class="text-muted">Tu socio confiable en el mercado inmobiliario. Más de 10 años ayudando a familias a encontrar su hogar ideal.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Propiedades</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Comprar</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Vender</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Arrendar</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Invertir</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Servicios</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Tasaciones</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Asesoría Legal</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Financiamiento</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Seguros</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Empresa</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Nosotros</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Agentes</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Carreras</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Ayuda</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Soporte</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contacto</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Sitemap</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2024 PropEasy. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">Desarrollado con <i class="fas fa-heart text-danger"></i> en Chile</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/sitemap.js"></script>
</body>
</html> 