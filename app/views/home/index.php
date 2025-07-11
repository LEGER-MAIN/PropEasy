<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Inyectar favoritos y sesión en JS
$userFavorites = [];
$isUserAuthenticated = false;
if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] && isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../models/FavoriteModel.php';
    require_once __DIR__ . '/../../models/PropertyModel.php';
    $favoriteModel = new FavoriteModel();
    $propertyModel = new PropertyModel();
    // Obtener los IDs de las propiedades más favoritas que el usuario tiene en favoritos
    $props = $propertyModel->getMostFavoritedProperties(100, 0); // 100 por si acaso
    foreach ($props as $p) {
        if ($favoriteModel->isFavorite($_SESSION['user_id'], $p['id'])) {
            $userFavorites[] = $p['id'];
        }
    }
    $isUserAuthenticated = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PropEasy - Tu Plataforma Inmobiliaria</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
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
                        <a class="nav-link active" href="/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/properties">Propiedades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/agent">Agentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contacto</a>
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

    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Encuentra tu hogar ideal</h1>
                    <p class="lead mb-4">La plataforma inmobiliaria más confiable para comprar, vender y gestionar propiedades con total seguridad y transparencia.</p>
                    <div class="d-flex gap-3">
                        <a href="/properties" class="btn btn-light btn-lg">
                            <i class="fas fa-search me-2"></i>Buscar Propiedades
                        </a>
                        <a href="/properties/sell" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Vender Propiedad
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/hero-image.jpg" alt="Propiedades" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">Buscar Propiedades</h3>
                            <form id="searchForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Tipo de Propiedad</label>
                                        <select class="form-select" name="tipo">
                                            <option value="">Todos los tipos</option>
                                            <option value="casa">Casa</option>
                                            <option value="apartamento">Apartamento</option>
                                            <option value="terreno">Terreno</option>
                                            <option value="local">Local Comercial</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Ubicación</label>
                                        <input type="text" class="form-control" name="ubicacion" placeholder="Ciudad o sector">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Precio Mínimo</label>
                                        <input type="number" class="form-control" name="precio_min" placeholder="$">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Precio Máximo</label>
                                        <input type="number" class="form-control" name="precio_max" placeholder="$">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="featured-properties py-5">
        <div class="container">
            <h2 class="text-center mb-5">Propiedades Destacadas</h2>
            <div class="row" id="featuredProperties">
                <!-- Las propiedades se cargarán dinámicamente -->
            </div>
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="btn btn-outline-primary">Cargar más</button>
                <button id="morePropertiesBtn" class="btn btn-primary mt-3" style="display:none;">Más propiedades</button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">¿Por qué elegir PropEasy?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h4>Seguridad Garantizada</h4>
                        <p>Todas las propiedades son validadas por agentes certificados con tokens de seguridad únicos.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-comments fa-3x text-primary mb-3"></i>
                        <h4>Comunicación Directa</h4>
                        <p>Chat integrado para comunicación directa entre clientes y agentes inmobiliarios.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                        <h4>Agenda Integrada</h4>
                        <p>Sistema de citas automatizado con recordatorios para visitas a propiedades.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
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
    <script src="assets/js/main.js"></script>
    <script>
    let featuredOffset = 0;
    const featuredLimit = 6;
    let allLoaded = false;

    window.userFavorites = <?php echo json_encode($userFavorites); ?>;
    window.isUserAuthenticated = <?php echo $isUserAuthenticated ? 'true' : 'false'; ?>;

    function renderPropertyCard(prop) {
        const isFav = window.userFavorites && window.userFavorites.includes(parseInt(prop.id));
        return `
        <div class="col-lg-4 col-md-6 mb-4 property-item" data-prop-id="${prop.id}">
            <div class="card property-card h-100 position-relative">
                <div class="position-relative">
                    <img src="${prop.imagen_principal}" class="card-img-top" alt="${prop.titulo || prop.title}" onerror="this.src='assets/images/placeholder.jpg'">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary">${prop.tipo || ''}</span>
                    </div>
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-success">${prop.estado || ''}</span>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-2 mt-2 mb-2 me-3">
                    <button class="favorite-btn p-0 border-0 bg-transparent${isFav ? ' active' : ''}" data-id="${prop.id}" style="z-index:2;">
                        <i class="fa-heart ${isFav ? 'fas text-danger' : 'far'}" id="favoriteIcon-${prop.id}" style="font-size: 2.2rem; color: #dc3545;"></i>
                    </button>
                    <span class="small text-muted" id="favoriteCount-${prop.id}">${prop.favorites_count || 0}</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">${prop.titulo || prop.title}</h5>
                    <p class="property-price mb-2">${prop.precio_formateado || ''}</p>
                    <p class="property-location mb-3">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        ${prop.ubicacion || prop.ciudad || ''}
                    </p>
                    <div class="row text-center mb-3 align-items-end" style="min-height: 70px;">
                        <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                            <div>
                                <i class="fas fa-bed fa-lg mb-1"></i>
                                <span class="fw-bold ms-1">${prop.habitaciones || prop.bedrooms || ''}</span>
                            </div>
                            <small class="text-muted">Habitaciones</small>
                        </div>
                        <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                            <div>
                                <i class="fas fa-bath fa-lg mb-1"></i>
                                <span class="fw-bold ms-1">${prop.banos || prop.bathrooms || 'N/A'}</span>
                            </div>
                            <small class="text-muted">Baños</small>
                        </div>
                        <div class="col-4 d-flex flex-column justify-content-end align-items-center">
                            <div>
                                <i class="fas fa-ruler-combined fa-lg mb-1"></i>
                                <span class="fw-bold ms-1">${prop.area || ''}m²</span>
                            </div>
                            <small class="text-muted">Área</small>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="/properties/detail/${prop.id}" class="btn btn-outline-primary">Ver Detalles</a>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    function loadFeaturedProperties() {
        if (allLoaded) return;
        document.getElementById('loadMoreBtn').disabled = true;
        fetch(`/home/apiMostFavoritedProperties?limit=${featuredLimit}&offset=${featuredOffset}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const container = document.getElementById('featuredProperties');
                    data.data.forEach(prop => {
                        // Evitar duplicados por ID
                        if (!container.querySelector(`[data-prop-id='${prop.id}']`)) {
                            container.insertAdjacentHTML('beforeend', renderPropertyCard(prop));
                        }
                    });
                    featuredOffset += data.data.length;
                    document.getElementById('loadMoreBtn').disabled = false;
                    if (data.data.length < featuredLimit) {
                        allLoaded = true;
                        document.getElementById('loadMoreBtn').style.display = 'none';
                        document.getElementById('morePropertiesBtn').style.display = 'inline-block';
                    }
                } else {
                    allLoaded = true;
                    document.getElementById('loadMoreBtn').style.display = 'none';
                    document.getElementById('morePropertiesBtn').style.display = 'inline-block';
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadFeaturedProperties();
        document.getElementById('loadMoreBtn').addEventListener('click', function(e) {
            e.preventDefault();
            loadFeaturedProperties();
        });
        document.getElementById('morePropertiesBtn').addEventListener('click', function() {
            window.location.href = '/properties';
        });

        // Delegación de eventos para favoritos
        document.getElementById('featuredProperties').addEventListener('click', function(e) {
            const btn = e.target.closest('.favorite-btn');
            if (!btn) return;
            e.preventDefault();
            var propertyId = btn.getAttribute('data-id');
            if (typeof window.isUserAuthenticated !== 'undefined' && !window.isUserAuthenticated) {
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                return;
            }
            var icon = btn.querySelector('i');
            var originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin';
            btn.disabled = true;
            
            fetch('/properties/favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'property_id=' + encodeURIComponent(propertyId)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    var counter = btn.nextElementSibling;
                    
                    if (data.is_favorite) {
                        btn.classList.add('active');
                        icon.className = 'fas fa-heart text-danger';
                    } else {
                        btn.classList.remove('active');
                        icon.className = 'far fa-heart';
                    }
                    
                    if (counter) {
                        counter.textContent = data.count;
                    }
                } else {
                    if (data.require_login) {
                    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                }
                    icon.className = originalClass;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                icon.className = originalClass;
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });
    </script>

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

    <script src="assets/js/properties.js"></script>
</body>
</html> 