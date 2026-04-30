// ===== LOGIN FORM VALIDATION =====
document.addEventListener('DOMContentLoaded', function() {
    
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    
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
    
    // Toggle show/hide password
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.innerHTML = isPassword
                ? '<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/><path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709z"/><path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>'
                : '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>';
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
    const container = input.parentNode;
    let errorDiv = container.querySelector('.invalid-feedback');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.classList.add('invalid-feedback');
        container.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

// Limpiar error de un input específico
function clearError(input) {
    input.classList.remove('is-invalid');
    const errorDiv = input.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
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