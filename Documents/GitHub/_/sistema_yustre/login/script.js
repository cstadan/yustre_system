// ===== LOGIN FORM VALIDATION =====
document.addEventListener('DOMContentLoaded', function() {
    
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const forgotPasswordLink = document.getElementById('forgotPassword');
    
    // Validación del formulario al enviar
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Limpiar mensajes de error previos
            clearErrors();
            
            let isValid = true;
            
            // Validar email
            if (!validateEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            }
            
            // Validar password
            if (passwordInput.value.length < 6) {
                showError(passwordInput, 'Password must be at least 6 characters');
                isValid = false;
            }
            
            // Si todo es válido, enviar el formulario
            if (isValid) {
                // Enviar el formulario a process_login.php
                loginForm.submit();
            }
        });
    }
    
    // Limpiar error cuando el usuario empiece a escribir
    emailInput.addEventListener('input', function() {
        clearError(this);
    });
    
    passwordInput.addEventListener('input', function() {
        clearError(this);
    });
    
    // Manejar el click en "Forgot password"
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Password recovery functionality would be implemented here.');
            // Aquí rediriges a la página de recuperación de contraseña
            // window.location.href = 'forgot-password.php';
        });
    }
    
});

// ===== FUNCIONES AUXILIARES =====

// Validar formato de email
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Mostrar error en un input
function showError(input, message) {
    input.classList.add('is-invalid');
    
    // Crear elemento de error si no existe
    let errorDiv = input.nextElementSibling;
    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
        errorDiv = document.createElement('div');
        errorDiv.classList.add('invalid-feedback');
        input.parentNode.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

// Limpiar error de un input específico
function clearError(input) {
    input.classList.remove('is-invalid');
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
        errorDiv.style.display = 'none';
    }
}

// Limpiar todos los errores
function clearErrors() {
    const inputs = document.querySelectorAll('.is-invalid');
    inputs.forEach(input => {
        clearError(input);
    });
}

// Mostrar mensaje de éxito (para pruebas)
function showSuccessMessage() {
    const loginBox = document.querySelector('.login-box');
    
    // Crear mensaje de éxito
    const successDiv = document.createElement('div');
    successDiv.classList.add('alert', 'alert-success', 'alert-dismissible', 'fade', 'show', 'mt-3');
    successDiv.setAttribute('role', 'alert');
    successDiv.innerHTML = `
        <strong>Success!</strong> Login credentials are valid.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    loginBox.appendChild(successDiv);
    
    // Auto-cerrar después de 3 segundos
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// ===== ANIMACIONES ADICIONALES =====

// Añadir efecto de entrada a los inputs cuando reciben foco
document.querySelectorAll('.custom-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.01)';
        this.parentElement.style.transition = 'transform 0.2s ease';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});