<?php
session_start();
require_once 'includes/config.php';

$pageTitle = "Inscription";
include 'includes/header.php';

// Initialisation des variables
$errors = [];
$formData = [
    'nom' => '',
    'prenom' => '',
    'mail' => '',
];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $formData['nom'] = trim($_POST['nom'] ?? '');
    $formData['prenom'] = trim($_POST['prenom'] ?? '');
    $formData['mail'] = trim($_POST['mail'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($formData['nom'])) {
        $errors['nom'] = 'Le nom est obligatoire';
    }

    if (empty($formData['prenom'])) {
        $errors['prenom'] = 'Le prénom est obligatoire';
    }

    if (empty($formData['mail'])) {
        $errors['mail'] = 'L\'email est obligatoire';
    } elseif (!filter_var($formData['mail'], FILTER_VALIDATE_EMAIL)) {
        $errors['mail'] = 'L\'email n\'est pas valide';
    } else {
        // Vérifier si l'email existe déjà
        $query = $db->prepare("SELECT id FROM utilisateur WHERE mail = ?");
        $query->execute([$formData['mail']]);
        if ($query->fetch()) {
            $errors['mail'] = 'Cet email est déjà utilisé';
        }
    }

    if (empty($password)) {
        $errors['password'] = 'Le mot de passe est obligatoire';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Le mot de passe doit faire au moins 8 caractères';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
    }

    // Si pas d'erreurs, insertion en base
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $insert = $db->prepare("
                INSERT INTO utilisateur (nom, prenom, mail, password)
                VALUES (?, ?, ?, ?)
            ");
            $insert->execute([
                $formData['nom'],
                $formData['prenom'],
                $formData['mail'],
                $hashedPassword
            ]);

            // Connexion automatique après inscription
            $userId = $db->lastInsertId();
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $formData['prenom'] . ' ' . $formData['nom'];
            
            // Redirection vers la page d'accueil
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors['general'] = 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.';
            error_log("Erreur inscription: " . $e->getMessage());
        }
    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Créer un compte</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= $errors['general'] ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                               id="nom" name="nom" value="<?= htmlspecialchars($formData['nom']) ?>" required>
                        <?php if (isset($errors['nom'])): ?>
                            <div class="invalid-feedback"><?= $errors['nom'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control <?= isset($errors['prenom']) ? 'is-invalid' : '' ?>" 
                               id="prenom" name="prenom" value="<?= htmlspecialchars($formData['prenom']) ?>" required>
                        <?php if (isset($errors['prenom'])): ?>
                            <div class="invalid-feedback"><?= $errors['prenom'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mail" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($errors['mail']) ? 'is-invalid' : '' ?>" 
                               id="mail" name="mail" value="<?= htmlspecialchars($formData['mail']) ?>" required>
                        <?php if (isset($errors['mail'])): ?>
                            <div class="invalid-feedback"><?= $errors['mail'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                               id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                        <small class="form-text text-muted">Minimum 8 caractères</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                               id="confirm_password" name="confirm_password" required>
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    Déjà un compte ? <a href="connexion.php">Connectez-vous</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>