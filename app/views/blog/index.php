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
            <a class="navbar-brand fw-bold" href="/propeasy/public/">
                <i class="fas fa-home me-2"></i>PropEasy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/propeasy/public/properties">Propiedades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/propeasy/public/about">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/propeasy/public/contact">Contacto</a>
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
                    <h1 class="display-4 fw-bold mb-4">Blog Inmobiliario</h1>
                    <p class="lead mb-4">Descubre las últimas tendencias, consejos y noticias del mercado inmobiliario chileno.</p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-newspaper fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0"><?= $stats['total_articulos'] ?> artículos</h5>
                            <small>Actualizado semanalmente</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="assets/img/blog-hero.jpg" alt="Blog Inmobiliario" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Barra de Búsqueda y Filtros -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="search-blog" class="form-control" 
                                           placeholder="Buscar artículos...">
                                    <button class="btn btn-primary" type="button" onclick="searchBlog()">
                                        <i class="fas fa-search me-2"></i>Buscar
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 flex-wrap">
                                    <select class="form-select" id="category-filter" onchange="filterByCategory()">
                                        <option value="">Todas las categorías</option>
                                        <option value="mercado">Mercado Inmobiliario</option>
                                        <option value="inversion">Inversión</option>
                                        <option value="financiamiento">Financiamiento</option>
                                        <option value="consejos">Consejos</option>
                                        <option value="tendencias">Tendencias</option>
                                    </select>
                                    <select class="form-select" id="sort-filter" onchange="sortArticles()">
                                        <option value="recent">Más recientes</option>
                                        <option value="popular">Más populares</option>
                                        <option value="title">Por título</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Artículos Destacados -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">
                    <i class="fas fa-star text-warning me-2"></i>
                    Artículos Destacados
                </h3>
                <div class="row">
                    <?php foreach ($articulos_destacados as $articulo): ?>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="<?= $articulo['imagen'] ?>" class="card-img-top" alt="<?= $articulo['titulo'] ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-<?= $articulo['categoria_color'] ?>"><?= $articulo['categoria'] ?></span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i><?= $articulo['fecha'] ?>
                                    </small>
                                </div>
                                <h5 class="card-title"><?= $articulo['titulo'] ?></h5>
                                <p class="card-text text-muted"><?= $articulo['resumen'] ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $articulo['autor_avatar'] ?>" class="rounded-circle me-2" width="30" height="30" alt="<?= $articulo['autor'] ?>">
                                        <small class="text-muted"><?= $articulo['autor'] ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-eye me-1 text-muted"></i>
                                        <small class="text-muted me-3"><?= $articulo['vistas'] ?></small>
                                        <i class="fas fa-heart me-1 text-muted"></i>
                                        <small class="text-muted"><?= $articulo['likes'] ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <a href="/propeasy/public/blog/article/<?= $articulo['id'] ?>" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-arrow-right me-2"></i>Leer Más
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Listado Principal de Artículos -->
        <div class="row">
            <div class="col-lg-8">
                <h3 class="mb-4">
                    <i class="fas fa-newspaper text-primary me-2"></i>
                    Últimos Artículos
                </h3>
                
                <div id="articles-container">
                    <?php foreach ($articulos as $articulo): ?>
                    <div class="card mb-4 border-0 shadow-sm article-card" data-category="<?= $articulo['categoria_slug'] ?>">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= $articulo['imagen'] ?>" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="<?= $articulo['titulo'] ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-<?= $articulo['categoria_color'] ?>"><?= $articulo['categoria'] ?></span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i><?= $articulo['fecha'] ?>
                                        </small>
                                    </div>
                                    <h5 class="card-title"><?= $articulo['titulo'] ?></h5>
                                    <p class="card-text text-muted"><?= $articulo['resumen'] ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $articulo['autor_avatar'] ?>" class="rounded-circle me-2" width="25" height="25" alt="<?= $articulo['autor'] ?>">
                                            <small class="text-muted"><?= $articulo['autor'] ?></small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-eye me-1 text-muted"></i>
                                            <small class="text-muted me-3"><?= $articulo['vistas'] ?></small>
                                            <i class="fas fa-heart me-1 text-muted"></i>
                                            <small class="text-muted"><?= $articulo['likes'] ?></small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="/propeasy/public/blog/article/<?= $articulo['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-arrow-right me-2"></i>Leer Más
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm ms-2" onclick="shareArticle(<?= $articulo['id'] ?>)">
                                            <i class="fas fa-share me-2"></i>Compartir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <nav aria-label="Navegación de artículos">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categorías -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Categorías</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($categorias as $categoria): ?>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                               onclick="filterByCategory('<?= $categoria['slug'] ?>')">
                                <span>
                                    <i class="fas fa-<?= $categoria['icono'] ?> me-2"></i>
                                    <?= $categoria['nombre'] ?>
                                </span>
                                <span class="badge bg-primary rounded-pill"><?= $categoria['cantidad'] ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Artículos Populares -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Más Populares</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($articulos_populares as $articulo): ?>
                        <div class="d-flex mb-3">
                            <img src="<?= $articulo['imagen'] ?>" class="rounded me-3" width="60" height="60" style="object-fit: cover;" alt="<?= $articulo['titulo'] ?>">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="/propeasy/public/blog/article/<?= $articulo['id'] ?>" class="text-decoration-none">
                                        <?= $articulo['titulo'] ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i><?= $articulo['vistas'] ?> vistas
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Newsletter</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Suscríbete para recibir las últimas noticias del mercado inmobiliario.</p>
                        <form id="newsletter-form">
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Tu email" required>
                            </div>
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-paper-plane me-2"></i>Suscribirse
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tags -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-hashtag me-2"></i>Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($tags as $tag): ?>
                            <span class="badge bg-light text-dark" style="cursor: pointer;" onclick="filterByTag('<?= $tag ?>')">
                                #<?= $tag ?>
                            </span>
                            <?php endforeach; ?>
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
                        <li><a href="#" class="text-muted text-decoration-none">Términos</a></li>
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
    <script src="assets/js/blog.js"></script>
</body>
</html> 