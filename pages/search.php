<?php
require_once "../includes/db_connection.php";
include "../includes/header.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Vous devez vous connecter pour accéder à cette page.";
    $_SESSION["message_type"] = "warning";
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $email = $first_name = $last_name = $phone = "";
//$profile_image = "../assets/default-profile.jpg";///AJOUTER IMAGE PAR DEFAUT

$sql = "SELECT username, email, first_name, last_name, phone_number, profile_image FROM users WHERE id = ?";

if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    $param_id = $user_id;

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) == 1){
            mysqli_stmt_bind_result($stmt, $username, $email, $first_name, $last_name, $phone, $user_profile_image);

            if(mysqli_stmt_fetch($stmt)){
                if(!empty($user_profile_image)) {
                    $profile_image = $user_profile_image;
                }
            }
        } else{
            $_SESSION["message"] = "Erreur lors de la récupération des informations utilisateur.";
            $_SESSION["message_type"] = "danger";
            header("location: ../index.php");
            exit;
        }
    } else{
        echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
    }

    mysqli_stmt_close($stmt);
}
if($stmt = mysqli_prepare($conn, $sql)) {
    // Set parameters and bind
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        // With password update
        if ($upload_image) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssi",
                $param_username,
                $param_email,
                $param_first_name,
                $param_last_name,
                $param_phone,
                $param_password,
                $param_profile_image,
                $param_id
            );
            $param_profile_image = $new_profile_image;
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssi",
                $param_username,
                $param_email,
                $param_first_name,
                $param_last_name,
                $param_phone,
                $param_password,
                $param_id
            );
        }
        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {                // Without password update
        if ($upload_image) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssi",
                $param_username,
                $param_email,
                $param_first_name,
                $param_last_name,
                $param_phone,
                $param_profile_image,
                $param_id
            );
            $param_profile_image = $new_profile_image;
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssi",
                $param_username,
                $param_email,
                $param_first_name,
                $param_last_name,
                $param_phone,
                $param_id
            );
        }
    }
}
