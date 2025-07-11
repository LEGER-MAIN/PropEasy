/**
 * PropEasy - Módulo de Solicitudes de Compra
 * JavaScript para manejar las funcionalidades del módulo de solicitudes
 */

class SolicitudesCompra {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initTooltips();
        this.initDatePickers();
    }

    bindEvents() {
        // Formulario de solicitud
        const formSolicitud = document.getElementById('formSolicitud');
        if (formSolicitud) {
            formSolicitud.addEventListener('submit', this.handleSubmit.bind(this));
        }

        // Botones de actualizar estado
        document.querySelectorAll('[data-action="actualizar-estado"]').forEach(btn => {
            btn.addEventListener('click', this.handleActualizarEstado.bind(this));
        });

        // Botones de cancelar solicitud
        document.querySelectorAll('[data-action="cancelar-solicitud"]').forEach(btn => {
            btn.addEventListener('click', this.handleCancelar.bind(this));
        });

        // Filtros de estado
        document.querySelectorAll('[data-filter="estado"]').forEach(filter => {
            filter.addEventListener('change', this.handleFiltro.bind(this));
        });
    }

    initTooltips() {
        // Inicializar tooltips de Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    initDatePickers() {
        // Configurar fecha mínima para inputs de fecha
        const fechaInputs = document.querySelectorAll('input[type="date"]');
        fechaInputs.forEach(input => {
            input.min = new Date().toISOString().split('T')[0];
        });
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            // Cambiar estado del botón
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
            submitBtn.disabled = true;
            
            // Validar formulario
            if (!this.validateForm(form)) {
                return;
            }
            
            // Enviar formulario
            const formData = new FormData(form);
            const response = await fetch('/solicitud-compra/crear', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('¡Solicitud enviada!', data.message);
                // Redirigir después de un breve delay
                setTimeout(() => {
                    window.location.href = '/solicitud-compra/mis-solicitudes';
                }, 2000);
            } else {
                this.showError('Error', data.message);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error', 'Ha ocurrido un error inesperado. Por favor, inténtelo de nuevo.');
        } finally {
            // Restaurar botón
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Validar precio ofrecido si se proporciona
        const precioInput = form.querySelector('#precio_ofrecido');
        if (precioInput && precioInput.value) {
            const precio = parseFloat(precioInput.value);
            if (precio <= 0) {
                precioInput.classList.add('is-invalid');
                this.showError('Error', 'El precio ofrecido debe ser mayor a 0');
                isValid = false;
            } else {
                precioInput.classList.remove('is-invalid');
            }
        }
        
        // Validar fecha de visita si se proporciona
        const fechaInput = form.querySelector('#fecha_visita_preferida');
        if (fechaInput && fechaInput.value) {
            const fecha = new Date(fechaInput.value);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            
            if (fecha < hoy) {
                fechaInput.classList.add('is-invalid');
                this.showError('Error', 'La fecha de visita no puede ser anterior a hoy');
                isValid = false;
            } else {
                fechaInput.classList.remove('is-invalid');
            }
        }
        
        return isValid;
    }

    async handleActualizarEstado(e) {
        e.preventDefault();
        
        const btn = e.target.closest('button');
        const solicitudId = btn.dataset.solicitudId;
        const nuevoEstado = btn.dataset.estado;
        
        if (!solicitudId || !nuevoEstado) {
            this.showError('Error', 'Datos incompletos');
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('solicitud_id', solicitudId);
            formData.append('nuevo_estado', nuevoEstado);
            
            const response = await fetch('/solicitud-compra/actualizar-estado', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('¡Actualizado!', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                this.showError('Error', data.message);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error', 'Ha ocurrido un error inesperado');
        }
    }

    async handleCancelar(e) {
        e.preventDefault();
        
        const btn = e.target.closest('button');
        const solicitudId = btn.dataset.solicitudId;
        
        if (!solicitudId) {
            this.showError('Error', 'ID de solicitud no válido');
            return;
        }
        
        // Confirmar cancelación
        const confirmed = await this.showConfirm(
            '¿Estás seguro?',
            'Esta acción no se puede deshacer. ¿Deseas cancelar la solicitud?'
        );
        
        if (!confirmed) return;
        
        try {
            const response = await fetch('/solicitud-compra/cancelar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `solicitud_id=${solicitudId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('¡Cancelada!', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                this.showError('Error', data.message);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error', 'Ha ocurrido un error inesperado');
        }
    }

    handleFiltro(e) {
        const filtro = e.target.value;
        const url = new URL(window.location);
        
        if (filtro) {
            url.searchParams.set('estado', filtro);
        } else {
            url.searchParams.delete('estado');
        }
        
        window.location.href = url.toString();
    }

    // Utilidades para mostrar mensajes
    showSuccess(title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonText: 'Aceptar'
            });
        } else {
            alert(`${title}: ${message}`);
        }
    }

    showError(title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonText: 'Aceptar'
            });
        } else {
            alert(`Error: ${message}`);
        }
    }

    async showConfirm(title, message) {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, mantener'
            });
            return result.isConfirmed;
        } else {
            return confirm(`${title}: ${message}`);
        }
    }

    // Métodos estáticos para uso global
    static actualizarEstado(solicitudId, nuevoEstado) {
        const solicitudes = new SolicitudesCompra();
        return solicitudes.handleActualizarEstado({
            preventDefault: () => {},
            target: {
                closest: () => ({
                    dataset: { solicitudId, estado: nuevoEstado }
                })
            }
        });
    }

    static cancelarSolicitud(solicitudId) {
        const solicitudes = new SolicitudesCompra();
        return solicitudes.handleCancelar({
            preventDefault: () => {},
            target: {
                closest: () => ({
                    dataset: { solicitudId }
                })
            }
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new SolicitudesCompra();
});

// Exportar para uso global
window.SolicitudesCompra = SolicitudesCompra; 