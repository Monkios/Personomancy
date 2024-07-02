<?php
	$list_choix_voies = Dictionary::GetChoixVoies( FALSE, FALSE );
	
	$choix_voie_repository = new ChoixVoieRepository();
	foreach( $list_choix_voies as $id => $nom ){
		$list_choix_voies[ $id ] = $choix_voie_repository->Find( $id );
	}
	
	if( isset( $_POST["add_choix_voie"] ) ){
		$choixVoie = $choix_voie_repository->Create( array( "nom" => Security::FilterInput( $_POST["choix_voie_nom"] ) ) );
		
		header( "Location: ?s=super&a=updateChoixVoie&i=" . $choixVoie->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/s/listChoixVoies.php";
	include "./views/bottom.php";
?>