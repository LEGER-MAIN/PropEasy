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
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-4 text-center">
                            <i class="fas fa-plus-circle me-2"></i>Publicar Nueva Propiedad
                        </h2>
                        <form method="POST" action="/properties/processPublish" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="titulo" class="form-label">Título *</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipo" class="form-label">Tipo *</label>
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <option value="">Selecciona...</option>
                                        <option value="casa">Casa</option>
                                        <option value="apartamento">Apartamento</option>
                                        <option value="terreno">Terreno</option>
                                        <option value="local">Local Comercial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="precio" class="form-label">Precio *</label>
                                    <input type="number" class="form-control" id="precio" name="precio" min="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="habitaciones" class="form-label">Habitaciones</label>
                                    <input type="number" class="form-control" id="habitaciones" name="habitaciones" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="banos" class="form-label">Baños</label>
                                    <input type="number" class="form-control" id="banos" name="banos" min="0">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="area" class="form-label">Área total (m²)</label>
                                    <input type="number" class="form-control" id="area" name="area" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="superficie_construida" class="form-label">Superficie construida (m²)</label>
                                    <input type="number" class="form-control" id="superficie_construida" name="superficie_construida" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="parqueos" class="form-label">Parqueos</label>
                                    <input type="number" class="form-control" id="parqueos" name="parqueos" min="0">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="ciudad" class="form-label">Ciudad *</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="sector" class="form-label">Sector</label>
                                    <input type="text" class="form-control" id="sector" name="sector">
                                </div>
                                <div class="col-md-4">
                                    <label for="direccion" class="form-label">Dirección exacta *</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Características</label>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="caracteristicas[]" value="Piscina" id="caracPiscina">
                                            <label class="form-check-label" for="caracPiscina"><i class="fas fa-swimming-pool me-1"></i>Piscina</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="caracteristicas[]" value="Terraza" id="caracTerraza">
                                            <label class="form-check-label" for="caracTerraza"><i class="fas fa-umbrella-beach me-1"></i>Terraza</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="caracteristicas[]" value="Seguridad" id="caracSeguridad">
                                            <label class="form-check-label" for="caracSeguridad"><i class="fas fa-shield-alt me-1"></i>Seguridad</label>
                                        </div>
                                    </div>
                                    <!-- Puedes agregar más características aquí -->
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="imagenes" class="form-label">Imágenes</label>
                                <input type="file" class="form-control" id="imagenes" name="imagenes[]" multiple accept="image/*">
                                <div class="form-text">Puedes subir varias imágenes (JPG, PNG, etc.)</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="video_url" class="form-label">Video tour (URL)</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://...">
                                </div>
                                <div class="col-md-6">
                                    <label for="plano_url" class="form-label">Plano/Documento (URL)</label>
                                    <input type="url" class="form-control" id="plano_url" name="plano_url" placeholder="https://...">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="latitud" class="form-label">Latitud</label>
                                    <input type="text" class="form-control" id="latitud" name="latitud" placeholder="Ej: 18.4861">
                                </div>
                                <div class="col-md-6">
                                    <label for="longitud" class="form-label">Longitud</label>
                                    <input type="text" class="form-control" id="longitud" name="longitud" placeholder="Ej: -69.9312">
                                </div>
                            </div>
                            <!-- Si el usuario es admin, mostrar selección de agente encargado -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="mb-3">
                                <label for="agente_id" class="form-label">Agente Encargado</label>
                                <select class="form-select" id="agente_id" name="agente_id">
                                    <option value="">Selecciona un agente...</option>
                                    <!-- Aquí deberías cargar los agentes desde la base de datos -->
                                </select>
                            </div>
                            <?php endif; ?>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Propuesta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 