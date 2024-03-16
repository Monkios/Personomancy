<?php
	if( is_numeric( $_GET["i"] ) ){
		$race_repository = new RaceRepository();
		
		$race = $race_repository->Find( $_GET["i"] );
		$pouvoirs = Dictionary::GetPouvoirs();
		
		if( isset( $_POST["race_id"] ) && $_GET["i"] == $_POST["race_id"] ){
			if( isset( $_POST["save_race"] ) ){
				$race->nom = mb_convert_encoding( Security::FilterInput( $_POST["race_nom"] ), 'ISO-8859-1', 'UTF-8');
				$race->active = isset( $_POST["race_active"] );

				$race->base_alerte = $_POST["race_alerte"];
				$race->base_constitution = $_POST["race_constitution"];
				$race->base_spiritisme = $_POST["race_spiritisme"];
				$race->base_intelligence = $_POST["race_intelligence"];
				$race->base_vigueur = $_POST["race_vigueur"];
				$race->base_volonte = $_POST["race_volonte"];
				
				foreach( $_POST["capacite_raciale"] as $id => $cout ){
					$race->list_capacites_raciales[ $id ][ 1 ] = $cout;
				}
				
				if( !$race_repository->Save( $race ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la race." );
				} else {
					Message::Notice( "Les informations de la race ont été mises à jour." );
				}
			} elseif( isset( $_POST["delete_capacite_raciale"] ) ){
				if( !$race_repository->RemoveCapaciteRaciale( $race, $_POST["delete_capacite_raciale"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la capacité raciale de la race." );
				} else {
					Message::Notice( "La capacité raciale a été retirée." );
				}
			} elseif( isset( $_POST["add_capacite_raciale"] ) ){
				if( is_numeric( $_POST["capacite_raciale_pouvoir"] ) &&
							is_numeric( $_POST["capacite_raciale_cout"] ) &&
							!$race_repository->AddCapaciteRaciale( $race, $_POST["capacite_raciale_pouvoir"], $_POST["capacite_raciale_cout"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la capacité raciale de la race." );
				} else {
					Message::Notice( "La capacité raciale a été ajoutée." );
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