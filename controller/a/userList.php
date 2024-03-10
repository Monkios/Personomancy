<?php
	$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
	$players = Community::GetPlayerList();
	
	if( isset( $_GET['u'] ) && is_numeric( $_GET['u'] ) ){
		if( array_key_exists( $_GET['u'], $players ) ){
			$p = $players[ $_GET['u'] ];
			$identity = new Identity( $p->Id );
			
			if( isset( $_GET['active'] ) ){
				if( $_GET['active'] == "f" && $p->IsActive ){
					$identity->SetPlayerAccess( Identity::IS_ACTIVE, FALSE );
					Message::Notice( "Joueur '" . $p->getFullName() . "' désactivé." );
				} else if( $_GET['active'] == "t" && !$p->IsActive ){
					$identity->SetPlayerAccess( Identity::IS_ACTIVE, TRUE );
					Message::Notice( "Joueur '" . $p->getFullName() . "' activé." );
				} else {
					Message::Erreur( "Impossible de compléter la demande en lien le statut 'Est actif' de ce joueur." );
				}
			}
			
			if( isset( $_GET['anim'] ) ){
				if( $_GET['anim'] == "f" && $p->IsAnimateur ){
					$identity->SetPlayerAccess( Identity::IS_ANIMATEUR, FALSE );
					Message::Notice( "Joueur '" . $p->getFullName() . "' n'est plus animateur." );
				} else if( $_GET['anim'] == "t" && !$p->IsAnimateur ){
					$identity->SetPlayerAccess( Identity::IS_ANIMATEUR, TRUE );
					Message::Notice( "Joueur '" . $p->getFullName() . "' est maintenant animateur." );
				} else {
					Message::Erreur( "Impossible de compléter la demande en lien avec le statut 'Est animateur' de ce joueur." );
				}
			}
			
			if( isset( $_GET['admin'] ) ){
				if( $_GET['admin'] == "f" && $p->IsAdministrateur ){
					$identity->SetPlayerAccess( Identity::IS_ADMINISTRATEUR, FALSE );
					Message::Notice( "Joueur '" . $p->getFullName() . "' n'est plus admin." );
				} else if( $_GET['admin'] == "t" && !$p->IsAdministrateur ){
					$identity->SetPlayerAccess( Identity::IS_ADMINISTRATEUR, TRUE );
					Message::Notice( "Joueur '" . $p->getFullName() . "' est maintenant admin." );
				} else {
					Message::Erreur( "Impossible de compléter la demande en lien avec le statut 'Est admin' de ce joueur." );
				}
			}
			
			header( "Location: ?s=admin&a=userList#player_" . $p->Id );
			die();
		} else {
			Message::Erreur( "Joueur demandé inexistant." );
		}
	}
	
	include "./views/top.php";
	include "./views/a/userList.php";
	include "./views/bottom.php";
?>