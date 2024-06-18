<?php
	if( is_numeric( $_GET["i"] ) ){
		$voie_repository = new VoieRepository();
		$voie = $voie_repository->Find( $_GET["i"] );
		
		if( isset( $_POST["voie_id"] ) && $_GET["i"] == $_POST["voie_id"] ){
			if( isset( $_POST["save_voie"] ) ){
				$voie->nom = Security::FilterInput( $_POST["voie_nom"] );
				$voie->description = Security::FilterInput( $_POST["voie_description"] );
				$voie->active = isset( $_POST["voie_active"] );
				
				if( !$voie_repository->Save( $voie ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la voie." );
				} else {
					Message::Notice( "Les informations de la voie ont été mises à jour." );
				}
			}
			
			header( "Location: ?s=admin&a=updateVoie&i=" . $voie->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de voie doit être numérique." );
	}
	
	$nav_links = array( "Liste des voies" => "?s=admin&a=listVoies" );
	
	include "./views/top.php";
	include "./views/a/updateVoie.php";
	include "./views/bottom.php";
?>