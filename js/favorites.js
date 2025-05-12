document.addEventListener('DOMContentLoaded', function() {
    // Gérer la suppression des favoris via le bouton "favorite-button"
    document.querySelectorAll('.favorite-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const propertyId = this.dataset.propertyId;
            const card = this.closest('.property-card');

            fetch('/TousMesProjets/ProjectAirBnb/includes/favorite_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `property_id=${propertyId}&action=remove`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animation de suppression
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            card.remove();

                            // Vérifier s'il reste des favoris
                            const remainingCards = document.querySelectorAll('.property-card');
                            if (remainingCards.length === 0) {
                                location.reload(); // Recharger pour afficher le message "aucun favori"
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        });

    });



    // Naviguer vers la page détails quand on clique sur la carte
    document.querySelectorAll('.property-card').forEach(card => {
        card.addEventListener('click', function() {
            const propertyId = this.dataset.propertyId;
            window.location.href = `property-details.php?id=${propertyId}`;
        });
    });



    // Handle removal of favorites using remove-favorite-btn (keep existing functionality)
    const removeButtons = document.querySelectorAll('.remove-favorite-btn');

    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const propertyId = this.dataset.propertyId;
            const propertyCard = this.closest('.property-card');

            // Send AJAX request to remove from favorites
            fetch('/TousMesProjets/ProjectAirBnb/includes/toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `property_id=${propertyId}`


            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the property card with animation
                        if (propertyCard) {
                            propertyCard.style.opacity = '0';
                            setTimeout(() => {
                                propertyCard.style.height = '0';
                                propertyCard.style.margin = '0';
                                propertyCard.style.padding = '0';
                                propertyCard.style.overflow = 'hidden';

                                setTimeout(() => {
                                    propertyCard.remove();

                                    // Check if there are no more favorites
                                    const remainingCards = document.querySelectorAll('.property-card');
                                    if (remainingCards.length === 0) {
                                        const container = document.querySelector('.favorites-container');
                                        if (container) {
                                            // Show a message when no favorites are left
                                            const emptyMessage = document.createElement('div');
                                            emptyMessage.className = 'alert alert-info';
                                            emptyMessage.textContent = 'Vous n\'avez pas encore de favoris. Trouvez des logements qui vous plaisent et ajoutez-les à vos favoris!';
                                            container.appendChild(emptyMessage);
                                        }

                                    }

                                }, 300);

                            }, 300);

                        }

                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                });

        });

    });

});


