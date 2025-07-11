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
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-home me-2"></i>PropEasy
            </a>
            <div class="d-flex">
                <?php if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']): ?>
                    <a href="/auth/logout" class="btn btn-outline-light ms-2">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                <?php else: ?>
                    <a href="/auth/login" class="btn btn-outline-light ms-2">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">Dashboard Administrador</h1>
                        <p class="text-muted mb-0">Panel de control y estadísticas del sistema</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Exportar Reporte
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nueva Propiedad
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Principales -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Propiedades Totales
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_properties']) ?></div>
                                <div class="text-xs text-success">
                                    <i class="fas fa-arrow-up"></i> +<?= $stats['properties_this_month'] ?> este mes
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-home fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Ventas Totales
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($stats['total_sales']) ?></div>
                                <div class="text-xs text-success">
                                    <i class="fas fa-arrow-up"></i> $<?= number_format($stats['sales_this_month']) ?> este mes
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Usuarios Activos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($stats['total_clients'] + $stats['total_agents']) ?></div>
                                <div class="text-xs text-info">
                                    <?= $stats['total_agents'] ?> agentes, <?= $stats['total_clients'] ?> clientes
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Citas Pendientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_appointments'] - $stats['completed_appointments'] ?></div>
                                <div class="text-xs text-warning">
                                    <?= $stats['completed_appointments'] ?> completadas
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y Contenido Principal -->
        <div class="row">
            <!-- Gráfico de Ventas -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Ventas Mensuales</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                <div class="dropdown-header">Opciones:</div>
                                <a class="dropdown-item" href="#">Ver Detalles</a>
                                <a class="dropdown-item" href="#">Exportar</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Configurar</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Propiedades por Tipo -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Propiedades por Tipo</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="propertyTypeChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Casas
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Apartamentos
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Terrenos
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Secundario -->
        <div class="row">
            <!-- Propiedades Recientes -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Propiedades Recientes</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Propiedad</th>
                                        <th>Agente</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentProperties as $property): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($property['title']) ?></td>
                                        <td><?= htmlspecialchars($property['agent']) ?></td>
                                        <td>$<?= number_format($property['price']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $property['status'] === 'Activa' ? 'success' : 'warning' ?>">
                                                <?= $property['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/properties/detail/<?= $property['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usuarios Recientes -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Usuarios Recientes</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['type'] === 'Agente' ? 'primary' : 'info' ?>">
                                                <?= $user['type'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $user['status'] === 'Activo' ? 'success' : 'warning' ?>">
                                                <?= $user['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/user/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes Recientes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Reportes Recientes</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentReports as $report): ?>
                                    <tr>
                                        <td>#<?= $report['id'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $report['type'] === 'Irregularidad' ? 'danger' : ($report['type'] === 'Sugerencia' ? 'info' : 'warning') ?>">
                                                <?= $report['type'] ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($report['description']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $report['status'] === 'Pendiente' ? 'warning' : ($report['status'] === 'Resuelto' ? 'success' : 'info') ?>">
                                                <?= $report['status'] ?>
                                            </span>
                                        </td>
                                        <td><?= $report['date'] ?></td>
                                        <td>
                                            <a href="/report/<?= $report['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
</body>
</html> 