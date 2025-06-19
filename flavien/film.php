<?php session_start();
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

// Traitement du formulaire de commentaire
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['user_id'])) {
        $errors[] = "Vous devez être connecté pour poster un commentaire";
    } else {
        $note = (int)($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');
        
        // Validation
        if ($note < 1 || $note > 5) {
            $errors[] = "La note doit être entre 1 et 5 étoiles";
        }
        if (empty($commentaire)) {
            $errors[] = "Le commentaire ne peut pas être vide";
        }
        if (strlen($commentaire) > 500) {
            $errors[] = "Le commentaire est trop long (500 caractères max)";
        }
        
        if (empty($errors)) {
            $insert = $db->prepare("
                INSERT INTO commentaires (id_film, id_utilisateur, note, commentaire)
                VALUES (?, ?, ?, ?)
            ");
            $insert->execute([$filmId, $_SESSION['user_id'], $note, $commentaire]);
            
            // Recharger la page pour afficher le nouveau commentaire
            header("Location: film.php?id=$filmId");
            exit;
        }
    }
}

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
    WHERE s.id_film = ? AND s.horaires > NOW()
    ORDER BY s.horaires
");
$sessionsQuery->execute([$filmId]);
$sessions = $sessionsQuery->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les commentaires
$commentsQuery = $db->prepare("
    SELECT c.*, u.prenom, u.nom 
    FROM commentaires c
    JOIN utilisateur u ON c.id_utilisateur = u.id
    WHERE c.id_film = ?
    ORDER BY c.date_creation DESC
");
$commentsQuery->execute([$filmId]);
$comments = $commentsQuery->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $film['titre'];
include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-4">
        <img src="<?= htmlspecialchars($film['url_image']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($film['titre']) ?>">
    </div>
    <div class="col-md-8">
        <h1><?= htmlspecialchars($film['titre']) ?> <small class="text-muted">(<?= $film['annee'] ?>)</small></h1>
        <p><strong>Réalisateur :</strong> 
            <a href="realisateur.php?id=<?= $film['id'] ?>" class="text-decoration-none">
                <?= htmlspecialchars($film['realisateur_prenom']) ?> <?= htmlspecialchars($film['realisateur_nom']) ?>
            </a>
        </p>
        
        <div class="mt-4">
            <h3>Synopsis</h3>
            <p class="lead"><?= nl2br(htmlspecialchars($film['description'] ?? 'Aucune description disponible')) ?></p>
        </div>
        
        <div class="mt-4">
    <h3>Acteurs principaux</h3>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php foreach ($actors as $actor): ?>
        <div class="col">
            <div class="card h-100 border-0">
                <a href="acteur.php?id=<?= $actor['id'] ?>" class="text-decoration-none text-dark">
                    <?php if (!empty($actor['url_photo_acteur'])): ?>
                        <img src="<?= htmlspecialchars($actor['url_photo_acteur']) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= htmlspecialchars($actor['prenom'] . ' ' . $actor['nom']) ?>"
                             style="width: 100%; height: 180px; object-fit: cover;">
                    <?php else: ?>
                        <div class="actor-photo-placeholder bg-light rounded" style="
                            height: 180px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <i class="fas fa-user fa-3x text-secondary"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body p-2 text-center">
                        <h6 class="card-title mb-0"><?= htmlspecialchars($actor['prenom']) ?></h6>
                        <h6 class="card-title mb-0 fw-bold"><?= htmlspecialchars($actor['nom']) ?></h6>
                    </div>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="mt-5">
    <h3>Séances disponibles</h3>
    <?php if (empty($sessions)): ?>
        <div class="alert alert-info">Aucune séance prévue pour le moment</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
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
                        <td><?= htmlspecialchars($session['salle']) ?></td>
                        <td>
                            <a href="reservation.php?id_seance=<?= $session['id'] ?>" class="btn btn-sm btn-success">
                                Réserver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Section Commentaires -->
<div class="mt-5">
    <h3>Commentaires</h3>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Formulaire de commentaire -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Ajouter un commentaire</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Note (sur 5)</label>
                        <div class="rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?= $i ?>" name="note" value="<?= $i ?>" <?= ($i == 5) ? 'checked' : '' ?>>
                                <label for="star<?= $i ?>">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Votre commentaire</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="submit_comment" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <a href="connexion.php" class="alert-link">Connectez-vous</a> pour laisser un commentaire
        </div>
    <?php endif; ?>
    <!-- Liste des commentaires -->
    <?php if (empty($comments)): ?>
        <div class="alert alert-info">Aucun commentaire pour ce film</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($comments as $comment): ?>
                <div class="list-group-item mb-3">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1"><?= htmlspecialchars($comment['prenom'] . ' ' . htmlspecialchars($comment['nom'])) ?></h5>
                        <small><?= date('d/m/Y H:i', strtotime($comment['date_creation'])) ?></small>
                    </div>
                    <div class="mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $comment['note']): ?>
                                ★
                            <?php else: ?>
                                ☆
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <p class="mb-1"><?= nl2br(htmlspecialchars($comment['commentaire'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating input {
        display: none;
    }
    .rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
    }
    .rating input:checked ~ label {
        color: #ffc107;
    }
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
    .actor-photo-placeholder {
        transition: all 0.3s ease;
    }
    .actor-photo-placeholder:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<?php include 'includes/footer.php'; ?>