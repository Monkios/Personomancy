<?php
	$_SESSION[ SESSION_KEY ][ "User" ] = NULL;
	unset( $_SESSION[ SESSION_KEY ][ "User" ] );
	
	Message::Notice( "Vous avez été déconnecté avec succès." );
	
	header( "Location: ./index.php" );
	die();
?>