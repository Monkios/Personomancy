<?php
	define( "PDF_FILL_COLOR", 191 );
	
	define( "CAPA_MIN_NB", 6 );
	define( "CONN_MIN_BASE", 14 );
	define( "CONN_MIN_AVANCEES", 6 );
	define( "CONN_MIN_SYNERGIQUES", 6 );
	define( "CONN_MIN_MAITRE", 2 );
	define( "CONN_MIN_LEGENDAIRES", 2 );
	define( "SORTS_NB_MIN", 6 );
	define( "ELEMENTS_PER_COLUMNS", 40 );
	
	$list_voies = Dictionary::GetVoies();
	$list_capacites = array();
	foreach( $list_voies as $voie_id => $voie_desc ){
		$list_capacites[ $voie_id ] = Dictionary::GetCapacitesByVoie( $voie_id );
	}
	$list_connaissances = Dictionary::GetConnaissances();
	$list_sorts = Dictionary::GetSortsWithCercles();
	
	if( is_numeric( $_GET['c'] ) ){
		$sheet = new CharacterSheet;
		$character_infos = $sheet->Load( $_GET['c'], TRUE );
		if( $character_infos->joueur_id == $_SESSION[ SESSION_KEY ][ "User" ]->Id ||
				$_SESSION[ SESSION_KEY ][ "User" ]->IsAnimateur ){
		} else {
			Message::Fatale( "Vous n'avez pas le droit d'accéder à ce personnage.",
					"U" . $_SESSION[ SESSION_KEY ][ "User" ]->Id . " / C" . $_GET['c'] );
		}
	} else {
		Message::Fatale( "L'identifiant de personnage doit être numérique." );
	}
	
	$prestige_voie = 0;
	$prestige_desc = "";
	if( $character_infos->prestige_id != 0 ){
		$list_prestiges = Dictionary::GetPrestiges();
		if( array_key_exists( $character_infos->prestige_id, $list_prestiges ) ){
			$prestige_voie = $list_prestiges[ $character_infos->prestige_id ][ "voie_id" ];
			$prestige_desc = $list_prestiges[ $character_infos->prestige_id ][ "nom" ];
		}
	}
	
	$tmp_sorts = array();
	$character_sorts = array();
	foreach( $character_infos->sorts as $sphere_id => $sorts_sphere ){
		$sphere_desc = "???.";
		foreach( $list_capacites as $voie_id => $voie_capacites ){
			foreach( $voie_capacites as $capacite_id => $capacite_desc ){
				if( $sphere_id == $capacite_id ){
					$sphere_desc = substr( $capacite_desc, 0, 4 ) . ".";
					break( 2 );
				}
			}
		}
		
		foreach( $sorts_sphere as $sort_id ){
			$character_sorts[] = $sort_id;
			if( array_key_exists( $sort_id, $list_sorts ) ){
				$tmp_sorts[ $sort_id ] = $list_sorts[ $sort_id ][ "nom" ] . " (" . $sphere_desc . " " . $list_sorts[ $sort_id ][ "cercle" ] . ")";
			} else {
				$tmp_sorts[ $sort_id ] = "Sort #" . $sort_id . " (" . $sphere_desc . " ?)";
			}
		}
	}
	$list_sorts = $tmp_sorts;

	include "./views/p/sheet.php";
?>