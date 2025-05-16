document.addEventListener('DOMContentLoaded', function() {
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
    // Fonction pour traiter l'action de toggle
    function togglePropertyAction() {
        if (!currentButton || !currentPropertyId) return;

        // Cacher la modale de confirmation si elle existe
        if (togglePropertyModal) {
            togglePropertyModal.hide();
        }
        fetch('../includes/property_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `property_id=${currentPropertyId}&action=toggle_status&status=${currentNewStatus}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'interface utilisateur
                    if (currentIsActive) {
                        // Changer en inactif
                        currentButton.innerHTML = '<i class="fas fa-toggle-on me-1"></i>Activer';

                        // Mettre à jour les badges de statut
                        const statusBadges = document.querySelectorAll(`.rental-status`);
                        statusBadges.forEach(badge => {
                            if (badge.closest('.rental-card') &&
                                badge.closest('.rental-card').querySelector(`.toggle-property[data-property-id="${currentPropertyId}"]`)) {
                                badge.textContent = 'Inactif';
                                badge.classList.remove('status-active');
                                badge.classList.add('status-expired');
                            }
                        });
                    } else {
                        // Changer en actif
                        currentButton.innerHTML = '<i class="fas fa-toggle-off me-1"></i>Désactiver';

                        // Mettre à jour les badges de statut
                        const statusBadges = document.querySelectorAll(`.rental-status`);
                        statusBadges.forEach(badge => {
                            if (badge.closest('.rental-card') &&
                                badge.closest('.rental-card').querySelector(`.toggle-property[data-property-id="${currentPropertyId}"]`)) {
                                badge.textContent = 'Actif';
                                badge.classList.remove('status-expired');
                                badge.classList.add('status-active');
                            }
                        });
                    }

                    if (notificationModal) {
                        // Afficher le message de succès dans la modale de notification
                        const iconElement = document.getElementById('notificationIcon');
                        const messageElement = document.getElementById('notificationModalText');
                        const titleElement = document.getElementById('notificationModalLabel');

                        if (iconElement && messageElement && titleElement) {
                            // Définir le titre, l'icône et le message appropriés
                            titleElement.textContent = 'Succès';
                            iconElement.innerHTML = `<i class="fas fa-check-circle fa-4x text-success"></i>`;
                            messageElement.textContent = `Propriété ${currentIsActive ? 'désactivée' : 'activée'} avec succès.`;

                            // Afficher la modale de notification
                            notificationModal.show();
                        } else {
                            // Fallback si les éléments ne sont pas trouvés
                            alert(`Propriété ${currentIsActive ? 'désactivée' : 'activée'} avec succès.`);
                        }
                    } else {
                        // Fallback si la modale n'est pas disponible
                        alert(`Propriété ${currentIsActive ? 'désactivée' : 'activée'} avec succès.`);
                    }
                } else {
                    if (notificationModal) {
                        // Afficher le message d'erreur dans la modale de notification
                        const iconElement = document.getElementById('notificationIcon');
                        const messageElement = document.getElementById('notificationModalText');
                        const titleElement = document.getElementById('notificationModalLabel');

                        if (iconElement && messageElement && titleElement) {
                            // Définir le titre, l'icône et le message appropriés
                            titleElement.textContent = 'Erreur';
                            iconElement.innerHTML = `<i class="fas fa-exclamation-circle fa-4x text-danger"></i>`;
                            messageElement.textContent = data.message || 'Une erreur est survenue lors de la mise à jour du statut.';

                            // Afficher la modale de notification
                            notificationModal.show();
                        } else {
                            // Fallback si les éléments ne sont pas trouvés
                            alert(data.message || 'Une erreur est survenue lors de la mise à jour du statut.');
                        }
                    } else {
                        // Fallback si la modale n'est pas disponible
                        alert(data.message || 'Une erreur est survenue lors de la mise à jour du statut.');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);

                if (notificationModal) {
                    // En cas d'erreur de réseau ou autre
                    const iconElement = document.getElementById('notificationIcon');
                    const messageElement = document.getElementById('notificationModalText');
                    const titleElement = document.getElementById('notificationModalLabel');

                    if (iconElement && messageElement && titleElement) {
                        titleElement.textContent = 'Erreur';
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle fa-4x text-danger"></i>`;
                        messageElement.textContent = 'Une erreur de réseau est survenue. Veuillez réessayer plus tard.';

                        notificationModal.show();
                    } else {
                        alert('Une erreur de réseau est survenue. Veuillez réessayer plus tard.');
                    }
                } else {
                    alert('Une erreur de réseau est survenue. Veuillez réessayer plus tard.');
                }
            });
    }

    const deleteButtons = document.querySelectorAll('.delete-property');
    let propertyToDelete = null;
    let propertyTitleToDelete = '';

    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                propertyToDelete = this.getAttribute('data-property-id');
                propertyTitleToDelete = this.getAttribute('data-property-title') || 'cette propriété';

                if (deletePropertyModal) {
                    const propertyTitleElement = document.getElementById('propertyTitleToDelete');
                    if (propertyTitleElement) {
                        propertyTitleElement.textContent = propertyTitleToDelete;
                    }
                    deletePropertyModal.show();
                } else {
                    if (confirm(`Êtes-vous sûr de vouloir supprimer "${propertyTitleToDelete}" ? Cette action est irréversible.`)) {
                        deletePropertyAction();
                    }
                }
            });
        });
    }
    // Gérer la confirmation de suppression depuis la modale
    const confirmDeleteProperty = document.getElementById('confirmDeleteProperty');
    if (confirmDeleteProperty) {
        confirmDeleteProperty.addEventListener('click', function() {
            deletePropertyAction();
        });
    }
});