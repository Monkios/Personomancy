<?php
	$list_choix_connaissances = Dictionary::GetChoixConnaissances( FALSE, FALSE );
	
	$choix_connaissance_repository = new ChoixConnaissanceRepository();
	foreach( $list_choix_connaissances as $id => $nom ){
		$list_choix_connaissances[ $id ] = $choix_connaissance_repository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_connaissance"] ) ){
		$choixConnaissance = $choix_connaissance_repository->Create( array( "nom" => Security::FilterInput( $_POST["choix_connaissance_nom"] ) ) );
		
		header( "Location: ?s=super&a=updateChoixConnaissance&i=" . $choixConnaissance->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/s/listChoixConnaissances.php";
	include "./views/bottom.php";
?>