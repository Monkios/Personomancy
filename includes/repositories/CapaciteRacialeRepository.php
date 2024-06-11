<?php
	class CapaciteRacialeRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad capacite entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, description, active,
						cout, race_id, choix_capacite_bonus_id, choix_connaissance_bonus_id, choix_capacite_raciale_bonus_id, choix_voie_bonus_id
					FROM capacite_raciale
					WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new CapaciteRaciale();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->description = $result[ "description" ];

				$entity->active = $result[ "active" ] == 1;

				$entity->cout = $result[ "cout" ];
				$entity->race_id = $result[ "race_id" ];

				$entity->choix_capacite_bonus_id = $result[ "choix_capacite_bonus_id" ];
				$entity->choix_connaissance_bonus_id = $result[ "choix_connaissance_bonus_id" ];
				$entity->choix_capacite_raciale_bonus_id = $result[ "choix_capacite_raciale_bonus_id" ];
				$entity->choix_voie_bonus_id = $result[ "choix_voie_bonus_id" ];

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			if( !in_array( "description", $opts ) ){
				$opts[ "description" ] = "";
			}

			$db = new Database();
			$sql = "INSERT INTO capacite_raciale ( nom, description, race_id )
					VALUES ( ?, ?, ? )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "description" ], $opts[ "id_race" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $capacite_raciale ){
			$db = new Database();
			$sql = "UPDATE capacite_raciale SET
					nom = ?,
					description = ?,
					active = ?,
					cout = ?,
					race_id = ?,
					choix_capacite_bonus_id = ?,
					choix_connaissance_bonus_id = ?,
					choix_capacite_raciale_bonus_id = ?,
					choix_voie_bonus_id = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$capacite_raciale->nom,
					$capacite_raciale->description,
					$capacite_raciale->active ? 1 : 0,
					$capacite_raciale->cout,
					$capacite_raciale->race_id,
					$capacite_raciale->choix_capacite_bonus_id,
					$capacite_raciale->choix_connaissance_bonus_id,
					$capacite_raciale->choix_capacite_raciale_bonus_id,
					$capacite_raciale->choix_voie_bonus_id,
					$capacite_raciale->id
			);
			
			$db->Query( $sql, $params );
			$capacite_raciale = $this->Find( $capacite_raciale->id );
			
			return $capacite_raciale != FALSE;
		}
		
		public function Delete( $id ){ die( "Not implemented exception." ); }
	}
?>