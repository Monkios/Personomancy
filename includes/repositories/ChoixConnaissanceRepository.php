<?php
	class ChoixConnaissanceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad choix connaissance entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM choix_connaissance 
						WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new ChoixConnaissance();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO choix_connaissance ( nom )
					VALUES ( ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $choix_connaissance ){
			$db = new Database();
			$sql = "UPDATE choix_connaissance SET
					nom = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$choix_connaissance->nom,
					$choix_connaissance->active ? 1 : 0,
					$choix_connaissance->id
			);
			
			$db->Query( $sql, $params );
			$choix_connaissance = $this->Find( $choix_connaissance->id );
			
			return $choix_connaissance != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
		
		public function GetConnaissances( ChoixConnaissance $choix_connaissance ){
			$connaissances = array();
			$connaissance_repository = new ConnaissanceRepository();
			
			$db = new Database();
			$sql = "SELECT ccc.connaissance_id
					FROM choix_connaissance_connaissance ccc
							LEFT JOIN connaissance c ON ccc.connaissance_id = c.id
					WHERE c.supprime = '0' AND ccc.choix_connaissance_id = ?
					ORDER BY c.nom";
			$db->Query( $sql, array( $choix_connaissance->id ) );
			while( $result = $db->GetResult() ){
				$connaissances[ $result[ "connaissance_id" ] ] = $connaissance_repository->Find( $result[ "connaissance_id" ] );
			}
			
			return $connaissances;
		}

		public function GetConnaissancesByChoixId( $choix_id ){
			$choix_connaissance = $this->Find( $choix_id );
			if( $choix_connaissance == FALSE){
				Message::Erreur( "Le choix de capacité doit être existant pour récupérer la liste des capacités." );
				return FALSE;
			}
			return $this->GetConnaissances( $choix_connaissance );
		}
		
		public function AddConnaissance( ChoixConnaissance $choix_connaissance, $connaissanceId ){
			$connaissances = $this->GetConnaissances( $choix_connaissance );
			
			if( !isset( $connaissances[ $connaissanceId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO choix_connaissance_connaissance ( choix_connaissance_id, connaissance_id )
						VALUE ( ?, ? )";
				$params = array(
					$choix_connaissance->id,
					$connaissanceId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveConnaissance( ChoixConnaissance $choix_connaissance, $connaissanceId ){
			$connaissances = $this->GetConnaissances( $choix_connaissance );
			
			if( isset( $connaissances[ $connaissanceId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM choix_connaissance_connaissance
						WHERE choix_connaissance_id = ? AND connaissance_id = ?";
				$params = array(
					$choix_connaissance->id,
					$connaissanceId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
	}
?>