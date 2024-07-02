<?php
	if( is_numeric( $_GET["i"] ) ){
		$cite_etat_repository = new CiteEtatRepository();
		$cite_etat = $cite_etat_repository->Find( $_GET["i"] );
		
		if( isset( $_POST["cite_etat_id"] ) && $_GET["i"] == $_POST["cite_etat_id"] ){
			if( isset( $_POST["save_cite_etat"] ) ){
				$cite_etat->nom = Security::FilterInput( $_POST["cite_etat_nom"] );
				$cite_etat->description = Security::FilterInput( $_POST["cite_etat_description"] );
				$cite_etat->active = isset( $_POST["cite_etat_active"] );
				
				if( !$cite_etat_repository->Save( $cite_etat ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations de la cité-État." );
				} else {
					Message::Notice( "Les informations de la cité-État ont été mises à jour." );
				}
			}
			
			header( "Location: ?s=super&a=updateCiteEtat&i=" . $cite_etat->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de cité-État doit être numérique." );
	}
	
	$nav_links = array( "Liste des cités-États" => "?s=super&a=listCitesEtats" );
	
	include "./views/top.php";
	include "./views/s/updateCiteEtat.php";
	include "./views/bottom.php";
?>