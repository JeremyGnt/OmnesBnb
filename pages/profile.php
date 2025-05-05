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

/// METTRE IMAGE EN PHOTO DE PROIL
//$profile_image = "../assets/default-profile.jpg"; // Default profile image

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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username_err = $email_err = $first_name_err = $last_name_err = $phone_err = $current_password_err = $new_password_err = $confirm_password_err = "";

    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } else{
        $username = trim($_POST["username"]);

        if($username != $_SESSION["username"]){
            $sql = "SELECT id FROM users WHERE username = ? AND id != ?";

            if($stmt = mysqli_prepare($conn, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $param_username, $param_id);
                $param_username = $username;
                $param_id = $user_id;

                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "Ce nom d'utilisateur est déjà utilisé.";
                    }
                } else{
                    echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                }

                mysqli_stmt_close($stmt);
            }
        }
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Veuillez entrer un email.";
    } else{
        $email = trim($_POST["email"]);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email_err = "Format d'email invalide.";
        } else {
            if(!preg_match("/@(omnesintervenant\.com|ece\.fr|edu\.ece\.fr)$/", $email)){
                $email_err = "Seules les adresses email d'Omnes sont autorisées.";
            } else {
                if($email != $_SESSION["email"]){
                    $sql = "SELECT id FROM users WHERE email = ? AND id != ?";

                    if($stmt = mysqli_prepare($conn, $sql)){
                        mysqli_stmt_bind_param($stmt, "si", $param_email, $param_id);
                        $param_email = $email;
                        $param_id = $user_id;

                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);

                            if(mysqli_stmt_num_rows($stmt) == 1){
                                $email_err = "Cette adresse email est déjà utilisée.";
                            }
                        } else{
                            echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                        }

                        mysqli_stmt_close($stmt);
                    }
                }
            }
        }
    }

    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Veuillez entrer votre prénom.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Veuillez entrer votre nom.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    if(!empty(trim($_POST["phone"]))){
        $phone = trim($_POST["phone"]);

        if(!preg_match("/^[0-9+\s()-]{8,20}$/", $phone)){
            $phone_err = "Format de numéro de téléphone invalide.";
        }
    }
    if(isset($_POST["current_password"]) && !empty(trim($_POST["current_password"]))){
        $current_password = trim($_POST["current_password"]);

        $sql = "SELECT password FROM users WHERE id = ?";

        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $user_id;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                        if(!password_verify($current_password, $hashed_password)){
                            $current_password_err = "Le mot de passe actuel est incorrect.";
                        }
                    }
                }
            }

            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["new_password"]))){
            $new_password_err = "Veuillez entrer le nouveau mot de passe.";
        } elseif(strlen(trim($_POST["new_password"])) < 8){
            $new_password_err = "Le mot de passe doit contenir au moins 8 caractères.";
        } else{
            $new_password = trim($_POST["new_password"]);
        }

        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Veuillez confirmer le nouveau mot de passe.";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)){
                $confirm_password_err = "Les mots de passe ne correspondent pas.";
            }
        }
    }