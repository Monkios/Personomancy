<?php
	if( isset( $_POST['send'] ) && isset( $_POST['email'] ) ){
		$player = Community::GetPlayerByEmail( mb_convert_encoding( Security::FilterInput( $_POST['email'] ), 'ISO-8859-1', 'UTF-8') );
		
		if( $player ){
			//if( $player->IsActive ){
				$identity = new Identity( $player->Id );
			
				$identity->SendForgotEmail();
				Message::Notice( "Un mot de passe temporaire vous a été envoyé. Si vous ne le recevez pas, veuillez vérifier dans vos courriers indésirables." );
			
				header( "Location: ./index.php" );
				die();
			//} else {
			//	Message::Erreur( "Le compte doit être activé avant de changer son mot de passe." );
			//}
		} else {
			Message::Erreur( "Le courriel reçu n'est associé à aucun utilisateur." );
		}
	}
	
	include "./views/top.php";
	include "./views/u/forgot.php";
	include "./views/bottom.php";
?>