<?php
	class Email {
		private function __construct(){}
		
		public static function Send( $dest, $subject, $body ){
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = SMTP_HOSTNAME;
			$mail->Port = 587;
			$mail->SMTPSecure = "tls";

			$mail->SMTPAuth = true;
			$mail->Username = SMTP_USERNAME;
			$mail->Password = SMTP_PASSWORD;
			
			$mail->setLanguage( "fr", PHPMAILER_LANG );
			$mail->CharSet = "UTF-8";
			
			$mail->SetFrom( LARP_ADDRESS, LARP_NAME );
			$mail->addReplyTo( LARP_ADDRESS, LARP_NAME );
			
			$mail->addAddress( $dest );
			$mail->addBCC( LARP_ADDRESS, LARP_NAME );
			$mail->Subject = LARP_NAME . " - " . $subject;
			
			$body .= "<br />\n";
			$body .= "<b><u>Informations sur la demande</u></b><br />\n";
			$body .= "<b>Date & heure :</b> " . date( "Y-m-d h:i" ) . "<br />\n";
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
			Message::Debug( "Courriel '" . $subject . " envoyé à " . $dest );
			return TRUE;
		}
	}
