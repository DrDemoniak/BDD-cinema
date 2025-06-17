<?php
require_once __DIR__ . '/config.php';

function getTousLesFilms() {
    global $pdo;
    return $pdo->query("SELECT * FROM films ORDER BY seance")->fetchAll();
}

function getFilmById(int $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM films WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function creerClient(string $nom, string $email): int {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO clients (nom, email) VALUES (?, ?)");
    $stmt->execute([$nom, $email]);
    return (int)$pdo->lastInsertId();
}

function reserver(int $filmId, int $clientId, int $places): void {
    global $pdo;
    $stmt = $pdo->prepare(
      "INSERT INTO reservations (film_id, client_id, places_reservees)
       VALUES (?, ?, ?)"
    );
    $stmt->execute([$filmId, $clientId, $places]);
    // mettre à jour les places restantes
    $pdo->prepare("UPDATE films SET places_totales = places_totales - ? WHERE id = ?")
        ->execute([$places, $filmId]);
}