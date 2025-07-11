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
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-4 text-center">
                            <i class="fas fa-check-circle me-2"></i>Validar Propiedad
                        </h2>
                        <div class="mb-4">
                            <h4><?= htmlspecialchars($propiedad['titulo']) ?></h4>
                            <p><strong>Tipo:</strong> <?= htmlspecialchars($propiedad['tipo']) ?> | <strong>Precio:</strong> $<?= number_format($propiedad['precio'], 2) ?></p>
                            <p><strong>Ubicación:</strong> <?= htmlspecialchars($propiedad['ubicacion']) ?>, <?= htmlspecialchars($propiedad['ciudad']) ?></p>
                            <p><strong>Descripción:</strong> <?= htmlspecialchars($propiedad['descripcion']) ?></p>
                        </div>
                        <form method="POST" action="/properties/processValidate">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($propiedad['id']) ?>">
                            <div class="mb-3">
                                <label for="token" class="form-label">Token de Validación</label>
                                <input type="text" class="form-control" id="token" name="token" required placeholder="Ingresa el token proporcionado por el cliente">
                                <div class="form-text">El cliente debe proporcionarte el token generado al enviar la propuesta.</div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check"></i> Validar y Publicar
                                </button>
                            </div>
                        </form>
                        <a href="/properties/pending" class="btn btn-link mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Volver al listado de pendientes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 