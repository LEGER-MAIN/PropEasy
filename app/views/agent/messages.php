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
                        <h1 class="h3 mb-0">Mis Mensajes</h1>
                        <p class="text-muted mb-0">Gestiona la comunicación con tus clientes</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="showNewMessageModal()">
                            <i class="fas fa-plus me-2"></i>Nuevo Mensaje
                        </button>
                        <button class="btn btn-primary" onclick="exportMessages()">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Mensajes -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Mensajes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    No Leídos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope-open fa-2x text-gray-300"></i>
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
                                    Respondidos
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">142</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-reply fa-2x text-gray-300"></i>
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
                                    Tiempo Promedio Respuesta
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">2.5h</div>
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
                                <label for="searchInput" class="form-label">Buscar Mensaje</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por cliente, propiedad...">
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">Estado</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="all">Todos los Estados</option>
                                    <option value="unread">No Leídos</option>
                                    <option value="read">Leídos</option>
                                    <option value="replied">Respondidos</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="dateFilter" class="form-label">Fecha</label>
                                <select class="form-select" id="dateFilter">
                                    <option value="all">Todas las Fechas</option>
                                    <option value="today">Hoy</option>
                                    <option value="week">Esta Semana</option>
                                    <option value="month">Este Mes</option>
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

        <!-- Lista de Mensajes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lista de Mensajes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Propiedad</th>
                                        <th>Mensaje</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message): ?>
                                    <tr data-message-id="<?= $message['id'] ?>" class="<?= $message['unread'] ? 'table-warning' : '' ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <?= strtoupper(substr($message['client'], 0, 1)) ?>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($message['client']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($message['email']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($message['property']) ?></div>
                                                <small class="text-muted">ID: <?= $message['id'] ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="message-preview">
                                                <div class="fw-bold"><?= htmlspecialchars(substr($message['message'], 0, 50)) ?>...</div>
                                                <small class="text-muted"><?= htmlspecialchars($message['message']) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold"><?= $message['date'] ?></div>
                                                <small class="text-muted">Hace 2 horas</small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($message['unread']): ?>
                                                <span class="badge bg-danger">No Leído</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Leído</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="viewMessage(<?= $message['id'] ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success" onclick="replyMessage(<?= $message['id'] ?>)">
                                                    <i class="fas fa-reply"></i>
                                                </button>
                                                <button class="btn btn-outline-info" onclick="showMessageHistory(<?= $message['id'] ?>)">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteMessage(<?= $message['id'] ?>)">
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

        <!-- Paginación -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Navegación de mensajes">
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

    <!-- Modal de Ver Mensaje -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ver Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="messageContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="replyFromModal()">Responder</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Responder Mensaje -->
    <div class="modal fade" id="replyMessageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Responder Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="replyForm">
                        <div class="mb-3">
                            <label for="replyTo" class="form-label">Para</label>
                            <input type="email" class="form-control" id="replyTo" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="replySubject" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="replySubject" value="Re: Consulta sobre propiedad">
                        </div>
                        <div class="mb-3">
                            <label for="replyMessage" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="replyMessage" rows="6" placeholder="Escribe tu respuesta..."></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendCopy" checked>
                                <label class="form-check-label" for="sendCopy">
                                    Enviar copia a mi email
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="sendReply()">Enviar Respuesta</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Historial de Mensajes -->
    <div class="modal fade" id="messageHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Historial de Mensajes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="messageHistoryContent">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/agent-dashboard.js"></script>
    <script>
        // Funcionalidades específicas para la página de mensajes
        let currentMessageId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Filtros
            document.getElementById('searchInput').addEventListener('input', function() {
                filterMessages();
            });

            document.getElementById('statusFilter').addEventListener('change', function() {
                filterMessages();
            });

            document.getElementById('dateFilter').addEventListener('change', function() {
                filterMessages();
            });
        });

        function filterMessages() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const client = row.querySelector('h6').textContent.toLowerCase();
                const property = row.querySelector('.fw-bold').textContent.toLowerCase();
                const message = row.querySelector('.message-preview .fw-bold').textContent.toLowerCase();
                const status = row.querySelector('.badge').textContent;
                
                const matchesSearch = client.includes(searchTerm) || 
                                    property.includes(searchTerm) || 
                                    message.includes(searchTerm);
                const matchesStatus = statusFilter === 'all' || 
                                    (statusFilter === 'unread' && status === 'No Leído') ||
                                    (statusFilter === 'read' && status === 'Leído');
                
                row.style.display = matchesSearch && matchesStatus ? 'table-row' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('dateFilter').value = 'all';
            filterMessages();
        }

        function viewMessage(messageId) {
            currentMessageId = messageId;
            const modal = new bootstrap.Modal(document.getElementById('viewMessageModal'));
            const content = document.getElementById('messageContent');
            
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
                    <div class="message-detail">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>De:</strong> María Silva (maria.silva@email.com)
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Fecha:</strong> 15/12/2024 - 14:30
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>Propiedad:</strong> Casa Moderna en Las Condes
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>Mensaje:</strong>
                                <div class="border rounded p-3 mt-2 bg-light">
                                    Hola, me interesa ver la propiedad. ¿Podríamos agendar una visita? 
                                    Me gustaría conocer más detalles sobre las características de la casa 
                                    y el barrio. ¿Está disponible para visitas este fin de semana?
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>Información del Cliente:</strong>
                                <ul class="list-unstyled mt-2">
                                    <li><i class="fas fa-phone me-2"></i>+56 9 8765 4321</li>
                                    <li><i class="fas fa-map-marker-alt me-2"></i>Santiago, Chile</li>
                                    <li><i class="fas fa-user me-2"></i>Cliente desde: Noviembre 2024</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
                
                // Marcar como leído
                markMessageAsRead(messageId);
            }, 1000);
        }

        function replyMessage(messageId) {
            currentMessageId = messageId;
            const modal = new bootstrap.Modal(document.getElementById('replyMessageModal'));
            
            // Obtener datos del mensaje
            const row = document.querySelector(`[data-message-id="${messageId}"]`);
            const email = row.querySelector('.text-muted').textContent;
            
            document.getElementById('replyTo').value = email;
            modal.show();
        }

        function replyFromModal() {
            const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewMessageModal'));
            viewModal.hide();
            
            setTimeout(() => {
                replyMessage(currentMessageId);
            }, 300);
        }

        function sendReply() {
            const to = document.getElementById('replyTo').value;
            const subject = document.getElementById('replySubject').value;
            const message = document.getElementById('replyMessage').value;
            const sendCopy = document.getElementById('sendCopy').checked;

            if (!message.trim()) {
                showToast('Por favor escribe un mensaje', 'warning');
                return;
            }

            // Simulación de envío
            showToast('Enviando respuesta...', 'info');
            
            setTimeout(() => {
                showToast('Respuesta enviada exitosamente', 'success');
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('replyMessageModal'));
                modal.hide();

                // Limpiar formulario
                document.getElementById('replyForm').reset();
                
                // Actualizar estado del mensaje
                const row = document.querySelector(`[data-message-id="${currentMessageId}"]`);
                if (row) {
                    row.classList.remove('table-warning');
                    const badge = row.querySelector('.badge');
                    badge.className = 'badge bg-success';
                    badge.textContent = 'Respondido';
                }
            }, 2000);
        }

        function showMessageHistory(messageId) {
            const modal = new bootstrap.Modal(document.getElementById('messageHistoryModal'));
            const content = document.getElementById('messageHistoryContent');
            
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
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>Mensaje recibido</h6>
                                <p class="text-muted">15/12/2024 - 14:30</p>
                                <p>Cliente solicitó información sobre la propiedad.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Respuesta enviada</h6>
                                <p class="text-muted">15/12/2024 - 15:45</p>
                                <p>Envié información detallada y propuse agendar visita.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Mensaje de seguimiento</h6>
                                <p class="text-muted">16/12/2024 - 10:00</p>
                                <p>Recordatorio sobre la visita programada.</p>
                            </div>
                        </div>
                    </div>
                `;
            }, 1000);
        }

        function deleteMessage(messageId) {
            if (confirm('¿Eliminar este mensaje? Esta acción no se puede deshacer.')) {
                const row = document.querySelector(`[data-message-id="${messageId}"]`);
                if (row) {
                    row.remove();
                    showToast('Mensaje eliminado exitosamente', 'success');
                }
            }
        }

        function markMessageAsRead(messageId) {
            const row = document.querySelector(`[data-message-id="${messageId}"]`);
            if (row) {
                row.classList.remove('table-warning');
                const badge = row.querySelector('.badge');
                if (badge.textContent === 'No Leído') {
                    badge.className = 'badge bg-success';
                    badge.textContent = 'Leído';
                }
            }
        }

        function showNewMessageModal() {
            showToast('Funcionalidad de nuevo mensaje en desarrollo', 'info');
        }

        function exportMessages() {
            showToast('Exportando mensajes...', 'info');
            // Simulación de exportación
            setTimeout(() => {
                showToast('Mensajes exportados exitosamente', 'success');
            }, 2000);
        }
    </script>

    <style>
        .message-preview {
            max-width: 300px;
        }

        .message-preview .fw-bold {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

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

        .message-detail {
            font-size: 14px;
        }
    </style>
</body>
</html> 