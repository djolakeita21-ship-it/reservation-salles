<?php
require 'config.php';

$erreurs = [];

// Ce bloc s'exécute uniquement quand le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';

    // 1. Validation des champs
    if ($nom === '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email n'est pas valide.";
    }
    if (strlen($motDePasse) < 8) {
        $erreurs[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }

    // 2. Vérifier que l'email n'est pas déjà utilisé (requête préparée)
    if (empty($erreurs)) {
        $requete = $pdo->prepare('SELECT id FROM utilisateurs WHERE email = ?');
        $requete->execute([$email]);
        if ($requete->fetch()) {
            $erreurs[] = 'Cet email est déjà utilisé.';
        }
    }

    // 3. Créer le compte avec un mot de passe haché
    if (empty($erreurs)) {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $requete = $pdo->prepare(
            'INSERT INTO utilisateurs (nom, email, mot_de_passe_hash) VALUES (?, ?, ?)'
        );
        $requete->execute([$nom, $email, $hash]);

        header('Location: connexion.php?inscrit=1');
        exit;
    }
}

require 'header.php';
?>

<h1>Inscription</h1>

<?php foreach ($erreurs as $erreur): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endforeach; ?>

<form method="post">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

    <label for="mot_de_passe">Mot de passe (8 caractères minimum)</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required>

    <button type="submit" class="bouton">Créer mon compte</button>
</form>

<?php require 'footer.php'; ?>
