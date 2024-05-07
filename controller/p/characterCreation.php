<?php
	$personnage_repository = new PersonnageRepository();
	
	$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
	if( !$joueur->IsAnimateur && $personnage_repository->GetAliveCountByPlayerId( $joueur->Id ) > 0  ){
		Message::Erreur( "Vous ne pouvez pas créer un personnage car vous en possédez déjà un." );
		header( "Location: index.php" );
		die();
	}
	
	$character_name = "";
	$character_cite_etat = 0;
	$character_croyance = 0;
	$character_race = 0;
	
	$list_cites_etats = Dictionary::GetCitesEtats();
	$list_croyances = Dictionary::GetCroyances();
	$list_races = Dictionary::GetRaces();
	
	$erreur = false;
	if( isset( $_POST ) && isset( $_POST['character_create'] ) ){
		// Valide que les informations recues sont bonnes
		if( empty( Security::FilterInput( $_POST['character_name'] ) ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez fournit aucun nom pour ce personnage." );
		} else {
			$character_name = Security::FilterInput( $_POST['character_name'] );
		}
		if( empty( $_POST['character_cite_etat'] ) || !is_numeric( $_POST['character_cite_etat'] ) || !array_key_exists( $_POST['character_cite_etat'], $list_cites_etats ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez choisit aucune cité-État pour ce personnage." );
		} else {
			$character_cite_etat = $_POST['character_cite_etat'];
		}
		if( empty( $_POST['character_croyance'] ) || !is_numeric( $_POST['character_croyance'] ) || !array_key_exists( $_POST['character_croyance'], $list_croyances ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez choisit aucune croyance pour ce personnage." );
		} else {
			$character_croyance = $_POST['character_croyance'];
		}
		if( empty( $_POST['character_race'] ) || !is_numeric( $_POST['character_race'] ) || !array_key_exists( $_POST['character_race'], $list_races ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez choisit aucune race pour ce personnage." );
		} else {
			$character_race = $_POST['character_race'];
		}
		
		// Si aucune erreur n'est trouvee, lance la creation du personnage
		if( !$erreur ){
			// Inserer le nouveau personnage
			$sheet = new CharacterSheet();
			$character = $sheet->Create( $joueur->Id, $character_name, $character_cite_etat, $character_croyance, $character_race );
			if( $character !== FALSE ){
				Message::Notice( "Création du personnage : ". $character_name );
				
				header( "Location: ?s=player&a=characterUpdate&c=" . $character->id );
				die();
			} else {
				Message::Erreur( "Une erreur s'est produite lors de l'enregistrement du personnage." );
			}
		}
	}
	
	unset( $joueur );
	
	include "./views/top.php";
	include "./views/p/characterCreation.php";
	include "./views/bottom.php";
?>