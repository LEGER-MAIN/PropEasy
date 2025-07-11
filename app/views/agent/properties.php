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
</head>
<body class="bg-light">
    <?php include __DIR__ . '/_navigation.php'; ?>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">Mis Propiedades</h1>
                        <p class="text-muted mb-0">Gestiona todas tus propiedades inmobiliarias</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="showNewPropertyModal()">
                            <i class="fas fa-plus me-2"></i>Nueva Propiedad
                        </button>
                        <button class="btn btn-primary" onclick="exportProperties()">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Avanzados y Búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Filtros y Búsqueda</h6>
                            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                                <i class="fas fa-filter me-1"></i>Filtros Avanzados
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros Básicos -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="searchInput" class="form-label">Buscar Propiedad</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Título, dirección, descripción...">
                            </div>
                            <div class="col-md-2">
                                <label for="statusFilter" class="form-label">Estado</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">Todos</option>
                                    <option value="activa">Activa</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="vendida">Vendida</option>
                                    <option value="rentada">Rentada</option>
                                    <option value="retirada">Retirada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="typeFilter" class="form-label">Tipo</label>
                                <select class="form-select" id="typeFilter">
                                    <option value="">Todos</option>
                                    <option value="casa">Casa</option>
                                    <option value="departamento">Departamento</option>
                                    <option value="oficina">Oficina</option>
                                    <option value="terreno">Terreno</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="orderBy" class="form-label">Ordenar por</label>
                                <select class="form-select" id="orderBy">
                                    <option value="fecha_creacion">Fecha Creación</option>
                                    <option value="fecha_actualizacion">Última Actualización</option>
                                    <option value="precio">Precio</option>
                                    <option value="titulo">Título</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-primary" onclick="applyFilters()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros Avanzados (Colapsable) -->
                        <div class="collapse" id="advancedFilters">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="priceMin" class="form-label">Precio Mínimo</label>
                                    <input type="number" class="form-control" id="priceMin" placeholder="Desde $">
                                </div>
                                <div class="col-md-3">
                                    <label for="priceMax" class="form-label">Precio Máximo</label>
                                    <input type="number" class="form-control" id="priceMax" placeholder="Hasta $">
                                </div>
                                <div class="col-md-2">
                                    <label for="orderDir" class="form-label">Dirección</label>
                                    <select class="form-select" id="orderDir">
                                        <option value="DESC">Descendente</option>
                                        <option value="ASC">Ascendente</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="limitFilter" class="form-label">Mostrar</label>
                                    <select class="form-select" id="limitFilter">
                                        <option value="10">10 propiedades</option>
                                        <option value="20" selected>20 propiedades</option>
                                        <option value="50">50 propiedades</option>
                                        <option value="100">100 propiedades</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-info w-100" onclick="exportProperties()">
                                        <i class="fas fa-download me-1"></i>Exportar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Masivas -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">
                                        Seleccionar todo
                                    </label>
                                </div>
                                <span id="selectedCount" class="text-muted">0 seleccionadas</span>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="bulkAction" style="width: auto;">
                                    <option value="">Acciones masivas...</option>
                                    <option value="activate">Activar seleccionadas</option>
                                    <option value="deactivate">Retirar seleccionadas</option>
                                    <option value="mark_sold">Marcar como vendidas</option>
                                </select>
                                <button class="btn btn-primary btn-sm" onclick="executeBulkAction()" disabled id="executeBulkBtn">
                                    <i class="fas fa-play me-1"></i>Ejecutar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista de Propiedades -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Lista de Propiedades</h5>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                                <label class="btn btn-outline-primary" for="gridView">
                                    <i class="fas fa-th"></i>
                                </label>
                                <input type="radio" class="btn-check" name="viewMode" id="listView">
                                <label class="btn btn-outline-primary" for="listView">
                                    <i class="fas fa-list"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Vista en Cuadrícula -->
                        <div id="gridViewContainer" class="row">
                            <?php foreach ($properties as $property): ?>
                            <div class="col-lg-4 col-md-6 mb-4 property-card" data-property-id="<?= $property['id'] ?>">
                                <div class="card h-100 shadow-sm">
                                    <div class="position-relative">
                                        <!-- Checkbox de selección -->
                                        <div class="position-absolute top-0 start-0 m-2 z-index-10">
                                            <div class="form-check">
                                                <input class="form-check-input property-checkbox" type="checkbox" 
                                                       value="<?= $property['id'] ?>" id="check-<?= $property['id'] ?>">
                                            </div>
                                        </div>
                                        
                                        <img src="<?= htmlspecialchars($property['image']) ?>" 
                                             class="card-img-top" 
                                             alt="<?= htmlspecialchars($property['title']) ?>"
                                             style="height: 200px; object-fit: cover;"
                                             onerror="this.src='/assets/img/property-default.svg'">
                                        
                                        <!-- Estado de la propiedad -->
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <div class="dropdown">
                                                <span class="badge bg-<?= getStatusBadgeClass($property['status']) ?> dropdown-toggle" 
                                                      data-bs-toggle="dropdown" style="cursor: pointer;">
                                                    <?= ucfirst($property['status']) ?>
                                                </span>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(<?= $property['id'] ?>, 'activa')">
                                                        <i class="fas fa-check text-success me-1"></i>Activar
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(<?= $property['id'] ?>, 'vendida')">
                                                        <i class="fas fa-handshake text-warning me-1"></i>Marcar Vendida
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(<?= $property['id'] ?>, 'rentada')">
                                                        <i class="fas fa-key text-info me-1"></i>Marcar Rentada
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(<?= $property['id'] ?>, 'retirada')">
                                                        <i class="fas fa-pause text-secondary me-1"></i>Retirar
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <!-- Contador de imágenes -->
                                        <?php if (count($property['images']) > 1): ?>
                                        <div class="position-absolute bottom-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">
                                                <i class="fas fa-images me-1"></i><?= count($property['images']) ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Indicador de días en mercado -->
                                        <div class="position-absolute bottom-0 start-0 m-2">
                                            <span class="badge bg-primary bg-opacity-75">
                                                <i class="fas fa-calendar me-1"></i><?= $property['days_on_market'] ?>d
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <h6 class="card-title mb-2"><?= htmlspecialchars($property['title']) ?></h6>
                                        <p class="card-text text-primary fw-bold fs-5 mb-2">$<?= number_format((float)($property['price'] ?? 0)) ?></p>
                                        
                                        <!-- Información básica -->
                                        <div class="row text-muted small mb-2">
                                            <div class="col-4 text-center">
                                                <i class="fas fa-bed me-1"></i><?= $property['habitaciones'] ?? 0 ?>
                                            </div>
                                            <div class="col-4 text-center">
                                                <i class="fas fa-bath me-1"></i><?= $property['banos'] ?? 0 ?>
                                            </div>
                                            <div class="col-4 text-center">
                                                <i class="fas fa-ruler-combined me-1"></i><?= $property['area'] ?? 0 ?>m²
                                            </div>
                                        </div>
                                        
                                        <!-- Estadísticas -->
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <i class="fas fa-eye me-1"></i><?= $property['views'] ?> vistas
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-comment me-1"></i><?= $property['inquiries'] ?> consultas
                                            </div>
                                        </div>
                                        
                                        <!-- Ubicación -->
                                        <p class="card-text text-muted small mt-2 mb-0">
                                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($property['ubicacion']) ?>
                                        </p>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100" role="group">
                                            <button class="btn btn-outline-primary btn-sm" onclick="viewProperty(<?= $property['id'] ?>)" title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="editProperty(<?= $property['id'] ?>)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-info btn-sm" onclick="showPropertyStats(<?= $property['id'] ?>)" title="Estadísticas">
                                                <i class="fas fa-chart-bar"></i>
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" onclick="shareProperty(<?= $property['id'] ?>)" title="Compartir">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="unassignProperty(<?= $property['id'] ?>)" title="Desasignar">
                                                <i class="fas fa-unlink"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php
                        // Función helper para clases de estado
                        function getStatusBadgeClass($status) {
                            switch(strtolower($status)) {
                                case 'activa': return 'success';
                                case 'pendiente': return 'warning';
                                case 'vendida': return 'info';
                                case 'rentada': return 'primary';
                                case 'retirada': return 'secondary';
                                default: return 'secondary';
                            }
                        }
                        ?>

                        <!-- Vista en Lista -->
                        <div id="listViewContainer" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Propiedad</th>
                                            <th>Precio</th>
                                            <th>Estado</th>
                                            <th>Vistas</th>
                                            <th>Consultas</th>
                                            <th>Última Actualización</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($properties as $property): ?>
                                        <tr data-property-id="<?= $property['id'] ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="assets/img/property-<?= $property['id'] ?>.jpg" 
                                                         class="rounded me-3" 
                                                         width="50" 
                                                         height="50"
                                                         onerror="this.src='assets/img/property-default.svg'">
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($property['title']) ?></h6>
                                                        <small class="text-muted">ID: <?= $property['id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                                                                            <td class="fw-bold text-primary">$<?= number_format((float)($property['price'] ?? 0)) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $property['status'] === 'Activa' ? 'success' : 'secondary' ?>">
                                                    <?= $property['status'] ?>
                                                </span>
                                            </td>
                                            <td><?= $property['views'] ?></td>
                                            <td><?= $property['inquiries'] ?></td>
                                            <td>Hace 2 días</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="viewProperty(<?= $property['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary" onclick="editProperty(<?= $property['id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" onclick="showPropertyStats(<?= $property['id'] ?>)">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" onclick="deleteProperty(<?= $property['id'] ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
            </div>
        </div>

        <!-- Paginación -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Navegación de propiedades">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal de Estadísticas de Propiedad -->
    <div class="modal fade" id="propertyStatsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Estadísticas de la Propiedad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="propertyStatsContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="deletePropertyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres eliminar esta propiedad? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/agent-dashboard.js"></script>
    <script src="assets/js/agent-properties-advanced.js"></script>
    <script>
        // Funcionalidades específicas para la página de propiedades
        document.addEventListener('DOMContentLoaded', function() {
            // Cambio de vista
            document.getElementById('gridView').addEventListener('change', function() {
                document.getElementById('gridViewContainer').classList.remove('d-none');
                document.getElementById('listViewContainer').classList.add('d-none');
            });

            document.getElementById('listView').addEventListener('change', function() {
                document.getElementById('gridViewContainer').classList.add('d-none');
                document.getElementById('listViewContainer').classList.remove('d-none');
            });

            // Filtros
            document.getElementById('searchInput').addEventListener('input', function() {
                filterProperties();
            });

            document.getElementById('statusFilter').addEventListener('change', function() {
                filterProperties();
            });

            document.getElementById('typeFilter').addEventListener('change', function() {
                filterProperties();
            });
        });

        function filterProperties() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;

            const cards = document.querySelectorAll('.property-card');
            const rows = document.querySelectorAll('tbody tr');

            // Filtrar tarjetas (vista cuadrícula)
            cards.forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const status = card.querySelector('.badge').textContent;
                
                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                
                card.style.display = matchesSearch && matchesStatus ? 'block' : 'none';
            });

            // Filtrar filas (vista lista)
            rows.forEach(row => {
                const title = row.querySelector('h6').textContent.toLowerCase();
                const status = row.querySelector('.badge').textContent;
                
                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                
                row.style.display = matchesSearch && matchesStatus ? 'table-row' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('typeFilter').value = 'all';
            filterProperties();
        }

        function showPropertyStats(propertyId) {
            // Simulación de carga de estadísticas
            const modal = new bootstrap.Modal(document.getElementById('propertyStatsModal'));
            const content = document.getElementById('propertyStatsContent');
            
            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            `;
            
            modal.show();

            // Simular carga de datos
            setTimeout(() => {
                content.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Vistas</h6>
                            <div class="progress mb-3">
                                <div class="progress-bar" style="width: 75%">156 vistas</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Consultas</h6>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" style="width: 60%">8 consultas</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Tiempo en el Mercado</h6>
                            <p class="text-muted">45 días</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Tasa de Conversión</h6>
                            <p class="text-muted">5.1%</p>
                        </div>
                    </div>
                `;
            }, 1000);
        }

        function deleteProperty(propertyId) {
            const modal = new bootstrap.Modal(document.getElementById('deletePropertyModal'));
            modal.show();

            document.getElementById('confirmDeleteBtn').onclick = function() {
                // Simulación de eliminación
                const card = document.querySelector(`[data-property-id="${propertyId}"]`);
                if (card) {
                    card.remove();
                }
                
                modal.hide();
                showToast('Propiedad eliminada exitosamente', 'success');
            };
        }

        function exportProperties() {
            showToast('Exportando propiedades...', 'info');
            // Simulación de exportación
            setTimeout(() => {
                showToast('Propiedades exportadas exitosamente', 'success');
            }, 2000);
        }

        function showNewPropertyModal() {
            showToast('Funcionalidad de nueva propiedad en desarrollo', 'info');
        }
    </script>
</body>
</html> 