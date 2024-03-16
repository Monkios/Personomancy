<?php
	$list_capacites_raciales = Dictionary::GetPouvoirs( FALSE, FALSE );
	
	$capacite_raciale_repository = new PouvoirRepository();
	foreach( $list_capacites_raciales as $id => $nom ){
		$list_capacites_raciales[ $id ] = $capacite_raciale_repository->Find( $id );
	}
	
	if( isset( $_POST["add_capacite_raciale"] ) ){
		$capaciteRaciale = $capacite_raciale_repository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["capacite_raciale_nom"] ), 'ISO-8859-1', 'UTF-8') ) );
		
		header( "Location: ?s=admin&a=updateCapaciteRaciale&i=" . $capaciteRaciale->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listCapacitesRaciales.php";
	include "./views/bottom.php";
?>