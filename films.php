<?php
require 'includes/config.php';   // définit $pdo
include 'templates/header.php';
?>

<h2>Liste des films</h2>
<ul>
<?php
// La table `films` contient les colonnes id, titre, annee :contentReference[oaicite:1]{index=1}
$stmt = $pdo->query('SELECT id, titre, annee FROM films ORDER BY titre');
$films = $stmt->fetchAll();
foreach ($films as $film): ?>
  <li>
    <strong><?= htmlspecialchars($film['titre']) ?></strong>
    (<?= htmlspecialchars($film['annee']) ?>)
  </li>
<?php endforeach; ?>
</ul>

<?php include 'templates/footer.php'; ?>