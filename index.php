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
	
	// Find controller to use
	if( isset( $_GET['s'] ) ){
		$control_section = $_GET['s'];
	}
	
	// User must login
	if( !isset( $_SESSION[ SESSION_KEY ] ) || !isset( $_SESSION[ SESSION_KEY ][ "User" ] ) ){
		$control_section = $default_section;
		$control_action = $default_action;
	} else {
		$user_identity = new Identity( $_SESSION[ SESSION_KEY ][ "User" ]->Id );
		$logged_in = TRUE;
	}
	
	// Inexistant section goes to default
	if( !$control_section ){
		$control_section = $default_section;
	}
	
	// Redirect on invalid controller
	if( !array_key_exists( $control_section, $section_types ) ){
		$frags = $_GET;
		unset( $frags[ "s" ] );
		
		header( "Location: ./index.php?" . http_build_query( $frags ) );
		die();
	}
	
	// Trigger requested action
	if( isset( $_GET['a'] ) ){
		$control_action = $_GET['a'];
	} elseif( !$control_action ) {
		$control_action = $default_action;
	}
	
	// Test if user has access
	if( $logged_in ){
		// GM access to GM page
		if( $control_section == "gm" && !$user_identity->HasAccess( Identity::IS_ANIMATEUR ) ){
			$control_section = $default_section;
			$control_action = $default_action;
		}
	}
	
	// Find the path to the action requested
	$control_dir = "./controller/" . $section_types[ $control_section ];
	$control_path = $control_dir . "/" . $control_action . ".php";
	if( file_exists( $control_path ) ){
		// Verify if the requested path is the same as the control path
		$requested_path = realpath( $control_dir ) . "/" . $control_action . ".php";
		$requested_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $requested_path); // Works in all OS
		if( realpath( $control_path ) !== $requested_path ){
			Message::Fatale( "Relative path traversal attack." );
		}
	} else {
		Message::Fatale( "File requested not found.", print_r( $_REQUEST, true ) );
	}
	
	// Include action's file
	if( $control_section && $control_action && realpath( $control_path ) !== FALSE ){
		include $control_path;
	} else {
		header( "Location: ./index.php" );
		die();
	}
?>