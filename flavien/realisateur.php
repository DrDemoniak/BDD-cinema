<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: catalogue.php');
    exit;
}

$realisateurId = (int)$_GET['id'];

// Récupérer les infos du réalisateur
$realisateurQuery = $db->prepare("
    SELECT * FROM realisateurs 
    WHERE id = ?
");
$realisateurQuery->execute([$realisateurId]);
$realisateur = $realisateurQuery->fetch(PDO::FETCH_ASSOC);

if (!$realisateur) {
    header('Location: catalogue.php');
    exit;
}

// Récupérer les films du réalisateur
$filmsQuery = $db->prepare("
    SELECT f.* FROM films f
    JOIN films_realisateurs fr ON f.id = fr.id_film
    WHERE fr.id_realisateur = ?
    ORDER BY f.annee DESC
");
$filmsQuery->execute([$realisateurId]);
$films = $filmsQuery->fetchAll(PDO::FETCH_ASSOC);

// Construire le lien Wikipedia
$wikipediaLink = "https://fr.wikipedia.org/wiki/" . 
                 str_replace(' ', '_', $realisateur['prenom'] . '_' . $realisateur['nom']);

$pageTitle = $realisateur['prenom'] . " " . $realisateur['nom'];
include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-4">
        <!-- Photo du réalisateur -->
        <div class="realisateur-photo mb-4" style="
            width: 100%; 
            height: 300px; 
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        ">
            <i class="fas fa-film" style="font-size: 100px; color: #666;"></i>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">Informations</div>
            <div class="card-body">
                <p><strong>Date de naissance :</strong> 
                    <?= !empty($realisateur['date_naissance']) ? date('d/m/Y', strtotime($realisateur['date_naissance'])) : 'Non renseignée' ?>
                </p>
                
                <?php if (!empty($realisateur['date_naissance'])): ?>
                    <p><strong>Âge :</strong> 
                        <?= date_diff(date_create($realisateur['date_naissance']), date_create('today'))->y ?> ans
                    </p>
                <?php endif; ?>
                
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
                    <?php if (!empty($realisateur['bio'])): ?>
                        <?= nl2br(htmlspecialchars($realisateur['bio'])) ?>
                    <?php else: ?>
                        <em>Aucune biographie disponible pour le moment.</em>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <h3 class="mb-4">Filmographie dans notre catalogue</h3>
        
        <?php if (!empty($films)): ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                <?php foreach ($films as $film): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($film['url_image']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($film['titre']) ?>" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($film['titre']) ?></h5>
                            <p class="card-text"><?= $film['annee'] ?></p>
                            <a href="film.php?id=<?= $film['id'] ?>" class="btn btn-sm btn-primary">
                                Voir le film
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Ce réalisateur n'a aucun film dans notre catalogue actuellement.
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .realisateur-photo {
        transition: transform 0.3s ease;
    }
    .realisateur-photo:hover {
        transform: scale(1.02);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>

<?php include 'includes/footer.php'; ?>