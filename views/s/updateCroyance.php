		<h2>Modification d'une croyance</h2>
		<div>
			<form method="post" action="?s=super&a=updateCroyance&i=<?php echo $croyance->id; ?>">
				<div>
					<label for="croyance_nom">Nom :</label>
					<input type="text" name="croyance_nom" id="croyance_nom" value="<?php echo $croyance->nom; ?>" />
				</div>
				<div>
					<label for="croyance_description">Description :</label>
					<input type="text" name="croyance_description" id="croyance_description" value="<?php echo $croyance->description; ?>" />
				</div>
				<div>
					<label for="croyance_active">Est activée</label>
					<input type="checkbox" name="croyance_active" id="croyance_active"<?php if( $croyance->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<input type="hidden" name="croyance_id" value="<?php echo $croyance->id; ?>" />
					<input type="submit" name="save_croyance" value="Enregistrer" />
				</div>
			</form>
		</div>