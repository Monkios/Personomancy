<?php
	$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
	$list = CharacterLog::GetByPlayer( $joueur->Id );
	unset( $joueur );
	
	include "./views/top.php";
	include "./views/p/playerLog.php";
	include "./views/bottom.php";
?>