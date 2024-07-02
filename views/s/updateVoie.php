		<h2>Modification d'une voie</h2>
		<div>
			<form method="post" action="?s=super&a=updateVoie&i=<?php echo $voie->id; ?>">
				<div>
					<label for="voie_nom">Nom :</label>
					<input type="text" name="voie_nom" id="voie_nom" value="<?php echo $voie->nom; ?>" />
				</div>
				<div>
					<label for="voie_description">Description :</label>
					<input type="text" name="voie_description" id="voie_description" value="<?php echo $voie->description; ?>" />
				</div>
				<div>
					<label for="voie_active">Est activée</label>
					<input type="checkbox" name="voie_active" id="voie_active"<?php if( $voie->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<input type="hidden" name="voie_id" value="<?php echo $voie->id; ?>" />
					<input type="submit" name="save_voie" value="Enregistrer" />
				</div>
			</form>
		</div>