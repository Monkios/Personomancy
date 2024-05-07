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

				//$entity->list_capacites_raciales = $this->FetchCapacitesRaciales( $id );

				return $entity;
			}
			
			return FALSE;
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
			
			/*foreach( $race->list_capacites_raciales as $id => $capacite_raciale ){
				if( $capacite_raciale[ 1 ] != $capacites_raciales[ $id ][ 1 ] ){
					$this->UpdateCoutCR( $race->id, $id, $capacites_raciales[ $id ][ 1 ] );
				}
			}*/
			
			return $race != FALSE;
		}
		
		public function Delete( $id_race ){ die( "NotImplementedException()" ); }
	}
?>