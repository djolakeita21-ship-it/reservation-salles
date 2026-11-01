<?php
require 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

// L'annulation passe par POST uniquement (jamais par un simple lien)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    // "AND utilisateur_id = ?" : on ne peut supprimer que SES propres réservations
    $requete = $pdo->prepare('DELETE FROM reservations WHERE id = ? AND utilisateur_id = ?');
    $requete->execute([$id, $_SESSION['utilisateur_id']]);
}

header('Location: mes_reservations.php?annulee=1');
exit;
