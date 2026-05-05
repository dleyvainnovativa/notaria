import { registerWithEmail } from './firebase/firebase-auth.js';

// Toggle password visibility (para ambos campos)
document.querySelectorAll('.input-group-text').forEach(toggle => {
    const input = toggle.parentElement.querySelector('input');
    const icon = toggle.querySelector('i');

    toggle.addEventListener('click', () => {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);

        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('register-form');
    if (!form) return;

    const errorBox = document.getElementById('error-message');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span> Creando cuenta...
        `;

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;

        errorBox.style.display = 'none';

        if (password !== confirm) {
            showError('Las contraseñas no coinciden', submitButton);
            return;
        }

        try {
            await registerWithEmail(name, email, password);
            // El redirect ya ocurre dentro de firebase-auth.js
        } catch (error) {
            showError(parseFirebaseError(error), submitButton);
        }
    });
});

function showError(message, button) {
    const errorBox = document.getElementById('error-message');
    errorBox.textContent = message;
    errorBox.style.display = 'block';

    button.disabled = false;
    button.innerHTML = 'Crear nueva cuenta';
}


