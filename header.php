<?php /* header.php : début de page + menu, inclus dans chaque page */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de salles</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="index.php" class="logo">Réservation de salles</a>
        <div>
            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                <a href="salles.php">Salles</a>
                <a href="mes_reservations.php">Mes réservations</a>
                <span class="bonjour">Bonjour <?= htmlspecialchars($_SESSION['nom']) ?></span>
                <a href="deconnexion.php">Déconnexion</a>
            <?php else: ?>
                <a href="connexion.php">Connexion</a>
                <a href="inscription.php">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
