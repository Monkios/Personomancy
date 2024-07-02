<?php
	$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
	$chars = Roster::GetAllCharactersByPlayer( $joueur->Id );
	
	// Un joueur qui a deja un personnage vivant ne peut pas en creer un nouveau
	$can_create = $joueur->IsAnimateur
			|| Roster::GetCharacterCountByPlayer( $joueur->Id ) == 0;
	
	$on_homepage = TRUE;
	
	include "./views/top.php";
	include "./views/p/index.php";
	include "./views/bottom.php";
?>