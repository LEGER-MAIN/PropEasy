<?php
$pageTitle = "Solicitud de Compra - " . $propiedad['titulo'];
$pageDescription = "Formulario para solicitar información sobre " . $propiedad['titulo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <meta name="description" content="<?= $pageDescription ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include 'app/views/layouts/header.php'; ?>

    <main class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="/properties">Propiedades</a></li>
                        <li class="breadcrumb-item"><a href="/properties/detail?id=<?= $propiedad['id'] ?>"><?= htmlspecialchars($propiedad['titulo']) ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Solicitud de Compra</li>
                    </ol>
                </nav>

                <!-- Alerta si ya existe solicitud -->
                <?php if ($solicitud_existente): ?>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Ya tienes una solicitud activa para esta propiedad con estado: 
                    <strong><?= ucfirst(str_replace('_', ' ', $solicitud_existente['estado'])) ?></strong>
                </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-home me-2"></i>
                            Solicitud de Compra
                        </h1>
                    </div>
                    <div class="card-body">
                        <!-- Información de la propiedad -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <?php 
                                $imagenes = json_decode($propiedad['imagenes'], true);
                                $imagenPrincipal = $imagenes[0] ?? '/assets/images/default-property.jpg';
                                ?>
                                <img src="<?= $imagenPrincipal ?>" alt="<?= htmlspecialchars($propiedad['titulo']) ?>" 
                                     class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <h3 class="h5 text-primary"><?= htmlspecialchars($propiedad['titulo']) ?></h3>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= htmlspecialchars($propiedad['ubicacion']) ?>, <?= htmlspecialchars($propiedad['ciudad']) ?>
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Precio: $<?= number_format($propiedad['precio'], 2) ?>
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <?= htmlspecialchars($propiedad['tipo']) ?> | 
                                    <?= $propiedad['habitaciones'] ?> hab. | 
                                    <?= $propiedad['banos'] ?> baños | 
                                    <?= $propiedad['area'] ?> m²
                                </p>
                            </div>
                        </div>

                        <hr>

                        <!-- Formulario de solicitud -->
                        <?php if (!$solicitud_existente): ?>
                        <form id="formSolicitud" method="POST" action="/solicitud-compra/crear">
                            <input type="hidden" name="propiedad_id" value="<?= $propiedad['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mensaje" class="form-label">
                                        <i class="fas fa-comment me-1"></i>
                                        Mensaje (opcional)
                                    </label>
                                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" 
                                              placeholder="Cuéntanos más sobre tu interés en esta propiedad..."></textarea>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="telefono_contacto" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Teléfono de contacto
                                    </label>
                                    <input type="tel" class="form-control" id="telefono_contacto" name="telefono_contacto" 
                                           placeholder="809-555-0123">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="precio_ofrecido" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        Precio que está dispuesto a pagar (opcional)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="precio_ofrecido" name="precio_ofrecido" 
                                               step="0.01" min="0" placeholder="250000.00">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_visita_preferida" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Fecha preferida para visita (opcional)
                                    </label>
                                    <input type="date" class="form-control" id="fecha_visita_preferida" name="fecha_visita_preferida" 
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="hora_visita_preferida" class="form-label">
                                        <i class="fas fa-clock me-1"></i>
                                        Hora preferida para visita (opcional)
                                    </label>
                                    <input type="time" class="form-control" id="hora_visita_preferida" name="hora_visita_preferida">
                                </div>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información importante:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Su solicitud será revisada por un agente inmobiliario</li>
                                    <li>Recibirá una notificación cuando el agente responda</li>
                                    <li>Puede hacer seguimiento de su solicitud desde su panel de cliente</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/properties/detail?id=<?= $propiedad['id'] ?>" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Enviar Solicitud
                                </button>
                            </div>
                        </form>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            <h4 class="mt-3">Solicitud ya enviada</h4>
                            <p class="text-muted">Ya has enviado una solicitud para esta propiedad. Puedes hacer seguimiento desde tu panel de cliente.</p>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="/solicitud-compra/mis-solicitudes" class="btn btn-primary me-md-2">
                                    <i class="fas fa-list me-1"></i>
                                    Ver mis solicitudes
                                </a>
                                <a href="/properties/detail?id=<?= $propiedad['id'] ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Volver a la propiedad
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'app/views/layouts/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formSolicitud');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    // Cambiar texto del botón
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
                    submitBtn.disabled = true;
                    
                    // Enviar formulario via AJAX
                    fetch('/solicitud-compra/crear', {
                        method: 'POST',
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Solicitud enviada!',
                                text: data.message,
                                confirmButtonText: 'Ver mis solicitudes'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/solicitud-compra/mis-solicitudes';
                                }
                            });
                        } else {
                            // Mostrar error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error inesperado. Por favor, inténtelo de nuevo.'
                        });
                    })
                    .finally(() => {
                        // Restaurar botón
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        });
    </script>
</body>
</html> 