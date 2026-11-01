<?php
require 'config.php';

// On vide la session puis on la détruit
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;
