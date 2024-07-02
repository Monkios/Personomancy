<?php
	if( is_numeric( $_GET["i"] ) ){
		$croyance_repository = new CroyanceRepository();
		$croyance = $croyance_repository->Find( $_GET["i"] );
		
		if( isset( $_POST["croyance_id"] ) && $_GET["i"] == $_POST["croyance_id"] ){
			if( isset( $_POST["save_croyance"] ) ){
				$croyance->nom = Security::FilterInput( $_POST["croyance_nom"] );
				$croyance->description = Security::FilterInput( $_POST["croyance_description"] );
				$croyance->active = isset( $_POST["croyance_active"] );
				
				if( !$croyance_repository->Save( $croyance ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la croyance." );
				} else {
					Message::Notice( "Les informations de la croyance ont été mises à jour." );
				}
			}
			
			header( "Location: ?s=super&a=updateCroyance&i=" . $croyance->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de croyance doit être numérique." );
	}
	
	$nav_links = array( "Liste des croyances" => "?s=super&a=listCroyances" );
	
	include "./views/top.php";
	include "./views/s/updateCroyance.php";
	include "./views/bottom.php";
?>