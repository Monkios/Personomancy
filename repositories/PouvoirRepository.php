<?php
	class PouvoirRepository implements IRepository {
		private $_db = FALSE;
		
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad pouvoir entity ID." );
			}
			
			$this->_db = new Database();
			$sql = "SELECT nom, active,
						affiche,
						bon_constitution, bon_intelligence,
						bon_alerte, bon_spiritisme,
						bon_vigueur, bon_volonte
					FROM pouvoir 
						WHERE id = ? AND supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			if( $result = $this->_db->GetResult() ){
				$entity = new Pouvoir();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				$entity->affiche = $result[ "affiche" ];
				
				$entity->bonus_constitution = $result[ "bon_constitution" ];
				$entity->bonus_intelligence = $result[ "bon_intelligence" ];
				$entity->bonus_alerte = $result[ "bon_alerte" ];
				$entity->bonus_spiritisme = $result[ "bon_spiritisme" ];
				$entity->bonus_vigueur = $result[ "bon_vigueur" ];
				$entity->bonus_volonte = $result[ "bon_volonte" ];

				$entity->list_bonus_capacite = $this->FetchBonusCapacites( $id );
				$entity->list_bonus_connaissance = $this->FetchBonusConnaissances( $id );
				$entity->list_bonus_voie = $this->FetchBonusVoies( $id );
				
				$entity->list_choix_capacite = $this->FetchChoixCapacites( $id );
				$entity->list_choix_connaissance = $this->FetchChoixConnaissances( $id );
				$entity->list_choix_pouvoir = $this->FetchChoixPouvoirs( $id );

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO pouvoir ( nom, active, supprime )
					VALUES ( ?, '0', '0' )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $pouvoir ){
			$db = new Database();
			$sql = "UPDATE pouvoir SET
					nom = ?,
					active = ?,
					affiche = ?,
					bon_alerte = ?,
					bon_constitution = ?,
					bon_intelligence = ?,
					bon_spiritisme = ?,
					bon_vigueur = ?,
					bon_volonte = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$pouvoir->nom,
					$pouvoir->active ? 1 : 0,
					$pouvoir->affiche ? 1 : 0,
					$pouvoir->bonus_alerte,
					$pouvoir->bonus_constitution,
					$pouvoir->bonus_intelligence,
					$pouvoir->bonus_spiritisme,
					$pouvoir->bonus_vigueur,
					$pouvoir->bonus_volonte,
					$pouvoir->id
			);
			
			$db->Query( $sql, $params );
			$pouvoir = $this->Find( $pouvoir->id );
			
			return $pouvoir != FALSE;
		}
		
		public function Delete( $id ){ die( "NotImplementedException()" ); }
		
		private function FetchBonusCapacites( $id ){
			$list = array();
			$cr = new CapaciteRepository();
			
			$sql = "SELECT p.id_capacite
					FROM pouvoir_capacite AS p
						LEFT JOIN capacite AS c ON p.id_capacite = c.id
					WHERE p.id_pouvoir = ? AND c.supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			while( $result = $this->_db->GetResult() ){
				$list[ $result[ "id_capacite" ] ] = $cr->Find( $result[ "id_capacite" ] );
			}
			return $list;
		}
		
		public function AddBonusCapacite( $pouvoir, $capaciteId ){
			if( !isset( $pouvoir->list_bonus_capacite[ $capaciteId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_capacite ( id_pouvoir, id_capacite )
						VALUE ( ?, ? )";
				$params = array(
					$pouvoir->id,
					$capaciteId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveBonusCapacite( $pouvoir, $capaciteId ){
			if( isset( $pouvoir->list_bonus_capacite[ $capaciteId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_capacite
						WHERE id_pouvoir = ? AND id_capacite = ?";
				$params = array(
					$pouvoir->id,
					$capaciteId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchBonusConnaissances( $id ){
			$list = array();
			$cr = new ConnaissanceRepository();
			
			$sql = "SELECT p.id_connaissance
					FROM pouvoir_connaissance AS p
						LEFT JOIN connaissance AS c ON p.id_connaissance = c.id
					WHERE p.id_pouvoir = ? AND c.supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			while( $result = $this->_db->GetResult() ){
				$list[ $result[ "id_connaissance" ] ] = $cr->Find( $result[ "id_connaissance" ] );
			}
			return $list;
		}
		
		public function AddBonusConnaissance( $pouvoir, $connaissanceId ){
			if( !isset( $pouvoir->list_bonus_connaissance[ $connaissanceId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_connaissance ( id_pouvoir, id_connaissance )
						VALUE ( ?, ? )";
				$params = array(
					$pouvoir->id,
					$connaissanceId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveBonusConnaissance( $pouvoir, $connaissanceId ){
			if( isset( $pouvoir->list_bonus_connaissance[ $connaissanceId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_connaissance
						WHERE id_pouvoir = ? AND id_connaissance = ?";
				$params = array(
					$pouvoir->id,
					$connaissanceId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchBonusVoies( $id ){
			$list = array();
			$vr = new VoieRepository();
			
			$sql = "SELECT p.id_voie
					FROM pouvoir_voie AS p
						LEFT JOIN voie AS v ON p.id_voie = v.id
					WHERE p.id_pouvoir = ? AND v.supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			while( $result = $this->_db->GetResult() ){
				$list[ $result[ "id_voie" ] ] = $vr->Find( $result[ "id_voie" ] );
			}
			return $list;
		}
		
		public function AddBonusVoie( $pouvoir, $voieId ){
			if( !isset( $pouvoir->list_bonus_voie[ $voieId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_voie ( id_pouvoir, id_voie )
						VALUE ( ?, ? )";
				$params = array(
					$pouvoir->id,
					$voieId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveBonusVoie( $pouvoir, $voieId ){
			if( isset( $pouvoir->list_bonus_voie[ $voieId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_voie
						WHERE id_pouvoir = ? AND id_voie = ?";
				$params = array(
					$pouvoir->id,
					$voieId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchChoixCapacites( $id ){
			$list = array();
			$ccr = new ChoixCapaciteRepository();
			
			$sql = "SELECT p.id_capacite_categorie
					FROM pouvoir_capacite_categorie AS p
						LEFT JOIN capacite_categorie AS c ON p.id_capacite_categorie = c.id
					WHERE p.id_pouvoir = ? AND c.supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			while( $result = $this->_db->GetResult() ){
				$list[ $result[ "id_capacite_categorie" ] ] = $ccr->Find( $result[ "id_capacite_categorie" ] );
			}
			return $list;
		}
		
		public function AddChoixCapacite( $pouvoir, $choixCapaciteId ){
			if( !isset( $pouvoir->list_choix_capacite[ $choixCapaciteId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_capacite_categorie ( id_pouvoir, id_capacite_categorie, nombre )
						VALUE ( ?, ?, '1' )";
				$params = array(
					$pouvoir->id,
					$choixCapaciteId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveChoixCapacite( $pouvoir, $choixCapaciteId ){
			if( isset( $pouvoir->list_choix_capacite[ $choixCapaciteId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_capacite_categorie
						WHERE id_pouvoir = ? AND id_capacite_categorie = ?";
				$params = array(
					$pouvoir->id,
					$choixCapaciteId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchChoixConnaissances( $id ){
			$list = array();
			$ccr = new ChoixConnaissanceRepository();
			
			$sql = "SELECT p.id_connaissance_categorie
					FROM pouvoir_connaissance_categorie AS p
						LEFT JOIN connaissance_categorie AS c ON p.id_connaissance_categorie = c.id
					WHERE p.id_pouvoir = ? AND c.supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			while( $result = $this->_db->GetResult() ){
				$list[ $result[ "id_connaissance_categorie" ] ] = $ccr->Find( $result[ "id_connaissance_categorie" ] );
			}
			return $list;
		}
		
		public function AddChoixConnaissance( $pouvoir, $choixConnaissanceId ){
			if( !isset( $pouvoir->list_choix_capacite[ $choixConnaissanceId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_connaissance_categorie ( id_pouvoir, id_connaissance_categorie, nombre )
						VALUE ( ?, ?, '1' )";
				$params = array(
					$pouvoir->id,
					$choixConnaissanceId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveChoixConnaissance( $pouvoir, $choixConnaissanceId ){
			if( isset( $pouvoir->list_choix_connaissance[ $choixConnaissanceId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_connaissance_categorie
						WHERE id_pouvoir = ? AND id_connaissance_categorie = ?";
				$params = array(
					$pouvoir->id,
					$choixConnaissanceId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchChoixPouvoirs( $id ){
			$list = array();
			$cpr = new ChoixPouvoirRepository();
			
			$sql = "SELECT p.id_pouvoir_categorie
					FROM pouvoir_pouvoir AS p
						LEFT JOIN pouvoir_categorie AS c ON p.id_pouvoir_categorie = c.id
					WHERE p.id_pouvoir = ? AND c.supprime = '0'";
			$this->_db->Query( $sql, array( $id ) );
			while( $result = $this->_db->GetResult() ){
				$list[ $result[ "id_pouvoir_categorie" ] ] = $cpr->Find( $result[ "id_pouvoir_categorie" ] );
			}
			return $list;
		}
		
		public function AddChoixPouvoir( $pouvoir, $choixPouvoirId ){
			if( !isset( $pouvoir->list_choix_pouvoir[ $choixPouvoirId ] ) ){
				$db = new Database();
				$sql = "INSERT INTO pouvoir_pouvoir ( id_pouvoir, id_pouvoir_categorie, nombre )
						VALUE ( ?, ?, '1' )";
				$params = array(
					$pouvoir->id,
					$choixPouvoirId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
		
		public function RemoveChoixPouvoir( $pouvoir, $choixPouvoirId ){
			if( isset( $pouvoir->list_choix_pouvoir[ $choixPouvoirId ] ) ){
				$db = new Database();
				$sql = "DELETE FROM pouvoir_pouvoir
						WHERE id_pouvoir = ? AND id_pouvoir_categorie = ?";
				$params = array(
					$pouvoir->id,
					$choixPouvoirId
				);
				
				$db->Query( $sql, $params );
				return TRUE;
			}
			return FALSE;
		}
	}
?>