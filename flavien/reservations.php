<?php
session_start();
require_once 'includes/config.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$pageTitle = "Mes réservations";
include 'includes/header.php';

// Récupérer les réservations de l'utilisateur
$query = $db->prepare("
    SELECT 
        su.id_seance,
        f.titre AS film_titre,
        f.url_image AS film_image,
        s.horaires,
        l.salle
    FROM seances_utilisateurs su
    JOIN seances s ON su.id_seance = s.id
    JOIN films f ON s.id_film = f.id
    JOIN lieux l ON s.id_lieu = l.id
    WHERE su.id_utilisateur = ?
    ORDER BY s.horaires DESC
");
$query->execute([$_SESSION['user_id']]);
$reservations = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h1 class="mb-4">Mes réservations</h1>

    <?php if (empty($reservations)): ?>
        <div class="alert alert-info">
            Vous n'avez aucune réservation pour le moment.
            <a href="seances.php" class="alert-link">Voir les séances disponibles</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reservations as $reservation): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= htmlspecialchars($reservation['film_image']) ?>" 
                                     class="img-fluid rounded-start h-100" 
                                     alt="<?= htmlspecialchars($reservation['film_titre']) ?>"
                                     style="object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($reservation['film_titre']) ?></h5>
                                    <p class="card-text">
                                        <strong>Date :</strong> <?= date('d/m/Y', strtotime($reservation['horaires'])) ?><br>
                                        <strong>Heure :</strong> <?= date('H:i', strtotime($reservation['horaires'])) ?><br>
                                        <strong>Salle :</strong> <?= htmlspecialchars($reservation['salle']) ?>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Réservé le <?= date('d/m/Y à H:i') ?>
                                        </small>
                                    </p>
                                    <a href="film.php?id=<?= $reservation['id_film'] ?? '' ?>" class="btn btn-primary">
                                        Voir le film
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>