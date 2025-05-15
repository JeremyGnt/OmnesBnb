document.addEventListener('DOMContentLoaded', function() {
    // Handle dashboard card clicks to show detail sections
    const dashboardCards = document.querySelectorAll('.dashboard-card');
    const dashboardDetails = document.getElementById('dashboard-details');
    const detailCards = document.querySelectorAll('.dashboard-detail-card');

    const reservationsDetails = document.getElementById('reservations-details');
    if (dashboardDetails && reservationsDetails) {
        dashboardDetails.style.display = 'block';
    }
    if (detailCards) {
        detailCards.forEach(card => {
            if (card.id !== 'reservations-details') { // Exclure reservations-details
                card.style.display = 'none';
            }
        });
    }
    if (dashboardCards && dashboardDetails) {
        dashboardCards.forEach(card => {
            card.addEventListener('click', function() {
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
                    detailCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    const editButtons = document.querySelectorAll('.edit-property');
    if (editButtons.length > 0) {
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const propertyId = this.getAttribute('data-property-id');
                window.location.href = `publish.php?edit=${propertyId}`;
            });
        });
    }

    const togglePropertyModalElement = document.getElementById('togglePropertyModal');
    const notificationModalElement = document.getElementById('notificationModal');
    const deletePropertyModalElement = document.getElementById('deletePropertyModal');

    let togglePropertyModal, notificationModal, deletePropertyModal;

    if (typeof bootstrap !== 'undefined') {
        if (togglePropertyModalElement) {
            togglePropertyModal = new bootstrap.Modal(togglePropertyModalElement);
        }

        if (notificationModalElement) {
            notificationModal = new bootstrap.Modal(notificationModalElement);
        }

        if (deletePropertyModalElement) {
            deletePropertyModal = new bootstrap.Modal(deletePropertyModalElement);
        }
    } else {
        console.error('Bootstrap n\'est pas chargé correctement');
    }


    const toggleButtons = document.querySelectorAll('.toggle-property');
    let currentButton = null;
    let currentPropertyId = null;
    let currentIsActive = null;
    let currentNewStatus = null;

    if (toggleButtons.length > 0) {
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentButton = this;
                currentPropertyId = this.getAttribute('data-property-id');
                currentIsActive = this.querySelector('i').classList.contains('fa-toggle-off');
                currentNewStatus = currentIsActive ? 0 : 1; // 0 = inactive, 1 = active
                const statusText = currentIsActive ? 'désactiver' : 'activer';

                if (togglePropertyModal) {
                    // Mettre à jour le texte de la modale de confirmation
                    const modalText = document.getElementById('togglePropertyModalText');
                    if (modalText) {
                        modalText.textContent = `Êtes-vous sûr de vouloir ${statusText} cette propriété ?`;
                    }
                    // Afficher la modale de confirmation
                    togglePropertyModal.show();
                } else {
                    // Fallback si la modale n'est pas disponible
                    if (confirm(`Êtes-vous sûr de vouloir ${statusText} cette propriété ?`)) {
                        togglePropertyAction();
                    }
                }
            });
        });
    }

    // Gérer la confirmation de l'action depuis la modale
    const confirmToggleProperty = document.getElementById('confirmToggleProperty');
    if (confirmToggleProperty) {
        confirmToggleProperty.addEventListener('click', function() {
            togglePropertyAction();
        });
    }