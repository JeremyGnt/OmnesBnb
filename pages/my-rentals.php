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
    <!-- Détails des cartes dashboard -->
    <div id="dashboard-details" class="mb-4" style="display: none;">
        <!-- Réservations details -->
        <div id="reservations-details" class="dashboard-detail-card">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info bg-opacity-10 border-0">
                    <h5 class="mb-0 text-info"><i class="fas fa-calendar-check me-2"></i>Détails des réservations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="mb-3">Statistiques de réservation</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total réservations
                                    <span class="badge bg-info rounded-pill"><?php echo $total_bookings; ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Ce mois-ci
                                    <span class="badge bg-primary rounded-pill"><?php echo ceil($total_bookings * 0.4); ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    À venir
                                    <span class="badge bg-success rounded-pill"><?php echo ceil($total_bookings * 0.6); ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8">
                            <h6 class="mb-3">Réservations récentes</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Dates</th>
                                        <th>Client</th>
                                        <th>Propriété</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $recentBookings = [];
                                    foreach ($my_properties as $property) {
                                        foreach ($property['bookings'] as $booking) {
                                            $booking['property_title'] = $property['title'];
                                            $booking['property_id'] = $property['id'];
                                            $recentBookings[] = $booking;
                                        }
                                    }
                                    usort($recentBookings, function($a, $b) {
                                        return strtotime($b['start_date']) - strtotime($a['start_date']);
                                    });

                                    $count = 0;
                                    foreach ($recentBookings as $booking):
                                        if ($count >= 5) break;
                                        ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($booking['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($booking['end_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($booking['tenant_name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['property_title']); ?></td>
                                            <td>
                                                <?php if ($booking['status'] == 'pending'): ?>
                                                    <span class="badge bg-warning">En attente</span>
                                                <?php elseif ($booking['status'] == 'confirmed'): ?>
                                                    <span class="badge bg-success">Confirmé</span>
                                                <?php elseif ($booking['status'] == 'cancelled'): ?>
                                                    <span class="badge bg-danger">Annulé</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Autre</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if ($booking['status'] == 'pending'): ?>
                                                        <button type="button" class="btn btn-success confirm-booking" data-booking-id="<?php echo $booking['id']; ?>">
                                                            <i class="fas fa-check"></i> Confirmer
                                                        </button>
                                                        <button type="button" class="btn btn-danger cancel-booking" data-booking-id="<?php echo $booking['id']; ?>">
                                                            <i class="fas fa-times"></i> Annuler
                                                        </button>
                                                    <?php elseif ($booking['status'] == 'confirmed'): ?>
                                                        <button type="button" class="btn btn-danger cancel-booking" data-booking-id="<?php echo $booking['id']; ?>">
                                                            <i class="fas fa-times"></i> Annuler
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    endforeach;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
