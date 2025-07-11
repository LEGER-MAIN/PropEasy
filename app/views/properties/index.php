<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
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
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-home me-2"></i>PropEasy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/properties">Propiedades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Agentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
                <div class="d-flex ms-auto align-items-center">
                    <?php if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']): ?>
                        <a href="/auth/logout" class="btn btn-outline-light">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-outline-light me-2">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                        <a href="/auth/register" class="btn btn-outline-light">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">Propiedades Disponibles</h1>
                    <p class="mb-0">Encuentra tu hogar ideal entre nuestras opciones</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-light text-primary fs-6">
                        <?= $pagination['total_properties'] ?> propiedades encontradas
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Sidebar con Filtros -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filtros
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="filterForm">
                            <!-- Tipo de Propiedad -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Propiedad</label>
                                <select class="form-select" name="tipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="casa" <?= $filtros['tipo'] === 'casa' ? 'selected' : '' ?>>Casa</option>
                                    <option value="apartamento" <?= $filtros['tipo'] === 'apartamento' ? 'selected' : '' ?>>Apartamento</option>
                                    <option value="terreno" <?= $filtros['tipo'] === 'terreno' ? 'selected' : '' ?>>Terreno</option>
                                    <option value="local" <?= $filtros['tipo'] === 'local' ? 'selected' : '' ?>>Local Comercial</option>
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ubicación</label>
                                <input type="text" class="form-control" name="ubicacion" 
                                       value="<?= htmlspecialchars($filtros['ubicacion']) ?>" 
                                       placeholder="Ciudad o sector">
                            </div>

                            <!-- Rango de Precio -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rango de Precio</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="precio_min" 
                                               value="<?= $filtros['precio_min'] ?>" placeholder="Mínimo">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="precio_max" 
                                               value="<?= $filtros['precio_max'] ?>" placeholder="Máximo">
                                    </div>
                                </div>
                            </div>

                            <!-- Habitaciones -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Habitaciones</label>
                                <select class="form-select" name="habitaciones">
                                    <option value="">Cualquier cantidad</option>
                                    <option value="1" <?= $filtros['habitaciones'] === '1' ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= $filtros['habitaciones'] === '2' ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= $filtros['habitaciones'] === '3' ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= $filtros['habitaciones'] === '4' ? 'selected' : '' ?>>4+</option>
                                </select>
                            </div>

                            <!-- Baños -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Baños</label>
                                <select class="form-select" name="baños">
                                    <option value="">Cualquier cantidad</option>
                                    <option value="1" <?= $filtros['baños'] === '1' ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= $filtros['baños'] === '2' ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= $filtros['baños'] === '3' ? 'selected' : '' ?>>3+</option>
                                </select>
                            </div>

                            <!-- Filtro de Favoritos -->
                            <?php if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']): ?>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="solo_favoritos" id="soloFavoritos" 
                                           value="1" <?= isset($filtros['solo_favoritos']) && $filtros['solo_favoritos'] ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold" for="soloFavoritos">
                                        <i class="fas fa-heart text-danger me-2"></i>Solo mis favoritos
                                    </label>
                                </div>
                                <small class="text-muted">Mostrar únicamente propiedades que has guardado como favoritas</small>
                            </div>
                            <?php endif; ?>

                            <!-- Ordenar por -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ordenar por</label>
                                <select class="form-select" name="orden">
                                    <option value="reciente" <?= $filtros['orden'] === 'reciente' ? 'selected' : '' ?>>Más recientes</option>
                                    <option value="precio_asc" <?= $filtros['orden'] === 'precio_asc' ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
                                    <option value="precio_desc" <?= $filtros['orden'] === 'precio_desc' ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
                                    <option value="area" <?= $filtros['orden'] === 'area' ? 'selected' : '' ?>>Área</option>
                                </select>
                            </div>

                            <!-- Botones -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Aplicar Filtros
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                                    <i class="fas fa-times me-2"></i>Limpiar Filtros
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="col-lg-9">
                <!-- Controles de Vista -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <span class="me-3">Vista:</span>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="viewMode" id="gridView" value="grid" checked>
                            <label class="btn btn-outline-primary" for="gridView">
                                <i class="fas fa-th"></i>
                            </label>
                            <input type="radio" class="btn-check" name="viewMode" id="listView" value="list">
                            <label class="btn btn-outline-primary" for="listView">
                                <i class="fas fa-list"></i>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="me-2">Mostrar:</span>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option value="12">12 por página</option>
                            <option value="24">24 por página</option>
                            <option value="48">48 por página</option>
                        </select>
                    </div>
                </div>

                <!-- Loading -->
                <div id="loading" class="text-center py-5" style="display: none;">
                    <div class="loading"></div>
                    <p class="mt-3">Cargando propiedades...</p>
                </div>

                <!-- Listado de Propiedades -->
                <div class="text-end mb-3">
                    <?php if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']): ?>
                        <a href="/properties/publish" class="btn btn-success">
                            <i class="fas fa-plus-circle me-1"></i> Publicar Propiedad
                        </a>
                    <?php endif; ?>
                </div>

                <div id="propertiesContainer" class="row">
                    <?php if (empty($propiedades)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>No se encontraron propiedades</h4>
                            <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                        </div>
                    <?php else: ?>
                        <?php 
                        // Usar el mismo archivo de tarjetas que el método filter()
                        $properties = $propiedades; // Alias para compatibilidad
                        include __DIR__ . '/_property_cards.php';
                        ?>
                    <?php endif; ?>
                </div>

                <!-- Paginación -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            <nav aria-label="Navegación de páginas">
                                <ul class="pagination">
                                    <!-- Botón anterior -->
                                    <?php if ($pagination['has_previous']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1])) ?>">Anterior</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <!-- Números de página -->
                                    <?php 
                                    $startPage = max(1, $pagination['current_page'] - 2);
                                    $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                                    ?>
                                    
                                    <?php if ($startPage > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                                        </li>
                                        <?php if ($startPage > 2): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($endPage < $pagination['total_pages']): ?>
                                        <?php if ($endPage < $pagination['total_pages'] - 1): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        <?php endif; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['total_pages']])) ?>"><?= $pagination['total_pages'] ?></a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <!-- Botón siguiente -->
                                    <?php if ($pagination['has_next']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1])) ?>">Siguiente</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="row mt-2">
                        <div class="col-12 text-center text-muted">
                            <?php
                            $start = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                            $end = min($pagination['current_page'] * $pagination['per_page'], $pagination['total_properties']);
                            ?>
                            Mostrando <?= $start ?> - <?= $end ?> de <?= $pagination['total_properties'] ?> propiedades
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>PropEasy</h5>
                    <p>Tu plataforma inmobiliaria de confianza</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 PropEasy. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        window.isUserAuthenticated = <?php echo (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']) ? 'true' : 'false'; ?>;
        window.userFavorites = <?php echo json_encode($userFavorites); ?>;

    </script>
    <script src="/assets/js/properties.js"></script>

    <!-- Modal para pedir inicio de sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    Debes iniciar sesión para agregar propiedades a tus favoritos.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="/auth/login" class="btn btn-primary">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <style>
    .favorite-btn.active i.fas {
        color: #dc3545;
    }
    .favorite-btn i.far {
        color: #dc3545;
        background: transparent;
    }
    .favorite-btn {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }
        
        .hero-section .btn {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .search-section .col-md-2,
        .search-section .col-md-3 {
            margin-bottom: 1rem;
        }
    }
    </style>
</body>
</html> 