/**
 * JavaScript for favorites page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gérer la suppression des favoris via le bouton "favorite-button"
    document.querySelectorAll('.favorite-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const propertyId = this.dataset.propertyId;
            const card = this.closest('.property-card');

            // Envoyer la requête AJAX pour supprimer des favoris
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
});