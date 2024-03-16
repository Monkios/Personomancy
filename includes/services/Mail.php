<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	class Email {
		private function __construct(){}
		
		public static function Send( $dest, $subject, $body ){
			$mail = new PHPMailer( true );
			$mail->IsSMTP();
			$mail->SMTPDebug = SMTP::DEBUG_OFF; #SMTP::DEBUG_SERVER;


			$mail->Host = MAIL_SMTP_HOSTNAME;
			$mail->Port = MAIL_SMTP_PORT;
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
			$mail->SMTPAuth = true;

			$mail->Username = MAIL_SMTP_USERNAME;
			$mail->Password = MAIL_SMTP_PASSWORD;
			
			$mail->setLanguage( "fr", PHPMAILER_LANG );
			$mail->CharSet = "UTF-8";
			
			$mail->SetFrom( MAIL_SMTP_USERNAME, LARP_NAME );
			$mail->addReplyTo( MAIL_SMTP_USERNAME, LARP_NAME );
			
			$mail->addAddress( $dest );
			#$mail->addBCC( SMTP_USERNAME, LARP_NAME );

			$mail->Subject = LARP_NAME . " - " . $subject;
			
			$body .= "<br />\n";
			$body .= "<b><u>Informations sur la demande</u></b><br />\n";
			$body .= "<b>Date & heure :</b> " . Date::FormatNow() . "<br />\n";
			$body .= "<b>IP du demandeur :</b> " . $_SERVER[ "REMOTE_ADDR" ] . "<br />\n";
			if( isset( $_SESSION[ SESSION_KEY ][ "User" ] ) && $_SESSION[ SESSION_KEY ][ "User" ]->getFullName() != "" ){
				$body .= "<b>Nom du demandeur :</b> " . $_SESSION[ SESSION_KEY ][ "User" ]->getFullName() . "<br />\n";
			}
			$body .= "<br />\n";
			$body .= "Si cette demande ne vient pas de vous, veuillez en informer l'équipe d'animation dans les plus brefs délais.";
			
			$mail->Body = $body;
			$mail->AltBody = strip_tags( str_ireplace( array( "<br>", "<br/>", "<br />" ), "", $body ) );
			
			if( !$mail->Send() ){
				Message::Erreur( "Le courriel n'a pu être envoyé.", $mail->ErrorInfo );
				return FALSE;
			}
			if( !self::SaveMail( $mail ) ){
				Message::Debug( "Le courriel n'a pu être archivé par GMail." );
			}
			Message::Debug( "Courriel '" . $subject . " envoyé à " . $dest );
			return TRUE;
		}

		# Save the e-mail to the Sent Mail folder on GMail
		# https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
		private static function SaveMail($mail)
		{
			//You can change 'Sent Mail' to any other folder or tag
			$path = imap_utf8_to_mutf7( MAIL_IMAP_SENT_MAILBOX );
			
			//Tell your server to open an IMAP connection using the same username and password as you used for SMTP
			$imapStream = imap_open($path, $mail->Username, $mail->Password);
		
			$result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
			imap_close($imapStream);
		
			return $result;
		}
	}
