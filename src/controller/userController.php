<?php

use model\manager\UserManager;
use model\manager\CatalogueManager;
use model\manager\ReservationsManager;

use model\mapping\ReservationsMapping;

use model\service\MailManager;

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== 0) {
    header("Location:./");
    exit();
}

$adminUser = new UserManager($connectPDO);
$manageCatalogue = new CatalogueManager($connectPDO);
$manageReservations = new ReservationsManager($connectPDO);

if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {

        case 'catalogue':
            if (isset($_GET['reservation'], $_GET['slug']) && !empty($_GET['slug'])) {
                // Page réservation
                $vehiculeToReserve = $manageCatalogue->findBySlug($_GET['slug']);
                if (!empty($_POST)) {



                    // Convertir les dates en objets DateTime pour la validation
                    // $dateDebutObj = new DateTime($dateDebut);
                    // $dateFinObj = new DateTime($dateFin);
                    // $now = new DateTime();


                    // // Vérifier que les champs existent et ne sont pas vides
                    // if (empty($dateDebut) || empty($dateFin)) {
                    //     die("Erreur : vous devez choisir une date de début et une date de fin.");
                    // }



                    // // Vérifier que la date de début n'est pas dans le passé
                    // if ($dateDebutObj < $now) {
                    //     die("Reservation impossible");
                    // }

                    // // Vérifier cohérence : date de fin doit être postérieure à la date de début
                    // if ($dateFinObj <= $dateDebutObj) {
                    //     die("Reservation impossible");
                    // }

                    $_SESSION["vehicule_id"] = $vehiculeToReserve->getId();
                    $_SESSION["date_debut"] = $_POST['date_debut'];
                    $_SESSION["date_fin"] = $_POST['date_fin'];
                    $_SESSION["vehicule_prix"] = $vehiculeToReserve->getPrix();
                    $_SESSION["vehicule_name"] = $vehiculeToReserve->getMarque();
                    $_SESSION["caution"] = $vehiculeToReserve->getCaution();



                    $row = new ReservationsMapping($_SESSION);


                    $insertReservation = $manageReservations->vehiculeReserved($row);
                    if ($insertReservation === true) {
                        header('Location:?pg=checkout_reservation');
                        exit();
                    }
                }

                require_once PATH . "/src/view/users/reservations_vehicule.php";
            } elseif (isset($_GET['slug']) && !empty($_GET['slug'])) {
                // Page détail
                $vehiculeDetails = $manageCatalogue->findBySlug($_GET['slug']);
                require_once PATH . "/src/view/users/catalogue_detail.php";
            } else {
                // Liste complète
                $allVehicule = $manageCatalogue->findAll();
                require_once PATH . "/src/view/users/catalogue.php";
            }
            break;

        case 'checkout_reservation':
            $dateDebut = new DateTime($_SESSION["date_debut"]);
            $dateFin   = new DateTime($_SESSION["date_fin"]);

            // Calcul de la différence
            $interval = $dateDebut->diff($dateFin);

            // Nombre de jours
            $nbJours = $interval->days;

            // prix de la caution
            $caution = $_SESSION['caution'];

            // prix du nombre de jours
            $prix_total = $_SESSION['vehicule_prix'] * $nbJours;

            $facture_total = $prix_total + $caution;

            if (isset($_POST['validation'])) {


                try {
                    $checkout_session = \Stripe\Checkout\Session::create([
                        "mode" => "payment",
                        "success_url" => "http://rentcar/?pg=reservation_billing",
                        "line_items" => [
                            [
                                "quantity" => 1,
                                "price_data" => [
                                    "currency" => "eur",
                                    "unit_amount" => $facture_total * 100,
                                    "product_data" => [
                                        "name" => $_SESSION['vehicule_name']
                                    ]
                                ]
                            ]
                        ]
                    ]);


                    http_response_code(303);
                    header('Location: ' . $checkout_session->url);
                    exit();
                } catch (\Throwable $th) {
                    echo "Le payment n'as pas pu etre effectué, opération annulé";
                }
            }
            require_once PATH . "/src/view/users/checkout_form.php";
            break;

        case 'reservation_billing':

            // en PROD utiliser un webhook Stripe (plus robuste)
            // crée un endpoint /stripe/webhook qui écoute les événements checkout.session.completed.

            // pour evité les facture en doublon en PROD mieux vaut gérer ça en base de données pour être sûr qu’une facture n’est jamais envoyée deux fois.

            $dateDebut = new DateTime($_SESSION["date_debut"]);
            $dateFin   = new DateTime($_SESSION["date_fin"]);
            $interval  = $dateDebut->diff($dateFin);
            $nbJours   = $interval->days;

            $caution       = $_SESSION['caution'];
            $prix_total    = $_SESSION['vehicule_prix'] * $nbJours;
            $facture_total = $prix_total + $caution;

            $user = $adminUser->findById($_SESSION['user_id']);
            if (empty($_SESSION['facture_envoyee'])) {
                $mailer = new MailManager;
                $mailer->sendFacturationCopy($user, [
                    'vehicule' => $_SESSION['vehicule_name'],
                    'dateDebut' => $_SESSION['date_debut'],
                    'dateFin' => $_SESSION['date_fin'],
                    'nbJours' => $nbJours,
                    'tarifJour' => $_SESSION['vehicule_prix'],
                    'totalLocation' => $prix_total,
                    'options' => $_SESSION['options'] ?? [],
                    'totalFacture' => $facture_total
                ]);
                $_SESSION['facture_envoyee'] = true;
            }
            require_once PATH . "/src/view/users/reservation_billing.php";

            break;
        case 'reservation':
            $vehiculeReserved = $manageReservations->findReservedByUser($_SESSION['user_id']);


            require_once PATH . "/src/view/users/reservation.php";

            break;
        case 'dashboard':
            if (isset($_GET['modify']) && $_GET['modify'] === "account_modify") {
                $userToUpdate = $adminUser->findById($_SESSION['user_id']);

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
                        if ($adminUser->updateProfile($_POST, $_SESSION['user_id'])) {
                            header('Location:?pg=dashboard');
                            exit();
                        }
                    }
                }

                require_once PATH . "/src/view/users/account_modify.php";
            } elseif (isset($_GET['modify']) && $_GET['modify'] === "account_delete") {

                $userToDelete = $adminUser->findById($_SESSION['user_id']);

                if (isset($_POST['delete_confirm']) && $_POST['delete_confirm'] === "1") {
                    $adminUser->deleteAccount($userToDelete);
                    unset($_SESSION['role']);
                    unset($_SESSION['user_id']);
                    header('Location:./');
                    exit();
                }


                require_once PATH . "/src/view/users/account_delete.php";
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
    require_once PATH . "/src/view/users/home.php";
}
