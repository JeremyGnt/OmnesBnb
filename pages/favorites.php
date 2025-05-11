<?php
?>
<div class="container py-4">
    <h1 class="mb-4">Mes favoris</h1>

    <?php if(empty($favorites)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Vous n'avez pas encore de favoris. Explorez nos <a href="../pages/search.php" class="alert-link">logements disponibles</a> et ajoutez-les à vos favoris!
        </div>
        <div class="mt-4">
            <a href="search.php" class="btn btn-outline-primary">
                <i class="fas fa-search me-2"></i>Découvrir plus de logements
            </a>
        </div>
    <?php endif; ?>
</div>