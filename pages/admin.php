<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmnesBnB - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../omnesbnb-equipe-2h/index.php">OmnesBnB</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../omnesbnb-equipe-2h/index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../omnesbnb-equipe-2h/pages/admin.php">Administration</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../omnesbnb-equipe-2h/index.php">
                        <i class="fas fa-arrow-left me-2"></i>Retour au site
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../omnesbnb-equipe-2h/pages/profile.php"><i class="fas fa-user me-2"></i>Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../omnesbnb-equipe-2h/includes/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-3">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
</div>

<!-- Admin container -->
<div class="container-fluid admin-container">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="sidebar-sticky">
                <div class="admin-profile text-center p-3">
                    <img src="<?= htmlspecialchars("../" . $admin_user['profile_image']) ?>" class="admin-avatar" alt="Admin">
                    <h5><?= htmlspecialchars($admin_user['username']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($admin_user['email']) ?></p>
                </div>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $active_tab == 'users' ? 'active' : '' ?>" href="?tab=users">
                            <i class="fas fa-users"></i>
                            Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $active_tab == 'properties' ? 'active' : '' ?>" href="?tab=properties">
                            <i class="fas fa-home"></i>
                            Propriétés
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $active_tab == 'bookings' ? 'active' : '' ?>" href="?tab=bookings">
                            <i class="fas fa-calendar-check"></i>
                            Réservations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $active_tab == 'reviews' ? 'active' : '' ?>" href="?tab=reviews">
                            <i class="fas fa-star"></i>
                            Avis
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-10 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Panneau d'administration</h1>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade <?= $active_tab == 'users' ? 'show active' : '' ?>" id="users-tab">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Gestion des utilisateurs</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom d'utilisateur</th>
                                        <th>Email</th>
                                        <th>Nom complet</th>
                                        <th>Type</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td><?= htmlspecialchars($user['username']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                            <td>
                                                <?php if ($user['user_type'] == 'admin'): ?>
                                                    <span class="badge bg-danger">Administrateur</span>
                                                <?php elseif ($user['user_type'] == 'staff'): ?>
                                                    <span class="badge bg-primary">Staff</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Étudiant</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <?php if ($user['id'] != $user_id): ?>
                                                    <button class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmDelete('user', <?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- propriétés de la Tab -->
                <div class="tab-pane fade <?= $active_tab == 'properties' ? 'show active' : '' ?>" id="properties-tab">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Gestion des propriétés</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Localisation</th>
                                        <th>Prix/nuit</th>
                                        <th>Propriétaire</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($properties as $property): ?>
                                        <tr>
                                            <td><?= $property['id'] ?></td>
                                            <td><?= htmlspecialchars($property['title']) ?></td>
                                            <td>
                                                <?php
                                                switch ($property['property_type']) {
                                                    case 'apartment':
                                                        echo 'Appartement';
                                                        break;
                                                    case 'studio':
                                                        echo 'Studio';
                                                        break;
                                                    case 'house':
                                                        echo 'Maison';
                                                        break;
                                                    case 'room':
                                                        echo 'Chambre';
                                                        break;
                                                    default:
                                                        echo $property['property_type'];
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($property['location']) ?></td>
                                            <td><?= number_format($property['price'], 2, ',', ' ') ?> €</td>
                                            <td>
                                                <?= htmlspecialchars($property['owner_name']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($property['owner_email']) ?></small>
                                            </td>
                                            <td>
                                                <?php if ($property['is_published']): ?>
                                                    <span class="badge bg-success">Publié</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Non publié</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="property-details.php?id=<?= $property['id'] ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                                <a href="edit_property.php?id=<?= $property['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete('property', <?= $property['id'] ?>, '<?= htmlspecialchars($property['title']) ?>')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- reservations Tab -->
                <div class="tab-pane fade <?= $active_tab == 'bookings' ? 'show active' : '' ?>" id="bookings-tab">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Gestion des réservations</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Propriété</th>
                                        <th>Locataire</th>
                                        <th>Propriétaire</th>
                                        <th>Dates</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><?= $booking['id'] ?></td>
                                            <td>
                                                <a href="property-details.php?id=<?= $booking['property_id'] ?>">
                                                    <?= htmlspecialchars($booking['property_title']) ?>
                                                </a><br>
                                                <small class="text-muted"><?= htmlspecialchars($booking['property_location']) ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($booking['renter_name']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($booking['renter_email']) ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($booking['owner_name']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($booking['owner_email']) ?></small>
                                            </td>
                                            <td>
                                                Du <?= date('d/m/Y', strtotime($booking['start_date'])) ?><br>
                                                au <?= date('d/m/Y', strtotime($booking['end_date'])) ?>
                                            </td>
                                            <td><?= number_format($booking['total_price'], 2, ',', ' ') ?> €</td>
                                            <td>
                                                <?php if ($booking['status'] == 'confirmed'): ?>
                                                    <span class="badge bg-success">Confirmée</span>
                                                <?php elseif ($booking['status'] == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Annulée</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="edit_booking.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete('booking', <?= $booking['id'] ?>, 'Réservation #<?= $booking['id'] ?>')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>