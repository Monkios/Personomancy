<?php
	class VoieRepository implements IRepository {
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad voie entity ID." );
			}
			
			$db = new Database();
			$sql = "SELECT nom, active
					FROM voie 
						WHERE id = ? AND supprime = '0'";
			$db->Query( $sql, array( $id ) );
			if( $result = $db->GetResult() ){
				$entity = new Voie();
				$entity->id = $id;

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "active" ] == 1;

				return $entity;
			}
			
			return FALSE;
		}
		
		public function Create( $opt = array() ){}
		public function Save( $voie ){}
		public function Delete( $id ){}
	}
?>