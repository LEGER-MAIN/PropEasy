/**
 * Sistema avanzado de gestión de propiedades para agentes
 * PropEasy - Gestión de Propiedades
 */

document.addEventListener('DOMContentLoaded', function() {
    initializePropertyManagement();
});

/**
 * Inicializa el sistema de gestión de propiedades
 */
function initializePropertyManagement() {
    // Eventos de filtros
    initializeFilters();
    
    // Eventos de selección masiva
    initializeBulkSelection();
    
    // Eventos de cambio de vista
    initializeViewToggle();
    
    // Tooltips
    initializeTooltips();
    
    // Auto-aplicar filtros al cambiar valores
    setupAutoFilters();
}

/**
 * Inicializa los filtros de propiedades
 */
function initializeFilters() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const orderBy = document.getElementById('orderBy');
    const orderDir = document.getElementById('orderDir');
    
    // Aplicar filtros en tiempo real para la búsqueda
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                applyFilters();
            }, 500); // Delay de 500ms para evitar muchas consultas
        });
    }
    
    // Aplicar filtros inmediatamente para selects
    [statusFilter, typeFilter, orderBy, orderDir].forEach(element => {
        if (element) {
            element.addEventListener('change', applyFilters);
        }
    });
}

/**
 * Configura auto-filtros para elementos avanzados
 */
function setupAutoFilters() {
    const priceMin = document.getElementById('priceMin');
    const priceMax = document.getElementById('priceMax');
    const limitFilter = document.getElementById('limitFilter');
    
    // Delay para campos de precio
    [priceMin, priceMax].forEach(element => {
        if (element) {
            let priceTimeout;
            element.addEventListener('input', function() {
                clearTimeout(priceTimeout);
                priceTimeout = setTimeout(() => {
                    applyFilters();
                }, 1000); // 1 segundo de delay para precio
            });
        }
    });
    
    // Aplicar inmediatamente para límite
    if (limitFilter) {
        limitFilter.addEventListener('change', applyFilters);
    }
}

/**
 * Aplica los filtros seleccionados
 */
function applyFilters() {
    showLoading('Aplicando filtros...');
    
    const formData = new FormData();
    formData.append('action', 'filter');
    
    // Obtener valores de todos los filtros
    const filters = {
        search: document.getElementById('searchInput')?.value || '',
        status: document.getElementById('statusFilter')?.value || '',
        tipo: document.getElementById('typeFilter')?.value || '',
        price_min: document.getElementById('priceMin')?.value || '',
        price_max: document.getElementById('priceMax')?.value || '',
        order_by: document.getElementById('orderBy')?.value || 'fecha_creacion',
        order_dir: document.getElementById('orderDir')?.value || 'DESC',
        limit: document.getElementById('limitFilter')?.value || '20'
    };
    
    // Agregar filtros al FormData
    Object.entries(filters).forEach(([key, value]) => {
        formData.append(key, value);
    });
    
    fetch('/agent/api/properties', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            updatePropertiesDisplay(data.data);
            updateResultsCount(data.count);
            
            // Actualizar selección masiva
            updateBulkSelectionState();
            
            showToast('Filtros aplicados correctamente', 'success');
        } else {
            showToast('Error al aplicar filtros: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Error de conexión al aplicar filtros', 'error');
    });
}

/**
 * Limpia todos los filtros
 */
function clearFilters() {
    // Limpiar campos de texto
    document.getElementById('searchInput').value = '';
    document.getElementById('priceMin').value = '';
    document.getElementById('priceMax').value = '';
    
    // Resetear selects
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('orderBy').value = 'fecha_creacion';
    document.getElementById('orderDir').value = 'DESC';
    document.getElementById('limitFilter').value = '20';
    
    // Aplicar filtros limpios
    applyFilters();
}

/**
 * Actualiza la visualización de propiedades
 */
function updatePropertiesDisplay(properties) {
    const gridContainer = document.getElementById('gridViewContainer');
    const listContainer = document.getElementById('listViewContainer');
    
    if (!properties || properties.length === 0) {
        const emptyMessage = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron propiedades</h5>
                    <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                </div>
            </div>
        `;
        gridContainer.innerHTML = emptyMessage;
        listContainer.innerHTML = '<div class="alert alert-info">No hay propiedades para mostrar</div>';
        return;
    }
    
    // Actualizar vista en cuadrícula
    updateGridView(properties);
    
    // Actualizar vista en lista
    updateListView(properties);
}

/**
 * Actualiza la vista en cuadrícula
 */
function updateGridView(properties) {
    const container = document.getElementById('gridViewContainer');
    let html = '';
    
    properties.forEach(property => {
        html += generatePropertyCard(property);
    });
    
    container.innerHTML = html;
    
    // Reinicializar eventos después de actualizar DOM
    reinitializeEvents();
}

/**
 * Genera una tarjeta de propiedad
 */
function generatePropertyCard(property) {
    const statusBadgeClass = getStatusBadgeClass(property.status);
    const imageCount = property.images ? property.images.length : 1;
    
    return `
        <div class="col-lg-4 col-md-6 mb-4 property-card" data-property-id="${property.id}">
            <div class="card h-100 shadow-sm">
                <div class="position-relative">
                    <!-- Checkbox de selección -->
                    <div class="position-absolute top-0 start-0 m-2 z-index-10">
                        <div class="form-check">
                            <input class="form-check-input property-checkbox" type="checkbox" 
                                   value="${property.id}" id="check-${property.id}">
                        </div>
                    </div>
                    
                    <img src="${property.image}" 
                         class="card-img-top" 
                         alt="${property.title}"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='/assets/img/property-default.svg'">
                    
                    <!-- Estado de la propiedad -->
                    <div class="position-absolute top-0 end-0 m-2">
                        <div class="dropdown">
                            <span class="badge bg-${statusBadgeClass} dropdown-toggle" 
                                  data-bs-toggle="dropdown" style="cursor: pointer;">
                                ${property.status}
                            </span>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(${property.id}, 'activa')">
                                    <i class="fas fa-check text-success me-1"></i>Activar
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(${property.id}, 'vendida')">
                                    <i class="fas fa-handshake text-warning me-1"></i>Marcar Vendida
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(${property.id}, 'rentada')">
                                    <i class="fas fa-key text-info me-1"></i>Marcar Rentada
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="changePropertyStatus(${property.id}, 'retirada')">
                                    <i class="fas fa-pause text-secondary me-1"></i>Retirar
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contador de imágenes -->
                    ${imageCount > 1 ? `
                    <div class="position-absolute bottom-0 end-0 m-2">
                        <span class="badge bg-dark bg-opacity-75">
                            <i class="fas fa-images me-1"></i>${imageCount}
                        </span>
                    </div>
                    ` : ''}
                    
                    <!-- Indicador de días en mercado -->
                    <div class="position-absolute bottom-0 start-0 m-2">
                        <span class="badge bg-primary bg-opacity-75">
                            <i class="fas fa-calendar me-1"></i>${property.days_on_market}d
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <h6 class="card-title mb-2">${property.title}</h6>
                    <p class="card-text text-primary fw-bold fs-5 mb-2">$${formatNumber(property.price)}</p>
                    
                    <!-- Información básica -->
                    <div class="row text-muted small mb-2">
                        <div class="col-4 text-center">
                            <i class="fas fa-bed me-1"></i>${property.habitaciones || 0}
                        </div>
                        <div class="col-4 text-center">
                            <i class="fas fa-bath me-1"></i>${property.banos || 0}
                        </div>
                        <div class="col-4 text-center">
                            <i class="fas fa-ruler-combined me-1"></i>${property.area || 0}m²
                        </div>
                    </div>
                    
                    <!-- Estadísticas -->
                    <div class="row text-muted small">
                        <div class="col-6">
                            <i class="fas fa-eye me-1"></i>${property.views} vistas
                        </div>
                        <div class="col-6">
                            <i class="fas fa-comment me-1"></i>${property.inquiries} consultas
                        </div>
                    </div>
                    
                    <!-- Ubicación -->
                    <p class="card-text text-muted small mt-2 mb-0">
                        <i class="fas fa-map-marker-alt me-1"></i>${property.ubicacion}
                    </p>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-outline-primary btn-sm" onclick="viewProperty(${property.id})" title="Ver Detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="editProperty(${property.id})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="showPropertyStats(${property.id})" title="Estadísticas">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="shareProperty(${property.id})" title="Compartir">
                            <i class="fas fa-share"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="unassignProperty(${property.id})" title="Desasignar">
                            <i class="fas fa-unlink"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Obtiene la clase CSS para el badge de estado
 */
function getStatusBadgeClass(status) {
    switch(status.toLowerCase()) {
        case 'activa': return 'success';
        case 'pendiente': return 'warning';
        case 'vendida': return 'info';
        case 'rentada': return 'primary';
        case 'retirada': return 'secondary';
        default: return 'secondary';
    }
}

/**
 * Formatea números con separadores de miles
 */
function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}

/**
 * Inicializa la selección masiva
 */
function initializeBulkSelection() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkActionSelect = document.getElementById('bulkAction');
    const executeBulkBtn = document.getElementById('executeBulkBtn');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const propertyCheckboxes = document.querySelectorAll('.property-checkbox');
            propertyCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkSelectionState();
        });
    }
    
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', updateBulkSelectionState);
    }
    
    // Actualizar estado inicial
    updateBulkSelectionState();
}

/**
 * Actualiza el estado de la selección masiva
 */
function updateBulkSelectionState() {
    const propertyCheckboxes = document.querySelectorAll('.property-checkbox');
    const selectedCheckboxes = document.querySelectorAll('.property-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    // Actualizar contador
    const selectedCountElement = document.getElementById('selectedCount');
    if (selectedCountElement) {
        selectedCountElement.textContent = `${selectedCount} seleccionadas`;
    }
    
    // Actualizar estado del checkbox principal
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        if (selectedCount === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (selectedCount === propertyCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
    
    // Habilitar/deshabilitar botón de ejecución
    const executeBulkBtn = document.getElementById('executeBulkBtn');
    const bulkActionSelect = document.getElementById('bulkAction');
    
    if (executeBulkBtn && bulkActionSelect) {
        const hasSelection = selectedCount > 0;
        const hasAction = bulkActionSelect.value !== '';
        
        executeBulkBtn.disabled = !(hasSelection && hasAction);
    }
    
    // Agregar eventos a checkboxes individuales
    propertyCheckboxes.forEach(checkbox => {
        checkbox.removeEventListener('change', updateBulkSelectionState);
        checkbox.addEventListener('change', updateBulkSelectionState);
    });
}

/**
 * Ejecuta acciones masivas
 */
function executeBulkAction() {
    const selectedCheckboxes = document.querySelectorAll('.property-checkbox:checked');
    const bulkAction = document.getElementById('bulkAction').value;
    
    if (selectedCheckboxes.length === 0) {
        showToast('Selecciona al menos una propiedad', 'warning');
        return;
    }
    
    if (!bulkAction) {
        showToast('Selecciona una acción a realizar', 'warning');
        return;
    }
    
    // Confirmar acción
    const actionNames = {
        'activate': 'activar',
        'deactivate': 'retirar',
        'mark_sold': 'marcar como vendidas'
    };
    
    const actionName = actionNames[bulkAction] || bulkAction;
    const confirmMessage = `¿Estás seguro de que quieres ${actionName} ${selectedCheckboxes.length} propiedades?`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    showLoading('Ejecutando acción masiva...');
    
    const propertyIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    const formData = new FormData();
    formData.append('action', 'bulk_action');
    formData.append('bulk_action', bulkAction);
    formData.append('property_ids', JSON.stringify(propertyIds));
    
    fetch('/agent/api/properties', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast(data.message, 'success');
            
            // Refrescar propiedades
            applyFilters();
            
            // Limpiar selección
            document.getElementById('selectAll').checked = false;
            document.getElementById('bulkAction').value = '';
            updateBulkSelectionState();
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Error de conexión al ejecutar acción', 'error');
    });
}

/**
 * Cambia el estado de una propiedad individual
 */
function changePropertyStatus(propertyId, newStatus) {
    event.preventDefault();
    
    showLoading('Cambiando estado...');
    
    const formData = new FormData();
    formData.append('action', 'change_status');
    formData.append('property_id', propertyId);
    formData.append('new_status', newStatus);
    
    fetch('/agent/api/properties', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast(data.message, 'success');
            
            // Refrescar propiedades
            applyFilters();
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Error de conexión', 'error');
    });
}

/**
 * Desasigna una propiedad del agente
 */
function unassignProperty(propertyId) {
    if (!confirm('¿Estás seguro de que quieres desasignar esta propiedad? Ya no podrás gestionarla.')) {
        return;
    }
    
    showLoading('Desasignando propiedad...');
    
    const formData = new FormData();
    formData.append('action', 'unassign_property');
    formData.append('property_id', propertyId);
    
    fetch('/agent/api/properties', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showToast(data.message, 'success');
            
            // Refrescar propiedades
            applyFilters();
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Error de conexión', 'error');
    });
}

/**
 * Funciones de utilidad
 */

function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function initializeViewToggle() {
    const gridViewRadio = document.getElementById('gridView');
    const listViewRadio = document.getElementById('listView');
    
    if (gridViewRadio) {
        gridViewRadio.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('gridViewContainer').classList.remove('d-none');
                document.getElementById('listViewContainer').classList.add('d-none');
            }
        });
    }
    
    if (listViewRadio) {
        listViewRadio.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('gridViewContainer').classList.add('d-none');
                document.getElementById('listViewContainer').classList.remove('d-none');
            }
        });
    }
}

function reinitializeEvents() {
    // Reinicializar tooltips
    initializeTooltips();
    
    // Reinicializar eventos de selección masiva
    updateBulkSelectionState();
}

function updateResultsCount(count) {
    // Actualizar contador de resultados si existe
    const resultsCount = document.getElementById('resultsCount');
    if (resultsCount) {
        resultsCount.textContent = `${count} propiedades encontradas`;
    }
}

function updateListView(properties) {
    // Implementar vista en lista si es necesario
    // Por ahora solo se usa la vista en cuadrícula
}

function exportProperties() {
    showToast('Funcionalidad de exportación en desarrollo', 'info');
}

function shareProperty(propertyId) {
    const url = window.location.origin + '/properties/detail/' + propertyId;
    
    if (navigator.share) {
        navigator.share({
            title: 'Propiedad en PropEasy',
            url: url
        });
    } else {
        // Fallback para navegadores que no soportan Web Share API
        navigator.clipboard.writeText(url).then(() => {
            showToast('Enlace copiado al portapapeles', 'success');
        });
    }
}

function viewProperty(propertyId) {
    window.open('/properties/detail/' + propertyId, '_blank');
}

function editProperty(propertyId) {
    // Redirigir a página de edición
    window.location.href = '/properties/edit/' + propertyId;
}

function showPropertyStats(propertyId) {
    // Mostrar modal con estadísticas de la propiedad
    showToast('Modal de estadísticas en desarrollo', 'info');
}

// Funciones de utilidad UI
function showLoading(message = 'Cargando...') {
    // Implementar loading spinner
    console.log(message);
}

function hideLoading() {
    // Ocultar loading spinner
}

function showToast(message, type = 'info') {
    // Implementar sistema de notificaciones toast
    console.log(`${type.toUpperCase()}: ${message}`);
    
    // Fallback con alert por ahora
    if (type === 'error') {
        alert('Error: ' + message);
    } else if (type === 'success') {
        alert('Éxito: ' + message);
    }
} 