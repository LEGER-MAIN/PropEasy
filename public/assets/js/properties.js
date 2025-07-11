// JavaScript para el listado de propiedades

// --- FAVORITOS: Mostrar modal si no hay sesión ---
// Variable global para saber si el usuario está autenticado
window.isUserAuthenticated = window.isUserAuthenticated ?? false;

document.addEventListener('DOMContentLoaded', function() {
    // Configurar formulario de filtros
    setupFilterForm();
    
    // Configurar controles de vista
    setupViewControls();
    
    // Configurar paginación
    setupPaginationButtons();
    
    // Configurar búsqueda en tiempo real
    setupRealTimeSearch();

    // Configurar favoritos
    setupFavoriteButtons();
    
    // Configurar filtro de favoritos
    setupFavoritesFilter();
});

// Función para configurar formulario de filtros
function setupFilterForm() {
    const filterForm = document.getElementById('filterForm');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    if (filterForm) {
        // Eliminar cualquier submit tradicional previo
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            applyFilters();
            return false;
        });
        
        // Prevenir envío por Enter en cualquier input
        filterForm.querySelectorAll('input, select').forEach(function(el) {
            el.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                    return false;
                }
            });
        });
        

    }
    
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            clearFilters();
        });
    }
}



// Función para aplicar filtros
function applyFilters(page = 1) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // Agregar página a los filtros
    formData.set('page', page);
    
    // Asegurar que el checkbox de favoritos se incluya correctamente
    const soloFavoritosCheckbox = document.getElementById('soloFavoritos');
    if (soloFavoritosCheckbox && soloFavoritosCheckbox.checked) {
        formData.set('solo_favoritos', '1');
    }
    
    // Mostrar loading
    showLoading();
    
    fetch('/properties/filter', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        // Crear un contenedor temporal para parsear el HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Buscar el contenedor de propiedades y la paginación
        const newPropertiesContainer = tempDiv.querySelector('#propertiesContainer');
        const newPagination = tempDiv.querySelector('.pagination');
        
        // Reemplazar el contenedor de propiedades
        const currentContainer = document.getElementById('propertiesContainer');
        if (newPropertiesContainer && currentContainer) {
            currentContainer.outerHTML = newPropertiesContainer.outerHTML;
        }
        
        // Buscar y reemplazar/agregar la paginación
        const currentPagination = document.querySelector('.pagination');
        const paginationParent = currentPagination ? currentPagination.closest('.row') : null;
        
        if (newPagination) {
            // Buscar el contenedor row de la paginación nueva
            let newPaginationContainer = newPagination.closest('.row');
            if (!newPaginationContainer) {
                // Si no tiene row padre, buscar en los siguientes elementos
                const allRows = tempDiv.querySelectorAll('.row');
                for (const row of allRows) {
                    if (row.querySelector('.pagination')) {
                        newPaginationContainer = row;
                        break;
                    }
                }
            }
            
            if (paginationParent && newPaginationContainer) {
                // Reemplazar paginación existente
                paginationParent.outerHTML = newPaginationContainer.outerHTML;
            } else if (newPaginationContainer) {
                // Agregar nueva paginación después del contenedor
                const newContainer = document.getElementById('propertiesContainer');
                if (newContainer && newContainer.parentNode) {
                    newContainer.parentNode.insertBefore(newPaginationContainer.cloneNode(true), newContainer.nextSibling);
                }
            }
        } else {
            // No hay paginación en la respuesta, remover la existente si existe
            if (paginationParent) {
                paginationParent.remove();
            }
        }
        
        hideLoading();
        
        // Reasignar eventos a los botones de favoritos
        setupFavoriteButtons();
        
        // Reasignar eventos a los botones de paginación
        setupPaginationButtons();
    })
    .catch(error => {
        console.error('Error en filtros:', error);
        hideLoading();
        showNotification('Error al aplicar filtros', 'error');
    });
}

// Función para limpiar filtros
function clearFilters() {
    const form = document.getElementById('filterForm');
    if (form) {
        form.reset();
        
        // Desmarcar checkbox de favoritos específicamente
        const soloFavoritosCheckbox = document.getElementById('soloFavoritos');
        if (soloFavoritosCheckbox) {
            soloFavoritosCheckbox.checked = false;
        }
        
        // Redirigir a la página sin filtros
        window.location.href = '/properties';
    }
}

// Función para configurar controles de vista
function setupViewControls() {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    if (gridView && listView) {
        gridView.addEventListener('change', function() {
            if (this.checked) {
                changeToGridView();
            }
        });
        
        listView.addEventListener('change', function() {
            if (this.checked) {
                changeToListView();
            }
        });
    }
}

// Función para cambiar a vista de cuadrícula
function changeToGridView() {
    const container = document.getElementById('propertiesContainer');
    const items = container.querySelectorAll('.property-item');
    
    container.className = 'row g-3';
    
    items.forEach(item => {
        // Preservar clases existentes como filter-result
        const existingClasses = item.className;
        const isFilterResult = existingClasses.includes('filter-result');
        
        if (isFilterResult) {
            item.className = 'col-lg-4 col-md-6 col-12 mb-4 property-item filter-result';
        } else {
            item.className = 'col-lg-4 col-md-6 mb-4 property-item';
        }
    });
    
    // Guardar preferencia
    localStorage.setItem('viewMode', 'grid');
}

// Función para cambiar a vista de lista
function changeToListView() {
    const container = document.getElementById('propertiesContainer');
    const items = container.querySelectorAll('.property-item');
    
    container.className = 'row g-3';
    
    items.forEach(item => {
        // Preservar clases existentes como filter-result
        const existingClasses = item.className;
        const isFilterResult = existingClasses.includes('filter-result');
        
        if (isFilterResult) {
            item.className = 'col-12 mb-4 property-item filter-result';
        } else {
            item.className = 'col-12 mb-4 property-item';
        }
    });
    
    // Guardar preferencia
    localStorage.setItem('viewMode', 'list');
}



// Función para configurar búsqueda en tiempo real
function setupRealTimeSearch() {
    const searchInputs = document.querySelectorAll('input[name="ubicacion"], input[name="precio_min"], input[name="precio_max"]');
    
    let searchTimeout;
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                // Solo buscar si hay al menos 3 caracteres o un precio
                if (this.value.length >= 3 || (this.name.includes('precio') && this.value)) {
                    performRealTimeSearch();
                }
            }, 500);
        });
    });
}

// Función para realizar búsqueda en tiempo real
function performRealTimeSearch() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const searchData = {};
    
    for (let [key, value] of formData.entries()) {
        if (value) {
            searchData[key] = value;
        }
    }
    
    // Mostrar loading
    showLoading();
    
    // Hacer petición AJAX
    fetch('/properties/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(searchData)
    })
    .then(response => response.json())
    .then(data => {
        updatePropertiesList(data);
        hideLoading();
    })
    .catch(error => {
        console.error('Error en la búsqueda:', error);
        hideLoading();
        showNotification('Error en la búsqueda. Inténtalo de nuevo.', 'danger');
    });
}

// Función para actualizar lista de propiedades
function updatePropertiesList(properties) {
    const container = document.getElementById('propertiesContainer');
    const countBadge = document.querySelector('.badge.bg-light');
    
    if (container) {
        if (properties.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No se encontraron propiedades</h4>
                    <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                </div>
            `;
        } else {
            container.innerHTML = '';
            properties.forEach(property => {
                const propertyElement = createPropertyElement(property);
                container.appendChild(propertyElement);
            });
            // Reasignar eventos después de crear elementos
            setupFavoriteButtons();
        }
    }
    
    // Actualizar contador
    if (countBadge) {
        countBadge.textContent = `${properties.length} propiedades encontradas`;
    }
}

// Función para crear elemento de propiedad
function createPropertyElement(property) {
    const div = document.createElement('div');
    div.className = 'col-lg-4 col-md-6 mb-4 property-item';
    div.setAttribute('data-prop-id', property.id);
    div.innerHTML = `
        <div class="card property-card h-100 position-relative">
            <div class="position-relative">
                <img src="${property.imagen_principal || 'assets/images/placeholder.jpg'}" 
                     class="card-img-top" 
                     alt="${property.titulo}"
                     onerror="this.src='assets/images/placeholder.jpg'">
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-primary">${property.tipo}</span>
                </div>
                <div class="position-absolute top-0 start-0 m-2">
                    <span class="badge bg-success">${property.estado || 'activa'}</span>
                </div>
            </div>
            <div class="d-flex justify-content-end align-items-center gap-2 mt-2 mb-2 me-3">
                <button class="favorite-btn p-0 border-0 bg-transparent" 
                        data-id="${property.id}"
                        style="z-index:2;">
                    <i class="fa-heart far" 
                       id="favoriteIcon-${property.id}" 
                       style="font-size: 2.2rem; color: #dc3545;"></i>
                </button>
                <span class="small text-muted" id="favoriteCount-${property.id}">
                    ${property.favorites_count || 0}
                </span>
            </div>
            <div class="card-body">
                <h5 class="card-title">${property.titulo}</h5>
                <p class="property-price mb-2">${formatPrice(property.precio)}</p>
                <p class="property-location mb-3">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    ${property.ubicacion || 'Ubicación no especificada'}
                </p>
                <div class="row text-center mb-3 align-items-end" style="min-height: 70px;">
                    <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                        <div>
                            <i class="fas fa-bed fa-lg mb-1"></i>
                            <span class="fw-bold ms-1">${property.habitaciones}</span>
                        </div>
                        <small class="text-muted">Habitaciones</small>
                    </div>
                    <div class="col-4 border-end d-flex flex-column justify-content-end align-items-center">
                        <div>
                            <i class="fas fa-bath fa-lg mb-1"></i>
                            <span class="fw-bold ms-1">${property.banos || 'N/A'}</span>
                        </div>
                        <small class="text-muted">Baños</small>
                    </div>
                    <div class="col-4 d-flex flex-column justify-content-end align-items-center">
                        <div>
                            <i class="fas fa-ruler-combined fa-lg mb-1"></i>
                            <span class="fw-bold ms-1">${property.area}m²</span>
                        </div>
                        <small class="text-muted">Área</small>
                    </div>
                </div>
                <div class="d-grid">
                    <a href="/properties/detail/${property.id}" class="btn btn-outline-primary">Ver Detalles</a>
                </div>
            </div>
        </div>
    `;
    return div;
}

// Función para mostrar loading
function showLoading() {
    const loading = document.getElementById('loading');
    const container = document.getElementById('propertiesContainer');
    
    if (loading) loading.style.display = 'block';
    if (container) container.style.display = 'none';
}

// Función para ocultar loading
function hideLoading() {
    const loading = document.getElementById('loading');
    const container = document.getElementById('propertiesContainer');
    
    if (loading) loading.style.display = 'none';
    if (container) container.style.display = 'block';
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

// Función para formatear precios
function formatPrice(price) {
    return new Intl.NumberFormat('es-DO', {
        style: 'currency',
        currency: 'DOP'
    }).format(price);
}

// Función para configurar botones de favoritos
function setupFavoriteButtons() {
    document.querySelectorAll('.favorite-btn').forEach(function(btn) {
        // Remover eventos previos para evitar duplicados
        btn.replaceWith(btn.cloneNode(true));
    });
    
    document.querySelectorAll('.favorite-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const propertyId = btn.getAttribute('data-id');
            
            if (!window.isUserAuthenticated) {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                return;
            }
            
            // Mostrar loading
            const icon = btn.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin';
            btn.disabled = true;
            
            fetch('/properties/favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'property_id=' + encodeURIComponent(propertyId)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Actualizar estado del botón
                    if (data.is_favorite) {
                        btn.classList.add('active');
                        icon.className = 'fas fa-heart text-danger';
                    } else {
                        btn.classList.remove('active');
                        icon.className = 'far fa-heart text-muted';
                    }
                    
                    // Actualizar contador si existe
                    const counter = document.getElementById('favoriteCount-' + propertyId);
                    if (counter) {
                        counter.textContent = data.count + ' favoritos';
                    }
                    
                    // Mostrar notificación
                    showNotification(data.message, 'success');
                } else {
                    if (data.require_login) {
                        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                        loginModal.show();
                    } else {
                        showNotification(data.message, 'error');
                    }
                    // Restaurar estado original
                    icon.className = originalClass;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al procesar la solicitud', 'error');
                // Restaurar estado original
                icon.className = originalClass;
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });
}

// Función para configurar el filtro de favoritos
function setupFavoritesFilter() {
    const soloFavoritosCheckbox = document.getElementById('soloFavoritos');
    
    if (soloFavoritosCheckbox) {
        // Event listener para cuando cambie el estado del checkbox
        soloFavoritosCheckbox.addEventListener('change', function() {
            // Aplicar filtros automáticamente cuando se marque/desmarque (volver a página 1)
            applyFilters(1);
        });
    }
}

// Función para configurar botones de paginación
function setupPaginationButtons() {
    const paginationButtons = document.querySelectorAll('.page-link[data-page]');
    
    paginationButtons.forEach(button => {
        // Remover eventos previos para evitar duplicados
        button.replaceWith(button.cloneNode(true));
    });
    
    // Reasignar eventos a los botones clonados
    document.querySelectorAll('.page-link[data-page]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page'));
            if (page && page > 0) {
                applyFilters(page);
            }
        });
    });
}

