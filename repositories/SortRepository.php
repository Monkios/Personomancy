<?php
	class SortRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad sort entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT s.nom, s.active,
						s.mot_pouvoir, s.niveau AS cercle,
						s.id_sphere AS sphere_id, sp.nom AS sphere_nom,
						s.champ_id, c.description AS champ_nom,
						s.duree_id, d.description AS duree_nom,
						s.portee_id, p.description AS portee_nom,
						s.sauvegarde_id, sa.description AS sauvegarde_nom
					FROM sort s
						LEFT JOIN capacite AS sp ON s.id_sphere = sp.id
						LEFT JOIN sort_champ AS c ON s.champ_id = c.id
						LEFT JOIN sort_duree AS d ON s.duree_id = d.id
						LEFT JOIN sort_portee AS p ON s.portee_id = p.id
						LEFT JOIN sort_sauvegarde AS sa ON s.sauvegarde_id = sa.id
					WHERE s.id = ? AND s.supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Sort();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				$entity->mot_pouvoir = $result[ "mot_pouvoir" ];
				$entity->cercle = $result[ "cercle" ];
				
				$entity->sphere_id = $result[ "sphere_id" ];
				$entity->sphere_nom = $result[ "sphere_nom" ];
				
				$entity->champ_id = $result[ "champ_id" ];
				$entity->champ_nom = $result[ "champ_nom" ];
				
				$entity->duree_id = $result[ "duree_id" ];
				$entity->duree_nom = $result[ "duree_nom" ];
				
				$entity->portee_id = $result[ "portee_id" ];
				$entity->portee_nom = $result[ "portee_nom" ];
				
				$entity->sauvegarde_id = $result[ "sauvegarde_id" ];
				$entity->sauvegarde_nom = $result[ "sauvegarde_nom" ];

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opts = array() ){
			$db = new Database();
			$sql = "INSERT INTO sort ( nom, id_sphere, niveau, active, supprime )
					VALUES ( ?, ?, ?, '0', '0' )";
			
			$db->Query( $sql, array( $opts[ "nom" ], $opts[ "id_sphere" ], $opts[ "cercle" ] ) );
			
			$insert_id = $db->GetInsertId();
			return $this->Find( $insert_id );
		}
		
		public function Save( $sort ){
			$db = new Database();
			$sql = "UPDATE sort SET
					nom = ?,
					active = ?,
					id_sphere = ?,
					niveau = ?
				WHERE supprime = '0' AND id = ?";
			$params = array(
					$sort->nom,
					$sort->active ? 1 : 0,
					$sort->sphere_id,
					$sort->cercle,
					$sort->id
			);
			
			$db->Query( $sql, $params );
			$sort = $this->Find( $sort->id );
			
			return $sort != FALSE;
		}
		
		public function Delete( $id_race ){ die( "Not implemented exception." ); }
	}
?>