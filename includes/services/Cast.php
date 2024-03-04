<?php
	class Cast {
		public static function GetCharacterById( $character_id ){
			if( is_numeric( $character_id ) ){
				$where = "p.id = ?";
				
				$characters_list = self::FetchCharacterList( "", $where, array( $character_id ) );
				$character = array_shift( $characters_list );
				
				return $character;
			}
			return FALSE;
		}
		
		public static function GetCharacterList( $sort_by = "character" ){
			$order_by = "";
			switch( $sort_by ) {
				case "character" :
					$order_by = "TRIM( p.nom ), TRIM( CONCAT( j.prenom, ' ', j.nom ) ), pj.quand DESC";
					break;
				case "player" :
					$order_by = "TRIM( CONCAT( j.prenom, ' ', j.nom ) ), TRIM( p.nom ), pj.quand DESC";
					break;
			}
			echo $order_by;
			return self::FetchCharacterList( $order_by );
		}
		
		public static function GetActiveCharactersOrderedByPlayerName(){
			$where = "p.est_vivant = '1' AND p.est_cree = '1'";
			$order_by = "TRIM( CONCAT( j.prenom, ' ', j.nom ) ), TRIM( p.nom ), pj.quand DESC";
			return self::FetchCharacterList( $order_by, $where );
		}
		
		public static function GetDeadCharacters(){
			$where = "p.est_vivant != '1'";
			$order_by = "TRIM( p.nom ), TRIM( CONCAT( j.prenom, ' ', j.nom ) ), pj.quand DESC";
			return self::FetchCharacterList( $order_by, $where );
		}
		
		public static function GetCharactersOfPlayer( $player_id ){
			$where = "p.joueur = ?";
			$order_by = "p.est_vivant DESC, TRIM( p.nom ), pj.quand DESC";
			return self::FetchCharacterList( $order_by, $where, array( $player_id ) );
		}
		
		private static function FetchCharacterList( $order_by = "", $where = "", $params = array() ){
			$list = array();
			
			$sql = "SELECT p.id, p.nom, p.est_vivant, p.est_cree,
						p.point_experience, p.total_experience,
						pj.quand AS last_update,
						CONCAT( u.prenom, ' ', u.nom ) AS user_update,
						j.id AS player_id,
						CONCAT( j.prenom, ' ', j.nom ) AS player_name
					FROM personnage AS p
						LEFT JOIN personnage_journal pj ON pj.id = ( SELECT id FROM personnage_journal WHERE active = '1' AND id_personnage = p.id ORDER BY quand DESC LIMIT 1 )
						LEFT JOIN joueur u ON pj.joueur_id = u.id
						LEFT JOIN joueur j ON p.joueur = j.id
					WHERE p.est_detruit = '0'";
			
			if( $where != "" ){
				$sql .= " AND " . $where;
			} else {
				$params = array();
			}
			
			if( $order_by != "" ){
				$sql .= " ORDER BY " . $order_by;
			}
			
			$db = new Database();
			$db->Query( $sql, $params );
			while( $r = $db->GetResult() ){
				$c = new Character();
				
				$c->Id = $r[ "id" ];
				$c->Nom = $r[ "nom" ];
				
				$c->JoueurId = $r[ "player_id" ];
				$c->JoueurNom = $r[ "player_name" ];
				
				$c->EstVivant = $r[ "est_vivant" ] == 1;
				$c->EstCree = $r[ "est_cree" ] == 1;
				
				$c->PointsExperienceRestants = $r[ "point_experience" ];
				$c->PointsExperienceTotal = $r[ "total_experience" ];
				
				$c->DernierChangementDate = $r[ "last_update" ];
				$c->DernierChangementPar = $r[ "user_update" ];
				
				$list[ $r[ "id" ] ] = $c;
			}
			
			return $list;
		}
	}
?>