<?php
	$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
	
	if( isset( $_POST['send'] ) ){
		$identity = new Identity( $joueur->Id );
		
		$new_firstname = $new_lastname = "";
		if( isset( $_POST['firstname'] ) && $_POST['firstname'] !== $joueur->FirstName ){
			// Prepare firstname
			$new_firstname = mb_convert_encoding( Security::FilterInput( $_POST['firstname'] ), 'ISO-8859-1', 'UTF-8');
		}
		
		if( isset( $_POST['lastname'] ) && $_POST['lastname'] !== $joueur->LastName ){
			// Prepare lastname
			$new_lastname = mb_convert_encoding( Security::FilterInput( $_POST['lastname'] ), 'ISO-8859-1', 'UTF-8');
		}
		
		if( $new_firstname != "" || $new_lastname != "" ){
			// Make the changes
			if( $identity->ChangeNameTo( $new_firstname, $new_lastname ) ){
				Message::Notice( "Le nom du joueur a été changé." );
			} else {
				Message::Erreur( "Une erreur s'est produite lors du changement d'adresse courriel." );
			}
		}
		
		if( isset( $_POST['email'] ) && $_POST['email'] !== $joueur->Email ){
			// Set new email
			if( $identity->ChangeEmailTo( mb_convert_encoding( Security::FilterEmail( $_POST['email'] ), 'ISO-8859-1', 'UTF-8') ) ){
				Message::Notice( "L'adresse courriel du joueur a été changée." );
			} else {
				Message::Erreur( "Une erreur s'est produite lors du changement d'adresse courriel." );
			}
		}
		
		if( isset( $_POST['password'] ) && $_POST['password'] != "" ){
			// Set new password
			$identity->ChangePasswordTo( mb_convert_encoding( Security::FilterInput( $_POST['password'] ), 'ISO-8859-1', 'UTF-8') ) ;
			
			// De-activate player
			if( $identity->SetPlayerAccess( Identity::IS_ACTIVE, FALSE ) &&
					$identity->SendValidationEmail() ){
				Message::Notice( "Le mot de passe du joueur a été changé." );
			}
		}
		
		$joueur = Community::GetPlayer( $joueur->Id );
		$_SESSION[ SESSION_KEY ][ "User" ] = $joueur;
	}
	
	include "./views/top.php";
	include "./views/u/profile.php";
	include "./views/bottom.php";
?>