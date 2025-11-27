<?php

use model\manager\UserManager;
use model\mapping\UserMapping;

$adminUser = new UserManager($connectPDO);

if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            // ######################################### TEMPORAIRE #############################################

            // if (isset($_GET['slug']) && !isset($_GET['id'])) {
            //     // slug = voiture (ex: tesla-model-s)
            //     $car = $carManager->findBySlug($_GET['slug']);
            // } elseif (isset($_GET['slug']) && isset($_GET['id'])) {
            //     // slug = voiture, id = action ou identifiant
            //     if ($_GET['id'] === 'edit') {
            //         $car = $carManager->findBySlug($_GET['slug']);
            //         // afficher formulaire d’édition
            //     } else {
            //         $car = $carManager->findById((int)$_GET['id']);
            //     }
            // } else {
            //     $cars = $carManager->findAll();
            // }
            // require_once PATH . "/src/view/catalogue.php";
            // ######################################### TEMPORAIRE #############################################

            break;

        case 'dashboard':

            $tab = $adminUser->findAll();
            require_once PATH . "/src/view/dashboard.php";
            break;
        case 'delete':

            if (isset($_GET['id'])) {
                $del = $adminUser->delete($_GET['id']);
                if ($del === true) {
                    header('Location:./');
                    exit();
                }
            }
            $tab = $adminUser->findAll();
            require_once PATH . "/src/view/dashboard.php";
            break;

        case 'edit':
            // $findById = $adminUser->findById()
            $tab = $adminUser->findAll();

            if (isset($_POST[''])) {
                # code...
            }
            
            require_once PATH . "/src/view/dashboard.php";
            break;



        case 'update':
            // $findById = $adminUser->findById()
            $tab = $adminUser->findAll();
            require_once PATH . "/src/view/dashboard.php";
            break;


        case 'deconnexion':
            if ($adminUser->disconnect()) {
                header('Location:./');
                exit();
            }
            break;

        default:
            require_once PATH . "/src/view/404.php";
            break;
    }
} else {
    require_once PATH . "/src/view/home.php";
}
