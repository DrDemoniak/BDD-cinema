<?php
session_start();
require_once 'includes/config.php';

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$pageTitle = "Mon compte";
include 'includes/header.php';

// Récupérer les infos de l'utilisateur
$query = $db->prepare("SELECT * FROM utilisateur WHERE id = ?");
$query->execute([$_SESSION['user_id']]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas (peut arriver si supprimé en base mais session active)
if (!$user) {
    session_destroy();
    header('Location: connexion.php');
    exit;
}

// Initialisation des variables
$errors = [];
$success = false;

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $mail = trim($_POST['mail'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation des données
    if (empty($nom)) {
        $errors['nom'] = 'Le nom est obligatoire';
    }

    if (empty($prenom)) {
        $errors['prenom'] = 'Le prénom est obligatoire';
    }

    if (empty($mail)) {
        $errors['mail'] = 'L\'email est obligatoire';
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors['mail'] = 'L\'email n\'est pas valide';
    } else {
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $query = $db->prepare("SELECT id FROM utilisateur WHERE mail = ? AND id != ?");
        $query->execute([$mail, $_SESSION['user_id']]);
        if ($query->fetch()) {
            $errors['mail'] = 'Cet email est déjà utilisé par un autre compte';
        }
    }

    // Vérification du mot de passe actuel si changement demandé
    $passwordChanged = false;
    if (!empty($newPassword)) {
        if (empty($currentPassword)) {
            $errors['current_password'] = 'Veuillez entrer votre mot de passe actuel';
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Mot de passe actuel incorrect';
        } elseif (strlen($newPassword) < 8) {
            $errors['new_password'] = 'Le nouveau mot de passe doit faire au moins 8 caractères';
        } elseif ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Les nouveaux mots de passe ne correspondent pas';
        } else {
            $passwordChanged = true;
        }
    }

    // Si pas d'erreurs, mise à jour
    if (empty($errors)) {
        try {
            if ($passwordChanged) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = $db->prepare("
                    UPDATE utilisateur 
                    SET nom = ?, prenom = ?, mail = ?, password = ?
                    WHERE id = ?
                ");
                $update->execute([$nom, $prenom, $mail, $hashedPassword, $_SESSION['user_id']]);
            } else {
                $update = $db->prepare("
                    UPDATE utilisateur 
                    SET nom = ?, prenom = ?, mail = ?
                    WHERE id = ?
                ");
                $update->execute([$nom, $prenom, $mail, $_SESSION['user_id']]);
            }

            // Mise à jour de la session
            $_SESSION['user_name'] = $prenom . ' ' . $nom;
            $success = true;
            
            // Recharger les données utilisateur
            $query = $db->prepare("SELECT * FROM utilisateur WHERE id = ?");
            $query->execute([$_SESSION['user_id']]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errors['general'] = 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.';
            error_log("Erreur mise à jour profil: " . $e->getMessage());
        }
    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Mon compte</h2>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success">Vos informations ont été mises à jour avec succès.</div>
                <?php endif; ?>
                
                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                                       id="nom" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required>
                                <?php if (isset($errors['nom'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['nom']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control <?= isset($errors['prenom']) ? 'is-invalid' : '' ?>" 
                                       id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required>
                                <?php if (isset($errors['prenom'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['prenom']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mail" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($errors['mail']) ? 'is-invalid' : '' ?>" 
                               id="mail" name="mail" value="<?= htmlspecialchars($user['mail'] ?? '') ?>" required>
                        <?php if (isset($errors['mail'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['mail']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <hr>
                    <h5>Changer le mot de passe</h5>
                    <p class="text-muted">Remplissez uniquement si vous souhaitez modifier votre mot de passe</p>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" 
                               id="current_password" name="current_password">
                        <?php if (isset($errors['current_password'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['current_password']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" 
                               id="new_password" name="new_password">
                        <?php if (isset($errors['new_password'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['new_password']) ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Minimum 8 caractères</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                               id="confirm_password" name="confirm_password">
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block">Mettre à jour</button>
                    </div>
                </form>
                
                <hr>
                
                <h5 class="mt-4">Mes réservations</h5>
                <div class="text-center">
                    <a href="reservations.php" class="btn btn-outline-primary">
                        Voir mes réservations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>