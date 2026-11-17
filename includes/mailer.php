<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envoie un e-mail HTML. Le contenu doit déjà être échappé par l'appelant.
 */
function send_email(string $to, string $subject, string $htmlBody): bool
{
    global $CONFIG;
    $autoload = dirname(__DIR__) . '/vendor/autoload.php';
    if (!is_file($autoload) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    require_once $autoload;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $CONFIG['smtp_host'];
        $mail->Port = $CONFIG['smtp_port'];
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($CONFIG['mail_from'], $CONFIG['app_name']);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = str_replace(["\r", "\n"], '', $subject);
        $mail->Body = $htmlBody;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}
