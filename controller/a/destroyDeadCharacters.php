<?php
	$pr = new PersonnageRepository();
	$undeads = $pr->FindAllDeads();
	
	$cr = new CharacterSheet();
	
	if( isset( $_POST[ "destroy_character" ] ) ){
		if( isset( $_POST[ "character_id" ] ) && count( $_POST[ "character_id" ] ) > 0 ){
			foreach( $_POST[ "character_id" ] as $dead_id ){
				if( is_numeric( $dead_id ) ){
					$dead = $cr->Load( $dead_id );
					if( $cr->Destroy( $dead->id ) ){
						Message::Notice( "Le personnage " . utf8_encode( $undeads[ $dead_id ]->nom ) . " a été détruit." );
						unset( $undeads[ $dead_id ] );
					}
				} else {
					Message::Erreur( "Identifiant de personnage invalide." );
				}
			}
		} else {
			Message::Notice( "Veuillez sélectionner au moins un personnage." );
		}
	}
	
	include "./views/top.php";
	include "./views/a/destroyDeadCharacters.php";
	include "./views/bottom.php";
?>