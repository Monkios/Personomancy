<?php
	if( isset( $_POST['send'] ) ){
		if( isset( $_POST['email'] ) && $_POST['email'] != "" &&
				isset( $_POST['password'] ) && $_POST['password'] != "" ){
			$player = Identity::GetConnectingPlayer( Security::FilterEmail( $_POST['email'] ), Security::FilterInput( $_POST['password'] ) );
			
			if( $player ){
				if( $player->IsActive ){
					$_SESSION[ SESSION_KEY ][ "User" ] = $player;
					unset( $player );
					
					header( "Location: ./index.php?s=player" );
					die();
				} else {
					header( "Location: ./index.php?s=user&a=activate&m=" . urlencode( $player->Email ) );
					die();
				}
			} else {
				Message::Notice( "Une erreur s'est produite en tentant de vous authentifier." );
				
				header( "Location: ./index.php" );
				die();
			}
		}
	}
	
	include "./views/top.php";
	include "./views/u/login.php";
	include "./views/bottom.php";
?>