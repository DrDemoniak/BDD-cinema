<?php session_start();
require_once 'includes/config.php';

$pageTitle = "Catalogue des films";
include 'includes/header.php';

// Récupérer les genres pour le filtre
$genres = $db->query("SELECT * FROM genres")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les acteurs pour le nouveau filtre
$acteurs = $db->query("SELECT * FROM acteurs ORDER BY nom, prenom")->fetchAll(PDO::FETCH_ASSOC);

// Filtres
$genreFilter = isset($_GET['genre']) ? (int)$_GET['genre'] : null;
$yearFilter = isset($_GET['annee']) ? (int)$_GET['annee'] : null;
$acteursFilter = isset($_GET['acteurs']) ? $_GET['acteurs'] : [];

// Construction de la requête
$sql = "SELECT DISTINCT f.* FROM films f";
$params = [];
$whereConditions = [];

if ($genreFilter) {
    $sql .= " JOIN genres_films gf ON f.id = gf.id_film";
    $whereConditions[] = "gf.id_genre = ?";
    $params[] = $genreFilter;
}

if ($yearFilter) {
    $whereConditions[] = "f.annee = ?";
    $params[] = $yearFilter;
}

// Filtre par acteurs
if (!empty($acteursFilter)) {
    $placeholders = rtrim(str_repeat('?,', count($acteursFilter)), ',');
    $whereConditions[] = "f.id IN (
        SELECT DISTINCT fa.id_film 
        FROM films_acteurs fa 
        WHERE fa.id_acteur IN ($placeholders)
    )";
    $params = array_merge($params, $acteursFilter);
}

// Ajouter WHERE si des conditions existent
if (!empty($whereConditions)) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
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
                    <!-- Filtre par genre -->
                    <div class="mb-3">
                        <label class="form-label">Genre</label>
                        <select name="genre" class="form-select">
                            <option value="">Tous les genres</option>
                            <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= $genreFilter == $genre['id'] ? 'selected' : '' ?>>
                                <?= $genre['intitule'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filtre par année -->
                    <div class="mb-3">
                        <label class="form-label">Année</label>
                        <input type="number" name="annee" class="form-control" value="<?= $yearFilter ?>" placeholder="Toutes les années">
                    </div>
                    
                    <!-- Nouveau filtre par acteurs -->
                    <div class="mb-3">
                        <label class="form-label">Acteurs</label>
                        <div class="actor-filter" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($acteurs as $acteur): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="acteurs[]" 
                                       id="actor-<?= $acteur['id'] ?>" 
                                       value="<?= $acteur['id'] ?>"
                                       <?= in_array($acteur['id'], $acteursFilter) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="actor-<?= $acteur['id'] ?>">
                                    <?= $acteur['prenom'] ?> <?= $acteur['nom'] ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="catalogue.php" class="btn btn-secondary">Réinitialiser</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <?php if (!empty($acteursFilter)): ?>
        <div class="alert alert-info mb-3">
            Filtre actif : 
            <?php 
            $selectedActors = array_map(function($id) use ($acteurs) {
                foreach ($acteurs as $a) if ($a['id'] == $id) return $a['prenom'].' '.$a['nom'];
                return '';
            }, $acteursFilter);
            echo implode(', ', $selectedActors);
            ?>
            <a href="catalogue.php?<?= http_build_query(array_diff_key($_GET, ['acteurs' => ''])) ?>" class="float-end">
                <small>Supprimer</small>
            </a>
        </div>
        <?php endif; ?>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($films as $film): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?= $film['url_image'] ?>" class="card-img-top" alt="<?= $film['titre'] ?>" style="height: 300px; object-fit: cover;">
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