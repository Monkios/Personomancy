<?php
	if( is_numeric( $_GET["i"] ) ){
		$connaissance_repository = new ConnaissanceRepository();
		
		$list_capacites = Dictionary::GetCapacites();
		$list_voies = Dictionary::GetVoies();
		
		$connaissance = $connaissance_repository->Find( $_GET["i"] );
		
		if( isset( $_POST["connaissance_id"] ) && $_GET["i"] == $_POST["connaissance_id"] ){
			if( isset( $_POST["save_connaissance"] ) &&
					( empty( $_POST[ "connaissance_prereq_capacite" ] ) || array_key_exists( $_POST["connaissance_prereq_capacite"], $list_capacites ) ) &&
					array_key_exists( $_POST["connaissance_prereq_voie_primaire"], $list_voies ) &&
					( empty( $_POST[ "connaissance_prereq_voie_secondaire" ] ) || array_key_exists( $_POST["connaissance_prereq_voie_secondaire"], $list_voies ) ) ){
				$connaissance->nom = Security::FilterInput( $_POST["connaissance_nom"] );
				$connaissance->description = Security::FilterInput( $_POST["connaissance_description"] );
				$connaissance->cout = is_numeric( $_POST["connaissance_cout"] ) ? $_POST["connaissance_cout"] : 0;
				$connaissance->active = isset( $_POST["connaissance_active"] );
				
				$connaissance->prereq_capacite = array_key_exists( $_POST["connaissance_prereq_capacite"], $list_capacites ) ? $_POST["connaissance_prereq_capacite"] : null;
				$connaissance->prereq_voie_primaire = array_key_exists( $_POST["connaissance_prereq_voie_primaire"], $list_voies ) ? $_POST["connaissance_prereq_voie_primaire"] : null;
				$connaissance->prereq_voie_secondaire = array_key_exists( $_POST["connaissance_prereq_voie_secondaire"], $list_voies ) ? $_POST["connaissance_prereq_voie_secondaire"] : null;
				
				// ...
				
				if( !$connaissance_repository->Save( $connaissance ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la connaissance." );
				} else {
					Message::Notice( "Les informations de la connaissance ont été mises à jour." );
				}
			}
			
			header( "Location: ?s=admin&a=updateConnaissance&i=" . $connaissance->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de connaissance doit être numérique." );
	}
	
	$nav_links = array( "Liste des connaissances" => "?s=admin&a=listConnaissances" );
	
	include "./views/top.php";
	include "./views/a/updateConnaissance.php";
	include "./views/bottom.php";
?>