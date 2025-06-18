<?php
session_start();
require_once 'includes/config.php';

$pageTitle = "Connexion";
include 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query = $db->prepare("SELECT * FROM utilisateur WHERE mail = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

    // Dans la partie vérification des identifiants
    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
    // ...
}
}
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Connexion</h2>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= $_POST['email'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <a href="inscription.php">Créer un compte</a> | 
                    <a href="motdepasse_oublie.php">Mot de passe oublié ?</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>