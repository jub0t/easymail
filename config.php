<?php


include "./vendor/autoload.php";

use Dallgoot\Yaml\Loader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$DEBUG = false;
$YLOADER = new Loader(null, 0, $DEBUG);
$M_CONFIG = $YLOADER->load("./config.yml")->parse();

function SendMail($receivers, $message)
{
    global $M_CONFIG;

    $mail = new PHPMailer(true);

    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->SMTPAuth   = true;
    $mail->isSMTP();

    $mail->Host       = $M_CONFIG["mail"]["host"];
    $mail->Username   = $M_CONFIG["mail"]["username"];
    $mail->Password   = $M_CONFIG["mail"]["password"];
    $mail->Port       = $M_CONFIG["mail"]["port"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->setFrom($M_CONFIG["mail"]["username"], $M_CONFIG["mail"]["displayName"]);

    foreach ($receivers as $key => $receiver) {
        if (is_null($receiver["username"])) {
            $mail->addAddress(
                $receiver["name"]
            );
        } else {
            $mail->addAddress(
                $receiver["email"],
                $receiver["displayName"]
            );
        }
    }

    // $mail->addAttachment('/var/tmp/file.tar.gz');
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');

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

    $mail->send();
    return true;
}
