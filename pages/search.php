<?php
?>
<div class="container search-page my-4">
    <h1 class="mb-4">Trouver un logement</h1>

    <div class="row">
        <!-- Colonne des filtres -->
        <div class="col-lg-3">
            <div class="d-lg-none mb-3">
                <button id="filter-toggle" class="btn btn-primary w-100">Filtres <i class="fas fa-filter"></i></button>
            </div>

            <div id="filters-container" class="d-none d-lg-block">
                <div class="search-filters">
                    <h5 class="mb-3">Filtres</h5>

                    <form id="search-filters" method="GET" action="search.php">
                        <div class="mb-3">
                            <label for="location" class="form-label">Destination</label>
                            <select class="form-select" id="location" name="location">
                                <option value="">Toutes les villes</option>
                                <?php while($city = mysqli_fetch_assoc($cities_result)): ?>
                                    <option value="<?= htmlspecialchars($city['city']) ?>"
                                        <?= ($location == $city['city']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($city['city']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="check_in" class="form-label">Date d'arrivée</label>
                            <input type="date" class="form-control" id="check_in" name="check_in"
                                   value="<?= htmlspecialchars($check_in) ?>" min="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="check_out" class="form-label">Date de départ</label>
                            <input type="date" class="form-control" id="check_out" name="check_out"
                                   value="<?= htmlspecialchars($check_out) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="guests" class="form-label">Nombre de voyageurs</label>
                            <select class="form-select" id="guests" name="guests">
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($guests == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="property_type" class="form-label">Type de logement</label>
                            <select class="form-select" id="property_type" name="property_type">
                                <option value="">Tous les types</option>
                                <option value="apartment" <?= ($property_type == 'apartment') ? 'selected' : '' ?>>Appartement</option>
                                <option value="studio" <?= ($property_type == 'studio') ? 'selected' : '' ?>>Studio</option>
                                <option value="house" <?= ($property_type == 'house') ? 'selected' : '' ?>>Maison</option>
                                <option value="room" <?= ($property_type == 'room') ? 'selected' : '' ?>>Chambre</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price-range" class="form-label">Prix max par nuit: <span id="price-value"><?= $max_price ?>€</span></label>
                            <input type="range" class="form-range" id="price-range" name="max_price"
                                   min="0" max="<?= $db_max_price ?>" value="<?= $max_price ?>">
                        </div>

                        <div class="mb-3">
                            <label for="min_rooms" class="form-label">Nombre minimum de pièces</label>
                            <select class="form-select" id="min_rooms" name="min_rooms">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($min_rooms == $i) ? 'selected' : '' ?>><?= $i ?>+</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <input type="hidden" name="min_price" value="0">

                        <button type="submit" class="btn btn-primary w-100">Appliquer les filtres</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne des resulats -->
        <div class="col-lg-9">
            <!-- Results Count -->
            <div class="mb-4">
                <h5><?= $count ?> logement(s) trouvé(s)</h5>
            </div>

            <?php if ($count == 0): ?>
                <div class="alert alert-info">
                    Aucun logement ne correspond à vos critères. Essayez d'élargir votre recherche.
                </div>
            <?php else: ?>
                <!-- resultat  -->
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php while ($property = mysqli_fetch_assoc($result)): ?>
                        <div class="col">
                            <a href="property-details.php?id=<?= $property['id'] ?>" class="property-link">
                                <div class="card property-card h-100">
                                    <div class="position-relative">
                                        <div class="property-image-container">
                                            <?php $image_path = getPropertyMainImage($conn, $property['id'], $property['main_image']); ?>
                                            <img src="<?= htmlspecialchars("../" . $image_path) ?>" class="property-image" alt="<?= htmlspecialchars($property['title']) ?>">
                                        </div>

                                        <?php if ($user_id): ?>
                                            <button class="favorite-button <?= $property['is_favorite'] ? 'favorited' : '' ?>" data-property-id="<?= $property['id'] ?>">
                                                <i class="<?= $property['is_favorite'] ? 'fas' : 'far' ?> fa-heart"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="property-info">
                                        <h5 class="card-title"><?= htmlspecialchars($property['title']) ?></h5>

                                        <!-- Emplacement information -->
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($property['location']) ?>
                                        </p>

                                        <!-- Info appart -->
                                        <div class="property-details mb-3">
                                            <div>
                                                <i class="fas fa-home me-1"></i>
                                                <?= htmlspecialchars(ucfirst($property['property_type'])) ?> -
                                                <?= htmlspecialchars($property['rooms']) ?> pièce(s)
                                            </div>
                                            <div>
                                                <i class="fas fa-user-friends me-1"></i>
                                                <?= htmlspecialchars($property['max_guests']) ?> voyageur(s) max
                                            </div>
                                        </div>

                                        <!-- Info prix  -->
                                        <div class="reservation-price">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-euro-sign me-1"></i>
                                                    Prix par nuit
                                                </div>
                                                <span class="price-amount"><?= number_format($property['price'], 2, ',', ' ') ?> €</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- Bloc pagination ajouté -->
        <?php
        $results_per_page = 8;
        $total_properties = mysqli_num_rows($full_result); // Résultat sans LIMIT
        $total_pages = ceil($total_properties / $results_per_page);
        $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        ?>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Pagination des résultats" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php
                    $query_params = $_GET;
                    $query_params['page'] = max(1, $current_page - 1);
                    $prev_url = '?' . http_build_query($query_params);
                    ?>
                    <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $prev_url ?>" aria-label="Précédent">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php
                        $query_params['page'] = $i;
                        $page_url = '?' . http_build_query($query_params);
                        ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $page_url ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php
                    $query_params['page'] = min($total_pages, $current_page + 1);
                    $next_url = '?' . http_build_query($query_params);
                    ?>
                    <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $next_url ?>" aria-label="Suivant">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>