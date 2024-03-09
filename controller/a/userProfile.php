<?php
	if( isset( $_GET['u'] ) && is_numeric( $_GET['u'] ) ){
		$player = Community::GetPlayer( $_GET['u'] );
		
		if( $player !== FALSE ){
			$identity = new Identity( $player->Id );
			
			$reload = FALSE;
			if( isset( $_POST['send'] ) ){
				$new_firstname = $new_lastname = "";
				if( isset( $_POST['firstname'] ) && $_POST['firstname'] !== $player->FirstName ){
					// Prepare firstname
					$new_firstname = Security::FilterInput( mb_convert_encoding( $_POST['firstname'], 'ISO-8859-1', 'UTF-8') );
				}
				
				if( isset( $_POST['lastname'] ) && $_POST['lastname'] !== $player->LastName ){
					// Prepare lastname
					$new_lastname = Security::FilterInput( mb_convert_encoding( $_POST['lastname'], 'ISO-8859-1', 'UTF-8') );
				}
				
				if( $new_firstname != "" || $new_lastname != "" ){
					// Make the changes
					if( $identity->ChangeNameTo( $new_firstname, $new_lastname ) ){
						Message::Notice( "Le nom du joueur a été changé." );
						$reload = TRUE;
					} else {
						Message::Erreur( "Une erreur s'est produite lors du changement d'adresse courriel." );
					}
				}
				
				if( isset( $_POST['email'] ) && $_POST['email'] !== $player->Email ){
					// Set new email
					if( $identity->ChangeEmailTo( Security::FilterEmail( $_POST['email'] ) ) ){				
						Message::Notice( "L'adresse courriel du joueur a été changée." );
						$reload = TRUE;
					} else {
						Message::Erreur( "Une erreur s'est produite lors du changement d'adresse courriel." );
					}
				}
			} else if( isset( $_POST['force_activation'] ) ){
				if( $identity->SetPlayerAccess( Identity::IS_ACTIVE, FALSE ) &&
						$identity->SendValidationEmail() ){
					Message::Notice( "Un courriel d'activation a été envoyé à ce joueur." );
					$reload = TRUE;
				}
			} else if( isset( $_POST['change_password'] ) ){
				if( $identity->SendForgotEmail() ){
					Message::Notice( "Un mot de passe temporaire a été envoyé à ce joueur." );
					$reload = TRUE;
				}
			} else if( isset( $_POST['add_passe_saison'] ) && !$player->PasseSaison ){
				if( $identity->ChangePasseSaison( true ) ){
					Message::Notice( "La passe de saison a été ajoutée à ce joueur." );
					$reload = TRUE;
				} else {
					Message::Erreur( "Une erreur s'est produite lors de l'ajout de la passe saison." );
				}
			} else if( isset( $_POST['remove_passe_saison'] ) && $player->PasseSaison ){
				if( $identity->ChangePasseSaison( false ) ){
					Message::Notice( "La passe de saison a été retirée de ce joueur." );
					$reload = TRUE;
				} else {
					Message::Erreur( "Une erreur s'est produite lors du retrait de la passe saison." );
				}
			}
			
			if( $reload ){
				$player = Community::GetPlayer( $player->Id );
			}
		} else {
			Message::Erreur( "Le joueur demandé n'existe pas." );
		}
	} else {
		Message::Fatale( "L'identifiant de joueur reçu est invalide.", $_GET['u'] );
	}
	
	include "./views/top.php";
	include "./views/a/userProfile.php";
	include "./views/bottom.php";
?>