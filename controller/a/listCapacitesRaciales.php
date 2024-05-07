<?php
	$list_capacites_raciales = Dictionary::GetCapacitesRaciales( FALSE, FALSE );
	$list_races = Dictionary::GetRaces( FALSE );
	
	$capacite_raciale_repository = new CapaciteRacialeRepository();
	foreach( $list_capacites_raciales as $id => $nom ){
		$list_capacites_raciales[ $id ] = $capacite_raciale_repository->Find( $id );
	}
	
	if( isset( $_POST["add_capacite_raciale"] ) ){
		$capaciteRaciale = $capacite_raciale_repository->Create( array( "nom" => Security::FilterInput( $_POST["capacite_raciale_nom"] ), $_POST["capacite_raciale_race"] ) );
		
		header( "Location: ?s=admin&a=updateCapaciteRaciale&i=" . $capaciteRaciale->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listCapacitesRaciales.php";
	include "./views/bottom.php";
?>