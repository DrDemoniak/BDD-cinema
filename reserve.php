<?php
require 'includes/fonctions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') header('Location: films.php');

$filmId = (int)$_POST['film_id'];
$nom    = trim($_POST['nom']);
$email  = trim($_POST['email']);
$places = (int)$_POST['places'];

// validations simples
if (!$nom || !filter_var($email, FILTER_VALIDATE_EMAIL) || $places < 1) {
    exit('Données invalides');
}

$clientId = creerClient($nom, $email);
reserver($filmId, $clientId, $places);

header('Location: merci.php');
exit;