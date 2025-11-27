<?php

use service\UserService;
use model\Exception\ExceptionFr;
require_once PATH."/src/model/Utilities.php";
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
    $exceptionFr = new ExceptionFr("Erreur de connexion à la base de données : " . $e->getMessage(), 0, $e);
    die($exceptionFr->getMessage());
}


// API pour la gestion des utilisateurs
if (isset($_GET['api']) && $_GET['api'] === 'users') {
    $userService = new UserService($connectPDO);

    // Récupération de la méthode HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    // Récupération de l'ID depuis l'URL (ex: ?api=users&id=1)
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    // Récupération des données JSON du body
    $input = json_decode(file_get_contents('php://input'), true) ?? [];

    switch ($method) {
        case 'GET':
            if ($id) {
                // GET /?api=users&id=1
                $response = $userService->getUserById($id);
            } else {
                // GET /?api=users
                $response = $userService->getAllUsers();
            }
            break;

        case 'POST':
            // POST /?api=users
            $response = $userService->createUser($input);
            break;

        case 'PUT':
            // PUT /?api=users&id=1
            if (!$id) {
                $response = [
                    'success' => false,
                    'message' => 'ID requis pour la mise à jour',
                    'status_code' => 400
                ];
            } else {
                $response = $userService->updateUser($id, $input);
            }
            break;

        case 'DELETE':
            // DELETE /?api=users&id=1
            if (!$id) {
                $response = [
                    'success' => false,
                    'message' => 'ID requis pour la suppression',
                    'status_code' => 400
                ];
            } else {
                $response = $userService->deleteUser($id);
            }
            break;

        default:
            $response = [
                'success' => false,
                'message' => 'Méthode non autorisée',
                'status_code' => 405
            ];
    }

    // Envoie la réponse JSON et arrête l'exécution
    $userService->sendJsonResponse($response);
    exit;
}


// si nous sommes connecté en tant qu'admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    // on charge le contrôleur admin
    require_once PATH . "/src/controller/adminController.php";
} else {
    // on charge le contrôleur public
    require_once PATH . "/src/controller/publicController.php";
}


// Débogage
debugBar();



// Bonne pratique
$connectPDO = null;
