<?php
	if( !$logged_in ){
		include( "./controller/u/login.php" );
	} else {
		include( "./controller/p/index.php" );
	}
	die();
?>