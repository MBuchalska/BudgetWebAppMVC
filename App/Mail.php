<?php

namespace App;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $userName, $subject, $text, $html)
    {
        $mail = new PHPMailer();
		
		$mail->isSMTP();
		$mail->Host = Config::MAIL_HOST;    // z usługi mailowej hosta
		$mail->SMTPAuth = true;
		$mail->Username = Config::MAIL_DOMAIN; //z usługi mailingowej 
		$mail->Password = Config::MAIL_PASS;  //z usługi mailingowej 
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;

		$mail->setFrom(Config::MAIL_DOMAIN, Config::MAIL_SENDER);
		$mail->addAddress($to, $userName); //tu można poprawić na nazwę użytkownika z bazy
		$mail->Subject =$subject;
		$mail->isHTML(true);
		$mail->Body = $html;
		$mail->AltBody=$text;
		
		
		if(!$mail->send()){
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}else{
			echo 'Message has been sent';
		}
		
	}


}