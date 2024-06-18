<?php
	class CharacterLog {
		public static function GetAll( $where = "l.active = '1'" ){
			$order_by = "l.id DESC, p.nom ASC";
			
			return self::FetchFromDB( $order_by, $where );
		}
		
		public static function GetByPlayer( $id ){
			$where = "l.active = '1' && p.joueur = ?";
			$order_by = "l.id DESC, p.nom ASC";
			
			return self::FetchFromDB( $order_by, $where, array( $id ) );
		}
		
		public static function GetByCharacter( $id ){
			$where = "l.active = '1' && l.personnage_id = ?";
			$order_by = "l.id DESC, p.nom ASC";
			
			return self::FetchFromDB( $order_by, $where, array( $id ) );
		}
		
		public static function GetLastByCharacter( $id ){
			$where = "l.personnage_id = ? AND l.id = ( SELECT MAX( id ) FROM personnage_journal WHERE personnage_id = l.personnage_id AND active = '1' )";
			$order_by = "";
			
			$result = self::FetchFromDB( $order_by, $where, array( $id ) );
			if( count( $result ) == 1 ){
				return array_values( $result )[ 0 ];
			}
			
			return FALSE;
		}
		
		private static function FetchFromDB( $order_by = "", $where = "", $params = array() ){
			$list = array();
			
			$sql = "SELECT l.id, l.active, l.quand, l.combien, l.quoi, l.pourquoi, l.note, l.backtrack,
							l.joueur_id AS player_id, CONCAT( j.prenom, ' ', j.nom ) AS player_name,
							p.id AS character_id, p.nom AS character_name,
							p.est_cree, p.est_vivant, p.est_detruit
					FROM personnage_journal l
						LEFT JOIN joueur j ON j.id = l.joueur_id
						LEFT JOIN personnage p ON l.personnage_id = p.id";
			
			if( $where != "" ){
				$sql .= " WHERE " . $where;
			} else {
				$params = array();
			}
			
			if( $order_by != "" ){
				$sql .= " ORDER BY " . $order_by;
			}
			
			$db = new Database();
			$db->Query( $sql, $params );
			while( $r = $db->GetResult() ){
				$m = new LogMessage();
				
				$m->Id = $r[ "id" ];
				$m->Active = $r[ "active" ];
				$m->Date = $r[ "quand" ];
				$m->Text = $r[ "note" ];
				$m->CanBacktrack = $r[ "backtrack" ] == "1";
				
				$m->Quoi = $r[ "quoi" ];
				$m->Pourquoi = $r[ "pourquoi" ];
				$m->Combien = $r[ "combien" ];
				
				$m->PlayerId = $r[ "player_id" ];
				$m->PlayerName = $r[ "player_name" ];
				
				$m->CharacterId = $r[ "character_id" ];
				$m->CharacterName = $r[ "character_name" ];
				
				$m->CharacterActive = $r[ "est_cree" ] == 1;
				$m->CharacterAlive = $r[ "est_vivant" ] == 1;
				$m->CharacterDestroyed = $r[ "est_detruit" ] == 1;
				
				$list[ $r[ "id" ] ] = $m;
			}
			
			return $list;
		}
	}
?>