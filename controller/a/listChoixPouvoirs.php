<?php
	$list_choix_pouvoirs = Dictionary::GetChoixPouvoirs( FALSE, FALSE );
	
	$choixPouvoirRepository = new ChoixPouvoirRepository();
	$choixPouvoirs = array();
	foreach( $list_choix_pouvoirs as $id => $nom ){
		$choixPouvoirs[] = $choixPouvoirRepository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_pouvoir"] ) ){
		$choixPouvoir = $choixPouvoirRepository->Create( array( "nom" => utf8_decode( Security::FilterInput( $_POST["choix_pouvoir_nom"] ) ) ) );
		
		header( "Location: ?s=admin&a=updateChoixPouvoir&i=" . $choixPouvoir->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listChoixPouvoirs.php";
	include "./views/bottom.php";
?>