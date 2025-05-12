document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            let isValid = true;

            if (emailInput && !emailInput.value.trim()) {
                isValid = false;
            }

            if (passwordInput && !passwordInput.value.trim()) {
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
