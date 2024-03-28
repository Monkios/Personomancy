<?php
	class RaceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad race entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM race 
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Race();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				$entity->list_capacites_raciales = $this->FetchCapacitesRaciales( $id );

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO race ( nom )
					VALUES ( ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $race ){
			$capacites_raciales = $race->list_capacites_raciales;
			
			$db = new Database();
			$sql = "UPDATE race SET
					nom = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$race->nom,
					$race->active ? 1 : 0,
					$race->id
			);
			
			$db->Query( $sql, $params );
			$race = $this->Find( $race->id );
			
			foreach( $race->list_capacites_raciales as $id => $capacite_raciale ){
				if( $capacite_raciale[ 1 ] != $capacites_raciales[ $id ][ 1 ] ){
					$this->UpdateCoutCR( $race->id, $id, $capacites_raciales[ $id ][ 1 ] );
				}
			}
			
			return $race != FALSE;
		}
		
		public function Delete( $id_race ){ die( "NotImplementedException()" ); }
		
		public function AddCapaciteRaciale( $race, $pouvoirId, $cout ){
			if( !isset( $race->list_capacites_raciales[ $pouvoirId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO race_pouvoir ( id_race, id_pouvoir, cout )
						VALUE ( ?, ?, ? )";
				$params = array(
					$race->id,
					$pouvoirId,
					$cout
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveCapaciteRaciale( $race, $pouvoirId ){
			if( isset( $race->list_capacites_raciales[ $pouvoirId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM race_pouvoir
						WHERE id_race = ? AND id_pouvoir = ?";
				$params = array(
					$race->id,
					$pouvoirId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchCapacitesRaciales( $race_id ){
			$list_capacites_raciales = array();
			
			$db = new Database();
			$sql = "SELECT p.id, p.nom, x.cout
					FROM race_pouvoir x
						LEFT JOIN pouvoir AS p On p.id = x.id_pouvoir
					WHERE x.id_race = ? AND p.supprime = '0'
					ORDER BY nom";
			$db->Query( $sql, array( $race_id ) );
			while( $result = $db->GetResult() ){
				$list_capacites_raciales[ $result[ "id" ] ] = array( $result[ "nom" ], $result[ "cout" ] );
			}
			return $list_capacites_raciales;
		}
		
		private function UpdateCoutCR( $raceId, $pouvoirId, $cout ){
			$db = new Database();
			$sql = "UPDATE race_pouvoir SET
					cout = ?
				WHERE id_race = ? AND id_pouvoir = ?";
			$params = array(
					$cout,
					$raceId,
					$pouvoirId
			);
			
			$db->Query( $sql, $params );
		}
	}
?>