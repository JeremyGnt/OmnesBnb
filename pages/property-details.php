<?php

require_once "../includes/db_connection.php";
include_once "../includes/header.php";

echo '<link rel="stylesheet" href="../css/property-details.css">';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "Aucun logement spécifié.";
    $_SESSION['message_type'] = "warning";
    header("Location: search.php");
    exit;
}

$property_id = (int)$_GET['id'];

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$sql = "SELECT p.*, 
        u.first_name, u.last_name, u.profile_image,
        (SELECT COUNT(*) FROM favorites WHERE property_id = p.id AND user_id = ?) AS is_favorite
        FROM properties p 
        JOIN users u ON p.owner_id = u.id
        WHERE p.id = ? AND p.is_published = TRUE";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $property_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = "Ce logement n'existe pas ou n'est pas disponible.";
    $_SESSION['message_type'] = "warning";
    header("Location: search.php");
    exit;
}

$property = mysqli_fetch_assoc($result);


$images_sql = "SELECT image_path FROM property_images WHERE property_id = ?";
$images_stmt = mysqli_prepare($conn, $images_sql);
mysqli_stmt_bind_param($images_stmt, "i", $property_id);
mysqli_stmt_execute($images_stmt);
$images_result = mysqli_stmt_get_result($images_stmt);

$images = [];
while ($img = mysqli_fetch_assoc($images_result)) {
    $images[] = $img['image_path'];
}


if (!empty($property['main_image'])) {

    $main_image_index = array_search($property['main_image'], $images);
    if ($main_image_index !== false) {
        array_splice($images, $main_image_index, 1);
    }
    array_unshift($images, $property['main_image']);
}

if (empty($images)) {
    $images[] = "assets/property_images/default.jpg";
}

$reviews_sql = "SELECT r.*, b.user_id, u.first_name, u.last_name, u.profile_image
                FROM reviews r
                JOIN bookings b ON r.booking_id = b.id
                JOIN users u ON b.user_id = u.id
                WHERE b.property_id = ?
                ORDER BY r.created_at DESC";

$reviews_stmt = mysqli_prepare($conn, $reviews_sql);
mysqli_stmt_bind_param($reviews_stmt, "i", $property_id);
mysqli_stmt_execute($reviews_stmt);
$reviews_result = mysqli_stmt_get_result($reviews_stmt);

$reviews = [];
$total_rating = 0;
$review_count = 0;

while ($review = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $review;
    $total_rating += $review['rating'];
    $review_count++;
}

$average_rating = $review_count > 0 ? round($total_rating / $review_count, 1) : 0;

$amenities = !empty($property['amenities']) ? explode(',', $property['amenities']) : [];


if ($user_id) {
    $activity_sql = "INSERT INTO user_activity (user_id, activity_type, property_id) VALUES (?, 'property_view', ?)";
    $activity_stmt = mysqli_prepare($conn, $activity_sql);
    mysqli_stmt_bind_param($activity_stmt, "ii", $user_id, $property_id);
    mysqli_stmt_execute($activity_stmt);
}
?>
