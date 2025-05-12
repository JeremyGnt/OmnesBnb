document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;

            const firstname = document.getElementById('firstname');
            const lastname = document.getElementById('lastname');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            const requiredFields = [firstname, lastname, email, password, confirmPassword];
            requiredFields.forEach(field => {
                if (field && !field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                isValid = false;
                confirmPassword.classList.add('is-invalid');
                const passwordFeedback = document.getElementById('password-feedback') ||
                    document.createElement('div');
                passwordFeedback.className = 'invalid-feedback';
                passwordFeedback.id = 'password-feedback';
                passwordFeedback.textContent = 'Les mots de passe ne correspondent pas';

                if (!document.getElementById('password-feedback')) {
                    confirmPassword.parentNode.appendChild(passwordFeedback);
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
