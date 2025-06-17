<?php
// Connexion à la DB
$host = '10.96.16.82:3306';
$dbname = 'cinema';
$user = 'colin';
$pass = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur DB: " . $e->getMessage());
}

// Constantes
define('SITE_NAME', 'CinéNetflix');
?>