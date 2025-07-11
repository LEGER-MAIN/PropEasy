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
                    <h1 class="display-4 fw-bold mb-4">Preguntas Frecuentes</h1>
                    <p class="lead mb-4">Encuentra respuestas rápidas a las preguntas más comunes sobre nuestros servicios inmobiliarios.</p>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-question-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">¿No encuentras tu respuesta?</h5>
                            <small>Contáctanos directamente</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <img src="assets/img/faq-hero.jpg" alt="Preguntas Frecuentes" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Barra de Búsqueda -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="search-faq" class="form-control form-control-lg" 
                                           placeholder="Busca en nuestras preguntas frecuentes...">
                                    <button class="btn btn-primary" type="button" onclick="searchFAQ()">
                                        <i class="fas fa-search me-2"></i>Buscar
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <button class="btn btn-outline-primary" onclick="showAllFAQs()">
                                    <i class="fas fa-list me-2"></i>Ver Todas
                                </button>
                                <button class="btn btn-outline-success" onclick="contactSupport()">
                                    <i class="fas fa-headset me-2"></i>Contactar Soporte
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="text-center mb-4">Categorías de Preguntas</h3>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card category-card text-center h-100" data-category="propiedades">
                            <div class="card-body">
                                <i class="fas fa-home fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Propiedades</h5>
                                <p class="card-text text-muted">Búsqueda, compra y venta</p>
                                <span class="badge bg-primary"><?= $stats['propiedades'] ?> preguntas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card category-card text-center h-100" data-category="financiamiento">
                            <div class="card-body">
                                <i class="fas fa-calculator fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Financiamiento</h5>
                                <p class="card-text text-muted">Créditos y préstamos</p>
                                <span class="badge bg-success"><?= $stats['financiamiento'] ?> preguntas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card category-card text-center h-100" data-category="servicios">
                            <div class="card-body">
                                <i class="fas fa-tools fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Servicios</h5>
                                <p class="card-text text-muted">Tasaciones y asesoría</p>
                                <span class="badge bg-info"><?= $stats['servicios'] ?> preguntas</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card category-card text-center h-100" data-category="cuenta">
                            <div class="card-body">
                                <i class="fas fa-user fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Mi Cuenta</h5>
                                <p class="card-text text-muted">Gestión de perfil</p>
                                <span class="badge bg-warning"><?= $stats['cuenta'] ?> preguntas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preguntas Frecuentes -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Propiedades -->
                <div class="faq-section" id="propiedades">
                    <h3 class="mb-4">
                        <i class="fas fa-home text-primary me-2"></i>
                        Propiedades
                    </h3>
                    
                    <div class="accordion" id="accordionPropiedades">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#prop1">
                                    ¿Cómo puedo buscar propiedades en PropEasy?
                                </button>
                            </h2>
                            <div id="prop1" class="accordion-collapse collapse show" data-bs-parent="#accordionPropiedades">
                                <div class="accordion-body">
                                    <p>Puedes buscar propiedades de varias formas:</p>
                                    <ul>
                                        <li><strong>Búsqueda básica:</strong> Usa la barra de búsqueda en la página principal</li>
                                        <li><strong>Filtros avanzados:</strong> Especifica ubicación, precio, tipo de propiedad, etc.</li>
                                        <li><strong>Mapa interactivo:</strong> Explora propiedades por ubicación geográfica</li>
                                        <li><strong>Guardar búsquedas:</strong> Crea alertas para nuevas propiedades</li>
                                    </ul>
                                    <div class="mt-3">
                                        <a href="/properties" class="btn btn-primary btn-sm">
                                            <i class="fas fa-search me-2"></i>Buscar Propiedades
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#prop2">
                                    ¿Qué información incluye cada ficha de propiedad?
                                </button>
                            </h2>
                            <div id="prop2" class="accordion-collapse collapse" data-bs-parent="#accordionPropiedades">
                                <div class="accordion-body">
                                    <p>Cada ficha incluye información completa:</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Información Básica</h6>
                                            <ul class="text-muted">
                                                <li>Precio y condiciones de venta/arriendo</li>
                                                <li>Ubicación exacta y comuna</li>
                                                <li>Metros cuadrados construidos y terreno</li>
                                                <li>Número de dormitorios y baños</li>
                                                <li>Tipo de propiedad (casa, departamento, etc.)</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Información Adicional</h6>
                                            <ul class="text-muted">
                                                <li>Galería de fotos profesional</li>
                                                <li>Video tour virtual</li>
                                                <li>Características y amenities</li>
                                                <li>Información del agente</li>
                                                <li>Calculadora de crédito</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#prop3">
                                    ¿Cómo puedo agendar una visita a una propiedad?
                                </button>
                            </h2>
                            <div id="prop3" class="accordion-collapse collapse" data-bs-parent="#accordionPropiedades">
                                <div class="accordion-body">
                                    <p>Para agendar una visita:</p>
                                    <ol>
                                        <li>Ve a la ficha de la propiedad que te interesa</li>
                                        <li>Haz clic en "Agendar Visita"</li>
                                        <li>Selecciona fecha y hora disponible</li>
                                        <li>Completa tus datos de contacto</li>
                                        <li>Recibirás confirmación por email y SMS</li>
                                    </ol>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Tip:</strong> Las visitas se confirman automáticamente y puedes cancelarlas hasta 24 horas antes.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financiamiento -->
                <div class="faq-section" id="financiamiento">
                    <h3 class="mb-4">
                        <i class="fas fa-calculator text-success me-2"></i>
                        Financiamiento
                    </h3>
                    
                    <div class="accordion" id="accordionFinanciamiento">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#fin1">
                                    ¿Cómo funciona la calculadora de crédito hipotecario?
                                </button>
                            </h2>
                            <div id="fin1" class="accordion-collapse collapse show" data-bs-parent="#accordionFinanciamiento">
                                <div class="accordion-body">
                                    <p>Nuestra calculadora te ayuda a estimar tu capacidad de pago:</p>
                                    <ul>
                                        <li><strong>Ingresa el precio de la propiedad</strong></li>
                                        <li><strong>Especifica el pie inicial</strong> (mínimo 20%)</li>
                                        <li><strong>Selecciona el plazo</strong> (hasta 30 años)</li>
                                        <li><strong>Ingresa tus ingresos mensuales</strong></li>
                                        <li><strong>Obtén el monto máximo de crédito</strong></li>
                                    </ul>
                                    <div class="mt-3">
                                        <a href="#" class="btn btn-success btn-sm" onclick="openCalculator()">
                                            <i class="fas fa-calculator me-2"></i>Usar Calculadora
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fin2">
                                    ¿Qué documentos necesito para solicitar un crédito hipotecario?
                                </button>
                            </h2>
                            <div id="fin2" class="accordion-collapse collapse" data-bs-parent="#accordionFinanciamiento">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-primary">Documentos Personales</h6>
                                            <ul class="text-muted">
                                                <li>Cédula de identidad vigente</li>
                                                <li>Certificado de residencia</li>
                                                <li>Certificado de estado civil</li>
                                                <li>Certificado de nacimiento</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-success">Documentos Laborales</h6>
                                            <ul class="text-muted">
                                                <li>Certificado de trabajo</li>
                                                <li>Últimas 3 liquidaciones de sueldo</li>
                                                <li>Certificado de cotizaciones previsionales</li>
                                                <li>Declaración de renta (si aplica)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Importante:</strong> Los requisitos pueden variar según el banco y el tipo de crédito.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fin3">
                                    ¿Cuál es el pie mínimo requerido para comprar una propiedad?
                                </button>
                            </h2>
                            <div id="fin3" class="accordion-collapse collapse" data-bs-parent="#accordionFinanciamiento">
                                <div class="accordion-body">
                                    <p>El pie mínimo varía según el tipo de propiedad:</p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Tipo de Propiedad</th>
                                                    <th>Pie Mínimo</th>
                                                    <th>Notas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Vivienda nueva</td>
                                                    <td><span class="badge bg-success">10%</span></td>
                                                    <td>Con subsidio habitacional</td>
                                                </tr>
                                                <tr>
                                                    <td>Vivienda usada</td>
                                                    <td><span class="badge bg-warning">20%</span></td>
                                                    <td>Sin subsidio</td>
                                                </tr>
                                                <tr>
                                                    <td>Segunda vivienda</td>
                                                    <td><span class="badge bg-info">30%</span></td>
                                                    <td>Inversión</td>
                                                </tr>
                                                <tr>
                                                    <td>Propiedad comercial</td>
                                                    <td><span class="badge bg-primary">40%</span></td>
                                                    <td>Uso comercial</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Servicios -->
                <div class="faq-section" id="servicios">
                    <h3 class="mb-4">
                        <i class="fas fa-tools text-info me-2"></i>
                        Servicios
                    </h3>
                    
                    <div class="accordion" id="accordionServicios">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ser1">
                                    ¿Qué servicios de tasación ofrecen?
                                </button>
                            </h2>
                            <div id="ser1" class="accordion-collapse collapse show" data-bs-parent="#accordionServicios">
                                <div class="accordion-body">
                                    <p>Ofrecemos servicios de tasación completos:</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-info">Tipos de Tasación</h6>
                                            <ul class="text-muted">
                                                <li>Tasación comercial</li>
                                                <li>Tasación residencial</li>
                                                <li>Tasación industrial</li>
                                                <li>Tasación de terrenos</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-info">Incluye</h6>
                                            <ul class="text-muted">
                                                <li>Inspección física</li>
                                                <li>Análisis de mercado</li>
                                                <li>Reporte detallado</li>
                                                <li>Certificación oficial</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="/contact" class="btn btn-info btn-sm">
                                            <i class="fas fa-phone me-2"></i>Solicitar Tasación
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ser2">
                                    ¿Cómo funciona el servicio de asesoría legal?
                                </button>
                            </h2>
                            <div id="ser2" class="accordion-collapse collapse" data-bs-parent="#accordionServicios">
                                <div class="accordion-body">
                                    <p>Nuestro servicio de asesoría legal incluye:</p>
                                    <ol>
                                        <li><strong>Evaluación inicial gratuita</strong> de tu caso</li>
                                        <li><strong>Revisión de documentos</strong> y contratos</li>
                                        <li><strong>Asesoría en trámites</strong> legales</li>
                                        <li><strong>Acompañamiento</strong> en firmas notariales</li>
                                        <li><strong>Seguimiento</strong> del proceso completo</li>
                                    </ol>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>Beneficio:</strong> Primera consulta sin costo para clientes registrados.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mi Cuenta -->
                <div class="faq-section" id="cuenta">
                    <h3 class="mb-4">
                        <i class="fas fa-user text-warning me-2"></i>
                        Mi Cuenta
                    </h3>
                    
                    <div class="accordion" id="accordionCuenta">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cue1">
                                    ¿Cómo puedo crear una cuenta en PropEasy?
                                </button>
                            </h2>
                            <div id="cue1" class="accordion-collapse collapse show" data-bs-parent="#accordionCuenta">
                                <div class="accordion-body">
                                    <p>Crear una cuenta es muy sencillo:</p>
                                    <ol>
                                        <li>Haz clic en "Iniciar Sesión" en la parte superior</li>
                                        <li>Selecciona "Crear Cuenta"</li>
                                        <li>Completa el formulario con tus datos</li>
                                        <li>Verifica tu email</li>
                                        <li>¡Listo! Ya puedes usar todas las funcionalidades</li>
                                    </ol>
                                    <div class="mt-3">
                                        <a href="/auth/register" class="btn btn-warning btn-sm">
                                            <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cue2">
                                    ¿Cómo puedo recuperar mi contraseña?
                                </button>
                            </h2>
                            <div id="cue2" class="accordion-collapse collapse" data-bs-parent="#accordionCuenta">
                                <div class="accordion-body">
                                    <p>Para recuperar tu contraseña:</p>
                                    <ol>
                                        <li>Ve a la página de inicio de sesión</li>
                                        <li>Haz clic en "¿Olvidaste tu contraseña?"</li>
                                        <li>Ingresa tu email registrado</li>
                                        <li>Recibirás un enlace de recuperación</li>
                                        <li>Crea una nueva contraseña segura</li>
                                    </ol>
                                    <div class="alert alert-info">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <strong>Seguridad:</strong> El enlace expira en 24 horas por tu seguridad.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cue3">
                                    ¿Cómo puedo actualizar mi información personal?
                                </button>
                            </h2>
                            <div id="cue3" class="accordion-collapse collapse" data-bs-parent="#accordionCuenta">
                                <div class="accordion-body">
                                    <p>Para actualizar tu información:</p>
                                    <ol>
                                        <li>Inicia sesión en tu cuenta</li>
                                        <li>Ve a "Mi Perfil" en el menú</li>
                                        <li>Haz clic en "Editar Información"</li>
                                        <li>Actualiza los campos necesarios</li>
                                        <li>Guarda los cambios</li>
                                    </ol>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Información que puedes actualizar:</h6>
                                            <ul class="text-muted">
                                                <li>Nombre y apellidos</li>
                                                <li>Email y teléfono</li>
                                                <li>Dirección</li>
                                                <li>Preferencias de búsqueda</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Información que requiere verificación:</h6>
                                            <ul class="text-muted">
                                                <li>RUT/Identificación</li>
                                                <li>Fecha de nacimiento</li>
                                                <li>Estado civil</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Preguntas Populares -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Preguntas Populares</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#prop1" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">¿Cómo buscar propiedades?</h6>
                                    <small class="text-muted">Propiedades</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">1.2k</span>
                            </a>
                            <a href="#fin1" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Calculadora de crédito</h6>
                                    <small class="text-muted">Financiamiento</small>
                                </div>
                                <span class="badge bg-success rounded-pill">856</span>
                            </a>
                            <a href="#ser1" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Servicios de tasación</h6>
                                    <small class="text-muted">Servicios</small>
                                </div>
                                <span class="badge bg-info rounded-pill">543</span>
                            </a>
                            <a href="#cue1" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Crear cuenta</h6>
                                    <small class="text-muted">Mi Cuenta</small>
                                </div>
                                <span class="badge bg-warning rounded-pill">432</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contacto Rápido -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-headset me-2"></i>¿Necesitas Ayuda?</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-phone fa-3x text-success mb-3"></i>
                        <h6 class="fw-bold">Soporte 24/7</h6>
                        <p class="text-muted mb-3">Nuestro equipo está disponible para ayudarte</p>
                        
                        <div class="d-grid gap-2">
                            <a href="tel:+56223456789" class="btn btn-success">
                                <i class="fas fa-phone me-2"></i>+56 2 2345 6789
                            </a>
                            <a href="mailto:soporte@propeasy.cl" class="btn btn-outline-success">
                                <i class="fas fa-envelope me-2"></i>soporte@propeasy.cl
                            </a>
                            <a href="/contact" class="btn btn-outline-primary">
                                <i class="fas fa-comments me-2"></i>Chat en Vivo
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <h4 class="text-primary fw-bold"><?= $stats['total'] ?></h4>
                                <small class="text-muted">Preguntas Totales</small>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-success fw-bold"><?= $stats['resueltas'] ?>%</h4>
                                <small class="text-muted">Resueltas</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info fw-bold"><?= $stats['categorias'] ?></h4>
                                <small class="text-muted">Categorías</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning fw-bold"><?= $stats['tiempo_respuesta'] ?></h4>
                                <small class="text-muted">Min. Respuesta</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enlaces Útiles -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-link me-2"></i>Enlaces Útiles</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="/terms" class="text-decoration-none">
                                    <i class="fas fa-gavel me-2"></i>Términos y Condiciones
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/privacy" class="text-decoration-none">
                                    <i class="fas fa-shield-alt me-2"></i>Política de Privacidad
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/about" class="text-decoration-none">
                                    <i class="fas fa-info-circle me-2"></i>Sobre Nosotros
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/contact" class="text-decoration-none">
                                    <i class="fas fa-envelope me-2"></i>Contacto
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-download me-2"></i>Descargar App
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
                    <h6 class="mb-3">Ayuda</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Soporte</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contacto</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Blog</a></li>
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
    <script src="assets/js/faq.js"></script>
</body>
</html> 