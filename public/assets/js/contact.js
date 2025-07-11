/**
 * JavaScript para la página de Contacto
 * Maneja formularios, validaciones y funcionalidades interactivas
 */

class ContactPage {
    constructor() {
        this.init();
    }

    /**
     * Inicializa la página de contacto
     */
    init() {
        this.bindEvents();
        this.initFormValidation();
        this.initMap();
        this.initSocialSharing();
        this.initNewsletterSubscription();
        this.initCallbackRequest();
        this.initReportBug();
    }

    /**
     * Vincula eventos a elementos del DOM
     */
    bindEvents() {
        // Formulario de contacto
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => this.handleContactSubmit(e));
        }

        // Validación en tiempo real
        this.setupRealTimeValidation();

        // Botones de contacto rápido
        this.setupQuickContact();

        // FAQ accordion
        this.setupFAQAccordion();

        // Redes sociales
        this.setupSocialLinks();
    }

    /**
     * Configura validación en tiempo real
     */
    setupRealTimeValidation() {
        const inputs = document.querySelectorAll('#contactForm input, #contactForm textarea, #contactForm select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }

    /**
     * Valida un campo específico
     */
    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Remover clases de error previas
        field.classList.remove('is-invalid');
        this.removeFieldError(field);

        // Validaciones específicas por tipo de campo
        switch (field.id) {
            case 'firstName':
            case 'lastName':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Este campo es requerido';
                } else if (value.length < 2) {
                    isValid = false;
                    errorMessage = 'Debe tener al menos 2 caracteres';
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

            case 'phone':
                if (value && !this.isValidPhone(value)) {
                    isValid = false;
                    errorMessage = 'Ingresa un teléfono válido';
                }
                break;

            case 'subject':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Selecciona un asunto';
                }
                break;

            case 'message':
                if (!value) {
                    isValid = false;
                    errorMessage = 'El mensaje es requerido';
                } else if (value.length < 10) {
                    isValid = false;
                    errorMessage = 'El mensaje debe tener al menos 10 caracteres';
                }
                break;
        }

        // Mostrar error si es necesario
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
     * Valida formato de teléfono
     */
    isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
        return phoneRegex.test(phone);
    }

    /**
     * Muestra error de campo
     */
    showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        errorDiv.id = `${field.id}-error`;
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
     * Inicializa validación del formulario
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
     * Maneja el envío del formulario de contacto
     */
    async handleContactSubmit(e) {
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
            // Preparar datos del formulario
            const formData = new FormData();
            formData.append('first_name', document.getElementById('firstName').value);
            formData.append('last_name', document.getElementById('lastName').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('subject', document.getElementById('subject').value);
            formData.append('message', document.getElementById('message').value);
            formData.append('newsletter', document.getElementById('newsletter').checked ? '1' : '0');
            formData.append('privacy', document.getElementById('privacy').checked ? '1' : '0');

            // Enviar formulario
            const response = await fetch('/propeasy/public/contact/send', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                form.reset();
                this.clearAllErrors();
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al enviar formulario:', error);
            this.showNotification('Error al enviar el mensaje. Intenta nuevamente.', 'error');
        } finally {
            // Restaurar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    /**
     * Limpia todos los errores del formulario
     */
    clearAllErrors() {
        const fields = document.querySelectorAll('#contactForm input, #contactForm textarea, #contactForm select');
        fields.forEach(field => {
            field.classList.remove('is-invalid');
            this.removeFieldError(field);
        });
    }

    /**
     * Configura contacto rápido
     */
    setupQuickContact() {
        // Botón de WhatsApp
        const whatsappBtn = document.querySelector('.btn-success');
        if (whatsappBtn) {
            whatsappBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.openWhatsApp();
            });
        }

        // Botón de llamada
        const callBtn = document.querySelector('.btn-primary');
        if (callBtn) {
            callBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.makeCall();
            });
        }
    }

    /**
     * Abre WhatsApp con mensaje predefinido
     */
    openWhatsApp() {
        const message = encodeURIComponent('Hola, me interesa obtener más información sobre sus propiedades.');
        const phone = '+56912345678';
        window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
    }

    /**
     * Realiza llamada telefónica
     */
    makeCall() {
        const phone = '+56223456789';
        window.location.href = `tel:${phone}`;
    }

    /**
     * Configura accordion de FAQs
     */
    setupFAQAccordion() {
        const accordionItems = document.querySelectorAll('.accordion-item');
        
        accordionItems.forEach(item => {
            const button = item.querySelector('.accordion-button');
            const content = item.querySelector('.accordion-collapse');
            
            if (button && content) {
                button.addEventListener('click', () => {
                    // Animar el contenido
                    content.style.transition = 'all 0.3s ease';
                });
            }
        });
    }

    /**
     * Configura enlaces de redes sociales
     */
    setupSocialLinks() {
        const socialLinks = document.querySelectorAll('.btn-outline-primary, .btn-outline-info, .btn-outline-success, .btn-outline-danger, .btn-outline-dark');
        
        socialLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const platform = this.getSocialPlatform(link);
                this.shareOnSocial(platform);
            });
        });
    }

    /**
     * Obtiene la plataforma social del enlace
     */
    getSocialPlatform(link) {
        const icon = link.querySelector('i');
        if (icon.classList.contains('fa-facebook-f')) return 'facebook';
        if (icon.classList.contains('fa-twitter')) return 'twitter';
        if (icon.classList.contains('fa-instagram')) return 'instagram';
        if (icon.classList.contains('fa-youtube')) return 'youtube';
        if (icon.classList.contains('fa-linkedin-in')) return 'linkedin';
        return 'facebook';
    }

    /**
     * Comparte en redes sociales
     */
    shareOnSocial(platform) {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent('PropEasy - Tu socio inmobiliario de confianza');
        const text = encodeURIComponent('Encuentra la propiedad perfecta con PropEasy');

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
            default:
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
        }

        window.open(shareUrl, '_blank', 'width=600,height=400');
    }

    /**
     * Inicializa mapa (simulación)
     */
    initMap() {
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            // En producción, aquí se cargaría Google Maps o similar
            console.log('Mapa inicializado');
        }
    }

    /**
     * Abre ubicación en Google Maps
     */
    openInMaps() {
        const address = encodeURIComponent('Av. Apoquindo 1234, Las Condes, Santiago, Chile');
        window.open(`https://www.google.com/maps/search/?api=1&query=${address}`, '_blank');
    }

    /**
     * Inicializa suscripción al newsletter
     */
    initNewsletterSubscription() {
        const newsletterForm = document.getElementById('newsletterForm');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => this.handleNewsletterSubmit(e));
        }
    }

    /**
     * Maneja suscripción al newsletter
     */
    async handleNewsletterSubmit(e) {
        e.preventDefault();
        
        const email = document.getElementById('newsletterEmail').value;
        const name = document.getElementById('newsletterName').value;

        if (!this.isValidEmail(email)) {
            this.showNotification('Por favor ingresa un email válido', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('email', email);
            formData.append('name', name);

            const response = await fetch('/propeasy/public/contact/newsletter', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                e.target.reset();
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al suscribirse:', error);
            this.showNotification('Error al suscribirse. Intenta nuevamente.', 'error');
        }
    }

    /**
     * Inicializa solicitud de callback
     */
    initCallbackRequest() {
        const callbackForm = document.getElementById('callbackForm');
        if (callbackForm) {
            callbackForm.addEventListener('submit', (e) => this.handleCallbackSubmit(e));
        }
    }

    /**
     * Maneja solicitud de callback
     */
    async handleCallbackSubmit(e) {
        e.preventDefault();
        
        const name = document.getElementById('callbackName').value;
        const phone = document.getElementById('callbackPhone').value;
        const preferredTime = document.getElementById('preferredTime').value;
        const subject = document.getElementById('callbackSubject').value;

        if (!name || !phone) {
            this.showNotification('Por favor completa todos los campos requeridos', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('preferred_time', preferredTime);
            formData.append('subject', subject);

            const response = await fetch('/propeasy/public/contact/callback', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                e.target.reset();
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al solicitar callback:', error);
            this.showNotification('Error al enviar solicitud. Intenta nuevamente.', 'error');
        }
    }

    /**
     * Inicializa reporte de bugs
     */
    initReportBug() {
        const reportForm = document.getElementById('reportForm');
        if (reportForm) {
            reportForm.addEventListener('submit', (e) => this.handleReportSubmit(e));
        }
    }

    /**
     * Maneja reporte de bugs
     */
    async handleReportSubmit(e) {
        e.preventDefault();
        
        const type = document.getElementById('reportType').value;
        const description = document.getElementById('reportDescription').value;
        const email = document.getElementById('reportEmail').value;
        const url = window.location.href;

        if (!type || !description) {
            this.showNotification('Por favor completa todos los campos requeridos', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('type', type);
            formData.append('description', description);
            formData.append('email', email);
            formData.append('url', url);

            const response = await fetch('/propeasy/public/contact/report', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                e.target.reset();
            } else {
                this.showNotification(result.message, 'error');
            }

        } catch (error) {
            console.error('Error al enviar reporte:', error);
            this.showNotification('Error al enviar reporte. Intenta nuevamente.', 'error');
        }
    }

    /**
     * Inicializa compartir en redes sociales
     */
    initSocialSharing() {
        // Funciones globales para compartir
        window.shareOnFacebook = () => this.shareOnSocial('facebook');
        window.shareOnTwitter = () => this.shareOnSocial('twitter');
        window.shareOnInstagram = () => this.shareOnSocial('instagram');
        window.shareOnYouTube = () => this.shareOnSocial('youtube');
        window.shareOnLinkedIn = () => this.shareOnSocial('linkedin');
        window.openInMaps = () => this.openInMaps();
    }

    /**
     * Muestra notificación
     */
    showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Agregar al DOM
        document.body.appendChild(notification);

        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    /**
     * Carga información de contacto desde API
     */
    async loadContactInfo() {
        try {
            const response = await fetch('/propeasy/public/contact/info');
            const result = await response.json();
            
            if (result.success) {
                this.updateContactInfo(result.data);
            }
        } catch (error) {
            console.error('Error al cargar información de contacto:', error);
        }
    }

    /**
     * Actualiza información de contacto en el DOM
     */
    updateContactInfo(data) {
        // Actualizar teléfonos
        const phoneElements = document.querySelectorAll('[data-phone]');
        phoneElements.forEach(element => {
            const type = element.dataset.phone;
            if (data.phones[type]) {
                element.textContent = data.phones[type];
                element.href = `tel:${data.phones[type]}`;
            }
        });

        // Actualizar emails
        const emailElements = document.querySelectorAll('[data-email]');
        emailElements.forEach(element => {
            const type = element.dataset.email;
            if (data.emails[type]) {
                element.textContent = data.emails[type];
                element.href = `mailto:${data.emails[type]}`;
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new ContactPage();
});

// Exportar para uso global
window.ContactPage = ContactPage; 