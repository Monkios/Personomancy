<?php
	$list_choix_capacites = Dictionary::GetChoixCapacites( FALSE, FALSE );
	
	$choixCapaciteRepository = new ChoixCapaciteRepository();
	$choixCapacites = array();
	foreach( $list_choix_capacites as $id => $nom ){
		$choixCapacites[] = $choixCapaciteRepository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_capacite"] ) ){
		$choixCapacite = $choixCapaciteRepository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["choix_capacite_nom"] ), 'ISO-8859-1', 'UTF-8') ) );
		
		header( "Location: ?s=admin&a=updateChoixCapacite&i=" . $choixCapacite->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listChoixCapacites.php";
	include "./views/bottom.php";
?>