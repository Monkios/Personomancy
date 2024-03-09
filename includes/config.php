<?php
	if( defined( "IS_CONFIG_INCLUDED" ) ){
		die( "Can't include CONFIG more than once." );
	}
	define( "IS_CONFIG_INCLUDED", TRUE );
	
	require "./includes/init.php";
	
	define( "LARP_NAME", "My LARP" );
	
	define( "DATABASE_SERVER", "localhost" );
	define( "DATABASE_PORT", "3306" );
	define( "DATABASE_USER", "root" );
	define( "DATABASE_PASSWORD", "" );
	define( "DATABASE_NAME", "personnomancy" );

	define( "SMTP_HOSTNAME", "smtp.gmail.com" );
	define( "SMTP_PORT", "465" );
	define( "SMTP_USERNAME", "chroniquesdakeras@gmail.com" );
	define( "SMTP_PASSWORD", "arjf luth rqoq ltcg" ); // https://support.google.com/accounts/answer/185833?p=InvalidSecondFactor&hl=fr
	
	define( "IDENTITY_TRIGGER_ACTIVATION", 3 ); // Mois depuis la dernière connexion qui force l'activation
	define( "PASSWORD_GEN_LENGTH", 10 ); // Longueur du mot de passe généré
	
	define( "SESSION_KEY", "Mancy_SessionKey" );
	
	define( "CHARACTER_BASE_XP", 250 );
	define( "CHARACTER_BASE_PCR", 4 );
	define( "CHARACTER_BASE_HP", 15 );
	define( "CHARACTER_BASE_MP", 10 );
	
	define( "CHARACTER_HP_PER_CON", 3 );	
	define( "CHARACTER_MP_PER_SPI", 3 );
	
	define( "CHARACTER_MAX_CAPACITES_SELECTIONS", 16 );
	define( "CHARACTER_MAX_XP_INVESTED", 1250 );
	define( "CHARACTER_REBUILD_PERTE_TAUX", 0.75 );
	
	define( "LOG_LEVEL", Message::$Notice );
	define( "LOG_FILE", "./logs/messages." . date( "y-m" ) . ".log" );
	
	if( !isset( $_SESSION[ SESSION_KEY ] ) ){
		$_SESSION[ SESSION_KEY ] = array();
	}
	
	// Initializing parameters
	Message::SetLogLevel( LOG_LEVEL, LOG_FILE );
	
	setlocale( LC_ALL, "fr_CA.utf8" );
	date_default_timezone_set( "America/New_York" );
?>
