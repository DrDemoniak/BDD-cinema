<?php
// includes/fonctions.php
require_once __DIR__ . '/config.php';

/**
 * Retourne toutes les séances à venir avec titre de film et salle
 */
function getToutesLesSeances() {
  global $pdo;
  $sql = "
    SELECT s.id, f.titre, s.horaires, l.salle
    FROM seances s
    JOIN films f      ON s.id_film = f.id
    JOIN lieux l      ON s.id_lieu = l.id
    WHERE s.horaires >= NOW()
    ORDER BY s.horaires
  ";
  return $pdo->query($sql)->fetchAll();
}

/**
 * Détails d’une séance
 */
function getSeanceById($id) {
  global $pdo;
  $sql = "
    SELECT s.id, f.titre, f.annee, s.horaires, l.salle
    FROM seances s
    JOIN films f ON s.id_film = f.id
    JOIN lieux l ON s.id_lieu = l.id
    WHERE s.id = ?
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
  return $stmt->fetch();
}

/**
 * Enregistre un utilisateur et renvoie son ID
 */
function creerUtilisateur($nom, $prenom, $mail) {
  global $pdo;
  $stmt = $pdo->prepare("
    INSERT INTO utilisateur (nom, prenom, mail)
    VALUES (?, ?, ?)
  ");
  $stmt->execute([$nom, $prenom, $mail]);
  return $pdo->lastInsertId();
}

/**
 * Inscrit l’utilisateur à la séance
 */
function inscrireSeance($seance_id, $utilisateur_id) {
  global $pdo;
  $stmt = $pdo->prepare("
    INSERT INTO seances_utilisateurs (id_seance, id_utilisateur)
    VALUES (?, ?)
  ");
  $stmt->execute([$seance_id, $utilisateur_id]);
}