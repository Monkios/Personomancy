<?php
	$email = "";
	$firstname = "";
	$lastname = "";
	
	if( isset( $_POST['send'] ) ){
		$email = isset( $_POST['email'] ) ? Security::FilterEmail( $_POST['email'] ) : "";
		$firstname = isset( $_POST['firstname'] ) ? Security::FilterInput( $_POST['firstname'] ) : "";
		$lastname = isset( $_POST['lastname'] ) ? Security::FilterInput( $_POST['lastname'] ) : "";

		if( $email != "" && $firstname != "" && $lastname != "" ){
			if( Community::GetPlayerByEMail( $email ) === FALSE ){
				if( Identity::Create( $email, $firstname, $lastname ) ){
					Message::Notice( "Votre compte a été créé avec succès. Un courriel d'activation vous a été envoyé." );
					
					header( "Location: ./index.php" );
					die();
				} else {
					Message::Erreur( "Une erreur s'est produite lors de la création de ce compte. Veuillez réessayer ou contacter l'animation." );
				}
			} else {
				Message::Erreur( "Cette adresse courriel est déjà associée à un compte." );
			}
		} else {
			Message::Erreur( "Tous les champs doivent être remplis." );
		}
	}

	include "./views/top.php";
	include "./views/u/register.php";
	include "./views/bottom.php";
?>