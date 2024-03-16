<?php
	class ConnaissanceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active,
							statistique, statistique_niveau,
							statistique_sec, statistique_sec_niveau,
							capacite, capacite_niveau,
							capacite_sec, capacite_sec_niveau,
							connaissance, connaissance_sec,
							voie,
							divinite
					FROM connaissance
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Connaissance();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				$entity->prereq_statistique_prim_id = $result[ "statistique" ];
				$entity->prereq_statistique_prim_sel = $result[ "statistique_niveau" ];
				$entity->prereq_statistique_sec_id = $result[ "statistique_sec" ];
				$entity->prereq_statistique_sec_sel = $result[ "statistique_sec_niveau" ];
				$entity->prereq_capacite_prim_id = $result[ "capacite" ];
				$entity->prereq_capacite_prim_sel = $result[ "capacite_niveau" ];
				$entity->prereq_capacite_sec_id = $result[ "capacite_sec" ];
				$entity->prereq_capacite_sec_sel = $result[ "capacite_sec_niveau" ];
				$entity->prereq_connaissance_prim_id = $result[ "connaissance" ];
				$entity->prereq_connaissance_sec_id = $result[ "connaissance_sec" ];
				$entity->prereq_voie_id = $result[ "voie" ];
				$entity->prereq_divin_id = $result[ "divinite" ];

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO connaissance ( nom, active, supprime )
					VALUES ( ?, '0', '0' )";
			
			$db->Query( $sql, array( $opts[ "nom" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $connaissance ){
			$db = new Database();
			$sql = "UPDATE connaissance SET
					nom = ?,
					active = b?,
					statistique = ?,
					statistique_niveau = ?,
					statistique_sec = ?,
					statistique_sec_niveau = ?,
					capacite = ?,
					capacite_niveau = ?,
					capacite_sec = ?,
					capacite_sec_niveau = ?,
					connaissance = ?,
					connaissance_sec = ?,
					voie = ?,
					divinite = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$connaissance->nom,
					$connaissance->active ? 1 : 0,
					$connaissance->prereq_statistique_prim_id,
					$connaissance->prereq_statistique_prim_sel,
					$connaissance->prereq_statistique_sec_id,
					$connaissance->prereq_statistique_sec_sel,
					$connaissance->prereq_capacite_prim_id,
					$connaissance->prereq_capacite_prim_sel,
					$connaissance->prereq_capacite_sec_id,
					$connaissance->prereq_capacite_sec_sel,
					$connaissance->prereq_connaissance_prim_id,
					$connaissance->prereq_connaissance_sec_id,
					$connaissance->prereq_voie_id,
					$connaissance->prereq_divin_id,
					
					$connaissance->id
			);
			
			$db->Query( $sql, $params );
			$connaissance = $this->Find( $connaissance->id );
			
			return $connaissance != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
	}
?>