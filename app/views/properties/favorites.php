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
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .favorites-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        .no-favorites {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .no-favorites i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        .pagination-custom {
            justify-content: center;
            margin-top: 2rem;
        }
        .pagination-custom .page-link {
            border-radius: 25px;
            margin: 0 0.2rem;
            border: none;
            color: #667eea;
        }
        .pagination-custom .page-link:hover {
            background: #667eea;
            color: white;
        }
        .pagination-custom .page-item.active .page-link {
            background: #667eea;
            border-color: #667eea;
        }
        .favorite-date {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Header de favoritos -->
    <div class="favorites-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-heart me-2"></i>Mis Favoritos</h1>
                    <p class="mb-0">Propiedades que has guardado para revisar más tarde</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="/properties" class="btn btn-outline-light">
                        <i class="fas fa-search me-2"></i>Buscar más propiedades
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Estadísticas de favoritos -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="fas fa-heart text-danger mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-0"><?= $totalFavorites ?></h3>
                    <p class="text-muted mb-0">Total Favoritos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="fas fa-star text-warning mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-0"><?= $stats['recent_favorites'] ?></h3>
                    <p class="text-muted mb-0">Esta semana</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="fas fa-home text-info mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-0"><?= count($stats['favorite_types']) ?></h3>
                    <p class="text-muted mb-0">Tipos diferentes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="fas fa-chart-line text-success mb-2" style="font-size: 2rem;"></i>
                    <h3 class="mb-0"><?= $currentPage ?></h3>
                    <p class="text-muted mb-0">Página actual</p>
                </div>
            </div>
        </div>

        <!-- Tipos de propiedades favoritas -->
        <?php if (!empty($stats['favorite_types'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-card">
                    <h5><i class="fas fa-chart-pie me-2"></i>Tipos de propiedades favoritas</h5>
                    <div class="row">
                        <?php foreach ($stats['favorite_types'] as $type): ?>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-capitalize"><?= htmlspecialchars($type['tipo']) ?></span>
                                <span class="badge bg-primary"><?= $type['count'] ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Lista de propiedades favoritas -->
        <?php if (!empty($favorites)): ?>
            <?php 
            // Preparar datos para usar exactamente el mismo formato que la vista principal
            $properties = $favorites;
            
            // Formatear datos para que sean idénticos a la vista principal
            foreach ($properties as &$prop) {
                // Asegurar que el precio esté formateado igual que en la vista principal
                $prop['precio_formateado'] = '$' . number_format($prop['precio'], 2);
                
                // Procesar imágenes igual que en la vista principal
                if (isset($prop['imagenes'])) {
                    $imagenes = is_string($prop['imagenes']) ? explode(',', $prop['imagenes']) : $prop['imagenes'];
                } else {
                    $imagenes = [];
                }
                $prop['imagen_principal'] = !empty($imagenes) ? $imagenes[0] : 'assets/images/placeholder.jpg';
                
                // Asegurar que todos los campos necesarios estén presentes
                $prop['ubicacion'] = $prop['ubicacion'] ?? $prop['ciudad'] ?? 'Ubicación no especificada';
                $prop['estado'] = $prop['estado'] ?? 'activa';
                $prop['banos'] = $prop['banos'] ?? $prop['bathrooms'] ?? 'N/A';
                $prop['favorites_count'] = $prop['favorites_count'] ?? 0;
            }
            unset($prop);
            
            // Array de favoritos para el JavaScript (todas son favoritas en esta vista)
            $userFavorites = array_column($favorites, 'id');
            ?>
            
                        <!-- Usar exactamente el mismo archivo que funciona en la página principal -->
            <div id="propertiesContainer">
                <?php include __DIR__ . '/_property_cards.php'; ?>
            </div>
            
        <?php else: ?>
        <div class="no-favorites">
            <i class="fas fa-heart-broken"></i>
            <h3>No tienes propiedades favoritas</h3>
            <p>¡Explora nuestras propiedades y guarda las que más te gusten!</p>
            <a href="/properties" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Explorar Propiedades
            </a>
        </div>
        <?php endif; ?>

        <!-- Paginación -->
        <?php if (!empty($favorites) && $totalPages > 1): ?>
        <nav aria-label="Navegación de favoritos" class="mt-4">
            <ul class="pagination pagination-custom">
                <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Anterior</a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Siguiente</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        window.isUserAuthenticated = <?php echo (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated']) ? 'true' : 'false'; ?>;
        window.userFavorites = <?php echo json_encode($userFavorites ?? []); ?>;
    </script>
    <script src="assets/js/properties.js"></script>

    <!-- Modal para pedir inicio de sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    Debes iniciar sesión para agregar propiedades a tus favoritos.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="/auth/login" class="btn btn-primary">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Asegurar que los estilos de favoritos funcionen correctamente */
    .favorite-btn.active i.fas {
        color: #dc3545 !important;
    }
    .favorite-btn i.far {
        color: #dc3545;
        background: transparent;
    }
    .favorite-btn {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    </style>
</body>
</html> 