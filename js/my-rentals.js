/**
 * JavaScript for my-rentals page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle dashboard card clicks to show detail sections
    const dashboardCards = document.querySelectorAll('.dashboard-card');
    const dashboardDetails = document.getElementById('dashboard-details');
    const detailCards = document.querySelectorAll('.dashboard-detail-card');

    const reservationsDetails = document.getElementById('reservations-details');
    if (dashboardDetails && reservationsDetails) {
        dashboardDetails.style.display = 'block';
    }

    // Hide all detail cards initially
    if (detailCards) {
        detailCards.forEach(card => {
            if (card.id !== 'reservations-details') { // Exclure reservations-details
                card.style.display = 'none';
            }
        });
    }

    // Add click event to dashboard cards
    if (dashboardCards && dashboardDetails) {
        dashboardCards.forEach(card => {
            card.addEventListener('click', function () {
                const cardType = this.getAttribute('data-card');
                const detailCard = document.getElementById(`${cardType}-details`);

                dashboardDetails.style.display = 'block';

                detailCards.forEach(card => {
                    if (card.id !== 'reservations-details') {
                        card.style.display = 'none';
                    }
                });

                if (detailCard) {
                    detailCard.style.display = 'block';
                    detailCard.scrollIntoView({behavior: 'smooth', block: 'start'});
                }
            });
        });
    }

