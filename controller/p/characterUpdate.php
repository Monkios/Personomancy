<?php
	$is_animateur = $_SESSION[ SESSION_KEY ][ "User" ]->IsAnimateur;
	$is_administrateur = $_SESSION[ SESSION_KEY ][ "User" ]->IsAdmin;
	
	if( is_numeric( $_GET["c"] ) ){
		$character_id = $_GET["c"];
		$char_sheet = new CharacterSheet;
		$personnage = $char_sheet->Load( $_GET['c'], TRUE );
		
		if( $personnage != FALSE ){
			// Bloque l'acces au personnage d'autrui sauf pour les animateurs
			if( !$is_animateur && $personnage->joueur_id != $_SESSION[ SESSION_KEY ][ "User" ]->Id ){
				Message::Fatale( "Vous n'avez pas le droit d'accéder à ce personnage.",
						"U" . $_SESSION[ SESSION_KEY ][ "User" ]->Id . " / C" . $_GET['c'] );
			}
			
			$can_change_bases = ( $personnage->est_cree == FALSE && ( empty( $personnage->race_id ) || $personnage->race_id == -1 ) ) || $is_animateur;
			$can_change_race = FALSE;
			$can_choose_spells = FALSE;
			$has_choices = FALSE;
			
			$can_change_xp = $personnage->est_cree && ( $is_administrateur || ( $is_animateur && $personnage->joueur_id == $_SESSION[ SESSION_KEY ][ "User" ]->Id ));
			$can_destroy = $is_administrateur || ( $is_animateur && $personnage->joueur_id == $_SESSION[ SESSION_KEY ][ "User" ]->Id );
			$can_rebuild = $can_rebuild_perte = $can_destroy;
			$can_add_comment = $can_destroy;
			$can_add_notes = $is_administrateur || $personnage->joueur_id == $_SESSION[ SESSION_KEY ][ "User" ]->Id;

			$list_journal = CharacterLog::GetByCharacter( $personnage->id );
			$last_log = CharacterLog::GetLastByCharacter( $personnage->id );
			
			$list_voies = Dictionary::GetVoies();
			$list_capacites = array();
			$list_sorts = array();
			$list_spheres = array();
			foreach( $list_voies as $voie_id => $voie_desc ){
				$capacites = Dictionary::GetCapacitesByVoie( $voie_id );
				$list_capacites[ $voie_id ] = $capacites;
				
				foreach( $capacites as $capacite_id => $capacite_nom ){
					if( array_key_exists( $capacite_id, $personnage->capacites ) && $personnage->capacites[ $capacite_id ] > 0 ){
						$list_spheres[ $capacite_id ] = $capacite_nom;
						
						$list_sorts[ $capacite_id ] = CapaciteRepository::GetSorts( $capacite_id );
						$can_choose_spells = TRUE;
					}
				}
			}
			$list_connaissances = Dictionary::GetConnaissances();
			$list_prestiges = Dictionary::GetPrestiges();
			$perso_prestige_sphere = 0;
			if( $personnage->prestige_id != 0 ){
				$perso_prestige_sphere = $list_prestiges[ $personnage->prestige_id ][ "voie_id" ];
			}
			
			$list_capacites_raciales = array();
			$race_capacites_raciales = array();
			if( is_numeric( $personnage->race_id ) && $personnage->race_id >= 0 && !$personnage->est_cree ){
				$rr = new RaceRepository();
				$race = $rr->Find( $personnage->race_id );
				if( $race ){
					$race_capacites_raciales = $race->list_capacites_raciales;
					foreach( $race_capacites_raciales as $cr_id => $cr_infos ){
						if( !array_key_exists( $cr_id, $personnage->capacites_raciales ) && $personnage->pc_raciales >= $cr_infos[ 1 ] ){
							$list_capacites_raciales[ $cr_id ] = $cr_infos[ 0 ] . " (" . $cr_infos[ 1 ] . " PCR)";
						}
					}
				}
			}
			
			$list_choix_capacites = array();
			$list_choix_capacites_capacites = array();
			if( count( $personnage->choix_capacites ) > 0 ){
				$ccar = new ChoixCapaciteRepository();
				foreach( $personnage->choix_capacites as $choix_id => $choix_nombre ){
					$choix_capacites = $ccar->Find( $choix_id );
					$list_choix_capacites[ $choix_id ] = $choix_capacites->nom;
					$list_choix_capacites_capacites[ $choix_id ] = $ccar->GetCapacites( $choix_capacites );
				}
			}
			
			$list_choix_connaissances = array();
			$list_choix_connaissances_connaissances = array();
			if( count( $personnage->choix_connaissances ) > 0 ){
				$ccor = new ChoixConnaissanceRepository();
				foreach( $personnage->choix_connaissances as $choix_id => $choix_nombre ){
					$choix_connaissance = $ccor->Find( $choix_id );
					$list_choix_connaissances[ $choix_id ] = $choix_connaissance->nom;
					$list_choix_connaissances_connaissances[ $choix_id ] = $ccor->GetConnaissances( $choix_connaissance );
				}
			}
			
			$list_choix_pouvoirs = array();
			$list_choix_pouvoirs_pouvoirs = array();
			if( count( $personnage->choix_pouvoirs ) > 0 ){
				$ccor = new ChoixPouvoirRepository();
				foreach( $personnage->choix_pouvoirs as $choix_id => $choix_nombre ){
					$choix_pouvoir = $ccor->Find( $choix_id );
					$list_choix_pouvoirs[ $choix_id ] = $choix_pouvoir->nom;
					$list_choix_pouvoirs_pouvoirs[ $choix_id ] = $ccor->GetPouvoirs( $choix_pouvoir );
				}
			}
			
			$has_choices = count( $list_choix_capacites ) > 0 || count( $list_choix_connaissances ) > 0 || count( $list_choix_pouvoirs ) > 0;
			
			// Liste seulement les informations de base si elles peuvent etre changees
			if( $can_change_bases ){
				if( $is_animateur ){
					$list_joueurs = Community::GetPlayerList( TRUE );
				}
				if( ( $last_log && $last_log->Quoi == CharacterSheet::RECORD_RACE ) || ( empty( $personnage->race_id ) || $personnage->race_id == -1 ) ){
					$can_change_race = TRUE;
					$list_races = Dictionary::GetRaces();
				}
				$list_alignements = Dictionary::GetAlignements();
				$list_factions = Dictionary::GetFactions();
				$list_religions = Dictionary::GetReligions();
				
				$perso_nom = $personnage->nom;
				$perso_alignement = $personnage->alignement_id;
				$perso_faction = $personnage->faction_id;
				$perso_religion = $personnage->religion_id;
				$perso_race = $personnage->race_id;
			}
			
			if( isset( $_GET["st"] ) ){
				if( $personnage->est_vivant ){
					// Sauvegarde des informations d'identification du personnage et changement de race
					if( $_GET["st"] == "bases" && isset( $_POST["save_bases"] ) ){
						$erreur = FALSE;
						if( $can_change_bases ){
							// Valide que le nom est bon
							if( empty( Security::FilterInput( $_POST['perso_nom'] ) ) ){
								Message::Erreur( "Vous n'avez fournit aucun nom pour ce personnage." );
								$erreur = TRUE;
							} else {
								$perso_nom = Security::FilterInput( $_POST['perso_nom'] );
							}
							// Valide que l'alignement est bon
							if( empty( $_POST['perso_alignement'] )
									|| !is_numeric( $_POST['perso_alignement'] )
									|| !array_key_exists( $_POST['perso_alignement'], $list_alignements ) ){
								Message::Erreur( "Vous n'avez choisit aucun alignement pour ce personnage." );
								$erreur = TRUE;
							} else {
								$perso_alignement = $_POST['perso_alignement'];
							}
							// Valide que la faction est bonne
							if( empty( $_POST['perso_faction'] )
									|| !is_numeric( $_POST['perso_faction'] )
									|| !array_key_exists( $_POST['perso_faction'], $list_factions ) ){
								Message::Erreur( "Vous n'avez choisit aucune faction pour ce personnage." );
								$erreur = TRUE;
							} else {
								$perso_faction = $_POST['perso_faction'];
							}
							// Valide que la religion est bonne
							if( empty( $_POST['perso_religion'] )
									|| !is_numeric( $_POST['perso_religion'] )
									|| !array_key_exists( $_POST['perso_religion'], $list_religions ) ){
								Message::Erreur( "Vous n'avez choisit aucune religion pour ce personnage." );
								$erreur = TRUE;
							} else {
								$perso_religion = $_POST['perso_religion'];
							}
						}
						
						// Ne fait pas la modification si l'un des champ est incorrect
						if( !$erreur ){
							// Ne fait la modification que si un des champ a change
							if( $perso_nom != $personnage->nom
									|| $perso_alignement != $personnage->alignement_id 
									|| $perso_faction != $personnage->faction_id 
									|| $perso_religion != $personnage->religion_id ){
								$personnage = $char_sheet->UpdateBases( $personnage->id, mb_convert_encoding( $perso_nom, 'ISO-8859-1', 'UTF-8'), $perso_alignement, $perso_faction, $perso_religion );
								
								if( $personnage == FALSE ){
									Message::Erreur( "Une erreur s'est produite en enregistrant les informations d'identification du personnage." );
									$erreur = true;
								} else {
									Message::Notice( "Les informations d'identification du personnage ont été enregistrées." );
								}
							}
							
							// Ne fait pas le changement de race si les informations de base n'ont pas ete enregistrees
							if( !empty( $_POST['perso_race'] )
									&& $can_change_race
									&& !$erreur ){
								// Valide que la race est bonne
								if( !is_numeric( $_POST['perso_race'] )
										|| !array_key_exists( $_POST['perso_race'], $list_races ) ){
									Message::Erreur( "Vous n'avez choisit aucune race pour ce personnage." );
								// Ne fait le changement que si la race a changee
								} elseif( $_POST['perso_race'] != $personnage->race_id ) {
									$perso_race = $_POST['perso_race'];
									$personnage = $char_sheet->ChangeRace( $personnage->id, $perso_race );
									
									if( $personnage == FALSE ){
										Message::Erreur( "Une erreur s'est produite lors du changement de race du personnage." );
									} else {
										Message::Notice( "La race du personnage a été changée." );
									}
								}
							}
						}
					// Alternativement, on peut aussi changer les points d'experience lorsque c'est permit
					} else if( $can_change_xp && isset( $_POST['change_xp'] ) &&is_numeric( $_POST[ 'change_xp' ] ) ){
						if( $char_sheet->ManageExperience( $personnage->id, $_POST[ 'change_xp' ], FALSE, TRUE, mb_convert_encoding( "Modification manuelle.", 'ISO-8859-1', 'UTF-8') ) ){
							Message::Notice( "Les points d'expérience du personnage ont été modifiés de " . $_POST['change_xp'] . "." );
						}
					}
					
					// Sauvegarde des selections de capacites raciales
					if( $_GET["st"] == "capacites_raciales" && isset( $_POST["perso_capacite_raciale"] ) ){
						if( is_numeric( $_POST["perso_capacite_raciale"] ) ){
							if( array_key_exists( $_POST["perso_capacite_raciale"], $list_capacites_raciales ) && !array_key_exists( $_POST["perso_capacite_raciale"], $personnage->capacites_raciales ) ){
								$personnage = $char_sheet->BuyCapaciteRaciale( $personnage->id, $_POST["perso_capacite_raciale"] );
								
								if( $personnage == FALSE ){
									Message::Erreur( "Une erreur s'est produite lors de l'ajout de la capacité raciale." );
								} else {
									Message::Notice( "La capacité raciale a été ajoutée." );
								}
							} else {
								Message::Erreur( "Le personnage possède déjà cette capacité raciale.", $_POST["perso_capacite_raciale"] );
							}
						} elseif( $_POST["perso_capacite_raciale"] == "burn" ) {
							$personnage = $char_sheet->BurnRemainingPCR( $personnage->id );
							if( $personnage == FALSE ){
								Message::Erreur( "Une erreur s'est produite lors de l'abandon des points de capacités raciales restants." );
							} else {
								Message::Notice( "Les points de capacités raciales restants ont été abandonnés." );
							}
						}
					}
					
					// Sauvegarde des selections de choix de capacites
					if( $_GET["st"] == "choix_capacites" && isset( $_POST["perso_choix_capacites"] ) && count( $_POST["perso_choix_capacites"] ) > 0 ){
						if( $personnage->pc_raciales == 0 ){
							// Bouclera sur chaque liste mais ne s'arretera pas sur celles qui ne retournent rien
							foreach( $_POST["perso_choix_capacites"] as $choix_capacites_id => $choix_capacites_capacite ){
								if( is_numeric( $choix_capacites_id )
										&& array_key_exists( $choix_capacites_id, $list_choix_capacites )
										&& array_key_exists( $choix_capacites_capacite, $list_choix_capacites_capacites[ $choix_capacites_id ] ) ){
									$personnage = $char_sheet->BuyChoixCapacite( $personnage->id, $choix_capacites_id, $choix_capacites_capacite );
							
									if( $personnage == FALSE ){
										Message::Erreur( "Une erreur s'est produite lors de la sélection de capacité." );
									} else {
										Message::Notice( "La capacité a été sélectionnée." );
									}
								}
							}
						} else {
							Message::Erreur( "Veuillez sélectionner vos capacités raciales avant de continuer." );
						}
					}
					
					// Sauvegarde des selections de choix de connaissances
					if( $_GET["st"] == "choix_connaissances" && isset( $_POST["perso_choix_connaissances"] ) && count( $_POST["perso_choix_connaissances"] ) > 0 ){
						if( $personnage->pc_raciales == 0 ){
							// Bouclera sur chaque liste mais ne s'arretera pas sur celles qui ne retournent rien
							foreach( $_POST["perso_choix_connaissances"] as $choix_connaissances_id => $choix_connaissances_connaissance ){
								if( is_numeric( $choix_connaissances_id )
										&& array_key_exists( $choix_connaissances_id, $list_choix_connaissances )
										&& array_key_exists( $choix_connaissances_connaissance, $list_choix_connaissances_connaissances[ $choix_connaissances_id ] ) ){
									$personnage = $char_sheet->BuyChoixConnaissance( $personnage->id, $choix_connaissances_id, $choix_connaissances_connaissance );
							
									if( $personnage == FALSE ){
										Message::Erreur( "Une erreur s'est produite lors de la sélection de connaissance." );
									} else {
										Message::Notice( "La connaissance a été sélectionnée." );
									}
								}
							}
						} else {
							Message::Erreur( "Veuillez sélectionner vos capacités raciales avant de continuer." );
						}
					}
					
					// Sauvegarde des selections de choix de pouvoirs
					if( $_GET["st"] == "choix_pouvoirs" && isset( $_POST["perso_choix_pouvoirs"] ) && count( $_POST["perso_choix_pouvoirs"] ) > 0 ){
						if( $personnage->pc_raciales == 0 ){
							// Bouclera sur chaque liste mais ne s'arretera pas sur celles qui ne retournent rien
							foreach( $_POST["perso_choix_pouvoirs"] as $choix_pouvoirs_id => $choix_pouvoirs_pouvoir ){
								if( is_numeric( $choix_pouvoirs_id )
										&& array_key_exists( $choix_pouvoirs_id, $list_choix_pouvoirs )
										&& array_key_exists( $choix_pouvoirs_pouvoir, $list_choix_pouvoirs_pouvoirs[ $choix_pouvoirs_id ] ) ){
									$personnage = $char_sheet->BuyChoixPouvoir( $personnage->id, $choix_pouvoirs_id, $choix_pouvoirs_pouvoir );
							
									if( $personnage == FALSE ){
										Message::Erreur( "Une erreur s'est produite lors de la sélection de capacité." );
									} else {
										Message::Notice( "La capacité a été sélectionnée." );
									}
								}
							}
						} else {
							Message::Erreur( "Veuillez sélectionner vos capacités raciales avant de continuer." );
						}
					}
					
					// Activation du personnage
					if( $_GET["st"] == "activation"
							&& isset( $_POST["activate_character"] )
							&& $personnage->pc_raciales == 0
							&& !$has_choices ){
							
						if( !$char_sheet->Activate( $personnage->id ) ){
							Message::Erreur( "Une erreur s'est produite lors d'activation de ce personnage." );
						} else {
							Message::Notice( "Le personnage a été activé." );
						}
					}
					
					if( $personnage->est_cree ){
						// Sauvegarde de l'achat d'un point d'attribut
						if( $_GET["st"] == "buy_attribute" ){
							$attr = FALSE;
							$diff = 0;
							
							if( isset( $_POST["perso_constitution"] ) && is_numeric( $_POST["perso_constitution"] ) ){
								$attr = CharacterSheet::BUY_ATTR_CONSTITUTION;
								$diff = $_POST["perso_constitution"] - $personnage->constitution;
							} else if( isset( $_POST["perso_spiritisme"] ) && is_numeric( $_POST["perso_spiritisme"] ) ){
								$attr = CharacterSheet::BUY_ATTR_SPIRITISME;
								$diff = $_POST["perso_spiritisme"] - $personnage->spiritisme;
							} else if( isset( $_POST["perso_intelligence"] ) && is_numeric( $_POST["perso_intelligence"] ) ){
								$attr = CharacterSheet::BUY_ATTR_INTELLIGENCE;
								$diff = $_POST["perso_intelligence"] - $personnage->intelligence;
							} else if( isset( $_POST["perso_alerte"] ) && is_numeric( $_POST["perso_alerte"] ) ){
								$attr = CharacterSheet::BUY_ATTR_ALERTE;
								$diff = $_POST["perso_alerte"] - $personnage->alerte;
							} else if( isset( $_POST["perso_vigueur"] ) && is_numeric( $_POST["perso_vigueur"] ) ){
								$attr = CharacterSheet::BUY_ATTR_VIGUEUR;
								$diff = $_POST["perso_vigueur"] - $personnage->vigueur;
							} else if( isset( $_POST["perso_volonte"] ) && is_numeric( $_POST["perso_volonte"] ) ){
								$attr = CharacterSheet::BUY_ATTR_VOLONTE;
								$diff = $_POST["perso_volonte"] - $personnage->volonte;
							}
							
							if( $attr != FALSE && $diff > 0 ){
								$personnage = $char_sheet->BuyAttribute( $personnage->id, $attr, $diff );
								
								if( $personnage == FALSE ){
									Message::Erreur( "Une erreur s'est produite lors de l'augmentation de l'attribut." );
								} else {
									Message::Notice( "L'attribut a été augmenté." );
								}
							}
						}
						
						// Sauvegarde de l'achat d'un point de capacite
						if( $_GET["st"] == "buy_capacite" ){
							if( isset( $_POST["perso_voie"] )
									&& is_numeric( $_POST["perso_voie"] )
									&& array_key_exists( $_POST["perso_voie"], $list_voies ) 
									&& !in_array( $_POST["perso_voie"], $personnage->voies ) ){
								$personnage = $char_sheet->BuyVoie( $personnage->id, $_POST["perso_voie"] );
								
								if( $personnage == FALSE ){
									Message::Erreur( "Une erreur s'est produite lors de l'ajout de la voie." );
								} else {
									Message::Notice( "La voie a été ajoutée." );
								}
							}
							
							if( isset( $_POST["perso_capacite"] ) ){
								foreach( $_POST["perso_capacite"] as $capacite_id => $new_score ){
									if( is_numeric( $capacite_id )
											&& is_numeric( $new_score )
											&& array_key_exists( $capacite_id, $personnage->capacites ) ){
										$diff = $new_score - $personnage->capacites[ $capacite_id ];
											
										if( $diff > 0 ){
											$personnage = $char_sheet->BuyCapacite( $personnage->id, $capacite_id, $diff );
											
											if( $personnage == FALSE ){
												Message::Erreur( "Une erreur s'est produite lors de l'augmentation de la capacité." );
											} else {
												Message::Notice( "La capacité a été augmentée." );
											}
										}
									}
								}
							}
							
							if( isset( $_POST["perso_prestige"] )
									&& is_numeric( $_POST["perso_prestige"] )
									&& array_key_exists( $_POST["perso_prestige"], $list_prestiges ) 
							){
								$personnage = $char_sheet->BuyPrestige( $personnage->id, $_POST["perso_prestige"] );
								
								if( $personnage == FALSE ){
									Message::Erreur( "Une erreur s'est produite lors de l'ajout de la connaissance de prestige." );
								} else {
									Message::Notice( "La connaissance de prestige a été ajoutée." );
								}
							}
						}
						
						// Sauvegarde de l'achat d'une connaissance
						if( $_GET["st"] == "buy_connaissance" ){
							if( isset( $_POST["perso_connaissance"] )
									&& is_numeric( $_POST["perso_connaissance"] )
									&& array_key_exists( $_POST["perso_connaissance"], $list_connaissances ) 
									&& !in_array( $_POST["perso_connaissance"], $personnage->connaissances ) ){
								$personnage = $char_sheet->BuyConnaissance( $personnage->id, $_POST["perso_connaissance"] );
								
								if( $personnage == FALSE ){
									Message::Erreur( "Une erreur s'est produite lors de l'ajout de la connaissance." );
								} else {
									Message::Notice( "La connaissance a été ajoutée." );
								}
							}
						}
						
						if( $_GET["st"] == "buy_sort" ){
							if( isset( $_POST["perso_sort"] )
									&& is_numeric( $_POST["perso_sort"] ) ){
								$sr = new SortRepository();
								$sort = $sr->Find( $_POST["perso_sort"] );
								
								if( $sort && $sort->active
										&& array_key_exists( $sort->sphere_id, $list_sorts )
										&& array_key_exists( $sort->id, $list_sorts[ $sort->sphere_id ] )
										&& array_key_exists( $sort->sphere_id, $personnage->capacites )
										&& $personnage->capacites[ $sort->sphere_id ] > 0
										&& ( !array_key_exists( $sort->sphere_id, $personnage->sorts )
											|| !in_array( $sort->id, $personnage->sorts[ $sort->sphere_id ] ) )
								){
									$personnage = $char_sheet->BuySort( $personnage->id, $sort->id );
									
									if( $personnage == FALSE ){
										Message::Erreur( "Une erreur s'est produite lors de l'ajout du sort." );
									} else {
										Message::Notice( "Le sort a été ajoutée." );
									}
								}
							}
						}
					}
					
					// Retour en arriere
					if( $_GET["st"] == "rollback"
							&& isset( $_POST["perso_rollback_id"] )
							&& is_numeric( $_POST["perso_rollback_id"] )
							&& $_POST["perso_rollback_id"] == $last_log->Id
							&& $last_log->CanBacktrack ){
						if( $last_log->Quoi == CharacterSheet::RECORD_XP && !$is_administrateur ){
							Message::Erreur( "Le gain d'XP ne peut être annulé par un retour en arrière." );
						} else {
							$personnage = $char_sheet->RollBack( $personnage );
							if( $personnage == FALSE ){
								Message::Erreur( "Une erreur s'est produite lors du retour en arrière." );
							}
						}
					}
					
					if( $_GET["st"] == "kill" ){
						$personnage = $char_sheet->Deactivate( $personnage->id );
						if( $personnage == FALSE ){
							Message::Erreur( "Une erreur s'est produite lors de la désactivation." );
						}
					}
				}

				if( $_GET["st"] == "comment" && isset( $_POST["perso_save_comment"] ) && isset( $_POST["perso_comment"] ) ){				
					if( $can_add_comment ){
						if( !$char_sheet->SaveCommentaire( $personnage, Security::FilterInput( $_POST["perso_comment"] ) ) ){
							Message::Erreur( "Une erreur s'est produite en enregistrant le commentaire." );
						} else {
							Message::Notice( "Le commentaire a été enregistré." );
						}
					}
				}
				
				if( $_GET["st"] == "notes" && isset( $_POST["perso_save_notes"] ) && isset( $_POST["perso_notes"] ) ){
					if( $can_add_notes ){
						if( !$char_sheet->SaveNotes( $personnage, Security::FilterInput( $_POST["perso_notes"] ) ) ){
							Message::Erreur( "Une erreur s'est produite en enregistrant les notes." );
						} else {
							Message::Notice( "Les notes ont été enregistrées." );
						}
					}
				}
				
				if( $_GET["st"] == "delete" && isset( $_POST["perso_delete"] ) ){
					if( $can_destroy ){
						if( !$char_sheet->Destroy( $personnage->id ) ){
							Message::Erreur( "Une erreur s'est produite lors de la suppression." );
						} else {
							Message::Notice( "La suppression s'est déroulée avec succès." );
							header( "Location: ?s=player" );
							die();
						}
					} else {
						Message::Erreur( "Vous n'avez pas les droits de suppression sur ce personnage." );
					}
				}
				
				if( $_GET["st"] == "rebuild" && isset( $_POST["perso_rebuild"] ) ){
					if( $can_rebuild ){
						$personnage_id = $char_sheet->RebuildComplet( $personnage->id );
						if( $personnage_id == FALSE ){
							Message::Erreur( "Une erreur s'est produite lors du rebuild." );
						} else {
							$character_id = $personnage_id;
							Message::Notice( "Le rebuild s'est déroulé avec succès." );
						}
					} else {
						Message::Erreur( "Vous n'avez pas les droits de rebuild sur ce personnage." );
					}
				}
				
				if( $_GET["st"] == "rebuild_perte" && isset( $_POST["perso_rebuild_perte"] ) ){
					if( $can_rebuild_perte ){
						$personnage_id = $char_sheet->RebuildPerte( $personnage->id );
						if( $personnage_id == FALSE ){
							Message::Erreur( "Une erreur s'est produite lors du rebuild à perte." );
						} else {
							$character_id = $personnage_id;
							Message::Notice( "Le rebuild à perte s'est déroulé avec succès." );
						}
					} else {
						Message::Erreur( "Vous n'avez pas les droits de rebuild à perte sur ce personnage." );
					}
				}
				
				header( "Location: ?s=player&a=characterUpdate&c=" . $character_id );
				die();
			}
		} else {
			Message::Erreur( "Personnage inexistant." );
			header( "Location: ?s=player" );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant de personnage doit être numérique." );
	}
	
	include "./views/top.php";
	include "./views/p/characterUpdate.php";
	include "./views/bottom.php";
?>