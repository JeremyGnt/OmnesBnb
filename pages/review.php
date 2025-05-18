<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Vous devez vous connecter pour laisser un avis.";
    $_SESSION["message_type"] = "warning";
    header("location: login.php");
    exit;
}

require_once "../includes/db_connection.php";
include "../includes/header.php";

$user_id = $_SESSION["user_id"];
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id <= 0) {
    echo '<div class="alert alert-danger">Réservation introuvable.</div>';
    include "../includes/footer.php";
    exit;
}

$sql = "SELECT b.*, p.title FROM bookings b JOIN properties p ON b.property_id = p.id WHERE b.id = ? AND b.user_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $reservation = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    $reservation = false;
}

if (!$reservation) {
    echo '<div class="alert alert-danger">Réservation introuvable ou accès non autorisé.</div>';
    include "../includes/footer.php";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $errors = [];
    if ($rating < 1 || $rating > 5) {
        $errors[] = "La note doit être comprise entre 1 et 5.";
    }
    if (empty($comment)) {
        $errors[] = "Le commentaire ne peut pas être vide.";
    }
    if (empty($errors)) {
        // Vérifier qu'il n'y a pas déjà un avis pour cette réservation
        $check_sql = "SELECT id FROM reviews WHERE booking_id = ?";
        if ($check_stmt = mysqli_prepare($conn, $check_sql)) {
            mysqli_stmt_bind_param($check_stmt, "i", $booking_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $errors[] = "Vous avez déjà laissé un avis pour cette réservation.";
            }
            mysqli_stmt_close($check_stmt);
        }
    }
    if (empty($errors)) {
        $now = date('Y-m-d H:i:s');
        $insert_sql = "INSERT INTO reviews (booking_id, rating, comment, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
        if ($insert_stmt = mysqli_prepare($conn, $insert_sql)) {
            mysqli_stmt_bind_param($insert_stmt, "iisss", $booking_id, $rating, $comment, $now, $now);
            if (mysqli_stmt_execute($insert_stmt)) {
                echo '<div class="alert alert-success">Merci pour votre avis !</div>';
            } else {
                echo '<div class="alert alert-danger">Erreur lors de l\'enregistrement de l\'avis.</div>';
            }
            mysqli_stmt_close($insert_stmt);
        } else {
            echo '<div class="alert alert-danger">Erreur lors de la préparation de la requête.</div>';
        }
    } else {
        foreach ($errors as $err) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($err) . '</div>';
        }
    }
}

// Affichage du formulaire d'avis (pas de traitement POST ici, à compléter selon besoin)
echo '<div class="container py-4">';
echo '<h2>Laisser un avis pour : ' . htmlspecialchars($reservation['title']) . '</h2>';
echo '<form method="post" action="#">';
echo '<div class="mb-3"><label for="rating" class="form-label">Note (1 à 5)</label><input type="number" min="1" max="5" name="rating" id="rating" class="form-control" required></div>';
echo '<div class="mb-3"><label for="comment" class="form-label">Commentaire</label><textarea name="comment" id="comment" class="form-control" rows="4" required></textarea></div>';
echo '<button type="submit" class="btn btn-primary">Envoyer l\'avis</button>';
echo '</form>';
echo '</div>';

include "../includes/footer.php";
