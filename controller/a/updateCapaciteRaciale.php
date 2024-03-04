<?php
	if( is_numeric( $_GET["i"] ) ){
		$capaciteRacialeRepository = new PouvoirRepository();
		
		$capaciteRaciale = $capaciteRacialeRepository->Find( $_GET["i"] );
		
		$list_capacites = Dictionary::GetCapacites();
		$list_connaissances = Dictionary::GetConnaissances();
		$list_voies = Dictionary::GetVoies();
		
		$list_choix_capacites = Dictionary::GetChoixCapacites();
		$list_choix_connaissances = Dictionary::GetChoixConnaissances();
		$list_choix_pouvoirs = Dictionary::GetChoixPouvoirs();
		
		if( isset( $_POST["capacite_raciale_id"] ) && $_GET["i"] == $_POST["capacite_raciale_id"] ){
			if( isset( $_POST["save_capacite_raciale"] ) ){
				$capaciteRaciale->nom = utf8_decode( Security::FilterInput( $_POST["capacite_raciale_nom"] ) );
				$capaciteRaciale->active = isset( $_POST["capacite_raciale_active"] );
				$capaciteRaciale->affiche = isset( $_POST["capacite_raciale_affiche"] );
				
				$capaciteRaciale->bonus_alerte = $_POST["capacite_raciale_bonus_alerte"];
				$capaciteRaciale->bonus_constitution = $_POST["capacite_raciale_bonus_constitution"];
				$capaciteRaciale->bonus_intelligence = $_POST["capacite_raciale_bonus_intelligence"];
				$capaciteRaciale->bonus_spiritisme = $_POST["capacite_raciale_bonus_spiritisme"];
				$capaciteRaciale->bonus_vigueur = $_POST["capacite_raciale_bonus_vigueur"];
				$capaciteRaciale->bonus_volonte = $_POST["capacite_raciale_bonus_volonte"];
				
				// ...
				
				if( !$capaciteRacialeRepository->Save( $capaciteRaciale ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la capacité raciale." );
				} else {
					Message::Notice( "Les informations de la capacité raciale ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_capacite"] ) ){
				if( is_numeric( $_POST["capacite"] ) &&
							is_numeric( $_POST["capacite"] ) &&
							!$capaciteRacialeRepository->AddBonusCapacite( $capaciteRaciale, $_POST["capacite"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la capacité à la capacité raciale." );
				} else {
					Message::Notice( "La capacité a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_capacite"] ) ){
				if( !$capaciteRacialeRepository->RemoveBonusCapacite( $capaciteRaciale, $_POST["delete_capacite"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la capacité de la capacité raciale." );
				} else {
					Message::Notice( "La capacité a été retirée." );
				}
			} elseif( isset( $_POST["add_connaissance"] ) ){
				if( is_numeric( $_POST["connaissance"] ) &&
							is_numeric( $_POST["connaissance"] ) &&
							!$capaciteRacialeRepository->AddBonusConnaissance( $capaciteRaciale, $_POST["connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la connaissance à la capacité raciale." );
				} else {
					Message::Notice( "La connaissance a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_connaissance"] ) ){
				if( !$capaciteRacialeRepository->RemoveBonusConnaissance( $capaciteRaciale, $_POST["delete_connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la connaissance de la capacité raciale." );
				} else {
					Message::Notice( "La connaissance a été retirée." );
				}
			} elseif( isset( $_POST["add_voie"] ) ){
				if( is_numeric( $_POST["voie"] ) &&
							is_numeric( $_POST["voie"] ) &&
							!$capaciteRacialeRepository->AddBonusVoie( $capaciteRaciale, $_POST["voie"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la voie à la capacité raciale." );
				} else {
					Message::Notice( "La voie a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_voie"] ) ){
				if( !$capaciteRacialeRepository->RemoveBonusVoie( $capaciteRaciale, $_POST["delete_voie"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la voie de la capacité raciale." );
				} else {
					Message::Notice( "La voie a été retirée." );
				}
			} elseif( isset( $_POST["add_choix_capacite"] ) ){
				if( is_numeric( $_POST["choix_capacite"] ) &&
							is_numeric( $_POST["choix_capacite"] ) &&
							!$capaciteRacialeRepository->AddChoixCapacite( $capaciteRaciale, $_POST["choix_capacite"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant le choix de capacité à la capacité raciale." );
				} else {
					Message::Notice( "Le choix de capacité a été ajouté." );
				}
			} elseif( isset( $_POST["delete_choix_capacite"] ) ){
				if( !$capaciteRacialeRepository->RemoveChoixCapacite( $capaciteRaciale, $_POST["delete_choix_capacite"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant le choix de capacité de la capacité raciale." );
				} else {
					Message::Notice( "Le choix de capacité a été retiré." );
				}
			} elseif( isset( $_POST["add_choix_connaissance"] ) ){
				if( is_numeric( $_POST["choix_connaissance"] ) &&
							is_numeric( $_POST["choix_connaissance"] ) &&
							!$capaciteRacialeRepository->AddChoixConnaissance( $capaciteRaciale, $_POST["choix_connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant le choix de capacité à la capacité raciale." );
				} else {
					Message::Notice( "Le choix de capacité a été ajouté." );
				}
			} elseif( isset( $_POST["delete_choix_connaissance"] ) ){
				if( !$capaciteRacialeRepository->RemoveChoixConnaissance( $capaciteRaciale, $_POST["delete_choix_connaissance"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant le choix de capacité de la capacité raciale." );
				} else {
					Message::Notice( "Le choix de capacité a été retiré." );
				}
			} elseif( isset( $_POST["add_choix_pouvoir"] ) ){
				if( is_numeric( $_POST["choix_pouvoir"] ) &&
							is_numeric( $_POST["choix_pouvoir"] ) &&
							!$capaciteRacialeRepository->AddChoixPouvoir( $capaciteRaciale, $_POST["choix_pouvoir"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant le choix de capacité à la capacité raciale." );
				} else {
					Message::Notice( "Le choix de capacité a été ajouté." );
				}
			} elseif( isset( $_POST["delete_choix_pouvoir"] ) ){
				if( !$capaciteRacialeRepository->RemoveChoixPouvoir( $capaciteRaciale, $_POST["delete_choix_pouvoir"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant le choix de capacité de la capacité raciale." );
				} else {
					Message::Notice( "Le choix de capacité a été retiré." );
				}
			}
			
			header( "Location: ?s=admin&a=updateCapaciteRaciale&i=" . $capaciteRaciale->id );
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