/**
 * JavaScript para la página de Términos y Condiciones
 * Maneja la interactividad, navegación y funcionalidades específicas
 */

// Variables globales
let currentSection = '';
let scrollTimeout;

/**
 * Inicialización cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando página de Términos y Condiciones...');
    
    // Inicializar funcionalidades
    initializeNavigation();
    initializeSmoothScrolling();
    initializeDownloadButton();
    initializeSectionTracking();
    initializeTooltips();
    initializeAnimations();
    
    // Verificar si hay parámetros en la URL para navegación directa
    checkUrlParameters();
    
    console.log('✅ Página de Términos y Condiciones inicializada correctamente');
});

/**
 * Inicializa la navegación por secciones
 */
function initializeNavigation() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                // Agregar clase activa al enlace
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Scroll suave a la sección
                scrollToSection(targetSection);
                
                // Actualizar URL sin recargar la página
                updateUrl(targetId);
            }
        });
    });
    
    console.log('📍 Navegación por secciones inicializada');
}

/**
 * Inicializa el scroll suave
 */
function initializeSmoothScrolling() {
    // Configurar scroll suave para toda la página
    document.documentElement.style.scrollBehavior = 'smooth';
    
    console.log('🔄 Scroll suave configurado');
}

/**
 * Inicializa el botón de descarga de PDF
 */
function initializeDownloadButton() {
    const downloadBtn = document.querySelector('button[onclick="downloadTerms()"]');
    
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            downloadTerms();
        });
    }
    
    console.log('📄 Botón de descarga inicializado');
}

/**
 * Inicializa el tracking de secciones activas
 */
function initializeSectionTracking() {
    const sections = document.querySelectorAll('[id^="aceptacion"], [id^="descripcion"], [id^="registro"], [id^="uso"], [id^="propiedad"], [id^="limitaciones"], [id^="terminacion"], [id^="contacto"]');
    
    // Observador de intersección para detectar secciones visibles
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const sectionId = entry.target.id;
                updateActiveSection(sectionId);
                updateUrl(sectionId);
            }
        });
    }, {
        threshold: 0.3,
        rootMargin: '-20% 0px -20% 0px'
    });
    
    sections.forEach(section => {
        observer.observe(section);
    });
    
    console.log('👁️ Tracking de secciones inicializado');
}

/**
 * Inicializa tooltips de Bootstrap
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    console.log('💡 Tooltips inicializados');
}

/**
 * Inicializa animaciones CSS
 */
function initializeAnimations() {
    // Agregar animaciones de entrada a las tarjetas
    const cards = document.querySelectorAll('.card');
    
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
    
    console.log('🎬 Animaciones inicializadas');
}

/**
 * Verifica parámetros en la URL para navegación directa
 */
function checkUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');
    
    if (section) {
        const targetSection = document.getElementById(section);
        if (targetSection) {
            setTimeout(() => {
                scrollToSection(targetSection);
                updateActiveSection(section);
            }, 500);
        }
    }
}

/**
 * Hace scroll suave a una sección específica
 * @param {HTMLElement} targetSection - Elemento objetivo
 */
function scrollToSection(targetSection) {
    const offset = 80; // Offset para el navbar fijo
    const targetPosition = targetSection.offsetTop - offset;
    
    window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
    });
    
    // Agregar efecto visual a la sección
    targetSection.style.backgroundColor = '#f8f9fa';
    setTimeout(() => {
        targetSection.style.backgroundColor = '';
    }, 2000);
}

/**
 * Actualiza la sección activa en la navegación
 * @param {string} sectionId - ID de la sección activa
 */
function updateActiveSection(sectionId) {
    if (currentSection === sectionId) return;
    
    currentSection = sectionId;
    
    // Actualizar enlaces de navegación
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${sectionId}`) {
            link.classList.add('active');
        }
    });
    
    // Actualizar indicador visual
    updateSectionIndicator(sectionId);
}

/**
 * Actualiza el indicador visual de la sección activa
 * @param {string} sectionId - ID de la sección activa
 */
function updateSectionIndicator(sectionId) {
    // Remover indicador anterior
    const previousIndicator = document.querySelector('.section-indicator');
    if (previousIndicator) {
        previousIndicator.remove();
    }
    
    // Crear nuevo indicador
    const indicator = document.createElement('div');
    indicator.className = 'section-indicator';
    indicator.style.cssText = `
        position: fixed;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        background: var(--bs-primary);
        color: white;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    indicator.textContent = getSectionTitle(sectionId);
    document.body.appendChild(indicator);
    
    // Mostrar indicador
    setTimeout(() => {
        indicator.style.opacity = '1';
    }, 100);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        indicator.style.opacity = '0';
        setTimeout(() => {
            if (indicator.parentNode) {
                indicator.remove();
            }
        }, 300);
    }, 3000);
}

/**
 * Obtiene el título de una sección
 * @param {string} sectionId - ID de la sección
 * @returns {string} Título de la sección
 */
function getSectionTitle(sectionId) {
    const titles = {
        'aceptacion-terminos': 'Aceptación',
        'descripcion-servicios': 'Servicios',
        'registro-usuario': 'Registro',
        'uso-plataforma': 'Uso',
        'propiedad-intelectual': 'Propiedad',
        'limitaciones-responsabilidad': 'Responsabilidad',
        'terminacion': 'Terminación',
        'contacto': 'Contacto'
    };
    
    return titles[sectionId] || 'Sección';
}

/**
 * Actualiza la URL sin recargar la página
 * @param {string} sectionId - ID de la sección
 */
function updateUrl(sectionId) {
    const url = new URL(window.location);
    url.searchParams.set('section', sectionId);
    window.history.pushState({}, '', url);
}

/**
 * Descarga el PDF de términos y condiciones
 */
function downloadTerms() {
    const downloadBtn = document.querySelector('button[onclick="downloadTerms()"]');
    
    if (downloadBtn) {
        // Cambiar estado del botón
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generando PDF...';
        downloadBtn.disabled = true;
        
        // Realizar petición para descargar PDF
        fetch('/propeasy/public/terms/download-pdf', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Error al generar el PDF');
        })
        .then(blob => {
            // Crear enlace de descarga
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'terminos-condiciones-propeasy.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            // Mostrar mensaje de éxito
            showNotification('PDF descargado correctamente', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al descargar el PDF', 'error');
        })
        .finally(() => {
            // Restaurar botón
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        });
    }
}

/**
 * Muestra una notificación
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de notificación (success, error, warning, info)
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
 * @param {string} type - Tipo de notificación
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
 * Valida si el usuario ha aceptado los términos
 * @param {number} userId - ID del usuario
 * @returns {Promise<boolean>} True si ha aceptado los términos
 */
async function checkTermsAcceptance(userId) {
    try {
        const response = await fetch('/propeasy/public/terms/check-acceptance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId })
        });
        
        if (response.ok) {
            const data = await response.json();
            return data.accepted;
        }
        
        return false;
    } catch (error) {
        console.error('Error al verificar aceptación:', error);
        return false;
    }
}

/**
 * Registra la aceptación de términos por parte del usuario
 * @param {number} userId - ID del usuario
 * @param {string} version - Versión de términos aceptada
 * @returns {Promise<boolean>} True si se registró correctamente
 */
async function acceptTerms(userId, version = '2.1') {
    try {
        const response = await fetch('/propeasy/public/terms/accept', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                user_id: userId,
                version: version
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                showNotification('Términos aceptados correctamente', 'success');
                return true;
            }
        }
        
        showNotification('Error al aceptar los términos', 'error');
        return false;
    } catch (error) {
        console.error('Error al aceptar términos:', error);
        showNotification('Error al aceptar los términos', 'error');
        return false;
    }
}

/**
 * Obtiene estadísticas de aceptación de términos
 * @returns {Promise<Object>} Estadísticas de aceptación
 */
async function getAcceptanceStats() {
    try {
        const response = await fetch('/propeasy/public/terms/stats');
        
        if (response.ok) {
            return await response.json();
        }
        
        return null;
    } catch (error) {
        console.error('Error al obtener estadísticas:', error);
        return null;
    }
}

/**
 * Actualiza las estadísticas en la interfaz
 */
async function updateStatsDisplay() {
    const stats = await getAcceptanceStats();
    
    if (stats) {
        // Actualizar elementos de estadísticas si existen
        const totalUsersEl = document.getElementById('total-users');
        const acceptedUsersEl = document.getElementById('accepted-users');
        const acceptanceRateEl = document.getElementById('acceptance-rate');
        
        if (totalUsersEl) totalUsersEl.textContent = stats.total_users.toLocaleString();
        if (acceptedUsersEl) acceptedUsersEl.textContent = stats.accepted_users.toLocaleString();
        if (acceptanceRateEl) acceptanceRateEl.textContent = `${stats.acceptance_rate}%`;
    }
}

/**
 * Función para copiar texto al portapapeles
 * @param {string} text - Texto a copiar
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Texto copiado al portapapeles', 'success');
    }).catch(() => {
        showNotification('Error al copiar texto', 'error');
    });
}

/**
 * Función para imprimir la página
 */
function printTerms() {
    window.print();
}

/**
 * Función para compartir en redes sociales
 * @param {string} platform - Plataforma de redes sociales
 */
function shareTerms(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('Términos y Condiciones - PropEasy');
    const text = encodeURIComponent('Conoce las condiciones de uso de nuestros servicios inmobiliarios');
    
    let shareUrl = '';
    
    switch (platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${text}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
            break;
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${text}%20${url}`;
            break;
        default:
            return;
    }
    
    window.open(shareUrl, '_blank', 'width=600,height=400');
}

/**
 * Función para buscar en el contenido
 * @param {string} query - Término de búsqueda
 */
function searchInTerms(query) {
    if (!query.trim()) return;
    
    // Remover resaltado anterior
    const previousHighlights = document.querySelectorAll('.search-highlight');
    previousHighlights.forEach(el => {
        el.classList.remove('search-highlight');
    });
    
    // Buscar y resaltar
    const content = document.querySelector('.col-lg-8');
    const regex = new RegExp(query, 'gi');
    
    function highlightText(node) {
        if (node.nodeType === 3) { // Text node
            const text = node.textContent;
            const matches = text.match(regex);
            
            if (matches) {
                const highlightedText = text.replace(regex, match => 
                    `<span class="search-highlight">${match}</span>`
                );
                const span = document.createElement('span');
                span.innerHTML = highlightedText;
                node.parentNode.replaceChild(span, node);
            }
        } else if (node.nodeType === 1 && node.childNodes) { // Element node
            Array.from(node.childNodes).forEach(highlightText);
        }
    }
    
    highlightText(content);
    
    // Scroll al primer resultado
    const firstHighlight = document.querySelector('.search-highlight');
    if (firstHighlight) {
        firstHighlight.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    showNotification(`Se encontraron ${document.querySelectorAll('.search-highlight').length} resultados`, 'info');
}

/**
 * Función para exportar términos como texto
 */
function exportAsText() {
    const content = document.querySelector('.col-lg-8');
    const text = content.textContent || content.innerText;
    
    const blob = new Blob([text], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = 'terminos-condiciones-propeasy.txt';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
    
    showNotification('Términos exportados como texto', 'success');
}

// Event listeners adicionales
document.addEventListener('keydown', function(e) {
    // Navegación con teclado
    if (e.ctrlKey || e.metaKey) {
        switch (e.key) {
            case 'f':
                e.preventDefault();
                const searchInput = document.getElementById('search-terms');
                if (searchInput) {
                    searchInput.focus();
                }
                break;
            case 'p':
                e.preventDefault();
                printTerms();
                break;
        }
    }
});

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
    
    .section-indicator {
        animation: slide-in-right 0.3s ease-out;
    }
    
    @keyframes slide-in-right {
        from {
            transform: translateX(100px) translateY(-50%);
            opacity: 0;
        }
        to {
            transform: translateX(0) translateY(-50%);
            opacity: 1;
        }
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    a[href^="#"].active {
        color: var(--bs-primary) !important;
        font-weight: bold;
    }
    
    a[href^="#"].active i {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }
`;

// Insertar estilos en el head
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

console.log('🎨 Estilos CSS agregados para funcionalidades de términos y condiciones'); 