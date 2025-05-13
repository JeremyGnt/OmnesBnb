<?php

session_start();

require_once "db_connection.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vous devez être connecté pour effectuer cette action.'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['property_id']) || !isset($_POST['action'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Paramètres manquants.'
    ]);
    exit;
}


$property_id = $_POST['property_id'];
$action = $_POST['action'];

//
$check_owner_sql = "SELECT owner_id FROM properties WHERE id = ?";
if ($check_stmt = mysqli_prepare($conn, $check_owner_sql)) {
    mysqli_stmt_bind_param($check_stmt, "i", $property_id);

    if (mysqli_stmt_execute($check_stmt)) {
        $owner_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($owner_result) > 0) {
            $property_data = mysqli_fetch_assoc($owner_result);

            // Vérifier si l'utilisateur est bien le propriétaire
            if ($property_data['owner_id'] == $user_id) {
                // Mettre à jour le statut de la propriété
                $update_sql = "UPDATE properties SET is_published = ? WHERE id = ?";

                if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "ii", $status, $property_id);

                    if (mysqli_stmt_execute($update_stmt)) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Le statut de la propriété a été mis à jour avec succès.',
                            'new_status' => $status
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Une erreur est survenue lors de la mise à jour du statut : ' . mysqli_error($conn)
                        ]);
                    }


