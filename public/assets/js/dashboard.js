// JavaScript para el dashboard de PropEasy

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráficos
    initializeCharts();
    
    // Configurar funcionalidades del dashboard
    setupDashboardFeatures();
    
    // Configurar notificaciones en tiempo real
    setupRealTimeNotifications();
});

// Función para inicializar gráficos
function initializeCharts() {
    // Gráfico de ventas mensuales
    const salesChartCtx = document.getElementById('salesChart');
    if (salesChartCtx) {
        const salesChart = new Chart(salesChartCtx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Ventas (RD$)',
                    data: [3200000, 4500000, 3800000, 5200000, 4800000, 6100000, 5500000, 6800000, 7200000, 8500000, 7800000, 9200000],
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de propiedades por tipo
    const propertyTypeChartCtx = document.getElementById('propertyTypeChart');
    if (propertyTypeChartCtx) {
        const propertyTypeChart = new Chart(propertyTypeChartCtx, {
            type: 'doughnut',
            data: {
                labels: ['Casas', 'Apartamentos', 'Terrenos', 'Locales'],
                datasets: [{
                    data: [45, 35, 15, 5],
                    backgroundColor: [
                        'rgb(13, 110, 253)',
                        'rgb(25, 135, 84)',
                        'rgb(13, 202, 240)',
                        'rgb(255, 193, 7)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Función para configurar funcionalidades del dashboard
function setupDashboardFeatures() {
    // Configurar botones de exportación
    setupExportButtons();
    
    // Configurar filtros de fecha
    setupDateFilters();
    
    // Configurar actualizaciones automáticas
    setupAutoRefresh();
}

// Función para configurar botones de exportación
function setupExportButtons() {
    const exportBtn = document.querySelector('.btn-outline-primary');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            exportDashboardData();
        });
    }
}

// Función para exportar datos del dashboard
function exportDashboardData() {
    // Simular exportación
    showNotification('Generando reporte de exportación...', 'info');
    
    setTimeout(() => {
        // Aquí se generaría el archivo real
        const link = document.createElement('a');
        link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(generateCSVData());
        link.download = 'dashboard_report_' + new Date().toISOString().split('T')[0] + '.csv';
        link.click();
        
        showNotification('Reporte exportado exitosamente', 'success');
    }, 2000);
}

// Función para generar datos CSV
function generateCSVData() {
    const headers = ['Métrica', 'Valor', 'Fecha'];
    const data = [
        ['Propiedades Totales', '156', new Date().toISOString().split('T')[0]],
        ['Ventas Totales', '$45,000,000', new Date().toISOString().split('T')[0]],
        ['Usuarios Activos', '242', new Date().toISOString().split('T')[0]],
        ['Citas Pendientes', '22', new Date().toISOString().split('T')[0]]
    ];
    
    return [headers, ...data].map(row => row.join(',')).join('\n');
}

// Función para configurar filtros de fecha
function setupDateFilters() {
    // Agregar filtros de fecha si no existen
    const header = document.querySelector('.d-flex.justify-content-between');
    if (header && !document.getElementById('dateFilter')) {
        const dateFilter = document.createElement('div');
        dateFilter.className = 'd-flex align-items-center gap-2';
        dateFilter.innerHTML = `
            <select class="form-select form-select-sm" id="dateFilter" style="width: auto;">
                <option value="today">Hoy</option>
                <option value="week">Esta Semana</option>
                <option value="month" selected>Este Mes</option>
                <option value="quarter">Este Trimestre</option>
                <option value="year">Este Año</option>
            </select>
        `;
        
        header.querySelector('.d-flex.gap-2').appendChild(dateFilter);
        
        // Configurar evento de cambio
        document.getElementById('dateFilter').addEventListener('change', function() {
            updateDashboardData(this.value);
        });
    }
}

// Función para actualizar datos del dashboard
function updateDashboardData(period) {
    showNotification('Actualizando datos...', 'info');
    
    // Simular actualización de datos
    setTimeout(() => {
        // Aquí se actualizarían los datos reales según el período
        console.log('Actualizando dashboard para período:', period);
        showNotification('Datos actualizados', 'success');
    }, 1000);
}

// Función para configurar actualizaciones automáticas
function setupAutoRefresh() {
    // Actualizar cada 5 minutos
    setInterval(() => {
        refreshDashboardStats();
    }, 300000); // 5 minutos
}

// Función para actualizar estadísticas del dashboard
function refreshDashboardStats() {
    // Simular actualización de estadísticas
    fetch('/propeasy/public/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            updateStatsDisplay(data);
        })
        .catch(error => {
            console.error('Error actualizando estadísticas:', error);
        });
}

// Función para actualizar display de estadísticas
function updateStatsDisplay(data) {
    // Actualizar contadores principales
    const statElements = document.querySelectorAll('.h5.mb-0.font-weight-bold');
    if (statElements.length >= 4) {
        statElements[0].textContent = number_format(data.total_properties || 156);
        statElements[1].textContent = '$' + number_format(data.total_sales || 45000000);
        statElements[2].textContent = number_format((data.total_clients || 234) + (data.total_agents || 8));
        statElements[3].textContent = (data.total_appointments || 89) - (data.completed_appointments || 67);
    }
}

// Función para configurar notificaciones en tiempo real
function setupRealTimeNotifications() {
    // Simular notificaciones en tiempo real
    setInterval(() => {
        const notifications = [
            'Nueva propiedad registrada',
            'Cita confirmada por cliente',
            'Nuevo usuario registrado',
            'Reporte de irregularidad recibido'
        ];
        
        const randomNotification = notifications[Math.floor(Math.random() * notifications.length)];
        
        // Solo mostrar ocasionalmente para no ser molesto
        if (Math.random() < 0.1) { // 10% de probabilidad
            showNotification(randomNotification, 'info');
        }
    }, 30000); // Cada 30 segundos
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Función para formatear números
function number_format(number) {
    return new Intl.NumberFormat('es-DO').format(number);
}

// Función para actualizar contadores con animación
function animateCounter(element, target, duration = 1000) {
    const start = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
    const increment = (target - start) / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = number_format(Math.floor(current));
    }, 16);
}

// Función para configurar tooltips
function setupTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Función para configurar popovers
function setupPopovers() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Inicializar tooltips y popovers cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    setupTooltips();
    setupPopovers();
}); 