document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.getElementById('reservation-form');

    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            let isValid = true;

            const checkinDate = document.getElementById('checkin-date');
            const checkoutDate = document.getElementById('checkout-date');

            if (checkinDate && checkoutDate) {
                const checkin = new Date(checkinDate.value);
                const checkout = new Date(checkoutDate.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (isNaN(checkin.getTime()) || isNaN(checkout.getTime())) {
                    isValid = false;
                    alert('Veuillez sélectionner des dates valides.');
                }
                else if (checkin < today) {
                    isValid = false;
                    checkinDate.classList.add('is-invalid');
                    alert('La date d\'arrivée ne peut pas être dans le passé.');
                }
                else if (checkout <= checkin) {
                    isValid = false;
                    checkoutDate.classList.add('is-invalid');
                    alert('La date de départ doit être après la date d\'arrivée.');
                }
                else {
                    checkinDate.classList.remove('is-invalid');
                    checkoutDate.classList.remove('is-invalid');
                }
            }
/////
            if (checkinDate && checkoutDate && nightlyRate && totalPriceElement) {
                const checkin = new Date(checkinDate.value);
                const checkout = new Date(checkoutDate.value);
                const pricePerNight = parseFloat(nightlyRate.dataset.price || 0);

                if (!isNaN(checkin.getTime()) && !isNaN(checkout.getTime()) && checkout > checkin) {
                    const timeDiff = checkout.getTime() - checkin.getTime();
                    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    const totalPrice = nights * pricePerNight;
                    totalPriceElement.textContent = totalPrice.toFixed(2) + ' €';
                    const totalPriceInput = document.getElementById('total-price-input');
                    if (totalPriceInput) {
                        totalPriceInput.value = totalPrice.toFixed(2);
                    }
                }
            }
        };

        // Add event listeners for date inputs
        const checkinDate = document.getElementById('checkin-date');
        const checkoutDate = document.getElementById('checkout-date');

        if (checkinDate) {
            checkinDate.addEventListener('change', updateTotalPrice);
        }

        if (checkoutDate) {
            checkoutDate.addEventListener('change', updateTotalPrice);
        }

        // Initialize price calculation
        updateTotalPrice();
    });
