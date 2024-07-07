<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendTestEmail($to, $subject, $message, $from = 'no-reply@example.com') {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;

        // Destinataires
        $mail->setFrom($from, 'Mailer');
        $mail->addAddress($to);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi de l'email: {$mail->ErrorInfo}");
        return false;
    }
}

