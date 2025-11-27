<?php
// require grace a lautoload
use model\manager\UserManager;
use model\mapping\UserMapping;

// instancie lobjet Manager pour les action utilisateurs
$manageUser = new UserManager($connectPDO);



if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            require_once PATH ."/src/view/catalogue.php";

            break;
        case 'connexion':
            // si le formulaire nest pas vide
            if (!empty($_POST['email']) && !empty($_POST['password'])) {

                // si connect est respecté l'utilisateur est rediriger vers la racine et la session devien admin
                if ($manageUser->connect($_POST)) {
                    header("Location:./");
                    exit();
                }
            }

            require_once PATH . "/src/view/connexion.php";
            break;


        case 'inscription':
            // si le formulaire nest pas vide
            if (isset($_POST['full_name'], $_POST['pseudo'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['date_birth'], $_POST['gender'])) {
                // prepare les données de l'utilisateur
                $newUser = new UserMapping($_POST);

                // si tout est bon, on inscrit dans la bdd et on redirige
                if ($manageUser->create($newUser)) {
                    header("Location:./");
                    exit();
                }
            }

            require_once PATH . "/src/view/inscription.php";
            break;

        default:
            require_once PATH . "/src/view/404.php";

            break;
    }
} else {
    require_once PATH . "/src/view/home.php";
    // require_once PATH . "/src/test/testEntity.php";
    // require_once PATH . "/src/test/testManager.php";
}
