<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
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
                    <div class="card-body p-5 text-center">
                        <?php if ($success): ?>
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h2 class="fw-bold mb-3">¡Propuesta enviada correctamente!</h2>
                            <p class="mb-4">Un agente revisará tu propuesta y te contactará si es aprobada.</p>
                            <?php if ($token): ?>
                                <div class="alert alert-info">
                                    <strong>Token de validación:</strong><br>
                                    <span class="fw-bold"><?= htmlspecialchars($token) ?></span>
                                    <br><small>Guarda este código para seguimiento o validación.</small>
                                </div>
                            <?php endif; ?>
                            <a href="/properties" class="btn btn-primary mt-3">
                                <i class="fas fa-home me-2"></i>Volver al listado de propiedades
                            </a>
                        <?php else: ?>
                            <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                            <h2 class="fw-bold mb-3">Error al enviar la propuesta</h2>
                            <p><?= htmlspecialchars($message) ?></p>
                            <a href="/properties/publish" class="btn btn-secondary mt-3">
                                <i class="fas fa-arrow-left me-2"></i>Volver al formulario
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 