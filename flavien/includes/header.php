<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= $pageTitle ?? 'Accueil' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/cinema/index.php"><?= SITE_NAME ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/cinema/index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cinema/catalogue.php">Films</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cinema/seances.php">Séances</a></li>
                </ul>
                <form class="d-flex" action="/cinema/recherche.php" method="get">
                    <input class="form-control me-2" type="search" name="q" placeholder="Rechercher...">
                    <button class="btn btn-outline-light" type="submit">🔍</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-4">