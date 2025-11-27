<?php

// typage strict
declare(strict_types=1);

// Autoload de composer (pour Twig, mais aussi toutes
// les bibliothèques tierces en PHP)
require_once "../vendor/autoload.php";

// démarrage de la session
session_start();

// inclusion du fichier de configuration si config.php existe
if (file_exists("../config.php")) {
    require_once "../config.php";
    // sinon on prend la configuration originale
} else {

    require_once "../config.dev.php";
}



// Autoload fonctionnel avec les namespaces personnels,
// ne fonctionne qu'en PHP Orienté Objet (fait main, on pourrait
// utiliser Composer pour y ajouter nos dépendances)
// et avec une arborescence de fichiers respectant les namespaces
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require PATH . '/src/' . $class . '.php';


});

// chargement du router
require_once PATH . "../src/controller/routerController.php";
