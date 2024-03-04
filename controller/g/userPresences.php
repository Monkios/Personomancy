<?php
	$joueurs = Community::GetPlayerList();
	
	$pr = new PersonnageRepository();
	$personnages = $pr->FindAllAlives( "player" );
	
	define( "PDF_FILL_COLOR", 191 );
	
	include "./views/g/userPresences.php";
?>