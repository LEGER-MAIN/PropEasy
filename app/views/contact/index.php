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
                        <a class="nav-link active" href="/propeasy/public/contact">Contacto</a>
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
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Contáctanos</h1>
                    <p class="lead mb-4">Estamos aquí para ayudarte a encontrar la propiedad perfecta. Nuestro equipo de expertos está listo para responder todas tus preguntas.</p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">+56 2 2345 6789</h5>
                            <small>Lun-Vie 9:00-18:00</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">info@propeasy.cl</h5>
                            <small>Respuesta en 24 horas</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/img/contact-hero.jpg" alt="Equipo PropEasy" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Información de Contacto -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">¿Cómo podemos ayudarte?</h2>
                <p class="text-muted">Elige la forma más conveniente para contactarnos</p>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-phone fa-2x"></i>
                        </div>
                        <h5 class="card-title">Llámanos</h5>
                        <p class="card-text text-muted">Habla directamente con nuestros agentes especializados</p>
                        <div class="mt-3">
                            <h6 class="text-primary">+56 2 2345 6789</h6>
                            <small class="text-muted">Lun-Vie 9:00-18:00</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </div>
                        <h5 class="card-title">WhatsApp</h5>
                        <p class="card-text text-muted">Envíanos un mensaje y te responderemos al instante</p>
                        <div class="mt-3">
                            <h6 class="text-success">+56 9 1234 5678</h6>
                            <small class="text-muted">24/7 disponible</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                        <h5 class="card-title">Email</h5>
                        <p class="card-text text-muted">Escribe un mensaje detallado y te responderemos pronto</p>
                        <div class="mt-3">
                            <h6 class="text-info">info@propeasy.cl</h6>
                            <small class="text-muted">Respuesta en 24h</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Contacto -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Envíanos un Mensaje</h4>
                        <p class="text-muted mb-0">Completa el formulario y nos pondremos en contacto contigo</p>
                    </div>
                    <div class="card-body p-4">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="firstName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="lastName" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Asunto *</label>
                                <select class="form-select" id="subject" required>
                                    <option value="">Seleccionar asunto</option>
                                    <option value="consulta-propiedad">Consulta sobre Propiedad</option>
                                    <option value="agendar-visita">Agendar Visita</option>
                                    <option value="vender-propiedad">Vender mi Propiedad</option>
                                    <option value="invertir">Inversión Inmobiliaria</option>
                                    <option value="trabajar">Trabajar con Nosotros</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Mensaje *</label>
                                <textarea class="form-control" id="message" rows="5" required 
                                          placeholder="Cuéntanos en qué podemos ayudarte..."></textarea>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" checked>
                                    <label class="form-check-label" for="newsletter">
                                        Recibir notificaciones de nuevas propiedades y ofertas especiales
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        Acepto la <a href="/propeasy/public/privacy" class="text-decoration-none">política de privacidad</a> *
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información de la Empresa -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Información de la Empresa</h5>
                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Dirección</h6>
                            <p class="text-muted mb-0">Av. Apoquindo 1234<br>Las Condes, Santiago<br>Chile</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="fas fa-clock text-primary me-2"></i>Horarios</h6>
                            <p class="text-muted mb-0">
                                Lunes - Viernes: 9:00 - 18:00<br>
                                Sábado: 9:00 - 14:00<br>
                                Domingo: Cerrado
                            </p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="fas fa-phone text-primary me-2"></i>Teléfonos</h6>
                            <p class="text-muted mb-0">
                                Oficina: +56 2 2345 6789<br>
                                WhatsApp: +56 9 1234 5678<br>
                                Emergencias: +56 9 8765 4321
                            </p>
                        </div>
                        <div>
                            <h6><i class="fas fa-envelope text-primary me-2"></i>Emails</h6>
                            <p class="text-muted mb-0">
                                General: info@propeasy.cl<br>
                                Ventas: ventas@propeasy.cl<br>
                                Soporte: soporte@propeasy.cl
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Síguenos</h5>
                        <p class="text-muted mb-3">Mantente actualizado con nuestras últimas propiedades y noticias</p>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary" onclick="shareOnFacebook()">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info" onclick="shareOnTwitter()">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success" onclick="shareOnInstagram()">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger" onclick="shareOnYouTube()">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="btn btn-outline-dark" onclick="shareOnLinkedIn()">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa y Ubicación -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Nuestra Ubicación</h4>
                        <p class="text-muted mb-0">Visítanos en nuestra oficina principal</p>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px; background-color: #f8f9fa;">
                            <!-- Mapa será cargado dinámicamente -->
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Mapa de Ubicación</h5>
                                    <p class="text-muted">Av. Apoquindo 1234, Las Condes, Santiago</p>
                                    <button class="btn btn-primary" onclick="openInMaps()">
                                        <i class="fas fa-directions me-2"></i>Abrir en Google Maps
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preguntas Frecuentes -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Preguntas Frecuentes</h4>
                        <p class="text-muted mb-0">Resolvemos tus dudas más comunes</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        ¿Cómo puedo agendar una visita a una propiedad?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Puedes agendar una visita de varias formas: llamando a nuestra oficina, enviando un WhatsApp, 
                                        completando el formulario de contacto o directamente desde la página de la propiedad. 
                                        Nuestro equipo te contactará para confirmar la cita.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        ¿Qué documentos necesito para comprar una propiedad?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Los documentos básicos incluyen: cédula de identidad, certificado de antecedentes, 
                                        certificado de avalúo fiscal, certificado de dominio vigente, y documentación financiera 
                                        si requieres un crédito hipotecario.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        ¿Cuánto tiempo toma el proceso de compra?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        El tiempo promedio es de 30 a 60 días, dependiendo de si requieres financiamiento 
                                        y la complejidad de la transacción. Nuestro equipo te guiará en cada paso del proceso.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        ¿Ofrecen servicios de tasación?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Sí, contamos con tasadores certificados que pueden realizar avalúos comerciales 
                                        y técnicos para propiedades residenciales y comerciales. Contáctanos para más información.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                        ¿Trabajan con todas las comunas de Santiago?
                                    </button>
                                </h2>
                                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Sí, tenemos cobertura en toda la Región Metropolitana y también en otras regiones del país. 
                                        Nuestro equipo de agentes está especializado en diferentes zonas y tipos de propiedades.
                                    </div>
                                </div>
                            </div>
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
                        <li><a href="#" class="text-muted text-decoration-none">Noticias</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Contacto</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Privacidad</a></li>
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
    <script src="assets/js/contact.js"></script>
</body>
</html> 