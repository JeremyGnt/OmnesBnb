<?php

require_once "../includes/db_connection.php";
include "../includes/header.php";

if (isset($_SESSION["user_id"])) {
    header("location: ../index.php");
    exit;
}

// variables nulles
$email = "";
$password = "";
$email_err = "";
$password_err = "";
$login_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // SI mail vide
    if (empty(trim($_POST["email"]))) {
        $email_err = "Veuillez entrer votre email.";
    } else {
        $email = trim($_POST["email"]);

        // uniquement mail OMNES
        if (!preg_match("/@(omnesintervenant\.com|ece\.fr|edu\.ece\.fr)$/", $email)) {
            $email_err = "Seules les adresses email d'Omnes sont autorisées.";
        }
    }

    // pareil pour mdp
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer votre mot de passe.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, username, email, password, first_name, last_name, phone_number, profile_image, user_type, is_verified FROM users WHERE email = ?";


        //LIAISON
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                // 1 seul utilsateur correspond à cette email
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // récupére les variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $first_name, $last_name, $phone_number, $profile_image, $user_type, $is_verified);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {

                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;
                            $_SESSION["first_name"] = $first_name;
                            $_SESSION["last_name"] = $last_name;
                            $_SESSION["phone_number"] = $phone_number;
                            $_SESSION["profile_image"] = $profile_image;
                            $_SESSION["user_type"] = $user_type;
                            $_SESSION["is_verified"] = $is_verified;

                            // Redirection
                            header("location: ../index.php");
                        } else {
                            $login_err = "Email ou mot de passe incorrect.";
                        }
                    }
                } else {
                    $login_err = "Email ou mot de passe incorrect.";
                }
            } else {
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Close requête
            mysqli_stmt_close($stmt);
        }
    }

    // fermer connection
    mysqli_close($conn);
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-card">
                <h2 class="text-center mb-4">Connexion</h2>
                <p class="text-center">Connectez-vous à votre compte OmnesBnB</p>

                <?php
                if (!empty($login_err)) {
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-floating mb-3">
                        <input type="email" name="email"
                               class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $email; ?>" id="email" placeholder="nom@example.com">
                        <label for="email">Adresse email</label>
                        <div class="invalid-feedback">
                            <?php echo $email_err; ?>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password"
                               class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                               id="password" placeholder="Mot de passe">
                        <label for="password">Mot de passe</label>
                        <div class="invalid-feedback">
                            <?php echo $password_err; ?>
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Connexion</button>
                    </div>
                    <p class="text-center">Vous n'avez pas de compte? <a href="register.php">Inscrivez-vous
                            maintenant</a>.</p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
