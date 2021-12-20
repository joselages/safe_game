<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';


function sendMail($data){
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    $mail->isSMTP();

    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = 0;

    $mail->SMTPAuth = true;

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->CharSet = 'UTF-8';

    $mail->Host = CONFIG["MAIL_HOST"];
    $mail->Port = CONFIG["MAIL_PORT"];
    $mail->Username = CONFIG["MAIL_ADDRESS"];
    $mail->Password = CONFIG["MAIL_PASSWORD"];

    $mail->setFrom(CONFIG["MAIL_ADDRESS"], 'Safe Game');

    $mail->addAddress($data['send_to'], $data['username']);

    $mail->Subject = $data['subject'];

    $mail->msgHTML($data['content']);

    if (!$mail->send()) {
        return false;
    }

    return true;
}