<?php
/**
 * Fonctions utilitaires pour le site CinéNetflix
 */

/**
 * Récupère les informations d'un film par son ID
 * 
 * @param PDO $db Connexion à la base de données
 * @param int $id ID du film
 * @return array|false Tableau des informations du film ou false si non trouvé
 */
function getFilmById(PDO $db, int $id) {
    $query = $db->prepare("
        SELECT f.*, r.nom AS realisateur_nom, r.prenom AS realisateur_prenom 
        FROM films f
        JOIN films_realisateurs fr ON f.id = fr.id_film
        JOIN realisateurs r ON fr.id_realisateur = r.id
        WHERE f.id = ?
        LIMIT 1
    ");
    $query->execute([$id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

/**
 * Récupère les acteurs d'un film
 * 
 * @param PDO $db Connexion à la base de données
 * @param int $filmId ID du film
 * @return array Liste des acteurs
 */
function getActorsByFilm(PDO $db, int $filmId) {
    $query = $db->prepare("
        SELECT a.* FROM acteurs a
        JOIN films_acteurs fa ON a.id = fa.id_acteur
        WHERE fa.id_film = ?
        ORDER BY a.nom, a.prenom
    ");
    $query->execute([$filmId]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les séances à venir pour un film
 * 
 * @param PDO $db Connexion à la base de données
 * @param int $filmId ID du film
 * @param int $limit Nombre maximum de séances à retourner (0 pour toutes)
 * @return array Liste des séances
 */
function getUpcomingSessionsByFilm(PDO $db, int $filmId, int $limit = 0) {
    $sql = "
        SELECT s.*, l.salle 
        FROM seances s
        JOIN lieux l ON s.id_lieu = l.id
        WHERE s.id_film = ? AND s.horaires > NOW()
        ORDER BY s.horaires
    ";
    
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    $query = $db->prepare($sql);
    $query->execute([$filmId]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère les films récents
 * 
 * @param PDO $db Connexion à la base de données
 * @param int $limit Nombre de films à retourner
 * @return array Liste des films
 */
function getRecentFilms(PDO $db, int $limit = 5) {
    $query = $db->prepare("
        SELECT * FROM films 
        ORDER BY annee DESC 
        LIMIT ?
    ");
    $query->execute([$limit]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Formate une date pour l'affichage
 * 
 * @param string $date Date au format MySQL
 * @param string $format Format de sortie (par défaut 'd/m/Y H:i')
 * @return string Date formatée
 */
function formatDate(string $date, string $format = 'd/m/Y H:i'): string {
    return date($format, strtotime($date));
}

/**
 * Effectue une réservation
 * 
 * @param PDO $db Connexion à la base de données
 * @param int $sessionId ID de la séance
 * @param int $userId ID de l'utilisateur
 * @param int $places Nombre de places
 * @return bool True si succès, false si échec
 */
function makeReservation(PDO $db, int $sessionId, int $userId, int $places = 1): bool {
    try {
        $db->beginTransaction();
        
        $insert = $db->prepare("
            INSERT INTO seances_utilisateurs (id_seance, id_utilisateur) 
            VALUES (?, ?)
        ");
        
        for ($i = 0; $i < $places; $i++) {
            $insert->execute([$sessionId, $userId]);
        }
        
        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack();
        error_log("Erreur de réservation: " . $e->getMessage());
        return false;
    }
}

/**
 * Vérifie la disponibilité d'une séance
 * 
 * @param PDO $db Connexion à la base de données
 * @param int $sessionId ID de la séance
 * @return array|false Données de la séance si disponible, false sinon
 */
function checkSessionAvailability(PDO $db, int $sessionId) {
    $query = $db->prepare("
        SELECT s.*, f.titre, l.salle,
               (SELECT COUNT(*) FROM seances_utilisateurs WHERE id_seance = s.id) AS reservations,
               (SELECT COUNT(*) FROM lieux WHERE id = s.id_lieu) AS capacite
        FROM seances s
        JOIN films f ON s.id_film = f.id
        JOIN lieux l ON s.id_lieu = l.id
        WHERE s.id = ? AND s.horaires > NOW()
    ");
    $query->execute([$sessionId]);
    return $query->fetch(PDO::FETCH_ASSOC);
}