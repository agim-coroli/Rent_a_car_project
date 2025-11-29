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
            } elseif(isset($_GET['modify']) && $_GET['modify'] === "manage_users"){
                require_once PATH . "/src/view/admins/manage_users.php";
                
            }
            
            else {
                require_once PATH . "/src/view/dashboard.php";
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

            $user = new UserMapping([
                'full_name' => $fullName,
                'pseudo' => $pseudo,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'date_birth' => $dateBirth,
                'gender' => $gender
            ]);

            $success = $adminUser->create($user);

            if ($success) {
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

        case 'edit':
            if (isset($_POST['full_name'], $_POST['pseudo'], $_POST['email'], $_POST['phone'], $_POST['date_birth'], $_POST['gender'], $_POST['role'])) {


                try {

                    $updateUser = new UserMapping($_POST);
                    if ($adminUser->updateProfile($updateUser)) {
                        header('Location:?pg=dashboard');
                        exit();
                    }
                } catch (\Exception $e) {
                    $exceptionFr = new ExceptionFr("Erreur lors du traitement : " . $e->getMessage(), 0, $e);
                    echo $exceptionFr->getMessage();
                }
            }

            $tab = $adminUser->findAll();

            require_once PATH . "/src/view/admins/dashboard.php";
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
