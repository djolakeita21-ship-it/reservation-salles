<?php
require 'config.php';

// Page réservée aux utilisateurs connectés
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$salles = $pdo->query('SELECT * FROM salles ORDER BY nom')->fetchAll();

require 'header.php';
?>

<h1>Salles disponibles</h1>

<?php if (empty($salles)): ?>
    <p>Aucune salle pour le moment.</p>
<?php else: ?>
    <div class="liste-salles">
        <?php foreach ($salles as $salle): ?>
            <div class="carte">
                <h2><?= htmlspecialchars($salle['nom']) ?></h2>
                <p><?= (int) $salle['capacite'] ?> personnes</p>
                <a class="bouton" href="reserver.php?salle_id=<?= (int) $salle['id'] ?>">Réserver</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require 'footer.php'; ?>
