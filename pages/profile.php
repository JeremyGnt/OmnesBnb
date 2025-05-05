<?php
// User profile page
require_once "../includes/db_connection.php";
include "../includes/header.php";

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Vous devez vous connecter pour accéder à cette page.";
    $_SESSION["message_type"] = "warning";
    header("location: login.php");
    exit;
}

// Get user information from database
$user_id = $_SESSION["user_id"];
$username = $email = $first_name = $last_name = $phone = "";
$profile_image = "../assets/default-profile.jpg"; // Default profile image

// Prepare a select statement
$sql = "SELECT username, email, first_name, last_name, phone_number, profile_image FROM users WHERE id = ?";

if($stmt = mysqli_prepare($conn, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $user_id;

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if user exists
        if(mysqli_stmt_num_rows($stmt) == 1){
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $username, $email, $first_name, $last_name, $phone, $user_profile_image);

            if(mysqli_stmt_fetch($stmt)){
                // Get user data
                if(!empty($user_profile_image)) {
                    $profile_image = $user_profile_image;
                }
            }
        } else{
            // User not found
            $_SESSION["message"] = "Erreur lors de la récupération des informations utilisateur.";
            $_SESSION["message_type"] = "danger";
            header("location: ../index.php");
            exit;
        }
    } else{
        echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Process form submission for profile update
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize error variables
    $username_err = $email_err = $first_name_err = $last_name_err = $phone_err = $current_password_err = $new_password_err = $confirm_password_err = "";

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } else {
        $username = trim($_POST["username"]);

        // Check if username is changed
        if ($username != $_SESSION["username"]) {
            // Check if the username is already taken
            $sql = "SELECT id FROM users WHERE username = ? AND id != ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_username, $param_id);
                $param_username = $username;
                $param_id = $user_id;

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "Ce nom d'utilisateur est déjà utilisé.";
                    }
                } else {
                    echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                }

                mysqli_stmt_close($stmt);
            }
        }
    }
}