<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-light">
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
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-center">
            <i class="fas fa-hourglass-half me-2"></i>Propiedades Pendientes de Validación
        </h2>
        <?php if (empty($pendientes)): ?>
            <div class="alert alert-info text-center">No hay propiedades pendientes de validación.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendientes as $prop): ?>
                            <tr>
                                <td><?= htmlspecialchars($prop['id']) ?></td>
                                <td><?= htmlspecialchars($prop['titulo']) ?></td>
                                <td><?= htmlspecialchars($prop['tipo']) ?></td>
                                <td>$<?= number_format($prop['precio'], 2) ?></td>
                                <td><?= htmlspecialchars($prop['cliente_id']) ?></td>
                                <td><?= htmlspecialchars($prop['fecha_creacion']) ?></td>
                                <td>
                                    <a href="/properties/validate/<?= $prop['id'] ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-check-circle"></i> Validar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 