<?php
include "../includes/header.php";
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4">Confirmer votre réservation</h2>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Détails du séjour</h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-8">
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-user-friends me-2"></i>Logement entier · Hôte:
                            </p>
                            <p>
                                <i class="fas fa-home me-2"></i> pièce(s) ·  voyageurs max
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Arrivée</h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Départ</h6>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6>Voyageurs</h6>
                        <p> voyageur(s)</p>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Vos coordonnées</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nom</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email</strong></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Téléphone</strong></p>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Ces informations seront partagées avec l'hôte une fois votre réservation confirmée.
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Politique d'annulation</h5>
                </div>
                <div class="card-body">
                    <p>Annulation gratuite jusqu'à 48 heures avant votre arrivée. Après cette date, des frais peuvent s'appliquer.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card booking-summary">
                <div class="card-header">
                    <h5 class="mb-0">Résumé du prix</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                    </div>

                    <form method="post" action="">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions générales</a>
                            </label>
                        </div>

                        <button type="submit" name="confirm_booking" class="btn btn-primary btn-lg w-100">
                            Confirmer et payer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include "../includes/footer.php"; ?>
