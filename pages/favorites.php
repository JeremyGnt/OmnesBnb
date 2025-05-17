<?php

require_once "../includes/db_connection.php";
include "../includes/header.php";

echo '<link rel="stylesheet" href="../omnesbnb-equipe-2h/css/favorites.css">';

if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Vous devez vous connecter pour accéder à vos favoris.";
    $_SESSION["message_type"] = "warning";
    header("location: login.php");
    exit;
}

if(isset($_GET["remove"]) && !empty($_GET["remove"])) {
    $property_id = $_GET["remove"];
    $user_id = $_SESSION["user_id"];

    $sql = "DELETE FROM favorites WHERE user_id = ? AND property_id = ?";
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $property_id);

        if(mysqli_stmt_execute($stmt)) {
            $_SESSION["message"] = "Propriété supprimée des favoris avec succès.";
            $_SESSION["message_type"] = "success";

            $activity_sql = "INSERT INTO user_activity (user_id, activity_type, property_id) VALUES (?, 'favorite_remove', ?)";
            $activity_stmt = mysqli_prepare($conn, $activity_sql);
            mysqli_stmt_bind_param($activity_stmt, "ii", $user_id, $property_id);
            mysqli_stmt_execute($activity_stmt);

            header("location: favorites.php");
            exit;
        } else {
            $_SESSION["message"] = "Une erreur est survenue. Veuillez réessayer.";
            $_SESSION["message_type"] = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}
$user_id = $_SESSION["user_id"];
$favorites = [];

$sql = "SELECT p.*, f.created_at AS favorite_date 
        FROM properties p 
        JOIN favorites f ON p.id = f.property_id 
        WHERE f.user_id = ? 
        ORDER BY f.created_at DESC";

if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if(mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result)) {
            $favorites[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}
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
    <?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach($favorites as $property): ?>
        <div class="col mb-4">
            <div class="property-card" data-property-id="<?php echo $property['id']; ?>">
                <div class="position-relative">
                    <div class="property-image-container">
                        <?php if(!empty($property['main_image'])): ?>
                        <img src="../<?php echo htmlspecialchars($property['main_image']); ?>" class="property-image" alt="<?php echo htmlspecialchars($property['title']); ?>">
                        <?php else: ?>
                        <img src="../assets/property_images/default.jpg" class="property-image" alt="<?php echo htmlspecialchars($property['title']); ?>">
                        <?php endif; ?>
                    </div>

                    <button class="favorite-button favorited" data-property-id="<?php echo $property['id']; ?>">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <div class="property-info">
                    <h5 class="card-title"><?php echo htmlspecialchars($property['title']); ?></h5>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-home"></i>
                        <?php echo htmlspecialchars(ucfirst($property['property_type'])); ?> -
                        <?php echo htmlspecialchars($property['rooms']); ?> pièce(s)
                    </p>
                    <p class="card-text">
                        <i class="fas fa-user-friends"></i> <?php echo htmlspecialchars($property['max_guests']); ?> personne(s) max
                    </p>                            <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="property-price"><?php echo htmlspecialchars($property['price']); ?>€ / nuit</span>
                        <div>
                            <a href="property-details.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-outline-secondary me-1">Voir détails</a>
                            <a href="reservation.php?property_id=<?php echo $property['id']; ?>" class="btn btn-sm btn-primary">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<script src="../js/favorites.js"></script>

<?php include_once "../includes/footer.php"; ?>
