<?php
	class ChoixPouvoirRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad choix pouvoir entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM pouvoir_categorie 
						WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new ChoixPouvoir();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO pouvoir_categorie ( nom, active, supprime )
					VALUES ( ?, '0', '0' )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $choix_pouvoir ){
			$db = new Database();
			$sql = "UPDATE pouvoir_categorie SET
					nom = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$choix_pouvoir->nom,
					$choix_pouvoir->active ? 1 : 0,
					$choix_pouvoir->id
			);
			
			$db->Query( $sql, $params );
			$choix_pouvoir = $this->Find( $choix_pouvoir->id );
			
			return $choix_pouvoir != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
		
		public function GetPouvoirs( ChoixPouvoir $choix_pouvoir ){
			$pouvoirs = array();
			$pr = new PouvoirRepository();
			
			$db = new Database();
			$sql = "SELECT ppc.id_pouvoir
					FROM pouvoir_pouvoir_categorie ppc
							LEFT JOIN pouvoir p ON ppc.id_pouvoir = p.id
					WHERE p.supprime = '0' AND ppc.id_pouvoir_categorie = ?
					ORDER BY p.nom";
			$db->Query( $sql, array( $choix_pouvoir->id ) );
			while( $result = $db->GetResult() ){
				$pouvoirs[ $result[ "id_pouvoir" ] ] = $pr->Find( $result[ "id_pouvoir" ] );
			}
			
			return $pouvoirs;
		}
		
		public function AddPouvoir( ChoixPouvoir $choix_pouvoir, $pouvoirId ){
			$pouvoirs = $this->GetPouvoirs( $choix_pouvoir );
			
			if( !isset( $pouvoirs[ $pouvoirId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_pouvoir_categorie ( id_pouvoir_categorie, id_pouvoir )
						VALUE ( ?, ? )";
				$params = array(
					$choix_pouvoir->id,
					$pouvoirId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemovePouvoir( ChoixPouvoir $choix_pouvoir, $pouvoirId ){
			$pouvoirs = $this->GetPouvoirs( $choix_pouvoir );
			
			if( isset( $pouvoirs[ $pouvoirId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_pouvoir_categorie
						WHERE id_pouvoir_categorie = ? AND id_pouvoir = ?";
				$params = array(
					$choix_pouvoir->id,
					$pouvoirId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
	}
?>