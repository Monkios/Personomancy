<?php
	if( is_numeric( $_GET["i"] ) ){
		$capacite_raciale_repository = new CapaciteRacialeRepository();
		
		$capacite_raciale = $capacite_raciale_repository->Find( $_GET["i"] );
		
		$list_races = Dictionary::GetRaces( FALSE );
		$list_choix_capacites = Dictionary::GetChoixCapacites();
		$list_choix_connaissances = Dictionary::GetChoixConnaissances();
		$list_choix_capacites_raciales = Dictionary::GetChoixCapacitesRaciales();
		$list_choix_voies = Dictionary::GetChoixVoies();
		
		if( isset( $_POST["capacite_raciale_id"] ) && $_GET["i"] == $_POST["capacite_raciale_id"] ){
			if( isset( $_POST["save_capacite_raciale"] ) ){
				$capacite_raciale->nom = Security::FilterInput( $_POST["capacite_raciale_nom"] );
				$capacite_raciale->description = Security::FilterInput( $_POST["capacite_raciale_description"] );
				$capacite_raciale->active = isset( $_POST["capacite_raciale_active"] );

				if( !isset( $_POST["capacite_raciale_race_id"] ) || !array_key_exists( $_POST[ "capacite_raciale_race_id"], $list_races ) ){
					Message::Erreur( "Aucun identifiant de race n'a été fournit ou ce dernier est invalide." );
				} else if( !isset( $_POST["capacite_raciale_cout"] ) || !is_numeric( $_POST[ "capacite_raciale_cout"] ) || $_POST[ "capacite_raciale_cout"] < 0 || $_POST[ "capacite_raciale_cout"] > 4 ){
					Message::Erreur( "Le coût de la capacité raciale doit être un nombre entre 0 et 4." );
				} else {
					$capacite_raciale->race_id = $_POST["capacite_raciale_race_id"];
					$capacite_raciale->cout = $_POST["capacite_raciale_cout" ];

					if( isset( $_POST["capacite_raciale_bonus_capacite"] ) && array_key_exists( $_POST["capacite_raciale_bonus_capacite"], $list_choix_capacites) ){
						$capacite_raciale->choix_capacite_bonus_id = $_POST["capacite_raciale_bonus_capacite"];
					} else {
						$capacite_raciale->choix_capacite_bonus_id = null;
					}
					if( isset( $_POST["capacite_raciale_bonus_connaissance"] ) && array_key_exists( $_POST["capacite_raciale_bonus_connaissance"], $list_choix_connaissances) ){
						$capacite_raciale->choix_connaissance_bonus_id = $_POST["capacite_raciale_bonus_connaissance"];
					} else {
						$capacite_raciale->choix_connaissance_bonus_id = null;
					}
					if( isset( $_POST["capacite_raciale_bonus_capacite_raciale"] ) && array_key_exists( $_POST["capacite_raciale_bonus_capacite_raciale"], $list_choix_capacites_raciales) ){
						$capacite_raciale->choix_capacite_raciale_bonus_id = $_POST["capacite_raciale_bonus_capacite_raciale"];
					} else {
						$capacite_raciale->choix_capacite_raciale_bonus_id = null;
					}
					if( isset( $_POST["capacite_raciale_bonus_voie"] ) && array_key_exists( $_POST["capacite_raciale_bonus_voie"], $list_choix_voies) ){
						$capacite_raciale->choix_voie_bonus_id = $_POST["capacite_raciale_bonus_voie"];
					} else {
						$capacite_raciale->choix_voie_bonus_id = null;
					}
					
					if( !$capacite_raciale_repository->Save( $capacite_raciale ) ){
						Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la capacité raciale." );
					} else {
						Message::Notice( "Les informations de la capacité raciale ont été mises à jour." );
					}
				}
			}
			
			header( "Location: ?s=admin&a=updateCapaciteRaciale&i=" . $capacite_raciale->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de capacité raciale doit être numérique." );
	}
	
	$nav_links = array( "Liste des capacités raciales" => "?s=admin&a=listCapacitesRaciales" );
	
	include "./views/top.php";
	include "./views/a/updateCapaciteRaciale.php";
	include "./views/bottom.php";
?>