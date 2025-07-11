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
                        <a class="nav-link active" href="/propeasy/public/about">Nosotros</a>
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
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Nuestra Historia</h1>
                    <p class="lead mb-4">Más de 10 años ayudando a familias chilenas a encontrar su hogar ideal. Somos tu socio confiable en el mercado inmobiliario.</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">+2,500</h5>
                            <small>Familias Felices</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">4.8/5</h5>
                            <small>Calificación Promedio</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/img/about-hero.jpg" alt="Equipo PropEasy" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Misión, Visión y Valores -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Nuestra Identidad</h2>
                <p class="text-muted">Los pilares que guían nuestro trabajo diario</p>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-bullseye fa-2x"></i>
                        </div>
                        <h4 class="card-title">Misión</h4>
                        <p class="card-text text-muted">Facilitar el acceso a la vivienda de calidad para todas las familias chilenas, ofreciendo un servicio personalizado, transparente y profesional que genere confianza y satisfacción en cada transacción.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-eye fa-2x"></i>
                        </div>
                        <h4 class="card-title">Visión</h4>
                        <p class="card-text text-muted">Ser la empresa inmobiliaria líder en Chile, reconocida por la excelencia en el servicio, la innovación tecnológica y el compromiso con el desarrollo sostenible de las comunidades.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <h4 class="card-title">Valores</h4>
                        <p class="card-text text-muted">Integridad, transparencia, excelencia, innovación, compromiso social y trabajo en equipo. Estos valores nos guían en cada interacción con nuestros clientes y la comunidad.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historia de la Empresa -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h3 class="fw-bold mb-4">Nuestra Historia</h3>
                                <p class="text-muted mb-4">PropEasy nació en 2014 con una visión clara: revolucionar el mercado inmobiliario chileno a través de la tecnología y el servicio personalizado.</p>
                                
                                <div class="timeline">
                                    <div class="timeline-item mb-4">
                                        <div class="d-flex">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                                <i class="fas fa-rocket"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold">2014 - Fundación</h6>
                                                <p class="text-muted mb-0">Nacimos como una pequeña oficina en Las Condes con 3 agentes inmobiliarios.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item mb-4">
                                        <div class="d-flex">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold">2017 - Expansión</h6>
                                                <p class="text-muted mb-0">Abrimos 5 nuevas oficinas en Santiago y alcanzamos 50 agentes.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item mb-4">
                                        <div class="d-flex">
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                                <i class="fas fa-laptop"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold">2020 - Digitalización</h6>
                                                <p class="text-muted mb-0">Lanzamos nuestra plataforma digital y herramientas de realidad virtual.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="d-flex">
                                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold">2024 - Liderazgo</h6>
                                                <p class="text-muted mb-0">Somos líderes en el mercado con presencia en 8 regiones y 200+ agentes.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <img src="assets/img/company-history.jpg" alt="Historia de PropEasy" class="img-fluid rounded shadow">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Nuestros Logros</h2>
                <p class="text-muted">Cifras que respaldan nuestro compromiso</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-primary counter" data-target="2500">0</h3>
                        <p class="text-muted mb-0">Propiedades Vendidas</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-success counter" data-target="200">0</h3>
                        <p class="text-muted mb-0">Agentes Expertos</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-info counter" data-target="98">0</h3>
                        <p class="text-muted mb-0">% Satisfacción</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-warning counter" data-target="8">0</h3>
                        <p class="text-muted mb-0">Regiones</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipo Directivo -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Nuestro Equipo Directivo</h2>
                <p class="text-muted">Conoce a los líderes que guían nuestra empresa</p>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <img src="assets/img/team/ceo.jpg" alt="CEO" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="card-title fw-bold">María González</h5>
                        <p class="text-primary mb-2">CEO & Fundadora</p>
                        <p class="text-muted small">Más de 15 años de experiencia en el sector inmobiliario. Fundó PropEasy con la visión de democratizar el acceso a la vivienda.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <img src="assets/img/team/cto.jpg" alt="CTO" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="card-title fw-bold">Carlos Rodríguez</h5>
                        <p class="text-primary mb-2">CTO & Co-Fundador</p>
                        <p class="text-muted small">Experto en tecnología con más de 12 años desarrollando soluciones digitales para el sector inmobiliario.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <img src="assets/img/team/cfo.jpg" alt="CFO" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="card-title fw-bold">Ana Silva</h5>
                        <p class="text-primary mb-2">CFO & Directora Financiera</p>
                        <p class="text-muted small">Contadora auditora con especialización en finanzas corporativas y más de 10 años en el sector inmobiliario.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valores Corporativos -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Nuestros Valores</h2>
                <p class="text-muted">Los principios que guían nuestro trabajo diario</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <h5 class="card-title">Integridad</h5>
                        <p class="card-text text-muted">Actuamos con honestidad y transparencia en todas nuestras transacciones y relaciones comerciales.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-lightbulb fa-2x"></i>
                        </div>
                        <h5 class="card-title">Innovación</h5>
                        <p class="card-text text-muted">Buscamos constantemente nuevas formas de mejorar nuestros servicios y la experiencia del cliente.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h5 class="card-title">Trabajo en Equipo</h5>
                        <p class="card-text text-muted">Valoramos la colaboración y el trabajo conjunto para lograr los mejores resultados para nuestros clientes.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <h5 class="card-title">Compromiso Social</h5>
                        <p class="card-text text-muted">Contribuimos al desarrollo de las comunidades donde operamos y promovemos la sostenibilidad.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificaciones y Premios -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Certificaciones y Premios</h2>
                <p class="text-muted">Reconocimientos que respaldan nuestra calidad</p>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-certificate fa-2x"></i>
                        </div>
                        <h5 class="card-title">ISO 9001:2015</h5>
                        <p class="text-muted">Certificación de Gestión de Calidad que garantiza nuestros procesos y servicios.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                        <h5 class="card-title">Mejor Empresa Inmobiliaria 2023</h5>
                        <p class="text-muted">Reconocimiento otorgado por la Cámara Chilena de la Construcción.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-award fa-2x"></i>
                        </div>
                        <h5 class="card-title">Excelencia en Servicio al Cliente</h5>
                        <p class="text-muted">Premio otorgado por la Asociación de Consumidores de Chile.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonios -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Lo que dicen nuestros clientes</h2>
                <p class="text-muted">Testimonios de familias que confiaron en nosotros</p>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Familia Martínez</h6>
                                <small class="text-muted">Las Condes, Santiago</small>
                            </div>
                        </div>
                        <p class="text-muted">"PropEasy nos ayudó a encontrar nuestra casa ideal en solo 2 semanas. Su equipo fue muy profesional y nos guió en todo el proceso."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Juan Pérez</h6>
                                <small class="text-muted">Providencia, Santiago</small>
                            </div>
                        </div>
                        <p class="text-muted">"Excelente servicio desde el primer contacto. Vendieron mi departamento en tiempo récord y con un precio superior al esperado."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">María González</h6>
                                <small class="text-muted">Ñuñoa, Santiago</small>
                            </div>
                        </div>
                        <p class="text-muted">"Como inversora, PropEasy me ha ayudado a construir un portafolio inmobiliario sólido. Su asesoría financiera es invaluable."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white border-0">
                    <div class="card-body p-5 text-center">
                        <h3 class="fw-bold mb-3">¿Listo para encontrar tu hogar ideal?</h3>
                        <p class="lead mb-4">Nuestro equipo de expertos está listo para ayudarte a encontrar la propiedad perfecta.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/propeasy/public/properties" class="btn btn-light btn-lg">
                                <i class="fas fa-search me-2"></i>Ver Propiedades
                            </a>
                            <a href="/propeasy/public/contact" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-phone me-2"></i>Contactarnos
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
    <script src="assets/js/about.js"></script>
</body>
</html> 