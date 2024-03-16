<?php
	$list_capacites = Dictionary::GetCapacites( FALSE, FALSE );
	$list_voies = Dictionary::GetVoies();
	
	$capacite_repository = new CapaciteRepository();
	foreach( $list_capacites as $id => $nom ){
		$list_capacites[ $id ] = $capacite_repository->Find( $id );
	}
	
	if( isset( $_POST["add_capacite"] ) ){
		$capacite = $capacite_repository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["capacite_nom"] ), 'ISO-8859-1', 'UTF-8'), "id_voie" => $_POST["capacite_voie"] ) );
		
		header( "Location: ?s=admin&a=updateCapacite&i=" . $capacite->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listCapacites.php";
	include "./views/bottom.php";
?>