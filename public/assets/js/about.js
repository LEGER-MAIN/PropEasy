/**
 * JavaScript para la página "Nosotros"
 * Maneja animaciones, contadores y funcionalidades interactivas
 */

class AboutPage {
    constructor() {
        this.init();
    }

    /**
     * Inicializa la página "Nosotros"
     */
    init() {
        this.bindEvents();
        this.initCounters();
        this.initAnimations();
        this.initTestimonialSlider();
        this.initTeamCards();
        this.initValuesCards();
        this.initAwardsCards();
        this.initTimeline();
        this.initContactForm();
    }

    /**
     * Vincula eventos a elementos del DOM
     */
    bindEvents() {
        // Smooth scroll para enlaces internos
        this.setupSmoothScroll();

        // Lazy loading de imágenes
        this.setupLazyLoading();

        // Intersection Observer para animaciones
        this.setupIntersectionObserver();

        // Formulario de contacto corporativo
        this.setupCorporateContact();
    }

    /**
     * Configura smooth scroll
     */
    setupSmoothScroll() {
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
     * Configura lazy loading
     */
    setupLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    /**
     * Configura Intersection Observer para animaciones
     */
    setupIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observar elementos animables
        const animatableElements = document.querySelectorAll('.card, .timeline-item, .stat-card');
        animatableElements.forEach(el => observer.observe(el));
    }

    /**
     * Inicializa contadores animados
     */
    initCounters() {
        const counters = document.querySelectorAll('.counter');
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.dataset.target);
                    const duration = 2000; // 2 segundos
                    const increment = target / (duration / 16); // 60fps
                    let current = 0;

                    const updateCounter = () => {
                        current += increment;
                        if (current < target) {
                            counter.textContent = Math.floor(current);
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target;
                        }
                    };

                    updateCounter();
                    counterObserver.unobserve(counter);
                }
            });
        });

        counters.forEach(counter => counterObserver.observe(counter));
    }

    /**
     * Inicializa animaciones
     */
    initAnimations() {
        // Animación de entrada para cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in-up');
        });

        // Animación para timeline
        const timelineItems = document.querySelectorAll('.timeline-item');
        timelineItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.2}s`;
            item.classList.add('slide-in-left');
        });

        // Animación para estadísticas
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.15}s`;
            card.classList.add('zoom-in');
        });
    }

    /**
     * Inicializa slider de testimonios
     */
    initTestimonialSlider() {
        const testimonials = document.querySelectorAll('.testimonial-card');
        let currentIndex = 0;

        const showTestimonial = (index) => {
            testimonials.forEach((testimonial, i) => {
                testimonial.style.display = i === index ? 'block' : 'none';
            });
        };

        const nextTestimonial = () => {
            currentIndex = (currentIndex + 1) % testimonials.length;
            showTestimonial(currentIndex);
        };

        const prevTestimonial = () => {
            currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
            showTestimonial(currentIndex);
        };

        // Auto-rotación cada 5 segundos
        setInterval(nextTestimonial, 5000);

        // Botones de navegación (si existen)
        const nextBtn = document.querySelector('.testimonial-next');
        const prevBtn = document.querySelector('.testimonial-prev');

        if (nextBtn) nextBtn.addEventListener('click', nextTestimonial);
        if (prevBtn) prevBtn.addEventListener('click', prevTestimonial);
    }

    /**
     * Inicializa tarjetas del equipo
     */
    initTeamCards() {
        const teamCards = document.querySelectorAll('.team-card');
        
        teamCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('team-card-hover');
            });

            card.addEventListener('mouseleave', () => {
                card.classList.remove('team-card-hover');
            });

            // Enlaces sociales
            const socialLinks = card.querySelectorAll('.social-link');
            socialLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const platform = link.dataset.platform;
                    const url = link.href;
                    this.openSocialLink(platform, url);
                });
            });
        });
    }

    /**
     * Inicializa tarjetas de valores
     */
    initValuesCards() {
        const valueCards = document.querySelectorAll('.value-card');
        
        valueCards.forEach(card => {
            card.addEventListener('click', () => {
                // Remover clase activa de todas las tarjetas
                valueCards.forEach(c => c.classList.remove('value-card-active'));
                // Agregar clase activa a la tarjeta clickeada
                card.classList.add('value-card-active');
                
                // Mostrar descripción detallada
                this.showValueDetails(card);
            });
        });
    }

    /**
     * Inicializa tarjetas de premios
     */
    initAwardsCards() {
        const awardCards = document.querySelectorAll('.award-card');
        
        awardCards.forEach(card => {
            card.addEventListener('click', () => {
                const awardName = card.querySelector('.card-title').textContent;
                const awardDescription = card.querySelector('.card-text').textContent;
                
                this.showAwardModal(awardName, awardDescription);
            });
        });
    }

    /**
     * Inicializa timeline
     */
    initTimeline() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        
        timelineItems.forEach((item, index) => {
            // Agregar delay de animación
            item.style.animationDelay = `${index * 0.3}s`;
            
            // Agregar evento click para mostrar detalles
            item.addEventListener('click', () => {
                this.showTimelineDetails(item, index);
            });
        });
    }

    /**
     * Inicializa formulario de contacto
     */
    initContactForm() {
        const contactForm = document.getElementById('corporateContactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => this.handleCorporateContact(e));
        }
    }

    /**
     * Configura contacto corporativo
     */
    setupCorporateContact() {
        const contactForm = document.getElementById('corporateContactForm');
        if (!contactForm) return;

        // Validación en tiempo real
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
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
            case 'subject':
                if (!value) {
                    isValid = false;
                    errorMessage = 'El asunto es requerido';
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
     * Maneja envío de formulario corporativo
     */
    async handleCorporateContact(e) {
        e.preventDefault();

        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Validar todos los campos
        const fields = form.querySelectorAll('input, textarea');
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
            formData.append('type', 'corporate');

            const response = await fetch('/propeasy/public/about/corporate-contact', {
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
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    /**
     * Limpia todos los errores del formulario
     */
    clearAllErrors() {
        const fields = document.querySelectorAll('#corporateContactForm input, #corporateContactForm textarea');
        fields.forEach(field => {
            field.classList.remove('is-invalid');
            this.removeFieldError(field);
        });
    }

    /**
     * Abre enlace social
     */
    openSocialLink(platform, url) {
        window.open(url, '_blank', 'width=600,height=400');
    }

    /**
     * Muestra detalles del valor
     */
    showValueDetails(card) {
        const valueName = card.querySelector('.card-title').textContent;
        const valueDescription = card.querySelector('.card-text').textContent;
        
        // Crear modal con detalles
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${valueName}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${valueDescription}</p>
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
     * Muestra modal de premio
     */
    showAwardModal(name, description) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${name}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${description}</p>
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
     * Muestra detalles del timeline
     */
    showTimelineDetails(item, index) {
        const year = item.querySelector('h6').textContent.split(' - ')[0];
        const title = item.querySelector('h6').textContent.split(' - ')[1];
        const description = item.querySelector('p').textContent;
        
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${year} - ${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${description}</p>
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
    async loadCompanyInfo() {
        try {
            const response = await fetch('/propeasy/public/about/info');
            const result = await response.json();
            
            if (result.success) {
                this.updateCompanyInfo(result.data);
            }
        } catch (error) {
            console.error('Error al cargar información de la empresa:', error);
        }
    }

    /**
     * Actualiza información de la empresa en el DOM
     */
    updateCompanyInfo(data) {
        // Actualizar estadísticas
        const statElements = document.querySelectorAll('[data-stat]');
        statElements.forEach(element => {
            const statType = element.dataset.stat;
            if (data.stats && data.stats[statType]) {
                element.textContent = data.stats[statType];
            }
        });

        // Actualizar información de contacto
        const contactElements = document.querySelectorAll('[data-contact]');
        contactElements.forEach(element => {
            const contactType = element.dataset.contact;
            if (data.contact && data.contact[contactType]) {
                element.textContent = data.contact[contactType];
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new AboutPage();
});

// Exportar para uso global
window.AboutPage = AboutPage; 