/**
 * Script de autenticación para PropEasy
 * Maneja validaciones de formularios, toggle de contraseñas y funcionalidades de autenticación
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle de visibilidad de contraseñas
    const togglePasswordButtons = document.querySelectorAll('[id^="toggle"]');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Validación del formulario de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }

    // Validación del formulario de registro
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateRegisterForm()) {
                e.preventDefault();
            }
        });
    }

    // Validación del formulario de recuperación de contraseña
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(e) {
            if (!validateForgotPasswordForm()) {
                e.preventDefault();
            }
        });
    }

    // Validación del formulario de reseteo de contraseña
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', function(e) {
            if (!validateResetPasswordForm()) {
                e.preventDefault();
            }
        });
    }

    // Validación en tiempo real de contraseñas
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            validatePasswordStrength(this);
        });
    });

    // Validación de confirmación de contraseña
    const confirmPasswordInputs = document.querySelectorAll('#confirm_password');
    confirmPasswordInputs.forEach(input => {
        input.addEventListener('input', function() {
            validatePasswordConfirmation(this);
        });
    });
});

/**
 * Valida el formulario de login
 * @returns {boolean} True si el formulario es válido
 */
function validateLoginForm() {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    let isValid = true;

    // Limpiar estados previos
    clearValidationStates([email, password]);

    // Validar email
    if (!email.value.trim()) {
        showError(email, 'El email es obligatorio');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, 'Formato de email inválido');
        isValid = false;
    }

    // Validar contraseña
    if (!password.value) {
        showError(password, 'La contraseña es obligatoria');
        isValid = false;
    }

    return isValid;
}

/**
 * Valida el formulario de registro
 * @returns {boolean} True si el formulario es válido
 */
function validateRegisterForm() {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const role = document.getElementById('role');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const terms = document.getElementById('terms');
    let isValid = true;

    // Limpiar estados previos
    clearValidationStates([name, email, role, password, confirmPassword]);

    // Validar nombre
    if (!name.value.trim()) {
        showError(name, 'El nombre es obligatorio');
        isValid = false;
    } else if (name.value.trim().length < 2) {
        showError(name, 'El nombre debe tener al menos 2 caracteres');
        isValid = false;
    }

    // Validar email
    if (!email.value.trim()) {
        showError(email, 'El email es obligatorio');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, 'Formato de email inválido');
        isValid = false;
    }

    // Validar rol
    if (!role.value) {
        showError(role, 'Debes seleccionar un tipo de usuario');
        isValid = false;
    }

    // Validar contraseña
    if (!password.value) {
        showError(password, 'La contraseña es obligatoria');
        isValid = false;
    } else if (password.value.length < 6) {
        showError(password, 'La contraseña debe tener al menos 6 caracteres');
        isValid = false;
    }

    // Validar confirmación de contraseña
    if (!confirmPassword.value) {
        showError(confirmPassword, 'Debes confirmar la contraseña');
        isValid = false;
    } else if (password.value !== confirmPassword.value) {
        showError(confirmPassword, 'Las contraseñas no coinciden');
        isValid = false;
    }

    // Validar términos y condiciones
    if (!terms.checked) {
        showError(terms, 'Debes aceptar los términos y condiciones');
        isValid = false;
    }

    return isValid;
}

/**
 * Valida el formulario de recuperación de contraseña
 * @returns {boolean} True si el formulario es válido
 */
function validateForgotPasswordForm() {
    const email = document.getElementById('email');
    let isValid = true;

    clearValidationStates([email]);

    if (!email.value.trim()) {
        showError(email, 'El email es obligatorio');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, 'Formato de email inválido');
        isValid = false;
    }

    return isValid;
}

/**
 * Valida el formulario de reseteo de contraseña
 * @returns {boolean} True si el formulario es válido
 */
function validateResetPasswordForm() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    let isValid = true;

    clearValidationStates([password, confirmPassword]);

    if (!password.value) {
        showError(password, 'La contraseña es obligatoria');
        isValid = false;
    } else if (password.value.length < 6) {
        showError(password, 'La contraseña debe tener al menos 6 caracteres');
        isValid = false;
    }

    if (!confirmPassword.value) {
        showError(confirmPassword, 'Debes confirmar la contraseña');
        isValid = false;
    } else if (password.value !== confirmPassword.value) {
        showError(confirmPassword, 'Las contraseñas no coinciden');
        isValid = false;
    }

    return isValid;
}

/**
 * Valida la fortaleza de la contraseña
 * @param {HTMLElement} input Elemento input de contraseña
 */
function validatePasswordStrength(input) {
    const password = input.value;
    const strengthIndicator = input.parentElement.querySelector('.password-strength');
    
    if (!strengthIndicator) {
        const indicator = document.createElement('div');
        indicator.className = 'password-strength mt-1';
        input.parentElement.appendChild(indicator);
    }

    let strength = 0;
    let message = '';

    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    switch (strength) {
        case 0:
        case 1:
            message = '<span class="text-danger">Muy débil</span>';
            break;
        case 2:
            message = '<span class="text-warning">Débil</span>';
            break;
        case 3:
            message = '<span class="text-info">Media</span>';
            break;
        case 4:
            message = '<span class="text-primary">Fuerte</span>';
            break;
        case 5:
            message = '<span class="text-success">Muy fuerte</span>';
            break;
    }

    if (password.length > 0) {
        strengthIndicator.innerHTML = `Fortaleza: ${message}`;
    } else {
        strengthIndicator.innerHTML = '';
    }
}

/**
 * Valida la confirmación de contraseña
 * @param {HTMLElement} input Elemento input de confirmación
 */
function validatePasswordConfirmation(input) {
    const password = document.getElementById('password');
    const confirmPassword = input;
    
    if (password.value !== confirmPassword.value) {
        showError(confirmPassword, 'Las contraseñas no coinciden');
    } else {
        showSuccess(confirmPassword, 'Las contraseñas coinciden');
    }
}

/**
 * Valida el formato de email
 * @param {string} email Email a validar
 * @returns {boolean} True si el email es válido
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Muestra un error en un campo
 * @param {HTMLElement} input Elemento input
 * @param {string} message Mensaje de error
 */
function showError(input, message) {
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    
    let feedback = input.parentElement.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        input.parentElement.appendChild(feedback);
    }
    feedback.textContent = message;
}

/**
 * Muestra un éxito en un campo
 * @param {HTMLElement} input Elemento input
 * @param {string} message Mensaje de éxito
 */
function showSuccess(input, message) {
    input.classList.add('is-valid');
    input.classList.remove('is-invalid');
    
    let feedback = input.parentElement.querySelector('.valid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'valid-feedback';
        input.parentElement.appendChild(feedback);
    }
    feedback.textContent = message;
}

/**
 * Limpia los estados de validación de los campos
 * @param {Array} inputs Array de elementos input
 */
function clearValidationStates(inputs) {
    inputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
        const feedback = input.parentElement.querySelector('.invalid-feedback, .valid-feedback');
        if (feedback) {
            feedback.remove();
        }
    });
}

/**
 * Muestra un mensaje de alerta
 * @param {string} message Mensaje a mostrar
 * @param {string} type Tipo de alerta (success, danger, warning, info)
 */
function showAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
    alertContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertContainer, container.firstChild);
    
    // Auto-dismiss después de 5 segundos
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.remove();
        }
    }, 5000);
}

/**
 * Redirige a una URL
 * @param {string} url URL a la que redirigir
 * @param {number} delay Delay en milisegundos (opcional)
 */
function redirect(url, delay = 0) {
    setTimeout(() => {
        window.location.href = url;
    }, delay);
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

// Función para hacer peticiones AJAX de autenticación
async function authRequest(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error en la autenticación:', error);
        showNotification('Error en la conexión. Inténtalo de nuevo.', 'danger');
        throw error;
    }
} 