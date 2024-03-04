<?php
	if( is_numeric( $_GET["i"] ) ){
		$connaissanceRepository = new ConnaissanceRepository();
		
		$list_capacites = Dictionary::GetCapacites();
		$list_connaissances = Dictionary::GetConnaissances();
		$list_religions = Dictionary::GetReligions();
		$list_statistiques = Dictionary::GetStatistiques();
		$list_voies = Dictionary::GetVoies();
		
		$connaissance = $connaissanceRepository->Find( $_GET["i"] );
		
		if( isset( $_POST["connaissance_id"] ) && $_GET["i"] == $_POST["connaissance_id"] ){
			if( isset( $_POST["save_connaissance"] ) ){
				$connaissance->nom = utf8_decode( Security::FilterInput( $_POST["connaissance_nom"] ) );
				$connaissance->active = isset( $_POST["connaissance_active"] );
				
				$connaissance->prereq_statistique_prim_id = ( array_key_exists( $_POST["connaissance_statistique_prim_id"], $list_statistiques ) ? $_POST["connaissance_statistique_prim_id"] : 0 );
				$connaissance->prereq_statistique_prim_sel = is_numeric( $_POST["connaissance_statistique_prim_sel"] ) ? $_POST["connaissance_statistique_prim_sel"] : 0;
				
				$connaissance->prereq_statistique_sec_id = array_key_exists( $_POST["connaissance_statistique_sec_id"], $list_statistiques ) ? $_POST["connaissance_statistique_sec_id"] : 0;
				$connaissance->prereq_statistique_sec_sel = is_numeric( $_POST["connaissance_statistique_sec_sel"] ) ? $_POST["connaissance_statistique_sec_sel"] : 0;
				
				$connaissance->prereq_capacite_prim_id = array_key_exists( $_POST["connaissance_capacite_prim_id"], $list_capacites ) ? $_POST["connaissance_capacite_prim_id"] : 0;
				$connaissance->prereq_capacite_prim_sel = is_numeric( $_POST["connaissance_capacite_prim_sel"] ) ? $_POST["connaissance_capacite_prim_sel"] : 0;
				
				$connaissance->prereq_capacite_sec_id = array_key_exists( $_POST["connaissance_capacite_sec_id"], $list_capacites ) ? $_POST["connaissance_capacite_sec_id"] : 0;
				$connaissance->prereq_capacite_sec_sel = is_numeric( $_POST["connaissance_capacite_sec_sel"] ) ? $_POST["connaissance_capacite_sec_sel"] : 0;
				
				$connaissance->prereq_connaissance_prim_id = array_key_exists( $_POST["connaissance_connaissance_prim_id"], $list_connaissances ) ? $_POST["connaissance_connaissance_prim_id"] : 0;
				$connaissance->prereq_connaissance_sec_id = array_key_exists( $_POST["connaissance_connaissance_sec_id"], $list_connaissances ) ? $_POST["connaissance_connaissance_sec_id"] : 0;
				$connaissance->prereq_voie_id = array_key_exists( $_POST["connaissance_voie_id"], $list_voies ) ? $_POST["connaissance_voie_id"] : 0;
				$connaissance->prereq_divin_id = array_key_exists( $_POST["connaissance_divin_id"], $list_religions ) ? $_POST["connaissance_divin_id"] : 0;
				
				// ...
				
				if( !$connaissanceRepository->Save( $connaissance ) ){
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