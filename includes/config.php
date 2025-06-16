<?php
// includes/config.php
$host = '10.96.16.83';
$db   = 'cinema';
$user = 'colin';
$pass = '';           // votre mot de passe MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  exit("Erreur de connexion à la BDD : " . $e->getMessage());
}
