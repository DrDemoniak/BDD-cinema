<?php session_start(); // Ajoutez cette ligne en tout premier
require_once 'includes/config.php';

if (!isset($_GET['id_seance'])) {
    header('Location: seances.php');
    exit;
}

$seanceId = (int)$_GET['id_seance'];

// Vérifier si la séance existe
$query = $db->prepare("
    SELECT s.*, f.titre, l.salle 
    FROM seances s
    JOIN films f ON s.id_film = f.id
    JOIN lieux l ON s.id_lieu = l.id
    WHERE s.id = ? AND s.horaires > NOW()
");
$query->execute([$seanceId]);
$seance = $query->fetch(PDO::FETCH_ASSOC);

if (!$seance) {
    header('Location: seances.php');
    exit;
}

$pageTitle = "Réservation - " . $seance['titre'];
include 'includes/header.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
    
    $userId = $_SESSION['user_id'];
    
    try {
        $db->beginTransaction();
        
        // Insérer la réservation
        $insert = $db->prepare("
            INSERT INTO seances_utilisateurs (id_seance, id_utilisateur)
            VALUES (?, ?)
        ");
        $insert->execute([$seanceId, $userId]);
        
        $db->commit();
        header('Location: confirmation.php?id=' . $seanceId);
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Erreur lors de la réservation : " . $e->getMessage();
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h2>Réservation</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <h3><?= $seance['titre'] ?></h3>
                <p>
                    <strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($seance['horaires'])) ?><br>
                    <strong>Salle :</strong> <?= $seance['salle'] ?>
                </p>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="places" class="form-label">Nombre de places</label>
                        <select class="form-select" id="places" name="places">
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                    <a href="film.php?id=<?= $seance['id_film'] ?>" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>