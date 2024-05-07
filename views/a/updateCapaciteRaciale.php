		<h2>Modification d'une capacité raciale</h2>
		<div>
			<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
				<div>
					<label for="capacite_raciale_nom">Nom :</label>
					<input type="text" name="capacite_raciale_nom" id="capacite_raciale_nom" value="<?php echo $capaciteRaciale->nom; ?>" />
				</div>
				<div>
					<label for="capacite_raciale_description">Description :</label>
					<input type="text" name="capacite_raciale_description" id="capacite_raciale_description" value="<?php echo $capaciteRaciale->description; ?>" />
				</div>
				<div>
					<label for="capacite_raciale_active">Est activée</label>
					<input type="checkbox" name="capacite_raciale_active" id="capacite_raciale_active"<?php if( $capaciteRaciale->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<label for="capacite_raciale_cout">Coût :</label>
					<input type="number" min="0" max="4" name="capacite_raciale_cout" id="capacite_raciale_cout" value="<?php echo $capaciteRaciale->cout; ?>" />
				</div>
				<div>
					<label for="capacite_raciale_race_id">Race :</label>
					<select name="capacite_raciale_race_id" id="capacite_raciale_race_id">
						<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_races as $id => $race ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capaciteRaciale->race_id ) echo " selected='selected'"; ?>><?php echo $race; ?></option>
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
	foreach( $list_choix_capacites as $id => $capacite ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capaciteRaciale->choix_capacite_bonus_id ) echo " selected='selected'"; ?>><?php echo $capacite; ?></option>
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
	foreach( $list_choix_connaissances as $id => $connaissance ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capaciteRaciale->choix_connaissance_bonus_id ) echo " selected='selected'"; ?>><?php echo $connaissance; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<label for="capacite_raciale_bonus_pouvoir">Pouvoir bonus :</label>
					<select name="capacite_raciale_bonus_pouvoir" id="capacite_raciale_bonus_pouvoir">
						<option value="">n.a.</option>
<?php
	foreach( $list_choix_pouvoirs as $id => $pouvoir ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capaciteRaciale->choix_pouvoir_bonus_id ) echo " selected='selected'"; ?>><?php echo $pouvoir; ?></option>
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
	foreach( $list_choix_voies as $id => $voie ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $capaciteRaciale->choix_voie_bonus_id ) echo " selected='selected'"; ?>><?php echo $voie; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="save_capacite_raciale" value="Enregistrer" />
				</div>
			</form>
		</div>