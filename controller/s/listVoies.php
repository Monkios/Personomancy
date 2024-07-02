<?php
	$list_voies = Dictionary::GetVoies( FALSE, FALSE );
	
	$voie_repository = new VoieRepository();
	foreach( $list_voies as $id => $nom ){
		$list_voies[ $id ] = $voie_repository->Find( $id );
	}
	
	if( isset( $_POST["add_voie"] ) ){
		$voie = $voie_repository->Create( array( "nom" => Security::FilterInput( $_POST["voie_nom"] ) ) );
		
		if( $voie !== FALSE ){
			header( "Location: ?s=super&a=updateVoie&i=" . $voie->id );
			die();
		}
	}
	
	include "./views/top.php";
	include "./views/a/listVoies.php";
	include "./views/bottom.php";
?>