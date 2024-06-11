		<h2>Modification d'une capacité raciale</h2>
		<div>
			<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capacite_raciale->id; ?>">
				<div>
					<label for="capacite_raciale_nom">Nom :</label>
					<input type="text" name="capacite_raciale_nom" id="capacite_raciale_nom" value="<?php echo $capacite_raciale->nom; ?>" />
				</div>
				<div>
					<label for="capacite_raciale_description">Description :</label>
					<input type="text" name="capacite_raciale_description" id="capacite_raciale_description" value="<?php echo $capacite_raciale->description; ?>" />
				</div>
				<div>
					<label for="capacite_raciale_active">Est activée</label>
					<input type="checkbox" name="capacite_raciale_active" id="capacite_raciale_active"<?php if( $capacite_raciale->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<label for="capacite_raciale_cout">Coût :</label>
					<input type="number" min="0" max="4" name="capacite_raciale_cout" id="capacite_raciale_cout" value="<?php echo $capacite_raciale->cout; ?>" />
				</div>
				<div>
					<label for="capacite_raciale_race_id">Race :</label>
					<select name="capacite_raciale_race_id" id="capacite_raciale_race_id">
						<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_races as $id => $race ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capacite_raciale->race_id ) echo " selected='selected'"; ?>><?php echo $race; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<label for="capacite_raciale_bonus_capacite">Capacité bonus :</label>
					<select name="capacite_raciale_bonus_capacite" id="capacite_raciale_bonus_capacite">
						<option value="">n.a.</option>
<?php
	foreach( $list_choix_capacites as $id => $capacite_bonus ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capacite_raciale->choix_capacite_bonus_id ) echo " selected='selected'"; ?>><?php echo $capacite_bonus; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<label for="capacite_raciale_bonus_connaissance">Connaissance bonus :</label>
					<select name="capacite_raciale_bonus_connaissance" id="capacite_raciale_bonus_connaissance">
						<option value="">n.a.</option>
<?php
	foreach( $list_choix_connaissances as $id => $connaissance_bonus ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capacite_raciale->choix_connaissance_bonus_id ) echo " selected='selected'"; ?>><?php echo $connaissance_bonus; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<label for="capacite_raciale_bonus_capacite_raciale">Capacité raciale bonus :</label>
					<select name="capacite_raciale_bonus_capacite_raciale" id="capacite_raciale_bonus_capacite_raciale">
						<option value="">n.a.</option>
<?php
	foreach( $list_choix_capacites_raciales as $id => $capacite_raciale_bonus ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capacite_raciale->choix_capacite_raciale_bonus_id ) echo " selected='selected'"; ?>><?php echo $capacite_raciale_bonus; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<label for="capacite_raciale_bonus_voie">Voie bonus :</label>
					<select name="capacite_raciale_bonus_voie" id="capacite_raciale_bonus_voie">
						<option value="">n.a.</option>
<?php
	foreach( $list_choix_voies as $id => $voie_bonus ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capacite_raciale->choix_voie_bonus_id ) echo " selected='selected'"; ?>><?php echo $voie_bonus; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capacite_raciale->id; ?>" />
					<input type="submit" name="save_capacite_raciale" value="Enregistrer" />
				</div>
			</form>
		</div>