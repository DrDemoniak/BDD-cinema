<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Vérification de l'ID du réalisateur dans l'URL
if (!isset($_GET['id'])) {
    header('Location: catalogue.php');
    exit;
}

$realisateurId = (int)$_GET['id'];

// 1. RÉCUPÉRATION DES INFORMATIONS DU RÉALISATEUR
$realisateurQuery = $db->prepare("
    SELECT * FROM realisateurs 
    WHERE id = ?
");
$realisateurQuery->execute([$realisateurId]);
$realisateur = $realisateurQuery->fetch(PDO::FETCH_ASSOC);

// Redirection si le réalisateur n'existe pas
if (!$realisateur) {
    header('Location: catalogue.php');
    exit;
}

// 2. RÉCUPÉRATION DE LA FILMOGRAPHIE (avec gestion des affiches)
$filmsQuery = $db->prepare("
    SELECT f.* FROM films f
    JOIN films_realisateurs fr ON f.id = fr.id_film
    WHERE fr.id_realisateur = ?
    ORDER BY f.annee DESC
");
$filmsQuery->execute([$realisateurId]);
$films = $filmsQuery->fetchAll(PDO::FETCH_ASSOC);

// 3. PRÉPARATION DU LIEN WIKIPEDIA
$wikipediaLink = "https://fr.wikipedia.org/wiki/" . 
                 str_replace(' ', '_', $realisateur['prenom'] . '_' . $realisateur['nom']);

$pageTitle = $realisateur['prenom'] . " " . $realisateur['nom'];
include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <!-- COLONNE DE GAUCHE - PHOTO ET INFOS -->
        <div class="col-md-4">
            <?php if (!empty($realisateur['url_image_realisateur'])): ?>
                <img src="<?= htmlspecialchars($realisateur['url_image_realisateur']) ?>" 
                     class="img-fluid rounded mb-4 shadow"
                     alt="Photo de <?= htmlspecialchars($realisateur['prenom'] . ' ' . $realisateur['nom']) ?>"
                     style="width: 100%; object-fit: contain; max-height: 450px;">
            <?php else: ?>
                <div class="bg-light rounded mb-4 d-flex align-items-center justify-content-center" 
                     style="height: 300px;">
                    <i class="fas fa-user-tie fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Informations</h5>
                </div>
                <div class="card-body">
                    <h4><?= htmlspecialchars($realisateur['prenom']) ?> <?= htmlspecialchars($realisateur['nom']) ?></h4>
                    <hr>
                    <p><strong>Date de naissance :</strong> <?= date('d/m/Y', strtotime($realisateur['date_naissance'])) ?></p>
                    <p><strong>Âge :</strong> <?= date_diff(date_create($realisateur['date_naissance']), date_create('today'))->y ?> ans</p>
                    
                    <a href="<?= $wikipediaLink ?>" target="_blank" class="btn btn-outline-primary w-100 mt-3">
                        <i class="fas fa-external-link-alt"></i> Voir sur Wikipedia
                    </a>
                </div>
            </div>
        </div>
        
        <!-- COLONNE DE DROITE - BIOGRAPHIE ET FILMOGRAPHIE -->
        <div class="col-md-8">
            <!-- SECTION BIOGRAPHIE -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Biographie</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($realisateur['bio'])): ?>
                        <div class="realisateur-bio">
                            <?= nl2br(htmlspecialchars($realisateur['bio'])) ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="fas fa-info-circle"></i> Aucune biographie disponible pour ce réalisateur.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- SECTION FILMOGRAPHIE -->
            <h3 class="mb-4">Filmographie</h3>
            
            <?php if (!empty($films)): ?>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    <?php foreach ($films as $film): ?>
                        <div class="col">
                            <div class="card h-100">
                                <!-- AFFICHE DU FILM - CORRECTION ICI -->
                                <div class="card-img-container" style="height: 400px; overflow: hidden;">
                                    <img src="<?= htmlspecialchars($film['url_image']) ?>" 
                                         class="card-img-top h-100 w-100"
                                         alt="<?= htmlspecialchars($film['titre']) ?>"
                                         style="object-fit: cover; object-position: center;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($film['titre']) ?></h5>
                                    <p class="card-text text-muted"><?= $film['annee'] ?></p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="film.php?id=<?= $film['id'] ?>" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-info-circle"></i> Détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Aucun film trouvé dans notre catalogue pour ce réalisateur.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- STYLE CSS SPÉCIFIQUE -->
<style>
    /* Style pour les affiches de films */
    .card-img-container {
        position: relative;
    }
    
    /* Style pour la biographie */
    .realisateur-bio {
        line-height: 1.6;
        text-align: justify;
        hyphens: auto;
    }
    
    /* Adaptation responsive */
    @media (max-width: 768px) {
        .card-img-container {
            height: 300px !important;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>