<?php
	class RaceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad race entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description, active
					FROM race 
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Race();
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
					FROM race 
					WHERE supprime = '0'";
			if( $active_only ){
				$sql .= " AND active = '1'";
			}
			$db->Query( $sql );
			while( $result = $db->GetResult() ){
				$entity = new Race();
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
			$sql = "INSERT INTO race ( nom, description )
					VALUES ( ?, ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $race ){
			$capacites_raciales = $race->list_capacites_raciales;
			
			$db = new Database();
			$sql = "UPDATE race SET
					nom = ?,
					description = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$race->nom,
					$race->description,
					$race->active ? 1 : 0,
					$race->id
			);
			
			$db->Query( $sql, $params );
			$race = $this->Find( $race->id );
			
			return $race != FALSE;
		}
		
		public function Delete( $race_id ){ die( "NotImplementedException()" ); }

		public function GetCapacitesRacialesByRace( $race_id ){
			$list_capacites_raciales = array();
			
			$db = new Database();
			$sql = "SELECT cr.id, cr.nom, cr.cout
					FROM capacite_raciale cr
					WHERE cr.race_id = ? AND cr.active = '1' AND cr.supprime = '0'
					ORDER BY nom";
			$db->Query( $sql, array( $race_id ) );
			while( $result = $db->GetResult() ){
				$list_capacites_raciales[ $result[ "id" ] ] = array( $result[ "nom" ], $result[ "cout" ] );
			}
			return $list_capacites_raciales;
		}
	}
?>