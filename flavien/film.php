<?php session_start(); // Ajoutez cette ligne en tout premier
require_once 'includes/config.php';

if (!isset($_GET['id'])) {
    header('Location: catalogue.php');
    exit;
}

$filmId = (int)$_GET['id'];

// Récupérer les infos du film
$query = $db->prepare("
    SELECT f.*, r.nom AS realisateur_nom, r.prenom AS realisateur_prenom 
    FROM films f
    JOIN films_realisateurs fr ON f.id = fr.id_film
    JOIN realisateurs r ON fr.id_realisateur = r.id
    WHERE f.id = ?
");
$query->execute([$filmId]);
$film = $query->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    header('Location: catalogue.php');
    exit;
}

$pageTitle = $film['titre'];
include 'includes/header.php';

// Récupérer les acteurs
$actorsQuery = $db->prepare("
    SELECT a.* FROM acteurs a
    JOIN films_acteurs fa ON a.id = fa.id_acteur
    WHERE fa.id_film = ?
");
$actorsQuery->execute([$filmId]);
$actors = $actorsQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les séances
$sessionsQuery = $db->prepare("
    SELECT s.*, l.salle FROM seances s
    JOIN lieux l ON s.id_lieu = l.id
    WHERE s.id_film = ?
    ORDER BY s.horaires
");
$sessionsQuery->execute([$filmId]);
$sessions = $sessionsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <img src="<?= $film['url_image'] ?>" class="img-fluid" alt="<?= $film['titre'] ?>">
    </div>
    <div class="col-md-8">
        <h1><?= $film['titre'] ?> <small class="text-muted">(<?= $film['annee'] ?>)</small></h1>
        <p><strong>Réalisateur :</strong> <?= $film['realisateur_prenom'] ?> <?= $film['realisateur_nom'] ?></p>
        
        <h3 class="mt-4">Acteurs</h3>
        <ul>
            <?php foreach ($actors as $actor): ?>
            <li>
                <a href="acteur.php?id=<?= $actor['id'] ?>">
                    <?= $actor['prenom'] ?> <?= $actor['nom'] ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<h3 class="mt-5">Séances disponibles</h3>
<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Salle</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sessions as $session): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($session['horaires'])) ?></td>
            <td><?= date('H:i', strtotime($session['horaires'])) ?></td>
            <td><?= $session['salle'] ?></td>
            <td>
                <a href="reservation.php?id_seance=<?= $session['id'] ?>" class="btn btn-sm btn-success">
                    Réserver
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>