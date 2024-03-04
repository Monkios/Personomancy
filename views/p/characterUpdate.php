<?php
	include "./views/view_helpers.php";
?>
		<h2>Fiche de personnage</h2>
		<div id="fiche_perso">
			<div class="fiche_regroupement fiche_regroupement_base">
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=bases">
					<div class="fiche_element">
						<label for="perso_joueur">Nom du joueur :</label>
						<span id="perso_joueur" class="as_input"><?php echo utf8_encode( $personnage->joueur_nom ); ?></span>
					</div>
					<div class="fiche_element">
						<label for="perso_nom">Nom du personnage :</label>
<?php
	if( $can_change_bases && $personnage->est_vivant ){
?>
						<input type="text" name="perso_nom" id="perso_nom" value="<?php echo utf8_encode( $perso_nom ); ?>" />
<?php
	} else {
?>
						<span id="perso_nom" class="as_input"><?php echo utf8_encode( $personnage->nom ); ?></span>
<?php
	}
?>
					</div>
					<div class="fiche_element">
						<label for="perso_alignement">Alignement :</label>
<?php
	if( $can_change_bases && $personnage->est_vivant ){
?>
						<select name="perso_alignement" id="perso_alignement">
<?php
		foreach( $list_alignements as $alignement_id => $alignement_nom ){
?>
							<option value="<?php echo $alignement_id; ?>"<?php echo ( $alignement_id == $perso_alignement ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $alignement_nom ); ?></option>
<?php
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_alignement" class="as_input"><?php echo utf8_encode( $personnage->alignement_nom ); ?></span>
<?php
	}
?>
					</div>
					<div class="fiche_element">
						<label for="perso_religion">Religion :</label>
<?php
	if( $can_change_bases && $personnage->est_vivant ){
?>
						<select name="perso_religion" id="perso_religion">
<?php
		foreach( $list_religions as $religion_id => $religion_infos ){
?>
							<option value="<?php echo $religion_id; ?>"<?php echo ( $religion_id == $perso_religion ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $religion_infos[ "nom" ] ); ?></option>
<?php
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_religion" class="as_input"><?php echo utf8_encode( $personnage->religion_nom ); ?></span>
<?php
	}
?>
					</div>
					<div class="fiche_element">
						<label for="perso_race">Race :</label>
<?php
	if( $can_change_race && $personnage->est_vivant ){
?>
						<select name="perso_race" id="perso_race">
							<option>-- Choix nécessaire --</option>
<?php
		foreach( $list_races as $race_id => $race_nom ){
?>
							<option value="<?php echo $race_id; ?>"<?php echo ( $race_id == $perso_race ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $race_nom ); ?></option>
<?php
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_race" class="as_input"><?php echo utf8_encode( $personnage->race_nom ); ?></span>
<?php
	}
?>
					</div>
					<div class="fiche_element">
						<label for="perso_faction">Faction :</label>
<?php
	if( $can_change_bases && $personnage->est_vivant ){
?>
						<select name="perso_faction" id="perso_faction">
<?php
		foreach( $list_factions as $faction_id => $faction_nom ){
			if( $faction_id != 1 ){
?>
							<option value="<?php echo $faction_id; ?>"<?php echo ( $faction_id == $perso_faction ) ? " selected='selected'" : ""; ?>><?php echo utf8_encode( $faction_nom ); ?></option>
<?php
			}
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_faction" class="as_input"><?php echo utf8_encode( $personnage->faction_nom ); ?></span>
<?php
	}
?>
					</div>
					<div class="fiche_element<?php echo ( $can_change_xp ? " xp_anim" : "" ); ?>">
<?php
	$exceeding_xp = $personnage->GetExceedingXP();
	$xp_txt = $personnage->GetRealCurrentXP() . " / ";
	if( $exceeding_xp <= 0 ){
		$xp_txt .= $personnage->px_totaux;
	} else {
		$xp_txt .= ( $personnage->px_totaux - $exceeding_xp );
		$xp_txt .= " (+" . $exceeding_xp . ")";
	}
?>
						<label for="perso_xp">Points d'expérience :</label>
						<span id="perso_xp" class="as_input"><?php echo $xp_txt; ?></span>
<?php
	if( $can_change_xp ){
?>
						<select name="change_xp" onchange="this.form.submit();">
<?php
		for( $xp = -100; $xp <= 250; $xp += 5 ){
?>
							<option value="<?php echo $xp; ?>"<?php echo $xp == 0 ? " selected='selected'" : ""; ?>><?php echo $xp > 0 ? "+" . $xp : $xp; ?> XP</option>
<?php
		}
?>
						</select>
<?php
	}
?>
					</div>
<?php
	if( $can_change_bases && $personnage->est_vivant ){
?>
					<div>
						<input type="submit" name="save_bases" value="Enregistrer" />
					</div>
<?php
	}
?>
				</form>
			</div>
<?php
	if( is_numeric( $personnage->race_id ) && $personnage->race_id >= 0 && $personnage->est_vivant && ( $personnage->est_cree == FALSE || $has_choices ) ){
?>
			<div class="fiche_regroupement fiche_regroupement_choix">
				<h3>Création du personnage</h3>
<?php
		if( $personnage->pc_raciales > 0 ){
?>
				<div class="fiche_element">
					<p>Chaque personnage débute avec <?php echo CHARACTER_BASE_PCR; ?> points qu'il peut utiliser pour sélectionner des capacités raciales. Chaque capacité raciale ne peut être sélectionnée qu'une seule fois.</p>
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=capacites_raciales">
						<label for="perso_capacite_raciale">Choix de capacités raciales :</label>
						<select name="perso_capacite_raciale" id="perso_capacite_raciale" onchange="this.form.submit();">
							<option>-- Choix nécessaire --</option>
<?php
			foreach( $list_capacites_raciales as $cr_id => $cr_desc ){
?>
							<option value="<?php echo $cr_id; ?>"><?php echo utf8_encode( $cr_desc ); ?></option>
<?php
			}
?>
							<option value="burn">Abandonner les points restants (<?php echo $personnage->pc_raciales; ?> PCR)</option>
						</select>
					</form>
				</div>
<?php
		}
		if( count( $personnage->choix_capacites ) > 0 ){
?>
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=choix_capacites">
<?php
			foreach( $personnage->choix_capacites as $choix_id => $choix_nombre ){
?>
					<div class="fiche_element">
						<label for="perso_choix_capacites_<?php echo $choix_id; ?>">Choix de capacités "<?php echo utf8_encode( $list_choix_capacites[ $choix_id ] ); ?>" (x<?php echo $choix_nombre; ?>) :</label>
						<select name="perso_choix_capacites[<?php echo $choix_id; ?>]" id="perso_choix_capacites_<?php echo $choix_id; ?>" onchange="this.form.submit();">
							<option value="">-- Choix nécessaire --</option>
<?php
				foreach( $list_choix_capacites_capacites[ $choix_id ] as $capacite_id => $capacite_infos ){
					if( $capacite_infos->active && in_array( $capacite_infos->voie_id, $personnage->voies ) ){
?>
							<option value="<?php echo $capacite_id; ?>"<?php echo ( $personnage->pc_raciales > 0 ) ? " disabled='disabled'" : ""; ?>><?php echo utf8_encode( $capacite_infos->nom ); ?></option>
<?php
					}
				}
?>
						</select>
					</div>
<?php
			}
?>
				</form>
<?php
		}
		if( count( $personnage->choix_connaissances ) > 0 ){
?>
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=choix_connaissances">
<?php
			foreach( $personnage->choix_connaissances as $choix_id => $choix_nombre ){
?>
					<div class="fiche_element">
						<label for="perso_choix_connaissances_<?php echo $choix_id; ?>">Choix de connaissances "<?php echo utf8_encode( $list_choix_connaissances[ $choix_id ] ); ?>" (x<?php echo $choix_nombre; ?>) :</label>
						<select name="perso_choix_connaissances[<?php echo $choix_id; ?>]" id="perso_choix_connaissances_<?php echo $choix_id; ?>" onchange="this.form.submit();">
							<option value="">-- Choix nécessaire --</option>
<?php
				foreach( $list_choix_connaissances_connaissances[ $choix_id ] as $connaissance_id => $connaissance_infos ){
					if( $connaissance_infos->active && !in_array( $connaissance_id, $personnage->connaissances ) && in_array( $connaissance_id, $personnage->connaissances_accessibles ) ){
?>
							<option value="<?php echo $connaissance_id; ?>"<?php echo ( $personnage->pc_raciales > 0 ) ? " disabled='disabled'" : ""; ?>><?php echo utf8_encode( $connaissance_infos->nom ); ?></option>
<?php
					}
				}
?>
						</select>
					</div>
<?php
			}
?>
				</form>
<?php
		}
		if( count( $personnage->choix_pouvoirs ) > 0 ){
?>
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=choix_pouvoirs">
<?php
			foreach( $personnage->choix_pouvoirs as $choix_id => $choix_nombre ){
?>
					<div class="fiche_element">
						<label for="perso_choix_pouvoirs_<?php echo $choix_id; ?>">Choix de pouvoirs "<?php echo utf8_encode( $list_choix_pouvoirs[ $choix_id ] ); ?>" (x<?php echo $choix_nombre; ?>) :</label>
						<select name="perso_choix_pouvoirs[<?php echo $choix_id; ?>]" id="perso_choix_pouvoirs_<?php echo $choix_id; ?>" onchange="this.form.submit();">
							<option value="">-- Choix nécessaire --</option>
<?php
				foreach( $list_choix_pouvoirs_pouvoirs[ $choix_id ] as $pouvoir_id => $pouvoir_infos ){
					if( $pouvoir_infos->active ){
?>
							<option value="<?php echo $pouvoir_id; ?>"<?php echo ( $personnage->pc_raciales > 0 ) ? " disabled='disabled'" : ""; ?>><?php echo utf8_encode( $pouvoir_infos->nom ); ?></option>
<?php
					}
				}
?>
						</select>
					</div>
<?php
			}
?>
				</form>
<?php
		}
?>
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=activation" onsubmit="return confirm('Voulez-vous vraiment activer ce personnage ?\n\nUne fois activé, il ne sera plus possible de changer ses informations de base.');">
<?php
		if( ( $personnage->pc_raciales > 0 ) || $has_choices ){
?>
					<p>Avant de pouvoir activer le personnage, un élément de chaque liste ci-dessus doit être sélectionné.</p>
<?php
		}
?>
					<input type="submit" name="activate_character" value="Activer le personnage"<?php echo ( $personnage->pc_raciales > 0 ) || $has_choices ? " disabled='disabled'" : ""; ?> />
					<p>En activant ce personnage, il pourra recevoir ses points d'expérience mais les informations de base du personnage (nom, alignement, religion) ne pourront plus être changées.</p>
				</form>
			</div>
<?php
	}
?>
			<div class="fiche_regroupement fiche_regroupement_statistiques">
				<h3>Statistiques</h3>
				<div class="fiche_element">
					<label for="perso_hp">Points de Vie :</label>
					<span id="perso_hp" class="as_input"><?php echo $personnage->GetPointsVieStr(); ?></span>
				</div>
				<div class="fiche_element">
					<label for="perso_mp">Points de Magie :</label>
					<span id="perso_mp" class="as_input"><?php echo $personnage->GetPointsMagieStr(); ?></span>
				</div>
				<div class="fiche_element">
					<label for="perso_fm">Force Magique :</label>
					<span id="perso_fm" class="as_input"><?php echo $personnage->GetForceMagique(); ?></span>
				</div>
				<div class="fiche_element">
					<label for="perso_s_alerte">Sauvegarde Alerte :</label>
					<span id="perso_s_alerte" class="as_input"><?php echo $personnage->GetSauvegardeAlerte(); ?></span>
				</div>
				<div class="fiche_element">
					<label for="perso_s_vigueur">Sauvegarde Vigueur :</label>
					<span id="perso_s_vigueur" class="as_input"><?php echo $personnage->GetSauvegardeVigueurStr(); ?></span>
				</div>
				<div class="fiche_element">
					<label for="perso_s_volonte">Sauvegarde Volonté :</label>
					<span id="perso_s_volonte" class="as_input"><?php echo $personnage->GetSauvegardeVolonteStr(); ?></span>
				</div>
			</div>
			<div class="fiche_regroupement fiche_regroupement_attributs">
				<h3>Attributs</h3>
				<div class="fiche_element">
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_attribute">
<?php
	AddSelectionsPips( "Constitution", "perso_constitution", $personnage->constitution, $personnage->est_vivant && $personnage->est_cree ? $personnage->GetRealCurrentXP() : 0 );
?>
					</form>
				</div>
				<div class="fiche_element">
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_attribute">
<?php
	AddSelectionsPips( "Spiritisme", "perso_spiritisme", $personnage->spiritisme, $personnage->est_vivant && $personnage->est_cree ? $personnage->GetRealCurrentXP() : 0 );
?>
					</form>
				</div>
				<div class="fiche_element">
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_attribute">
<?php
	AddSelectionsPips( "Intelligence", "perso_intelligence", $personnage->intelligence, $personnage->est_vivant && $personnage->est_cree ? $personnage->GetRealCurrentXP() : 0 );
?>
					</form>
				</div>
				<div class="fiche_element">
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_attribute">
<?php
	AddSelectionsPips( "Alerte", "perso_alerte", $personnage->alerte, $personnage->est_vivant && $personnage->est_cree ? $personnage->GetRealCurrentXP() : 0 );
?>
					</form>
				</div>
				<div class="fiche_element">
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_attribute">
<?php
	AddSelectionsPips( "Vigueur", "perso_vigueur", $personnage->vigueur, $personnage->est_vivant && $personnage->est_cree ? $personnage->GetRealCurrentXP() : 0 );
?>
					</form>
				</div>
				<div class="fiche_element">
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_attribute">
<?php
	AddSelectionsPips( "Volonté", "perso_volonte", $personnage->volonte, $personnage->est_vivant && $personnage->est_cree ? $personnage->GetRealCurrentXP() : 0 );
?>
					</form>
				</div>
			</div>
			<div class="fiche_regroupement fiche_regroupement_cr">
				<h3>Capacités raciales</h3>
<?php
	$nb_cr = 0;
	foreach( $personnage->capacites_raciales as $cr_id => $cr_desc ){
?>
				<div class="fiche_element">
					<label for="perso_cr_<?php echo $cr_id; ?>">CR <?php echo ++$nb_cr; ?> :</label>
					<span id="perso_cr_<?php echo $cr_id; ?>" class="as_input"><?php echo utf8_encode( $cr_desc ); ?></span>
				</div>
<?php
	}
?>
			</div>
			<div class="fiche_regroupement fiche_regroupement_capacites">
<?php
	foreach( $list_voies as $voie_id => $voie_desc ){
		$has_voie = in_array( $voie_id, $personnage->voies );
		
		$voie_status = "";
		if( $has_voie ){
			$voie_status = "checked='checked' disabled='disabled'";
		} elseif( $personnage->est_vivant && $personnage->est_cree ) {
			$voie_status = "onclick='this.form.submit();'";
		} else {
			$voie_status = "disabled='disabled'";
		}
?>
				<div>
					<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_capacite">
						<h3>
							<span><?php echo utf8_encode( $voie_desc ); ?></span>
							<input type="checkbox" name="perso_voie" value="<?php echo $voie_id; ?>"<?php echo $voie_status; ?> />
						</h3>
<?php
		if( $has_voie
				&& ( $personnage->prestige_id == 0 
						|| $perso_prestige_sphere == $voie_id )
		){
?>
						<div class="fiche_element_prestige">
<?php
			foreach( $list_prestiges as $prestige_id => $prestige_infos ){
				if( $prestige_infos[ "voie_id" ] == $voie_id
						&& ( $personnage->prestige_id == 0 || $personnage->prestige_id == $prestige_id )
				){
					$prestige_status = "";
					if( $personnage->prestige_id == $prestige_id ){
						$prestige_status = " checked='checked' disabled='disabled'";
					} elseif( $personnage->GetRealCurrentXP() >= CharacterSheet::GetPrestigeCost() && $personnage->est_vivant && $personnage->est_cree ){
						$prestige_status = " onchange='this.form.submit()'";
					} else {
						$prestige_status = " disabled='disabled'";
					}
?>
							<div class="fiche_element">
								<label for="perso_prestige_<?php echo $prestige_id; ?>"><?php echo utf8_encode( $prestige_infos[ "nom" ] ); ?></label>
								<input type="checkbox" name="perso_prestige" id="perso_prestige_<?php echo $prestige_id; ?>" value="<?php echo $prestige_id; ?>"<?php echo $prestige_status; ?> />
							</div>
<?php
				}
			}
?>
						</div>
<?php
		}
?>
<?php
		foreach( $list_capacites[ $voie_id ] as $capacite_id => $capacite_desc ){
			$nb_selections = 0;
			if( array_key_exists( $capacite_id, $personnage->capacites ) ){
				$nb_selections = $personnage->capacites[ $capacite_id ];
			}
			$px_restants = $personnage->GetRealCurrentXP();
			if( !$has_voie || !$personnage->est_vivant || !$personnage->est_cree ){
				$px_restants = 0;
			}
?>
						<div class="fiche_element">
<?php
			AddSelectionsPips( utf8_encode( $capacite_desc ), "perso_capacite[" . $capacite_id . "]", $nb_selections, $px_restants );
?>
						</div>
<?php
		}
?>
					</form>
				</div>
<?php
	}
?>
			</div>
			<div class="fiche_regroupement fiche_regroupement_connaissances">
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_connaissance">
					<h3>Connaissances</h3>
<?php
	foreach( $personnage->connaissances_accessibles as $connaissance_id ){
		$connaissance_status = "";
		if( in_array( $connaissance_id, $personnage->connaissances ) ){
			$connaissance_status = " checked='checked' disabled='disabled'";
		} elseif( CharacterSheet::GetConnaissanceCost() <= $personnage->GetRealCurrentXP() && $personnage->est_vivant && $personnage->est_cree ) {
			$connaissance_status = " onchange='this.form.submit()'";
		} else {
			$connaissance_status = " disabled='disabled'";
		}
?>
					<div class="fiche_element">
						<label for="perso_connaissance_<?php echo $connaissance_id; ?>" title="<?php echo htmlentities( utf8_encode( $list_connaissances[ $connaissance_id ] ) ); ?>"><?php echo utf8_encode( $list_connaissances[ $connaissance_id ] ); ?></label>
						<input type="checkbox" name="perso_connaissance" id="perso_connaissance_<?php echo $connaissance_id; ?>" value="<?php echo $connaissance_id; ?>"<?php echo $connaissance_status; ?> />
					</div>
<?php
	}
?>
				</form>
			</div>
<?php
	if( $can_choose_spells ){
?>
			<div class="fiche_regroupement fiche_regroupement_sorts">
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=buy_sort">
					<h3>Sortilèges et Recettes</h3>
<?php
		foreach( $list_sorts as $sphere_id => $sphere_sorts ){
			if( count( $sphere_sorts ) > 0 ){
				$sphere_sorts_acquis = 0;
				if( array_key_exists( $sphere_id, $personnage->sorts ) ){
					$sphere_sorts_acquis = count( $personnage->sorts[ $sphere_id ] );
				}
?>
					<h4><?php echo utf8_encode( $list_spheres[ $sphere_id ] ); ?></h4>
<?php
				foreach( $list_sorts[ $sphere_id ] as $sort_id => $sort_infos ){
					if( $sort_infos->active && $sort_infos->cercle <= $personnage->capacites[ $sphere_id ] ){
						$sort_status = "";
						if( $sphere_sorts_acquis > 0 && in_array( $sort_id, $personnage->sorts[ $sphere_id ] ) ){
							$sort_status = " checked='checked' disabled='disabled'";
						} elseif( $sphere_sorts_acquis + 1 >= $sort_infos->cercle && $personnage->capacites[ $sphere_id ] > $sphere_sorts_acquis && $personnage->est_vivant && $personnage->est_cree ) {
							$sort_status = " onchange='this.form.submit()'";
						} else {
							$sort_status = " disabled='disabled'";
						}
?>
					<div class="fiche_element">
						<label for="perso_sort_<?php echo $sort_id; ?>"><?php echo utf8_encode( $sort_infos->GetCompleteName() ); ?></label>
						<input type="checkbox" name="perso_sort" id="perso_sort_<?php echo $sort_id; ?>" value="<?php echo $sort_id; ?>"<?php echo $sort_status; ?> />
					</div>
<?php
					}
				}
?>
<?php
			}
		}
?>
				</form>
			</div>
<?php
	}
?>
		</div>
		<div id="perso_options">
			<h3>Gestion du personnage</h3>
			<h4>Commentaires de l'animation</h4>
<?php
	if( $can_add_comment ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=comment">
				<textarea name="perso_comment"><?php echo utf8_encode( $personnage->commentaire ); ?></textarea>
				<input type="submit" name="perso_save_comment" value="Enregistrer le commentaire" />
			</form>
<?php
	} else {
?>
			<textarea readonly="readonly"><?php echo utf8_encode( $personnage->commentaire ); ?></textarea>
<?php
	}
?>
			<h4>Notes</h4>
<?php
	if( $can_add_notes ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=notes">
				<textarea name="perso_notes"><?php echo utf8_encode( $personnage->notes ); ?></textarea>
				<input type="submit" name="perso_save_notes" value="Enregistrer les notes" />
			</form>
<?php
	} else {
?>
			<textarea readonly="readonly"><?php echo utf8_encode( $personnage->notes ); ?></textarea>
<?php
	}
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=rollback" id="perso_journal">
				<h4>Journal des modifications</h4>
				<ul>
<?php
	$last_modification = TRUE;
	foreach( $list_journal as $journal_id => $journal_desc ){
?>
					<li>
						<span><?php echo date( "Y-m-d H:i", strtotime( $journal_desc->Date ) ) . " : " . utf8_encode( $journal_desc->Text ); ?></span>
<?php
		if( $last_modification ){
			$last_modification = FALSE;
			if( $journal_desc->CanBacktrack && $personnage->est_vivant ){
?>
						<button type="submit" name="perso_rollback_id" value="<?php echo $journal_desc->Id; ?>">Annuler</button>
<?php
			}
		}
?>
					</li>
<?php
	}
?>
				</ul>
			</form>
<?php
	if( $personnage->est_vivant ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=kill" onsubmit="return confirm('Voulez-vous vraiment désactiver ce personnage ?\n\nUne fois désactivé, il ne sera plus possible de reprendre ce personnage et vous devrez en commencer un nouveau.');" id="perso_kill">
				<h3>Désactivation</h3>
				<p>En désactivant ce personnage, vous aurez la possibilité de vous en créer un nouveau.</p>
				<button type="submit" name="perso_kill" value="1">Désactiver ce personnage</button>
			</form>
<?php
	} else {
		if( $can_destroy ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=delete" onsubmit="return confirm('Voulez-vous vraiment supprimer ce personnage ?');" id="perso_delete">
				<button type="submit" name="perso_delete">Supprimer ce personnage</button>
			</form>
<?php
		}
	}
	if( $can_rebuild ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=rebuild" onsubmit="return confirm('Voulez-vous vraiment offrir un rebuild à ce personnage ?');" id="perso_rebuild">
				<button type="submit" name="perso_rebuild">Rebuild complet (<?=$personnage->px_totaux; ?> XP)</button>
			</form>
<?php
	}
	if( $can_rebuild_perte ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=rebuild_perte" onsubmit="return confirm('Voulez-vous vraiment offrir un rebuild à perte à ce personnage ?');" id="perso_rebuild_perte">
				<button type="submit" name="perso_rebuild_perte">Rebuild à perte (<?=$personnage->GetPerteXP(); ?> XP)</button>
			</form>
<?php
	}
?>
		</div>