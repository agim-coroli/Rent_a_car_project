<?php
use model\manager\UserManager;
use model\mapping\UserMapping;
// use DateTime;


if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            require_once PATH . "/src/view/catalogue.php";

            break;
        case 'dashboard':
            require_once PATH . "/src/view/dashboard.php";

            break;
        case 'deconnexion':
            $disconnectUser = new UserManager($connectPDO);
            if ($disconnectUser->disconnect()) {
                header('Location:'.RACINE_URL);
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
