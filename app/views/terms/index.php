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
                    <h1 class="display-4 fw-bold mb-4">Términos y Condiciones</h1>
                    <p class="lead mb-4">Conoce las condiciones de uso de nuestros servicios y las reglas que rigen nuestra plataforma inmobiliaria.</p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-gavel fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Última actualización</h5>
                            <small>15 de Enero, 2024</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="assets/img/terms-hero.jpg" alt="Términos y Condiciones" class="img-fluid rounded shadow">
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
                                        <a href="#aceptacion-terminos" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Aceptación de Términos
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#descripcion-servicios" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Descripción de Servicios
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#registro-usuario" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Registro de Usuario
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#uso-plataforma" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Uso de la Plataforma
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="#propiedad-intelectual" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Propiedad Intelectual
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#limitaciones-responsabilidad" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Limitaciones de Responsabilidad
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="#terminacion" class="text-decoration-none">
                                            <i class="fas fa-chevron-right text-primary me-2"></i>Terminación
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
                <!-- Aceptación de Términos -->
                <div class="card border-0 shadow-sm mb-4" id="aceptacion-terminos">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-check-circle text-primary me-2"></i>
                            1. Aceptación de Términos
                        </h3>
                        <p class="text-muted mb-4">Al acceder y utilizar la plataforma PropEasy, aceptas estar sujeto a estos términos y condiciones de uso.</p>
                        
                        <div class="alert alert-info">
                            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Importante</h6>
                            <p class="mb-0">El uso de nuestros servicios implica la aceptación completa de estos términos. Si no estás de acuerdo con alguna parte, no debes utilizar nuestros servicios.</p>
                        </div>

                        <h5 class="fw-bold mb-3">1.1 Aceptación Explícita</h5>
                        <p class="text-muted mb-3">Al registrarte en nuestra plataforma o utilizar nuestros servicios, confirmas que:</p>
                        <ul class="text-muted mb-4">
                            <li>Has leído y comprendido estos términos y condiciones</li>
                            <li>Aceptas estar sujeto a todas las disposiciones aquí contenidas</li>
                            <li>Tienes la capacidad legal para celebrar este acuerdo</li>
                            <li>Proporcionarás información veraz y actualizada</li>
                        </ul>

                        <h5 class="fw-bold mb-3">1.2 Modificaciones</h5>
                        <p class="text-muted mb-3">Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios entrarán en vigor inmediatamente después de su publicación en la plataforma.</p>
                        
                        <div class="alert alert-warning">
                            <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Notificación de Cambios</h6>
                            <p class="mb-0">Te notificaremos sobre cambios importantes en los términos a través de email o mediante un aviso en la plataforma.</p>
                        </div>
                    </div>
                </div>

                <!-- Descripción de Servicios -->
                <div class="card border-0 shadow-sm mb-4" id="descripcion-servicios">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-home text-primary me-2"></i>
                            2. Descripción de Servicios
                        </h3>
                        <p class="text-muted mb-4">PropEasy es una plataforma inmobiliaria que conecta compradores, vendedores, arrendadores y arrendatarios.</p>
                        
                        <h5 class="fw-bold mb-3">2.1 Servicios Principales</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Búsqueda de Propiedades</h6>
                                        <p class="text-muted small">Acceso a catálogo completo de propiedades con filtros avanzados.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Intermediación</h6>
                                        <p class="text-muted small">Conectamos compradores y vendedores para facilitar transacciones.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Herramientas Financieras</h6>
                                        <p class="text-muted small">Calculadoras de crédito hipotecario y análisis de inversión.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Gestión de Citas</h6>
                                        <p class="text-muted small">Agenda de visitas y seguimiento de propiedades de interés.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">2.2 Servicios Adicionales</h5>
                        <ul class="text-muted">
                            <li>Asesoría inmobiliaria personalizada</li>
                            <li>Tasaciones y avalúos de propiedades</li>
                            <li>Servicios de financiamiento hipotecario</li>
                            <li>Asesoría legal y notarial</li>
                            <li>Seguros inmobiliarios</li>
                        </ul>
                    </div>
                </div>

                <!-- Registro de Usuario -->
                <div class="card border-0 shadow-sm mb-4" id="registro-usuario">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            3. Registro de Usuario
                        </h3>
                        <p class="text-muted mb-4">Para utilizar ciertos servicios, es necesario crear una cuenta en nuestra plataforma.</p>
                        
                        <h5 class="fw-bold mb-3">3.1 Requisitos de Registro</h5>
                        <div class="alert alert-success">
                            <h6 class="fw-bold"><i class="fas fa-check me-2"></i>Para registrarte necesitas:</h6>
                            <ul class="mb-0">
                                <li>Ser mayor de 18 años</li>
                                <li>Proporcionar información veraz y completa</li>
                                <li>Tener capacidad legal para celebrar contratos</li>
                                <li>Aceptar estos términos y condiciones</li>
                            </ul>
                        </div>

                        <h5 class="fw-bold mb-3">3.2 Información Requerida</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary">Información Personal</h6>
                                <ul class="text-muted small">
                                    <li>Nombre completo</li>
                                    <li>Dirección de email</li>
                                    <li>Número de teléfono</li>
                                    <li>Dirección física</li>
                                    <li>RUT o identificación</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success">Información Adicional</h6>
                                <ul class="text-muted small">
                                    <li>Preferencias de búsqueda</li>
                                    <li>Presupuesto disponible</li>
                                    <li>Zonas de interés</li>
                                    <li>Tipo de propiedad buscada</li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">3.3 Responsabilidades del Usuario</h5>
                        <div class="alert alert-warning">
                            <h6 class="fw-bold"><i class="fas fa-shield-alt me-2"></i>Es tu responsabilidad:</h6>
                            <ul class="mb-0">
                                <li>Mantener la confidencialidad de tu cuenta</li>
                                <li>Notificar inmediatamente cualquier uso no autorizado</li>
                                <li>Actualizar tu información cuando sea necesario</li>
                                <li>No compartir tu cuenta con terceros</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Uso de la Plataforma -->
                <div class="card border-0 shadow-sm mb-4" id="uso-plataforma">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-laptop text-primary me-2"></i>
                            4. Uso de la Plataforma
                        </h3>
                        <p class="text-muted mb-4">Establecemos reglas claras para el uso adecuado de nuestra plataforma.</p>
                        
                        <h5 class="fw-bold mb-3">4.1 Uso Permitido</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-success">
                                            <i class="fas fa-check-circle me-2"></i>Permitido
                                        </h6>
                                        <ul class="text-muted small mb-0">
                                            <li>Buscar propiedades</li>
                                            <li>Contactar agentes</li>
                                            <li>Agendar visitas</li>
                                            <li>Guardar favoritos</li>
                                            <li>Usar calculadoras</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-danger">
                                            <i class="fas fa-times-circle me-2"></i>Prohibido
                                        </h6>
                                        <ul class="text-muted small mb-0">
                                            <li>Uso comercial no autorizado</li>
                                            <li>Spam o mensajes masivos</li>
                                            <li>Información falsa</li>
                                            <li>Acceso no autorizado</li>
                                            <li>Interferir con el servicio</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">4.2 Conducta del Usuario</h5>
                        <p class="text-muted mb-3">Al utilizar nuestra plataforma, te comprometes a:</p>
                        <ul class="text-muted mb-4">
                            <li>Actuar de manera ética y profesional</li>
                            <li>Respetar los derechos de otros usuarios</li>
                            <li>No realizar actividades fraudulentas</li>
                            <li>Cumplir con todas las leyes aplicables</li>
                            <li>No intentar acceder a sistemas restringidos</li>
                        </ul>

                        <h5 class="fw-bold mb-3">4.3 Contenido del Usuario</h5>
                        <p class="text-muted mb-3">Al publicar contenido en nuestra plataforma:</p>
                        <ul class="text-muted">
                            <li>Eres responsable del contenido que publicas</li>
                            <li>Nos otorgas licencia para usar el contenido</li>
                            <li>Debes tener derechos sobre el contenido</li>
                            <li>El contenido no debe ser ofensivo o ilegal</li>
                        </ul>
                    </div>
                </div>

                <!-- Propiedad Intelectual -->
                <div class="card border-0 shadow-sm mb-4" id="propiedad-intelectual">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-copyright text-primary me-2"></i>
                            5. Propiedad Intelectual
                        </h3>
                        <p class="text-muted mb-4">Todos los derechos de propiedad intelectual de la plataforma pertenecen a PropEasy.</p>
                        
                        <h5 class="fw-bold mb-3">5.1 Derechos de PropEasy</h5>
                        <div class="alert alert-primary">
                            <h6 class="fw-bold"><i class="fas fa-gavel me-2"></i>Propiedad de PropEasy</h6>
                            <p class="mb-0">Incluye pero no se limita a: diseño, código, logos, marcas, contenido, bases de datos y funcionalidades de la plataforma.</p>
                        </div>

                        <h5 class="fw-bold mb-3">5.2 Licencia de Uso</h5>
                        <p class="text-muted mb-3">Te otorgamos una licencia limitada, no exclusiva y revocable para:</p>
                        <ul class="text-muted mb-4">
                            <li>Acceder y utilizar la plataforma</li>
                            <li>Ver y buscar propiedades</li>
                            <li>Contactar agentes inmobiliarios</li>
                            <li>Utilizar herramientas de la plataforma</li>
                        </ul>

                        <h5 class="fw-bold mb-3">5.3 Restricciones</h5>
                        <p class="text-muted mb-3">No puedes:</p>
                        <ul class="text-muted">
                            <li>Copiar, modificar o distribuir el contenido</li>
                            <li>Usar la plataforma para fines comerciales no autorizados</li>
                            <li>Intentar ingeniería inversa del software</li>
                            <li>Eliminar marcas de agua o créditos</li>
                        </ul>
                    </div>
                </div>

                <!-- Limitaciones de Responsabilidad -->
                <div class="card border-0 shadow-sm mb-4" id="limitaciones-responsabilidad">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-exclamation-triangle text-primary me-2"></i>
                            6. Limitaciones de Responsabilidad
                        </h3>
                        <p class="text-muted mb-4">Establecemos límites claros sobre nuestra responsabilidad en el uso de la plataforma.</p>
                        
                        <h5 class="fw-bold mb-3">6.1 Alcance de Nuestros Servicios</h5>
                        <div class="alert alert-info">
                            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>PropEasy actúa como intermediario</h6>
                            <p class="mb-0">No somos propietarios de las propiedades listadas ni garantizamos las transacciones entre usuarios.</p>
                        </div>

                        <h5 class="fw-bold mb-3">6.2 Limitaciones Específicas</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-warning">
                                            <i class="fas fa-home me-2"></i>Propiedades
                                        </h6>
                                        <p class="text-muted small mb-0">No verificamos la exactitud de toda la información de las propiedades listadas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-warning">
                                            <i class="fas fa-users me-2"></i>Usuarios
                                        </h6>
                                        <p class="text-muted small mb-0">No verificamos la identidad o credibilidad de todos los usuarios registrados.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-warning">
                                            <i class="fas fa-handshake me-2"></i>Transacciones
                                        </h6>
                                        <p class="text-muted small mb-0">No garantizamos el éxito de las transacciones entre usuarios.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-warning">
                                            <i class="fas fa-tools me-2"></i>Servicio
                                        </h6>
                                        <p class="text-muted small mb-0">No garantizamos la disponibilidad continua del servicio.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">6.3 Exclusión de Garantías</h5>
                        <p class="text-muted mb-3">La plataforma se proporciona "tal como está" sin garantías de ningún tipo, incluyendo:</p>
                        <ul class="text-muted">
                            <li>Garantías de comerciabilidad</li>
                            <li>Garantías de idoneidad para un propósito específico</li>
                            <li>Garantías de no infracción</li>
                            <li>Garantías de disponibilidad continua</li>
                        </ul>
                    </div>
                </div>

                <!-- Terminación -->
                <div class="card border-0 shadow-sm mb-4" id="terminacion">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-times-circle text-primary me-2"></i>
                            7. Terminación
                        </h3>
                        <p class="text-muted mb-4">Establecemos las condiciones bajo las cuales se puede terminar el uso de nuestros servicios.</p>
                        
                        <h5 class="fw-bold mb-3">7.1 Terminación por el Usuario</h5>
                        <div class="alert alert-success">
                            <h6 class="fw-bold"><i class="fas fa-user-times me-2"></i>Puedes terminar tu cuenta en cualquier momento</h6>
                            <p class="mb-0">Contacta nuestro equipo de soporte para solicitar la eliminación de tu cuenta y datos personales.</p>
                        </div>

                        <h5 class="fw-bold mb-3">7.2 Terminación por PropEasy</h5>
                        <p class="text-muted mb-3">Podemos terminar o suspender tu cuenta por:</p>
                        <ul class="text-muted mb-4">
                            <li>Violación de estos términos y condiciones</li>
                            <li>Actividad fraudulenta o ilegal</li>
                            <li>Uso inapropiado de la plataforma</li>
                            <li>Incumplimiento de leyes aplicables</li>
                            <li>Comportamiento abusivo hacia otros usuarios</li>
                        </ul>

                        <h5 class="fw-bold mb-3">7.3 Efectos de la Terminación</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-danger">Inmediato</h6>
                                <ul class="text-muted small">
                                    <li>Acceso bloqueado a la plataforma</li>
                                    <li>Cancelación de servicios activos</li>
                                    <li>Eliminación de contenido público</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning">30 días</h6>
                                <ul class="text-muted small">
                                    <li>Eliminación completa de datos</li>
                                    <li>Cancelación de suscripciones</li>
                                    <li>Archivado de transacciones</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="card border-0 shadow-sm mb-4" id="contacto">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            8. Contacto
                        </h3>
                        <p class="text-muted mb-4">Para consultas sobre estos términos y condiciones, contáctanos a través de los siguientes canales:</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold">Email Legal</h6>
                                <p class="text-muted mb-0">legal@propeasy.cl</p>
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
                            <p class="mb-0">Nos comprometemos a responder todas las consultas legales dentro de los 5 días hábiles.</p>
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
                                Uso personal permitido
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Información veraz requerida
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Propiedad intelectual protegida
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Responsabilidades claras
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Terminación transparente
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Descargar Términos -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                        <h5 class="fw-bold">Descargar Términos</h5>
                        <p class="text-muted small mb-3">Descarga una copia de nuestros términos y condiciones en formato PDF.</p>
                        <button class="btn btn-primary" onclick="downloadTerms()">
                            <i class="fas fa-download me-2"></i>Descargar PDF
                        </button>
                    </div>
                </div>

                <!-- Versiones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Versiones</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold">v2.1 - 15 Enero 2024</h6>
                            <p class="text-muted small mb-0">Actualización de políticas de uso y nuevas funcionalidades.</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">v2.0 - 1 Diciembre 2023</h6>
                            <p class="text-muted small mb-0">Revisión completa de términos y nuevas secciones legales.</p>
                        </div>
                        <div>
                            <h6 class="fw-bold">v1.9 - 1 Noviembre 2023</h6>
                            <p class="text-muted small mb-0">Cumplimiento con nuevas regulaciones y mejoras en claridad.</p>
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
                                <a href="/privacy" class="text-decoration-none">
                                    <i class="fas fa-shield-alt me-2"></i>Política de Privacidad
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-cookie-bite me-2"></i>Política de Cookies
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-question-circle me-2"></i>Preguntas Frecuentes
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-gavel me-2"></i>Información Legal
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
                        <li><a href="#" class="text-muted text-decoration-none">Términos</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Privacidad</a></li>
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
    <script src="assets/js/terms.js"></script>
</body>
</html> 