<?php
	if( is_numeric( $_GET["i"] ) ){
		$choix_capacite_repository = new ChoixCapaciteRepository();
		$choixCapacite = $choix_capacite_repository->Find( $_GET["i"] );
		$capacites = $choix_capacite_repository->GetCapacites( $choixCapacite );
		
		$list_capacites = Dictionary::GetCapacites();
		
		if( isset( $_POST["choix_capacite_id"] ) && $_GET["i"] == $_POST["choix_capacite_id"] ){
			if( isset( $_POST["save_choix_capacite"] ) ){
				$choixCapacite->nom = Security::FilterInput( $_POST["choix_capacite_nom"] );
				$choixCapacite->active = isset( $_POST["choix_capacite_active"] );
				
				// ...
				
				if( !$choix_capacite_repository->Save( $choixCapacite ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du choix de capacité." );
				} else {
					Message::Notice( "Les informations du choix de capacité ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_capacite"] ) ){
				if( is_numeric( $_POST["capacite"] ) &&
							is_numeric( $_POST["capacite"] ) &&
							!$choix_capacite_repository->AddCapacite( $choixCapacite, $_POST["capacite"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la capacité bonus au choix." );
				} else {
					Message::Notice( "La capacité a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_capacite"] ) ){
				if( !$choix_capacite_repository->RemoveCapacite( $choixCapacite, $_POST["delete_capacite"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la capacité bonus au choix." );
				} else {
					Message::Notice( "La capacité a été retirée." );
				}
			}
			
			header( "Location: ?s=super&a=updateChoixCapacite&i=" . $choixCapacite->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant du choix de capacité doit être numérique." );
	}
	
	$nav_links = array( "Liste des choix de capacités" => "?s=super&a=listChoixCapacites" );
	
	include "./views/top.php";
	include "./views/s/updateChoixCapacite.php";
	include "./views/bottom.php";
?>