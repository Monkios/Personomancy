<?php
	if( is_numeric( $_GET["i"] ) ){
		$choix_capacite_raciale_repository = new ChoixCapaciteRacialeRepository();
		$choixCapaciteRaciale = $choix_capacite_raciale_repository->Find( $_GET["i"] );
		$capacites_raciales = $choix_capacite_raciale_repository->GetCapacitesRaciales( $choixCapaciteRaciale );
		
		$list_capacites_raciales = Dictionary::GetCapacitesRaciales();
		
		if( isset( $_POST["choix_capacite_raciale_id"] ) && $_GET["i"] == $_POST["choix_capacite_raciale_id"] ){
			if( isset( $_POST["save_choix_capacite_raciale"] ) ){
				$choixCapaciteRaciale->nom = Security::FilterInput( $_POST["choix_capacite_raciale_nom"] );
				$choixCapaciteRaciale->active = isset( $_POST["choix_capacite_raciale_active"] );
				
				// ...
				
				if( !$choix_capacite_raciale_repository->Save( $choixCapaciteRaciale ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du choix de capacite_raciale." );
				} else {
					Message::Notice( "Les informations du choix de capacite_raciale ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_capacite_raciale"] ) ){
				if( is_numeric( $_POST["capacite_raciale"] ) &&
							is_numeric( $_POST["capacite_raciale"] ) &&
							!$choix_capacite_raciale_repository->AddCapaciteRaciale( $choixCapaciteRaciale, $_POST["capacite_raciale"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la capacité raciale bonus au choix." );
				} else {
					Message::Notice( "La capacite_raciale a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_capacite_raciale"] ) ){
				if( !$choix_capacite_raciale_repository->RemoveCapaciteRaciale( $choixCapaciteRaciale, $_POST["delete_capacite_raciale"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la capacité raciale bonus au choix." );
				} else {
					Message::Notice( "La capacite_raciale a été retirée." );
				}
			}
			
			header( "Location: ?s=super&a=updateChoixCapaciteRaciale&i=" . $choixCapaciteRaciale->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant du choix de capacité raciale doit être numérique." );
	}
	
	$nav_links = array( "Liste des choix de capacité raciale" => "?s=super&a=listChoixCapacitesRaciales" );
	
	include "./views/top.php";
	include "./views/a/updateChoixCapaciteRaciale.php";
	include "./views/bottom.php";
?>