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

else if ($action === 'remove') {
    $remove_sql = "DELETE FROM favorites WHERE user_id = ? AND property_id = ?";
    $remove_stmt = mysqli_prepare($conn, $remove_sql);
    mysqli_stmt_bind_param($remove_stmt, "ii", $user_id, $property_id);

    if (mysqli_stmt_execute($remove_stmt)) {
        $activity_sql = "INSERT INTO user_activity (user_id, activity_type, property_id) VALUES (?, 'favorite_remove', ?)";
        $activity_stmt = mysqli_prepare($conn, $activity_sql);
        mysqli_stmt_bind_param($activity_stmt, "ii", $user_id, $property_id);
        mysqli_stmt_execute($activity_stmt);

        echo json_encode(['success' => true, 'message' => 'Removed from favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
}
else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

mysqli_close($conn);
?>
