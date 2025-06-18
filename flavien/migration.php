<?php
require_once 'includes/config.php';

$users = $db->query("SELECT id, password FROM utilisateur")->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    // Si le mot de passe n'est pas déjà hashé
    if (!password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        $update = $db->prepare("UPDATE utilisateur SET password = ? WHERE id = ?");
        $update->execute([$hashedPassword, $user['id']]);
    }
}

echo "Migration terminée";