<?php
	class Dictionary {
		private static function GetResult( $sql, $args = array() ){
			$result = array();
			
			$db = new Database();
			$db->Query( $sql, $args );
			while( $row = $db->GetResult() ){
				$result[ $row[ "id" ] ] = $row;
			}
			
			return $result;
		}
		
		private static function GetResultByName( $sql, $args = array() ){
			$result = array();
			
			$db = new Database();
			$db->Query( $sql, $args );
			while( $row = $db->GetResult() ){
				$result[ $row[ "id" ] ] = $row[ "nom" ];
			}
			
			return $result;
		}
		
		public static function GetCapacites( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM capacite
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}

		public static function GetCapacitesByVoie( $voie_id ){
			$params = array();
			$sql = "SELECT id, nom
					FROM capacite
					WHERE supprime = '0' AND active = '1' AND voie_id = ?
					ORDER BY nom";
			$params[] = $voie_id;
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetCapacitesRaciales( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM capacite_raciale
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetChoixCapacites( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM choix_capacite
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetChoixConnaissances( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM choix_connaissance
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetChoixCapacitesRaciales( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM choix_capacite_raciale
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetChoixVoies( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM choix_voie
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetCitesEtats( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM cite_etat
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetConnaissances( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM connaissance
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY prereq_voie_primaire, cout, nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetCroyances( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM croyance
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResult( $sql, $params );
		}
		
		public static function GetRaces( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM race
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetVoies( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM voie
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = '1'";
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetExperienceChanges(){
			$sql = "SELECT MIN( j.id ) AS id, j.note AS nom
					FROM personnage_journal j
					WHERE j.quoi = '1' AND j.active = '1'
					GROUP BY j.note
					ORDER BY j.note";
			$tmp = self::GetResultByName( $sql );
			
			$dict = array();
			foreach( $tmp as $note ){
				if( preg_match( "/.*?\((.*)\)$/i", $note, $matches ) === 1 && !in_array( $matches[ 1 ], $dict ) ){
					$dict[] = $matches[ 1 ];
				}
			}
			sort( $dict );
			
			return $dict;
		}
	}
?>