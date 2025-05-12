<?php
?>



<div class="container py-4">

    <div class="row mb-4">

        <h3 class="mb-4">Tableau de bord</h3>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 dashboard-card" data-card="revenus">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-25 p-3 mb-3" id="revenus-icon">
                        <i class="fas fa-euro-sign fa-2x text-success"></i>
                    </div>
                    <h5 class="card-title">Revenus</h5>
                    <h3 class="mb-0 text-success"><?php echo number_format($total_earned, 2); ?>€</h3>
                    <p class="text-muted mt-2 mb-0">Total reçu</p>
                </div>

            </div>

        </div>




        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 dashboard-card" data-card="depenses">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-25 p-3 mb-3" id="depenses-icon">
                        <i class="fas fa-shopping-cart fa-2x text-danger"></i>
                    </div>
                    <h5 class="card-title">Dépenses</h5>
                    <h3 class="mb-0 text-danger"><?php echo number_format($total_spent, 2); ?>€</h3>
                    <p class="text-muted mt-2 mb-0">Total dépensé</p>
                </div>

            </div>

        </div>



        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 dashboard-card" data-card="proprietes">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-25 p-3 mb-3" id="proprietes-icon">
                        <i class="fas fa-home fa-2x text-primary"></i>
                    </div>
                    <h5 class="card-title">Propriétés</h5>
                    <h3 class="mb-0 text-primary"><?php echo $active_listings; ?></h3>
                    <p class="text-muted mt-2 mb-0">Annonces actives</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 dashboard-card" data-card="reservations">
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-25 p-3 mb-3" id="reservations-icon">
                        <i class="fas fa-calendar-check fa-2x text-info"></i>
                    </div>
                    <h5 class="card-title">Réservations</h5>
                    <h3 class="mb-0 text-info"><?php echo $total_bookings; ?></h3>
                    <p class="text-muted mt-2 mb-0">Total des réservations</p>
                </div>
            </div>
        </div>

    </div>


</div>
