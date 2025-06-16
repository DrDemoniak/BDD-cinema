<?php include 'templates/header.php'; ?>
<?php
require 'includes/fonctions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php'); exit;
}

$seance_id = (int)$_POST['seance_id'];
$nom       = trim($_POST['nom']);
$prenom    = trim($_POST['prenom']);
$mail      = trim($_POST['mail']);

if (!$nom || !$prenom || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
  exit('Données invalides');
}

// création utilisateur + inscription
$user_id = creerUtilisateur($nom, $prenom, $mail);
inscrireSeance($seance_id, $user_id);

// redirection
header('Location: merci.php');
include 'templates/footer.php';
exit;
?>