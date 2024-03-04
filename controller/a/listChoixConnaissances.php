<?php
	$list_choix_connaissances = Dictionary::GetChoixConnaissances( FALSE, FALSE );
	
	$choixConnaissanceRepository = new ChoixConnaissanceRepository();
	$choixConnaissances = array();
	foreach( $list_choix_connaissances as $id => $nom ){
		$choixConnaissances[] = $choixConnaissanceRepository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_connaissance"] ) ){
		$choixConnaissance = $choixConnaissanceRepository->Create( array( "nom" => utf8_decode( Security::FilterInput( $_POST["choix_connaissance_nom"] ) ) ) );
		
		header( "Location: ?s=admin&a=updateChoixConnaissance&i=" . $choixConnaissance->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listChoixConnaissances.php";
	include "./views/bottom.php";
?>