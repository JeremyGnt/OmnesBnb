
document.addEventListener('DOMContentLoaded', function() {
    // Menu pour mobile
    const mobileMenuToggle = document.querySelector('.navbar-toggler');
    if(mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            document.body.classList.toggle('menu-open');
        });
    }

    // validation par date
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    if(startDateInput && endDateInput) {

        const today = new Date();
        const todayFormatted = today.toISOString().split('T')[0];
        startDateInput.min = todayFormatted;


        startDateInput.addEventListener('change', function() {
            if(startDateInput.value) {
                endDateInput.min = startDateInput.value;
                // If end date is before start date, reset it
                if(endDateInput.value && endDateInput.value < startDateInput.value) {
                    endDateInput.value = startDateInput.value;
                }
            }
        });
    }

    // prévisualisation image dans la page principale
    const propertyImageInput = document.getElementById('property_image');
    const imagePreview = document.getElementById('image_preview');

    if(propertyImageInput && imagePreview) {
        propertyImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if(file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" alt="Property preview">`;
                }

                reader.readAsDataURL(file);
            }
        });
    }

    // prévisualisation photo de profil
    const profileImageInput = document.getElementById('profile_image');
    const profilePreview = document.getElementById('profile_preview');

    if(profileImageInput && profilePreview) {
        profileImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if(file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    profilePreview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded-circle profile-avatar" alt="Profile preview">`;
                }

                reader.readAsDataURL(file);
            }
        });
    }

    // mdp
    const passwordInput = document.getElementById('password');

    if(passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if(password.length >= 8) strength += 1;
            if(password.match(/[a-z]+/)) strength += 1;
            if(password.match(/[A-Z]+/)) strength += 1;
            if(password.match(/[0-9]+/)) strength += 1;
            if(password.match(/[^a-zA-Z0-9]+/)) strength += 1;

        });
    }

    // calculer le prix en focntion des nuit
    const pricePerNightInput = document.getElementById('price_per_night');
    const numberOfNightsInput = document.getElementById('number_of_nights');
    const totalPriceDisplay = document.getElementById('total_price');

    function calculateTotalPrice() {
        if(pricePerNightInput && numberOfNightsInput && totalPriceDisplay) {
            const pricePerNight = parseFloat(pricePerNightInput.value) || 0;
            const numberOfNights = parseInt(numberOfNightsInput.value) || 0;
            const totalPrice = pricePerNight * numberOfNights;

            totalPriceDisplay.textContent = totalPrice.toFixed(2) + ' €';
        }
    }

    if(pricePerNightInput && numberOfNightsInput) {
        pricePerNightInput.addEventListener('input', calculateTotalPrice);
        numberOfNightsInput.addEventListener('input', calculateTotalPrice);
    }
});
