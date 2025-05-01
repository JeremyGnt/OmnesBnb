<?php
?>
<div class="container py-4">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-2">Mes réservations</h1>
        <p class="text-muted">Gérez vos séjours passés et à venir</p>
        <div class="separator mx-auto my-3"></div>
    </div>

    <!-- Tabs for different reservation statuses -->
    <ul class="nav nav-tabs mb-4 justify-content-center">
        <li class="nav-item">
            <a class="nav-link <?php echo $active_tab == 'upcoming' ? 'active' : ''; ?>" href="?tab=upcoming">
                <i class="fas fa-calendar-alt me-2"></i> À venir <span class="badge bg-primary ms-2"><?php echo count($upcoming_reservations); ?></span>
</a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo $active_tab == 'past' ? 'active' : ''; ?>" href="?tab=past">
        <i class="fas fa-history me-2"></i> Passées <span class="badge bg-secondary ms-2"><?php echo count($past_reservations); ?></span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo $active_tab == 'cancelled' ? 'active' : ''; ?>" href="?tab=cancelled">
        <i class="fas fa-ban me-2"></i> Annulées <span class="badge bg-light text-dark ms-2"><?php echo count($cancelled_reservations); ?></span>
    </a>
</li>
</ul>

<!-- Tab content -->
<div class="tab-content">
    <!-- Upcoming Reservations -->
    <div class="tab-pane fade <?php echo $active_tab == 'upcoming' ? 'show active' : ''; ?>" id="upcoming">
        <?php if(empty($upcoming_reservations)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Vous n'avez pas de réservations à venir. <a href="../pages/search.php" class="alert-link">Recherchez un logement</a> pour planifier votre prochain séjour!
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($upcoming_reservations as $reservation): ?>
                    <div class="col-md-6 col-lg-4 mb-4">                            <div class="card reservation-card h-100">                            <div class="position-relative">
                                <?php if(!empty($reservation['main_image'])): ?>
                                    <img src="../<?php echo htmlspecialchars($reservation['main_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($reservation['title']); ?>">
                                <?php else: ?>
                                    <img src="../assets/property_images/default-property.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($reservation['title']); ?>">
                                <?php endif; ?>

                                <?php if($reservation['status'] == 'confirmed'): ?>
                                    <div class="status-badge status-confirmed">
                                        <i class="fas fa-check-circle me-1"></i> Confirmée
                                    </div>
                                <?php elseif($reservation['status'] == 'pending'): ?>
                                    <div class="status-badge status-pending">
                                        <i class="fas fa-clock me-1"></i> En attente
                                    </div>
                                <?php elseif($reservation['status'] == 'cancelled'): ?>
                                    <div class="status-badge status-cancelled">
                                        <i class="fas fa-times-circle me-1"></i> Annulée
                                    </div>
                                <?php endif; ?></div>                                <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($reservation['title']); ?></h5>

                                <!-- Location information -->
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($reservation['location']); ?>
                                </p>

                                <!-- Host information -->
                                <p class="card-text">
                                    <i class="fas fa-user"></i>
                                    Hébergé par <?php echo htmlspecialchars($reservation['owner_name']); ?>
                                </p>

                                <!-- Reservation dates in styled box -->
                                <div class="reservation-dates">
                                    <div>
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <strong>Du</strong> <?php echo date('d/m/Y', strtotime($reservation['start_date'])); ?>
                                        <strong>au</strong> <?php echo date('d/m/Y', strtotime($reservation['end_date'])); ?>
                                    </div>
                                    <div>
                                        <i class="fas fa-moon me-1"></i>
                                        <?php
                                        $interval = date_diff(date_create($reservation['start_date']), date_create($reservation['end_date']));
                                        echo $interval->format('%a') . ' nuit(s)';
                                        ?>
                                    </div>
                                </div>

                                <!-- Price information in styled box -->
                                <div class="reservation-price">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-euro-sign me-1"></i>
                                            Total
                                            <?php if(isset($reservation['guests'])): ?>
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-user-friends me-1 small"></i><?php echo $reservation['guests']; ?> voyageur(s)
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <span class="price-amount"><?php echo number_format($reservation['total_price'], 2, ',', ' '); ?> €</span>
                                    </div>
                                </div>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $reservation['id']; ?>">
                                        Annuler
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal<?php echo $reservation['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirmer l'annulation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir annuler votre réservation pour
                                            <strong><?php echo htmlspecialchars($reservation['title']); ?></strong>
                                            du <?php echo date('d/m/Y', strtotime($reservation['start_date'])); ?>
                                            au <?php echo date('d/m/Y', strtotime($reservation['end_date'])); ?> ?</p>
                                        <p class="text-danger">Cette action est irréversible.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <a href="?cancel=<?php echo $reservation['id']; ?>&tab=upcoming" class="btn btn-danger">Confirmer l'annulation</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>


<?php include "../includes/footer.php"; ?>