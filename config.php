<?php
	if( defined( "IS_CONFIG_INCLUDED" ) ){
		die( "Can't include CONFIG more than once." );
	}
	define( "IS_CONFIG_INCLUDED", TRUE );
	
	require "./includes/init.php";
	
	define( "LARP_NAME", "My LARP" );
	define( "LARP_ADDRESS", "mylarp@address.com" );
	
	define( "DATABASE_SERVER", "database.server" );
	define( "DATABASE_PORT", "3306" );
	define( "DATABASE_USER", "database_username" );
	define( "DATABASE_PASSWORD", "database_password" );
	define( "DATABASE_NAME", "database_name" );

	define( "SMTP_HOSTNAME", "smtp.hostname" );
	define( "SMTP_USERNAME", "smtp.username" );
	define( "SMTP_PASSWORD", "smtp.password" );
	
	define( "IDENTITY_TRIGGER_ACTIVATION", 3 ); // Mois depuis la dernière connexion qui force l'activation
	define( "PASSWORD_GEN_LENGTH", 10 ); // Longueur du mot de passe généré
	
	define( "SESSION_KEY", "Mancy_SessionKey" );
	
	define( "CHARACTER_BASE_XP", 250 );
	define( "CHARACTER_BASE_PCR", 4 );
	define( "CHARACTER_BASE_AUTOMATICS", "{\"capacites\":[],\"connaissances\":[24],\"pouvoirs\":[],\"voies\":[2]}" );
	define( "CHARACTER_BASE_HP", 15 );
	define( "CHARACTER_BASE_MP", 10 );
	
	define( "CHARACTER_HP_PER_CON", 3 );
	define( "CHARACTER_BONUS_HP_VIG_AVC_ID", 214 );
	define( "CHARACTER_BONUS_HP_VIG_AVC_NB", 5 );
	
	define( "CHARACTER_MP_PER_SPI", 3 );
	define( "CHARACTER_BONUS_MP_VOL_AVC_ID", 215 );
	define( "CHARACTER_BONUS_MP_VOL_AVC_NB", 5 );
	
	define( "CHARACTER_BONUS_MP_INT_AVC_ID", 234 );
	define( "CHARACTER_BONUS_MP_INT_AVC_NB", 3 );
	define( "CHARACTER_BONUS_MP_INT_MTR_ID", 237 );
	define( "CHARACTER_BONUS_MP_INT_MTR_NB", 3 );
	define( "CHARACTER_BONUS_MP_INT_LGD_ID", 246 );
	define( "CHARACTER_BONUS_MP_INT_LGD_NB", 3 );
	
	define( "CHARACTER_BONUS_VIG_CON_AVC_ID", 212 );
	define( "CHARACTER_BONUS_VIG_CON_AVC_NB", 1 );
	define( "CHARACTER_BONUS_VIG_VOL_MTR_ID", 111 );
	define( "CHARACTER_BONUS_VIG_VOL_MTR_NB", 1 );
	
	define( "CHARACTER_BONUS_VOL_SPI_AVC_ID", 213 );
	define( "CHARACTER_BONUS_VOL_SPI_AVC_NB", 1 );
	define( "CHARACTER_BONUS_VOL_VIG_MTR_ID", 92 );
	define( "CHARACTER_BONUS_VOL_VIG_MTR_NB", 1 );
	
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
?>
