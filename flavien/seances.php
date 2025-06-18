<?php session_start(); // Ajoutez cette ligne en tout premier
require_once 'includes/config.php';

$pageTitle = "Planning des séances";
include 'includes/header.php';

// Récupérer toutes les séances futures triées chronologiquement
$query = $db->query("
    SELECT s.*, f.titre, l.salle 
    FROM seances s
    JOIN films f ON s.id_film = f.id
    JOIN lieux l ON s.id_lieu = l.id
    ORDER BY s.horaires ASC
");
$sessions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Planning des séances</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Film</th>
            <th>Salle</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sessions as $session): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($session['horaires'])) ?></td>
            <td><?= date('H:i', strtotime($session['horaires'])) ?></td>
            <td>
                <a href="film.php?id=<?= $session['id_film'] ?>">
                    <?= $session['titre'] ?>
                </a>
            </td>
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