		<h2>Modification d'une race</h2>
		<div>
			<form method="post" action="?s=admin&a=updateRace&i=<?php echo $race->id; ?>">
				<div>
					<label for="race_nom">Nom :</label>
					<input type="text" name="race_nom" id="race_nom" value="<?php echo $race->nom; ?>" />
				</div>
				<div>
					<label for="race_active">Est activée</label>
					<input type="checkbox" name="race_active" id="race_active"<?php if( $race->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Attributs de base</h3>
					<div>
						<label for="race_alerte">Alerte :</label>
						<select name="race_alerte" id="race_alerte">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $race->base_alerte == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="race_constitution">Consitution :</label>
						<select name="race_constitution" id="race_constitution">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $race->base_constitution == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="race_intelligence">Intelligence :</label>
						<select name="race_intelligence" id="race_intelligence">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $race->base_intelligence == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="race_spiritisme">Spiritisme :</label>
						<select name="race_spiritisme" id="race_spiritisme">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $race->base_spiritisme == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="race_vigueur">Vigueur :</label>
						<select name="race_vigueur" id="race_vigueur">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $race->base_vigueur == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="race_volonte">Volonté :</label>
						<select name="race_volonte" id="race_volonte">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $race->base_volonte == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<h3>Capacités raciales</h3>
					<ul>
<?php
	foreach( $race->list_capacites_raciales as $id => $capacite ){
?>
						<li>
							<label for="capacite_raciale_<?php echo $id; ?>"><?php echo $capacite[ 0 ]; ?></label>
							<select name="capacite_raciale[<?php echo $id; ?>]" id="capacite_raciale_<?php echo $id; ?>">
<?php
		for( $i = 0; $i <= 5; $i++ ){
?>
								<option value="<?php echo $i; ?>"<?php if( $capacite[ 1 ] == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
		}
?>
							</select>
							<button type="submit" name="delete_capacite_raciale" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette capacité raciales de cette race ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="race_id" value="<?php echo $race->id; ?>" />
					<input type="submit" name="save_race" value="Enregistrer" />
				</div>
			</form>
			<hr />
			<div>
				<h3>Ajouter une capacité raciale</h3>
				<form method="post" action="?s=admin&a=updateRace&i=<?php echo $race->id; ?>">
					<div>
						<label for="capacite_raciale_pouvoir">Pouvoir :</label>
						<select name="capacite_raciale_pouvoir" id="capacite_raciale_pouvoir">
<?php
	foreach( $pouvoirs as $id => $nom ){
		if( !array_key_exists( $id, $race->list_capacites_raciales ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo $nom; ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<div>
						<label for="capacite_raciale_cout">Coût :</label>
						<select name="capacite_raciale_cout" id="capacite_raciale_cout">
<?php
	for( $i = 0; $i < 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<input type="hidden" name="race_id" value="<?php echo $race->id; ?>" />
					<input type="submit" name="add_capacite_raciale" value="Ajouter" />
				</form>
			</div>
		</div>