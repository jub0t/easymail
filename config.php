<?php

include "./vendor/autoload.php";

use Dallgoot\Yaml\Loader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load configuration from YAML file
$YLOADER = new Loader(null, 0, false);
$M_CONFIG = $YLOADER->load("./config.yml")->parse();

function SendMail($receivers, $message)
{
    global $M_CONFIG;

    // Check if PHPMailer class exists
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        throw new Exception('PHPMailer class not found. Please check if the autoload file is loaded properly.');
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Set SMTP configuration
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->SMTPAuth = true;

    // No SMTP because we'll be hosting this directly on our namecheap cpanel :)
    // $mail->isSMTP();

    // Authentication loaded from config.yml file, ig.
    $mail->Username = $M_CONFIG->mail->username;
    $mail->Password = $M_CONFIG->mail->password;
    $mail->Host = $M_CONFIG->mail->host;
    $mail->Port = $M_CONFIG->mail->port;

    // Set sender information
    $mail->setFrom($M_CONFIG->mail->username, $M_CONFIG->mail->displayName);

    // Add receivers
    foreach ($receivers as $receiver) {
        $displayName = isset($receiver["displayName"]) ? $receiver["displayName"] : null;
        $mail->addAddress($receiver["email"], $displayName);
    }

    // Set email content
    $mail->isHTML(true);
    if (isset($message["subject"])) {
        $mail->Subject = $message["subject"];
    }
    if (isset($message["body"])) {
        $mail->Body = $message["body"];
    }
    if (isset($message["altBody"])) {
        $mail->AltBody = $message["altBody"];
    }

    try {
        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Handle exceptions
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
