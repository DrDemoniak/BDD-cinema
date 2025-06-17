<?php
require_once 'includes/config.php';

if (!isset($_GET['id'])) {
    header('Location: seances.php');
    exit;
}

$seanceId = (int)$_GET['id'];

// Récupérer les détails de la réservation
$query = $db->prepare("
    SELECT s.*, f.titre, l.salle 
    FROM seances s
    JOIN films f ON s.id_film = f.id
    JOIN lieux l ON s.id_lieu = l.id
    WHERE s.id = ?
");
$query->execute([$seanceId]);
$seance = $query->fetch(PDO::FETCH_ASSOC);

if (!$seance) {
    header('Location: seances.php');
    exit;
}

$pageTitle = "Réservation confirmée";
include 'includes/header.php';
?>

<div class="alert alert-success text-center">
    <h2>Votre réservation est confirmée !</h2>
</div>

<div class="card">
    <div class="card-body text-center">
        <h3><?= $seance['titre'] ?></h3>
        <p>
            <strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($seance['horaires'])) ?><br>
            <strong>Salle :</strong> <?= $seance['salle'] ?>
        </p>
        <p class="mt-4">
            Un email de confirmation vous a été envoyé.
        </p>
        <a href="film.php?id=<?= $seance['id_film'] ?>" class="btn btn-primary">
            Retour au film
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>