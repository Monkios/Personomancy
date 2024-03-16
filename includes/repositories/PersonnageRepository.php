<?php
	class PersonnageRepository implements IRepository {
		private $_db = FALSE;
		
		public function __construct(){
			$this->_db = new Database();
		}
		
		public function Find( int $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad personnage entity ID.", func_get_args() );
				return FALSE;
			}
			
			$where = "p.id = ?";
			$order_by = "";
			
			$characters_list = $this->FetchCharacterList( $order_by, $where, array( $id ) );
			return array_shift( $characters_list );
		}
		
		public function FindComplete( int $id ) {
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad personnage complet entity ID.", func_get_args() );
				return FALSE;
			}
			
			$personnage = $this->Find( $id );
			if( $personnage && $this->Complete( $personnage, TRUE ) ){
				return $personnage;
			}
			return FALSE;
		}
		
		public function FindAll( string $sort_by = "character" ){
			$order_by = "";
			switch( $sort_by ) {
				case "character" :
					$order_by = "TRIM( p.nom ), TRIM( CONCAT( j.prenom, ' ', j.nom ) ), pj.quand DESC";
					break;
				case "player" :
					$order_by = "TRIM( CONCAT( j.prenom, ' ', j.nom ) ), TRIM( p.nom ), pj.quand DESC";
					break;
				default :
					Message::Fatale( "Unknown sort : " . $sort_by );
			}
			return $this->FetchCharacterList( $order_by );
		}
		
		public function FindAllByPlayerId( $player_id ){
			if( !is_numeric( $player_id ) ){
				Message::Fatale( "Bad personnage entity ID.", func_get_args() );
				return FALSE;
			}
			
			$where = "p.joueur = ?";
			$order_by = "p.est_vivant DESC, TRIM( p.nom ), pj.quand DESC";
			
			return $this->FetchCharacterList( $order_by, $where, array( $player_id ) );
		}
		
		public function FindAllAlives( $sort_by = "character" ){
			$where = "p.est_vivant = '1'";
			switch( $sort_by ) {
				case "character" :
					$order_by = "TRIM( p.nom ), TRIM( CONCAT( j.prenom, ' ', j.nom ) ), pj.quand DESC";
					break;
				case "player" :
					$order_by = "TRIM( CONCAT( j.prenom, ' ', j.nom ) ), TRIM( p.nom ), pj.quand DESC";
					break;
				default :
					Message::Fatale( "Unknown sort : " . $sort_by );
			}
			return $this->FetchCharacterList( $order_by, $where );
		}
		
		public function FindAllDeads(){
			$where = "p.est_vivant != '1'";
			$order_by = "TRIM( p.nom ), TRIM( CONCAT( j.prenom, ' ', j.nom ) ), pj.quand DESC";
			
			return $this->FetchCharacterList( $order_by, $where );
		}
		
		public function GetAliveCountByPlayerId( $player_id ){
			if( !is_numeric( $player_id ) ){
				Message::Fatale( "Bad personnage entity ID.", func_get_args() );
				return FALSE;
			}
			
			$where = "p.est_vivant = '1' AND p.joueur = ?";
			$order_by = "";
			return count( $this->FetchCharacterList( $order_by, $where, array( $player_id ) ) );
		}
		
		public function Create( array $opts = array() ){
			if( !array_key_exists( "cite_etat", $opts ) || $opts[ "cite_etat" ] != "" && !is_numeric( $opts[ "cite_etat" ] ) ){
				Message::Fatale( "Cité-état invalide.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "name", $opts ) || $opts[ "name" ] == "" ){
				Message::Fatale( "Nom invalide.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "player", $opts ) || !is_numeric( $opts[ "player" ] ) ){
				Message::Fatale( "Joueur invalide.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "croyance", $opts ) || !is_numeric( $opts[ "croyance" ] ) ){
				Message::Fatale( "Croyance invalide.", func_get_args() );
				return FALSE;
			}
			
			// Insertion
			$sql = "INSERT INTO personnage ( joueur, nom, race_id, cite_etat_id, croyance_id, point_capacite_raciale, point_experience, total_experience, commentaire, notes )
						VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
			$params = array(
					$opts[ "player_id" ],
					$opts[ "name" ],
					$opts[ "race_id" ],
					$opts[ "cite_etat_id" ],
					$opts[ "croyance_id" ],
					CHARACTER_BASE_PCR,
					CHARACTER_BASE_XP,
					CHARACTER_BASE_XP,
					CHARACTER_DEFAULT_COMMENTS,
					CHARACTER_DEFAULT_NOTES
			);
			
			$this->_db->Query( $sql, $params );
			$insert_id = $this->_db->GetInsertId();
			
			$character = $this->FindComplete( $insert_id );
			if( !$character ){
				$this->Delete( $insert_id );
				return FALSE;
			}
			
			return $character;
		}
		
		public function Save( GenericEntity $personnage ){
			if( !$personnage->est_complet ){
				$sql = "UPDATE personnage SET
						joueur = ?,
						nom = ?,
						race_id = ?,
						croyance_id = ?,
						cite_etat_id = ?,
						point_capacite_raciale = ?,
						point_experience = ?,
						total_experience = ?,
						est_vivant = ?,
						est_cree = ?,
						commentaire = ?,
						notes = ?
					WHERE est_detruit = '0' AND id = ?";
				$params = array(
						$personnage->joueur_id,
						$personnage->nom,
						$personnage->race_id,
						$personnage->croyance_id,
						$personnage->cite_etat_id,
						$personnage->pc_raciales,
						$personnage->px_restants,
						$personnage->px_totaux,
						$personnage->est_vivant ? 1 : 0,
						$personnage->est_cree ? 1 : 0,
						$personnage->commentaire,
						$personnage->notes,
						$personnage->id
				);
			} else {
				$sql = "UPDATE personnage SET
						joueur = ?,
						nom = ?,
						race_id = ?,
						croyance_id = ?,
						cite_etat_id = ?,
						point_capacite_raciale = ?,
						point_experience = ?,
						total_experience = ?,
						est_vivant = ?,
						est_cree = ?,
						commentaire = ?,
						notes = ?
					WHERE est_detruit = '0' AND id = ?";
				$params = array(
						$personnage->joueur_id,
						$personnage->nom,
						$personnage->race_id,
						$personnage->croyance_id,
						$personnage->cite_etat_id,
						$personnage->pc_raciales,
						$personnage->px_restants,
						$personnage->px_totaux,
						$personnage->est_vivant ? 1 : 0,
						$personnage->est_cree ? 1 : 0,
						$personnage->commentaire,
						$personnage->notes,
						$personnage->id
				);
			}
			
			$this->_db->Query( $sql, $params );
			$personnage = $this->FindComplete( $personnage->id );
			
			return $personnage != FALSE;
		}
		
		public function Activate( PersonnagePartiel &$personnage ){
			if( $personnage->est_cree == FALSE ){
				$personnage->est_cree = TRUE;
				
				return $this->Save( $personnage );
			}
			
			return FALSE;
		}
		
		public function Deactivate( PersonnagePartiel &$personnage ){
			if( $personnage->est_vivant == TRUE ){
				$personnage->est_vivant = FALSE;
				
				return $this->Save( $personnage );
			}
			
			return FALSE;
		}
		
		public function Delete( int $id ){
			if( is_numeric( $id ) ){
				$sql = "UPDATE personnage SET est_detruit = '1' WHERE id = ?";
				$this->_db->Query( $sql, array( $id ) );
				
				return TRUE;
			}
			
			return FALSE;
		}
		
		public function AddRace( Personnage &$personnage, $race_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( !empty( $personnage->race_id ) && $personnage->race_id != -1 ){
					Message::Fatale( "Une race a déjà été attribuée à ce personnage.", func_get_args() );
					return FALSE;
				}
				
				$race_repository = new RaceRepository();
				$race = $race_repository->Find( $race_id );
				if( $race == FALSE ){
					Message::Fatale( "Race à ajouter invalide.", func_get_args() );
					return FALSE;
				}
				
				if( $race->active ){
					$personnage->race_id = $race_id;

					return $this->Save( $personnage );
				}
			}
			
			return FALSE;
		}
		
		public function RemoveRace( Personnage &$personnage ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( empty( $personnage->race_id ) || $personnage->race_id == -1 ){
					Message::Fatale( "Ce personnage n'a pas de race à retirer.", func_get_args() );
					return FALSE;
				}
				
				$race_repository = new RaceRepository();
				$race = $race_repository->Find( $personnage->race_id );
				if( $race == FALSE ){
					Message::Fatale( "Race à retirer invalide.", func_get_args() );
					return FALSE;
				}
				
				$personnage->race_id = -1;
				return $this->Save( $personnage );
			}
			
			return FALSE;
		}
		
		public function BuyCapaciteRaciale( Personnage &$personnage, $id_capacite_raciale ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				$race_repository = new RaceRepository();
				$race = $race_repository->Find( $personnage->race_id );
				if( $race->active
						&& array_key_exists( $id_capacite_raciale, $race->list_capacites_raciales )
						&& $race->list_capacites_raciales[ $id_capacite_raciale ][ 1 ] <= $personnage->pc_raciales
						&& $this->AddPouvoir( $personnage, $id_capacite_raciale ) ){
					$personnage->pc_raciales -= $race->list_capacites_raciales[ $id_capacite_raciale ][ 1 ];
					$this->Save( $personnage );
						
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			
			return FALSE;
		}
		
		public function RefundCapaciteRaciale( Personnage &$personnage, $id_capacite_raciale, $cout = FALSE ){
			if( $personnage
					&& $personnage->est_vivant
					&& $personnage->est_complet
					&& array_key_exists( $id_capacite_raciale, $personnage->capacites_raciales )
					&& $this->RemovePouvoir( $personnage, $id_capacite_raciale ) ){
				// Si l'ancien cout a ete fourni, on l'utilise
				if( $cout !== FALSE ){
					$personnage->pc_raciales += $cout;
				// Sinon, on prend celui de la race
				} else {
					$race_repository = new RaceRepository();
					$race = $race_repository->Find( $personnage->race_id );
					$personnage->pc_raciales -= $race->list_capacites_raciales[ $id_capacite_raciale ][ 1 ];
				}
				$this->Save( $personnage );
						
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function AddVoie( Personnage &$personnage, $id_voie ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetVoies( $id_voie ) ) == 0 ){
					Message::Fatale( "Bad voie ID", func_get_args() );
					return FALSE;
				}
				
				if( !in_array( $id_voie, $personnage->voies ) ){
					$sql = "INSERT INTO personnage_voie ( id_personnage, id_voie )
							VALUES ( ?, ? )";
					$this->_db->Query( $sql, array( $personnage->id, $id_voie ) );
					
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			
			return FALSE;
		}
		
		public function RemoveVoie( Personnage &$personnage, $id_voie ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetVoies( $id_voie ) ) == 0 ){
					Message::Fatale( "Bad voie ID", func_get_args() );
					return FALSE;
				}
				
				if( !in_array( $id_voie, $personnage->voies ) ){
					Message::Fatale( "Character must have voie to remove", func_get_args() );
					return FALSE;
				}
				
				$sql = "DELETE FROM personnage_voie
						WHERE id_personnage = ? AND id_voie = ?";
				$this->_db->Query( $sql, array( $personnage->id, $id_voie ) );
					
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function AddCapacite( Personnage &$personnage, $id_capacite, $nb_selections ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetCapacites( $id_capacite ) ) == 0 ){
					Message::Fatale( "Bad capacité ID", func_get_args() );
					return FALSE;
				}
				
				if( is_numeric( $nb_selections )
						&& $nb_selections > 0
						&& ( $nb_selections + $personnage->capacites[ $id_capacite ] ) <= CHARACTER_MAX_CAPACITES_SELECTIONS
						&& ( $nb_selections + $personnage->capacites[ $id_capacite ] ) <= 20 // HARD LIMIT (BD = ENUM)
						&& $this->HasPrerequisCapacite( $personnage, $id_capacite ) ){
					if( !array_key_exists( $id_capacite, $personnage->capacites ) || $personnage->capacites[ $id_capacite ] == 0 ){
						$sql = "INSERT INTO personnage_capacite( niveau, id_personnage, id_capacite )
								VALUES ( ?, ?, ? )";
					} else {
						$sql = "UPDATE personnage_capacite
								SET niveau = ( niveau * 1 ) + ?
								WHERE id_personnage = ? AND id_capacite = ?";
					}
					$this->_db->Query( $sql, array( $nb_selections, $personnage->id, $id_capacite ) );
						
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			
			return FALSE;
		}
		
		public function RemoveCapacite( Personnage &$personnage, $id_capacite, $nb_selections ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetCapacites( $id_capacite ) ) == 0 ){
					Message::Fatale( "Bad capacité ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_capacite, $personnage->capacites ) || $personnage->capacites[ $id_capacite ] == 0 ){
					Message::Fatale( "Character must have capacite to remove", func_get_args() );
					return FALSE;
				}
				
				if( is_numeric( $nb_selections )
						&& $nb_selections <= $personnage->capacites[ $id_capacite ] ){
					if( $personnage->capacites[ $id_capacite ] == $nb_selections ){
						$sql = "DELETE FROM personnage_capacite
								WHERE niveau = ?
									AND id_personnage = ?
									AND id_capacite = ?";
					} else {
						$sql = "UPDATE personnage_capacite
								SET niveau = ( niveau * 1 ) - ?
								WHERE id_personnage = ? AND id_capacite = ?";
					}
					$this->_db->Query( $sql, array( $nb_selections, $personnage->id, $id_capacite ) );
						
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			
			return FALSE;
		}
		
		public function AddConnaissance( Personnage &$personnage, $id_connaissance ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetConnaissances( $id_connaissance ) ) == 0 ){
					Message::Fatale( "Bad connaissance ID", func_get_args() );
					return FALSE;
				}
				
				if( !in_array( $id_connaissance, $personnage->connaissances )
						&& $this->HasPrerequisConnaissance( $personnage, $id_connaissance ) ){
					$sql = "INSERT INTO personnage_connaissance ( id_personnage, id_connaissance )
							VALUES ( ?, ? )";
					$this->_db->Query( $sql, array( $personnage->id, $id_connaissance ) );
					
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			
			return FALSE;
		}
		
		public function RemoveConnaissance( Personnage &$personnage, $id_connaissance ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetConnaissances( $id_connaissance ) ) == 0 ){
					Message::Fatale( "Bad connaissance ID", func_get_args() );
					return FALSE;
				}
				
				if( !in_array( $id_connaissance, $personnage->connaissances ) ){
					Message::Fatale( "Character must have connaissance to remove", func_get_args() );
					return FALSE;
				}
				
				$sql = "DELETE FROM personnage_connaissance
						WHERE id_personnage = ? AND id_connaissance = ?";
				$this->_db->Query( $sql, array( $personnage->id, $id_connaissance ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		private function AddChoixCapacite( Personnage &$personnage, $id_choix_capacite ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixCapacites( $id_choix_capacite ) ) == 0 ){
					Message::Fatale( "Bad choix capacité ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_choix_capacite, $personnage->choix_capacites ) ){
					$sql = "INSERT INTO personnage_capacite_categorie ( id_personnage, id_capacite_categorie, nombre )
							VALUES ( ?, ?, '1' )";
				} else {
					$sql = "UPDATE personnage_capacite_categorie
							SET nombre = ( nombre * 1 ) + 1
							WHERE id_personnage = ? AND id_capacite_categorie = ?";
				}
				$this->_db->Query( $sql, array( $personnage->id, $id_choix_capacite ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function BuyChoixCapacite( Personnage &$personnage, $list_choix_id, $choix_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixCapacites( $list_choix_id ) ) == 0 ){
					Message::Fatale( "Bad buy choix capacité ID", func_get_args() );
					return FALSE;
				}
				
				if( array_key_exists( $list_choix_id, $personnage->choix_capacites ) && $personnage->choix_capacites[ $list_choix_id ] > 0 ){
					$choix_capacite_repository = new ChoixCapaciteRepository();
					$choix_capacite = $choix_capacite_repository->Find( $list_choix_id );
					if( $choix_capacite && $choix_capacite->active ){
						$liste_capacites = $choix_capacite_repository->GetCapacites( $choix_capacite );
						if( array_key_exists( $choix_id, $liste_capacites ) ){
							if( $this->AddCapacite( $personnage, $choix_id, 1 ) ){
								if( $this->RemoveChoixCapacite( $personnage, $list_choix_id ) == FALSE ){
									$this->RemoveCapacite( $personnage, $choix_id, 1 );
									return FALSE;
								}
								
								$personnage = $this->FindComplete( $personnage->id );
								return $personnage != FALSE;
							}
						}
					}
				}	
			}
			
			return FALSE;
		}
		
		private function RemoveChoixCapacite( Personnage &$personnage, $id_choix_capacite ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixCapacites( $id_choix_capacite ) ) == 0 ){
					Message::Fatale( "Bad choix capacité ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_choix_capacite, $personnage->choix_capacites ) || $personnage->choix_capacites[ $id_choix_capacite ] == 0 ){
					Message::Fatale( "Character must have choix_capacite to remove", func_get_args() );
					return FALSE;
				}
				
				if( $personnage->choix_capacites[ $id_choix_capacite ] == 1 ){
					$sql = "DELETE FROM personnage_capacite_categorie
							WHERE id_personnage = ? AND id_capacite_categorie = ?";
				} else {
					$sql = "UPDATE personnage_capacite_categorie
							SET nombre = ( nombre * 1 ) - 1
							WHERE id_personnage = ? AND id_capacite_categorie = ?";
				}
				$this->_db->Query( $sql, array( $personnage->id, $id_choix_capacite ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function RefundChoixCapacite( Personnage &$personnage, $list_choix_id, $choix_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixCapacites( $list_choix_id ) ) == 0 ){
					Message::Fatale( "Bad refund choix capacité ID", func_get_args() );
					return FALSE;
				}
				
				if( array_key_exists( $choix_id, $personnage->capacites ) && $personnage->capacites[ $choix_id ] > 0 ){
					$choix_capacite_repository = new ChoixCapaciteRepository();
					$choix_capacite = $choix_capacite_repository->Find( $list_choix_id );
					if( $choix_capacite ){
						$liste_capacites = $choix_capacite_repository->GetCapacites( $choix_capacite );
						if( array_key_exists( $choix_id, $liste_capacites ) ){
							if( $this->RemoveCapacite( $personnage, $choix_id, 1 ) ){
								if( $this->AddChoixCapacite( $personnage, $list_choix_id ) == FALSE ){
									$this->AddCapacite( $personnage, $choix_id, 1 );
									return FALSE;
								}
								
								$personnage = $this->FindComplete( $personnage->id );
								return $personnage != FALSE;
							}
						}
					}
				}	
			}
			
			return FALSE;
		}
				
		private function HasPrerequisCapacite( Personnage &$personnage, $id_capacite ){
			if( $personnage ){
				$capacite_repository = new CapaciteRepository();
				$capacite = $capacite_repository->Find( $id_capacite );
				
				return $capacite !== FALSE && $capacite->active
						&& in_array( $capacite->voie_id, $personnage->voies );
			}
			return FALSE;
		}
		
		private function FetchCharacterList( $order_by = "", $where = "", $params = array() ){
			$list = array();
			
			$sql = "SELECT p.id, p.nom, p.est_vivant, p.est_cree,
						p.point_experience, p.total_experience, p.point_capacite_raciale,
						pj.quand AS last_update,
						CONCAT( u.prenom, ' ', u.nom ) AS user_update,
						j.id AS player_id,
						CONCAT( j.prenom, ' ', j.nom ) AS player_name,
						ce.id AS cite_etat_id,
						ce.nom AS cite_etat_nom,
						ra.id AS race_id,
						ra.nom AS race_nom,
						cr.id AS croyance_id,
						cr.nom AS croyance_nom,
						p.notes, p.commentaire
					FROM personnage AS p
						LEFT JOIN personnage_journal pj ON pj.id = ( SELECT id FROM personnage_journal WHERE active = '1' AND personnage_id = p.id ORDER BY quand DESC LIMIT 1 )
						LEFT JOIN joueur u ON pj.joueur_id = u.id
						LEFT JOIN joueur j ON p.joueur = j.id
						LEFT JOIN cite_etat ce ON p.cite_etat_id = ce.id
						LEFT JOIN race ra ON p.race_id = ra.id
						LEFT JOIN croyance cr ON p.croyance_id = cr.id
					WHERE p.est_detruit = '0'";
			
			if( $where != "" ){
				$sql .= " AND " . $where;
			} else {
				$params = array();
			}
			
			if( $order_by != "" ){
				$sql .= " ORDER BY " . $order_by;
			}
			
			$db = new Database();
			$db->Query( $sql, $params );
			while( $result = $db->GetResult() ){
				$entity = new Personnage();
				$entity->id = $result[ "id" ];

				$entity->nom = $result[ "nom" ];
				$entity->active = $result[ "est_vivant" ] == 1;
				$entity->est_vivant = $result[ "est_vivant" ] == 1;
				$entity->est_cree = $result[ "est_cree" ] == 1;
				
				$entity->joueur_id = $result[ "player_id" ];
				$entity->joueur_nom = $result[ "player_name" ];
				
				$entity->px_restants = $result[ "point_experience" ];
				$entity->px_totaux = $result[ "total_experience" ];
				$entity->pc_raciales = $result[ "point_capacite_raciale" ];
				
				$entity->dernier_changement_date = $result[ "last_update" ];
				$entity->dernier_changement_par = $result[ "user_update" ];
				
				$entity->cite_etat_id = $result[ "cite_etat_id" ];
				$entity->cite_etat_nom = $result[ "cite_etat_nom" ];
				$entity->race_id = $result[ "race_id" ];
				$entity->race_nom = $result[ "race_nom" ];
				$entity->croyance_id = $result[ "croyance_id" ];
				$entity->croyance_nom = $result[ "croyance_nom" ];
				
				$entity->notes = $result[ "notes" ];
				$entity->commentaire = $result[ "commentaire" ];
				
				$list[ $result[ "id" ] ] = $entity;
			}
			
			return $list;
		}
		
		private function Complete( Personnage &$personnage, $force = FALSE ){
			if( $personnage->est_complet && !$force ){
				// Le personnage a déjà été complété
				return true;
			}
			// Chaine les appels de construction
			if(	$this->FetchCapacites( $personnage )
					&& $this->FetchCapacitesRaciales( $personnage )
					&& $this->FetchChoixCapacites( $personnage )
					&& $this->FetchConnaissances( $personnage )
					&& $this->FetchConnaissancesAccessibles( $personnage )
					&& $this->FetchVoies( $personnage ) ){
				$personnage->est_complet = TRUE;
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchCapacitesRaciales( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT po.id, po.nom
						FROM personnage p
							LEFT JOIN personnage_pouvoir pp ON pp.id_personnage = p.id AND pp.id_pouvoir IN ( SELECT id_pouvoir FROM race_pouvoir )
							LEFT JOIN pouvoir po ON po.id = pp.id_pouvoir
						WHERE p.id = ?
						ORDER BY po.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->capacites_raciales[ $result[ "id" ] ] = $result[ "nom" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchCapacites( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT c.id AS id_capacite, COALESCE( pc.niveau, 0 ) AS niveau
						FROM personnage_voie AS pv
							LEFT JOIN capacite AS c ON c.voie = pv.id_voie
							LEFT JOIN personnage_capacite AS pc ON pc.id_personnage = pv.id_personnage AND pc.id_capacite = c.id
						WHERE pv.id_personnage = ?";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->capacites[ $result[ "id_capacite" ] ] = $result[ "niveau" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchChoixCapacites( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT c.id, x.nombre
						FROM capacite_categorie AS c
							LEFT JOIN personnage_capacite_categorie AS x ON c.id = x.id_capacite_categorie
						WHERE x.id_personnage = ? And c.active = '1' And c.supprime = '0'
						ORDER BY c.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->choix_capacites[ $result[ "id" ] ] = $result[ "nombre" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchConnaissances( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT c.id,
								CASE
									WHEN ( c.statistique > 0 AND c.statistique_niveau = 10 ) OR
										( c.capacite > 0 AND c.capacite_niveau = 10 ) THEN 'L'
									WHEN ( c.statistique > 0 AND c.statistique_niveau = 7 ) OR
										( c.capacite > 0 AND c.capacite_niveau = 7 ) THEN 'M'
									WHEN ( c.statistique_sec > 0 AND c.statistique_sec_niveau = 5 ) OR
										( c.capacite_sec > 0 AND c.capacite_sec_niveau = 5 ) THEN 'S'
									WHEN ( c.statistique > 0 AND c.statistique_niveau = 5 ) OR
										( c.capacite > 0 AND c.capacite_niveau = 5 ) THEN 'A'
									ELSE 'B'
								END AS type
						FROM personnage_connaissance pc
							LEFT JOIN connaissance c ON pc.id_connaissance = c.id
						WHERE pc.id_personnage = ?
						ORDER BY c.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					switch( $result[ "type" ] ){
						case "B" :
							$personnage->connaissances_base[] = $result[ "id" ];
							break;
						case "A" :
							$personnage->connaissances_avancees[] = $result[ "id" ];
							break;
						case "S" :
							$personnage->connaissances_synergiques[] = $result[ "id" ];
							break;
						case "M" :
							$personnage->connaissances_maitres[] = $result[ "id" ];
							break;
						case "L" :
							$personnage->connaissances_legendaires[] = $result[ "id" ];
							break;
					}
					$personnage->connaissances[] = $result[ "id" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchConnaissancesAccessibles( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT c.id
						FROM connaissance c,
							personnage p
						WHERE p.id = ?
								AND c.active = '1' AND c.supprime = '0'
								AND ( c.voie = 0 OR c.voie IN
										( SELECT id_voie FROM personnage_voie WHERE id_personnage = p.id )
								)
								AND ( c.capacite = 0 OR c.capacite IN
										( SELECT id_capacite FROM personnage_capacite WHERE id_personnage = p.id AND niveau - 1 >= c.capacite_niveau )
								)
								AND ( c.capacite_sec = 0 OR c.capacite_sec IN
										( SELECT id_capacite FROM personnage_capacite WHERE id_personnage = p.id AND niveau - 1 >= c.capacite_niveau )
								)
								AND ( c.connaissance = 0 OR c.connaissance IN
										( SELECT id_connaissance FROM personnage_connaissance WHERE id_personnage = p.id )
								)
								AND ( c.connaissance_sec = 0 OR c.connaissance_sec IN
										( SELECT id_connaissance FROM personnage_connaissance WHERE id_personnage = p.id )
								)
								AND ( c.divinite = 0 OR c.divinite = p.croyance )
								AND ( c.statistique = 0
									 OR ( c.statistique = 1 AND statistique_niveau <= p.st_constitution )
									 OR ( c.statistique = 2 AND statistique_niveau <= p.st_intelligence )
									 OR ( c.statistique = 3 AND statistique_niveau <= p.st_alerte )
									 OR ( c.statistique = 4 AND statistique_niveau <= p.st_spiritisme )
									 OR ( c.statistique = 5 AND statistique_niveau <= p.st_vigueur )
									 OR ( c.statistique = 6 AND statistique_niveau <= p.st_volonte )
								)
								AND ( c.statistique_sec = 0
									 OR ( c.statistique_sec = 1 AND statistique_sec_niveau <= p.st_constitution )
									 OR ( c.statistique_sec = 2 AND statistique_sec_niveau <= p.st_intelligence )
									 OR ( c.statistique_sec = 3 AND statistique_sec_niveau <= p.st_alerte )
									 OR ( c.statistique_sec = 4 AND statistique_sec_niveau <= p.st_spiritisme )
									 OR ( c.statistique_sec = 5 AND statistique_sec_niveau <= p.st_vigueur )
									 OR ( c.statistique_sec = 6 AND statistique_sec_niveau <= p.st_volonte )
								)
						ORDER BY c.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->connaissances_accessibles[] = $result[ "id" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchVoies( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT id_voie
						FROM personnage_voie
						WHERE id_personnage = ?";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->voies[] = $result[ "id_voie" ];
				}
				return TRUE;
			}
			return FALSE;
		}
	}
?>