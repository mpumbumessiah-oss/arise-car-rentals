<?php
// includes/email-config.php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * -----------------------
 * PHPMailer LOADING
 * -----------------------
 */
$phpmailerPath = __DIR__ . '/../PHPMailer/src/';

if (!file_exists($phpmailerPath . 'PHPMailer.php')) {
    die('PHPMailer not found. Check installation path.');
}

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

/**
 * -----------------------
 * SMTP CONFIGURATION
 * -----------------------
 * ⚠️ Best practice: move these to .env later
 */
// ========== YOUR BREVO SMTP CREDENTIALS ==========
define('SMTP_HOST', 'smtp-relay.brevo.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'ac7276001@smtp-brevo.com');   // <-- Your login
define('SMTP_PASS', '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');  // <-- Paste your SMTP key here
define('SMTP_FROM', 'info@ariserenals.ae');
define('SMTP_FROM_NAME', 'Arise Car Rentals');
// =================================================

/**
 * -----------------------
 * SEND EMAIL FUNCTION
 * -----------------------
 */
function sendEmail(string $to, string $subject, string $message, bool $isHTML = true): bool
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Encoding (IMPORTANT FIX)
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);

        // Content
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
        return false;
    }
}