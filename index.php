<?php
	if( defined( "CAME_FROM_INDEX" ) ){
		die( "Can't include INDEX more than once." );
	}
	define( "CAME_FROM_INDEX", true );
	
	require "./includes/config.php";
	
	$control_section = FALSE;
	$control_action  = FALSE;
	$control_path    = FALSE;
	$user_identity 	 = FALSE;
	$logged_in       = FALSE;
	
	// Détermine le contrôleur à utiliser
	if( isset( $_GET['s'] ) ){
		$control_section = $_GET['s'];
	}
	
	// L'usager doit s'authentifier et son compte doit être activé
	if( !isset( $_SESSION[ SESSION_KEY ] ) || !isset( $_SESSION[ SESSION_KEY ][ "User" ] ) || !$_SESSION[ SESSION_KEY ][ "User" ]->IsActive ){
		$control_section = $default_section;
		$control_action = $default_action;
	} else {
		$user_identity = new Identity( $_SESSION[ SESSION_KEY ][ "User" ]->Id );
		$logged_in = TRUE;
	}
	
	// Si aucun contrôleur n'est spécifié, dirige vers le défaut
	if( !$control_section ){
		$control_section = $default_section;
	}
	
	// Si le contrôleur est invalide, redirige vers l'index
	if( !array_key_exists( $control_section, $section_types ) ){
		$frags = $_GET;
		unset( $frags[ "s" ] );
		
		header( "Location: ./index.php?" . http_build_query( $frags ) );
		die();
	}
	
	// Déclenche l'action demandée
	if( isset( $_GET['a'] ) ){
		$control_action = $_GET['a'];
	} elseif( !$control_action ) {
		$control_action = $default_action;
	}
	
	// Valide les accès de l'utilisateur
	if( $logged_in ){
		switch( $control_section ) {
			case "admin" :
				// Les administrateurs accèdent aux pages d'administration
				if( !$user_identity->HasAccess( Identity::IS_ADMINISTRATEUR ) ){
					$control_section = $default_section;
					$control_action = $default_action;
				}
				break;
			case "gm" :
				// Les animateurs accèdent aux pages de l'animation
				if( !$user_identity->HasAccess( Identity::IS_ANIMATEUR ) ){
					$control_section = $default_section;
					$control_action = $default_action;
				}
				break;
			default :
				// Rien.
		}
	}
	
	// Trouve le chemin vers l'action demandée
	$control_dir = "./controller/" . $section_types[ $control_section ];
	$control_path = $control_dir . "/" . $control_action . ".php";
	if( file_exists( $control_path ) ){
		// Vérifie si le chemin demandé est le même que le chemin de l'action
		// Évite qu'un attaquant essaie d'accéder à des pages inappropriées
		$requested_path = realpath( $control_dir ) . "/" . $control_action . ".php";
		$requested_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $requested_path); // Works in all OS
		if( realpath( $control_path ) !== $requested_path ){
			Message::Fatale( "Relative path traversal attack." );
		}
	} else {
		Message::Fatale( "File requested not found.", print_r( $_REQUEST, true ) );
	}
	
	// Ouvre la page vers l'action demandée
	if( $control_section && $control_action && realpath( $control_path ) !== FALSE ){
		include $control_path;
	} else {
		header( "Location: ./index.php" );
		die();
	}
?>