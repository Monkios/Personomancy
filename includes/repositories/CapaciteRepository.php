<?php
	class CapaciteRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description, active, voie_id
					FROM capacite
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Capacite();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->description = $result[ "description" ];

				$entity->active = $result[ "active" ] == 1;

				$entity->voie_id = $result[ "voie_id" ];

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			if( !in_array( "description", $opts ) ){
				$opts[ "description" ] = "";
			}

			$db = new Database();
			$sql = "INSERT INTO capacite ( nom, description, voie_id, active, supprime )
					VALUES ( ?, ?, ?, 1, 0 )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ], $opts[ "id_voie" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $capacite ){
			$db = new Database();
			$sql = "UPDATE capacite SET
					nom = ?,
					description = ?,
					active = ?,
					voie_id = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$capacite->nom,
					$capacite->description,
					$capacite->active ? 1 : 0,
					$capacite->voie_id,
					$capacite->id
			);
			
			$db->Query( $sql, $params );
			$capacite = $this->Find( $capacite->id );
			
			return $capacite != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
	}
?>