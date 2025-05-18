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

?>
