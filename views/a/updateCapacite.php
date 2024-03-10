		<h2>Modification d'une capacité</h2>
		<div>
			<form method="post" action="?s=admin&a=updateCapacite&i=<?php echo $capacite->id; ?>">
				<div>
					<label for="capacite_nom">Nom :</label>
					<input type="text" name="capacite_nom" id="capacite_nom" value="<?php echo $capacite->nom; ?>" />
				</div>
				<div>
					<label for="capacite_active">Est activée</label>
					<input type="checkbox" name="capacite_active" id="capacite_active"<?php if( $capacite->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<label for="capacite_voie">Voie :</label>
					<select name="capacite_voie" id="capacite_voie">
<?php
	foreach( $list_voies as $id => $nom ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capacite->voie_id ) echo " selected='selected'"; ?>><?php echo $nom; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<input type="hidden" name="capacite_id" value="<?php echo $capacite->id; ?>" />
					<input type="submit" name="save_capacite" value="Enregistrer" />
				</div>
			</form>
		</div>