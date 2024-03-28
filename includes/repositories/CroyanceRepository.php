<?php
	class CroyanceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description, active
					FROM croyance
					WHERE id = ? AND supprime = 0";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Croyance();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->description = $result[ "description" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			if( !in_array( "description", $opts ) ){
				$opts[ "description" ] = "";
			}

			$db = new Database();
			$sql = "INSERT INTO croyance ( nom, description )
					VALUES ( ?, ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $croyance ){
			$db = new Database();
			$sql = "UPDATE croyance SET
					nom = ?,
					description = ?,
					active = ?
				WHERE supprime = 0 AND id = ?";
			$params = array(
					$croyance->nom,
					$croyance->description,
					$croyance->active ? 1 : 0,
					$croyance->id
			);
			
			$db->Query( $sql, $params );
			$croyance = $this->Find( $croyance->id );
			
			return $croyance != FALSE;
		}
		
		public function Delete( $id ){}
	}
?>