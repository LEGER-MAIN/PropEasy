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
                    <h1 class="display-4 fw-bold mb-4">Únete a Nuestro Equipo</h1>
                    <p class="lead mb-4">Forma parte de una empresa líder en el sector inmobiliario y desarrolla tu carrera profesional con nosotros.</p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0"><?= $stats['total_vacantes'] ?> posiciones abiertas</h5>
                            <small>En diferentes áreas y ubicaciones</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="assets/img/careers-hero.jpg" alt="Carreras PropEasy" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Filtros de Búsqueda -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="search-jobs" class="form-control" 
                                           placeholder="Buscar empleos...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="department-filter" onchange="filterJobs()">
                                    <option value="">Todos los departamentos</option>
                                    <option value="ventas">Ventas</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="tecnologia">Tecnología</option>
                                    <option value="administracion">Administración</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="location-filter" onchange="filterJobs()">
                                    <option value="">Todas las ubicaciones</option>
                                    <option value="santiago">Santiago</option>
                                    <option value="valparaiso">Valparaíso</option>
                                    <option value="concepcion">Concepción</option>
                                    <option value="remoto">Remoto</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" onclick="searchJobs()">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                <h4 class="fw-bold"><?= $stats['empleados'] ?>+</h4>
                                <p class="text-muted mb-0">Empleados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>
                                <h4 class="fw-bold"><?= $stats['oficinas'] ?></h4>
                                <p class="text-muted mb-0">Oficinas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="fas fa-star fa-3x text-warning mb-3"></i>
                                <h4 class="fw-bold"><?= $stats['anos_experiencia'] ?> años</h4>
                                <p class="text-muted mb-0">Experiencia</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <i class="fas fa-trophy fa-3x text-info mb-3"></i>
                                <h4 class="fw-bold"><?= $stats['premios'] ?>+</h4>
                                <p class="text-muted mb-0">Premios</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listado de Vacantes -->
        <div class="row">
            <div class="col-lg-8">
                <h3 class="mb-4">
                    <i class="fas fa-briefcase text-primary me-2"></i>
                    Vacantes Disponibles
                </h3>
                
                <div id="jobs-container">
                    <?php foreach ($vacantes as $vacante): ?>
                    <div class="card mb-4 border-0 shadow-sm job-card" 
                         data-department="<?= $vacante['departamento_slug'] ?>" 
                         data-location="<?= $vacante['ubicacion_slug'] ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-<?= $vacante['tipo_color'] ?>"><?= $vacante['tipo'] ?></span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i><?= $vacante['fecha_publicacion'] ?>
                                        </small>
                                    </div>
                                    <h5 class="card-title"><?= $vacante['titulo'] ?></h5>
                                    <p class="card-text text-muted"><?= $vacante['descripcion'] ?></p>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span class="text-muted me-3"><?= $vacante['ubicacion'] ?></span>
                                        <i class="fas fa-clock text-success me-2"></i>
                                        <span class="text-muted me-3"><?= $vacante['tipo_contrato'] ?></span>
                                        <i class="fas fa-dollar-sign text-warning me-2"></i>
                                        <span class="text-muted"><?= $vacante['salario'] ?></span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($vacante['habilidades'] as $habilidad): ?>
                                        <span class="badge bg-light text-dark"><?= $habilidad ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <button class="btn btn-primary mb-2 w-100" onclick="applyJob(<?= $vacante['id'] ?>)">
                                        <i class="fas fa-paper-plane me-2"></i>Postular
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm w-100" onclick="viewJobDetails(<?= $vacante['id'] ?>)">
                                        <i class="fas fa-eye me-2"></i>Ver Detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <nav aria-label="Navegación de vacantes">
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
                <!-- Beneficios -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-gift me-2"></i>Beneficios</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Seguro de salud privado
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Plan de pensiones
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Horario flexible
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Capacitación continua
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Bonos por desempeño
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Ambiente de trabajo dinámico
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Departamentos -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-building me-2"></i>Departamentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($departamentos as $depto): ?>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                               onclick="filterByDepartment('<?= $depto['slug'] ?>')">
                                <span>
                                    <i class="fas fa-<?= $depto['icono'] ?> me-2"></i>
                                    <?= $depto['nombre'] ?>
                                </span>
                                <span class="badge bg-primary rounded-pill"><?= $depto['vacantes'] ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Ubicaciones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Ubicaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($ubicaciones as $ubicacion): ?>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                               onclick="filterByLocation('<?= $ubicacion['slug'] ?>')">
                                <span>
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <?= $ubicacion['nombre'] ?>
                                </span>
                                <span class="badge bg-warning rounded-pill"><?= $ubicacion['vacantes'] ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Contacto RRHH -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Contacto RRHH</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">María González</h6>
                        <p class="text-muted mb-3">Gerente de Recursos Humanos</p>
                        
                        <div class="d-grid gap-2">
                            <a href="tel:+56223456789" class="btn btn-primary">
                                <i class="fas fa-phone me-2"></i>+56 2 2345 6789
                            </a>
                            <a href="mailto:rrhh@propeasy.cl" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>rrhh@propeasy.cl
                            </a>
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
    <script src="assets/js/careers.js"></script>
</body>
</html> 