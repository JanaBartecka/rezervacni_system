<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';

Class Mail {
    /**
     * send email
     * 
     * @param array $mailList - array of recepients with email and full name
     * @param string $subject - subject of the email
     * @param string $body - body of the email
     * 
     * @return void
     */

     public static function sendMail($mailList, $subject, $body, $addAddress = true) {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'mczirafadknl@gmail.com';                     //SMTP username
            $mail->Password   = 'khvutgnragbjartj';                               //SMTP password
            $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            $mail->CharSet    = 'UTF-8';
            $mail->Encoding   = 'base64';
            //Recipients
            $mail->setFrom('info@mc-zirafa.cz', 'MC Zirafa - rezervacni system');
            $addAddress && $mail->addAddress('info@mc-zirafa.cz', 'MC Zirafa - rezervacni system');
            foreach($mailList as $recepient) {
                $mail->addBCC($recepient['email'], $recepient['first_name'] . ' ' . $recepient['second_name']);     //Add a recipient
                echo $recepient['email'];
                echo $recepient['first_name'];
                echo $recepient['second_name'];
            };
            $mail->addReplyTo('info@mc-zirafa.cz', 'MC Zirafa');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
            //Content
            $mail->Subject = $subject;
            $mail->Body    = $body;
        
            $mail->send();
            return true;
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Zprava nebyla odeslana. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
     }
}

