<?php
	class PrestigeRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad prestige entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT p.nom, p.active,
						v.id AS voie_id, v.nom AS voie_nom
					FROM prestige AS p
						LEFT JOIN voie AS v ON v.id = p.voie_id
					WHERE p.id = ? AND p.supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Prestige();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				$entity->voie_id = $result[ "voie_id" ];
				$entity->voie_nom = $result[ "voie_nom" ];

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opt = array() ){}
		public function Save( $prestige ){}
		public function Delete( $id_sort ){}
	}
?>