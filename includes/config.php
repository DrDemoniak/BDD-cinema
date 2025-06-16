<?php
// Connexion PDO
$host   = '10.96.16.82';
$port   = 3306;
$db     = 'cinema';
$user   = 'colin';
$pass   = '';          // à adapter
$charset= 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit("Erreur de connexion : " . $e->getMessage());
}