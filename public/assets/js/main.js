// JavaScript principal para PropEasy

document.addEventListener('DOMContentLoaded', function() {
    // Cargar propiedades destacadas al cargar la página
    loadFeaturedProperties();
    
    // Configurar el formulario de búsqueda
    setupSearchForm();
    
    // Configurar animaciones
    setupAnimations();
});

// Función para cargar propiedades destacadas
function loadFeaturedProperties() {
    const container = document.getElementById('featuredProperties');
    if (!container) return;
    
    // Mostrar loading
    container.innerHTML = '<div class="col-12 text-center"><div class="loading"></div><p>Cargando propiedades...</p></div>';
    
    // Simular carga de datos (aquí se conectará con el backend)
    setTimeout(() => {
        const properties = [
            {
                id: 1,
                title: 'Casa Moderna en Santo Domingo Este',
                price: '$2,500,000',
                location: 'Santo Domingo Este',
                bedrooms: 3,
                bathrooms: 2,
                area: '150m²',
                image: 'assets/images/property1.jpg',
                type: 'Casa'
            },
            {
                id: 2,
                title: 'Apartamento de Lujo en Piantini',
                price: '$1,800,000',
                location: 'Piantini, Santo Domingo',
                bedrooms: 2,
                bathrooms: 2,
                area: '120m²',
                image: 'assets/images/property2.jpg',
                type: 'Apartamento'
            },
            {
                id: 3,
                title: 'Terreno Comercial en Santiago',
                price: '$3,200,000',
                location: 'Santiago de los Caballeros',
                bedrooms: 0,
                bathrooms: 0,
                area: '500m²',
                image: 'assets/images/property3.jpg',
                type: 'Terreno'
            }
        ];
        
        displayProperties(properties, container);
    }, 1000);
}

// Función para mostrar propiedades
function displayProperties(properties, container) {
    container.innerHTML = '';
    
    properties.forEach(property => {
        const propertyCard = createPropertyCard(property);
        container.appendChild(propertyCard);
    });
}

// Función para crear tarjeta de propiedad
function createPropertyCard(property) {
    const col = document.createElement('div');
    col.className = 'col-lg-4 col-md-6 mb-4 fade-in-up';
    
    col.innerHTML = `
        <div class="card property-card h-100">
            <img src="${property.image}" class="card-img-top" alt="${property.title}" 
                 onerror="this.src='assets/images/placeholder.jpg'">
            <div class="card-body">
                <h5 class="card-title">${property.title}</h5>
                <p class="property-price">${property.price}</p>
                <p class="property-location">
                    <i class="fas fa-map-marker-alt me-1"></i>${property.location}
                </p>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <small class="text-muted">Habitaciones</small>
                        <div><i class="fas fa-bed"></i> ${property.bedrooms}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Baños</small>
                        <div><i class="fas fa-bath"></i> ${property.bathrooms}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Área</small>
                        <div><i class="fas fa-ruler-combined"></i> ${property.area}</div>
                    </div>
                </div>
                <div class="d-grid">
                    <a href="property/${property.id}" class="btn btn-outline-primary">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
    `;
    
    return col;
}

// Función para configurar el formulario de búsqueda
function setupSearchForm() {
    const searchForm = document.getElementById('searchForm');
    if (!searchForm) return;
    
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(searchForm);
        const searchParams = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value.trim()) {
                searchParams.append(key, value);
            }
        }
        
        // Redirigir a la página de búsqueda con los parámetros
        window.location.href = `/properties/search?${searchParams.toString()}`;
    });
}

// Función para configurar animaciones
function setupAnimations() {
    // Animación para elementos al hacer scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observar elementos que necesitan animación
    document.querySelectorAll('.property-card, .features-section .col-md-4').forEach(el => {
        observer.observe(el);
    });
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

// Función para validar formularios
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Función para formatear precios
function formatPrice(price) {
    return new Intl.NumberFormat('es-DO', {
        style: 'currency',
        currency: 'DOP'
    }).format(price);
}

// Función para hacer peticiones AJAX
async function makeRequest(url, options = {}) {
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error en la petición:', error);
        showNotification('Error en la conexión. Inténtalo de nuevo.', 'danger');
        throw error;
    }
} 