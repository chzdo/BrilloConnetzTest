<?php


namespace controllers;

require('../vendor/autoload.php');
use \PHPMailer\PHPMailer\PHPMailer;

/**
 * PHPMailer class for sending emails
 */

class email{

    static function sendMail($address,$body){
       
        $mail = new PHPMailer(true);
        $mail->Host       = 'smtp.mailtrap.io';   ; 
        $mail->isSMTP();                // Set the SMTP server to send through
        $mail->SMTPAuth   = true;  
        $mail->SMTPDebug = 0;  
        $mail->Username   = '';                     // SMTP username
        $mail->Password   = '';                              // SMTP password
               // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = '2525'; 
        $mail->setFrom("test@test.com","test");
        $mail->addAddress($address);
        $mail->Subject = "Registration";
        $mail->Body= "$body";

        return $mail->send();
      

    }
}
