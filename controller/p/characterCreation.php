<?php
	$pr = new PersonnageRepository();
	
	$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
	if( !$joueur->IsAnimateur && $pr->GetAliveCountByPlayerId( $joueur->Id ) > 0  ){
		Message::Erreur( "Vous ne pouvez pas créer un personnage car vous en possédez déjà un." );
		header( "Location: index.php" );
		die();
	}
	
	$character_name = "";
	$character_alignment = 0;
	$character_race = 0;
	$character_religion = 0;
	
	$list_alignements = Dictionary::GetAlignements();
	$list_factions = Dictionary::GetFactions();
	$list_races = Dictionary::GetRaces();
	$list_religions = Dictionary::GetReligions();
	
	$erreur = false;
	if( isset( $_POST ) && isset( $_POST['character_create'] ) ){
		// Valide que les informations recues sont bonnes
		if( empty( Security::FilterInput( $_POST['character_name'] ) ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez fournit aucun nom pour ce personnage." );
		} else {
			$character_name = Security::FilterInput( $_POST['character_name'] );
		}
		if( empty( $_POST['character_alignment'] ) || !is_numeric( $_POST['character_alignment'] ) || !array_key_exists( $_POST['character_alignment'], $list_alignements ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez choisit aucun alignement pour ce personnage." );
		} else {
			$character_alignment = $_POST['character_alignment'];
		}
		if( empty( $_POST['character_faction'] ) || !is_numeric( $_POST['character_faction'] ) || !array_key_exists( $_POST['character_faction'], $list_factions ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez choisit aucune faction pour ce personnage." );
		} else {
			$character_faction = $_POST['character_faction'];
		}
		if( empty( $_POST['character_religion'] ) || !is_numeric( $_POST['character_religion'] ) || !array_key_exists( $_POST['character_religion'], $list_religions ) ){
			$erreur = true;
			Message::Erreur( "Vous n'avez choisit aucune religion pour ce personnage." );
		} else {
			$character_religion = $_POST['character_religion'];
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
			$character = $sheet->Create( $joueur->Id, mb_convert_encoding( $character_name, 'ISO-8859-1', 'UTF-8'), $character_alignment, $character_faction, $character_religion, $character_race );
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