<?php
	$list_choix_capacites = Dictionary::GetChoixCapacites( FALSE, FALSE );
	
	$choix_capacite_repository = new ChoixCapaciteRepository();
	foreach( $list_choix_capacites as $id => $nom ){
		$list_choix_capacites[ $id ] = $choix_capacite_repository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_capacite"] ) ){
		$choixCapacite = $choix_capacite_repository->Create( array( "nom" => Security::FilterInput( $_POST["choix_capacite_nom"] ) ) );
		
		header( "Location: ?s=super&a=updateChoixCapacite&i=" . $choixCapacite->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/s/listChoixCapacites.php";
	include "./views/bottom.php";
?>