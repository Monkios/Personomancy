<?php
	class VoieRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description, active
					FROM voie
					WHERE id = ? AND supprime = 0";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Voie();
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
			$sql = "INSERT INTO voie ( nom, description, active, supprime )
					VALUES ( ?, ?, 1, 0 )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $voie ){
			$db = new Database();
			$sql = "UPDATE voie SET
					nom = ?,
					description = ?,
					active = ?
				WHERE supprime = 0 AND id = ?";
			$params = array(
					$voie->nom,
					$voie->description,
					$voie->active ? 1 : 0,
					$voie->id
			);
			
			$db->Query( $sql, $params );
			$voie = $this->Find( $voie->id );
			
			return $voie != FALSE;
		}
		
		public function Delete( $id ){}
	}
?>