<?php
require 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

// 1. On récupère la salle demandée (l'id vient de l'URL ou du formulaire)
$salleId = (int) ($_GET['salle_id'] ?? $_POST['salle_id'] ?? 0);
$requete = $pdo->prepare('SELECT * FROM salles WHERE id = ?');
$requete->execute([$salleId]);
$salle = $requete->fetch();

if (!$salle) {
    header('Location: salles.php');
    exit;
}

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Le champ datetime-local envoie un format du type 2026-11-05T14:30
    $debut = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['date_debut'] ?? '');
    $fin = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['date_fin'] ?? '');

    // 2. Validation des dates
    if (!$debut || !$fin) {
        $erreurs[] = 'Les dates sont invalides.';
    } else {
        if ($fin <= $debut) {
            $erreurs[] = "L'heure de fin doit être après l'heure de début.";
        }
        if ($debut < new DateTime()) {
            $erreurs[] = 'Impossible de réserver dans le passé.';
        }
    }

    // 3. La salle est-elle déjà prise sur ce créneau ?
    // Deux créneaux se chevauchent si : début existant < fin demandée ET fin existante > début demandé
    if (empty($erreurs)) {
        $debutSql = $debut->format('Y-m-d H:i:s');
        $finSql = $fin->format('Y-m-d H:i:s');

        $requete = $pdo->prepare(
            'SELECT COUNT(*) FROM reservations
             WHERE salle_id = ? AND date_debut < ? AND date_fin > ?'
        );
        $requete->execute([$salleId, $finSql, $debutSql]);

        if ($requete->fetchColumn() > 0) {
            $erreurs[] = 'Cette salle est déjà réservée sur ce créneau.';
        }
    }

    // 4. Tout est bon : on enregistre la réservation
    if (empty($erreurs)) {
        $requete = $pdo->prepare(
            'INSERT INTO reservations (utilisateur_id, salle_id, date_debut, date_fin)
             VALUES (?, ?, ?, ?)'
        );
        $requete->execute([$_SESSION['utilisateur_id'], $salleId, $debutSql, $finSql]);

        header('Location: mes_reservations.php?ok=1');
        exit;
    }
}

require 'header.php';
?>

<h1>Réserver : <?= htmlspecialchars($salle['nom']) ?></h1>
<p>Capacité : <?= (int) $salle['capacite'] ?> personnes</p>

<?php foreach ($erreurs as $erreur): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endforeach; ?>

<form method="post">
    <input type="hidden" name="salle_id" value="<?= (int) $salle['id'] ?>">

    <label for="date_debut">Début</label>
    <input type="datetime-local" id="date_debut" name="date_debut"
           value="<?= htmlspecialchars($_POST['date_debut'] ?? '') ?>" required>

    <label for="date_fin">Fin</label>
    <input type="datetime-local" id="date_fin" name="date_fin"
           value="<?= htmlspecialchars($_POST['date_fin'] ?? '') ?>" required>

    <button type="submit" class="bouton">Confirmer la réservation</button>
</form>

<?php require 'footer.php'; ?>
