<?php
	class ChoixVoieRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad choix voie entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM choix_voie 
						WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new ChoixVoie();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO choix_voie ( nom )
					VALUES ( ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $choix_voie ){
			$db = new Database();
			$sql = "UPDATE choix_voie SET
					nom = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$choix_voie->nom,
					$choix_voie->active ? 1 : 0,
					$choix_voie->id
			);
			
			$db->Query( $sql, $params );
			$choix_voie = $this->Find( $choix_voie->id );
			
			return $choix_voie != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
		
		public function GetVoies( ChoixVoie $choix_voie ){
			$voies = array();
			$voie_repository = new VoieRepository();
			
			$db = new Database();
			$sql = "SELECT ccc.voie_id
					FROM choix_voie_voie ccc
							LEFT JOIN voie c ON ccc.voie_id = c.id
					WHERE c.supprime = '0' AND ccc.choix_voie_id = ?
					ORDER BY c.nom";
			$db->Query( $sql, array( $choix_voie->id ) );
			while( $result = $db->GetResult() ){
				$voies[ $result[ "voie_id" ] ] = $voie_repository->Find( $result[ "voie_id" ] );
			}
			
			return $voies;
		}
		
		public function AddVoie( ChoixVoie $choix_voie, $voieId ){
			$voies = $this->GetVoies( $choix_voie );
			
			if( !isset( $voies[ $voieId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO choix_voie_voie ( choix_voie_id, voie_id )
						VALUE ( ?, ? )";
				$params = array(
					$choix_voie->id,
					$voieId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveVoie( ChoixVoie $choix_voie, $voieId ){
			$voies = $this->GetVoies( $choix_voie );
			
			if( isset( $voies[ $voieId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM choix_voie_voie
						WHERE choix_voie_id = ? AND voie_id = ?";
				$params = array(
					$choix_voie->id,
					$voieId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
	}
?>