<?php
session_start();
require 'includes/config.php';   // définit $pdo
include 'templates/header.php';
?>

<h2>Mon profil</h2>

<?php if (!isset($_SESSION['user_id'])): ?>

  <!-- Formulaire de connexion par email -->
  <form method="post">
    <label>
      Votre email :
      <input type="email" name="email" required>
    </label>
    <button type="submit">Se connecter</button>
  </form>

  <?php
  // Traitement du POST pour identifier l'utilisateur
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    // table `utilisateur` avec colonnes id, mail, nom, prenom :contentReference[oaicite:2]{index=2}
    $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE mail = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
      $_SESSION['user_id'] = $user['id'];
      header('Location: mon_profil.php');
      exit;
    } else {
      echo '<p style="color:red;">Aucun utilisateur trouvé avec cet email.</p>';
    }
  }
  ?>

<?php else: ?>

  <?php
  // Récupérer les infos utilisateur
  $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE id = ?');
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch();

  echo '<p>Bienvenue <strong>'
       . htmlspecialchars($user['prenom']) . ' '
       . htmlspecialchars($user['nom'])
       . '</strong> (' . htmlspecialchars($user['mail']) . ')</p>';

  // Récupérer les réservations : joint seances_utilisateurs → seances → films → lieux
  // seances_utilisateurs(id_seance, id_utilisateur) :contentReference[oaicite:3]{index=3}
  $sql = "
    SELECT s.horaires, f.titre, l.salle
    FROM seances_utilisateurs su
    JOIN seances s  ON su.id_seance   = s.id
    JOIN films f    ON s.id_film      = f.id
    JOIN lieux l    ON s.id_lieu      = l.id
    WHERE su.id_utilisateur = ?
    ORDER BY s.horaires
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$_SESSION['user_id']]);
  $reservations = $stmt->fetchAll();
  ?>

  <h3>Mes réservations</h3>
  <?php if (count($reservations) > 0): ?>
    <ul>
      <?php foreach ($reservations as $res): ?>
        <li>
          <?= date('d/m/Y H:i', strtotime($res['horaires'])) ?>
          — <em><?= htmlspecialchars($res['titre']) ?></em>
          (Salle <?= htmlspecialchars($res['salle']) ?>)
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Vous n’avez pas encore réservé de séance.</p>
  <?php endif; ?>

  <!-- Déconnexion simple -->
  <form method="post" action="logout.php">
    <button>Se déconnecter</button>
  </form>

<?php endif; ?>

<?php include 'templates/footer.php'; ?>