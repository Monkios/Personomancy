<?php
	class ChoixConnaissanceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad choix connaissance entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM connaissance_categorie 
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
			$sql = "INSERT INTO connaissance_categorie ( nom, active, supprime )
					VALUES ( ?, '0', '0' )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $choix_connaissance ){
			$db = new Database();
			$sql = "UPDATE connaissance_categorie SET
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
			$cr = new ConnaissanceRepository();
			
			$db = new Database();
			$sql = "SELECT ccc.id_connaissance
					FROM connaissance_connaissance_categorie ccc
							LEFT JOIN connaissance c ON ccc.id_connaissance = c.id
					WHERE c.supprime = '0' AND ccc.id_connaissance_categorie = ?
					ORDER BY c.nom";
			$db->Query( $sql, array( $choix_connaissance->id ) );
			while( $result = $db->GetResult() ){
				$connaissances[ $result[ "id_connaissance" ] ] = $cr->Find( $result[ "id_connaissance" ] );
			}
			
			return $connaissances;
		}
		
		public function AddConnaissance( ChoixConnaissance $choix_connaissance, $connaissanceId ){
			$connaissances = $this->GetConnaissances( $choix_connaissance );
			
			if( !isset( $connaissances[ $connaissanceId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO connaissance_connaissance_categorie ( id_connaissance_categorie, id_connaissance )
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
				$sql = "DELETE FROM connaissance_connaissance_categorie
						WHERE id_connaissance_categorie = ? AND id_connaissance = ?";
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