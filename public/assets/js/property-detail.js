/**
 * JavaScript para el Detalle de Propiedad
 * Maneja la interactividad y funcionalidades específicas de la página de detalle
 */

class PropertyDetail {
    constructor() {
        this.propertyId = this.getPropertyIdFromUrl();
        this.isFavorite = false;
        this.init();
    }

    /**
     * Inicializa el detalle de propiedad
     */
    init() {
        this.bindEvents();
        this.initCarousel();
        this.initLightbox();
        this.loadPropertyStats();
        this.initMortgageCalculator();
        this.checkFavoriteStatus();
    }

    /**
     * Obtiene el ID de la propiedad desde la URL
     */
    getPropertyIdFromUrl() {
        const urlParts = window.location.pathname.split('/');
        return urlParts[urlParts.length - 1];
    }

    /**
     * Vincula eventos del DOM
     */
    bindEvents() {
        // Botones de acción
        document.querySelectorAll('[onclick*="showContactModal"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showContactModal();
            });
        });

        document.querySelectorAll('[onclick*="showAppointmentModal"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showAppointmentModal();
            });
        });

        document.querySelectorAll('[onclick*="toggleFavorite"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleFavorite();
            });
        });

        // Compartir en redes sociales
        document.querySelectorAll('[onclick*="shareOn"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const action = btn.getAttribute('onclick');
                if (action.includes('Facebook')) this.shareOnFacebook();
                else if (action.includes('Twitter')) this.shareOnTwitter();
                else if (action.includes('WhatsApp')) this.shareOnWhatsApp();
                else if (action.includes('copyLink')) this.copyLink();
            });
        });

        // Formularios
        this.bindFormEvents();
        
        // Calculadora de hipoteca
        this.bindCalculatorEvents();
    }

    /**
     * Vincula eventos de formularios
     */
    bindFormEvents() {
        // Formulario de contacto
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendContactForm();
            });
        }

        // Formulario de cita
        const appointmentForm = document.getElementById('appointmentForm');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendAppointmentForm();
            });
        }
    }

    /**
     * Vincula eventos de la calculadora
     */
    bindCalculatorEvents() {
        const downPaymentSlider = document.getElementById('downPayment');
        const downPaymentValue = document.getElementById('downPaymentValue');
        
        if (downPaymentSlider && downPaymentValue) {
            downPaymentSlider.addEventListener('input', (e) => {
                downPaymentValue.textContent = e.target.value + '%';
            });
        }
    }

    /**
     * Inicializa el carrusel de imágenes
     */
    initCarousel() {
        const carousel = document.getElementById('propertyCarousel');
        if (carousel) {
            // Configurar opciones del carrusel
            const bsCarousel = new bootstrap.Carousel(carousel, {
                interval: 5000,
                wrap: true
            });
        }
    }

    /**
     * Inicializa Lightbox para galería
     */
    initLightbox() {
        // Lightbox ya está configurado con data-lightbox en las imágenes
        // Aquí se pueden agregar configuraciones adicionales si es necesario
    }

    /**
     * Va a una slide específica del carrusel
     */
    goToSlide(index) {
        const carousel = document.getElementById('propertyCarousel');
        if (carousel) {
            const bsCarousel = bootstrap.Carousel.getInstance(carousel);
            if (bsCarousel) {
                bsCarousel.to(index);
            }
        }
    }

    /**
     * Carga estadísticas de la propiedad
     */
    async loadPropertyStats() {
        try {
            const response = await fetch(`/properties/stats/${this.propertyId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.updateStatsDisplay(data.data);
                }
            }
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    /**
     * Actualiza la visualización de estadísticas
     */
    updateStatsDisplay(stats) {
        // Actualizar contadores en la página
        const statsElements = {
            'views': document.querySelector('.h4.text-primary'),
            'favorites': document.querySelector('.h4.text-success'),
            'inquiries': document.querySelector('.h4.text-info'),
            'days_on_market': document.querySelector('.h4.text-warning')
        };

        if (statsElements.views) statsElements.views.textContent = stats.views;
        if (statsElements.favorites) statsElements.favorites.textContent = stats.favorites;
        if (statsElements.inquiries) statsElements.inquiries.textContent = stats.inquiries;
        if (statsElements.days_on_market) statsElements.days_on_market.textContent = stats.days_on_market;
    }

    /**
     * Inicializa la calculadora de hipoteca
     */
    initMortgageCalculator() {
        // La calculadora se inicializa en bindCalculatorEvents
        // Aquí se pueden agregar configuraciones adicionales
    }

    /**
     * Calcula la hipoteca
     */
    calculateMortgage() {
        const propertyValue = parseFloat(document.getElementById('propertyValue').value);
        const downPaymentPercent = parseFloat(document.getElementById('downPayment').value);
        const loanTerm = parseFloat(document.getElementById('loanTerm').value);
        const interestRate = parseFloat(document.getElementById('interestRate').value);

        if (!propertyValue || !downPaymentPercent || !loanTerm || !interestRate) {
            this.showToast('Por favor completa todos los campos', 'warning');
            return;
        }

        const downPayment = propertyValue * (downPaymentPercent / 100);
        const loanAmount = propertyValue - downPayment;
        const monthlyRate = interestRate / 100 / 12;
        const numberOfPayments = loanTerm * 12;

        let monthlyPayment = 0;
        if (monthlyRate > 0) {
            monthlyPayment = loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments)) / 
                           (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
        } else {
            monthlyPayment = loanAmount / numberOfPayments;
        }

        const totalPayment = monthlyPayment * numberOfPayments;

        // Mostrar resultados
        document.getElementById('monthlyPayment').textContent = '$' + this.formatNumber(Math.round(monthlyPayment));
        document.getElementById('totalPayment').textContent = '$' + this.formatNumber(Math.round(totalPayment));
        
        const resultDiv = document.getElementById('mortgageResult');
        if (resultDiv) {
            resultDiv.style.display = 'block';
        }

        this.showToast('Cálculo completado', 'success');
    }

    /**
     * Verifica el estado de favoritos
     */
    checkFavoriteStatus() {
        // Verificar si la propiedad está en favoritos (localStorage)
        const favorites = JSON.parse(localStorage.getItem('propertyFavorites') || '[]');
        this.isFavorite = favorites.includes(this.propertyId);
        this.updateFavoriteButton();
    }

    /**
     * Actualiza el botón de favoritos
     */
    updateFavoriteButton() {
        const icon = document.getElementById('favoriteIcon');
        const text = document.getElementById('favoriteText');
        
        if (icon && text) {
            if (this.isFavorite) {
                icon.className = 'fas fa-heart text-danger me-2';
                text.textContent = 'Quitar de Favoritos';
            } else {
                icon.className = 'fas fa-heart me-2';
                text.textContent = 'Agregar a Favoritos';
            }
        }
    }

    /**
     * Alterna el estado de favoritos
     */
    async toggleFavorite() {
        const action = this.isFavorite ? 'remove' : 'add';
        
        try {
            const response = await fetch('/propeasy/public/property/favorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    property_id: this.propertyId,
                    action: action
                })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.isFavorite = !this.isFavorite;
                    this.updateFavoriteButton();
                    
                    // Actualizar localStorage
                    const favorites = JSON.parse(localStorage.getItem('propertyFavorites') || '[]');
                    if (this.isFavorite) {
                        if (!favorites.includes(this.propertyId)) {
                            favorites.push(this.propertyId);
                        }
                    } else {
                        const index = favorites.indexOf(this.propertyId);
                        if (index > -1) {
                            favorites.splice(index, 1);
                        }
                    }
                    localStorage.setItem('propertyFavorites', JSON.stringify(favorites));
                    
                    this.showToast(data.message, 'success');
                }
            }
        } catch (error) {
            console.error('Error al actualizar favoritos:', error);
            this.showToast('Error al actualizar favoritos', 'error');
        }
    }

    /**
     * Muestra el modal de contacto
     */
    showContactModal() {
        const modal = new bootstrap.Modal(document.getElementById('contactModal'));
        modal.show();
    }

    /**
     * Muestra el modal de cita
     */
    showAppointmentModal() {
        const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
        modal.show();
    }

    /**
     * Envía el formulario de contacto
     */
    async sendContactForm() {
        const formData = new FormData();
        formData.append('name', document.getElementById('contactName').value);
        formData.append('email', document.getElementById('contactEmail').value);
        formData.append('phone', document.getElementById('contactPhone').value);
        formData.append('message', document.getElementById('contactMessage').value);
        formData.append('property_id', this.propertyId);
        formData.append('newsletter', document.getElementById('contactNewsletter').checked);

        try {
            const response = await fetch('/properties/contact', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.showToast(data.message, 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('contactModal'));
                    modal.hide();
                    document.getElementById('contactForm').reset();
                } else {
                    this.showToast(data.message, 'error');
                }
            }
        } catch (error) {
            console.error('Error al enviar formulario:', error);
            this.showToast('Error al enviar el mensaje', 'error');
        }
    }

    /**
     * Envía el formulario de cita
     */
    async sendAppointmentForm() {
        const formData = new FormData();
        formData.append('name', document.getElementById('appointmentName').value);
        formData.append('email', document.getElementById('appointmentEmail').value);
        formData.append('phone', document.getElementById('appointmentPhone').value);
        formData.append('date', document.getElementById('appointmentDate').value);
        formData.append('time', document.getElementById('appointmentTime').value);
        formData.append('notes', document.getElementById('appointmentNotes').value);
        formData.append('property_id', this.propertyId);

        try {
            const response = await fetch('/properties/appointment', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.showToast(data.message, 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
                    modal.hide();
                    document.getElementById('appointmentForm').reset();
                } else {
                    this.showToast(data.message, 'error');
                }
            }
        } catch (error) {
            console.error('Error al agendar cita:', error);
            this.showToast('Error al agendar la visita', 'error');
        }
    }

    /**
     * Comparte en Facebook
     */
    shareOnFacebook() {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent(document.title);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    }

    /**
     * Comparte en Twitter
     */
    shareOnTwitter() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('¡Mira esta propiedad en PropEasy!');
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
    }

    /**
     * Comparte en WhatsApp
     */
    shareOnWhatsApp() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('¡Mira esta propiedad en PropEasy!');
        window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
    }

    /**
     * Copia el enlace al portapapeles
     */
    async copyLink() {
        try {
            await navigator.clipboard.writeText(window.location.href);
            this.showToast('Enlace copiado al portapapeles', 'success');
        } catch (error) {
            console.error('Error al copiar enlace:', error);
            this.showToast('Error al copiar el enlace', 'error');
        }
    }

    /**
     * Muestra toast de notificación
     */
    showToast(message, type = 'info') {
        // Crear toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        // Agregar al contenedor de toasts
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.appendChild(toast);

        // Mostrar toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remover después de que se oculte
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    /**
     * Formatea números
     */
    formatNumber(num) {
        return new Intl.NumberFormat('es-CL').format(num);
    }
}

// Inicializar detalle de propiedad cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new PropertyDetail();
});

// Funciones globales para compatibilidad con onclick
function showContactModal() {
    if (window.propertyDetail) {
        window.propertyDetail.showContactModal();
    }
}

function showAppointmentModal() {
    if (window.propertyDetail) {
        window.propertyDetail.showAppointmentModal();
    }
}

function toggleFavorite() {
    if (window.propertyDetail) {
        window.propertyDetail.toggleFavorite();
    }
}

function goToSlide(index) {
    if (window.propertyDetail) {
        window.propertyDetail.goToSlide(index);
    }
}

function calculateMortgage() {
    if (window.propertyDetail) {
        window.propertyDetail.calculateMortgage();
    }
}

function shareOnFacebook() {
    if (window.propertyDetail) {
        window.propertyDetail.shareOnFacebook();
    }
}

function shareOnTwitter() {
    if (window.propertyDetail) {
        window.propertyDetail.shareOnTwitter();
    }
}

function shareOnWhatsApp() {
    if (window.propertyDetail) {
        window.propertyDetail.shareOnWhatsApp();
    }
}

function copyLink() {
    if (window.propertyDetail) {
        window.propertyDetail.copyLink();
    }
}

// Nuevas funciones para la funcionalidad mejorada
function showWhatsAppContact() {
    const propertyTitle = document.querySelector('h1')?.textContent || 'Propiedad';
    const message = `Hola, me interesa la propiedad: ${propertyTitle}. ¿Podrías darme más información?`;
    const whatsappUrl = `https://wa.me/56912345678?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function showPhoneContact() {
    window.location.href = 'tel:+56912345678';
}

function openDirections() {
    const address = document.querySelector('[data-property-address]')?.dataset.propertyAddress;
    const lat = document.querySelector('[data-property-lat]')?.dataset.propertyLat;
    const lng = document.querySelector('[data-property-lng]')?.dataset.propertyLng;
    
    let mapsUrl = 'https://maps.google.com/';
    
    if (lat && lng && lat !== '0' && lng !== '0') {
        mapsUrl += `?q=${lat},${lng}`;
    } else if (address) {
        mapsUrl += `?q=${encodeURIComponent(address)}`;
    } else {
        alert('Información de ubicación no disponible');
        return;
    }
    
    window.open(mapsUrl, '_blank');
}

function shareProperty() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href
        });
    } else {
        copyLink();
    }
}

function reportProperty() {
    const propertyId = window.location.pathname.split('/').pop();
    
    // Crear modal dinámico para reportar
    const modalHtml = `
        <div class="modal fade" id="reportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reportar Propiedad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reportForm">
                            <div class="mb-3">
                                <label for="reportEmail" class="form-label">Email (opcional)</label>
                                <input type="email" class="form-control" id="reportEmail" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="reportReason" class="form-label">Razón del reporte *</label>
                                <select class="form-select" id="reportReason" name="reason" required>
                                    <option value="">Seleccionar razón</option>
                                    <option value="informacion_incorrecta">Información incorrecta</option>
                                    <option value="spam">Spam</option>
                                    <option value="contenido_inapropiado">Contenido inapropiado</option>
                                    <option value="precio_sospechoso">Precio sospechoso</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="reportDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="reportDescription" name="description" rows="3" 
                                          placeholder="Describe el problema..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning" onclick="sendReportForm()">Enviar Reporte</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Agregar modal al DOM si no existe
    if (!document.getElementById('reportModal')) {
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    modal.show();
}

function sendReportForm() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    
    // Agregar ID de propiedad
    const propertyId = window.location.pathname.split('/').pop();
    formData.append('property_id', propertyId);
    
    // Validar razón requerida
    const reason = formData.get('reason');
    if (!reason) {
        alert('Debes seleccionar una razón');
        return;
    }
    
    // Enviar datos
    fetch('/properties/report', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('reportModal')).hide();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Error al enviar el reporte. Intenta nuevamente.');
        console.error('Error:', error);
    });
}

function sendContactForm() {
    if (window.propertyDetail) {
        window.propertyDetail.sendContactForm();
    }
}

function sendAppointmentForm() {
    if (window.propertyDetail) {
        window.propertyDetail.sendAppointmentForm();
    }
}

// Exportar para uso global
window.PropertyDetail = PropertyDetail; 