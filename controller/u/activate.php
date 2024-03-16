<?php
	$email = "";
	
	if( isset( $_POST['send'] ) && isset( $_POST['email'] ) && $_POST['email'] != "" ){
		$email = Security::FilterEmail( $_POST['email'] );
	} elseif( isset( $_GET['m'] ) ){
		$email = Security::FilterEmail( $_GET['m'] );
	}

	if( $email != "" ){
		$player = Community::GetPlayerByEMail( mb_convert_encoding( $email, 'ISO-8859-1', 'UTF-8') );
		
		if( !$player->IsActive ){
			$identity = new Identity( $player->Id );
			if( isset( $_GET['k'] ) ){
				if( $identity->ActivateAccount( Security::FilterInput( $_GET['k'] ) ) ){
					Message::Notice( "Votre compte a été activé. Vous pouvez maintenant vous connecter." );
					
					header( "Location: ./index.php" );
					die();
				} else {
					Message::Erreur( "Une erreur s'est produite lors de l'activation. Veuillez saisir vos renseignements à nouveau." );
				}
			} else {
				$identity->SendValidationEmail();
				Message::Notice( "Ce compte doit être activé avant de pouvoir permettre la connection. Un courriel de validation a été envoyé. Si vous ne le recevez pas, veuillez vérifier dans vos courriers indésirables." );
					
				header( "Location: ./index.php" );
				die();
			}
		} else {
			Message::Notice( "Cet utilisateur est déjà actif et n'a pas besoin d'être activé." );
			header( "Location: ./index.php" );
		}
	} else {
		Message::Notice( "Cette adresse courriel n'a pas été trouvée dans la base de données." );
		
		// On ne veut pas ramener à la page d'accueil un utilisateur qui tente activement d'activer son compte
		if( !isset( $_POST['send'] ) ){
			header( "Location: ./index.php" );
			die();
		}
	}
	
	include "./views/top.php";
	include "./views/u/activate.php";
	include "./views/bottom.php";
?>