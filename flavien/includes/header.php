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
            <a class="navbar-brand" href="/cinema/flavien/index.php"><?= SITE_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/cinema/flavien/index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cinema/flavien/catalogue.php">Films</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cinema/flavien/seances.php">Séances</a></li>
                </ul>
                
                <form class="d-flex me-2" action="/cinema/flavien/recherche.php" method="get">
                    <input class="form-control me-2" type="search" name="q" placeholder="Rechercher...">
                    <button class="btn btn-outline-light" type="submit">🔍</button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?= htmlspecialchars($_SESSION['user_name'] ?? 'Mon compte') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/cinema/flavien/compte.php">Mon compte</a></li>
                                <li><a class="dropdown-item" href="/cinema/flavien/reservations.php">Mes réservations</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/cinema/flavien/deconnexion.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinema/flavien/connexion.php">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cinema/flavien/inscription.php">Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">