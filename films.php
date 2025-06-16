<?php
require 'includes/fonctions.php';
$films = getTousLesFilms();
include 'templates/header.php';
?>

<h2>Films en séance</h2>
<ul>
  <?php foreach ($films as $f): ?>
    <li>
      <a href="seance.php?id=<?= $f['id'] ?>">
        <?= htmlspecialchars($f['titre']) ?>
        (<?= date('d/m H:i', strtotime($f['seance'])) ?>)
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?php include 'templates/footer.php'; ?>