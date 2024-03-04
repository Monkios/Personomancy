<?php
	if( is_numeric( $_GET["i"] ) ){
		$choixConnaissanceRepository = new ChoixConnaissanceRepository();
		$choixConnaissance = $choixConnaissanceRepository->Find( $_GET["i"] );
		$connaissances = $choixConnaissanceRepository->GetConnaissances( $choixConnaissance );
		
		$list_connaissances = Dictionary::GetConnaissances();
		
		if( isset( $_POST["choix_connaissance_id"] ) && $_GET["i"] == $_POST["choix_connaissance_id"] ){
			if( isset( $_POST["save_choix_connaissance"] ) ){
				$choixConnaissance->nom = utf8_decode( Security::FilterInput( $_POST["choix_connaissance_nom"] ) );
				$choixConnaissance->active = isset( $_POST["choix_connaissance_active"] );
				
				// ...
				
				if( !$choixConnaissanceRepository->Save( $choixConnaissance ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du choix de connaissance." );
				} else {
					Message::Notice( "Les informations du choix de connaissance ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_connaissance"] ) ){
				if( is_numeric( $_POST["connaissance"] ) &&
							is_numeric( $_POST["connaissance"] ) &&
							!$choixConnaissanceRepository->AddConnaissance( $choixConnaissance, $_POST["connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la connaissance au choix de connaissances." );
				} else {
					Message::Notice( "La connaissance a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_connaissance"] ) ){
				if( !$choixConnaissanceRepository->RemoveConnaissance( $choixConnaissance, $_POST["delete_connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la connaissance au choix de connaissances." );
				} else {
					Message::Notice( "La connaissance a été retirée." );
				}
			}
			
			header( "Location: ?s=admin&a=updateChoixConnaissance&i=" . $choixConnaissance->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant du choix de connaissance doit être numérique." );
	}
	
	$nav_links = array( "Liste des choix de connaissances" => "?s=admin&a=listChoixConnaissances" );
	
	include "./views/top.php";
	include "./views/a/updateChoixConnaissance.php";
	include "./views/bottom.php";
?>