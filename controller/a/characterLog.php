<?php
	$show_canceled = false;
	$where = "l.active = '1'";
	if( isset( $_GET[ "show_canceled" ] ) && $_GET[ "show_canceled" ] == 1 ){
		$show_canceled = true;
		$where = "1 = 1";
	}
	
	$show_dead = false;
	if( isset( $_GET[ "show_dead" ] ) && $_GET[ "show_dead" ] == 1 ){
		$show_dead = true;
		$where .= "";
	} else {
		$where .= " && p.est_detruit = '0' && p.est_vivant = '1'";
	}
	
	$list = CharacterLog::GetAll( $where );
	
	include "./views/top.php";
	include "./views/a/characterLog.php";
	include "./views/bottom.php";
?>