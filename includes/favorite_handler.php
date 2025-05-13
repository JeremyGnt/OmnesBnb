<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "db_connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Utilisateur non connecté',
        'redirect' => '/TousMesProjets/omnesbnb-equipe-2h/pages/login.php'
    ]);
    exit;
}
$user_id = $_SESSION['user_id'];

if (!isset($_POST['property_id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

$property_id = (int)$_POST['property_id'];
$action = $_POST['action'];

if ($property_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'property_id invalide']);
    exit;
}

if ($action === 'add') {
    $check_sql = "SELECT id FROM favorites WHERE user_id = ? AND property_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $property_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo json_encode(['success' => true, 'message' => 'Déjà un favori']);
        exit;
    }

    $add_sql = "INSERT INTO favorites (user_id, property_id) VALUES (?, ?)";
    $add_stmt = mysqli_prepare($conn, $add_sql);
    mysqli_stmt_bind_param($add_stmt, "ii", $user_id, $property_id);

    if (mysqli_stmt_execute($add_stmt)) {
        $activity_sql = "INSERT INTO user_activity (user_id, activity_type, property_id) VALUES (?, 'favorite_add', ?)";
        $activity_stmt = mysqli_prepare($conn, $activity_sql);
        mysqli_stmt_bind_param($activity_stmt, "ii", $user_id, $property_id);
        mysqli_stmt_execute($activity_stmt);

        echo json_encode(['success' => true, 'message' => 'Ajouté aux favoris']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur:' . mysqli_error($conn)]);
    }
}
else if ($action === 'remove') {
    $remove_sql = "DELETE FROM favorites WHERE user_id = ? AND property_id = ?";
    $remove_stmt = mysqli_prepare($conn, $remove_sql);
    mysqli_stmt_bind_param($remove_stmt, "ii", $user_id, $property_id);

    if (mysqli_stmt_execute($remove_stmt)) {
        $activity_sql = "INSERT INTO user_activity (user_id, activity_type, property_id) VALUES (?, 'favorite_remove', ?)";
        $activity_stmt = mysqli_prepare($conn, $activity_sql);
        mysqli_stmt_bind_param($activity_stmt, "ii", $user_id, $property_id);
        mysqli_stmt_execute($activity_stmt);

        echo json_encode(['success' => true, 'message' => 'Retiré des favoris']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database erreure: ' . mysqli_error($conn)]);
    }
}
else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

mysqli_close($conn);
?>
