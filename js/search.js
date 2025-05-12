document.addEventListener('DOMContentLoaded', function() {
//filtres
    const filterForm = document.getElementById('search-filters');
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');

   // prix
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = priceRange.value + '€';
        });
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
        });
    }


    const favoriteButtons = document.querySelectorAll('.favorite-button');

    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const propertyId = this.dataset.propertyId;
            const isFavorite = this.classList.contains('favorited');

            this.classList.toggle('favorited');

            if (isFavorite) {
                this.innerHTML = '<i class="far fa-heart"></i>';
            } else {
                this.innerHTML = '<i class="fas fa-heart"></i>';
            }

            fetch('omnesbnb-equipe-2h/includes/favorite_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `property_id=${propertyId}&action=${isFavorite ? 'remove' : 'add'}`
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        button.classList.toggle('favorited');
                        button.innerHTML = isFavorite ?
                            '<i class="fas fa-heart"></i>' :
                            '<i class="far fa-heart"></i>';

                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert visual change on error
                    button.classList.toggle('favorited');
                    button.innerHTML = isFavorite ?
                        '<i class="fas fa-heart"></i>' :
                        '<i class="far fa-heart"></i>';
                });
        });
    });
    const filterToggle = document.getElementById('filter-toggle');
    const filtersContainer = document.getElementById('filters-container');

    if (filterToggle && filtersContainer) {
        filterToggle.addEventListener('click', function() {
            filtersContainer.classList.toggle('d-none');
            filtersContainer.classList.toggle('d-block');
        });
    }
});
