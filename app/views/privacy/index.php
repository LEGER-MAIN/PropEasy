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
                    <h1 class="display-4 fw-bold mb-4">Política de Privacidad</h1>
                    <p class="lead mb-4">Tu privacidad es importante para nosotros. Conoce cómo protegemos y utilizamos tu información personal.</p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-alt fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Última actualización</h5>
                            <small>15 de Enero, 2024</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="assets/img/privacy-hero.jpg" alt="Privacidad y Seguridad" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Navegación de Contenido -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Contenido</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="#informacion-recolectada" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Información que Recolectamos
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#uso-informacion" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Cómo Usamos tu Información
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#compartir-informacion" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Compartir Información
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#seguridad-datos" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Seguridad de Datos
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="#derechos-usuario" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Tus Derechos
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#cookies" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Cookies y Tecnologías
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#menores-edad" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Menores de Edad
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#contacto" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Contacto
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Información que Recolectamos -->
                <div class="card border-0 shadow-sm mb-4" id="informacion-recolectada">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-database text-primary me-2"></i>
                            Información que Recolectamos
                        </h3>
                        <p class="text-muted mb-4">Recolectamos diferentes tipos de información para proporcionarte nuestros servicios:</p>
                        
                        <h5 class="fw-bold mb-3">Información Personal</h5>
                        <ul class="text-muted mb-4">
                            <li>Nombre completo y apellidos</li>
                            <li>Dirección de correo electrónico</li>
                            <li>Número de teléfono</li>
                            <li>Dirección física</li>
                            <li>Información de identificación (RUT)</li>
                            <li>Información financiera (cuando sea necesario)</li>
                        </ul>

                        <h5 class="fw-bold mb-3">Información de Uso</h5>
                        <ul class="text-muted mb-4">
                            <li>Páginas visitadas en nuestro sitio web</li>
                            <li>Tiempo de permanencia en cada página</li>
                            <li>Propiedades que has visto o marcado como favoritas</li>
                            <li>Búsquedas realizadas</li>
                            <li>Interacciones con nuestro contenido</li>
                        </ul>

                        <h5 class="fw-bold mb-3">Información Técnica</h5>
                        <ul class="text-muted">
                            <li>Dirección IP</li>
                            <li>Tipo de navegador y versión</li>
                            <li>Sistema operativo</li>
                            <li>Dispositivo utilizado</li>
                            <li>Información de cookies</li>
                        </ul>
                    </div>
                </div>

                <!-- Cómo Usamos tu Información -->
                <div class="card border-0 shadow-sm mb-4" id="uso-informacion">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-cogs text-primary me-2"></i>
                            Cómo Usamos tu Información
                        </h3>
                        <p class="text-muted mb-4">Utilizamos tu información para los siguientes propósitos:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Servicios Inmobiliarios</h6>
                                        <p class="text-muted small">Proporcionar información sobre propiedades, agendar visitas y facilitar transacciones.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Comunicación</h6>
                                        <p class="text-muted small">Enviar notificaciones, actualizaciones y responder consultas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Análisis y Mejoras</h6>
                                        <p class="text-muted small">Analizar el uso del sitio para mejorar nuestros servicios.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Seguridad</h6>
                                        <p class="text-muted small">Proteger contra fraudes y mantener la seguridad de nuestros usuarios.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compartir Información -->
                <div class="card border-0 shadow-sm mb-4" id="compartir-informacion">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-share-alt text-primary me-2"></i>
                            Compartir Información
                        </h3>
                        <p class="text-muted mb-4">No vendemos, alquilamos ni compartimos tu información personal con terceros, excepto en las siguientes circunstancias:</p>
                        
                        <div class="alert alert-info">
                            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Con tu Consentimiento</h6>
                            <p class="mb-0">Solo compartimos información cuando nos das tu consentimiento explícito.</p>
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Proveedores de Servicios</h6>
                            <p class="mb-0">Trabajamos con proveedores confiables que nos ayudan a operar nuestro sitio web y servicios.</p>
                        </div>

                        <div class="alert alert-danger">
                            <h6 class="fw-bold"><i class="fas fa-gavel me-2"></i>Requerimientos Legales</h6>
                            <p class="mb-0">Podemos divulgar información si es requerido por ley o para proteger nuestros derechos.</p>
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Proveedores de Servicios</h5>
                        <ul class="text-muted">
                            <li>Proveedores de hosting y servicios web</li>
                            <li>Servicios de análisis y marketing</li>
                            <li>Proveedores de servicios de pago</li>
                            <li>Servicios de comunicación por email</li>
                        </ul>
                    </div>
                </div>

                <!-- Seguridad de Datos -->
                <div class="card border-0 shadow-sm mb-4" id="seguridad-datos">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-lock text-primary me-2"></i>
                            Seguridad de Datos
                        </h3>
                        <p class="text-muted mb-4">Implementamos medidas de seguridad técnicas y organizacionales para proteger tu información:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-primary">
                                            <i class="fas fa-shield-alt me-2"></i>Encriptación
                                        </h6>
                                        <p class="text-muted small mb-0">Todos los datos sensibles se transmiten y almacenan de forma encriptada.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-success">
                                            <i class="fas fa-user-shield me-2"></i>Acceso Controlado
                                        </h6>
                                        <p class="text-muted small mb-0">Acceso limitado a personal autorizado con autenticación estricta.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-info">
                                            <i class="fas fa-sync-alt me-2"></i>Copias de Seguridad
                                        </h6>
                                        <p class="text-muted small mb-0">Copias de seguridad regulares y seguras de todos los datos.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-warning">
                                            <i class="fas fa-eye-slash me-2"></i>Monitoreo Continuo
                                        </h6>
                                        <p class="text-muted small mb-0">Monitoreo 24/7 para detectar y prevenir amenazas de seguridad.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success mt-3">
                            <h6 class="fw-bold"><i class="fas fa-check-circle me-2"></i>Certificaciones de Seguridad</h6>
                            <p class="mb-0">Nuestros sistemas cumplen con estándares internacionales de seguridad y están certificados por entidades reconocidas.</p>
                        </div>
                    </div>
                </div>

                <!-- Tus Derechos -->
                <div class="card border-0 shadow-sm mb-4" id="derechos-usuario">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-user-check text-primary me-2"></i>
                            Tus Derechos
                        </h3>
                        <p class="text-muted mb-4">Como usuario, tienes los siguientes derechos sobre tu información personal:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Derecho de Acceso</h6>
                                        <p class="text-muted small">Solicitar una copia de la información personal que tenemos sobre ti.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Derecho de Rectificación</h6>
                                        <p class="text-muted small">Corregir información personal que sea inexacta o incompleta.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Derecho de Eliminación</h6>
                                        <p class="text-muted small">Solicitar la eliminación de tu información personal en ciertas circunstancias.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-pause"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Derecho de Limitación</h6>
                                        <p class="text-muted small">Limitar el procesamiento de tu información personal.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Derecho de Portabilidad</h6>
                                        <p class="text-muted small">Recibir tu información personal en un formato estructurado.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Derecho de Oposición</h6>
                                        <p class="text-muted small">Oponerte al procesamiento de tu información personal.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-primary mt-3">
                            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Ejercer tus Derechos</h6>
                            <p class="mb-0">Para ejercer cualquiera de estos derechos, contáctanos a través de nuestro formulario de contacto o envía un email a <strong>privacy@propeasy.cl</strong></p>
                        </div>
                    </div>
                </div>

                <!-- Cookies y Tecnologías -->
                <div class="card border-0 shadow-sm mb-4" id="cookies">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-cookie-bite text-primary me-2"></i>
                            Cookies y Tecnologías Similares
                        </h3>
                        <p class="text-muted mb-4">Utilizamos cookies y tecnologías similares para mejorar tu experiencia en nuestro sitio web:</p>
                        
                        <h5 class="fw-bold mb-3">Tipos de Cookies que Utilizamos</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Propósito</th>
                                        <th>Duración</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Cookies Esenciales</strong></td>
                                        <td>Funcionamiento básico del sitio web</td>
                                        <td>Sesión</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cookies de Rendimiento</strong></td>
                                        <td>Analizar el uso del sitio y mejorar la experiencia</td>
                                        <td>2 años</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cookies de Funcionalidad</strong></td>
                                        <td>Recordar preferencias y configuraciones</td>
                                        <td>1 año</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cookies de Marketing</strong></td>
                                        <td>Mostrar publicidad relevante</td>
                                        <td>6 meses</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <h6 class="fw-bold"><i class="fas fa-cog me-2"></i>Gestionar Cookies</h6>
                            <p class="mb-0">Puedes gestionar tus preferencias de cookies a través de la configuración de tu navegador o utilizando nuestro panel de control de cookies.</p>
                        </div>
                    </div>
                </div>

                <!-- Menores de Edad -->
                <div class="card border-0 shadow-sm mb-4" id="menores-edad">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-child text-primary me-2"></i>
                            Menores de Edad
                        </h3>
                        <p class="text-muted mb-4">Nuestros servicios no están dirigidos a menores de 18 años. No recolectamos intencionalmente información personal de menores de edad.</p>
                        
                        <div class="alert alert-danger">
                            <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Política para Menores</h6>
                            <ul class="mb-0">
                                <li>No aceptamos registros de usuarios menores de 18 años</li>
                                <li>Si eres padre o tutor y crees que tu hijo nos ha proporcionado información, contáctanos inmediatamente</li>
                                <li>Eliminaremos cualquier información de menores de edad que hayamos recolectado sin saberlo</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="card border-0 shadow-sm mb-4" id="contacto">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            Contacto
                        </h3>
                        <p class="text-muted mb-4">Si tienes preguntas sobre esta política de privacidad o sobre cómo manejamos tu información personal, contáctanos:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold">Email de Privacidad</h6>
                                <p class="text-muted mb-0">privacy@propeasy.cl</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold">Teléfono</h6>
                                <p class="text-muted mb-0">+56 2 2345 6789</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold">Dirección</h6>
                                <p class="text-muted mb-0">Av. Apoquindo 1234<br>Las Condes, Santiago<br>Chile</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold">Horario de Atención</h6>
                                <p class="text-muted mb-0">Lun-Vie: 9:00-18:00<br>Sáb: 9:00-14:00</p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="fw-bold"><i class="fas fa-clock me-2"></i>Tiempo de Respuesta</h6>
                            <p class="mb-0">Nos comprometemos a responder todas las consultas relacionadas con privacidad dentro de los 30 días hábiles.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Resumen Rápido -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Resumen Rápido</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                No vendemos tu información
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Encriptación de datos
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Control total de tus datos
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Cumplimiento legal
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Transparencia total
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Descargar Política -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <h5 class="fw-bold">Descargar Política</h5>
                        <p class="text-muted small mb-3">Descarga una copia de nuestra política de privacidad en formato PDF.</p>
                        <button class="btn btn-primary" onclick="downloadPrivacyPolicy()">
                            <i class="fas fa-download me-2"></i>Descargar PDF
                        </button>
                    </div>
                </div>

                <!-- Actualizaciones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Últimas Actualizaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold">15 Enero 2024</h6>
                            <p class="text-muted small mb-0">Actualización de políticas de cookies y nuevas funcionalidades de control de datos.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">1 Diciembre 2023</h6>
                            <p class="text-muted small mb-0">Mejoras en la seguridad de datos y nuevos derechos de usuario.</p>
                        </div>
                        <div>
                            <h6 class="fw-bold">1 Noviembre 2023</h6>
                            <p class="text-muted small mb-0">Cumplimiento con nuevas regulaciones de protección de datos.</p>
                        </div>
                    </div>
                </div>

                <!-- Enlaces Relacionados -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-link me-2"></i>Enlaces Relacionados</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-file-contract me-2"></i>Términos y Condiciones
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-cookie-bite me-2"></i>Política de Cookies
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-shield-alt me-2"></i>Seguridad de Datos
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-question-circle me-2"></i>Preguntas Frecuentes
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-envelope me-2"></i>Contacto Legal
                                </a>
                            </li>
                        </ul>
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
                    <h6 class="mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Privacidad</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Términos</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Cookies</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contacto</a></li>
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
    <script src="assets/js/privacy.js"></script>
</body>
</html> 