<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
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
                    <h1 class="mb-2">Resultados de Búsqueda</h1>
                    <p class="mb-0">
                        <?php if (!empty($searchParams['ubicacion'])): ?>
                            Búsqueda: "<?= htmlspecialchars($searchParams['ubicacion']) ?>"
                        <?php endif; ?>
                        <?php if (!empty($searchParams['tipo'])): ?>
                            - Tipo: <?= ucfirst($searchParams['tipo']) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-light text-primary fs-6">
                        <?= $totalResults ?> propiedades encontradas
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row">
            <!-- Sidebar con Filtros de Búsqueda -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i>Refinar Búsqueda
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="searchForm" method="GET" action="/properties/search">
                            <!-- Tipo de Propiedad -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Propiedad</label>
                                <select class="form-select" name="tipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="casa" <?= $searchParams['tipo'] === 'casa' ? 'selected' : '' ?>>Casa</option>
                                    <option value="apartamento" <?= $searchParams['tipo'] === 'apartamento' ? 'selected' : '' ?>>Apartamento</option>
                                    <option value="terreno" <?= $searchParams['tipo'] === 'terreno' ? 'selected' : '' ?>>Terreno</option>
                                    <option value="local" <?= $searchParams['tipo'] === 'local' ? 'selected' : '' ?>>Local Comercial</option>
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ubicación</label>
                                <input type="text" class="form-control" name="ubicacion" 
                                       value="<?= htmlspecialchars($searchParams['ubicacion']) ?>" 
                                       placeholder="Ciudad o sector">
                            </div>

                            <!-- Rango de Precio -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rango de Precio</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="precio_min" 
                                               value="<?= $searchParams['precio_min'] ?>" placeholder="Mínimo">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="precio_max" 
                                               value="<?= $searchParams['precio_max'] ?>" placeholder="Máximo">
                                    </div>
                                </div>
                            </div>

                            <!-- Habitaciones -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Habitaciones</label>
                                <select class="form-select" name="habitaciones">
                                    <option value="">Cualquier cantidad</option>
                                    <option value="1" <?= $searchParams['habitaciones'] === '1' ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= $searchParams['habitaciones'] === '2' ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= $searchParams['habitaciones'] === '3' ? 'selected' : '' ?>>3</option>
                                    <option value="4" <?= $searchParams['habitaciones'] === '4' ? 'selected' : '' ?>>4+</option>
                                </select>
                            </div>

                            <!-- Baños -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Baños</label>
                                <select class="form-select" name="banos">
                                    <option value="">Cualquier cantidad</option>
                                    <option value="1" <?= $searchParams['banos'] === '1' ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= $searchParams['banos'] === '2' ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= $searchParams['banos'] === '3' ? 'selected' : '' ?>>3+</option>
                                </select>
                            </div>

                            <!-- Botones -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Buscar Nuevamente
                                </button>
                                <a href="/properties" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Ver Todas las Propiedades
                                </a>
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
                        <span class="me-2">Resultados: <?= $totalResults ?></span>
                    </div>
                </div>

                <!-- Resultados de Búsqueda -->
                <div id="propertiesContainer" class="row">
                    <?php if (empty($propiedades)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>No se encontraron propiedades</h4>
                            <p class="text-muted">Intenta ajustar tus criterios de búsqueda</p>
                            <a href="/properties" class="btn btn-primary">Ver Todas las Propiedades</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($propiedades as $propiedad): ?>
                            <div class="col-lg-4 col-md-6 mb-4 property-item" data-prop-id="<?= $propiedad['id'] ?>">
                                <div class="card property-card h-100 position-relative">
                                    <!-- Imagen -->
                                    <div class="position-relative">
                                        <img src="<?= $propiedad['imagen_principal'] ?>" 
                                             class="card-img-top" 
                                             alt="<?= htmlspecialchars($propiedad['titulo']) ?>" 
                                             onerror="this.src='/assets/images/placeholder.jpg'">
                                    </div>
                                    <!-- Etiquetas y favoritos -->
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                                        <div>
                                            <span class="badge bg-primary me-1"><?= ucfirst($propiedad['tipo']) ?></span>
                                            <span class="badge bg-success"><?= ucfirst($propiedad['estado']) ?></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php $isFav = in_array($propiedad['id'], $userFavorites); ?>
                                            <span class="small text-muted" id="favoriteCount-<?= $propiedad['id'] ?>">
                                                <?= $propiedad['favorites_count'] ?? 0 ?>
                                            </span>
                                            <button class="favorite-btn p-0 border-0 bg-transparent<?= $isFav ? ' active' : '' ?>" 
                                                    data-id="<?= $propiedad['id'] ?>">
                                                <i class="fa-heart <?= $isFav ? 'fas text-danger' : 'far text-muted' ?>" 
                                                   id="favoriteIcon-<?= $propiedad['id'] ?>" 
                                                   style="font-size: 1.5rem;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($propiedad['titulo']) ?></h5>
                                        <p class="property-price mb-2"><?= $propiedad['precio_formateado'] ?></p>
                                        <p class="property-location mb-3">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?= htmlspecialchars($propiedad['ubicacion'] ?? $propiedad['ciudad']) ?>
                                        </p>
                                        <div class="row text-center mb-3 align-items-end" style="min-height: 70px;">
                                            <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                                                <div>
                                                    <i class="fas fa-bed fa-lg mb-1"></i>
                                                    <span class="fw-bold ms-1"><?= $propiedad['habitaciones'] ?></span>
                                                </div>
                                                <small class="text-muted">Habitaciones</small>
                                            </div>
                                            <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                                                <div>
                                                    <i class="fas fa-bath fa-lg mb-1"></i>
                                                    <span class="fw-bold ms-1"><?= $propiedad['banos'] ?? 'N/A' ?></span>
                                                </div>
                                                <small class="text-muted">Baños</small>
                                            </div>
                                            <div class="col-4 d-flex flex-column justify-content-end align-items-center">
                                                <div>
                                                    <i class="fas fa-ruler-combined fa-lg mb-1"></i>
                                                    <span class="fw-bold ms-1"><?= $propiedad['area'] ?>m²</span>
                                                </div>
                                                <small class="text-muted">Área</small>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <a href="/properties/detail/<?= $propiedad['id'] ?>" class="btn btn-outline-primary">
                                                Ver Detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Paginación (para implementar en el futuro) -->
                <?php if (!empty($propiedades) && count($propiedades) > 0): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Navegación de páginas">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Anterior</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para favoritos -->
    <script>
        // Variables globales
        window.userFavorites = <?php echo json_encode($userFavorites); ?>;
        window.isUserAuthenticated = <?php echo (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']) ? 'true' : 'false'; ?>;

        // Configurar botones de favoritos
        function setupFavoriteButtons() {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');
            
            favoriteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (!window.isUserAuthenticated) {
                        alert('Debes iniciar sesión para guardar favoritos');
                        window.location.href = '/auth/login';
                        return;
                    }
                    
                    const propertyId = this.dataset.id;
                    const icon = this.querySelector('i');
                    const countSpan = document.getElementById(`favoriteCount-${propertyId}`);
                    
                    // Deshabilitar botón temporalmente
                    this.disabled = true;
                    
                    // Hacer petición AJAX
                    fetch('/properties/favorite', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `property_id=${propertyId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Actualizar UI
                            if (data.is_favorite) {
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                this.classList.add('active');
                            } else {
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                this.classList.remove('active');
                            }
                            
                            // Actualizar contador
                            if (countSpan) {
                                countSpan.textContent = data.favorites_count;
                            }
                        } else {
                            alert(data.message || 'Error al procesar favorito');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error de conexión');
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
                });
            });
        }

        // Inicializar cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            setupFavoriteButtons();
        });
    </script>
</body>
</html> 