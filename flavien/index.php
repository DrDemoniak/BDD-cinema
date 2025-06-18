<?php session_start(); // Ajoutez cette ligne en tout premier
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = "Accueil";
include 'includes/header.php';

// Requête pour les films récents
$query = $db->query("SELECT * FROM films ORDER BY annee DESC LIMIT 5");
$recentFilms = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero-section mb-5">
    <h1>Bienvenue sur <?= SITE_NAME ?></h1>
    <p>Découvrez les derniers films à l'affiche</p>
</div>

<h2 class="mb-4">Nouveautés</h2>
<div class="row">
    <?php foreach ($recentFilms as $film): ?>
    <div class="col-md-2 mb-4">
        <div class="card h-100">
            <img src="<?= $film['url_image'] ?>" class="img-fluid" alt="<?= $film['titre'] ?>">
            <div class="card-body">
                <h5 class="card-title"><?= $film['titre'] ?></h5>
                <p class="card-text"><?= $film['annee'] ?></p>
                <a href="film.php?id=<?= $film['id'] ?>" class="btn btn-primary">Voir plus</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>