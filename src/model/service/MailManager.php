<?php

namespace model\service;

use Dotenv\Dotenv;
use model\mapping\UserMapping;
use model\Exception\ExceptionFr;
use PHPMailer\PHPMailer\PHPMailer;

$dotenv = Dotenv::createImmutable(__DIR__); // adapte le chemin si ton .env est ailleurs
$dotenv->load();

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
            $mail->Subject = 'Confirmation d’inscription';
            $mail->Body    = "Bonjour {$user->getFullName()},<br>
                              Clique sur ce lien pour confirmer ton email : 
                              <a href='http://rentcar/?pg=confirmToken&token={$user->getEmailToken()}'>Confirmer</a>";

            return $mail->send();
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de l'envoi du mail : " . $mail->ErrorInfo);
        }
    }
}
