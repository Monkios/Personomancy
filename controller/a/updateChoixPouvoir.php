<?php
	if( is_numeric( $_GET["i"] ) ){
		$choixPouvoirRepository = new ChoixPouvoirRepository();
		$choixPouvoir = $choixPouvoirRepository->Find( $_GET["i"] );
		$pouvoirs = $choixPouvoirRepository->GetPouvoirs( $choixPouvoir );
		
		$list_pouvoirs = Dictionary::GetPouvoirs();
		
		if( isset( $_POST["choix_pouvoir_id"] ) && $_GET["i"] == $_POST["choix_pouvoir_id"] ){
			if( isset( $_POST["save_choix_pouvoir"] ) ){
				$choixPouvoir->nom = utf8_decode( Security::FilterInput( $_POST["choix_pouvoir_nom"] ) );
				$choixPouvoir->active = isset( $_POST["choix_pouvoir_active"] );
				
				// ...
				
				if( !$choixPouvoirRepository->Save( $choixPouvoir ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du choix de pouvoirs." );
				} else {
					Message::Notice( "Les informations du choix de pouvoirs ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_pouvoir"] ) ){
				if( is_numeric( $_POST["pouvoir"] ) &&
							is_numeric( $_POST["pouvoir"] ) &&
							!$choixPouvoirRepository->AddPouvoir( $choixPouvoir, $_POST["pouvoir"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant le pouvoir au choix de pouvoirs." );
				} else {
					Message::Notice( "Le pouvoir a été ajouté." );
				}
			} elseif( isset( $_POST["delete_pouvoir"] ) ){
				if( !$choixPouvoirRepository->RemovePouvoir( $choixPouvoir, $_POST["delete_pouvoir"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant le pouvoir au choix de pouvoirs." );
				} else {
					Message::Notice( "Le pouvoir a été retiré." );
				}
			}
			
			header( "Location: ?s=admin&a=updateChoixPouvoir&i=" . $choixPouvoir->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant du choix de pouvoirs doit être numérique." );
	}
	
	$nav_links = array( "Liste des choix de pouvoirs" => "?s=admin&a=listChoixPouvoirs" );
	
	include "./views/top.php";
	include "./views/a/updateChoixPouvoir.php";
	include "./views/bottom.php";
?>