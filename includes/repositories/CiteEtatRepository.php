<?php
	class CiteEtatRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description, active
					FROM cite_etat
					WHERE id = ? AND supprime = 0";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new CiteEtat();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->description = $result[ "description" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}

		public function FindAll( $active_only = TRUE ){
			$list = array();
			$db = new Database();
			$sql = "SELECT id, nom, description, active
					FROM cite_etat
					WHERE supprime = 0";
			if( $active_only ){
				$sql .= " AND active = '1'";
			}
			$db->Query( $sql );
			while( $result = $db->GetResult() ){
				$entity = new CiteEtat();
				$entity->id = $result[ "id" ];
				$entity->nom = $result[ "nom" ];
				$entity->description = $result[ "description" ];
				$entity->active = $result[ "active" ] == 1;

				$list[ $entity->id ] = $entity;
			}
			
			return $list;
		}
		
		public function Create( $opts = array() ){
			if( !in_array( "description", $opts ) ){
				$opts[ "description" ] = "";
			}

			$db = new Database();
			$sql = "INSERT INTO cite_etat ( nom, description )
					VALUES ( ?, ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $cite_etat ){
			$db = new Database();
			$sql = "UPDATE cite_etat SET
					nom = ?,
					description = ?,
					active = ?
				WHERE supprime = 0 AND id = ?";
			$params = array(
					$cite_etat->nom,
					$cite_etat->description,
					$cite_etat->active ? 1 : 0,
					$cite_etat->id
			);
			
			$db->Query( $sql, $params );
			$cite_etat = $this->Find( $cite_etat->id );
			
			return $cite_etat != FALSE;
		}
		
		public function Delete( $id ){}
	}
?>