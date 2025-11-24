<?php


# Connexion PDO
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
    die($e->getMessage());
}


// api pour plutard
if (isset($_GET['api'])) {
    // echo json_encode();
}


// si nous sommes connecté en tant qu'admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    // on charge le contrôleur admin
    require_once PATH . "/src/controller/adminController.php";
}else{
    // on charge le contrôleur public
    require_once PATH . "/src/controller/publicController.php";

}


// Débogage
echo '<div class="container"><hr><h3>Barre de débogage</h3><hr>';
echo '<h4>session_id() ou SID</h4>';
var_dump(session_id());
echo '<h4>$_GET</h4>';
var_dump($_GET);
echo '<h4>$_SESSION</h4>';
var_dump($_SESSION);
echo '<h3>$_POST</h3>';
var_dump($_POST);
echo '</div>';



// Bonne pratique
$connectPDO = null;
