<?php
$pageTitle = "Detalle de Solicitud - " . $solicitud['propiedad_titulo'];
$pageDescription = "Información detallada de la solicitud de compra";
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
            <div class="col-lg-10 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                        <?php if ($_SESSION['role'] === 'client'): ?>
                        <li class="breadcrumb-item"><a href="/solicitud-compra/mis-solicitudes">Mis Solicitudes</a></li>
                        <?php elseif ($_SESSION['role'] === 'agent'): ?>
                        <li class="breadcrumb-item"><a href="/solicitud-compra/solicitudes-agente">Solicitudes</a></li>
                        <?php else: ?>
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">Detalle</li>
                    </ol>
                </nav>

                <!-- Header de la página -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">
                            <i class="fas fa-file-alt me-2 text-primary"></i>
                            Detalle de Solicitud
                        </h1>
                        <p class="text-muted mb-0">Información completa de la solicitud de compra</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge <?= getEstadoBadgeClass($solicitud['estado']) ?> fs-6">
                            <?= getEstadoText($solicitud['estado']) ?>
                        </span>
                        <a href="<?= $_SERVER['HTTP_REFERER'] ?? '/dashboard' ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Información de la propiedad -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-home me-2"></i>
                                    Propiedad
                                </h5>
                            </div>
                            <div class="card-body">
                                <h4 class="text-primary"><?= htmlspecialchars($solicitud['propiedad_titulo']) ?></h4>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Ubicación
                                        </p>
                                        <p class="mb-0"><?= htmlspecialchars($solicitud['propiedad_ubicacion']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars($solicitud['propiedad_ciudad']) ?>, <?= htmlspecialchars($solicitud['propiedad_sector']) ?></small>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            Precio
                                        </p>
                                        <h5 class="text-primary mb-0">$<?= number_format($solicitud['propiedad_precio'], 2) ?></h5>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-bed me-1"></i>
                                            Habitaciones
                                        </p>
                                        <p class="mb-0"><?= $solicitud['propiedad_habitaciones'] ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-bath me-1"></i>
                                            Baños
                                        </p>
                                        <p class="mb-0"><?= $solicitud['propiedad_banos'] ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-ruler-combined me-1"></i>
                                            Área
                                        </p>
                                        <p class="mb-0"><?= $solicitud['propiedad_area'] ?> m²</p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Descripción
                                    </p>
                                    <p class="mb-0"><?= htmlspecialchars($solicitud['propiedad_descripcion']) ?></p>
                                </div>
                                
                                <a href="/properties/detail?id=<?= $solicitud['propiedad_id'] ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Ver Propiedad
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Información del cliente -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>
                                    Cliente
                                </h5>
                            </div>
                            <div class="card-body">
                                <h4 class="text-info"><?= htmlspecialchars($solicitud['cliente_nombre']) ?></h4>
                                
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email
                                    </p>
                                    <p class="mb-0"><?= htmlspecialchars($solicitud['cliente_email']) ?></p>
                                </div>
                                
                                <?php if ($solicitud['cliente_telefono']): ?>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-phone me-1"></i>
                                        Teléfono
                                    </p>
                                    <p class="mb-0"><?= htmlspecialchars($solicitud['cliente_telefono']) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['telefono_contacto']): ?>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-phone me-1"></i>
                                        Teléfono de Contacto
                                    </p>
                                    <p class="mb-0"><?= htmlspecialchars($solicitud['telefono_contacto']) ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['precio_ofrecido']): ?>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-tag me-1"></i>
                                        Precio Ofrecido
                                    </p>
                                    <h5 class="text-success mb-0">$<?= number_format($solicitud['precio_ofrecido'], 2) ?></h5>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['fecha_visita_preferida']): ?>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-calendar me-1"></i>
                                        Visita Preferida
                                    </p>
                                    <p class="mb-0">
                                        <?= formatDate($solicitud['fecha_visita_preferida']) ?>
                                        <?= $solicitud['hora_visita_preferida'] ? ' - ' . $solicitud['hora_visita_preferida'] : '' ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['mensaje']): ?>
                                <div class="mb-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-comment me-1"></i>
                                        Mensaje del Cliente
                                    </p>
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($solicitud['mensaje'])) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la solicitud -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información de la Solicitud
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar-plus me-1"></i>
                                            Fecha de Creación
                                        </p>
                                        <p class="mb-0"><?= formatDate($solicitud['fecha_creacion']) ?></p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Última Actualización
                                        </p>
                                        <p class="mb-0"><?= formatDate($solicitud['fecha_actualizacion']) ?></p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-user-tie me-1"></i>
                                            Agente Asignado
                                        </p>
                                        <p class="mb-0">
                                            <?= $solicitud['agente_nombre'] ? htmlspecialchars($solicitud['agente_nombre']) : 'Sin asignar' ?>
                                        </p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-flag me-1"></i>
                                            Estado Actual
                                        </p>
                                        <span class="badge <?= getEstadoBadgeClass($solicitud['estado']) ?> fs-6">
                                            <?= getEstadoText($solicitud['estado']) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($solicitud['notas_agente']): ?>
                                <div class="mt-3">
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-sticky-note me-1"></i>
                                        Notas del Agente
                                    </p>
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($solicitud['notas_agente'])) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones según el rol -->
                <?php if (in_array($_SESSION['role'], ['agent', 'admin'])): ?>
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs me-2"></i>
                                    Gestión de la Solicitud
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nuevo_estado" class="form-label">Cambiar Estado</label>
                                        <select class="form-select" id="nuevo_estado">
                                            <option value="">Seleccionar estado...</option>
                                            <option value="nuevo" <?= $solicitud['estado'] === 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                                            <option value="en_revision" <?= $solicitud['estado'] === 'en_revision' ? 'selected' : '' ?>>En Revisión</option>
                                            <option value="cita_agendada" <?= $solicitud['estado'] === 'cita_agendada' ? 'selected' : '' ?>>Cita Agendada</option>
                                            <option value="cerrado" <?= $solicitud['estado'] === 'cerrado' ? 'selected' : '' ?>>Cerrado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="notas_agente" class="form-label">Notas del Agente</label>
                                        <textarea class="form-control" id="notas_agente" rows="3" placeholder="Agregar notas sobre esta solicitud..."><?= htmlspecialchars($solicitud['notas_agente'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary" onclick="actualizarSolicitud()">
                                        <i class="fas fa-save me-1"></i>
                                        Actualizar Solicitud
                                    </button>
                                    <?php if ($solicitud['estado'] === 'cita_agendada'): ?>
                                    <a href="/citas/crear?solicitud_id=<?= $solicitud['id'] ?>" class="btn btn-success">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Crear Cita
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Acciones para clientes -->
                <?php if ($_SESSION['role'] === 'client' && $solicitud['estado'] === 'nuevo'): ?>
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Acciones Disponibles
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Puedes cancelar esta solicitud si ya no estás interesado en la propiedad.</p>
                                <button class="btn btn-danger" onclick="cancelarSolicitud(<?= $solicitud['id'] ?>)">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar Solicitud
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'app/views/layouts/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function actualizarSolicitud() {
            const nuevoEstado = document.getElementById('nuevo_estado').value;
            const notasAgente = document.getElementById('notas_agente').value;
            
            if (!nuevoEstado) {
                Swal.fire('Error', 'Debe seleccionar un estado', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('solicitud_id', <?= $solicitud['id'] ?>);
            formData.append('nuevo_estado', nuevoEstado);
            formData.append('notas_agente', notasAgente);
            
            fetch('/solicitud-compra/actualizar-estado', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        '¡Actualizado!',
                        data.message,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error',
                    'Ha ocurrido un error inesperado',
                    'error'
                );
            });
        }
        
        function cancelarSolicitud(solicitudId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, mantener'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/solicitud-compra/cancelar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'solicitud_id=' + solicitudId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Cancelada!',
                                data.message,
                                'success'
                            ).then(() => {
                                window.location.href = '/solicitud-compra/mis-solicitudes';
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error',
                            'Ha ocurrido un error inesperado',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
</body>
</html>

<?php
// Funciones helper para la vista
function getEstadoBadgeClass($estado) {
    switch ($estado) {
        case 'nuevo':
            return 'bg-primary';
        case 'en_revision':
            return 'bg-warning';
        case 'cita_agendada':
            return 'bg-info';
        case 'cerrado':
            return 'bg-secondary';
        default:
            return 'bg-secondary';
    }
}

function getEstadoText($estado) {
    switch ($estado) {
        case 'nuevo':
            return 'Nueva';
        case 'en_revision':
            return 'En Revisión';
        case 'cita_agendada':
            return 'Cita Agendada';
        case 'cerrado':
            return 'Cerrada';
        default:
            return ucfirst($estado);
    }
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}
?> 