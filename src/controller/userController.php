<?php

use model\manager\UserManager;
use model\mapping\UserMapping;

use model\manager\CatalogueManager;
use model\mapping\CatalogueMapping;

use model\manager\AgendaManager;
use model\mapping\AgendaMapping;

use model\Exception\ExceptionFr;

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== 0) {
    header("Location:./");
    exit();
}

$adminUser = new UserManager($connectPDO);
$manageCatalogue = new CatalogueManager($connectPDO);
$manageAgenda = new AgendaManager($connectPDO);

if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            if (isset($_GET['reservation'], $_GET['slug']) && !empty($_GET['slug'])) {
                // Page réservation
                $vehiculeToReserve = $manageCatalogue->findBySlug($_GET['slug']);
                $datesAndHours = $manageAgenda->getAllDatesAndHours();
                
                // var_dump($datesAndHours);
                require_once PATH . "/src/view/users/reservations_vehicule.php";
            } elseif (isset($_GET['slug']) && !empty($_GET['slug'])) {
                // Page détail
                $vehiculeDetails = $manageCatalogue->findBySlug($_GET['slug']);
                require_once PATH . "/src/view/users/catalogue_detail.php";
            } else {
                // Liste complète
                $allVehicule = $manageCatalogue->findAll();
                require_once PATH . "/src/view/guests/catalogue.php";
            }
            break;

        case 'dashboard':
            if (isset($_GET['modify']) && $_GET['modify'] === "account_modify") {
                $userToUpdate = $adminUser->findById($_SESSION['id']);

                if (!empty($_POST)) {

                    if (
                        empty($_POST['full_name']) ||
                        empty($_POST['pseudo']) ||
                        empty($_POST['email']) ||
                        empty($_POST['phone']) ||
                        empty($_POST['password']) ||
                        empty($_POST['date_birth']) ||
                        empty($_POST['gender'])
                    ) {
                        echo "<p style='color:red;'>❌ Tous les champs sont obligatoires.</p>";
                    } else {
                        try {
                            $updateUser = new UserMapping($_POST);
                            $updateUser->setId($userToUpdate->getId());

                            if ($adminUser->updateProfile($updateUser)) {
                                header('Location:?pg=dashboard');
                                exit();
                            } else {
                                echo "<p style='color:red;'>❌ Mise à jour du profil échouée.</p>";
                            }
                        } catch (\Throwable $e) {
                            echo "<p style='color:red;'>❌ Erreur lors de la mise à jour : " . $e->getMessage() . "</p>";
                        }
                    }
                }

                require_once PATH . "/src/view/account_modify.php";
            } elseif (isset($_GET['modify']) && $_GET['modify'] === "account_delete") {

                $userToDelete = $adminUser->findById($_SESSION['id']);

                if (isset($_POST['delete_confirm']) && $_POST['delete_confirm'] === "1") {
                    $adminUser->deleteAccount($userToDelete);
                    unset($_SESSION['role']);
                    unset($_SESSION['id']);
                    header('Location:./');
                    exit();
                }


                require_once PATH . "/src/view/account_delete.php";
            } else {
                require_once PATH . "/src/view/users/dashboard.php";
            }
            break;


        case 'edit':
            require_once PATH . "/src/view/users/dashboard.php";
            break;

        case '':
            require_once PATH . "/src/view/users/dashboard.php";
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
