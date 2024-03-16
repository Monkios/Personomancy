<?php
	include "./views/view_helpers.php";
?>
		<h2>Fiche de personnage</h2>
		<div id="fiche_perso">
			<div class="fiche_regroupement fiche_regroupement_base">
				<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=bases">
					<div class="fiche_element">
						<label for="perso_joueur">Nom du joueur :</label>
						<span id="perso_joueur" class="as_input"><?php echo $personnage->joueur_nom; ?></span>
					</div>
					<div class="fiche_element">
						<label for="perso_nom">Nom du personnage :</label>
<?php
	if( $can_change_bases && $personnage->est_vivant ){
?>
						<input type="text" name="perso_nom" id="perso_nom" value="<?php echo $perso_nom; ?>" />
<?php
	} else {
?>
						<span id="perso_nom" class="as_input"><?php echo $personnage->nom; ?></span>
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
							<option value="<?php echo $alignement_id; ?>"<?php echo ( $alignement_id == $perso_alignement ) ? " selected='selected'" : ""; ?>><?php echo $alignement_nom; ?></option>
<?php
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_alignement" class="as_input"><?php echo $personnage->alignement_nom; ?></span>
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
							<option value="<?php echo $religion_id; ?>"<?php echo ( $religion_id == $perso_religion ) ? " selected='selected'" : ""; ?>><?php echo $religion_infos[ "nom" ]; ?></option>
<?php
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_religion" class="as_input"><?php echo $personnage->religion_nom; ?></span>
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
							<option value="<?php echo $race_id; ?>"<?php echo ( $race_id == $perso_race ) ? " selected='selected'" : ""; ?>><?php echo $race_nom; ?></option>
<?php
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_race" class="as_input"><?php echo $personnage->race_nom; ?></span>
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
							<option value="<?php echo $faction_id; ?>"<?php echo ( $faction_id == $perso_faction ) ? " selected='selected'" : ""; ?>><?php echo $faction_nom; ?></option>
<?php
			}
		}
?>
						</select>
<?php
	} else {
?>
						<span id="perso_faction" class="as_input"><?php echo $personnage->faction_nom; ?></span>
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
							<option value="<?php echo $cr_id; ?>"><?php echo $cr_desc; ?></option>
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
						<label for="perso_choix_capacites_<?php echo $choix_id; ?>">Choix de capacités "<?php echo $list_choix_capacites[ $choix_id ]; ?>" (x<?php echo $choix_nombre; ?>) :</label>
						<select name="perso_choix_capacites[<?php echo $choix_id; ?>]" id="perso_choix_capacites_<?php echo $choix_id; ?>" onchange="this.form.submit();">
							<option value="">-- Choix nécessaire --</option>
<?php
				foreach( $list_choix_capacites_capacites[ $choix_id ] as $capacite_id => $capacite_infos ){
					if( $capacite_infos->active && in_array( $capacite_infos->voie_id, $personnage->voies ) ){
?>
							<option value="<?php echo $capacite_id; ?>"<?php echo ( $personnage->pc_raciales > 0 ) ? " disabled='disabled'" : ""; ?>><?php echo $capacite_infos->nom; ?></option>
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
			<div class="fiche_regroupement fiche_regroupement_cr">
				<h3>Capacités raciales</h3>
<?php
	$nb_cr = 0;
	foreach( $personnage->capacites_raciales as $cr_id => $cr_desc ){
?>
				<div class="fiche_element">
					<label for="perso_cr_<?php echo $cr_id; ?>">CR <?php echo ++$nb_cr; ?> :</label>
					<span id="perso_cr_<?php echo $cr_id; ?>" class="as_input"><?php echo $cr_desc; ?></span>
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
							<span><?php echo $voie_desc; ?></span>
							<input type="checkbox" name="perso_voie" value="<?php echo $voie_id; ?>"<?php echo $voie_status; ?> />
						</h3>
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
			AddSelectionsPips( $capacite_desc, "perso_capacite[" . $capacite_id . "]", $nb_selections, $px_restants );
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
						<label for="perso_connaissance_<?php echo $connaissance_id; ?>" title="<?php echo htmlentities( $list_connaissances[ $connaissance_id ] ); ?>"><?php echo $list_connaissances[ $connaissance_id ]; ?></label>
						<input type="checkbox" name="perso_connaissance" id="perso_connaissance_<?php echo $connaissance_id; ?>" value="<?php echo $connaissance_id; ?>"<?php echo $connaissance_status; ?> />
					</div>
<?php
	}
?>
				</form>
			</div>
		</div>
		<div id="perso_options">
			<h3>Gestion du personnage</h3>
			<h4>Commentaires de l'animation</h4>
<?php
	if( $can_add_comment ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=comment">
				<textarea name="perso_comment"><?php echo $personnage->commentaire; ?></textarea>
				<input type="submit" name="perso_save_comment" value="Enregistrer le commentaire" />
			</form>
<?php
	} else {
?>
			<textarea readonly="readonly"><?php echo $personnage->commentaire; ?></textarea>
<?php
	}
?>
			<h4>Notes</h4>
<?php
	if( $can_add_notes ){
?>
			<form method="post" action="?s=player&a=characterUpdate&c=<?php echo $personnage->id; ?>&st=notes">
				<textarea name="perso_notes"><?php echo $personnage->notes; ?></textarea>
				<input type="submit" name="perso_save_notes" value="Enregistrer les notes" />
			</form>
<?php
	} else {
?>
			<textarea readonly="readonly"><?php echo $personnage->notes; ?></textarea>
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
						<span><?php echo Date::FormatSQLDate( $journal_desc->Date ) . " : " . $journal_desc->Text; ?></span>
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