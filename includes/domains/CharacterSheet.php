<?php
	class CharacterSheet {
		public function __construct(){}
		
		public static function GetSelectionCost( $s ){
			return $s * 5;
		}
		
		public static function GetSelectionCompoundCost( $s, $i = 0 ){
			$cost = ( $s * 5 / 2 ) * ( $s + 1 );
			if( $i != 0 ){
				$cost -= self::GetSelectionCompoundCost( $i );
			}
			return $cost;
		}
		
		public static function GetVoieCost( $nb_voies_possedees ){
			return $nb_voies_possedees * 10;
		}
		
		public static function GetConnaissanceCost(){
			return 10;
		}
		
		public static function GetPrestigeCost(){
			return 100;
		}
		
		public function Load( $character_id ){
			if( is_numeric( $character_id ) ){
				$personnage_repository = new PersonnageRepository();
				$c = $personnage_repository->FindComplete( $character_id );
				
				if( $c !== FALSE ){
					return $c;
				}
			} else {
				$character_id = "NUM";
			}
			
			Message::Erreur( "Identifiant de personnage #" . $character_id . " invalide." );
			return FALSE;
		}
		
		public function Create( $player_id, $name, $alignment_id, $faction_id, $religion_id, $race_id ){
			if( !Community::GetPlayer( $player_id ) ){
				Message::Fatale( "Bad player ID", func_get_args() );
				return FALSE;
			}
			if( count( Dictionary::GetAlignements( $alignment_id ) ) == 0 ){
				Message::Fatale( "Bad alignment ID", func_get_args() );
				return FALSE;
			}
			if( $faction_id != "" && count( Dictionary::GetFactions( $faction_id ) ) == 0 ){
				Message::Fatale( "Bad faction ID", func_get_args() );
				return FALSE;
			}
			if( count( Dictionary::GetReligions( $religion_id ) ) == 0 ){
				Message::Fatale( "Bad religion ID", func_get_args() );
				return FALSE;
			}
			if( count( Dictionary::GetRaces( $race_id ) ) == 0 ){
				Message::Fatale( "Bad race ID", func_get_args() );
				return FALSE;
			}
			
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Create( array( "alignement" => $alignment_id, "faction" => $faction_id, "name" => $name, "player" => $player_id, "religion" => $religion_id ) );
			
			if( $c !== FALSE ){
				$character_id = $c->id;
				
				$this->RecordAction( $c->id, CharacterSheet::RECORD_MESSAGE, 0, 0, mb_convert_encoding( "Création du personnage", 'ISO-8859-1', 'UTF-8'), FALSE );
				$this->RecordAction( $c->id, CharacterSheet::RECORD_XP, 1, CHARACTER_BASE_XP, mb_convert_encoding( "Gain de " . CHARACTER_BASE_XP . "  points d'expérience. (Création du personnage)", 'ISO-8859-1', 'UTF-8'), FALSE );
				
				if( $this->AddRace( $c, $race_id ) ){
					return $c;
				}
				
			}
			
			return FALSE;
		}
		
		public function UpdateBases( $character_id, $new_name, $new_alignement, $new_faction, $new_religion ){
			if( count( Dictionary::GetAlignements( $new_alignement ) ) == 0 ){
				Message::Fatale( "Bad alignment ID", func_get_args() );
				return FALSE;
			}
			if( $new_faction != "" && count( Dictionary::GetFactions( $new_faction ) ) == 0 ){
				Message::Fatale( "Bad faction ID", func_get_args() );
				return FALSE;
			}
			if( count( Dictionary::GetReligions( $new_religion ) ) == 0 ){
				Message::Fatale( "Bad religion ID", func_get_args() );
				return FALSE;
			}
			
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $character_id );
			
			$c->nom = $new_name;
			$c->alignement_id = $new_alignement;
			$c->faction_id = $new_faction;
			$c->religion_id = $new_religion;
			
			$personnage_repository->Save( $c );
			
			return $c;
		}
		
		public function ChangeRace( $character_id, $new_race ){
			$race_removed = FALSE;
			
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			if( $c != FALSE ){
				if( !empty( $c->race_id ) && $c->race_id != -1 ){
					// La derniere modification doit avoir ete l'ajout de la race
					$last_modif = CharacterLog::GetLastByCharacter( $character_id );
					if( $last_modif->Quoi == CharacterSheet::RECORD_RACE ){
						// Revient a la modification precedente
						if( $this->Rollback( $c ) ){
							$race_removed = TRUE;
						}
					}
				} else {
					$race_removed = TRUE;
				}
			}
			
			// Si aucune race ou que la race a bien ete enlevee
			if( $race_removed && $this->AddRace( $c, $new_race ) ){
				return $c;
			}
			
			return FALSE;
		}
		
		public function BuyCapaciteRaciale( $character_id, $new_cr ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			// Valide que le personnage peut acheter des capacite raciales
			if( $c != FALSE ){
				$pouvoir_repository = new PouvoirRepository();
				$cr = $pouvoir_repository->Find( $new_cr );
				if( !empty( $c->race_id ) && $c->race_id != -1 ){
					$race_repository = new RaceRepository();
					$race = $race_repository->Find( $c->race_id );
					if( $c->pc_raciales >= $race->list_capacites_raciales[ $new_cr ][ 1 ] ){
						if( $personnage_repository->BuyCapaciteRaciale( $c, $new_cr ) ){
							$this->RecordAction( $c->id, CharacterSheet::RECORD_RACIALE_CAPACITE, $new_cr, $race->list_capacites_raciales[ $new_cr ][ 1 ], mb_convert_encoding( "Ajout de la capacité raciale : ", 'ISO-8859-1', 'UTF-8') . $cr->nom, TRUE );
							return $c;
						}
					}
				}
			}
			
			return FALSE;
		}
		
		private function AddRace( Personnage &$character, $new_race ){
			$race = Dictionary::GetRaces( $new_race );
			if( count( $race ) == 1 ){
				$personnage_repository = new PersonnageRepository();
				if( $personnage_repository->AddRace( $character, $new_race ) != FALSE ){
					$this->RecordAction( $character->id, CharacterSheet::RECORD_RACE, $new_race, 1, mb_convert_encoding( "Sélection de la race : ", 'ISO-8859-1', 'UTF-8') . $race[ $new_race ], TRUE );
					
					return TRUE;
				}
			}
			Message::Erreur( "Une erreur s'est produite lors de l'ajout de la race #" . $new_race );
			
			return FALSE;
		}
		
		private function RemoveRace( Personnage &$character ){
			$personnage_repository = new PersonnageRepository();
			if( $personnage_repository->RemoveRace( $character ) == FALSE ) {
				Message::Erreur( "Une erreur s'est produite lors du retrait de la race #" . $new_race );
				return FALSE;
			}
			
			return TRUE;
		}
		
		private function RefundCapaciteRaciale( Personnage &$character, $old_cr, $cout = FALSE ){
			// Valide que le personnage possede cette capacite raciale
			if( $character != FALSE && array_key_exists( $old_cr, $character->capacites_raciales ) ){
				$personnage_repository = new PersonnageRepository();
				if( $personnage_repository->RefundCapaciteRaciale( $character, $old_cr, $cout ) ){
					return $character;
				}
			}
			
			return FALSE;
		}
		
		public function BurnRemainingPCR( $character_id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			// Valide que le personnage peut acheter des capacite raciales
			if( $c != FALSE && $c->pc_raciales > 0 ){
				$remaining_pcr = $c->pc_raciales;
				
				$c->pc_raciales -= $remaining_pcr;
				if( $personnage_repository->Save( $c ) ){
					$this->RecordAction( $c->id, CharacterSheet::RECORD_RACIALE_EMPTY, 0, $remaining_pcr, mb_convert_encoding( "Abandon des " . $remaining_pcr . " points de capacités raciales restants.", 'ISO-8859-1', 'UTF-8'), TRUE );
					
					return $personnage_repository->FindComplete( $character_id );
				}
			}
			
			return FALSE;
		}
		
		public function BuyChoixCapacite( $character_id, $list_id, $choix_id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			// Valide que le personnage peut acheter des capacite raciales
			if( $c != FALSE && array_key_exists( $list_id, $c->choix_capacites ) ){
				$choix_capacite_repository = new ChoixCapaciteRepository();
				$choix_capacite = $choix_capacite_repository->Find( $list_id );
				if( $choix_capacite ){
					$liste_capacites = $choix_capacite_repository->GetCapacites( $choix_capacite );
					if( array_key_exists( $choix_id, $liste_capacites ) ){
						if( $personnage_repository->BuyChoixCapacite( $c, $list_id, $choix_id ) ){
							$this->RecordAction( $c->id, CharacterSheet::RECORD_CHOIX_CAPACITE, $list_id, $choix_id, mb_convert_encoding( "Sélection de la capacité : ", 'ISO-8859-1', 'UTF-8') . $liste_capacites[ $choix_id ]->nom, TRUE );
							return $c;
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function RefundChoixCapacite( Personnage &$character, $list_id, $choix_id ){
			// Valide que le personnage possede cette capacite
			if( $character != FALSE && array_key_exists( $choix_id, $character->capacites ) && $character->capacites[ $choix_id ] > 0 ){
				$choix_capacite_repository = new ChoixCapaciteRepository();
				$choix_capacite = $choix_capacite_repository->Find( $list_id );
				if( $choix_capacite ){
					$liste_capacites = $choix_capacite_repository->GetCapacites( $choix_capacite );
					if( array_key_exists( $choix_id, $liste_capacites ) ){
						$personnage_repository = new PersonnageRepository();
						if( $personnage_repository->RefundChoixCapacite( $character, $list_id, $choix_id ) ){
							return $character;
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function BuyCapacite( $character_id, $capacite_id, $nb_selections ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			
			if( $c != FALSE && $c->est_vivant && $c->est_complet
					&& array_key_exists( $capacite_id, $c->capacites ) ){
				$capacite_repository = new CapaciteRepository();
				$capacite = $capacite_repository->Find( $capacite_id );
				$curr_score = $c->capacites[ $capacite_id ];
				
				if( $nb_selections > 0
						&& $curr_score + $nb_selections <= CHARACTER_MAX_CAPACITES_SELECTIONS
						&& $capacite && $capacite->active
						&& in_array( $capacite->voie_id, $c->voies ) ){
					$xp_cost = self::GetSelectionCompoundCost( $curr_score + $nb_selections, $curr_score );
					if( $c->CanAfford( $xp_cost ) ){
						if( $personnage_repository->AddCapacite( $c, $capacite_id, $nb_selections ) ){
							$c->px_restants -= $xp_cost;
						
							if( $personnage_repository->Save( $c ) ){
								$this->RecordAction( $c->id, CharacterSheet::RECORD_CAPACITE, $capacite_id, $nb_selections, mb_convert_encoding( "Augmentation de la capacité " . $capacite->nom . " à " . ( $curr_score + $nb_selections ) . " (-" . $xp_cost . " XP)", 'ISO-8859-1', 'UTF-8'), TRUE );
							
								return $c;
							}
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function RefundCapacite( Personnage &$character, $capacite_id, $nb_selections ){
			if( $character != FALSE && $character->est_complet
					&& array_key_exists( $capacite_id, $character->capacites ) ){
				$curr_score = $character->capacites[ $capacite_id ];
				
				$xp_cost = CharacterSheet::GetSelectionCompoundCost( $curr_score, $curr_score - $nb_selections );
				if( $character->px_restants + $xp_cost <= $character->px_totaux ){
					$personnage_repository = new PersonnageRepository();
					if( $personnage_repository->RemoveCapacite( $character, $capacite_id, $nb_selections ) ){
						$character->px_restants += $xp_cost;
						
						if( $personnage_repository->Save( $character ) ){
							return $character;
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function BuyVoie( $character_id, $voie_id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			
			if( $c != FALSE && $c->est_vivant && $c->est_complet
					&& !in_array( $voie_id, $c->voies ) ){
				$voie_repository = new VoieRepository();
				$voie = $voie_repository->Find( $voie_id );
				
				if( $voie && $voie->active ){
					$xp_cost = self::GetVoieCost( count( $c->voies ) );
					if( $c->CanAfford( $xp_cost ) ){
						if( $personnage_repository->AddVoie( $c, $voie_id ) ){
							$c->px_restants -= $xp_cost;
						
							if( $personnage_repository->Save( $c ) ){
								$this->RecordAction( $c->id, CharacterSheet::RECORD_VOIE, $voie_id, 1, "Ajout de la voie " . $voie->nom . " (-" . $xp_cost . " XP)", TRUE );
							
								return $c;
							}
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function RefundVoie( Personnage $character, $voie_id ){
			if( $character->est_vivant && $character->est_complet
					&& in_array( $voie_id, $character->voies ) ){
				$xp_cost = self::GetVoieCost( count( $character->voies ) - 1 );
				if( $character->px_restants + $xp_cost <= $character->px_totaux ){
					$personnage_repository = new PersonnageRepository();
					if( $personnage_repository->RemoveVoie( $character, $voie_id ) ){
						$character->px_restants += $xp_cost;
					
						if( $personnage_repository->Save( $character ) ){
							return $character;
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function BuyConnaissance( $character_id, $connaissance_id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->FindComplete( $character_id );
			
			if( $c != FALSE && $c->est_vivant && $c->est_complet
					&& !in_array( $connaissance_id, $c->connaissances ) ){
				$connaissance_repository = new ConnaissanceRepository();
				$connaissance = $connaissance_repository->Find( $connaissance_id );
				
				if( $connaissance && $connaissance->active ){
					$xp_cost = self::GetConnaissanceCost();
					if( $c->CanAfford( $xp_cost ) ){
						if( $personnage_repository->AddConnaissance( $c, $connaissance_id ) ){
							$c->px_restants -= $xp_cost;
						
							if( $personnage_repository->Save( $c ) ){
								$this->RecordAction( $c->id, CharacterSheet::RECORD_CONNAISSANCE, $connaissance_id, 1, "Ajout de la connaissance " . $connaissance->nom . " (-" . $xp_cost . " XP)", TRUE );
							
								return $c;
							}
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function RefundConnaissance( Personnage $character, $connaissance_id ){
			if( $character->est_vivant && $character->est_complet
					&& in_array( $connaissance_id, $character->connaissances ) ){
				$xp_cost = self::GetConnaissanceCost();
				if( $character->px_restants + $xp_cost <= $character->px_totaux ){
					$personnage_repository = new PersonnageRepository();
					if( $personnage_repository->RemoveConnaissance( $character, $connaissance_id ) ){
						$character->px_restants += $xp_cost;
					
						if( $personnage_repository->Save( $character ) ){
							return $character;
						}
					}
				}
			}
			
			return FALSE;
		}
		
		public function SaveCommentaire( Personnage $character, $commentaire ){
			$personnage_repository = new PersonnageRepository();
			
			// Ne fait pas la mise à jour si le commentaire est identique
			if( $character->commentaire == $commentaire ){
				return $character;
			}
			
			$character->commentaire = $commentaire;
			if( $personnage_repository->Save( $character ) ){
				return $character;
			}
			
			return FALSE;
		}
		
		public function SaveNotes( Personnage $character, $notes ){
			$personnage_repository = new PersonnageRepository();
			
			// Ne fait pas la mise à jour si les notes sont identiques
			if( $character->notes == $notes ){
				return $character;
			}
			
			$character->notes = $notes;
			if( $personnage_repository->Save( $character ) ){
				return $character;
			}
			
			return FALSE;
		}
		
		public function Activate( $id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $id );
			
			if( $c
					&& !$c->est_cree
					&& $c->pc_raciales == 0
					&& count( $c->choix_capacites ) == 0
					&& count( $c->choix_connaissances ) == 0
					&& count( $c->choix_pouvoirs ) == 0
			){
				if( $personnage_repository->Activate( $c ) ){
					$this->RecordAction( $c->id, CharacterSheet::RECORD_ACTIVATE, 0, 0, mb_convert_encoding( "Activation du personnage", 'ISO-8859-1', 'UTF-8'), FALSE );
					return TRUE;
				}
			}
			
			return FALSE;
		}
		
		public function Deactivate( $id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $id );
			
			if( $c && $c->est_vivant ){
				if( $personnage_repository->Deactivate( $c ) ){
					$this->RecordAction( $c->id, CharacterSheet::RECORD_DEACTIVATE, 0, 0, mb_convert_encoding( "Désactivation du personnage", 'ISO-8859-1', 'UTF-8'), FALSE );
					return TRUE;
				}
			}
			
			return FALSE;
		}
		
		public function Destroy( $id ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $id );
			
			if( $c && !$c->est_vivant ){
				if( $personnage_repository->Delete( $c->id ) ){
					$this->RecordAction( $c->id, CharacterSheet::RECORD_DESTROY, 0, 0, mb_convert_encoding( "Destruction de ", 'ISO-8859-1', 'UTF-8') . $c->nom, FALSE );
					return TRUE;
				}
			}
			
			return FALSE;
		}
		
		public function RebuildComplet( $id, $suffix = FALSE ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $id );
			
			if( !$suffix ){
				$suffix = mb_convert_encoding( " [REMPLACÉ " . date( "Y-m-d" ) . "]", 'ISO-8859-1', 'UTF-8');
			}
			
			if( $c ){
				return $this->Rebuild( $c, $c->px_totaux, $suffix );
			}
			return FALSE;
		}
		
		public function RebuildPerte( $id, $suffix = FALSE ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $id );
			
			if( !$suffix ){
				$suffix = mb_convert_encoding( " [REMPLACÉ (à perte) " . date( "Y-m-d" ) . "]", 'ISO-8859-1', 'UTF-8');
			}
			
			if( $c ){
				return $this->Rebuild( $c, $c->GetPerteXP(), $suffix );
			}
			return FALSE;
		}
		
		private function Rebuild( $c, $xp_after_rebuild, $suffix ){
			$rebuild = $this->Create( $c->joueur_id, $c->nom, $c->alignement_id, $c->faction_id, $c->religion_id, $c->race_id );
			
			$this->RecordAction( $c->id, 6, $rebuild->id, $rebuild->px_totaux, mb_convert_encoding( "Personnage copié.", 'ISO-8859-1', 'UTF-8'), FALSE );
			if( $rebuild ){
				// La différence entre l'XP que le personnage doit avoir et celui qu'il a déjà
				$nouvel_xp = $xp_after_rebuild - $rebuild->px_totaux;
				if( $nouvel_xp <= 0 || $this->ManageExperience( $rebuild->id, $nouvel_xp, FALSE, TRUE, mb_convert_encoding( "Transfert des points d'expérience de l'ancien personnage vers le nouveau.", 'ISO-8859-1', 'UTF-8') ) ){
					if( $nouvel_xp > 0 ){
						$rebuild->px_restants += $nouvel_xp;
						$rebuild->px_totaux += $nouvel_xp;
					}
					
					// Bloque les modifications
					$this->RecordAction( $rebuild->id, 0, 0, $c->id, mb_convert_encoding( "Copie du personnage.", 'ISO-8859-1', 'UTF-8'), FALSE );
					
					// Laisse une trace sur l'ancetre
					$this->UpdateBases( $c->id, $c->nom . $suffix, $c->alignement_id, $c->faction_id, $c->religion_id );
					$this->Deactivate( $c->id );
					
					$this->SaveCommentaire( $rebuild, "Rebuild de " . $c->nom . " (" . date( "Y-m-d" ) . ")" );
					
					return $rebuild->id;
				} else {
					Message::Erreur( "Incapable d'ajouter les points d'expérience au personnage." );
				}
			} else {
				Message::Erreur( "Incapable de créer le nouveau personnage à partir de l'ancien." );
			}
			return FALSE;
		}
		
		public function ManageExperience( $character_id, $modificateur, $silent = FALSE, $modif_total = FALSE, $raison = "" ){
			if( is_numeric( $modificateur ) ){
				$personnage_repository = new PersonnageRepository();
				$c = $personnage_repository->Find( $character_id );
				
				if( $c && $c->est_vivant ){
					$c->px_restants += $modificateur;
					
					if( $modif_total ){
						$c->px_totaux += $modificateur;
					}
					
					if( $personnage_repository->Save( $c ) ){
						if( !$silent ){
							// Ajoute des parenthese au commentaire
							if( $raison != "" ){
								$raison = " (" . $raison . ")";
							}
						
							// La raison recue a deja ete decodee par le controleur
							$record_msg = mb_convert_encoding( ( $modificateur >= 0 ? "Gain" : "Perte" ) . " de " . abs( $modificateur ) . " points d'expérience", 'ISO-8859-1', 'UTF-8') . $raison;
							$this->RecordAction( $c->id, CharacterSheet::RECORD_XP, 0, $modificateur, $record_msg, TRUE );
						}
						
						return TRUE;
					}
				}
			}
		}
		
		public function Rollback( Personnage &$character ){
			$rolled_back = FALSE;
			$last_modif = CharacterLog::GetLastByCharacter( $character->id );
			
			if( $last_modif != FALSE && $last_modif->CanBacktrack ){
				switch( $last_modif->Quoi ){
					case CharacterSheet::RECORD_XP :
						// Modification des XP
						if( $this->ManageExperience( $character->id, ( $last_modif->Combien * -1 ), TRUE, TRUE ) ){
							$rolled_back = true;
						}
						break;
					case CharacterSheet::RECORD_RACE :
						if( $this->RemoveRace( $character ) ){
							Message::Notice( "La race du personnage a été retirée." );
							$rolled_back = true;
						}
						break;
					case CharacterSheet::RECORD_RACIALE_CAPACITE :
						// Achat d'une capacite raciale
						if( $this->RefundCapaciteRaciale( $character, $last_modif->Pourquoi, $last_modif->Combien ) ){
							Message::Notice( "La capacité raciale a été retirée." );
							$rolled_back = true;
						}
						break;
					case CharacterSheet::RECORD_RACIALE_EMPTY :
						// Vidage des PCRs du personnage
						$personnage_repository = new PersonnageRepository();
						$character->pc_raciales += $last_modif->Combien;
						if( $personnage_repository->Save( $character ) ){
							Message::Notice( "La sélection de capacité raciale a été retirée." );
							$rolled_back = true;
						}
						break;
					//TODO: case CharacterSheet::RECORD_REBUILD :
					//TODO: case CharacterSheet::RECORD_DESTROY :
					//TODO: case CharacterSheet::RECORD_ACTIVATE :
					//	break;
					case CharacterSheet::RECORD_VOIE :
						// Achat d'une voie
						if( $this->RefundVoie( $character, $last_modif->Pourquoi ) ){
							Message::Notice( "La voie a été retiré." );
							$rolled_back = true;
						}
						break;
					case CharacterSheet::RECORD_CAPACITE :
						// Achat d'un niveau de capacité
						if( $this->RefundCapacite( $character, $last_modif->Pourquoi, $last_modif->Combien ) ){
							Message::Notice( "La capacité a été retiré." );
							$rolled_back = true;
						}
						break;
					case CharacterSheet::RECORD_CONNAISSANCE :
						// Achat d'une connaissance
						if( $this->RefundConnaissance( $character, $last_modif->Pourquoi ) ){
							Message::Notice( "La connaissance a été retiré." );
							$rolled_back = true;
						}
						break;
					case CharacterSheet::RECORD_CHOIX_CAPACITE :
						// Achat d'un choix de capacite
						if( $this->RefundChoixCapacite( $character, $last_modif->Pourquoi, $last_modif->Combien ) ){
							Message::Notice( "La sélection de capacité a été retirée." );
							$rolled_back = true;
						}
						break;
					default :
						Message::Fatale( "Type de modification inconnue.", func_get_args() );
						break;
				}
				
				if( $rolled_back ){
					$sql = "Update personnage_journal Set active = '0' Where id_personnage = ? And id = ?";
					$db = new Database();
					$db->Query( $sql, array( $character->id, $last_modif->Id ) );
					
					return TRUE;
				} else {
					// Sinon, remet le personnage comme il etait
					$personnage_repository = new PersonnageRepository();
					$character = $personnage_repository->FindComplete( $character->id );
				}
			}
			
			return FALSE;
		}
		
		const RECORD_MESSAGE = 0;
		const RECORD_XP = 1;
		const RECORD_RACE = 2;
		const RECORD_RACIALE_CAPACITE = 4;
		const RECORD_RACIALE_EMPTY = 5;
		const RECORD_REBUILD = 6;
		const RECORD_DESTROY = 7;
		const RECORD_ACTIVATE = 8;
		const RECORD_DEACTIVATE = 9;
		const RECORD_VOIE = 10;
		const RECORD_CAPACITE = 11;
		const RECORD_CONNAISSANCE = 12;
		const RECORD_CHOIX_CAPACITE = 21;
		private function RecordAction( $character_id, $quoi, $pourquoi, $combien, $note = "", $can_backtrack = true ){
			$personnage_repository = new PersonnageRepository();
			$c = $personnage_repository->Find( $character_id );
			
			if( $c ) {
				if( is_numeric( $quoi ) && is_numeric( $pourquoi ) && is_numeric( $combien ) ){
					// Si aucune note n'a été donnée, on la créé nous-même
					if( $note == "" ){
						$note = "Quoi : " . $quoi . ", Pourquoi : " . $pourquoi . ", Combien : " . $combien . ", Backtrack : " . (  ( $can_backtrack ) ? "true" : "false" );
					}
					
					$db = new Database();
					$sql = "Insert Into personnage_journal
								( id_personnage, quoi, pourquoi, combien, note, backtrack, joueur_id )
							Values
								( ?, ?, ?, ?, ?, ?, ? )";
					$db->Query( $sql, array( $c->id, $quoi, $pourquoi, $combien, $note, ( $can_backtrack ? '1' : '0' ), $_SESSION[ SESSION_KEY ][ "User" ]->Id ) );
					
					return TRUE;
				}
			}
			return FALSE;
		}
	}
?>