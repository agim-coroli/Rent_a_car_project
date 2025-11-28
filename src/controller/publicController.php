<?php
// require grace a lautoload
use model\manager\UserManager;
use model\mapping\UserMapping;
use model\Exception\ExceptionFr;
use model\service\MailManager;


// instancie lobjet Manager pour les action utilisateurs
$manageUser = new UserManager($connectPDO);



if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            require_once PATH . "/src/view/guests/catalogue.php";

            break;
        case 'connexion':
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $user = $manageUser->connect($_POST);

                if ($user) {
                    if ($user->getRole() == 1) {
                        // Admin
                        header("Location: ./");
                        exit();
                    } else {
                        // Utilisateur normal
                        header("Location: ./");
                        exit();
                    }
                } else {
                    echo "Email ou mot de passe incorrect ❌";
                }
            }
            require_once PATH . "/src/view/guests/connexion.php";
            break;


        case 'inscription':
            try {
                // Vérifie que tous les champs sont présents
                if (!isset(
                    $_POST['full_name'],
                    $_POST['pseudo'],
                    $_POST['email'],
                    $_POST['phone'],
                    $_POST['password'],
                    $_POST['password_confirm'],
                    $_POST['date_birth'],
                    $_POST['gender']
                )) {
                    throw new ExceptionFr("Tous les champs requis doivent être remplis.");
                }

                // Vérifie la cohérence des mots de passe AVANT de créer l'objet
                if ($_POST['password'] !== $_POST['password_confirm']) {
                    throw new ExceptionFr("Les mots de passe ne correspondent pas.");
                }

                // Prépare les données utilisateur (UserMapping valide et nettoie via ses setters)
                $newUser = new UserMapping($_POST);

                // Sauvegarde en DB via le Manager
                if ($manageUser->create($newUser)) {

                    $mailer = new MailManager;
                    $mailer->sendConfirmationEmail($newUser);
                    // Succès → redirection
                    header("Location:?pg=confirmToken");
                    exit();
                } else {
                    throw new ExceptionFr("Échec lors de l'inscription en base de données.");
                }
            } catch (ExceptionFr $e) {
                // Erreurs métier (validation, cohérence, etc.)
                echo "Erreur : " . htmlspecialchars($e->getMessage());
            } catch (Throwable $e) {
                // Erreurs inattendues (PDO, logique interne, etc.)
                echo "Erreur inattendue : " . htmlspecialchars($e->getMessage());
            }

            require_once PATH . "/src/view/guests/inscription.php";
            break;

        case 'confirmToken':
            if (isset($_GET['token'])) {
                $token = $_GET['token'];

                // Cherche l'utilisateur par token
                $user = $manageUser->findByToken($token);
                var_dump($user->getEmailTokenExpires());

                if ($user) {
                    // Confirme l'email
                    $manageUser->confirmEmail($user);
                    echo "Email confirmé avec succès ✅";
                    header('Location: ./');
                    exit();
                } else {
                    echo "Token invalide ❌";
                }
            }
            require_once PATH . "/src/view/guests/confirmToken.php";
            break;

        case "dashboard":
            require_once PATH . "/src/view/dashboard.php";
            break;

        case 'deconnexion':
            if ($manageUser->disconnect()) {
                header('Location: ./');
                exit();
            }
            break;

        default:
            require_once PATH . "/src/view/404.php";
            break;
    }
} else {
    require_once PATH . "/src/view/guests/home.php";
    // require_once PATH . "/src/test/testEntity.php";
    // require_once PATH . "/src/test/testManager.php";
}
