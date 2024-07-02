<?php
	$sort_by = "character";
	$only_alives = FALSE;
	if( isset( $_GET[ "sort" ] ) && $_GET[ "sort" ] == "player" ){
		$sort_by = "player";
	}
	
	$personnage_repository = new PersonnageRepository();
	if( !isset( $_GET[ "alive" ] ) || $_GET[ "alive" ] == "y" ){
		$only_alives = TRUE;
		$chars = $personnage_repository->GetAllCharactersAlive( $sort_by );
	} else {
		$chars = $personnage_repository->GetAllCharacters( $sort_by );
	}
	
	include "./views/top.php";
	include "./views/g/characterList.php";
	include "./views/bottom.php";
?>