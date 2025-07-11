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

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                            <h2 class="fw-bold">Crear Cuenta</h2>
                            <p class="text-muted">Únete a PropEasy y encuentra tu hogar ideal</p>
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

                        <form id="registerForm" method="POST" action="/auth/processRegister">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nombre Completo
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           placeholder="Tu nombre completo" required>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu nombre completo.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="tu@email.com" required>
                                    <div class="invalid-feedback">
                                        Por favor ingresa un correo electrónico válido.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">
                                        <i class="fas fa-users me-2"></i>Tipo de Usuario
                                    </label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Selecciona tu tipo</option>
                                        <option value="client">Cliente (Comprar/Vender)</option>
                                        <option value="agent">Agente Inmobiliario</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor selecciona tu tipo de usuario.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="(809) 123-4567">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Mínimo 6 caracteres" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        La contraseña debe tener al menos 6 caracteres.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Repite tu contraseña" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        Las contraseñas no coinciden.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>Dirección
                                </label>
                                <textarea class="form-control" id="address" name="address" rows="2" 
                                          placeholder="Tu dirección completa"></textarea>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="/terms" class="text-decoration-none">Términos y Condiciones</a> 
                                    y la <a href="/privacy" class="text-decoration-none">Política de Privacidad</a>
                                </label>
                                <div class="invalid-feedback">
                                    Debes aceptar los términos y condiciones.
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="newsletter">
                                <label class="form-check-label" for="newsletter">
                                    Quiero recibir notificaciones sobre nuevas propiedades
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes una cuenta?</p>
                            <a href="/auth/login" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <a href="/" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>Volver al inicio
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