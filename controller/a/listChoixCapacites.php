<?php
	$list_choix_capacites = Dictionary::GetChoixCapacites( FALSE, FALSE );
	
	$choixCapaciteRepository = new ChoixCapaciteRepository();
	$choixCapacites = array();
	foreach( $list_choix_capacites as $id => $nom ){
		$choixCapacites[] = $choixCapaciteRepository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_capacite"] ) ){
		$choixCapacite = $choixCapaciteRepository->Create( array( "nom" => utf8_decode( Security::FilterInput( $_POST["choix_capacite_nom"] ) ) ) );
		
		header( "Location: ?s=admin&a=updateChoixCapacite&i=" . $choixCapacite->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listChoixCapacites.php";
	include "./views/bottom.php";
?>