<?php

namespace model\service;

use DateTime;
use Exception;
use model\mapping\UserMapping;

use PHPMailer\PHPMailer\PHPMailer;



class MailManager
{
    public function sendConfirmationEmail(UserMapping $user): bool
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_USER'], 'No reply');
            $mail->addAddress($user->getEmail(), $user->getFullName());

            $mail->isHTML(true);
            $mail->Subject = "Confirmation d'inscription";

            $mail->Body    = "Bonjour {$user->getFullName()},<br>
                              Clique sur ce lien pour confirmer ton email : 
                              <a href='http://rentcar/?pg=confirmToken&token={$user->getEmailToken()}'>Confirmer</a>";

            return $mail->send();
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de l'envoi du mail : " . $mail->ErrorInfo);
        }
    }

    public function sendResetPasswordEmail(UserMapping $user): bool
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_USER'], 'No reply');
            $mail->addAddress($user->getEmail(), $user->getFullName());

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de mot de passe';

            $mail->Body    = "Bonjour {$user->getFullName()},<br>
                          Clique sur ce lien pour réinitialiser ton mot de passe : 
                          <a href='http://rentcar/?pg=reset_password_form&token={$user->getPasswordToken()}'>Réinitialiser mon mot de passe</a>";

            return $mail->send();
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de l'envoi du mail : " . $mail->ErrorInfo);
        }
    }

    public function sendFacturationCopy(UserMapping $user, array $reservation): bool
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_USER'], 'No reply');
            $mail->addAddress($user->getEmail(), $user->getFullName());

            $mail->isHTML(true);
            $mail->Subject = 'Copie de la facture de votre location';

            // Préparer le HTML des options
            $optionsHtml = '';
            if (!empty($reservation['options'])) {
                foreach ($reservation['options'] as $opt) {
                    $optionsHtml .= '
                <tr>
                  <td>' . htmlspecialchars($opt["nom"]) . '</td>
                  <td>1</td>
                  <td>' . htmlspecialchars($opt["prix"]) . '</td>
                  <td>' . htmlspecialchars($opt["prix"]) . '</td>
                </tr>';
                }
            }
            // Conversion des dates pour un affichage lisible
            $dateDebutObj = new DateTime($reservation['dateDebut']);
            $dateFinObj   = new DateTime($reservation['dateFin']);

            // Construire le body
            $mail->Body = '
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
    h1 { text-align: center; text-transform: uppercase; margin-bottom: 30px; }
    .header, .client { margin-bottom: 20px; }
    .header p, .client p { margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 30px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background: #f2f2f2; }
    .total { text-align: right; font-weight: bold; }
    .footer { margin-top: 30px; }
  </style>
</head>
<body>
  <h1>Facture</h1>

  <div class="header">
    <p><strong>Société :</strong> Rentacar</p>
    <p><strong>Adresse :</strong> Rue de l\'adresse 1, 1000 Bruxelles</p>
    <p><strong>Téléphone :</strong> 0477 84 56 25</p>
    <p><strong>Email :</strong> example@gmail.com</p>
  </div>

  <div class="client">
    <p><strong>Client :</strong> ' . htmlspecialchars($user->getFullName()) . '</p>
    <p><strong>Email :</strong> ' . htmlspecialchars($user->getEmail()) . '</p>
  </div>

  <p><strong>Facture n° :</strong> FAC-' . date("YmdHis") . '</p>
  <p><strong>Date d\'émission :</strong> ' . date("d/m/Y") . '</p>

  <table>
    <tr>
      <th>Description</th>
      <th>Quantité</th>
      <th>Prix unitaire (€)</th>
      <th>Total (€)</th>
    </tr>
    <tr>
      <td>' . htmlspecialchars($reservation['vehicule']) . ' (du ' . $dateDebutObj->format("d/m/Y H:i") . ' au ' . $dateFinObj->format("d/m/Y H:i") . ')</td>
      <td>' . htmlspecialchars($reservation['nbJours']) . ' jours</td>
      <td>' . htmlspecialchars($reservation['tarifJour']) . '</td>
      <td>' . htmlspecialchars($reservation['totalLocation']) . '</td>
    </tr>
    ' . $optionsHtml . '
    <tr>
      <td colspan="3" class="total">Total TTC</td>
      <td>' . htmlspecialchars($reservation['totalFacture']) . ' €</td>
    </tr>
  </table>

  <div class="footer">
    <p><strong>Mode de paiement :</strong> Carte Visa</p>
    <p>Merci pour votre confiance et bonne route !</p>
  </div>
</body>
</html>';

            return $mail->send();
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de l'envoi du mail : " . $mail->ErrorInfo);
        }
    }
}
