		<h2>Modification d'une cité-État</h2>
		<div>
			<form method="post" action="?s=admin&a=updateCiteEtat&i=<?php echo $cite_etat->id; ?>">
				<div>
					<label for="cite_etat_nom">Nom :</label>
					<input type="text" name="cite_etat_nom" id="cite_etat_nom" value="<?php echo $cite_etat->nom; ?>" />
				</div>
				<div>
					<label for="cite_etat_description">Description :</label>
					<input type="text" name="cite_etat_description" id="cite_etat_description" value="<?php echo $cite_etat->description; ?>" />
				</div>
				<div>
					<label for="cite_etat_active">Est activée</label>
					<input type="checkbox" name="cite_etat_active" id="cite_etat_active"<?php if( $cite_etat->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<input type="hidden" name="cite_etat_id" value="<?php echo $cite_etat->id; ?>" />
					<input type="submit" name="save_cite_etat" value="Enregistrer" />
				</div>
			</form>
		</div>