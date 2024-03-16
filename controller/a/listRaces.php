<?php
	$list_races = Dictionary::GetRaces( FALSE, FALSE );
	
	$race_repository = new RaceRepository();
	foreach( $list_races as $id => $nom ){
		$list_races[ $id ] = $race_repository->Find( $id );
	}
	
	if( isset( $_POST["add_race"] ) ){
		$race = $race_repository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["race_nom"] ), 'ISO-8859-1', 'UTF-8') ) );
		
		header( "Location: ?s=admin&a=updateRace&i=" . $race->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listRaces.php";
	include "./views/bottom.php";
?>