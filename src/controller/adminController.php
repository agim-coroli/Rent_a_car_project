<?php

use model\manager\UserManager;
use model\manager\CatalogueManager;
use model\manager\AgendaManager;

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== 1) {
    header("Location:./");
    exit();
}


$adminUser = new UserManager($connectPDO);
$manageCatalogue = new CatalogueManager($connectPDO);

if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            if (isset($_GET['reservation'], $_GET['slug']) && !empty($_GET['slug'])) {
                // Page réservation
                $vehiculeToReserve = $manageCatalogue->findBySlug($_GET['slug']);
                $datesAndHours = $manageAgenda->getAllDatesAndHours();

                require_once PATH . "/src/view/admins/reservations_vehicule.php";
            } elseif (isset($_GET['slug']) && !empty($_GET['slug'])) {
                // Page détail
                $vehiculeDetails = $manageCatalogue->findBySlug($_GET['slug']);
                require_once PATH . "/src/view/admins/catalogue_detail.php";
            } else {
                // Liste complète
                $allVehicule = $manageCatalogue->findAll();
                require_once PATH . "/src/view/admins/catalogue.php";
            }
            break;


        case 'dashboard':
            $tab = $adminUser->findAll();
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
                    } else {
                        if ($adminUser->updateProfile($_POST, $_SESSION['id'])) {
                            header('Location:?pg=dashboard');
                            exit();
                        }
                    }
                }

                require_once PATH . "/src/view/admins/account_modify.php";
            } elseif (isset($_GET['modify']) && $_GET['modify'] === "account_delete") {

                $userToDelete = $adminUser->findById($_SESSION['id']);

                if (isset($_POST['delete_confirm']) && $_POST['delete_confirm'] === "1") {
                    $adminUser->deleteAccount($userToDelete);
                    unset($_SESSION['role']);
                    unset($_SESSION['id']);
                    header('Location:./');
                    exit();
                }


                require_once PATH . "/src/view/admins/account_delete.php";
            } elseif (isset($_GET['modify']) && $_GET['modify'] === "manage_users") {
                require_once PATH . "/src/view/admins/manage_users.php";
            } else {
                require_once PATH . "/src/view/admins/dashboard.php";
            }
            break;

        case 'generate-user':
            $firstNames = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Luc', 'Emma', 'Thomas', 'Julie', 'Antoine', 'Camille', 'Nicolas', 'Laura', 'David', 'Sarah', 'Alexandre', 'Claire'];
            $lastNames = ['Dupont', 'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Richard', 'Petit', 'Durand', 'Leroy', 'Moreau', 'Simon', 'Laurent', 'Lefebvre', 'Michel', 'Garcia'];

            $genders = ['Masculin', 'Feminin'];
            $domains = ['gmail.com', 'yahoo.fr', 'hotmail.com', 'outlook.fr', 'example.com'];

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $pseudo = strtolower($firstName . $lastName . rand(100, 999));
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@' . $domains[array_rand($domains)]);
            $phone = '+' . rand(32, 39) . rand(100000000, 999999999);
            $password = 'password' . rand(1000, 9999);
            $gender = $genders[array_rand($genders)];

            $year = date('Y') - rand(18, 80);
            $month = rand(1, 12);
            $day = rand(1, 28);
            $dateBirth = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $userData = [
                'full_name' => $fullName,
                'pseudo' => $pseudo,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'date_birth' => $dateBirth,
                'gender' => $gender
            ];

            $newUser = $adminUser->create($userData);

            if ($newUser) {
                header('Location: ?pg=dashboard&modify=manage_users');
                exit();
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                $del = $adminUser->delete($_GET['id']);
                if ($del === true) {
                    header('Location:?pg=dashboard&modify=manage_users');
                    exit();
                }
            } else {
                header('Location:?pg=dashboard&error=missing_id');
                exit();
            }
            break;

        case 'edit':
            // Traitement de la soumission du formulaire
            if (isset($_POST['full_name'], $_POST['pseudo'], $_POST['email'], $_POST['phone'], $_POST['date_birth'], $_POST['gender'], $_POST['role'], $_POST['id'])) {
                try {
                    if ($adminUser->updateProfile($_POST, (int)$_POST['id'])) {
                        header('Location:?pg=dashboard&modify=manage_users');
                        exit();
                    }
                } catch (\Exception $e) {
                    throw new Exception("Erreur lors du traitement : " . $e->getMessage(), 0, $e);
                }
            }

            // Charger tous les utilisateurs pour la vue manage_users.php
            $tab = $adminUser->findAll();

            // Vérifier si un ID est passé pour l'édition
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                // Charger la vue manage_users.php qui gère l'affichage du formulaire d'édition
                require_once PATH . "/src/view/admins/manage_users.php";
            } else {
                // Si pas d'ID, rediriger vers la liste des utilisateurs
                header('Location:?pg=dashboard&modify=manage_users');
                exit();
            }
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
    require_once PATH . "/src/view/admins/home.php";
}
