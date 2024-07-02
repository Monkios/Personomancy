<?php
	$list_cites_etats = Dictionary::GetCitesEtats( FALSE, FALSE );
	
	$cite_etat_repository = new CiteEtatRepository();
	foreach( $list_cites_etats as $id => $nom ){
		$list_cites_etats[ $id ] = $cite_etat_repository->Find( $id );
	}
	
	if( isset( $_POST["add_cite_etat"] ) ){
		$cite_etat = $cite_etat_repository->Create( array( "nom" => Security::FilterInput( $_POST["cite_etat_nom"] ) ) );
		
		if( $cite_etat !== FALSE ){
			header( "Location: ?s=super&a=updateCiteEtat&i=" . $cite_etat->id );
			die();
		}
	}
	
	include "./views/top.php";
	include "./views/s/listCitesEtats.php";
	include "./views/bottom.php";
?>