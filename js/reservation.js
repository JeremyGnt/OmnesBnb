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
            const guestCount = document.getElementById('guest-count');
            const maxGuests = guestCount ? parseInt(guestCount.dataset.maxGuests) : 0;

            if (guestCount && (isNaN(guestCount.value) || parseInt(guestCount.value) <= 0)) {
                isValid = false;
                guestCount.classList.add('is-invalid');
                alert('Veuillez sélectionner un nombre de voyageurs valide.');
            }
            else if (guestCount && parseInt(guestCount.value) > maxGuests) {
                isValid = false;
                guestCount.classList.add('is-invalid');
                alert(`Le nombre maximum de voyageurs pour cette propriété est de ${maxGuests}.`);
            }
            else if (guestCount) {
                guestCount.classList.remove('is-invalid');
            }

            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                isValid = false;
                alert('Veuillez sélectionner un mode de paiement.');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    const updateTotalPrice = function() {
        const checkinDate = document.getElementById('checkin-date');
        const checkoutDate = document.getElementById('checkout-date');
        const nightlyRate = document.getElementById('nightly-rate');
        const totalPriceElement = document.getElementById('total-price');

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
    const checkinDate = document.getElementById('checkin-date');
    const checkoutDate = document.getElementById('checkout-date');
    if (checkinDate) {
        checkinDate.addEventListener('change', updateTotalPrice);
    }

    if (checkoutDate) {
        checkoutDate.addEventListener('change', updateTotalPrice);
    }
    updateTotalPrice();
});

