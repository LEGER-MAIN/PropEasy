/**
 * JavaScript para el Dashboard del Agente
 * Maneja la interactividad y funcionalidades específicas del agente
 */

class AgentDashboard {
    constructor() {
        this.init();
    }

    /**
     * Inicializa el dashboard del agente
     */
    init() {
        this.bindEvents();
        this.initRealTimeUpdates();
        this.initNotifications();
        this.loadInitialData();
    }

    /**
     * Vincula eventos del DOM
     */
    bindEvents() {
        // Botones de acción rápida
        document.querySelectorAll('.btn-outline-primary').forEach(btn => {
            if (btn.textContent.includes('Nueva Propiedad')) {
                btn.addEventListener('click', () => this.showNewPropertyModal());
            }
        });

        document.querySelectorAll('.btn-primary').forEach(btn => {
            if (btn.textContent.includes('Agendar Cita')) {
                btn.addEventListener('click', () => this.showAppointmentModal());
            }
        });

        // Enlaces de navegación
        document.querySelectorAll('a[href*="/properties"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.navigateToProperties();
            });
        });

        document.querySelectorAll('a[href*="/clients"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.navigateToClients();
            });
        });

        document.querySelectorAll('a[href*="/appointments"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.navigateToAppointments();
            });
        });

        document.querySelectorAll('a[href*="/messages"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.navigateToMessages();
            });
        });

        // Acciones en tablas
        this.bindTableActions();
        
        // Filtros y búsquedas
        this.bindFilters();
    }

    /**
     * Vincula acciones en las tablas
     */
    bindTableActions() {
        // Botones de acción en propiedades
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const action = btn.querySelector('i').className;
                
                if (action.includes('eye')) {
                    this.viewProperty(btn.closest('tr').dataset.id);
                } else if (action.includes('edit')) {
                    this.editProperty(btn.closest('tr').dataset.id);
                }
            });
        });

        // Mensajes no leídos
        document.querySelectorAll('.list-group-item').forEach(item => {
            if (item.querySelector('.badge.bg-danger')) {
                item.addEventListener('click', () => {
                    this.markMessageAsRead(item.dataset.messageId);
                });
            }
        });
    }

    /**
     * Vincula filtros y búsquedas
     */
    bindFilters() {
        // Filtro de estado en propiedades
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', () => {
                this.filterProperties();
            });
        }

        // Búsqueda en tiempo real
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.searchProperties(e.target.value);
                }, 300);
            });
        }
    }

    /**
     * Inicializa actualizaciones en tiempo real
     */
    initRealTimeUpdates() {
        // Actualizar estadísticas cada 30 segundos
        setInterval(() => {
            this.updateStats();
        }, 30000);

        // Actualizar mensajes cada 60 segundos
        setInterval(() => {
            this.checkNewMessages();
        }, 60000);

        // Actualizar citas cada 5 minutos
        setInterval(() => {
            this.updateAppointments();
        }, 300000);
    }

    /**
     * Inicializa notificaciones
     */
    initNotifications() {
        // Solicitar permisos de notificación
        if ('Notification' in window) {
            Notification.requestPermission();
        }

        // Configurar notificaciones push
        this.setupPushNotifications();
    }

    /**
     * Configura notificaciones push
     */
    setupPushNotifications() {
        // Simulación de notificaciones push
        // En producción, aquí se configuraría con un servicio real
        console.log('Configurando notificaciones push para el agente...');
    }

    /**
     * Carga datos iniciales
     */
    loadInitialData() {
        this.updateStats();
        this.loadRecentActivity();
    }

    /**
     * Actualiza estadísticas
     */
    async updateStats() {
        try {
            const response = await fetch('/propeasy/public/agent/stats', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateStatsDisplay(data.data);
            }
        } catch (error) {
            console.error('Error al actualizar estadísticas:', error);
        }
    }

    /**
     * Actualiza la visualización de estadísticas
     */
    updateStatsDisplay(stats) {
        // Actualizar contadores
        document.querySelectorAll('[data-stat]').forEach(element => {
            const statKey = element.dataset.stat;
            if (stats[statKey] !== undefined) {
                if (statKey.includes('commission')) {
                    element.textContent = `$${this.formatNumber(stats[statKey])}`;
                } else {
                    element.textContent = stats[statKey];
                }
            }
        });

        // Actualizar barras de progreso
        this.updateProgressBars(stats);
    }

    /**
     * Actualiza barras de progreso
     */
    updateProgressBars(stats) {
        const responseRateBar = document.querySelector('.progress-bar.bg-primary');
        if (responseRateBar) {
            responseRateBar.style.width = `${stats.response_rate}%`;
        }

        const satisfactionBar = document.querySelector('.progress-bar.bg-success');
        if (satisfactionBar) {
            satisfactionBar.style.width = `${(stats.client_satisfaction / 5) * 100}%`;
        }
    }

    /**
     * Verifica nuevos mensajes
     */
    async checkNewMessages() {
        try {
            const response = await fetch('/propeasy/public/agent/messages/new', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.newMessages > 0) {
                    this.showNewMessageNotification(data.newMessages);
                    this.updateMessageCount(data.newMessages);
                }
            }
        } catch (error) {
            console.error('Error al verificar mensajes:', error);
        }
    }

    /**
     * Muestra notificación de nuevos mensajes
     */
    showNewMessageNotification(count) {
        // Notificación del navegador
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('PropEasy - Nuevos Mensajes', {
                body: `Tienes ${count} mensaje(s) nuevo(s)`,
                icon: '/propeasy/public/assets/img/logo.png'
            });
        }

        // Notificación en la interfaz
        this.showToast(`Tienes ${count} mensaje(s) nuevo(s)`, 'info');
    }

    /**
     * Actualiza contador de mensajes
     */
    updateMessageCount(count) {
        const messageLink = document.querySelector('a[href*="/messages"]');
        if (messageLink) {
            let badge = messageLink.querySelector('.badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge bg-danger ms-1';
                messageLink.appendChild(badge);
            }
            badge.textContent = count;
        }
    }

    /**
     * Actualiza citas
     */
    async updateAppointments() {
        try {
            const response = await fetch('/propeasy/public/agent/appointments/today', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateAppointmentsDisplay(data.appointments);
            }
        } catch (error) {
            console.error('Error al actualizar citas:', error);
        }
    }

    /**
     * Actualiza visualización de citas
     */
    updateAppointmentsDisplay(appointments) {
        const container = document.querySelector('.card-body');
        if (!container) return;

        // Actualizar lista de citas
        const appointmentsList = container.querySelector('.list-group');
        if (appointmentsList) {
            appointmentsList.innerHTML = '';
            
            appointments.forEach(appointment => {
                const item = this.createAppointmentItem(appointment);
                appointmentsList.appendChild(item);
            });
        }
    }

    /**
     * Crea elemento de cita
     */
    createAppointmentItem(appointment) {
        const item = document.createElement('div');
        item.className = 'border-bottom pb-3 mb-3';
        item.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">${appointment.client}</h6>
                    <p class="text-muted mb-1 small">${appointment.property}</p>
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-calendar me-1"></i>
                        ${appointment.date} a las ${appointment.time}
                    </p>
                </div>
                <span class="badge bg-${appointment.status === 'Confirmada' ? 'success' : 'warning'}">
                    ${appointment.status}
                </span>
            </div>
        `;
        return item;
    }

    /**
     * Carga actividad reciente
     */
    async loadRecentActivity() {
        try {
            const response = await fetch('/propeasy/public/agent/activity', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateActivityDisplay(data.activity);
            }
        } catch (error) {
            console.error('Error al cargar actividad:', error);
        }
    }

    /**
     * Actualiza visualización de actividad
     */
    updateActivityDisplay(activity) {
        const container = document.querySelector('.card-body');
        if (!container) return;

        const activityList = container.querySelector('.list-group');
        if (activityList) {
            activityList.innerHTML = '';
            
            activity.forEach(item => {
                const listItem = this.createActivityItem(item);
                activityList.appendChild(listItem);
            });
        }
    }

    /**
     * Crea elemento de actividad
     */
    createActivityItem(activity) {
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.innerHTML = `
            <div>
                <i class="fas ${activity.icon} text-${activity.color} me-2"></i>
                ${activity.description}
            </div>
            <small class="text-muted">${activity.time}</small>
        `;
        return item;
    }

    /**
     * Marca mensaje como leído
     */
    async markMessageAsRead(messageId) {
        try {
            const response = await fetch('/propeasy/public/agent/message/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message_id: messageId })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.removeUnreadBadge(messageId);
                    this.showToast('Mensaje marcado como leído', 'success');
                }
            }
        } catch (error) {
            console.error('Error al marcar mensaje como leído:', error);
        }
    }

    /**
     * Remueve badge de no leído
     */
    removeUnreadBadge(messageId) {
        const messageItem = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageItem) {
            const badge = messageItem.querySelector('.badge.bg-danger');
            if (badge) {
                badge.remove();
            }
            messageItem.classList.remove('bg-light');
        }
    }

    /**
     * Filtra propiedades
     */
    filterProperties() {
        const statusFilter = document.getElementById('statusFilter');
        const status = statusFilter ? statusFilter.value : 'all';
        
        document.querySelectorAll('tbody tr').forEach(row => {
            const propertyStatus = row.querySelector('.badge').textContent;
            
            if (status === 'all' || propertyStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    /**
     * Busca propiedades
     */
    searchProperties(query) {
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const title = row.querySelector('td:first-child').textContent.toLowerCase();
            const matches = title.includes(query.toLowerCase());
            row.style.display = matches ? '' : 'none';
        });
    }

    /**
     * Navega a propiedades
     */
    navigateToProperties() {
        window.location.href = '/propeasy/public/agent/properties';
    }

    /**
     * Navega a clientes
     */
    navigateToClients() {
        window.location.href = '/propeasy/public/agent/clients';
    }

    /**
     * Navega a citas
     */
    navigateToAppointments() {
        window.location.href = '/propeasy/public/agent/appointments';
    }

    /**
     * Navega a mensajes
     */
    navigateToMessages() {
        window.location.href = '/propeasy/public/agent/messages';
    }

    /**
     * Muestra modal de nueva propiedad
     */
    showNewPropertyModal() {
        // Simulación de modal
        this.showToast('Funcionalidad de nueva propiedad en desarrollo', 'info');
    }

    /**
     * Muestra modal de agendar cita
     */
    showAppointmentModal() {
        // Simulación de modal
        this.showToast('Funcionalidad de agendar cita en desarrollo', 'info');
    }

    /**
     * Ve propiedad
     */
    viewProperty(propertyId) {
        window.location.href = `/propeasy/public/property/${propertyId}`;
    }

    /**
     * Edita propiedad
     */
    editProperty(propertyId) {
        window.location.href = `/propeasy/public/property/${propertyId}/edit`;
    }

    /**
     * Muestra toast de notificación
     */
    showToast(message, type = 'info') {
        // Crear toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        // Agregar al contenedor de toasts
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.appendChild(toast);

        // Mostrar toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remover después de que se oculte
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    /**
     * Formatea números
     */
    formatNumber(num) {
        return new Intl.NumberFormat('es-CL').format(num);
    }
}

// Inicializar dashboard cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new AgentDashboard();
});

// Exportar para uso global
window.AgentDashboard = AgentDashboard;

// Dashboard Agente - Funcionalidades JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard del Agente cargado');
    
    // Inicializar tooltips de Bootstrap
    initializeTooltips();
    
    // Marcar enlaces del navbar como activos
    markActiveNavLink();
    
    // Funcionalidad para actualizar estadísticas
    setupStatsRefresh();
    
    // Funcionalidad para mensajes
    setupMessageActions();
    
    // Animaciones de entrada
    animateCards();
});

function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (typeof bootstrap !== 'undefined') {
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

function markActiveNavLink() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}

function setupStatsRefresh() {
    // Actualizar estadísticas cada 5 minutos
    setInterval(() => {
        fetch('/agent/stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Estadísticas actualizadas');
                }
            })
            .catch(error => console.error('Error actualizando estadísticas:', error));
    }, 300000);
}

function setupMessageActions() {
    document.addEventListener('click', function(e) {
        if (e.target.matches('.mark-read-btn')) {
            e.preventDefault();
            const messageId = e.target.dataset.messageId;
            markMessageAsRead(messageId, e.target);
        }
    });
}

function markMessageAsRead(messageId, button) {
    const formData = new FormData();
    formData.append('message_id', messageId);
    
    fetch('/agent/mark-message-read', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.closest('.list-group-item').classList.remove('bg-light');
            showNotification('Mensaje marcado como leído', 'success');
        }
    })
    .catch(error => console.error('Error:', error));
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function animateCards() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
} 