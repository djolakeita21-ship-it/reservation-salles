<?php
require 'config.php';
require 'header.php';
?>

<h1>Réservation de salles</h1>
<p>Réservez facilement une salle pour vos réunions.</p>

<?php if (!isset($_SESSION['utilisateur_id'])): ?>
    <p>
        <a class="bouton" href="inscription.php">Créer un compte</a>
        <a class="bouton bouton-clair" href="connexion.php">Se connecter</a>
    </p>
<?php else: ?>
    <p><a class="bouton" href="salles.php">Voir les salles</a></p>
<?php endif; ?>

<?php require 'footer.php'; ?>
