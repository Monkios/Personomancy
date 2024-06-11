<?php
	class ChoixCapaciteRacialeRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad choix capacite_raciale entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM choix_capacite_raciale 
						WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new CapaciteRaciale();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO choix_capacite_raciale ( nom )
					VALUES ( ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $choix_capacite_raciale ){
			$db = new Database();
			$sql = "UPDATE choix_capacite_raciale SET
					nom = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$choix_capacite_raciale->nom,
					$choix_capacite_raciale->active ? 1 : 0,
					$choix_capacite_raciale->id
			);
			
			$db->Query( $sql, $params );
			$choix_capacite_raciale = $this->Find( $choix_capacite_raciale->id );
			
			return $choix_capacite_raciale != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
		
		public function GetCapacitesRaciales( CapaciteRaciale $choix_capacite_raciale ){
			$capacites_raciales = array();
			$capacite_raciale_repository = new CapaciteRacialeRepository();
			
			$db = new Database();
			$sql = "SELECT ccrcr.capacite_raciale_id
					FROM choix_capacite_raciale_capacite_raciale ccrcr
							LEFT JOIN capacite_raciale cr ON ccrcr.capacite_raciale_id = cr.id
					WHERE cr.supprime = '0' AND ccrcr.choix_capacite_raciale_id = ?
					ORDER BY cr.nom";
			$db->Query( $sql, array( $choix_capacite_raciale->id ) );
			while( $result = $db->GetResult() ){
				$capacites_raciales[ $result[ "capacite_raciale_id" ] ] = $capacite_raciale_repository->Find( $result[ "capacite_raciale_id" ] );
			}
			
			return $capacites_raciales;
		}
		
		public function AddCapaciteRaciale( CapaciteRaciale $choix_capacite_raciale, $capacite_racialeId ){
			$capacites_raciales = $this->GetCapacitesRaciales( $choix_capacite_raciale );
			
			if( !isset( $capacites_raciales[ $capacite_racialeId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO choix_capacite_raciale_capacite_raciale ( choix_capacite_raciale_id, capacite_raciale_id )
						VALUE ( ?, ? )";
				$params = array(
					$choix_capacite_raciale->id,
					$capacite_racialeId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveCapaciteRaciale( CapaciteRaciale $choix_capacite_raciale, $capacite_racialeId ){
			$capacites_raciales = $this->GetCapacitesRaciales( $choix_capacite_raciale );
			
			if( isset( $capacites_raciales[ $capacite_racialeId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM choix_capacite_raciale_capacite_raciale
						WHERE choix_capacite_raciale_id = ? AND capacite_raciale_id = ?";
				$params = array(
					$choix_capacite_raciale->id,
					$capacite_racialeId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
	}
?>