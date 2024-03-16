<?php
	$list_choix_capacites = Dictionary::GetChoixCapacites( FALSE, FALSE );
	
	$choix_capacite_repository = new ChoixCapaciteRepository();
	foreach( $list_choix_capacites as $id => $nom ){
		$list_choix_capacites[ $id ] = $choix_capacite_repository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_capacite"] ) ){
		$choixCapacite = $choix_capacite_repository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["choix_capacite_nom"] ), 'ISO-8859-1', 'UTF-8') ) );
		
		header( "Location: ?s=admin&a=updateChoixCapacite&i=" . $choixCapacite->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listChoixCapacites.php";
	include "./views/bottom.php";
?>