<?php include 'templates/header.php'; ?>
<?php
require 'includes/fonctions.php';
$seances = getToutesLesSeances();
include 'templates/header.php';
?>

<h2>Séances à venir</h2>
<ul>
<?php foreach ($seances as $s): ?>
  <li>
    <a href="seance.php?id=<?= $s['id'] ?>">
      <?= htmlspecialchars($s['titre']) ?>
      – <?= date('d/m/Y H:i', strtotime($s['horaires'])) ?>
      (<?= htmlspecialchars($s['salle']) ?>)
    </a>
  </li>
<?php endforeach; ?>
</ul>

<?php include 'templates/footer.php'; ?>