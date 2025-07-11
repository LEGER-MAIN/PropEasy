/**
 * JavaScript para la página de Carreras
 * Maneja la búsqueda, filtrado, postulación y funcionalidades interactivas
 */

// Variables globales
let searchTimeout;
let currentDepartment = '';
let currentLocation = '';
let jobResults = [];

/**
 * Inicialización cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando página de Carreras...');
    
    // Inicializar funcionalidades
    initializeSearch();
    initializeFilters();
    initializeJobCards();
    initializeAnimations();
    initializeContactHR();
    
    console.log('✅ Página de Carreras inicializada correctamente');
});

/**
 * Inicializa la funcionalidad de búsqueda
 */
function initializeSearch() {
    const searchInput = document.getElementById('search-jobs');
    
    if (searchInput) {
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performJobSearch(query);
                }, 300);
            } else if (query.length === 0) {
                showAllJobs();
            }
        });
        
        // Búsqueda con Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchJobs();
            }
        });
    }
    
    console.log('🔍 Funcionalidad de búsqueda inicializada');
}

/**
 * Inicializa los filtros
 */
function initializeFilters() {
    const departmentFilter = document.getElementById('department-filter');
    const locationFilter = document.getElementById('location-filter');
    
    if (departmentFilter) {
        departmentFilter.addEventListener('change', function() {
            currentDepartment = this.value;
            filterJobs();
        });
    }
    
    if (locationFilter) {
        locationFilter.addEventListener('change', function() {
            currentLocation = this.value;
            filterJobs();
        });
    }
    
    console.log('📂 Filtros inicializados');
}

/**
 * Inicializa las tarjetas de empleos
 */
function initializeJobCards() {
    const jobCards = document.querySelectorAll('.job-card');
    
    jobCards.forEach(card => {
        // Efecto hover
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
    });
    
    console.log('💼 Tarjetas de empleos inicializadas');
}

/**
 * Inicializa las animaciones
 */
function initializeAnimations() {
    // Animaciones de entrada para las tarjetas
    const cards = document.querySelectorAll('.card, .job-card');
    
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'all 0.6s ease-out';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                cardObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    cards.forEach(card => {
        cardObserver.observe(card);
    });
    
    // Efectos hover para departamentos
    const departmentCards = document.querySelectorAll('.list-group-item');
    departmentCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    console.log('🎬 Animaciones inicializadas');
}

/**
 * Inicializa el contacto de RRHH
 */
function initializeContactHR() {
    const contactButtons = document.querySelectorAll('a[href^="tel:"], a[href^="mailto:"]');
    
    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Agregar efecto visual
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    console.log('📞 Contacto RRHH inicializado');
}

/**
 * Realiza la búsqueda de empleos
 * @param {string} query Término de búsqueda
 */
function performJobSearch(query) {
    if (query.length < 2) return;
    
    // Mostrar indicador de carga
    showLoadingIndicator();
    
    fetch('/propeasy/public/careers/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            query: query,
            departamento: currentDepartment,
            ubicacion: currentLocation
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingIndicator();
        displayJobResults(data);
    })
    .catch(error => {
        hideLoadingIndicator();
        console.error('Error en búsqueda:', error);
        showNotification('Error al realizar la búsqueda', 'error');
    });
}

/**
 * Función principal de búsqueda (llamada desde el botón)
 */
function searchJobs() {
    const searchInput = document.getElementById('search-jobs');
    const query = searchInput ? searchInput.value.trim() : '';
    
    if (query.length < 2) {
        showNotification('Ingresa al menos 2 caracteres para buscar', 'warning');
        return;
    }
    
    performJobSearch(query);
}

/**
 * Muestra todos los empleos (reset de búsqueda)
 */
function showAllJobs() {
    const jobCards = document.querySelectorAll('.job-card');
    const searchInput = document.getElementById('search-jobs');
    
    // Limpiar búsqueda
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Mostrar todas las tarjetas
    jobCards.forEach(card => {
        card.style.display = 'block';
        card.style.opacity = '1';
    });
    
    // Resetear filtros
    const departmentFilter = document.getElementById('department-filter');
    const locationFilter = document.getElementById('location-filter');
    
    if (departmentFilter) departmentFilter.value = '';
    if (locationFilter) locationFilter.value = '';
    
    currentDepartment = '';
    currentLocation = '';
    
    showNotification('Mostrando todas las vacantes', 'info');
}

/**
 * Filtra empleos por departamento
 * @param {string} department Departamento seleccionado
 */
function filterByDepartment(department) {
    currentDepartment = department;
    
    // Actualizar filtro visual
    const departmentFilter = document.getElementById('department-filter');
    if (departmentFilter) {
        departmentFilter.value = department;
    }
    
    filterJobs();
}

/**
 * Filtra empleos por ubicación
 * @param {string} location Ubicación seleccionada
 */
function filterByLocation(location) {
    currentLocation = location;
    
    // Actualizar filtro visual
    const locationFilter = document.getElementById('location-filter');
    if (locationFilter) {
        locationFilter.value = location;
    }
    
    filterJobs();
}

/**
 * Aplica filtros a los empleos
 */
function filterJobs() {
    const jobCards = document.querySelectorAll('.job-card');
    const searchInput = document.getElementById('search-jobs');
    const query = searchInput ? searchInput.value.trim() : '';
    
    let visibleCount = 0;
    
    jobCards.forEach(card => {
        const department = card.dataset.department;
        const location = card.dataset.location;
        
        let shouldShow = true;
        
        // Filtrar por departamento
        if (currentDepartment && department !== currentDepartment) {
            shouldShow = false;
        }
        
        // Filtrar por ubicación
        if (currentLocation && location !== currentLocation) {
            shouldShow = false;
        }
        
        // Filtrar por búsqueda
        if (query && shouldShow) {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const description = card.querySelector('.card-text').textContent.toLowerCase();
            
            if (!title.includes(query.toLowerCase()) && !description.includes(query.toLowerCase())) {
                shouldShow = false;
            }
        }
        
        if (shouldShow) {
            card.style.display = 'block';
            card.style.opacity = '1';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    showNotification(`Mostrando ${visibleCount} vacantes`, 'info');
}

/**
 * Muestra los resultados de búsqueda
 * @param {Object} data Datos de la búsqueda
 */
function displayJobResults(data) {
    const jobsContainer = document.getElementById('jobs-container');
    
    if (!jobsContainer) return;
    
    // Ocultar tarjetas originales
    const originalCards = document.querySelectorAll('.job-card');
    originalCards.forEach(card => {
        card.style.display = 'none';
    });
    
    if (data.total_resultados === 0) {
        jobsContainer.innerHTML = `
            <div class="alert alert-info">
                <h5><i class="fas fa-search me-2"></i>No se encontraron vacantes</h5>
                <p class="mb-0">No encontramos empleos que coincidan con "${data.query}". 
                <a href="#" onclick="showAllJobs()" class="text-decoration-none">Ver todas las vacantes</a></p>
            </div>
        `;
        return;
    }
    
    // Mostrar resultados
    jobsContainer.innerHTML = `
        <div class="mb-4">
            <h3><i class="fas fa-search text-primary me-2"></i>Resultados de búsqueda</h3>
            <p class="text-muted">Se encontraron ${data.total_resultados} vacantes</p>
        </div>
    `;
    
    data.resultados.forEach(empleo => {
        const jobCard = document.createElement('div');
        jobCard.className = 'card mb-4 border-0 shadow-sm job-card';
        jobCard.innerHTML = `
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary">${empleo.tipo}</span>
                            <small class="text-muted">Vacante #${empleo.id}</small>
                        </div>
                        <h5 class="card-title">${empleo.titulo}</h5>
                        <p class="card-text text-muted">${empleo.descripcion}</p>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <span class="text-muted me-3">${empleo.ubicacion || 'Ubicación no especificada'}</span>
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            <span class="text-muted">${empleo.salario}</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <button class="btn btn-primary mb-2 w-100" onclick="applyJob(${empleo.id})">
                            <i class="fas fa-paper-plane me-2"></i>Postular
                        </button>
                        <button class="btn btn-outline-secondary btn-sm w-100" onclick="viewJobDetails(${empleo.id})">
                            <i class="fas fa-eye me-2"></i>Ver Detalles
                        </button>
                    </div>
                </div>
            </div>
        `;
        jobsContainer.appendChild(jobCard);
    });
    
    // Agregar botón para volver
    const backButton = document.createElement('div');
    backButton.className = 'text-center mt-4';
    backButton.innerHTML = `
        <button class="btn btn-outline-primary" onclick="showAllJobs()">
            <i class="fas fa-arrow-left me-2"></i>Ver todas las vacantes
        </button>
    `;
    jobsContainer.appendChild(backButton);
    
    showNotification(`Se encontraron ${data.total_resultados} vacantes`, 'success');
}

/**
 * Postula a un empleo
 * @param {number} jobId ID del empleo
 */
function applyJob(jobId) {
    // Obtener detalles del empleo
    fetch(`/propeasy/public/careers/job-details?id=${jobId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showApplicationModal(data.empleo);
        } else {
            showNotification('Error al obtener detalles del empleo', 'error');
        }
    })
    .catch(error => {
        console.error('Error al obtener detalles:', error);
        showNotification('Error al obtener detalles del empleo', 'error');
    });
}

/**
 * Muestra el modal de postulación
 * @param {Object} empleo Datos del empleo
 */
function showApplicationModal(empleo) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'applicationModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-paper-plane me-2"></i>Postular a: ${empleo.titulo}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="applicationForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="applicantName" class="form-label">Nombre completo *</label>
                                <input type="text" class="form-control" id="applicantName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="applicantEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="applicantEmail" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="applicantPhone" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="applicantPhone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="applicantExperience" class="form-label">Años de experiencia</label>
                                <select class="form-select" id="applicantExperience">
                                    <option value="">Seleccionar</option>
                                    <option value="0-1">0-1 años</option>
                                    <option value="1-3">1-3 años</option>
                                    <option value="3-5">3-5 años</option>
                                    <option value="5+">5+ años</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="applicantCV" class="form-label">CV (URL o descripción)</label>
                            <textarea class="form-control" id="applicantCV" rows="3" 
                                      placeholder="Pega el enlace a tu CV o describe tu experiencia..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="applicantMessage" class="form-label">Mensaje de motivación</label>
                            <textarea class="form-control" id="applicantMessage" rows="4" 
                                      placeholder="Cuéntanos por qué te interesa este puesto..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitApplication(${empleo.id})">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Postulación
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    // Limpiar modal cuando se cierre
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

/**
 * Envía la postulación
 * @param {number} jobId ID del empleo
 */
function submitApplication(jobId) {
    const name = document.getElementById('applicantName').value.trim();
    const email = document.getElementById('applicantEmail').value.trim();
    const phone = document.getElementById('applicantPhone').value.trim();
    const cv = document.getElementById('applicantCV').value.trim();
    const message = document.getElementById('applicantMessage').value.trim();
    
    if (!name || !email) {
        showNotification('Por favor completa los campos requeridos', 'warning');
        return;
    }
    
    fetch('/propeasy/public/careers/apply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            empleo_id: jobId,
            nombre: name,
            email: email,
            telefono: phone,
            cv: cv,
            mensaje: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('applicationModal')).hide();
        } else {
            showNotification(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error al enviar postulación:', error);
        showNotification('Error al enviar la postulación', 'error');
    });
}

/**
 * Muestra detalles de un empleo
 * @param {number} jobId ID del empleo
 */
function viewJobDetails(jobId) {
    fetch(`/propeasy/public/careers/job-details?id=${jobId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showJobDetailsModal(data.empleo);
        } else {
            showNotification('Error al obtener detalles del empleo', 'error');
        }
    })
    .catch(error => {
        console.error('Error al obtener detalles:', error);
        showNotification('Error al obtener detalles del empleo', 'error');
    });
}

/**
 * Muestra el modal con detalles del empleo
 * @param {Object} empleo Datos del empleo
 */
function showJobDetailsModal(empleo) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'jobDetailsModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-briefcase me-2"></i>${empleo.titulo}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Información General</h6>
                            <ul class="list-unstyled">
                                <li><strong>Departamento:</strong> ${empleo.departamento}</li>
                                <li><strong>Ubicación:</strong> ${empleo.ubicacion}</li>
                                <li><strong>Tipo:</strong> ${empleo.tipo}</li>
                                <li><strong>Contrato:</strong> ${empleo.tipo_contrato}</li>
                                <li><strong>Salario:</strong> ${empleo.salario}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success">Descripción</h6>
                            <p>${empleo.descripcion}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-info">Requisitos</h6>
                            <ul>
                                ${empleo.requisitos.map(req => `<li>${req}</li>`).join('')}
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-warning">Responsabilidades</h6>
                            <ul>
                                ${empleo.responsabilidades.map(resp => `<li>${resp}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-success">Beneficios</h6>
                        <ul>
                            ${empleo.beneficios.map(ben => `<li>${ben}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="applyJob(${empleo.id})">
                        <i class="fas fa-paper-plane me-2"></i>Postular Ahora
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    // Limpiar modal cuando se cierre
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

/**
 * Muestra indicador de carga
 */
function showLoadingIndicator() {
    const searchInput = document.getElementById('search-jobs');
    if (searchInput) {
        searchInput.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'20\' height=\'20\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23007bff\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpath d=\'M21 12a9 9 0 11-6.219-8.56\'/%3E%3C/svg%3E")';
        searchInput.style.backgroundRepeat = 'no-repeat';
        searchInput.style.backgroundPosition = 'right 10px center';
        searchInput.style.backgroundSize = '20px';
    }
}

/**
 * Oculta indicador de carga
 */
function hideLoadingIndicator() {
    const searchInput = document.getElementById('search-jobs');
    if (searchInput) {
        searchInput.style.backgroundImage = '';
    }
}

/**
 * Muestra una notificación
 * @param {string} message Mensaje a mostrar
 * @param {string} type Tipo de notificación
 */
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    notification.innerHTML = `
        <i class="fas fa-${getNotificationIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

/**
 * Obtiene el ícono para el tipo de notificación
 * @param {string} type Tipo de notificación
 * @returns {string} Nombre del ícono
 */
function getNotificationIcon(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-triangle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    
    return icons[type] || 'info-circle';
}

// Agregar estilos CSS para funcionalidades
const styles = `
    .job-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .list-group-item {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .modal-content {
        animation: slide-in-up 0.3s ease-out;
    }
    
    @keyframes slide-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
`;

// Insertar estilos en el head
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

console.log('🎨 Estilos CSS agregados para funcionalidades de Carreras'); 