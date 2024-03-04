<?php
	$sort_by = "character";
	$only_alives = FALSE;
	if( isset( $_GET[ "sort" ] ) && $_GET[ "sort" ] == "player" ){
		$sort_by = "player";
	}
	
	$pr = new PersonnageRepository();
	if( !isset( $_GET[ "alive" ] ) || $_GET[ "alive" ] == "y" ){
		$only_alives = TRUE;
		$chars = $pr->FindAllAlives( $sort_by );
	} else {
		$chars = $pr->FindAll( $sort_by );
	}
	
	include "./views/top.php";
	include "./views/g/characterList.php";
	include "./views/bottom.php";
?>