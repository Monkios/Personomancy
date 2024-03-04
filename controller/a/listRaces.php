<?php
	$raceNoms = Dictionary::GetRaces( FALSE, FALSE );
	
	$raceRepository = new RaceRepository();
	$races = array();
	foreach( $raceNoms as $id => $nom ){
		$races[] = $raceRepository->Find( $id );
	}
	
	if( isset( $_POST["add_race"] ) ){
		$race = $raceRepository->Create( array( "nom" => utf8_decode( Security::FilterInput( $_POST["race_nom"] ) ) ) );
		
		header( "Location: ?s=admin&a=updateRace&i=" . $race->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listRaces.php";
	include "./views/bottom.php";
?>