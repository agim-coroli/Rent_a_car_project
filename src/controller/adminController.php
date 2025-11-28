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
            require_once PATH . "/src/view/dashboard.php";
            break;

        case 'generate-user':
            // Génération d'un utilisateur avec des données aléatoires
            $firstNames = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Luc', 'Emma', 'Thomas', 'Julie', 'Antoine', 'Camille', 'Nicolas', 'Laura', 'David', 'Sarah', 'Alexandre', 'Claire'];
            $lastNames = ['Dupont', 'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert', 'Richard', 'Petit', 'Durand', 'Leroy', 'Moreau', 'Simon', 'Laurent', 'Lefebvre', 'Michel', 'Garcia'];

            $genders = ['Masculin', 'Feminin'];
            $domains = ['gmail.com', 'yahoo.fr', 'hotmail.com', 'outlook.fr', 'example.com'];

            // Génération des données aléatoires
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $pseudo = strtolower($firstName . $lastName . rand(100, 999));
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@' . $domains[array_rand($domains)]);
            $phone = '+' . rand(32, 39) . rand(100000000, 999999999);
            $password = 'password' . rand(1000, 9999); // Mot de passe simple pour les tests
            $gender = $genders[array_rand($genders)];

            // Date de naissance aléatoire entre 18 et 80 ans
            $year = date('Y') - rand(18, 80);
            $month = rand(1, 12);
            $day = rand(1, 28);
            $dateBirth = sprintf('%04d-%02d-%02d', $year, $month, $day);



            // Créer l'utilisateur
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
                header('Location: ?pg=dashboard');
                exit();
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                $del = $adminUser->delete($_GET['id']);
                if ($del === true) {
                    header('Location:?pg=dashboard&success=user_deleted');
                    exit();
                } else {
                    header('Location:?pg=dashboard&error=delete_failed');
                    exit();
                }
            } else {
                header('Location:?pg=dashboard&error=missing_id');
                exit();
            }
            break;

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

            require_once PATH . "/src/view/dashboard.php";
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
