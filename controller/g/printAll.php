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
	$list_prestiges = Dictionary::GetPrestiges();
	$list_sorts_cercles = Dictionary::GetSortsWithCercles();
	
	$pr = new PersonnageRepository();
	$partials = $pr->FindAllAlives();
	
	$chars = array();
	foreach( $partials as $id => $c ){
		if( $c->est_cree ){
			$chars[ $id ] = $pr->FindComplete( $c->id );
		}
	}
	
	include "./views/g/printAll.php";
?>