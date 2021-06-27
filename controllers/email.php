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
        $mail->Username   = '8932661fa94e64';                     // SMTP username
        $mail->Password   = '2d54a4c0e60c08';                              // SMTP password
               // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = '2525'; 
        $mail->setFrom("test@test.com","test");
        $mail->addAddress($address);
        $mail->Subject = "Registration";
        $mail->Body= "$body";

        return $mail->send();
      

    }
}
