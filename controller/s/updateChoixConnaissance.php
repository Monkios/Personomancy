<?php
	if( is_numeric( $_GET["i"] ) ){
		$choix_connaissance_repository = new ChoixConnaissanceRepository();
		$choixConnaissance = $choix_connaissance_repository->Find( $_GET["i"] );
		$connaissances = $choix_connaissance_repository->GetConnaissances( $choixConnaissance );
		
		$list_connaissances = Dictionary::GetConnaissances();
		
		if( isset( $_POST["choix_connaissance_id"] ) && $_GET["i"] == $_POST["choix_connaissance_id"] ){
			if( isset( $_POST["save_choix_connaissance"] ) ){
				$choixConnaissance->nom = Security::FilterInput( $_POST["choix_connaissance_nom"] );
				$choixConnaissance->active = isset( $_POST["choix_connaissance_active"] );
				
				// ...
				
				if( !$choix_connaissance_repository->Save( $choixConnaissance ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du choix de connaissance." );
				} else {
					Message::Notice( "Les informations du choix de connaissance ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_connaissance"] ) ){
				if( is_numeric( $_POST["connaissance"] ) &&
							is_numeric( $_POST["connaissance"] ) &&
							!$choix_connaissance_repository->AddConnaissance( $choixConnaissance, $_POST["connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la connaissance bonus au choix." );
				} else {
					Message::Notice( "La connaissance a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_connaissance"] ) ){
				if( !$choix_connaissance_repository->RemoveConnaissance( $choixConnaissance, $_POST["delete_connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la connaissance bonus au choix." );
				} else {
					Message::Notice( "La connaissance a été retirée." );
				}
			}
			
			header( "Location: ?s=super&a=updateChoixConnaissance&i=" . $choixConnaissance->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant du choix de connaissance doit être numérique." );
	}
	
	$nav_links = array( "Liste des choix de connaissances" => "?s=super&a=listChoixConnaissances" );
	
	include "./views/top.php";
	include "./views/s/updateChoixConnaissance.php";
	include "./views/bottom.php";
?>