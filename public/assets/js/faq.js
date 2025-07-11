/**
 * JavaScript para la página de Preguntas Frecuentes (FAQ)
 * Maneja la búsqueda, filtrado, animaciones y funcionalidades interactivas
 */

// Variables globales
let searchTimeout;
let currentCategory = '';
let searchResults = [];

/**
 * Inicialización cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando página de FAQ...');
    
    // Inicializar funcionalidades
    initializeSearch();
    initializeCategoryFilter();
    initializeAccordion();
    initializeAnimations();
    initializePopularQuestions();
    initializeContactSupport();
    
    console.log('✅ Página de FAQ inicializada correctamente');
});

/**
 * Inicializa la funcionalidad de búsqueda
 */
function initializeSearch() {
    const searchInput = document.getElementById('search-faq');
    const searchButton = document.querySelector('button[onclick="searchFAQ()"]');
    
    if (searchInput) {
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else if (query.length === 0) {
                showAllFAQs();
            }
        });
        
        // Búsqueda con Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchFAQ();
            }
        });
    }
    
    console.log('🔍 Funcionalidad de búsqueda inicializada');
}

/**
 * Inicializa el filtro por categorías
 */
function initializeCategoryFilter() {
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const category = this.dataset.category;
            filterByCategory(category);
            
            // Actualizar estado visual
            categoryCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    console.log('📂 Filtro por categorías inicializado');
}

/**
 * Inicializa el comportamiento del acordeón
 */
function initializeAccordion() {
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const button = item.querySelector('.accordion-button');
        const content = item.querySelector('.accordion-collapse');
        
        if (button && content) {
            button.addEventListener('click', function() {
                // Registrar vista cuando se abre una pregunta
                const preguntaId = content.id;
                registerQuestionView(preguntaId);
                
                // Agregar animación
                setTimeout(() => {
                    content.style.transition = 'all 0.3s ease';
                }, 100);
            });
        }
    });
    
    console.log('📖 Comportamiento de acordeón inicializado');
}

/**
 * Inicializa las animaciones
 */
function initializeAnimations() {
    // Animaciones de entrada para las tarjetas
    const cards = document.querySelectorAll('.card, .category-card');
    
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
    
    // Efectos hover para categorías
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    console.log('🎬 Animaciones inicializadas');
}

/**
 * Inicializa las preguntas populares
 */
function initializePopularQuestions() {
    const popularLinks = document.querySelectorAll('.list-group-item[href^="#"]');
    
    popularLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            scrollToQuestion(targetId);
        });
    });
    
    console.log('⭐ Preguntas populares inicializadas');
}

/**
 * Inicializa el contacto de soporte
 */
function initializeContactSupport() {
    const contactButtons = document.querySelectorAll('button[onclick="contactSupport()"]');
    
    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            contactSupport();
        });
    });
    
    console.log('📞 Contacto de soporte inicializado');
}

/**
 * Realiza la búsqueda de preguntas
 * @param {string} query Término de búsqueda
 */
function performSearch(query) {
    if (query.length < 2) return;
    
    // Mostrar indicador de carga
    showLoadingIndicator();
    
    fetch('/propeasy/public/faq/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            query: query,
            categoria: currentCategory
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingIndicator();
        displaySearchResults(data);
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
function searchFAQ() {
    const searchInput = document.getElementById('search-faq');
    const query = searchInput ? searchInput.value.trim() : '';
    
    if (query.length < 2) {
        showNotification('Ingresa al menos 2 caracteres para buscar', 'warning');
        return;
    }
    
    performSearch(query);
}

/**
 * Muestra todas las preguntas (reset de búsqueda)
 */
function showAllFAQs() {
    const faqSections = document.querySelectorAll('.faq-section');
    const searchInput = document.getElementById('search-faq');
    
    // Limpiar búsqueda
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Mostrar todas las secciones
    faqSections.forEach(section => {
        section.style.display = 'block';
        section.style.opacity = '1';
    });
    
    // Remover resaltado de búsqueda
    const highlights = document.querySelectorAll('.search-highlight');
    highlights.forEach(highlight => {
        highlight.classList.remove('search-highlight');
    });
    
    // Resetear categorías
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.classList.remove('active');
    });
    
    currentCategory = '';
    
    showNotification('Mostrando todas las preguntas', 'info');
}

/**
 * Filtra preguntas por categoría
 * @param {string} category Categoría seleccionada
 */
function filterByCategory(category) {
    currentCategory = category;
    
    // Obtener preguntas de la categoría
    fetch(`/propeasy/public/faq/category?categoria=${category}`)
    .then(response => response.json())
    .then(data => {
        displayCategoryResults(data);
    })
    .catch(error => {
        console.error('Error al filtrar por categoría:', error);
        showNotification('Error al filtrar por categoría', 'error');
    });
}

/**
 * Muestra los resultados de búsqueda
 * @param {Object} data Datos de la búsqueda
 */
function displaySearchResults(data) {
    const faqSections = document.querySelectorAll('.faq-section');
    const resultsContainer = document.getElementById('search-results');
    
    // Ocultar secciones originales
    faqSections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Crear contenedor de resultados si no existe
    if (!resultsContainer) {
        const container = document.createElement('div');
        container.id = 'search-results';
        container.className = 'search-results-container';
        document.querySelector('.col-lg-8').appendChild(container);
    }
    
    const container = document.getElementById('search-results');
    container.innerHTML = '';
    
    if (data.total_resultados === 0) {
        container.innerHTML = `
            <div class="alert alert-info">
                <h5><i class="fas fa-search me-2"></i>No se encontraron resultados</h5>
                <p class="mb-0">No encontramos preguntas que coincidan con "${data.query}". 
                <a href="#" onclick="suggestQuestion('${data.query}')" class="text-decoration-none">¿Sugerir esta pregunta?</a></p>
            </div>
        `;
        return;
    }
    
    // Mostrar resultados
    container.innerHTML = `
        <div class="mb-4">
            <h3><i class="fas fa-search text-primary me-2"></i>Resultados de búsqueda</h3>
            <p class="text-muted">Se encontraron ${data.total_resultados} resultados para "${data.query}"</p>
        </div>
    `;
    
    data.resultados.forEach(resultado => {
        const resultCard = document.createElement('div');
        resultCard.className = 'card mb-3';
        resultCard.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-question-circle text-primary me-2"></i>
                    ${highlightText(resultado.pregunta, data.query)}
                </h5>
                <p class="card-text">${highlightText(resultado.respuesta, data.query)}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-${getCategoryColor(resultado.categoria)}">${getCategoryName(resultado.categoria)}</span>
                    <small class="text-muted">
                        <i class="fas fa-eye me-1"></i>${resultado.vistas} vistas
                    </small>
                </div>
            </div>
        `;
        container.appendChild(resultCard);
    });
    
    // Agregar botón para volver
    const backButton = document.createElement('div');
    backButton.className = 'text-center mt-4';
    backButton.innerHTML = `
        <button class="btn btn-outline-primary" onclick="showAllFAQs()">
            <i class="fas fa-arrow-left me-2"></i>Ver todas las preguntas
        </button>
    `;
    container.appendChild(backButton);
    
    showNotification(`Se encontraron ${data.total_resultados} resultados`, 'success');
}

/**
 * Muestra los resultados por categoría
 * @param {Object} data Datos de la categoría
 */
function displayCategoryResults(data) {
    const faqSections = document.querySelectorAll('.faq-section');
    const resultsContainer = document.getElementById('category-results');
    
    // Ocultar secciones originales
    faqSections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Crear contenedor de resultados si no existe
    if (!resultsContainer) {
        const container = document.createElement('div');
        container.id = 'category-results';
        container.className = 'category-results-container';
        document.querySelector('.col-lg-8').appendChild(container);
    }
    
    const container = document.getElementById('category-results');
    container.innerHTML = `
        <div class="mb-4">
            <h3><i class="fas fa-${getCategoryIcon(data.categoria)} text-${getCategoryColor(data.categoria)} me-2"></i>${getCategoryName(data.categoria)}</h3>
            <p class="text-muted">${data.total_preguntas} preguntas en esta categoría</p>
        </div>
    `;
    
    // Crear acordeón con las preguntas
    const accordion = document.createElement('div');
    accordion.className = 'accordion';
    accordion.id = `accordion${data.categoria.charAt(0).toUpperCase() + data.categoria.slice(1)}`;
    
    data.preguntas.forEach((pregunta, index) => {
        const accordionItem = document.createElement('div');
        accordionItem.className = 'accordion-item';
        accordionItem.innerHTML = `
            <h2 class="accordion-header">
                <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#${pregunta.id}">
                    ${pregunta.pregunta}
                </button>
            </h2>
            <div id="${pregunta.id}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" data-bs-parent="#accordion${data.categoria.charAt(0).toUpperCase() + data.categoria.slice(1)}">
                <div class="accordion-body">
                    ${pregunta.respuesta}
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>${pregunta.vistas} vistas
                        </small>
                    </div>
                </div>
            </div>
        `;
        accordion.appendChild(accordionItem);
    });
    
    container.appendChild(accordion);
    
    // Agregar botón para volver
    const backButton = document.createElement('div');
    backButton.className = 'text-center mt-4';
    backButton.innerHTML = `
        <button class="btn btn-outline-primary" onclick="showAllFAQs()">
            <i class="fas fa-arrow-left me-2"></i>Ver todas las categorías
        </button>
    `;
    container.appendChild(backButton);
    
    showNotification(`Mostrando ${data.total_preguntas} preguntas de ${getCategoryName(data.categoria)}`, 'info');
}

/**
 * Resalta texto en los resultados de búsqueda
 * @param {string} text Texto original
 * @param {string} query Término de búsqueda
 * @returns {string} Texto con resaltado
 */
function highlightText(text, query) {
    if (!query) return text;
    
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<span class="search-highlight">$1</span>');
}

/**
 * Obtiene el color de la categoría
 * @param {string} categoria Categoría
 * @returns {string} Color de Bootstrap
 */
function getCategoryColor(categoria) {
    const colors = {
        'propiedades': 'primary',
        'financiamiento': 'success',
        'servicios': 'info',
        'cuenta': 'warning'
    };
    
    return colors[categoria] || 'secondary';
}

/**
 * Obtiene el nombre de la categoría
 * @param {string} categoria Categoría
 * @returns {string} Nombre de la categoría
 */
function getCategoryName(categoria) {
    const names = {
        'propiedades': 'Propiedades',
        'financiamiento': 'Financiamiento',
        'servicios': 'Servicios',
        'cuenta': 'Mi Cuenta'
    };
    
    return names[categoria] || categoria;
}

/**
 * Obtiene el ícono de la categoría
 * @param {string} categoria Categoría
 * @returns {string} Nombre del ícono
 */
function getCategoryIcon(categoria) {
    const icons = {
        'propiedades': 'home',
        'financiamiento': 'calculator',
        'servicios': 'tools',
        'cuenta': 'user'
    };
    
    return icons[categoria] || 'question-circle';
}

/**
 * Registra la vista de una pregunta
 * @param {string} preguntaId ID de la pregunta
 */
function registerQuestionView(preguntaId) {
    fetch('/propeasy/public/faq/register-view', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            pregunta_id: preguntaId,
            user_id: getUserId()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Vista registrada:', preguntaId);
        }
    })
    .catch(error => {
        console.error('Error al registrar vista:', error);
    });
}

/**
 * Sugiere una nueva pregunta
 * @param {string} pregunta Pregunta sugerida
 */
function suggestQuestion(pregunta = '') {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'suggestQuestionModal';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-lightbulb me-2"></i>Sugerir Nueva Pregunta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="suggestQuestionForm">
                        <div class="mb-3">
                            <label for="suggestPregunta" class="form-label">Tu pregunta</label>
                            <textarea class="form-control" id="suggestPregunta" rows="3" required>${pregunta}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="suggestCategoria" class="form-label">Categoría sugerida</label>
                            <select class="form-select" id="suggestCategoria">
                                <option value="">Seleccionar categoría</option>
                                <option value="propiedades">Propiedades</option>
                                <option value="financiamiento">Financiamiento</option>
                                <option value="servicios">Servicios</option>
                                <option value="cuenta">Mi Cuenta</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="suggestEmail" class="form-label">Tu email (opcional)</label>
                            <input type="email" class="form-control" id="suggestEmail" placeholder="para notificarte cuando agreguemos la respuesta">
                        </div>
                        <div class="mb-3">
                            <label for="suggestNombre" class="form-label">Tu nombre (opcional)</label>
                            <input type="text" class="form-control" id="suggestNombre" placeholder="tu nombre">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitSuggestion()">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Sugerencia
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
 * Envía la sugerencia de pregunta
 */
function submitSuggestion() {
    const pregunta = document.getElementById('suggestPregunta').value.trim();
    const categoria = document.getElementById('suggestCategoria').value;
    const email = document.getElementById('suggestEmail').value.trim();
    const nombre = document.getElementById('suggestNombre').value.trim();
    
    if (!pregunta) {
        showNotification('Por favor ingresa tu pregunta', 'warning');
        return;
    }
    
    fetch('/propeasy/public/faq/suggest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            pregunta: pregunta,
            categoria: categoria,
            email: email,
            nombre: nombre
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('suggestQuestionModal')).hide();
        } else {
            showNotification(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error al enviar sugerencia:', error);
        showNotification('Error al enviar la sugerencia', 'error');
    });
}

/**
 * Contacta al soporte
 */
function contactSupport() {
    // Redirigir a la página de contacto con parámetros específicos
    window.location.href = '/propeasy/public/contact?tipo=soporte&origen=faq';
}

/**
 * Hace scroll a una pregunta específica
 * @param {string} questionId ID de la pregunta
 */
function scrollToQuestion(questionId) {
    const question = document.getElementById(questionId);
    if (question) {
        question.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Abrir el acordeón si está cerrado
        const accordionButton = question.querySelector('.accordion-button');
        const accordionCollapse = question.querySelector('.accordion-collapse');
        
        if (accordionButton && accordionCollapse && !accordionCollapse.classList.contains('show')) {
            accordionButton.click();
        }
        
        // Efecto visual
        question.style.backgroundColor = '#f8f9fa';
        setTimeout(() => {
            question.style.backgroundColor = '';
        }, 2000);
    }
}

/**
 * Muestra indicador de carga
 */
function showLoadingIndicator() {
    const searchInput = document.getElementById('search-faq');
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
    const searchInput = document.getElementById('search-faq');
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

/**
 * Obtiene el ID del usuario actual (simulado)
 * @returns {number|null} ID del usuario
 */
function getUserId() {
    // En producción, obtener de la sesión
    return 123; // Simulado
}

// Agregar estilos CSS para funcionalidades
const styles = `
    .search-highlight {
        background-color: #ffeb3b;
        padding: 2px 4px;
        border-radius: 3px;
        animation: highlight-pulse 0.5s ease-in-out;
    }
    
    @keyframes highlight-pulse {
        0% { background-color: #ffeb3b; }
        50% { background-color: #ffc107; }
        100% { background-color: #ffeb3b; }
    }
    
    .category-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .category-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .category-card.active {
        border-color: var(--bs-primary);
        background-color: var(--bs-primary);
        color: white;
    }
    
    .category-card.active .text-primary,
    .category-card.active .text-success,
    .category-card.active .text-info,
    .category-card.active .text-warning {
        color: white !important;
    }
    
    .search-results-container,
    .category-results-container {
        animation: slide-in-up 0.5s ease-out;
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
    
    .accordion-button:not(.collapsed) {
        background-color: var(--bs-primary);
        color: white;
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
`;

// Insertar estilos en el head
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

console.log('🎨 Estilos CSS agregados para funcionalidades de FAQ'); 