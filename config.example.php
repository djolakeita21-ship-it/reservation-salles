<?php
// config.example.php : modèle de configuration.
// Copier ce fichier en "config.php" puis adapter les valeurs ci-dessous.
// Le vrai config.php n'est PAS envoyé sur GitHub (voir .gitignore).

session_start();

$hote = 'localhost';
$nomBase = 'reservation_salles';
$utilisateur = 'root';        // identifiant MySQL
$motDePasse = '';             // mot de passe MySQL (vide par défaut avec XAMPP)

try {
    $pdo = new PDO(
        "mysql:host=$hote;dbname=$nomBase;charset=utf8mb4",
        $utilisateur,
        $motDePasse,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // affiche les erreurs SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // résultats sous forme de tableaux
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
