/**
 * JavaScript para la página de Política de Privacidad
 * Maneja funcionalidades interactivas y formularios de solicitudes
 */

class PrivacyPage {
    constructor() {
        this.init();
    }

    /**
     * Inicializa la página de política de privacidad
     */
    init() {
        this.bindEvents();
        this.initSmoothScroll();
        this.initFormValidation();
        this.initDataRequests();
        this.initDownloadFunctionality();
        this.initChangelog();
        this.initCookieConsent();
    }

    /**
     * Vincula eventos a elementos del DOM
     */
    bindEvents() {
        // Navegación de contenido
        this.setupContentNavigation();

        // Formularios de solicitudes
        this.setupRequestForms();

        // Botones de descarga
        this.setupDownloadButtons();

        // Enlaces de contacto
        this.setupContactLinks();
    }

    /**
     * Configura navegación de contenido
     */
    setupContentNavigation() {
        const navLinks = document.querySelectorAll('a[href^="#"]');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Configura smooth scroll
     */
    initSmoothScroll() {
        // Smooth scroll para enlaces internos
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Inicializa validación de formularios
     */
    initFormValidation() {
        // Configurar validación personalizada de Bootstrap
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    /**
     * Inicializa solicitudes de datos
     */
    initDataRequests() {
        // Formulario de acceso a datos
        const accessForm = document.getElementById('dataAccessForm');
        if (accessForm) {
            accessForm.addEventListener('submit', (e) => this.handleDataAccess(e));
        }

        // Formulario de eliminación de datos
        const deletionForm = document.getElementById('dataDeletionForm');
        if (deletionForm) {
            deletionForm.addEventListener('submit', (e) => this.handleDataDeletion(e));
        }

        // Formulario de rectificación de datos
        const rectificationForm = document.getElementById('dataRectificationForm');
        if (rectificationForm) {
            rectificationForm.addEventListener('submit', (e) => this.handleDataRectification(e));
        }
    }

    /**
     * Configura formularios de solicitudes
     */
    setupRequestForms() {
        const forms = document.querySelectorAll('.data-request-form');
        forms.forEach(form => {
            // Validación en tiempo real
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldError(input));
            });
        });
    }

    /**
     * Valida campo del formulario
     */
    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        field.classList.remove('is-invalid');
        this.removeFieldError(field);

        switch (field.name) {
            case 'name':
                if (!value) {
                    isValid = false;
                    errorMessage = 'El nombre es requerido';
                } else if (value.length < 2) {
                    isValid = false;
                    errorMessage = 'El nombre debe tener al menos 2 caracteres';
                }
                break;

            case 'email':
                if (!value) {
                    isValid = false;
                    errorMessage = 'El email es requerido';
                } else if (!this.isValidEmail(value)) {
                    isValid = false;
                    errorMessage = 'Ingresa un email válido';
                }
                break;

            case 'identification':
                if (!value) {
                    isValid = false;
                    errorMessage = 'La identificación es requerida';
                } else if (!this.isValidRUT(value)) {
                    isValid = false;
                    errorMessage = 'Ingresa un RUT válido';
                }
                break;

            case 'reason':
                if (field.required && !value) {
                    isValid = false;
                    errorMessage = 'El motivo es requerido';
                }
                break;

            case 'current_data':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Los datos actuales son requeridos';
                }
                break;

            case 'corrected_data':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Los datos corregidos son requeridos';
                }
                break;
        }

        if (!isValid) {
            field.classList.add('is-invalid');
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    /**
     * Valida formato de email
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Valida formato de RUT chileno
     */
    isValidRUT(rut) {
        // Validación básica de RUT chileno
        const rutRegex = /^[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9kK]$/;
        return rutRegex.test(rut);
    }

    /**
     * Muestra error de campo
     */
    showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.id = `${field.name}-error`;
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * Remueve error de campo
     */
    removeFieldError(field) {
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * Limpia error de campo
     */
    clearFieldError(field) {
        field.classList.remove('is-invalid');
        this.removeFieldError(field);
    }

    /**
     * Maneja solicitud de acceso a datos
     */
    async handleDataAccess(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Validar todos los campos
        const fields = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            this.showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }

        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

        try {
            const formData = new FormData(form);

            const response = await fetch('/propeasy/public/privacy/dataAccess', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                form.reset();
                this.clearAllErrors(form);
                
                // Mostrar número de referencia
                if (result.reference) {
                    this.showReferenceModal(result.reference, 'Acceso a Datos');
                }
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al enviar solicitud:', error);
            this.showNotification('Error al enviar la solicitud. Intenta nuevamente.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    /**
     * Maneja solicitud de eliminación de datos
     */
    async handleDataDeletion(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Validar todos los campos
        const fields = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            this.showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }

        // Confirmar eliminación
        if (!confirm('¿Estás seguro de que deseas solicitar la eliminación de tus datos? Esta acción no se puede deshacer.')) {
            return;
        }

        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

        try {
            const formData = new FormData(form);

            const response = await fetch('/propeasy/public/privacy/dataDeletion', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                form.reset();
                this.clearAllErrors(form);
                
                // Mostrar número de referencia
                if (result.reference) {
                    this.showReferenceModal(result.reference, 'Eliminación de Datos');
                }
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al enviar solicitud:', error);
            this.showNotification('Error al enviar la solicitud. Intenta nuevamente.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    /**
     * Maneja solicitud de rectificación de datos
     */
    async handleDataRectification(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Validar todos los campos
        const fields = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            this.showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }

        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

        try {
            const formData = new FormData(form);

            const response = await fetch('/propeasy/public/privacy/dataRectification', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                form.reset();
                this.clearAllErrors(form);
                
                // Mostrar número de referencia
                if (result.reference) {
                    this.showReferenceModal(result.reference, 'Rectificación de Datos');
                }
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al enviar solicitud:', error);
            this.showNotification('Error al enviar la solicitud. Intenta nuevamente.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    /**
     * Limpia todos los errores del formulario
     */
    clearAllErrors(form) {
        const fields = form.querySelectorAll('input, textarea, select');
        fields.forEach(field => {
            field.classList.remove('is-invalid');
            this.removeFieldError(field);
        });
    }

    /**
     * Inicializa funcionalidad de descarga
     */
    initDownloadFunctionality() {
        // Configurar función global para descarga
        window.downloadPrivacyPolicy = () => this.downloadPolicy();
    }

    /**
     * Configura botones de descarga
     */
    setupDownloadButtons() {
        const downloadBtns = document.querySelectorAll('.download-policy-btn');
        downloadBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.downloadPolicy();
            });
        });
    }

    /**
     * Descarga política de privacidad
     */
    async downloadPolicy() {
        try {
            const response = await fetch('/propeasy/public/privacy/download');
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'politica-privacidad-propeasy.pdf';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                this.showNotification('Descarga iniciada', 'success');
            } else {
                this.showNotification('Error al descargar el archivo', 'error');
            }
        } catch (error) {
            console.error('Error al descargar:', error);
            this.showNotification('Error al descargar el archivo', 'error');
        }
    }

    /**
     * Inicializa changelog
     */
    initChangelog() {
        this.loadChangelog();
    }

    /**
     * Carga historial de cambios
     */
    async loadChangelog() {
        try {
            const response = await fetch('/propeasy/public/privacy/changelog');
            const result = await response.json();
            
            if (result.success) {
                this.displayChangelog(result.data);
            }
        } catch (error) {
            console.error('Error al cargar changelog:', error);
        }
    }

    /**
     * Muestra changelog en la página
     */
    displayChangelog(changelog) {
        const changelogContainer = document.getElementById('changelogContainer');
        if (!changelogContainer) return;

        let html = '';
        changelog.forEach(version => {
            html += `
                <div class="mb-3">
                    <h6 class="fw-bold">${version.date} - Versión ${version.version}</h6>
                    <ul class="text-muted small">
                        ${version.changes.map(change => `<li>${change}</li>`).join('')}
                    </ul>
                </div>
            `;
        });

        changelogContainer.innerHTML = html;
    }

    /**
     * Inicializa consentimiento de cookies
     */
    initCookieConsent() {
        // Verificar si ya se ha dado consentimiento
        const consent = localStorage.getItem('cookieConsent');
        if (!consent) {
            this.showCookieConsent();
        }
    }

    /**
     * Muestra banner de consentimiento de cookies
     */
    showCookieConsent() {
        const banner = document.createElement('div');
        banner.className = 'cookie-consent-banner';
        banner.style.cssText = `
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 1rem;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: space-between;
        `;
        
        banner.innerHTML = `
            <div>
                <p class="mb-0">Utilizamos cookies para mejorar tu experiencia. Al continuar navegando, aceptas nuestra política de cookies.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-light btn-sm" onclick="this.parentElement.parentElement.remove()">Rechazar</button>
                <button class="btn btn-primary btn-sm" onclick="acceptCookies()">Aceptar</button>
            </div>
        `;
        
        document.body.appendChild(banner);
    }

    /**
     * Configura enlaces de contacto
     */
    setupContactLinks() {
        const contactLinks = document.querySelectorAll('.contact-link');
        contactLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const type = link.dataset.type;
                this.openContactModal(type);
            });
        });
    }

    /**
     * Abre modal de contacto
     */
    openContactModal(type) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Contacto - ${type}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Para contactarnos sobre ${type.toLowerCase()}, puedes:</p>
                        <ul>
                            <li>Email: ${type === 'Privacidad' ? 'privacy@propeasy.cl' : 'legal@propeasy.cl'}</li>
                            <li>Teléfono: +56 2 2345 6789</li>
                            <li>Horario: Lun-Vie 9:00-18:00</li>
                        </ul>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
        
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
        });
    }

    /**
     * Muestra modal con número de referencia
     */
    showReferenceModal(reference, type) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Solicitud Enviada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6>Tu solicitud de ${type} ha sido enviada exitosamente</h6>
                        <p class="text-muted">Número de referencia: <strong>${reference}</strong></p>
                        <p class="text-muted small">Guarda este número para hacer seguimiento de tu solicitud.</p>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
        
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
        });
    }

    /**
     * Muestra notificación
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
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

    /**
     * Carga información desde API
     */
    async loadPrivacyInfo() {
        try {
            const response = await fetch('/propeasy/public/privacy/info');
            const result = await response.json();
            
            if (result.success) {
                this.updatePrivacyInfo(result.data);
            }
        } catch (error) {
            console.error('Error al cargar información de privacidad:', error);
        }
    }

    /**
     * Actualiza información de privacidad en el DOM
     */
    updatePrivacyInfo(data) {
        // Actualizar fecha de última actualización
        const lastUpdatedElement = document.querySelector('[data-last-updated]');
        if (lastUpdatedElement && data.lastUpdated) {
            lastUpdatedElement.textContent = data.lastUpdated;
        }

        // Actualizar información de contacto
        const contactElements = document.querySelectorAll('[data-contact]');
        contactElements.forEach(element => {
            const contactType = element.dataset.contact;
            if (data.company && data.company[contactType]) {
                element.textContent = data.company[contactType];
            }
        });
    }
}

// Funciones globales
window.acceptCookies = function() {
    localStorage.setItem('cookieConsent', 'accepted');
    const banner = document.querySelector('.cookie-consent-banner');
    if (banner) {
        banner.remove();
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new PrivacyPage();
});

// Exportar para uso global
window.PrivacyPage = PrivacyPage; 