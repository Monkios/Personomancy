<?php
	if( is_numeric( $_GET["i"] ) ){
		$sortRepository = new SortRepository();
		$sort = $sortRepository->Find( $_GET["i"] );
		
		$list_spheres = Dictionary::GetCapacites();
		
		if( isset( $_POST["sort_id"] ) && $_GET["i"] == $_POST["sort_id"] ){
			if( isset( $_POST["save_sort"] ) && array_key_exists( $_POST["sort_sphere"], $list_spheres ) && is_numeric( $_POST["sort_cercle"] ) ){
				$sort->nom = utf8_decode( Security::FilterInput( $_POST["sort_nom"] ) );
				$sort->active = isset( $_POST["sort_active"] );
				
				$sort->sphere_id = $_POST["sort_sphere"];
				$sort->cercle = $_POST["sort_cercle"];
				
				// ...
				
				if( !$sortRepository->Save( $sort ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du sort." );
				} else {
					Message::Notice( "Les informations du sort ont été mises à jour." );
				}
			}
			
			header( "Location: ?s=admin&a=updateSort&i=" . $sort->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de sort doit être numérique." );
	}
	
	$nav_links = array( "Liste des sorts" => "?s=admin&a=listSorts" );
	
	include "./views/top.php";
	include "./views/a/updateSort.php";
	include "./views/bottom.php";
?>