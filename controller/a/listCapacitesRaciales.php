<?php
	$capaciteRacialeNoms = Dictionary::GetPouvoirs( FALSE, FALSE );
	
	$capaciteRacialeRepository = new PouvoirRepository();
	$capacitesRaciales = array();
	foreach( $capaciteRacialeNoms as $id => $nom ){
		$capacitesRaciales[] = $capaciteRacialeRepository->Find( $id );
	}
	
	if( isset( $_POST["add_capacite_raciale"] ) ){
		$capaciteRaciale = $capaciteRacialeRepository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["capacite_raciale_nom"] ), 'ISO-8859-1', 'UTF-8') ) );
		
		header( "Location: ?s=admin&a=updateCapaciteRaciale&i=" . $capaciteRaciale->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listCapacitesRaciales.php";
	include "./views/bottom.php";
?>