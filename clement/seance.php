<?php
require 'includes/fonctions.php';
if (!isset($_GET['id'])) header('Location: films.php');
$film = getFilmById((int)$_GET['id']);
if (!$film) exit('Film introuvable');
include 'templates/header.php';
?>

<article>
  <h2><?= htmlspecialchars($film['titre']) ?></h2>
  <p>Genre : <?= htmlspecialchars($film['genre']) ?></p>
  <p>Auteur : <?= htmlspecialchars($film['auteur']) ?></p>
  <p>Séance : <?= date('d/m/Y H:i', strtotime($film['seance'])) ?></p>
  <p>Places restantes : <?= $film['places_totales'] ?></p>

  <h3>Réserver</h3>
  <form action="reserve.php" method="post">
    <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
    <label>Nom : <input type="text" name="nom" required></label><br>
    <label>Email : <input type="email" name="email" required></label><br>
    <label>Nombre de places :
      <input type="number" name="places" min="1" max="<?= $film['places_totales'] ?>" required>
    </label><br>
    <button type="submit">Confirmer</button>
  </form>
</article>

<?php include 'templates/footer.php'; ?>