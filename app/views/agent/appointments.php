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
                        <h1 class="h3 mb-0">Mi Agenda</h1>
                        <p class="text-muted mb-0">Gestiona tus citas y visitas</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="showNewAppointmentModal()">
                            <i class="fas fa-plus me-2"></i>Nueva Cita
                        </button>
                        <button class="btn btn-primary" onclick="exportAppointments()">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Citas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Citas Hoy
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                    Confirmadas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                    Pendientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                    Esta Semana
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
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
                            <div class="col-md-3">
                                <label for="dateFilter" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="dateFilter">
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">Estado</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="all">Todos los Estados</option>
                                    <option value="Confirmada">Confirmada</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Cancelada">Cancelada</option>
                                    <option value="Completada">Completada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="clientFilter" class="form-label">Cliente</label>
                                <select class="form-select" id="clientFilter">
                                    <option value="all">Todos los Clientes</option>
                                    <option value="Carlos Rodríguez">Carlos Rodríguez</option>
                                    <option value="Ana Martínez">Ana Martínez</option>
                                    <option value="Luis González">Luis González</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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

        <!-- Vista de Citas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Lista de Citas</h5>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="viewMode" id="listView" checked>
                                <label class="btn btn-outline-primary" for="listView">
                                    <i class="fas fa-list"></i> Lista
                                </label>
                                <input type="radio" class="btn-check" name="viewMode" id="calendarView">
                                <label class="btn btn-outline-primary" for="calendarView">
                                    <i class="fas fa-calendar"></i> Calendario
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Vista en Lista -->
                        <div id="listViewContainer">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha y Hora</th>
                                            <th>Cliente</th>
                                            <th>Propiedad</th>
                                            <th>Estado</th>
                                            <th>Notas</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($appointments as $appointment): ?>
                                        <tr data-appointment-id="<?= $appointment['id'] ?>">
                                            <td>
                                                <div>
                                                    <div class="fw-bold"><?= $appointment['date'] ?></div>
                                                    <div class="text-muted"><?= $appointment['time'] ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-3">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 35px; height: 35px;">
                                                            <?= strtoupper(substr($appointment['client'], 0, 1)) ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($appointment['client']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($appointment['property']) ?></div>
                                                    <small class="text-muted">ID: <?= $appointment['id'] ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $appointment['status'] === 'Confirmada' ? 'success' : 'warning' ?>">
                                                    <?= $appointment['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= htmlspecialchars($appointment['notes']) ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="viewAppointment(<?= $appointment['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary" onclick="editAppointment(<?= $appointment['id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <?php if ($appointment['status'] === 'Pendiente'): ?>
                                                    <button class="btn btn-outline-success" onclick="confirmAppointment(<?= $appointment['id'] ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-outline-danger" onclick="cancelAppointment(<?= $appointment['id'] ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Vista en Calendario -->
                        <div id="calendarViewContainer" class="d-none">
                            <div class="calendar-wrapper">
                                <div class="calendar-header">
                                    <button class="btn btn-outline-primary" onclick="previousMonth()">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <h5 id="currentMonth">Diciembre 2024</h5>
                                    <button class="btn btn-outline-primary" onclick="nextMonth()">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="calendar-grid">
                                    <div class="calendar-days">
                                        <div>Dom</div>
                                        <div>Lun</div>
                                        <div>Mar</div>
                                        <div>Mié</div>
                                        <div>Jue</div>
                                        <div>Vie</div>
                                        <div>Sáb</div>
                                    </div>
                                    <div class="calendar-dates" id="calendarDates">
                                        <!-- Fechas generadas dinámicamente -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximas Citas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Próximas Citas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach (array_slice($appointments, 0, 3) as $appointment): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card border-<?= $appointment['status'] === 'Confirmada' ? 'success' : 'warning' ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="card-title"><?= htmlspecialchars($appointment['client']) ?></h6>
                                                <p class="card-text text-muted"><?= htmlspecialchars($appointment['property']) ?></p>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= $appointment['date'] ?> a las <?= $appointment['time'] ?>
                                                    </small>
                                                </p>
                                            </div>
                                            <span class="badge bg-<?= $appointment['status'] === 'Confirmada' ? 'success' : 'warning' ?>">
                                                <?= $appointment['status'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Nueva Cita -->
    <div class="modal fade" id="newAppointmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        <div class="mb-3">
                            <label for="appointmentClient" class="form-label">Cliente</label>
                            <select class="form-select" id="appointmentClient" required>
                                <option value="">Seleccionar cliente</option>
                                <option value="Carlos Rodríguez">Carlos Rodríguez</option>
                                <option value="Ana Martínez">Ana Martínez</option>
                                <option value="Luis González">Luis González</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentProperty" class="form-label">Propiedad</label>
                            <select class="form-select" id="appointmentProperty" required>
                                <option value="">Seleccionar propiedad</option>
                                <option value="Casa Moderna en Las Condes">Casa Moderna en Las Condes</option>
                                <option value="Departamento en Providencia">Departamento en Providencia</option>
                                <option value="Casa Familiar en Ñuñoa">Casa Familiar en Ñuñoa</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointmentDate" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="appointmentDate" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointmentTime" class="form-label">Hora</label>
                                    <input type="time" class="form-control" id="appointmentTime" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentNotes" class="form-label">Notas</label>
                            <textarea class="form-control" id="appointmentNotes" rows="3" placeholder="Detalles de la cita..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveAppointment()">Guardar Cita</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/agent-dashboard.js"></script>
    <script>
        // Funcionalidades específicas para la página de citas
        document.addEventListener('DOMContentLoaded', function() {
            // Cambio de vista
            document.getElementById('listView').addEventListener('change', function() {
                document.getElementById('listViewContainer').classList.remove('d-none');
                document.getElementById('calendarViewContainer').classList.add('d-none');
            });

            document.getElementById('calendarView').addEventListener('change', function() {
                document.getElementById('listViewContainer').classList.add('d-none');
                document.getElementById('calendarViewContainer').classList.remove('d-none');
                generateCalendar();
            });

            // Filtros
            document.getElementById('dateFilter').addEventListener('change', function() {
                filterAppointments();
            });

            document.getElementById('statusFilter').addEventListener('change', function() {
                filterAppointments();
            });

            document.getElementById('clientFilter').addEventListener('change', function() {
                filterAppointments();
            });

            // Establecer fecha actual
            document.getElementById('dateFilter').value = new Date().toISOString().split('T')[0];
        });

        function filterAppointments() {
            const dateFilter = document.getElementById('dateFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const clientFilter = document.getElementById('clientFilter').value;

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const date = row.querySelector('td:first-child .fw-bold').textContent;
                const status = row.querySelector('.badge').textContent;
                const client = row.querySelector('h6').textContent;
                
                const matchesDate = !dateFilter || date === dateFilter;
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                const matchesClient = clientFilter === 'all' || client === clientFilter;
                
                row.style.display = matchesDate && matchesStatus && matchesClient ? 'table-row' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('dateFilter').value = '';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('clientFilter').value = 'all';
            filterAppointments();
        }

        function showNewAppointmentModal() {
            const modal = new bootstrap.Modal(document.getElementById('newAppointmentModal'));
            modal.show();
        }

        function saveAppointment() {
            const client = document.getElementById('appointmentClient').value;
            const property = document.getElementById('appointmentProperty').value;
            const date = document.getElementById('appointmentDate').value;
            const time = document.getElementById('appointmentTime').value;
            const notes = document.getElementById('appointmentNotes').value;

            if (!client || !property || !date || !time) {
                showToast('Por favor completa todos los campos requeridos', 'warning');
                return;
            }

            // Simulación de guardado
            showToast('Cita guardada exitosamente', 'success');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('newAppointmentModal'));
            modal.hide();

            // Limpiar formulario
            document.getElementById('appointmentForm').reset();
        }

        function viewAppointment(appointmentId) {
            showToast(`Viendo cita ID: ${appointmentId}`, 'info');
        }

        function editAppointment(appointmentId) {
            showToast(`Editando cita ID: ${appointmentId}`, 'info');
        }

        function confirmAppointment(appointmentId) {
            if (confirm('¿Confirmar esta cita?')) {
                // Simulación de confirmación
                const row = document.querySelector(`[data-appointment-id="${appointmentId}"]`);
                const badge = row.querySelector('.badge');
                badge.className = 'badge bg-success';
                badge.textContent = 'Confirmada';
                
                showToast('Cita confirmada exitosamente', 'success');
            }
        }

        function cancelAppointment(appointmentId) {
            if (confirm('¿Cancelar esta cita?')) {
                // Simulación de cancelación
                const row = document.querySelector(`[data-appointment-id="${appointmentId}"]`);
                const badge = row.querySelector('.badge');
                badge.className = 'badge bg-danger';
                badge.textContent = 'Cancelada';
                
                showToast('Cita cancelada exitosamente', 'success');
            }
        }

        function exportAppointments() {
            showToast('Exportando citas...', 'info');
            // Simulación de exportación
            setTimeout(() => {
                showToast('Citas exportadas exitosamente', 'success');
            }, 2000);
        }

        // Funciones del calendario
        function generateCalendar() {
            const calendarDates = document.getElementById('calendarDates');
            const currentDate = new Date();
            const currentMonth = currentDate.getMonth();
            const currentYear = currentDate.getFullYear();
            
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
            
            let calendarHTML = '';
            
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                const isCurrentMonth = date.getMonth() === currentMonth;
                const isToday = date.toDateString() === currentDate.toDateString();
                
                calendarHTML += `
                    <div class="calendar-date ${isCurrentMonth ? '' : 'other-month'} ${isToday ? 'today' : ''}">
                        <div class="date-number">${date.getDate()}</div>
                        <div class="appointment-indicator"></div>
                    </div>
                `;
            }
            
            calendarDates.innerHTML = calendarHTML;
            document.getElementById('currentMonth').textContent = `${getMonthName(currentMonth)} ${currentYear}`;
        }

        function getMonthName(month) {
            const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                           'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            return months[month];
        }

        function previousMonth() {
            // Implementar navegación al mes anterior
            showToast('Navegando al mes anterior', 'info');
        }

        function nextMonth() {
            // Implementar navegación al mes siguiente
            showToast('Navegando al mes siguiente', 'info');
        }
    </script>

    <style>
        .calendar-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-grid {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .calendar-days > div {
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-right: 1px solid #dee2e6;
        }

        .calendar-days > div:last-child {
            border-right: none;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-date {
            min-height: 80px;
            padding: 5px;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            position: relative;
        }

        .calendar-date:nth-child(7n) {
            border-right: none;
        }

        .calendar-date.other-month {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .calendar-date.today {
            background-color: #e3f2fd;
        }

        .date-number {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .appointment-indicator {
            width: 8px;
            height: 8px;
            background-color: #007bff;
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
            right: 5px;
        }

        .avatar {
            width: 35px;
            height: 35px;
        }
    </style>
</body>
</html> 