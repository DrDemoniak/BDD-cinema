<?php
require 'includes/fonctions.php';
if (!isset($_GET['id'])) {
  header('Location: index.php'); exit;
}
$seance = getSeanceById((int)$_GET['id']);
if (!$seance) {
  exit('Séance introuvable');
}
include 'templates/header.php';
?>

<article>
  <h2><?= htmlspecialchars($seance['titre']) ?></h2>
  <p>Année : <?= $seance['annee'] ?></p>
  <p>Quand : <?= date('d/m/Y H:i', strtotime($seance['horaires'])) ?></p>
  <p>Salle : <?= htmlspecialchars($seance['salle']) ?></p>

  <h3>Réservation</h3>
  <form action="reserve.php" method="post">
    <input type="hidden" name="seance_id" value="<?= $seance['id'] ?>">
    <label>Nom : <input type="text" name="nom" required></label><br>
    <label>Prénom : <input type="text" name="prenom" required></label><br>
    <label>Email : <input type="email" name="mail" required></label><br>
    <button type="submit">Envoyer</button>
  </form>
</article>

<?php include 'templates/footer.php'; ?>