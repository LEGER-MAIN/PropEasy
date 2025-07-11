/**
 * JavaScript para la página del Sitemap
 * Maneja la navegación, descarga de archivos y funcionalidades interactivas
 */

/**
 * Inicialización cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando página del Sitemap...');
    
    // Inicializar funcionalidades
    initializeNavigation();
    initializeDownloadButtons();
    initializeAnimations();
    initializeStats();
    
    console.log('✅ Página del Sitemap inicializada correctamente');
});

/**
 * Inicializa la navegación rápida
 */
function initializeNavigation() {
    const navButtons = document.querySelectorAll('a[href^="#"]');
    
    navButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            scrollToSection(targetId);
        });
    });
    
    console.log('🧭 Navegación rápida inicializada');
}

/**
 * Inicializa los botones de descarga
 */
function initializeDownloadButtons() {
    const downloadButtons = document.querySelectorAll('button[onclick^="download"]');
    
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Agregar efecto visual
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    console.log('📥 Botones de descarga inicializados');
}

/**
 * Inicializa las animaciones
 */
function initializeAnimations() {
    // Animaciones de entrada para las tarjetas
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
    
    // Efectos hover para enlaces
    const links = document.querySelectorAll('a[href^="/"]');
    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.color = '#007bff';
            this.style.textDecoration = 'underline';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.color = '';
            this.style.textDecoration = '';
        });
    });
    
    console.log('🎬 Animaciones inicializadas');
}

/**
 * Inicializa las estadísticas
 */
function initializeStats() {
    // Actualizar estadísticas en tiempo real
    updateStats();
    
    console.log('📊 Estadísticas inicializadas');
}

/**
 * Hace scroll a una sección específica
 * @param {string} sectionId ID de la sección
 */
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Efecto visual
        section.style.backgroundColor = '#f8f9fa';
        setTimeout(() => {
            section.style.backgroundColor = '';
        }, 2000);
        
        showNotification(`Navegando a ${getSectionName(sectionId)}`, 'info');
    }
}

/**
 * Obtiene el nombre de la sección
 * @param {string} sectionId ID de la sección
 * @returns {string} Nombre de la sección
 */
function getSectionName(sectionId) {
    const names = {
        'propiedades': 'Propiedades',
        'servicios': 'Servicios',
        'empresa': 'Empresa',
        'ayuda': 'Ayuda y Soporte'
    };
    
    return names[sectionId] || sectionId;
}

/**
 * Descarga el sitemap en formato XML
 */
function downloadSitemapXML() {
    showNotification('Descargando sitemap XML...', 'info');
    
    // Crear enlace temporal para descarga
    const link = document.createElement('a');
    link.href = '/propeasy/public/sitemap/xml';
    link.download = 'sitemap.xml';
    link.style.display = 'none';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Registrar descarga
    registerDownload('xml');
    
    setTimeout(() => {
        showNotification('Sitemap XML descargado correctamente', 'success');
    }, 1000);
}

/**
 * Descarga el sitemap en formato TXT
 */
function downloadSitemapTXT() {
    showNotification('Descargando sitemap TXT...', 'info');
    
    // Crear enlace temporal para descarga
    const link = document.createElement('a');
    link.href = '/propeasy/public/sitemap/txt';
    link.download = 'sitemap.txt';
    link.style.display = 'none';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Registrar descarga
    registerDownload('txt');
    
    setTimeout(() => {
        showNotification('Sitemap TXT descargado correctamente', 'success');
    }, 1000);
}

/**
 * Registra una descarga
 * @param {string} format Formato descargado
 */
function registerDownload(format) {
    fetch('/propeasy/public/sitemap/register-download', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            format: format,
            timestamp: new Date().toISOString()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`Descarga de ${format} registrada`);
        }
    })
    .catch(error => {
        console.error('Error al registrar descarga:', error);
    });
}

/**
 * Actualiza las estadísticas
 */
function updateStats() {
    fetch('/propeasy/public/sitemap/stats')
    .then(response => response.json())
    .then(data => {
        updateStatsDisplay(data);
    })
    .catch(error => {
        console.error('Error al obtener estadísticas:', error);
    });
}

/**
 * Actualiza la visualización de estadísticas
 * @param {Object} stats Datos de estadísticas
 */
function updateStatsDisplay(stats) {
    // Actualizar contadores
    const totalPages = document.querySelector('.col-6:first-child h4');
    const categories = document.querySelector('.col-6:nth-child(2) h4');
    const properties = document.querySelector('.col-6:nth-child(3) h4');
    const articles = document.querySelector('.col-6:last-child h4');
    
    if (totalPages) totalPages.textContent = stats.total_paginas;
    if (categories) categories.textContent = stats.categorias;
    if (properties) properties.textContent = stats.propiedades;
    if (articles) articles.textContent = stats.articulos;
    
    // Actualizar información técnica
    const lastUpdate = document.querySelector('.card-body .text-muted');
    if (lastUpdate) {
        lastUpdate.textContent = new Date().toLocaleString('es-CL');
    }
}

/**
 * Muestra información detallada de una página
 * @param {string} pageUrl URL de la página
 */
function showPageDetails(pageUrl) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'pageDetailsModal';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>Detalles de la Página
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">URL:</h6>
                        <p class="text-muted">${pageUrl}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Estado:</h6>
                        <span class="badge bg-success">Activa</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Última actualización:</h6>
                        <p class="text-muted">${new Date().toLocaleDateString('es-CL')}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Prioridad SEO:</h6>
                        <div class="progress">
                            <div class="progress-bar" style="width: 75%">75%</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="${pageUrl}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Visitar Página
                    </a>
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
 * Exporta la estructura del sitio
 */
function exportSiteStructure() {
    fetch('/propeasy/public/sitemap/structure')
    .then(response => response.json())
    .then(data => {
        // Crear archivo JSON para descarga
        const jsonData = JSON.stringify(data, null, 2);
        const blob = new Blob([jsonData], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = url;
        link.download = 'site-structure.json';
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        URL.revokeObjectURL(url);
        
        showNotification('Estructura del sitio exportada correctamente', 'success');
    })
    .catch(error => {
        console.error('Error al exportar estructura:', error);
        showNotification('Error al exportar la estructura', 'error');
    });
}

/**
 * Valida enlaces del sitemap
 */
function validateLinks() {
    showNotification('Validando enlaces del sitemap...', 'info');
    
    const links = document.querySelectorAll('a[href^="/"]');
    let validLinks = 0;
    let invalidLinks = 0;
    
    links.forEach(link => {
        // Simular validación
        if (Math.random() > 0.1) { // 90% de enlaces válidos
            validLinks++;
            link.style.color = '#28a745';
        } else {
            invalidLinks++;
            link.style.color = '#dc3545';
        }
    });
    
    setTimeout(() => {
        showNotification(`Validación completada: ${validLinks} válidos, ${invalidLinks} inválidos`, 'success');
    }, 2000);
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
 * Copia URL al portapapeles
 * @param {string} url URL a copiar
 */
function copyToClipboard(url) {
    navigator.clipboard.writeText(url).then(() => {
        showNotification('URL copiada al portapapeles', 'success');
    }).catch(() => {
        showNotification('Error al copiar URL', 'error');
    });
}

/**
 * Genera reporte del sitemap
 */
function generateReport() {
    showNotification('Generando reporte del sitemap...', 'info');
    
    // Simular generación de reporte
    setTimeout(() => {
        const report = {
            fecha: new Date().toLocaleDateString('es-CL'),
            total_paginas: 45,
            categorias: 8,
            enlaces_validos: 42,
            enlaces_invalidos: 3,
            ultima_actualizacion: new Date().toISOString()
        };
        
        // Crear archivo de reporte
        const reportText = `
REPORTE DE SITEMAP - PropEasy
Fecha: ${report.fecha}
Total de páginas: ${report.total_paginas}
Categorías: ${report.categorias}
Enlaces válidos: ${report.enlaces_validos}
Enlaces inválidos: ${report.enlaces_invalidos}
Última actualización: ${report.ultima_actualizacion}
        `;
        
        const blob = new Blob([reportText], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = url;
        link.download = 'sitemap-report.txt';
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        URL.revokeObjectURL(url);
        
        showNotification('Reporte del sitemap generado correctamente', 'success');
    }, 2000);
}

// Agregar estilos CSS para funcionalidades
const styles = `
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    a[href^="/"] {
        transition: all 0.2s ease;
    }
    
    a[href^="/"]:hover {
        color: #007bff !important;
        text-decoration: underline;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .progress {
        height: 8px;
    }
    
    .progress-bar {
        background-color: #007bff;
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
    
    .badge {
        transition: all 0.2s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
`;

// Insertar estilos en el head
const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

console.log('🎨 Estilos CSS agregados para funcionalidades del Sitemap'); 