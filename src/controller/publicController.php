<?php

use model\manager\UserManager;
use model\mapping\UserMapping;



use model\Exception\ExceptionFr;
use model\service\MailManager;

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
                        header("Location: ./");
                        exit();
                    } else {
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

                if ($_POST['password'] !== $_POST['password_confirm']) {
                    throw new ExceptionFr("Les mots de passe ne correspondent pas.");
                }

                $newUser = new UserMapping($_POST);

                if ($manageUser->create($newUser)) {

                    $mailer = new MailManager;
                    $mailer->sendConfirmationEmail($newUser);
                    header("Location:?pg=confirmToken");
                    exit();
                } else {
                    throw new ExceptionFr("Échec lors de l'inscription en base de données.");
                }
            } catch (ExceptionFr $e) {
                echo "Erreur : " . htmlspecialchars($e->getMessage());
            } catch (Throwable $e) {
                echo "Erreur inattendue : " . htmlspecialchars($e->getMessage());
            }

            require_once PATH . "/src/view/guests/inscription.php";
            break;

        case 'confirmToken':
            if (isset($_GET['token'])) {
                $token = $_GET['token'];

                $user = $manageUser->findByToken($token);

                if ($user) {
                    $manageUser->confirmEmail($user);
                    echo "Email confirmé avec succès ✅";
                    header('Location: ./');
                    exit();
                } else {
                    echo "Token invalide ❌";
                }
            } else {
                echo "<h1>Confirmation d'email</h1>";
                echo "<p>Vérifiez votre boîte mail et cliquez sur le lien de confirmation.</p>";
            }
            require_once PATH . "/src/view/guests/inscription.php";

            break;

        case "dashboard":
            require_once PATH . "/src/view/guests/dashboard.php";
            break;

        case 'forgot_password':
            try {
                if (!empty($_POST['email'])) {
                    $email = trim($_POST['email']);

                    $user = $manageUser->findByEmailForReset($email);

                    if ($user) {
                        if ($manageUser->generatePasswordResetToken($user)) {
                            $mailer = new MailManager;
                            $mailer->sendResetPasswordEmail($user);

                            echo "Un email de réinitialisation a été envoyé à votre adresse email ✅";
                        } else {
                            throw new ExceptionFr("Erreur lors de la génération du token.");
                        }
                    } else {
                        echo "Si cet email existe, un lien de réinitialisation a été envoyé ✅";
                    }
                }
            } catch (ExceptionFr $e) {
                echo "Erreur : " . htmlspecialchars($e->getMessage());
            } catch (Throwable $e) {
                echo "Erreur inattendue : " . htmlspecialchars($e->getMessage());
            }

            require_once PATH . "/src/view/guests/reset_password.php";
            break;

        case 'reset_password_form':
            if (!isset($_GET['token'])) {
                echo "Token manquant ❌";
                require_once PATH . "/src/view/404.php";
                break;
            }

            $token = $_GET['token'];
            $user = $manageUser->findByPasswordToken($token);

            if (!$user) {
                echo "Token invalide ou expiré ❌";
                require_once PATH . "/src/view/404.php";
                break;
            }

            require_once PATH . "/src/view/guests/reset_password_form.php";
            break;

        case 'reset_password':
            try {
                if (!isset($_POST['token'], $_POST['newPassword'], $_POST['confirmNewPassword'])) {
                    throw new ExceptionFr("Tous les champs sont requis.");
                }

                $token = $_POST['token'];
                $newPassword = $_POST['newPassword'];
                $confirmNewPassword = $_POST['confirmNewPassword'];

                if ($newPassword !== $confirmNewPassword) {
                    throw new ExceptionFr("Les mots de passe ne correspondent pas.");
                }

                $user = $manageUser->findByPasswordToken($token);

                if (!$user) {
                    throw new ExceptionFr("Token invalide ou expiré.");
                }

                if (!$user->getId()) {
                    throw new ExceptionFr("Erreur : ID utilisateur introuvable.");
                }

                $currentPasswordHash = $manageUser->getPasswordHashById($user->getId());

                if ($currentPasswordHash && is_string($currentPasswordHash) && strlen($currentPasswordHash) > 0) {
                    if (password_verify($newPassword, $currentPasswordHash) === true) {
                        throw new ExceptionFr("Le nouveau mot de passe doit être différent de l'ancien mot de passe.");
                    }
                }

                $user->setPassword($newPassword);

                if ($manageUser->resetPassword($user)) {
                    echo "Mot de passe réinitialisé avec succès ✅";
                    header('Location: ?pg=connexion');
                    exit();
                } else {
                    throw new ExceptionFr("Erreur lors de la réinitialisation du mot de passe.");
                }
            } catch (ExceptionFr $e) {
                echo "Erreur : " . htmlspecialchars($e->getMessage());
            } catch (Throwable $e) {
                echo "Erreur inattendue : " . htmlspecialchars($e->getMessage());
            }
            break;

        default:
            require_once PATH . "/src/view/404.php";
            break;
    }
} else {
    require_once PATH . "/src/view/guests/home.php";
}
