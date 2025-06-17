<?php
require_once 'includes/config.php';

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

$pageTitle = "Résultats pour : " . htmlspecialchars($searchTerm);
include 'includes/header.php';

if (empty($searchTerm)) {
    echo '<div class="alert alert-info">Veuillez entrer un terme de recherche</div>';
    include 'includes/footer.php';
    exit;
}

// Recherche dans les films
$filmsQuery = $db->prepare("
    SELECT * FROM films 
    WHERE titre LIKE :search
    ORDER BY titre
");
$filmsQuery->execute([':search' => "%$searchTerm%"]);
$films = $filmsQuery->fetchAll(PDO::FETCH_ASSOC);

// Recherche dans les acteurs
$actorsQuery = $db->prepare("
    SELECT * FROM acteurs 
    WHERE nom LIKE :search OR prenom LIKE :search
    ORDER BY nom
");
$actorsQuery->execute([':search' => "%$searchTerm%"]);
$actors = $actorsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Résultats pour "<?= htmlspecialchars($searchTerm) ?>"</h2>

<?php if (empty($films) && empty($actors)): ?>
    <div class="alert alert-warning">Aucun résultat trouvé</div>
<?php endif; ?>

<?php if (!empty($films)): ?>
    <h3 class="mt-4">Films</h3>
    <div class="row">
        <?php foreach ($films as $film): ?>
        <div class="col-md-2 mb-4">
            <div class="card h-100">
                <img src="<?= $film['url_image'] ?>" class="img-fluid" alt="<?= $film['titre'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $film['titre'] ?></h5>
                    <a href="film.php?id=<?= $film['id'] ?>" class="btn btn-sm btn-primary">Voir</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($actors)): ?>
    <h3 class="mt-4">Acteurs</h3>
    <div class="list-group">
        <?php foreach ($actors as $actor): ?>
        <a href="acteur.php?id=<?= $actor['id'] ?>" class="list-group-item list-group-item-action">
            <?= $actor['prenom'] ?> <?= $actor['nom'] ?>
        </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>