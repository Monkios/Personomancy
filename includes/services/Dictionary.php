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
		
		public static function GetAlignements( $id = FALSE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM alignement
					WHERE active = '1' AND supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
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
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetCapacitesByVoie( $id ){
			$sql = "SELECT id, nom
					FROM capacite
					WHERE voie = ? AND active = '1' AND supprime = '0'
					ORDER BY nom";
			return self::GetResultByName( $sql, array( $id ) );
		}
		
		public static function GetChoixCapacites( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM capacite_categorie
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetChoixConnaissances( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM connaissance_categorie
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetChoixPouvoirs( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM pouvoir_categorie
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = ?";
				$params[] = 1;
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
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetFactions( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM faction
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetPouvoirs( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM pouvoir
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetPrestiges( $id = FALSE ){
			$params = array();
			$sql = "SELECT id, nom, voie_id
					FROM prestige
					WHERE active = '1' AND supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
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
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetReligions( $id = FALSE ){
			$params = array();
			$sql = "SELECT d.id, d.nom, p.nom as pantheon
					FROM divinite d
						INNER JOIN pantheon p ON p.id = d.pantheon
					WHERE d.active = '1' AND d.supprime = '0' AND p.active = '1' AND p.supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND d.id = ?";
				$params[] = $id;
			}
			$sql .= " ORDER BY p.nom, d.nom";
			return self::GetResult( $sql, $params );
		}
		
		public static function GetSorts( $id = FALSE, $activeOnly = TRUE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM sort
					WHERE supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			if( $activeOnly ){
				$sql .= " AND active = ?";
				$params[] = 1;
			}
			$sql .= " ORDER BY nom";
			return self::GetResultByName( $sql, $params );
		}
		
		public static function GetSortsWithCercles(){
			$sql = "SELECT id, nom, niveau AS cercle
					FROM sort
					WHERE active = '1' AND supprime = '0'
					ORDER BY nom";
			return self::GetResult( $sql );
		}
		
		public static function GetStatistiques(){
			$statistiques = array(
				1 => mb_convert_encoding( "Constitution", 'ISO-8859-1', 'UTF-8'),
				2 => mb_convert_encoding( "Intelligence", 'ISO-8859-1', 'UTF-8'),
				3 => mb_convert_encoding( "Alerte", 'ISO-8859-1', 'UTF-8'),
				4 => mb_convert_encoding( "Spiritisme", 'ISO-8859-1', 'UTF-8'),
				5 => mb_convert_encoding( "Vigueur", 'ISO-8859-1', 'UTF-8'),
				6 => mb_convert_encoding( "Volonté", 'ISO-8859-1', 'UTF-8')
			);
			asort( $statistiques );
			
			return $statistiques;
		}
		
		public static function GetVoies( $id = FALSE ){
			$params = array();
			$sql = "SELECT id, nom
					FROM voie
					WHERE active = '1' AND supprime = '0'";
			if( $id !== FALSE ){
				$sql .= " AND id = ?";
				$params[] = $id;
			}
			$sql .= " ORDER BY ordre_affichage, nom";
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