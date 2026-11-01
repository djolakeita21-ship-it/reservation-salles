<?php
require 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

// JOIN : on récupère le nom de la salle en même temps que la réservation
$requete = $pdo->prepare(
    'SELECT r.id, r.date_debut, r.date_fin, s.nom AS salle
     FROM reservations r
     JOIN salles s ON s.id = r.salle_id
     WHERE r.utilisateur_id = ?
     ORDER BY r.date_debut'
);
$requete->execute([$_SESSION['utilisateur_id']]);
$reservations = $requete->fetchAll();

require 'header.php';
?>

<h1>Mes réservations</h1>

<?php if (isset($_GET['ok'])): ?>
    <p class="message succes">Réservation enregistrée.</p>
<?php endif; ?>
<?php if (isset($_GET['annulee'])): ?>
    <p class="message succes">Réservation annulée.</p>
<?php endif; ?>

<?php if (empty($reservations)): ?>
    <p>Vous n'avez aucune réservation. <a href="salles.php">Réserver une salle</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>Salle</th><th>Début</th><th>Fin</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['salle']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($r['date_debut'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($r['date_fin'])) ?></td>
                    <td>
                        <form method="post" action="annuler.php"
                              onsubmit="return confirm('Annuler cette réservation ?');">
                            <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                            <button type="submit" class="bouton bouton-danger">Annuler</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require 'footer.php'; ?>
