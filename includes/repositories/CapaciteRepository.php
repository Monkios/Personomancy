<?php
	class CapaciteRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active, voie
					FROM capacite
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Capacite();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				$entity->voie_id = $result[ "voie" ];

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO capacite ( nom, voie, active, supprime )
					VALUES ( ?, ?, '0', '0' )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "id_voie" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $capacite ){
			$db = new Database();
			$sql = "UPDATE capacite SET
					nom = ?,
					active = b?,
					voie = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$capacite->nom,
					$capacite->active ? 1 : 0,
					$capacite->voie_id,
					$capacite->id
			);
			
			$db->Query( $sql, $params );
			$capacite = $this->Find( $capacite->id );
			
			return $capacite != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
		
		public static function GetSorts( $capacite_id ){
			if( !is_numeric( $capacite_id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$sorts = array();
			$db = new Database();
			$sql = "SELECT id
					FROM sort
					WHERE id_sphere = ? AND supprime = '0'
					ORDER BY niveau, nom";
			
			$sr = new SortRepository();
			$db->Query( $sql, array( $capacite_id ) );
			while( $result = $db->GetResult() ){
				$sorts[ $result[ "id" ] ] = $sr->Find( $result[ "id" ] );
			}
			
			return $sorts;
		}
	}
?>