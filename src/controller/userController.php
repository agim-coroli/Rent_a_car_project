<?php

use model\manager\UserManager;
use model\mapping\UserMapping;
use model\Exception\ExceptionFr;

$adminUser = new UserManager($connectPDO);

if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            require_once PATH . "/src/view/guests/catalogue.php";
            break;

        case 'dashboard':
            $tab = $adminUser->findAll();
            require_once PATH . "/src/view/users/dashboard.php";
            break;


        case 'edit':


            require_once PATH . "/src/view/users/dashboard.php";
            break;



        case 'update':




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
    require_once PATH . "/src/view/guests/home.php";
}
