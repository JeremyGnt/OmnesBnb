<?php

require_once "../includes/db_connection.php";
include "../includes/header.php";

// Si session deja ouverte
if(isset($_SESSION["user_id"])){
    header("location: ../index.php");
    exit;
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
                                <input type="text" name="first_name" class="form-control " id="first_name" placeholder="Prénom">
                                <label for="first_name">Prénom</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" name="last_name" class="form-control " value="" id="last_name" placeholder="Nom">
                                <label for="last_name">Nom</label>

                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control " value="" id="email" placeholder="nom@example.com">
                        <label for="email">Adresse email Omnes</label>

                        <div class="form-text">Utilisez votre adresse email @omnesintervenant.com, @ece.fr ou @edu.ece.fr</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control " id="password" placeholder="Mot de passe">
                        <label for="password">Mot de passe</label>

                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="confirm_password" class="form-control " id="confirm_password" placeholder="Confirmez le mot de passe">
                        <label for="confirm_password">Confirmez le mot de passe</label>

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





<?php include "../includes/footer.php"; ?>
