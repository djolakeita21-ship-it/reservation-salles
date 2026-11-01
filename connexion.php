<?php
require 'config.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    // On cherche l'utilisateur par son email (requête préparée)
    $requete = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
    $requete->execute([$email]);
    $utilisateur = $requete->fetch();

    // password_verify compare le mot de passe saisi avec le hash en base
    if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe_hash'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['nom'];
        $_SESSION['role'] = $utilisateur['role'];

        header('Location: salles.php');
        exit;
    }

    // Message volontairement vague : on ne dit pas si c'est l'email ou le mot de passe
    $erreur = 'Email ou mot de passe incorrect.';
}

require 'header.php';
?>

<h1>Connexion</h1>

<?php if (isset($_GET['inscrit'])): ?>
    <p class="message succes">Compte créé, vous pouvez vous connecter.</p>
<?php endif; ?>

<?php if ($erreur !== ''): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="post">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="mot_de_passe">Mot de passe</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>

    <button type="submit" class="bouton">Se connecter</button>
</form>

<?php require 'footer.php'; ?>
