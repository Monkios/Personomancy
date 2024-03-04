<?php
	class PersonnageRepository implements IRepository {
		private $_db = FALSE;
		
		public function __construct(){
			$this->_db = new Database();
		}
		
		public function Find( $id ){
			if( !is_numeric( $id ) ){
				Message::Fatale( "Bad personnage entity ID.", func_get_args() );
				return FALSE;
			}
			
			$where = "p.id = ?";
			$order_by = "";
			
			$characters_list = $this->FetchCharacterList( $order_by, $where, array( $id ) );
			return array_shift( $characters_list );
		}
		
		public function FindComplete( $id ){
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
		
		public function FindAll( $sort_by = "character" ){
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
		
		public function Create( $opts = array() ){
			if( !array_key_exists( "alignement", $opts ) || !is_numeric( $opts[ "alignement" ] ) ){
				Message::Fatale( "Invalid alignment ID.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "faction", $opts ) || $opts[ "faction" ] != "" && !is_numeric( $opts[ "faction" ] ) ){
				Message::Fatale( "Invalid faction ID.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "name", $opts ) || $opts[ "name" ] == "" ){
				Message::Fatale( "Invalid name ID.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "player", $opts ) || !is_numeric( $opts[ "player" ] ) ){
				Message::Fatale( "Invalid player ID.", func_get_args() );
				return FALSE;
			}
			if( !array_key_exists( "religion", $opts ) || !is_numeric( $opts[ "religion" ] ) ){
				Message::Fatale( "Invalid religion ID.", func_get_args() );
				return FALSE;
			}
			
			// Insertion
			$sql = "INSERT INTO personnage ( nom, alignement, faction, religion, joueur, point_capacite_raciale, point_experience, total_experience, commentaire, notes )
						VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
			$params = array(
					$opts[ "name" ],
					$opts[ "alignement" ],
					$opts[ "faction" ],
					$opts[ "religion" ],
					$opts[ "player" ],
					CHARACTER_BASE_PCR,
					CHARACTER_BASE_XP,
					CHARACTER_BASE_XP,
					"",
					""
			);
			
			$this->_db->Query( $sql, $params );
			$insert_id = $this->_db->GetInsertId();
			
			$character = $this->FindComplete( $insert_id );
			
			$error_occured = FALSE;
			if( $character ){
				// Ajout des bases obtenus automatiquement par tous les personnages
				$automatics = json_decode( CHARACTER_BASE_AUTOMATICS, true );
				if( array_key_exists( "voies", $automatics ) ){
					foreach( $automatics[ "voies" ] as $id_voie ){
						if( $this->AddVoie( $character, $id_voie ) == FALSE ){
							Message::Erreur( "CREATION : Une erreur s'est produite lors de l'ajout de la voie #" . $id_voie );
							$error_occured = TRUE;
						}
					}
				}
				if( array_key_exists( "capacites", $automatics ) ){
					foreach( $automatics[ "capacites" ] as $id_capacite ){
						if( $this->AddCapacite( $character, $id_capacite, 1 ) == FALSE ){
							Message::Erreur( "CREATION : Une erreur s'est produite lors de l'ajout de la capacité #" . $id_capacite );
							$error_occured = TRUE;
						}
					}
				}
				if( array_key_exists( "connaissances", $automatics ) ){
					foreach( $automatics[ "connaissances" ] as $id_connaissance ){
						if( $this->AddConnaissance( $character, $id_connaissance ) == FALSE ){
							Message::Erreur( "CREATION : Une erreur s'est produite lors de l'ajout de la connaissance #" . $id_connaissance );
							$error_occured = TRUE;
						}
					}
				}
				if( array_key_exists( "pouvoirs", $automatics ) ){
					foreach( $automatics[ "pouvoirs" ] as $id_pouvoir ){
						if( $this->AddPouvoir( $character, $id_pouvoir ) == FALSE ){
							Message::Erreur( "CREATION : Une erreur s'est produite lors de l'ajout du pouvoir #" . $id_pouvoir );
							$error_occured = TRUE;
						}
					}
				}
			}
			
			if( !$character || $error_occured ){
				$this->Delete( $insert_id );
				return FALSE;
			}
			
			return $character;
		}
		
		public function Save( $personnage ){
			if( !$personnage->est_complet ){
				$sql = "UPDATE personnage SET
						joueur = ?,
						nom = ?,
						race = ?,
						religion = ?,
						alignement = ?,
						faction = ?,
						prestige = ?,
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
						$personnage->religion_id,
						$personnage->alignement_id,
						$personnage->faction_id,
						$personnage->prestige_id,
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
						race = ?,
						religion = ?,
						alignement = ?,
						faction = ?,
						prestige = ?,
						st_alerte = ?,
						st_constitution = ?,
						st_intelligence = ?,
						st_spiritisme = ?,
						st_vigueur = ?,
						st_volonte = ?,
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
						$personnage->religion_id,
						$personnage->alignement_id,
						$personnage->faction_id,
						$personnage->prestige_id,
						$personnage->alerte,
						$personnage->constitution,
						$personnage->intelligence,
						$personnage->spiritisme,
						$personnage->vigueur,
						$personnage->volonte,
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
		
		public function Activate( Personnage &$personnage ){
			if( $personnage->est_cree == FALSE ){
				$personnage->est_cree = TRUE;
				
				return $this->Save( $personnage );
			}
			
			return FALSE;
		}
		
		public function Deactivate( Personnage &$personnage ){
			if( $personnage->est_vivant == TRUE ){
				$personnage->est_vivant = FALSE;
				
				return $this->Save( $personnage );
			}
			
			return FALSE;
		}
		
		public function Delete( $id ){
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
					Message::Fatale( "Can't already have a race", func_get_args() );
					return FALSE;
				}
				
				$rr = new RaceRepository();
				$race = $rr->Find( $race_id );
				if( $race == FALSE ){
					Message::Fatale( "Bad race ID", func_get_args() );
					return FALSE;
				}
				
				if( $race->active ){
					$personnage->race_id = $race_id;
					$personnage->alerte += $race->base_alerte;
					$personnage->constitution += $race->base_constitution;
					$personnage->intelligence += $race->base_intelligence;
					$personnage->spiritisme += $race->base_spiritisme;
					$personnage->vigueur += $race->base_vigueur;
					$personnage->volonte += $race->base_volonte;
					$this->Save( $personnage );
					
					foreach( $race->list_capacites_raciales as $cr_id => $cr_infos ){
						
						if( $cr_infos[ 1 ] == 0 ){
							if( $this->AddPouvoir( $personnage, $cr_id ) == FALSE ){
								Message::Erreur( "Une erreur s'est produite lors de l'ajout de la capacité raciale #" . $cr_id );
							
								break;
							}
						}
					}
					
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			
			return FALSE;
		}
		
		public function RemoveRace( Personnage &$personnage ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( empty( $personnage->race_id ) || $personnage->race_id == -1 ){
					Message::Fatale( "Can't have no race to remove", func_get_args() );
					return FALSE;
				}
				
				$rr = new RaceRepository();
				$race = $rr->Find( $personnage->race_id );
				if( $race == FALSE ){
					Message::Fatale( "Bad race ID", func_get_args() );
					return FALSE;
				}
				
				foreach( $race->list_capacites_raciales as $cr_id => $cr_infos ){
					if( $cr_infos[ 1 ] == 0
							&& $this->RemovePouvoir( $personnage, $cr_id ) == FALSE ){
						Message::Erreur( "Une erreur s'est produite lors de l'ajout de la capacité raciale #" . $cr_id );
						
						break;
					}
				}
				
				$personnage->race_id = -1;
				$personnage->alerte -= $race->base_alerte;
				$personnage->constitution -= $race->base_constitution;
				$personnage->intelligence -= $race->base_intelligence;
				$personnage->spiritisme -= $race->base_spiritisme;
				$personnage->vigueur -= $race->base_vigueur;
				$personnage->volonte -= $race->base_volonte;
				$this->Save( $personnage );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function BuyCapaciteRaciale( Personnage &$personnage, $id_capacite_raciale ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				$rr = new RaceRepository();
				$race = $rr->Find( $personnage->race_id );
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
					$rr = new RaceRepository();
					$race = $rr->Find( $personnage->race_id );
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
		
		public function AddSort( Personnage &$personnage, $id_sort ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetSorts( $id_sort ) ) == 0 ){
					Message::Fatale( "Bad sort ID", func_get_args() );
					return FALSE;
				}
				
				$sql = "INSERT INTO personnage_sort ( id_personnage, id_sort )
						VALUES ( ?, ? )";
				$this->_db->Query( $sql, array( $personnage->id, $id_sort ) );
					
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function RemoveSort( Personnage &$personnage, $id_sort ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetSorts( $id_sort ) ) == 0 ){
					Message::Fatale( "Bad sort ID", func_get_args() );
					return FALSE;
				}
				
				$sql = "DELETE FROM personnage_sort
						WHERE id_personnage = ? AND id_sort = ?";
				$this->_db->Query( $sql, array( $personnage->id, $id_sort ) );
				
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
					$ccr = new ChoixCapaciteRepository();
					$choix_capacite = $ccr->Find( $list_choix_id );
					if( $choix_capacite && $choix_capacite->active ){
						$liste_capacites = $ccr->GetCapacites( $choix_capacite );
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
					$ccr = new ChoixCapaciteRepository();
					$choix_capacite = $ccr->Find( $list_choix_id );
					if( $choix_capacite ){
						$liste_capacites = $ccr->GetCapacites( $choix_capacite );
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
		
		private function AddChoixConnaissance( Personnage &$personnage, $id_choix_connaissance ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixConnaissances( $id_choix_connaissance ) ) == 0 ){
					Message::Fatale( "Bad choix connaissance ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_choix_connaissance, $personnage->choix_connaissances ) ){
					$sql = "INSERT INTO personnage_connaissance_categorie ( id_personnage, id_connaissance_categorie, nombre )
							VALUES ( ?, ?, '1' )";
				} else {
					$sql = "UPDATE personnage_connaissance_categorie
							SET nombre = ( nombre * 1 ) + 1
							WHERE id_personnage = ? AND id_connaissance_categorie = ?";
				}
				$this->_db->Query( $sql, array( $personnage->id, $id_choix_connaissance ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function BuyChoixConnaissance( Personnage &$personnage, $list_choix_id, $choix_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixConnaissances( $list_choix_id ) ) == 0 ){
					Message::Fatale( "Bad buy choix connaissance ID", func_get_args() );
					return FALSE;
				}
				
				if( array_key_exists( $list_choix_id, $personnage->choix_connaissances ) && $personnage->choix_connaissances[ $list_choix_id ] > 0 ){
					$ccr = new ChoixConnaissanceRepository();
					$choix_connaissance = $ccr->Find( $list_choix_id );
					if( $choix_connaissance && $choix_connaissance->active ){
						$liste_connaissances = $ccr->GetConnaissances( $choix_connaissance );
						if( array_key_exists( $choix_id, $liste_connaissances ) ){
							if( $this->AddConnaissance( $personnage, $choix_id ) ){
								if( $this->RemoveChoixConnaissance( $personnage, $list_choix_id ) == FALSE ){
									$this->RemoveConnaissance( $personnage, $choix_id );
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
		
		private function RemoveChoixConnaissance( Personnage &$personnage, $id_choix_connaissance ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixConnaissances( $id_choix_connaissance ) ) == 0 ){
					Message::Fatale( "Bad choix connaissance ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_choix_connaissance, $personnage->choix_connaissances ) || $personnage->choix_connaissances[ $id_choix_connaissance ] == 0 ){
					Message::Fatale( "Character must have choix_connaissance to remove", func_get_args() );
					return FALSE;
				}
				
				if( $personnage->choix_connaissances[ $id_choix_connaissance ] == 1 ){
					$sql = "DELETE FROM personnage_connaissance_categorie
							WHERE id_personnage = ? AND  id_connaissance_categorie = ?";
				} else {
					$sql = "UPDATE personnage_connaissance_categorie
							SET nombre = ( nombre * 1 ) - 1
							WHERE id_personnage = ? AND id_connaissance_categorie = ?";
				}
				$this->_db->Query( $sql, array( $personnage->id, $id_choix_connaissance ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function RefundChoixConnaissance( Personnage &$personnage, $list_choix_id, $choix_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixConnaissances( $list_choix_id ) ) == 0 ){
					Message::Fatale( "Bad refund choix connaissance ID", func_get_args() );
					return FALSE;
				}
				
				if( in_array( $choix_id, $personnage->connaissances ) ){
					$ccr = new ChoixConnaissanceRepository();
					$choix_connaissance = $ccr->Find( $list_choix_id );
					if( $choix_connaissance ){
						$liste_connaissances = $ccr->GetConnaissances( $choix_connaissance );
						if( array_key_exists( $choix_id, $liste_connaissances ) ){
							if( $this->RemoveConnaissance( $personnage, $choix_id ) ){
								if( $this->AddChoixConnaissance( $personnage, $list_choix_id ) == FALSE ){
									$this->AddConnaissance( $personnage, $choix_id );
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
		
		private function AddChoixPouvoir( Personnage &$personnage, $id_choix_pouvoir ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixPouvoirs( $id_choix_pouvoir ) ) == 0 ){
					Message::Fatale( "Bad choix pouvoir ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_choix_pouvoir, $personnage->choix_pouvoirs ) ){
					$sql = "INSERT INTO personnage_pouvoir_categorie ( id_personnage, id_pouvoir_categorie, nombre )
							VALUES ( ?, ?, '1' )";
				} else {
					$sql = "UPDATE personnage_pouvoir_categorie
							SET nombre = ( nombre * 1 ) + 1
							WHERE id_personnage = ? AND id_pouvoir_categorie = ?";
				}
				$this->_db->Query( $sql, array( $personnage->id, $id_choix_pouvoir ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		public function RefundChoixPouvoir( Personnage &$personnage, $list_choix_id, $choix_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixPouvoirs( $list_choix_id ) ) == 0 ){
					Message::Fatale( "Bad refund choix pouvoir ID", func_get_args() );
					return FALSE;
				}
				
				if( array_key_exists( $choix_id, $personnage->pouvoirs ) ){
					$cpr = new ChoixPouvoirRepository();
					$choix_pouvoir = $cpr->Find( $list_choix_id );
					if( $choix_pouvoir ){
						$liste_pouvoirs = $cpr->GetPouvoirs( $choix_pouvoir );
						if( array_key_exists( $choix_id, $liste_pouvoirs ) ){
							if( $this->RemovePouvoir( $personnage, $choix_id ) ){
								if( $this->AddChoixPouvoir( $personnage, $list_choix_id ) == FALSE ){
									$this->AddPouvoir( $personnage, $choix_id );
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
		
		public function BuyChoixPouvoir( Personnage &$personnage, $list_choix_id, $choix_id ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixPouvoirs( $list_choix_id ) ) == 0 ){
					Message::Fatale( "Bad buy choix pouvoir ID", func_get_args() );
					return FALSE;
				}
				
				if( array_key_exists( $list_choix_id, $personnage->choix_pouvoirs ) && $personnage->choix_pouvoirs[ $list_choix_id ] > 0 ){
					$ccr = new ChoixPouvoirRepository();
					$choix_pouvoir = $ccr->Find( $list_choix_id );
					if( $choix_pouvoir && $choix_pouvoir->active ){
						$liste_pouvoirs = $ccr->GetPouvoirs( $choix_pouvoir );
						if( array_key_exists( $choix_id, $liste_pouvoirs ) ){
							if( $this->AddPouvoir( $personnage, $choix_id ) ){
								if( $this->RemoveChoixPouvoir( $personnage, $list_choix_id ) == FALSE ){
									$this->RemovePouvoir( $personnage, $choix_id );
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
		
		private function RemoveChoixPouvoir( Personnage &$personnage, $id_choix_pouvoir ){
			if( $personnage && $personnage->est_vivant && $personnage->est_complet ){
				if( count( Dictionary::GetChoixPouvoirs( $id_choix_pouvoir ) ) == 0 ){
					Message::Fatale( "Bad choix pouvoir ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_choix_pouvoir, $personnage->choix_pouvoirs ) || $personnage->choix_pouvoirs[ $id_choix_pouvoir ] == 0 ){
					Message::Fatale( "Character must have choix_pouvoir to remove", func_get_args() );
					return FALSE;
				}
				
				if( $personnage->choix_pouvoirs[ $id_choix_pouvoir ] == 1 ){
					$sql = "DELETE FROM personnage_pouvoir_categorie
							WHERE id_personnage = ? AND id_pouvoir_categorie = ?";
				} else {
					$sql = "UPDATE personnage_pouvoir_categorie
							SET nombre = ( nombre * 1 ) - 1
							WHERE id_personnage = ? AND id_pouvoir_categorie = ?";
				}
				$this->_db->Query( $sql, array( $personnage->id, $id_choix_pouvoir ) );
				
				$personnage = $this->FindComplete( $personnage->id );
				return $personnage != FALSE;
			}
			
			return FALSE;
		}
		
		private function AddPouvoir( Personnage &$personnage, $id_pouvoir ){
			if( $personnage && $personnage->est_vivant ){
				if( count( Dictionary::GetPouvoirs( $id_pouvoir ) ) == 0 ){
					Message::Fatale( "Bad pouvoir ID", func_get_args() );
					return FALSE;
				}
				
				if( $personnage->est_complet ){
					$pr = new PouvoirRepository();
					$pouvoir = $pr->Find( $id_pouvoir );
					
					if( $pouvoir->bonus_constitution > 0 || $pouvoir->bonus_spiritisme > 0 || $pouvoir->bonus_intelligence > 0
							|| $pouvoir->bonus_alerte > 0 || $pouvoir->bonus_vigueur > 0 || $pouvoir->bonus_volonte > 0 ){
						$personnage->alerte += $pouvoir->bonus_alerte;
						$personnage->constitution += $pouvoir->bonus_constitution;
						$personnage->intelligence += $pouvoir->bonus_intelligence;
						$personnage->spiritisme += $pouvoir->bonus_spiritisme;
						$personnage->vigueur += $pouvoir->bonus_vigueur;
						$personnage->volonte += $pouvoir->bonus_volonte;
						$this->Save( $personnage );
					}
					
					if( array_key_exists( $id_pouvoir, $personnage->pouvoirs ) ){
						// Incremente le nombre de pouvoir de ce type obtenu
						$sql = "UPDATE personnage_pouvoir
								SET nombre = ( nombre * 1 ) + 1
								WHERE id_personnage = ? AND id_pouvoir = ?";
					} else {
						// Insere le pouvoir dans la liste de ceux du personnage
						$sql = "INSERT INTO personnage_pouvoir( id_personnage, id_pouvoir, nombre )
								VALUES ( ?, ?, '1' )";
					}
					$this->_db->Query( $sql, array( $personnage->id, $id_pouvoir ) );
					
					foreach( $pouvoir->list_bonus_voie as $voie ){
						if( $this->AddVoie( $personnage, $voie->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la voie #" . $id_voie );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_bonus_capacite as $capacite ){
						if( $this->AddCapacite( $personnage, $capacite->id, 1 ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la capacite #" . $capacite->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_bonus_connaissance as $connaissance ){
						if( $this->AddConnaissance( $personnage, $connaissance->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la connaissance #" . $connaissance->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_choix_capacite as $choix_capacite ){
						if( $this->AddChoixCapacite( $personnage, $choix_capacite->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la choix_capacite #" . $choix_capacite->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_choix_connaissance as $choix_connaissance ){
						if( $this->AddChoixConnaissance( $personnage, $choix_connaissance->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la choix_connaissance #" . $choix_connaissance->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_choix_pouvoir as $choix_pouvoir ){
						if( $this->AddChoixPouvoir( $personnage, $choix_pouvoir->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la choix_pouvoir #" . $choix_pouvoir->id );
							return FALSE;
						}
					}
				
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			return FALSE;
		}
		
		private function RemovePouvoir( Personnage &$personnage, $id_pouvoir ){
			if( $personnage && $personnage->est_vivant ){
				if( count( Dictionary::GetPouvoirs( $id_pouvoir ) ) == 0 ){
					Message::Fatale( "Bad pouvoir ID", func_get_args() );
					return FALSE;
				}
				
				if( !array_key_exists( $id_pouvoir, $personnage->pouvoirs ) ){
					Message::Fatale( "Character must have power to remove", func_get_args() );
					return FALSE;
				}
				
				if( $personnage->est_complet ){
					$pr = new PouvoirRepository();
					$pouvoir = $pr->Find( $id_pouvoir );
					
					foreach( $pouvoir->list_choix_pouvoir as $choix_pouvoir ){
						if( $this->RemoveChoixPouvoir( $personnage, $choix_pouvoir->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la choix_pouvoir #" . $choix_pouvoir->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_choix_connaissance as $choix_connaissance ){
						if( $this->RemoveChoixConnaissance( $personnage, $choix_connaissance->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la choix_connaissance #" . $choix_connaissance->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_choix_capacite as $choix_capacite ){
						if( $this->RemoveChoixCapacite( $personnage, $choix_capacite->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la choix_capacite #" . $choix_capacite->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_bonus_connaissance as $connaissance ){
						if( $this->RemoveConnaissance( $personnage, $connaissance->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la connaissance #" . $connaissance->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_bonus_capacite as $capacite ){
						if( $this->RemoveCapacite( $personnage, $capacite->id, 1 ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la capacite #" . $capacite->id );
							return FALSE;
						}
					}
					
					foreach( $pouvoir->list_bonus_voie as $voie ){
						if( $this->RemoveVoie( $personnage, $voie->id ) == FALSE ){
							Message::Erreur( "POUVOIR : Une erreur s'est produite lors de l'ajout de la voie #" . $id_voie );
							return FALSE;
						}
					}
					
					if( $personnage->pouvoirs[ $id_pouvoir ] > 1 ){
						// Incremente le nombre de pouvoir de ce type obtenu
						$sql = "UPDATE personnage_pouvoir
								SET nombre = ( nombre * 1 ) - 1
								WHERE id_personnage = ? AND id_pouvoir = ?";
					} else {
						// Insere le pouvoir dans la liste de ceux du personnage
						$sql = "DELETE FROM personnage_pouvoir
								WHERE id_personnage = ? AND id_pouvoir = ?";
					}
					$this->_db->Query( $sql, array( $personnage->id, $id_pouvoir ) );
					
					if( $pouvoir->bonus_constitution > 0 || $pouvoir->bonus_spiritisme > 0 || $pouvoir->bonus_intelligence > 0
							|| $pouvoir->bonus_alerte > 0 || $pouvoir->bonus_vigueur > 0 || $pouvoir->bonus_volonte > 0 ){
						$personnage->alerte -= $pouvoir->bonus_alerte;
						$personnage->constitution -= $pouvoir->bonus_constitution;
						$personnage->intelligence -= $pouvoir->bonus_intelligence;
						$personnage->spiritisme -= $pouvoir->bonus_spiritisme;
						$personnage->vigueur -= $pouvoir->bonus_vigueur;
						$personnage->volonte -= $pouvoir->bonus_volonte;
						$this->Save( $personnage );
					}
				
					$personnage = $this->FindComplete( $personnage->id );
					return $personnage != FALSE;
				}
			}
			return FALSE;
		}
		
		private function HasPrerequisCapacite( Personnage &$personnage, $id_capacite ){
			if( $personnage ){
				$cr = new CapaciteRepository();
				$capacite = $cr->Find( $id_capacite );
				
				return $capacite !== FALSE && $capacite->active
						&& in_array( $capacite->voie_id, $personnage->voies );
			}
			return FALSE;
		}
		
		private function HasPrerequisConnaissance( Personnage &$personnage, $id_connaissance ){
			if( $personnage ){
				$cr = new ConnaissanceRepository();
				$connaissance = $cr->Find( $id_connaissance );
				
				return $connaissance !== FALSE && $connaissance->active
						&& in_array( $id_connaissance, $personnage->connaissances_accessibles );
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
						a.id AS alignement_id,
						a.nom AS alignement_nom,
						f.id AS faction_id,
						f.nom AS faction_nom,
						ra.id AS race_id,
						ra.nom AS race_nom,
						re.id AS religion_id,
						re.nom AS religion_nom,
						pr.id AS prestige_id,
						pr.nom AS prestige_nom,
						p.notes, p.commentaire
					FROM personnage AS p
						LEFT JOIN personnage_journal pj ON pj.id = ( SELECT id FROM personnage_journal WHERE active = '1' AND id_personnage = p.id ORDER BY quand DESC LIMIT 1 )
						LEFT JOIN joueur u ON pj.joueur_id = u.id
						LEFT JOIN joueur j ON p.joueur = j.id
						LEFT JOIN alignement a ON p.alignement = a.id
						LEFT JOIN faction f ON p.faction = f.id
						LEFT JOIN race ra ON p.race = ra.id
						LEFT JOIN divinite re ON p.religion = re.id
						LEFT JOIN prestige pr ON p.prestige = pr.id
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
				
				$entity->alignement_id = $result[ "alignement_id" ];
				$entity->alignement_nom = $result[ "alignement_nom" ];
				$entity->faction_id = $result[ "faction_id" ];
				$entity->faction_nom = $result[ "faction_nom" ];
				$entity->race_id = $result[ "race_id" ];
				$entity->race_nom = $result[ "race_nom" ];
				$entity->religion_id = $result[ "religion_id" ];
				$entity->religion_nom = $result[ "religion_nom" ];
				$entity->prestige_id = $result[ "prestige_id" ];
				$entity->prestige_nom = $result[ "prestige_nom" ];
				
				$entity->notes = $result[ "notes" ];
				$entity->commentaire = $result[ "commentaire" ];
				
				$list[ $result[ "id" ] ] = $entity;
			}
			
			return $list;
		}
		
		private function Complete( Personnage &$personnage, $force = FALSE ){
			if( $force || !$personnage->est_complet ){
				$sql = "SELECT p.st_constitution, p.st_spiritisme, p.st_vigueur,
								p.st_volonte, p.st_intelligence, p.st_alerte
						FROM personnage p
								LEFT JOIN joueur j ON p.joueur = j.id
						WHERE p.id = ?";
				$this->_db->Query( $sql, array( $personnage->id ) );
				if( $result = $this->_db->GetResult() ){
					$personnage->constitution = $result[ "st_constitution" ];
					$personnage->spiritisme = $result[ "st_spiritisme" ];
					$personnage->vigueur = $result[ "st_vigueur" ];
					$personnage->volonte = $result[ "st_volonte" ];
					$personnage->intelligence = $result[ "st_intelligence" ];
					$personnage->alerte = $result[ "st_alerte" ];
					
					// Chaine les appels de construction
					if(	$this->FetchCapacites( $personnage )
							&& $this->FetchCapacitesRaciales( $personnage )
							&& $this->FetchChoixCapacites( $personnage )
							&& $this->FetchChoixConnaissances( $personnage )
							&& $this->FetchChoixPouvoirs( $personnage )
							&& $this->FetchConnaissances( $personnage )
							&& $this->FetchConnaissancesAccessibles( $personnage )
							&& $this->FetchPouvoirs( $personnage )
							&& $this->FetchSorts( $personnage )
							&& $this->FetchVoies( $personnage ) ){
						$personnage->est_complet = TRUE;
						return TRUE;
					}
				}
				return FALSE;
			} else {
				return TRUE;
			}
		}
		
		private function FetchCapacitesRaciales( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT po.id, po.nom
						FROM personnage p
							LEFT JOIN personnage_pouvoir pp ON pp.id_personnage = p.id AND pp.id_pouvoir IN ( SELECT id_pouvoir FROM race_pouvoir /*WHERE id_race = p.race*/ )
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
		
		private function FetchChoixConnaissances( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT c.id, x.nombre
						FROM connaissance_categorie AS c
							LEFT JOIN personnage_connaissance_categorie AS x ON c.id = x.id_connaissance_categorie
						WHERE x.id_personnage = ? And c.active = '1' And c.supprime = '0'
						ORDER BY c.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->choix_connaissances[ $result[ "id" ] ] = $result[ "nombre" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchChoixPouvoirs( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT c.id, x.nombre
						FROM pouvoir_categorie AS c
							LEFT JOIN personnage_pouvoir_categorie AS x ON c.id = x.id_pouvoir_categorie
						WHERE x.id_personnage = ? And c.active = '1' And c.supprime = '0'
						ORDER BY c.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->choix_pouvoirs[ $result[ "id" ] ] = $result[ "nombre" ];
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
								AND ( c.divinite = 0 OR c.divinite = p.religion )
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
		
		private function FetchPouvoirs( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT pp.id_pouvoir, pp.nombre
						FROM personnage_pouvoir pp
							LEFT JOIN pouvoir p ON p.id = pp.id_pouvoir
						WHERE pp.id_personnage = ? AND p.supprime = '0'";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					$personnage->pouvoirs[ $result[ "id_pouvoir" ] ] = $result[ "nombre" ];
				}
				return TRUE;
			}
			return FALSE;
		}
		
		private function FetchSorts( Personnage &$personnage ){
			if( $personnage ){
				$sql = "SELECT ps.id_sort, s.id_sphere
						FROM personnage_sort AS ps
							LEFT JOIN sort AS s ON ps.id_sort = s.id
							LEFT JOIN capacite AS c ON s.id_sphere = c.id
						WHERE ps.id_personnage = ?
						ORDER BY c.nom, s.niveau, s.nom";
				$this->_db->Query( $sql, array( $personnage->id ) );
				while( $result = $this->_db->GetResult() ){
					if( !array_key_exists( $result[ "id_sphere" ], $personnage->sorts ) ){
						$personnage->sorts[ $result[ "id_sphere" ] ] = array();
					}
					$personnage->sorts[ $result[ "id_sphere" ] ][] = $result[ "id_sort" ];
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