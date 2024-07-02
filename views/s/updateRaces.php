		<h2>Modification d'une race</h2>
		<div>
			<form method="post" action="?s=super&a=updateRace&i=<?php echo $race->id; ?>">
				<div>
					<label for="race_nom">Nom :</label>
					<input type="text" name="race_nom" id="race_nom" value="<?php echo $race->nom; ?>" />
				</div>
				<div>
					<label for="race_description">Description :</label>
					<input type="text" name="race_description" id="race_description" value="<?php echo $race->description; ?>" />
				</div>
				<div>
					<label for="race_active">Est activée</label>
					<input type="checkbox" name="race_active" id="race_active"<?php if( $race->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<input type="hidden" name="race_id" value="<?php echo $race->id; ?>" />
					<input type="submit" name="save_race" value="Enregistrer" />
				</div>
			</form>
		</div>