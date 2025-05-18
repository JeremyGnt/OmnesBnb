<?php

require_once "../includes/db_connection.php";
include_once "../includes/header.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Vous devez être connecté pour accéder à cette page.";
    $_SESSION['message_type'] = "danger";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$admin_check_sql = "SELECT * FROM users WHERE id = ? AND (email = 'admin@ece.fr' OR user_type = 'admin')";
$stmt = mysqli_prepare($conn, $admin_check_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$admin_result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($admin_result) == 0) {
    $_SESSION['message'] = "Vous n'avez pas les droits d'accès nécessaires.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../index.php");
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_type = isset($_GET['delete_type']) ? $_GET['delete_type'] : '';
    if ($delete_type === 'user' && $delete_id != $user_id) {
        $sql = "DELETE FROM users WHERE id = ?";
    } elseif ($delete_type === 'property') {
        $sql = "DELETE FROM properties WHERE id = ?";
    } elseif ($delete_type === 'booking') {
        $sql = "DELETE FROM bookings WHERE id = ?";
    } elseif ($delete_type === 'review') {
        $sql = "DELETE FROM reviews WHERE id = ?";
    } else {
        $sql = '';
    }
    if ($sql) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = ucfirst($delete_type) . " supprimé avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression: " . mysqli_error($conn);
            $_SESSION['message_type'] = "danger";
        }
    }
    header("Location: admin.php?tab=" . $delete_type . "s");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $type && $id) {
    if ($type === 'booking') {
        $property_id = intval($_POST['property_id']);
        $user_id_b = intval($_POST['user_id']);
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
        $guests = intval($_POST['guests']);
        $total_price = floatval($_POST['total_price']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $sql = "UPDATE bookings SET property_id=?, user_id=?, start_date=?, end_date=?, guests=?, total_price=?, status=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iissiisi", $property_id, $user_id_b, $start_date, $end_date, $guests, $total_price, $status, $id);
    } elseif ($type === 'property') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $property_type = mysqli_real_escape_string($conn, $_POST['property_type']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
        $price = floatval($_POST['price']);
        $surface_area = intval($_POST['surface_area']);
        $rooms = intval($_POST['rooms']);
        $max_guests = intval($_POST['max_guests']);
        $available_from = mysqli_real_escape_string($conn, $_POST['available_from']);
        $available_to = mysqli_real_escape_string($conn, $_POST['available_to']);
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $owner_id = intval($_POST['owner_id']);
        $sql = "UPDATE properties SET title=?, description=?, property_type=?, location=?, address=?, city=?, postal_code=?, price=?, surface_area=?, rooms=?, max_guests=?, available_from=?, available_to=?, is_published=?, owner_id=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssdiiisssiii", $title, $description, $property_type, $location, $address, $city, $postal_code, $price, $surface_area, $rooms, $max_guests, $available_from, $available_to, $is_published, $owner_id, $id);
    } elseif ($type === 'review') {
        $rating = intval($_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $sql = "UPDATE reviews SET rating=?, comment=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isi", $rating, $comment, $id);
    } else {
        $stmt = null;
    }
    if (isset($stmt) && $stmt && mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = ucfirst($type) . " mis à jour avec succès.";
        $_SESSION['message_type'] = "success";
        header("Location: admin.php?tab=" . $type . "s");
        exit;
    } elseif (isset($stmt)) {
        $_SESSION['message'] = "Erreur lors de la mise à jour: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
}
if ($type && $id) {
    if ($type === 'booking') {
        $booking_sql = "SELECT * FROM bookings WHERE id = ?";
        $booking_stmt = mysqli_prepare($conn, $booking_sql);
        mysqli_stmt_bind_param($booking_stmt, "i", $id);
        mysqli_stmt_execute($booking_stmt);
        $booking_result = mysqli_stmt_get_result($booking_stmt);
        $booking = mysqli_fetch_assoc($booking_result);
        $properties = mysqli_query($conn, "SELECT id, title FROM properties ORDER BY title");
        $users = mysqli_query($conn, "SELECT id, username FROM users ORDER BY username");
        echo '<div class="container mt-4"><h3>Éditer une réservation</h3><form method="post">';
        echo '<label>Propriété</label><select name="property_id" class="form-control">';
        while ($p = mysqli_fetch_assoc($properties)) {
            $sel = $p['id'] == $booking['property_id'] ? 'selected' : '';
            echo "<option value='{$p['id']}' $sel>{$p['title']}</option>";
        }
        echo '</select>';
        echo '<label>Utilisateur</label><select name="user_id" class="form-control">';
        while ($u = mysqli_fetch_assoc($users)) {
            $sel = $u['id'] == $booking['user_id'] ? 'selected' : '';
            echo "<option value='{$u['id']}' $sel>{$u['username']}</option>";
        }
        echo '</select>';
        echo '<label>Date début</label><input type="date" name="start_date" class="form-control" value="' . $booking['start_date'] . '">';
        echo '<label>Date fin</label><input type="date" name="end_date" class="form-control" value="' . $booking['end_date'] . '">';
        echo '<label>Nombre de voyageurs</label><input type="number" name="guests" class="form-control" value="' . $booking['guests'] . '">';
        echo '<label>Prix total</label><input type="number" step="0.01" name="total_price" class="form-control" value="' . $booking['total_price'] . '">';
        echo '<label>Statut</label><input type="text" name="status" class="form-control" value="' . $booking['status'] . '">';
        echo '<button type="submit" class="btn btn-primary mt-2">Enregistrer</button>';
        echo '</form></div>';
    } elseif ($type === 'property') {
        $property_sql = "SELECT * FROM properties WHERE id = ?";
        $property_stmt = mysqli_prepare($conn, $property_sql);
        mysqli_stmt_bind_param($property_stmt, "i", $id);
        mysqli_stmt_execute($property_stmt);
        $property_result = mysqli_stmt_get_result($property_stmt);
        $property = mysqli_fetch_assoc($property_result);
        $owners = mysqli_query($conn, "SELECT id, username FROM users ORDER BY username");
        echo '<div class="container mt-4"><h3>Éditer une propriété</h3><form method="post">';
        echo '<label>Titre</label><input type="text" name="title" class="form-control" value="' . $property['title'] . '">';
        echo '<label>Description</label><textarea name="description" class="form-control">' . $property['description'] . '</textarea>';
        echo '<label>Type</label><input type="text" name="property_type" class="form-control" value="' . $property['property_type'] . '">';
        echo '<label>Lieu</label><input type="text" name="location" class="form-control" value="' . $property['location'] . '">';
        echo '<label>Adresse</label><input type="text" name="address" class="form-control" value="' . $property['address'] . '">';
        echo '<label>Ville</label><input type="text" name="city" class="form-control" value="' . $property['city'] . '">';
        echo '<label>Code postal</label><input type="text" name="postal_code" class="form-control" value="' . $property['postal_code'] . '">';
        echo '<label>Prix</label><input type="number" step="0.01" name="price" class="form-control" value="' . $property['price'] . '">';
        echo '<label>Surface (m²)</label><input type="number" name="surface_area" class="form-control" value="' . $property['surface_area'] . '">';
        echo '<label>Pièces</label><input type="number" name="rooms" class="form-control" value="' . $property['rooms'] . '">';
        echo '<label>Voyageurs max</label><input type="number" name="max_guests" class="form-control" value="' . $property['max_guests'] . '">';
        echo '<label>Disponible du</label><input type="date" name="available_from" class="form-control" value="' . $property['available_from'] . '">';
        echo '<label>Disponible au</label><input type="date" name="available_to" class="form-control" value="' . $property['available_to'] . '">';
        echo '<label>Publié</label><input type="checkbox" name="is_published" value="1"' . ($property['is_published'] ? ' checked' : '') . '>';
        echo '<label>Propriétaire</label><select name="owner_id" class="form-control">';
        while ($o = mysqli_fetch_assoc($owners)) {
            $sel = $o['id'] == $property['owner_id'] ? 'selected' : '';
            echo "<option value='{$o['id']}' $sel>{$o['username']}</option>";
        }
        echo '</select>';
        echo '<button type="submit" class="btn btn-primary mt-2">Enregistrer</button>';
        echo '</form></div>';
    } elseif ($type === 'review') {
        $review_sql = "SELECT * FROM reviews WHERE id = ?";
        $review_stmt = mysqli_prepare($conn, $review_sql);
        mysqli_stmt_bind_param($review_stmt, "i", $id);
        mysqli_stmt_execute($review_stmt);
        $review_result = mysqli_stmt_get_result($review_stmt);
        $review = mysqli_fetch_assoc($review_result);
        echo '<div class="container mt-4"><h3>Éditer un avis</h3><form method="post">';
        echo '<label>Note</label><input type="number" name="rating" class="form-control" min="1" max="5" value="' . $review['rating'] . '">';
        echo '<label>Commentaire</label><textarea name="comment" class="form-control">' . $review['comment'] . '</textarea>';
        echo '<button type="submit" class="btn btn-primary mt-2">Enregistrer</button>';
        echo '</form></div>';
    }
}
?>






