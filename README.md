# Réservation de salles

Application web de réservation de salles, réalisée dans le cadre du BTS SIO option SLAM.
Un utilisateur crée un compte, consulte les salles, réserve un créneau et peut l'annuler.
Une salle ne peut jamais être réservée deux fois sur le même créneau.

## Fonctionnalités

- Inscription et connexion (mots de passe hachés)
- Liste des salles avec leur capacité
- Réservation d'une salle avec contrôle des chevauchements de créneaux
- Consultation et annulation de ses propres réservations
- Pages protégées par session

## Technologies

- PHP 8 (accès à la base avec PDO)
- MySQL
- HTML et CSS
- XAMPP (Apache + MySQL) et VS Code
- Git et GitHub

## Base de données

Trois tables :

| Table | Rôle |
|---|---|
| `utilisateurs` | comptes, mot de passe haché, rôle |
| `salles` | nom et capacité |
| `reservations` | lie un utilisateur à une salle, avec date de début et de fin |

Le script de création est dans `creation_tables.sql`.

## Sécurité

- Requêtes préparées (protection contre l'injection SQL)
- `password_hash()` et `password_verify()` pour les mots de passe
- `htmlspecialchars()` sur les données affichées (protection contre le XSS)
- Annulation limitée aux réservations de l'utilisateur connecté

## Installation

1. Installer [XAMPP](https://www.apachefriends.org) et démarrer Apache et MySQL.
2. Copier ce dossier dans `C:\xampp\htdocs\reservation-salles`.
3. Ouvrir `http://localhost/phpmyadmin`, créer une base `reservation_salles` (encodage `utf8mb4_general_ci`).
4. Dans l'onglet SQL de cette base, exécuter le contenu de `creation_tables.sql`.
5. Vérifier les identifiants dans `config.php` (par défaut `root`, sans mot de passe).
6. Ouvrir `http://localhost/reservation-salles/`.

## Captures d'écran

[Ajouter ici une capture de la page des salles et une de « Mes réservations »]

## Pistes d'amélioration

- Interface d'administration pour gérer les salles
- Tests automatisés
- Utilisation d'un framework (Laravel)

## Auteur

Djola Keita, BTS SIO SLAM
