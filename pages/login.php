<?php

require_once "../includes/db_connection.php";
include "../includes/header.php";

if (isset($_SESSION["user_id"])) {
    header("location: ../index.php");
    exit;
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
                        <label for="email">Adresse email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password"
                               id="password" placeholder="Mot de passe">
                        <label for="password">Mot de passe</label>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Connexion</button>

        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
