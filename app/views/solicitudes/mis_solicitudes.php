<?php
$pageTitle = "Mis Solicitudes de Compra";
$pageDescription = "Seguimiento de todas tus solicitudes de compra de propiedades";
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
            <div class="col-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="/dashboard">Panel de Cliente</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mis Solicitudes</li>
                    </ol>
                </nav>

                <!-- Header de la página -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">
                            <i class="fas fa-list me-2 text-primary"></i>
                            Mis Solicitudes de Compra
                        </h1>
                        <p class="text-muted mb-0">Seguimiento de todas tus solicitudes de propiedades</p>
                    </div>
                    <a href="/properties" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Nueva Solicitud
                    </a>
                </div>

                <?php if (empty($solicitudes)): ?>
                <!-- Estado vacío -->
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 text-muted">No tienes solicitudes</h3>
                    <p class="text-muted mb-4">Aún no has enviado solicitudes de compra para ninguna propiedad.</p>
                    <a href="/properties" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        Explorar Propiedades
                    </a>
                </div>
                <?php else: ?>
                <!-- Lista de solicitudes -->
                <div class="row">
                    <?php foreach ($solicitudes as $solicitud): ?>
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="badge <?= $this->getEstadoBadgeClass($solicitud['estado']) ?>">
                                    <?= $this->getEstadoText($solicitud['estado']) ?>
                                </span>
                                <small class="text-muted">
                                    <?= $this->formatDate($solicitud['fecha_creacion']) ?>
                                </small>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <?= htmlspecialchars($solicitud['propiedad_titulo']) ?>
                                </h5>
                                
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= htmlspecialchars($solicitud['propiedad_ubicacion']) ?>
                                </p>
                                
                                <p class="text-muted mb-2">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Precio: $<?= number_format($solicitud['propiedad_precio'], 2) ?>
                                </p>
                                
                                <?php if ($solicitud['precio_ofrecido']): ?>
                                <p class="text-info mb-2">
                                    <i class="fas fa-tag me-1"></i>
                                    Tu oferta: $<?= number_format($solicitud['precio_ofrecido'], 2) ?>
                                </p>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['mensaje']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Tu mensaje:</small>
                                    <p class="mb-0 small"><?= htmlspecialchars(substr($solicitud['mensaje'], 0, 100)) ?><?= strlen($solicitud['mensaje']) > 100 ? '...' : '' ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($solicitud['agente_nombre']): ?>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user me-1"></i>
                                    Agente: <?= htmlspecialchars($solicitud['agente_nombre']) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/solicitud-compra/ver?id=<?= $solicitud['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver Detalles
                                    </a>
                                    
                                    <?php if ($solicitud['estado'] === 'nuevo'): ?>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelarSolicitud(<?= $solicitud['id'] ?>)">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Estadísticas -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h4 class="h5 mb-3">
                            <i class="fas fa-chart-bar me-2"></i>
                            Resumen de Solicitudes
                        </h4>
                        <div class="row">
                            <?php
                            $estados = ['nuevo', 'en_revision', 'cita_agendada', 'cerrado'];
                            $contadores = array_count_values(array_column($solicitudes, 'estado'));
                            ?>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                        <h5 class="card-title"><?= $contadores['nuevo'] ?? 0 ?></h5>
                                        <p class="card-text small">Nuevas</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-clock fa-2x mb-2"></i>
                                        <h5 class="card-title"><?= $contadores['en_revision'] ?? 0 ?></h5>
                                        <p class="card-text small">En Revisión</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                        <h5 class="card-title"><?= $contadores['cita_agendada'] ?? 0 ?></h5>
                                        <p class="card-text small">Con Cita</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                                        <h5 class="card-title"><?= $contadores['cerrado'] ?? 0 ?></h5>
                                        <p class="card-text small">Cerradas</p>
                                    </div>
                                </div>
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