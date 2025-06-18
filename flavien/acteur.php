<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: catalogue.php');
    exit;
}

$actorId = (int)$_GET['id'];

// Récupérer les infos de l'acteur
$actorQuery = $db->prepare("
    SELECT * FROM acteurs 
    WHERE id = ?
");
$actorQuery->execute([$actorId]);
$actor = $actorQuery->fetch(PDO::FETCH_ASSOC);

if (!$actor) {
    header('Location: catalogue.php');
    exit;
}

// Récupérer les films de l'acteur
$filmsQuery = $db->prepare("
    SELECT f.* FROM films f
    JOIN films_acteurs fa ON f.id = fa.id_film
    WHERE fa.id_acteur = ?
    ORDER BY f.annee DESC
");
$filmsQuery->execute([$actorId]);
$films = $filmsQuery->fetchAll(PDO::FETCH_ASSOC);

// Construire le lien Wikipedia (format: Prénom_Nom)
$wikipediaLink = "https://fr.wikipedia.org/wiki/" . 
                 str_replace(' ', '_', $actor['prenom'] . '_' . $actor['nom']);

$pageTitle = $actor['prenom'] . " " . $actor['nom'];
include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-4">
        <!-- Placeholder pour la photo - à remplacer par un vrai système si disponible -->
        <div class="actor-photo mb-4" style="
            width: 100%; 
            height: 300px; 
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        ">
            <i class="fas fa-user" style="font-size: 100px; color: #666;"></i>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">Informations</div>
            <div class="card-body">
                <p><strong>Date de naissance :</strong> <?= date('d/m/Y', strtotime($actor['date_naissance'])) ?></p>
                <p><strong>Âge :</strong> <?= date_diff(date_create($actor['date_naissance']), date_create('today'))->y ?> ans</p>
                
                <a href="<?= $wikipediaLink ?>" target="_blank" class="btn btn-outline-primary w-100 mt-3">
                    <i class="fas fa-external-link-alt"></i> Voir sur Wikipedia
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">Biographie</div>
            <div class="card-body">
                <p class="card-text">
                    <?php if (!empty($actor['bio'])): ?>
                        <?= nl2br(htmlspecialchars($actor['bio'])) ?>
                    <?php else: ?>
                        <em>Aucune biographie disponible pour le moment.</em>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <h3 class="mb-4">Films dans notre catalogue</h3>
        
        <?php if (!empty($films)): ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                <?php foreach ($films as $film): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?= $film['url_image'] ?>" class="card-img-top" alt="<?= $film['titre'] ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $film['titre'] ?></h5>
                            <p class="card-text"><?= $film['annee'] ?></p>
                            <a href="film.php?id=<?= $film['id'] ?>" class="btn btn-sm btn-primary">Voir le film</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Cet acteur n'apparaît dans aucun film de notre catalogue actuellement.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>