<?php
	$list_choix_capacites_raciales = Dictionary::GetChoixCapacitesRaciales( FALSE, FALSE );
	
	$choix_capacite_raciale_repository = new ChoixCapaciteRacialeRepository();
	foreach( $list_choix_capacites_raciales as $id => $nom ){
		$list_choix_capacites_raciales[ $id ] = $choix_capacite_raciale_repository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_capacite_raciale"] ) ){
		$choixCapaciteRaciale = $choix_capacite_raciale_repository->Create( array( "nom" => Security::FilterInput( $_POST["choix_capacite_raciale_nom"] ) ) );
		
		header( "Location: ?s=super&a=updateChoixCapaciteRaciale&i=" . $choixCapaciteRaciale->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/s/listChoixCapacitesRaciales.php";
	include "./views/bottom.php";
?>