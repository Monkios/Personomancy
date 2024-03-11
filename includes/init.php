<?php
	if( defined( "IS_INIT_INCLUDED" ) ){
		die( "Can't include INIT more than once." );
	}
	define( "IS_INIT_INCLUDED", TRUE );
	
	define( "MANCY_NAME", "Mancy - Outil de gestion de personnage" );
	define( "MANCY_NAME_ABBREV", "Mancy" );
	define( "MANCY_VERSION", "3.0" );
	define( "IS_DEV_MODE", TRUE );
	
	$default_section = "user";
	$default_action = "index";
	$section_types = array(
		"admin" => "a",
		"gm"  => "g",
		"player" => "p",
		"user"  => "u"
	);
	$on_homepage = FALSE;
	
	if( IS_DEV_MODE ){
		error_reporting( E_ALL );
		ini_set( "display_errors", 1 );
	}
	
	require_once "./includes/interfaces/IRepository.php";

	require_once "./includes/models/LogMessage.php";
	require_once "./includes/models/Player.php";

	require_once "./includes/entities/GenericEntity.php";
	require_once "./includes/entities/Capacite.php";
	require_once "./includes/entities/ChoixCapacite.php";
	require_once "./includes/entities/Connaissance.php";
	require_once "./includes/entities/Croyance.php";
	require_once "./includes/entities/PersonnagePartiel.php";
	require_once "./includes/entities/Personnage.php";
	require_once "./includes/entities/Race.php";
	require_once "./includes/entities/Voie.php";
	
	require_once "./includes/domains/CharacterSheet.php";
	require_once "./includes/domains/Identity.php";
	require_once "./includes/domains/CharacterLog.php";
	require_once "./includes/domains/Community.php";
	
	require_once "./includes/services/Database.php";
	require_once "./includes/services/Date.php";
	require_once "./includes/services/Dictionary.php";
	require_once "./includes/services/Mail.php";
	require_once "./includes/services/Message.php";
	require_once "./includes/services/Security.php";
	
	require_once "./includes/repositories/CapaciteRepository.php";
	require_once "./includes/repositories/ChoixCapaciteRepository.php";
	require_once "./includes/repositories/ConnaissanceRepository.php";
	require_once "./includes/repositories/CroyanceRepository.php";
	require_once "./includes/repositories/PersonnageRepository.php";
	require_once "./includes/repositories/RaceRepository.php";
	require_once "./includes/repositories/VoieRepository.php";
	
	session_start();
	
	require "./vendor/autoload.php";

	define( "PHPMAILER_LANG", "./vendor/phpmailer/phpmailer/language" );
	
	header('Content-Type: text/html; charset=utf-8');
?>