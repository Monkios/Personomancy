<?php
	if( is_numeric( $_GET["i"] ) ){
		$race_repository = new RaceRepository();
		
		$race = $race_repository->Find( $_GET["i"] );
		
		if( isset( $_POST["race_id"] ) && $_GET["i"] == $_POST["race_id"] ){
			if( isset( $_POST["save_race"] ) ){
				$race->nom = Security::FilterInput( $_POST["race_nom"] );
				$race->description = Security::FilterInput( $_POST["race_description"] );
				$race->active = isset( $_POST["race_active"] );
				
				if( !$race_repository->Save( $race ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la race." );
				} else {
					Message::Notice( "Les informations de la race ont été mises à jour." );
				}			
			}
			header( "Location: ?s=admin&a=updateRace&i=" . $race->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de race doit être numérique." );
	}
	
	$nav_links = array( "Liste des races" => "?s=admin&a=listRaces" );
	
	include "./views/top.php";
	include "./views/a/updateRaces.php";
	include "./views/bottom.php";
?>