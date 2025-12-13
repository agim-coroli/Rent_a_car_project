<?php

use Dotenv\Dotenv;

require_once PATH . "/src/model/Utilities.php";

try {
    $connectPDO = new PDO(
        DB_TYPE . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PWD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage(), 0, $e);
}



$dotenv = Dotenv::createImmutable(PATH);
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);


if (isset($_SESSION['role']) && $_SESSION['role'] === 1) {
    require_once PATH . "/src/controller/adminController.php";
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 0) {
    require_once PATH . "/src/controller/userController.php";
} else {
    require_once PATH . "/src/controller/publicController.php";
}

debugBar();

$connectPDO = null;
