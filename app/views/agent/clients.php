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
                        <h1 class="h3 mb-0">Mis Clientes</h1>
                        <p class="text-muted mb-0">Gestiona tu cartera de clientes</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="showNewClientModal()">
                            <i class="fas fa-plus me-2"></i>Nuevo Cliente
                        </button>
                        <button class="btn btn-primary" onclick="exportClients()">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Clientes -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Clientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">28</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                    Clientes Activos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">15</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                    Nuevos este Mes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-plus fa-2x text-gray-300"></i>
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
                                    Pendientes de Contacto
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="searchInput" class="form-label">Buscar Cliente</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre, email...">
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">Estado</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="all">Todos los Estados</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                    <option value="Prospecto">Prospecto</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="typeFilter" class="form-label">Tipo</label>
                                <select class="form-select" id="typeFilter">
                                    <option value="all">Todos los Tipos</option>
                                    <option value="Comprador">Comprador</option>
                                    <option value="Vendedor">Vendedor</option>
                                    <option value="Inversor">Inversor</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Clientes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lista de Clientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Contacto</th>
                                        <th>Estado</th>
                                        <th>Tipo</th>
                                        <th>Propiedades Vistas</th>
                                        <th>Último Contacto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $client): ?>
                                    <tr data-client-id="<?= $client['id'] ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <?= strtoupper(substr($client['name'], 0, 1)) ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($client['name']) ?></h6>
                                                    <small class="text-muted">ID: <?= $client['id'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($client['email']) ?></div>
                                                <div><i class="fas fa-phone me-1"></i><?= htmlspecialchars($client['phone']) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $client['status'] === 'Activo' ? 'success' : 'secondary' ?>">
                                                <?= $client['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">Comprador</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?= $client['properties_viewed'] ?> propiedades</span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= $client['last_contact'] ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="viewClient(<?= $client['id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary" onclick="editClient(<?= $client['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-success" onclick="contactClient(<?= $client['id'] ?>)">
                                                    <i class="fas fa-phone"></i>
                                                </button>
                                                <button class="btn btn-outline-info" onclick="showClientHistory(<?= $client['id'] ?>)">
                                                    <i class="fas fa-history"></i>
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

        <!-- Paginación -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Navegación de clientes">
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

    <!-- Modal de Historial del Cliente -->
    <div class="modal fade" id="clientHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Historial del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="clientHistoryContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Contacto -->
    <div class="modal fade" id="contactClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contactar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="contactForm">
                        <div class="mb-3">
                            <label for="contactType" class="form-label">Tipo de Contacto</label>
                            <select class="form-select" id="contactType" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="phone">Llamada</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="meeting">Reunión</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contactNotes" class="form-label">Notas</label>
                            <textarea class="form-control" id="contactNotes" rows="3" placeholder="Detalles del contacto..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nextAction" class="form-label">Próxima Acción</label>
                            <input type="text" class="form-control" id="nextAction" placeholder="¿Qué sigue?">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveContact()">Guardar Contacto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/agent-dashboard.js"></script>
    <script>
        // Funcionalidades específicas para la página de clientes
        document.addEventListener('DOMContentLoaded', function() {
            // Filtros
            document.getElementById('searchInput').addEventListener('input', function() {
                filterClients();
            });

            document.getElementById('statusFilter').addEventListener('change', function() {
                filterClients();
            });

            document.getElementById('typeFilter').addEventListener('change', function() {
                filterClients();
            });
        });

        function filterClients() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const name = row.querySelector('h6').textContent.toLowerCase();
                const email = row.querySelector('.fas.fa-envelope').parentElement.textContent.toLowerCase();
                const status = row.querySelector('.badge').textContent;
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                
                row.style.display = matchesSearch && matchesStatus ? 'table-row' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('typeFilter').value = 'all';
            filterClients();
        }

        function viewClient(clientId) {
            showToast(`Viendo cliente ID: ${clientId}`, 'info');
        }

        function editClient(clientId) {
            showToast(`Editando cliente ID: ${clientId}`, 'info');
        }

        function contactClient(clientId) {
            const modal = new bootstrap.Modal(document.getElementById('contactClientModal'));
            modal.show();
        }

        function showClientHistory(clientId) {
            const modal = new bootstrap.Modal(document.getElementById('clientHistoryModal'));
            const content = document.getElementById('clientHistoryContent');
            
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
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Llamada telefónica</h6>
                                <p class="text-muted">15/12/2024 - 14:30</p>
                                <p>Cliente interesado en propiedades en Las Condes. Agendó visita para mañana.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Email enviado</h6>
                                <p class="text-muted">10/12/2024 - 09:15</p>
                                <p>Envié información sobre nuevas propiedades disponibles.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6>Primer contacto</h6>
                                <p class="text-muted">05/12/2024 - 16:00</p>
                                <p>Cliente se registró a través del sitio web. Interesado en compra.</p>
                            </div>
                        </div>
                    </div>
                `;
            }, 1000);
        }

        function saveContact() {
            const contactType = document.getElementById('contactType').value;
            const notes = document.getElementById('contactNotes').value;
            const nextAction = document.getElementById('nextAction').value;

            if (!contactType) {
                showToast('Por favor selecciona un tipo de contacto', 'warning');
                return;
            }

            // Simulación de guardado
            showToast('Contacto guardado exitosamente', 'success');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('contactClientModal'));
            modal.hide();

            // Limpiar formulario
            document.getElementById('contactForm').reset();
        }

        function showNewClientModal() {
            showToast('Funcionalidad de nuevo cliente en desarrollo', 'info');
        }

        function exportClients() {
            showToast('Exportando clientes...', 'info');
            // Simulación de exportación
            setTimeout(() => {
                showToast('Clientes exportados exitosamente', 'success');
            }, 2000);
        }
    </script>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -35px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }

        .avatar {
            width: 40px;
            height: 40px;
        }
    </style>
</body>
</html> 