<?php
	$list_connaissances = Dictionary::GetConnaissances( FALSE, FALSE );
	
	$connaissanceRepository = new ConnaissanceRepository();
	$connaissances = array();
	foreach( $list_connaissances as $id => $nom ){
		$connaissances[] = $connaissanceRepository->Find( $id );
	}
	
	if( isset( $_POST["add_connaissance"] ) ){
		$connaissance = $connaissanceRepository->Create( array( "nom" => mb_convert_encoding( Security::FilterInput( $_POST["connaissance_nom"] ), 'ISO-8859-1', 'UTF-8') ) );
		
		header( "Location: ?s=admin&a=updateConnaissance&i=" . $connaissance->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listConnaissances.php";
	include "./views/bottom.php";
?>