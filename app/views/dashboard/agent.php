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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/agent-dashboard.css">
</head>
<body class="bg-light">
    <?php include __DIR__ . '/../agent/_navigation.php'; ?>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 fw-bold">Dashboard Agente</h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-1"></i>
                            <?php 
                                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                $fecha = $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[date('n')-1] . ' de ' . date('Y');
                                echo $fecha;
                            ?>
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="/properties/publish" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Nueva Propiedad
                        </a>
                        <a href="/agent/appointments" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Ver Agenda
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/agent/stats">
                                    <i class="fas fa-chart-bar me-2"></i>Estadísticas Detalladas
                                </a></li>
                                <li><a class="dropdown-item" href="/agent/messages">
                                    <i class="fas fa-inbox me-2"></i>Bandeja de Entrada
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/agent/settings">
                                    <i class="fas fa-cog me-2"></i>Configuración
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Principales -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-primary text-uppercase fw-bold small mb-1">
                                    Mis Propiedades
                                </div>
                                <div class="h4 mb-0 fw-bold text-dark"><?= $stats['total_properties'] ?? 0 ?></div>
                                <div class="text-success small">
                                    <i class="fas fa-arrow-up"></i> <?= $stats['active_properties'] ?? 0 ?> activas
                                </div>
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-home fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-success text-uppercase fw-bold small mb-1">
                                    Ventas Realizadas
                                </div>
                                <div class="h4 mb-0 fw-bold text-dark"><?= $stats['sold_properties'] ?? 0 ?></div>
                                <div class="text-success small">
                                    <i class="fas fa-percentage"></i> <?= $stats['conversion_rate'] ?? 0 ?>% conversión
                                </div>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-chart-line fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-warning text-uppercase fw-bold small mb-1">
                                    Citas Pendientes
                                </div>
                                <div class="h4 mb-0 fw-bold text-dark"><?= $stats['pending_appointments'] ?? 0 ?></div>
                                <div class="text-info small">
                                    <i class="fas fa-envelope"></i> <?= $stats['unread_contacts'] ?? 0 ?> mensajes nuevos
                                </div>
                            </div>
                            <div class="text-warning">
                                <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas y Métricas -->
        <div class="row mb-4">
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="/properties/publish" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-3">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    <div>
                                        <div class="fw-bold">Nueva Propiedad</div>
                                        <small class="text-muted">Publicar ahora</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="/agent/messages" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center py-3">
                                    <i class="fas fa-envelope-open me-2"></i>
                                    <div>
                                        <div class="fw-bold">Ver Mensajes</div>
                                        <small class="text-muted"><?= $stats['unread_contacts'] ?? 0 ?> nuevos</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="/agent/appointments" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center py-3">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    <div>
                                        <div class="fw-bold">Mi Agenda</div>
                                        <small class="text-muted"><?= $stats['pending_appointments'] ?? 0 ?> pendientes</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="/agent/clients" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center py-3">
                                    <i class="fas fa-users me-2"></i>
                                    <div>
                                        <div class="fw-bold">Mis Clientes</div>
                                        <small class="text-muted">Gestionar</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actividad Reciente</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-eye text-primary me-2"></i>
                                    Nueva vista en propiedad
                                </div>
                                <small class="text-muted">Hace 5 min</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-comment text-success me-2"></i>
                                    Mensaje de cliente
                                </div>
                                <small class="text-muted">Hace 15 min</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-calendar-check text-info me-2"></i>
                                    Cita confirmada
                                </div>
                                <small class="text-muted">Hace 1 hora</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="row">
            <!-- Mis Propiedades -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Mis Propiedades</h6>
                        <a href="/agent/properties" class="btn btn-sm btn-outline-primary">
                            Ver Todas
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Propiedad</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                        <th>Vistas</th>
                                        <th>Consultas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($myProperties as $property): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($property['title']) ?></td>
                                        <td>$<?= number_format((float)($property['price'] ?? 0)) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $property['status'] === 'Activa' ? 'success' : 'secondary' ?>">
                                                <?= $property['status'] ?>
                                            </span>
                                        </td>
                                        <td><?= $property['views'] ?></td>
                                        <td><?= $property['inquiries'] ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/properties/detail/<?= $property['id'] ?>" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/properties/edit/<?= $property['id'] ?>" class="btn btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximas Citas -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Próximas Citas</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentAppointments)): ?>
                            <p class="text-muted text-center">No hay citas programadas</p>
                        <?php else: ?>
                            <?php foreach ($recentAppointments as $appointment): ?>
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($appointment['client']) ?></h6>
                                            <p class="text-muted mb-1 small"><?= htmlspecialchars($appointment['property']) ?></p>
                                            <p class="text-muted mb-0 small">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= $appointment['date'] ?> a las <?= $appointment['time'] ?>
                                            </p>
                                        </div>
                                        <span class="badge bg-<?= $appointment['status'] === 'Confirmada' ? 'success' : 'warning' ?>">
                                            <?= $appointment['status'] ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="text-center">
                            <a href="/agent/appointments" class="btn btn-sm btn-outline-primary">
                                Ver Agenda Completa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes Recientes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Mensajes Recientes</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentMessages)): ?>
                            <p class="text-muted text-center">No hay mensajes nuevos</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentMessages as $message): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-start <?= $message['unread'] ? 'bg-light' : '' ?>">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold"><?= htmlspecialchars($message['client']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($message['property']) ?></div>
                                            <div class="mt-1"><?= htmlspecialchars($message['message']) ?></div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted"><?= $message['date'] ?></small>
                                            <?php if ($message['unread']): ?>
                                                <div class="mt-1">
                                                    <span class="badge bg-danger">Nuevo</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="text-center mt-3">
                            <a href="/agent/messages" class="btn btn-outline-primary">
                                Ver Todos los Mensajes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/agent-dashboard.js"></script>
</body>
</html> 