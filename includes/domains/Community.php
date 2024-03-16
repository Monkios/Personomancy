<?php
	class Community {
		private function __construct(){}
		
		public static function GetPlayer( $id ){
			if( is_numeric( $id ) ){
				$sql = "SELECT j.id, j.prenom, j.nom, j.courriel, j.salt,
						j.est_animateur, j.est_administrateur, j.active, j.date_insert, j.date_modify
					FROM joueur j
					WHERE j.id = ?";
			
				$t = Community::FetchPlayerList( $sql, array( $id ) );
				if( $t && count( $t ) == 1 ){
					return reset( $t ); // Retourne le premier élément de la liste
				} else {
					return FALSE;
				}
			} else {
				Message::Fatale( "Id must be numerical.", $id );
			}
		}
		
		public static function GetPlayerByEMail( $email ){
			if( $r = Database::FetchOne(
					"SELECT id FROM joueur WHERE LOWER( courriel ) = ?",
					array( strtolower( $email ) ) )
			){
				return Community::GetPlayer( $r[ "id" ] );
			}
			return FALSE;
		}
		
		public static function GetPlayerList( $active_only = FALSE ){
			$sql = "SELECT j.id, j.prenom, j.nom, j.courriel, j.salt,
						j.est_animateur, j.est_administrateur, j.active, j.date_insert, j.date_modify,
						(
							SELECT COUNT( p.id )
							FROM personnage p
							WHERE p.est_vivant = '1' AND p.est_cree = '1' AND p.est_detruit = '0' AND p.joueur = j.id
						)  AS nb_characters
					FROM joueur j ";
			if( $active_only != FALSE ){
				$sql .= "WHERE j.active = '1' ";
			}
			$sql .= "ORDER BY j.prenom, j.nom, j.courriel";
			
			return Community::FetchPlayerList( $sql );
		}
		
		private static function FetchPlayerList( $sql, $params = array() ){
			$return = array();
			$db = new Database();
			
			$db->Query( $sql, $params );
			while( $r = $db->GetResult() ){
				$player = new Player();
				
				$player->Id = $r[ "id" ];
				$player->FirstName = $r[ "prenom" ];
				$player->LastName = $r[ "nom" ];
				$player->Email = $r[ "courriel" ];
				$player->Salt = $r[ "salt" ];
				$player->IsActive = $r[ "active" ] == 1;
				$player->IsAnimateur = $r[ "est_animateur" ] == 1;
				$player->IsAdministrateur = $r[ "est_administrateur" ] == 1;
				$player->DateInsert = $r[ "date_insert" ];
				$player->DateModify = $r[ "date_modify" ];
				
				$return[ $player->Id ] = $player;
			}
			
			return $return;
		}
	}
?>