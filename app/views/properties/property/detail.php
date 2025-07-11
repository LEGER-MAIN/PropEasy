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
    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
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

    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <i class="fas fa-home me-2"></i>PropEasy
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="/properties">Propiedades</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($property['title']) ?></li>
            </ol>
        </nav>

        <!-- Información Principal -->
        <div class="row">
            <!-- Galería de Imágenes -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                            <?php if (isset($property['images']) && is_array($property['images']) && count($property['images']) > 1): ?>
                            <div class="carousel-indicators">
                                <?php for ($i = 0; $i < count($property['images']); $i++): ?>
                                <button type="button" data-bs-target="#propertyCarousel" data-bs-slide-to="<?= $i ?>" 
                                        class="<?= $i === 0 ? 'active' : '' ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>" 
                                        aria-label="Slide <?= $i + 1 ?>"></button>
                                <?php endfor; ?>
                            </div>
                            <?php endif; ?>
                            <div class="carousel-inner">
                                <?php if (isset($property['images']) && is_array($property['images'])): ?>
                                    <?php foreach ($property['images'] as $index => $image): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <a href="<?= $image ?>" data-lightbox="property-gallery">
                                            <img src="<?= $image ?>" class="d-block w-100" alt="Imagen <?= $index + 1 ?>" 
                                                 style="height: 500px; object-fit: cover;"
                                                 onerror="this.src='assets/img/property-default.svg'">
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <img src="assets/img/property-default.svg" class="d-block w-100" alt="Imagen por defecto" 
                                             style="height: 500px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($property['images']) && is_array($property['images']) && count($property['images']) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Botón de favorito debajo de la imagen -->
                <div class="d-flex justify-content-end align-items-center gap-2 mt-2 mb-2 me-3">
                    <button class="favorite-btn p-0 border-0 bg-transparent<?= (isset($isFavorite) && $isFavorite) ? ' active' : '' ?>"
                            data-id="<?= $property['id'] ?>"
                            style="z-index:2;">
                        <i class="fa-heart <?= (isset($isFavorite) && $isFavorite) ? 'fas text-danger' : 'far' ?>" id="favoriteIcon-<?= $property['id'] ?>" style="font-size: 2.2rem;"></i>
                    </button>
                    <span class="small text-muted" id="favoriteCount-<?= $property['id'] ?>"><?= isset($property['favorites_count']) ? $property['favorites_count'] : 0 ?></span>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="col-lg-4 mb-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h3 class="card-title text-primary mb-3">$<?= number_format($property['price'] ?? 0) ?></h3>
                        
                        <!-- Información del Agente -->
                        <?php if (isset($property['agent']) && is_array($property['agent'])): ?>
                        <div class="d-flex align-items-center mb-4">
                            <img src="<?= $property['agent']['photo'] ?? 'assets/img/agent-default.jpg' ?>" 
                                 class="rounded-circle me-3" 
                                 width="60" height="60"
                                 onerror="this.src='assets/img/agent-default.jpg'">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($property['agent']['name'] ?? 'Agente') ?></h6>
                                <p class="text-muted mb-1"><?= htmlspecialchars($property['agent']['title'] ?? 'Agente Inmobiliario') ?></p>
                                <div class="text-warning">
                                    <?php $rating = $property['agent']['rating'] ?? 4; ?>
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <i class="fas fa-star<?= $i < $rating ? '' : '-o' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="ms-1 text-muted">(<?= $property['agent']['reviews'] ?? 0 ?> reseñas)</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2 mb-4">
                            <button class="btn btn-primary btn-lg" onclick="showContactModal()">
                                <i class="fas fa-phone me-2"></i>Contactar Agente
                            </button>
                            <button class="btn btn-outline-primary" onclick="showAppointmentModal()">
                                <i class="fas fa-calendar me-2"></i>Agendar Visita
                            </button>
                            <?php if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client'): ?>
                            <a href="/solicitud-compra?propiedad_id=<?= $property['id'] ?>" class="btn btn-success">
                                <i class="fas fa-file-alt me-2"></i>Solicitar Compra
                            </a>
                            <?php elseif (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']): ?>
                            <a href="/auth/login" class="btn btn-success">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión para Solicitar
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Información de Contacto -->
                        <?php if (isset($property['agent']) && is_array($property['agent'])): ?>
                        <div class="border-top pt-3">
                            <h6 class="mb-3">Información de Contacto</h6>
                            <div class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <a href="tel:<?= $property['agent']['phone'] ?? '' ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($property['agent']['phone'] ?? 'No disponible') ?>
                                </a>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <a href="mailto:<?= $property['agent']['email'] ?? '' ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($property['agent']['email'] ?? 'No disponible') ?>
                                </a>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                Disponible: <?= htmlspecialchars($property['agent']['availability'] ?? 'No especificado') ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Compartir -->
                        <div class="border-top pt-3">
                            <h6 class="mb-3">Compartir</h6>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="shareOnFacebook()">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="shareOnTwitter()">
                                    <i class="fab fa-twitter"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="shareOnWhatsApp()">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="copyLink()">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Detallada -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Características Principales -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Características Principales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-bed text-primary me-2"></i>
                                        <strong>Dormitorios:</strong> <?= $property['bedrooms'] ?? 0 ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-bath text-primary me-2"></i>
                                        <strong>Baños:</strong> <?= $property['bathrooms'] ?? 0 ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-ruler-combined text-primary me-2"></i>
                                        <strong>Superficie:</strong> <?= $property['area'] ?? 0 ?> m²
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-car text-primary me-2"></i>
                                        <strong>Estacionamientos:</strong> <?= $property['parqueos'] ?? 0 ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        <strong>Tipo:</strong> <?= htmlspecialchars($property['type'] ?? 'No especificado') ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <strong>Comuna:</strong> <?= htmlspecialchars($property['commune'] ?? 'No especificado') ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <strong>Año:</strong> <?= htmlspecialchars($property['year_built'] ?? 'No especificado') ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-home text-primary me-2"></i>
                                        <strong>Estado:</strong> <?= htmlspecialchars($property['condition'] ?? 'No especificado') ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <?php if (isset($property['superficie_construida']) && $property['superficie_construida'] > 0): ?>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Superficie Construida:</strong> <?= $property['superficie_construida'] ?> m²
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Descripción</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3"><?= nl2br(htmlspecialchars($property['description'] ?? 'Descripción no disponible')) ?></p>
                        
                        <?php if (isset($property['caracteristicas']) && is_array($property['caracteristicas']) && !empty($property['caracteristicas'])): ?>
                        <h6 class="mb-3">Características Destacadas:</h6>
                        <div class="row">
                            <?php foreach ($property['caracteristicas'] as $caracteristica): ?>
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <?= htmlspecialchars($caracteristica) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Multimedia 
                <?php if (isset($property['video_url']) && !empty($property['video_url'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Video Tour</h5>
                    </div>
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= htmlspecialchars($property['video_url']) ?>" 
                                    title="Video Tour de la Propiedad" 
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Planos 
                <?php if (isset($property['plano_url']) && !empty($property['plano_url'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Planos</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?= htmlspecialchars($property['plano_url']) ?>" 
                             class="img-fluid" 
                             alt="Planos de la propiedad"
                             style="max-height: 500px;">
                    </div>
                </div> -->
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Ubicación -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Ubicación</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3" data-property-address="<?= htmlspecialchars($property['direccion'] ?? '') ?>">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <strong>Dirección:</strong><br>
                            <?= htmlspecialchars($property['direccion'] ?? 'No especificada') ?>
                        </p>
                        
                        <!-- Datos ocultos para JavaScript -->
                        <div style="display: none;" 
                             data-property-lat="<?= $property['latitud'] ?? '0' ?>"
                             data-property-lng="<?= $property['longitud'] ?? '0' ?>"
                             data-agent-phone="<?= $property['agent']['phone'] ?? '' ?>"
                             data-agent-whatsapp="<?= $property['agent']['whatsapp'] ?? '' ?>">
                        </div>
                        
                        <?php if (isset($property['latitud']) && isset($property['longitud']) && $property['latitud'] != 0 && $property['longitud'] != 0): ?>
                        <div id="map" style="height: 250px; background-color: #f8f9fa; border-radius: 8px; margin-bottom: 15px;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="fas fa-map-marked-alt fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">Mapa de ubicación</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-grid">
                            <button class="btn btn-outline-primary btn-sm" onclick="openDirections()">
                                <i class="fas fa-directions me-2"></i>Cómo llegar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto Rápido -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Contacto Rápido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="showContactModal()">
                                <i class="fas fa-envelope me-2"></i>Enviar Mensaje
                            </button>
                            <button class="btn btn-success" onclick="showWhatsAppContact()">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </button>
                            <button class="btn btn-info" onclick="showPhoneContact()">
                                <i class="fas fa-phone me-2"></i>Llamar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Acciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="showAppointmentModal()">
                                <i class="fas fa-calendar me-2"></i>Agendar Visita
                            </button>
                            <button class="btn btn-outline-secondary" onclick="shareProperty()">
                                <i class="fas fa-share-alt me-2"></i>Compartir
                            </button>
                            <button class="btn btn-outline-warning" onclick="reportProperty()">
                                <i class="fas fa-flag me-2"></i>Reportar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Contacto -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contactar Agente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="contactForm">
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="contactName" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="contactEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactPhone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="contactPhone">
                        </div>
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Mensaje *</label>
                            <textarea class="form-control" id="contactMessage" rows="4" required 
                                      placeholder="Hola, me interesa esta propiedad..."></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="contactNewsletter">
                                <label class="form-check-label" for="contactNewsletter">
                                    Recibir notificaciones de nuevas propiedades
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="sendContactForm()">Enviar Mensaje</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Agendar Visita -->
    <div class="modal fade" id="appointmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agendar Visita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        <div class="mb-3">
                            <label for="appointmentName" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" id="appointmentName" required>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="appointmentEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentPhone" class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="appointmentPhone" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointmentDate" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" id="appointmentDate" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointmentTime" class="form-label">Hora *</label>
                                    <select class="form-select" id="appointmentTime" required>
                                        <option value="">Seleccionar hora</option>
                                        <option value="09:00">09:00</option>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="12:00">12:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                        <option value="17:00">17:00</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentNotes" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control" id="appointmentNotes" rows="3" 
                                      placeholder="Información adicional sobre la visita..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="sendAppointmentForm()">Agendar Visita</button>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.isUserAuthenticated = <?php echo (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']) ? 'true' : 'false'; ?>;
    </script>
    <script src="/assets/js/properties.js"></script>
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/property-detail.js"></script>
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
    </style>
</body>
</html> 