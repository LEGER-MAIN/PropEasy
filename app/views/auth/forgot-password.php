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
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-home me-2"></i>PropEasy
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-key fa-3x text-primary mb-3"></i>
                            <h2 class="fw-bold">Recuperar Contraseña</h2>
                            <p class="text-muted">Ingresa tu email para recibir un enlace de recuperación</p>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form id="forgotPasswordForm" method="POST" action="/auth/processForgotPassword">
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                       placeholder="tu@email.com" required>
                                <div class="form-text">
                                    Te enviaremos un enlace para restablecer tu contraseña.
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Enlace
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <a href="/auth/login" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>Volver al login
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="/" class="text-decoration-none">
                                <i class="fas fa-home me-2"></i>Ir al inicio
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
    <script src="/assets/js/auth.js"></script>
</body>
</html> 