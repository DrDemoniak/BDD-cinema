<?php session_start(); // Ajoutez cette ligne en tout premier
require_once 'includes/config.php';

$pageTitle = "Catalogue des films";
include 'includes/header.php';

// Récupérer les genres pour le filtre
$genres = $db->query("SELECT * FROM genres")->fetchAll(PDO::FETCH_ASSOC);

// Filtres
$genreFilter = isset($_GET['genre']) ? (int)$_GET['genre'] : null;
$yearFilter = isset($_GET['annee']) ? (int)$_GET['annee'] : null;

// Construction de la requête
$sql = "SELECT f.* FROM films f";
$params = [];

if ($genreFilter) {
    $sql .= " JOIN genres_films gf ON f.id = gf.id_film WHERE gf.id_genre = ?";
    $params[] = $genreFilter;
}

if ($yearFilter) {
    $sql .= $genreFilter ? " AND" : " WHERE";
    $sql .= " f.annee = ?";
    $params[] = $yearFilter;
}

$sql .= " ORDER BY f.titre";

$query = $db->prepare($sql);
$query->execute($params);
$films = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">Filtres</div>
            <div class="card-body">
                <form method="get">
                    <div class="mb-3">
                        <label class="form-label">Genre</label>
                        <select name="genre" class="form-select">
                            <option value="">Tous</option>
                            <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= $genreFilter == $genre['id'] ? 'selected' : '' ?>>
                                <?= $genre['intitule'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Année</label>
                        <input type="number" name="annee" class="form-control" value="<?= $yearFilter ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="catalogue.php" class="btn btn-secondary">Réinitialiser</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php foreach ($films as $film): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="<?= $film['url_image'] ?>" class="img-fluid" alt="<?= $film['titre'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $film['titre'] ?></h5>
                        <p class="card-text"><?= $film['annee'] ?></p>
                        <a href="film.php?id=<?= $film['id'] ?>" class="btn btn-primary">Détails</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>