<?php
	$list_capacites_raciales = Dictionary::GetCapacitesRaciales( FALSE, FALSE );
	$list_races = Dictionary::GetRaces( FALSE );

	$list_choix_capacites = Dictionary::GetChoixCapacites( FALSE, FALSE );
	$list_choix_capacites_raciales = Dictionary::GetChoixCapacitesRaciales( FALSE, FALSE );
	$list_choix_connaissances = Dictionary::GetChoixConnaissances( FALSE, FALSE );
	$list_choix_voies = Dictionary::GetChoixVoies( FALSE, FALSE );
	
	$capacite_raciale_repository = new CapaciteRacialeRepository();
	foreach( $list_capacites_raciales as $id => $nom ){
		$list_capacites_raciales[ $id ] = $capacite_raciale_repository->Find( $id );
	}
	
	if( isset( $_POST["add_capacite_raciale"] ) ){
		$capacite_raciale = $capacite_raciale_repository->Create( array( "nom" => Security::FilterInput( $_POST["capacite_raciale_nom"] ), "race_id" => $_POST["capacite_raciale_race"] ) );
		
		header( "Location: ?s=super&a=updateCapaciteRaciale&i=" . $capacite_raciale->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/s/listCapacitesRaciales.php";
	include "./views/bottom.php";
?>