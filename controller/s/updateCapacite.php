<?php
	if( is_numeric( $_GET["i"] ) ){
		$capacite_repository = new CapaciteRepository();
		
		$capacite = $capacite_repository->Find( $_GET["i"] );
		
		$list_voies = Dictionary::GetVoies();
		
		if( isset( $_POST["capacite_id"] ) && $_GET["i"] == $_POST["capacite_id"] ){
			if( isset( $_POST["save_capacite"] ) && array_key_exists( $_POST["capacite_voie"], $list_voies ) ){
				$capacite->nom = Security::FilterInput( $_POST["capacite_nom"] );
				$capacite->description = Security::FilterInput( $_POST["capacite_description"] );
				$capacite->active = isset( $_POST["capacite_active"] );
				$capacite->voie_id = $_POST["capacite_voie"];
				
				if( !$capacite_repository->Save( $capacite ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la capacité." );
				} else {
					Message::Notice( "Les informations de la capacité ont été mises à jour." );
				}
			}
			
			header( "Location: ?s=super&a=updateCapacite&i=" . $capacite->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de capacité doit être numérique." );
	}
	
	$nav_links = array( "Liste des capacités" => "?s=super&a=listCapacites" );
	
	include "./views/top.php";
	include "./views/s/updateCapacite.php";
	include "./views/bottom.php";
?>