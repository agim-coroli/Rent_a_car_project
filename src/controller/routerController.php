<?php

use service\UserService;
use model\Exception\ExceptionFr;
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
    $exceptionFr = new ExceptionFr("Erreur de connexion à la base de données : " . $e->getMessage(), 0, $e);
    die($exceptionFr->getMessage());
}

if (isset($_GET['api']) && $_GET['api'] === 'users') {
    $userService = new UserService($connectPDO);

    $method = $_SERVER['REQUEST_METHOD'];

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    $input = json_decode(file_get_contents('php://input'), true) ?? [];

    switch ($method) {
        case 'GET':
            if ($id) {
                $response = $userService->getUserById($id);
            } else {
                $response = $userService->getAllUsers();
            }
            break;

        case 'POST':
            $response = $userService->createUser($input);
            break;

        case 'PUT':
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

    $userService->sendJsonResponse($response);
    exit;
}



$dotenv = Dotenv::createImmutable(PATH);
$dotenv->load();

if (isset($_SESSION['role']) && $_SESSION['role'] === 1) {
    require_once PATH . "/src/controller/adminController.php";
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 0) {
    require_once PATH . "/src/controller/userController.php";
} else {
    require_once PATH . "/src/controller/publicController.php";
}

debugBar();

$connectPDO = null;
