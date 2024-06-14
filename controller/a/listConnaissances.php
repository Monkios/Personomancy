<?php
	$list_connaissances = Dictionary::GetConnaissances( FALSE, FALSE );
	$list_voies = Dictionary::GetVoies();
	
	$connaissance_repository = new ConnaissanceRepository();
	foreach( $list_connaissances as $id => $nom ){
		$list_connaissances[ $id ] = $connaissance_repository->Find( $id, "cout" );
	}
	
	if( isset( $_POST["add_connaissance"] ) ){
		$connaissance = $connaissance_repository->Create( array( "nom" => Security::FilterInput( $_POST["connaissance_nom"] ), "id_voie" => $_POST["connaissance_voie"] ) );
		
		header( "Location: ?s=admin&a=updateConnaissance&i=" . $connaissance->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listConnaissances.php";
	include "./views/bottom.php";
?>