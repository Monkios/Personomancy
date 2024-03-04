<?php
	$list_sorts = Dictionary::GetSorts( FALSE, FALSE );
	$list_spheres = Dictionary::GetCapacites();
	
	$sortRepository = new SortRepository();
	$sorts = array();
	foreach( $list_sorts as $id => $nom ){
		$sorts[] = $sortRepository->Find( $id );
	}
	
	if( isset( $_POST["add_sort"] ) ){
		$sort = $sortRepository->Create( array( "nom" => utf8_decode( Security::FilterInput( $_POST["sort_nom"] ) ), "id_sphere" => $_POST["sort_sphere"], "cercle" => $_POST["sort_cercle"] ) );
		
		header( "Location: ?s=admin&a=updateSort&i=" . $sort->id );
		die();
	}
	
	include "./views/top.php";
	include "./views/a/listSorts.php";
	include "./views/bottom.php";
?>