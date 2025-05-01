<?php

require_once "../includes/db_connection.php";
include "../includes/header.php";

// Si session deja ouverte
if(isset($_SESSION["user_id"])){
    header("location: ../index.php");
    exit;
}

// variables nulles
$email = $password = $confirm_password = $first_name = $last_name = "";
$email_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $terms_err= "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Veuillez entrer un email.";
    } else{
        // Check email format
        $email = trim($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email_err = "Format d'email invalide.";
        } else {
            // Check if email domain is from Omnes
            if(!preg_match("/@(omnesintervenant\.com|ece\.fr|edu\.ece\.fr)$/", $email)){
                $email_err = "Seules les adresses email d'Omnes sont autorisées.";
            } else {
                // Check if email already exists
                $sql = "SELECT id FROM users WHERE email = ?";

                if($stmt = mysqli_prepare($conn, $sql)){
                    mysqli_stmt_bind_param($stmt, "s", $param_email);
                    $param_email = $email;

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

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Veuillez entrer votre prénom.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Veuillez entrer votre nom.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Le mot de passe doit contenir au moins 8 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }
    //cocher case CGU
    if (!isset($_POST['terms'])) {
        $terms_err = "Vous devez accepter les conditions pour vous inscrire.";
    }

    // Check input errors before inserting in database

    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($terms_err)){

        // Determine user type based on email domain
        $user_type = 'student'; // Default
        if(preg_match("/@ece\.fr$/", $email)) {
            $user_type = 'staff';
        } elseif(preg_match("/@omnesintervenant\.com$/", $email)) {
            $user_type = 'staff';
        }

        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, first_name, last_name, user_type, is_verified) VALUES (?, ?, ?, ?, ?, FALSE)";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_email, $param_password, $param_first_name, $param_last_name, $param_user_type);

            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_user_type = $user_type;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                $_SESSION['message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                $_SESSION['message_type'] = "success";
                header("location: login.php");
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-card">
                <h2 class="text-center mb-4">Inscription</h2>
                <p class="text-center">Créez votre compte OmnesBnB avec votre adresse email Omnes</p>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>" id="first_name" placeholder="Prénom">
                                <label for="first_name">Prénom</label>
                                <div class="invalid-feedback">
                                    <?php echo $first_name_err; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>" id="last_name" placeholder="Nom">
                                <label for="last_name">Nom</label>
                                <div class="invalid-feedback">
                                    <?php echo $last_name_err; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="email" placeholder="nom@example.com">
                        <label for="email">Adresse email Omnes</label>
                        <div class="invalid-feedback">
                            <?php echo $email_err; ?>
                        </div>
                        <div class="form-text">Utilisez votre adresse email @omnesintervenant.com, @ece.fr ou @edu.ece.fr</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" placeholder="Mot de passe">
                        <label for="password">Mot de passe</label>
                        <div class="invalid-feedback">
                            <?php echo $password_err; ?>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" id="confirm_password" placeholder="Confirmez le mot de passe">
                        <label for="confirm_password">Confirmez le mot de passe</label>
                        <div class="invalid-feedback">
                            <?php echo $confirm_password_err; ?>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input <?php echo (!empty($terms_err)) ? 'is-invalid' : ''; ?>" type="checkbox" name="terms" value="1" id="terms">
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions générales d'utilisation</a>
                        </label>
                        <div class="invalid-feedback">
                            <?php echo $terms_err; ?>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                    </div>

                    <p class="text-center">Vous avez déjà un compte? <a href="login.php">Connectez-vous ici</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Condition d'utilisation -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Conditions générales d'utilisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Acceptation des conditions</h6>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>



<?php include "../includes/footer.php"; ?>
