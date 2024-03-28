<?php
	class ConnaissanceRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description,
							cout,
							prereq_capacite,
							prereq_voie_primaire, prereq_voie_secondaire,
							active
					FROM connaissance
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Connaissance();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->description = $result[ "description" ];
				$entity->cout = $result[ "cout" ];

				$entity->prereq_capacite = $result[ "prereq_capacite" ];
				$entity->prereq_voie_primaire = $result[ "prereq_voie_primaire" ];
				$entity->prereq_voie_secondaire = $result[ "prereq_voie_secondaire" ];

				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			if( !in_array( "description", $opts ) ){
				$opts[ "description" ] = "";
			}

			$db = new Database();
			$sql = "INSERT INTO connaissance ( nom, description, prereq_voie_primaire )
					VALUES ( ?, ?, ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ], $opts[ "id_voie" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $connaissance ){
			$db = new Database();
			$sql = "UPDATE connaissance SET
					nom = ?,
					description = ?,
					cout = ?,
					prereq_capacite = ?,
					prereq_voie_primaire = ?,
					prereq_voie_secondaire = ?,
					active = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$connaissance->nom,
					$connaissance->description,
					$connaissance->cout,

					$connaissance->prereq_capacite,
					$connaissance->prereq_voie_primaire,
					$connaissance->prereq_voie_secondaire,

					$connaissance->active ? 1 : 0,					
					$connaissance->id
			);
			
			$db->Query( $sql, $params );
			$connaissance = $this->Find( $connaissance->id );
			
			return $connaissance != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
	}
?>