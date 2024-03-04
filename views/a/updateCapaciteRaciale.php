		<h2>Modification d'une capacité raciale</h2>
		<div>
			<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
				<div>
					<label for="capacite_raciale_nom">Nom :</label>
					<input type="text" name="capacite_raciale_nom" id="capacite_raciale_nom" value="<?php echo utf8_encode( $capaciteRaciale->nom ); ?>" />
				</div>
				<div>
					<label for="capacite_raciale_active">Est activée</label>
					<input type="checkbox" name="capacite_raciale_active" id="capacite_raciale_active"<?php if( $capaciteRaciale->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<label for="capacite_raciale_affiche">Affichée sur la fiche</label>
					<input type="checkbox" name="capacite_raciale_affiche" id="capacite_raciale_affiche"<?php if( $capaciteRaciale->affiche == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Bonus aux attributs</h3>
					<div>
						<label for="capacite_raciale_bonus_alerte">Alerte :</label>
						<select name="capacite_raciale_bonus_alerte" id="capacite_raciale_bonus_alerte">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $capaciteRaciale->bonus_alerte == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="capacite_raciale_bonus_constitution">Consitution :</label>
						<select name="capacite_raciale_bonus_constitution" id="capacite_raciale_bonus_constitution">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $capaciteRaciale->bonus_constitution == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="capacite_raciale_bonus_intelligence">Intelligence :</label>
						<select name="capacite_raciale_bonus_intelligence" id="capacite_raciale_bonus_intelligence">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $capaciteRaciale->bonus_intelligence == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="capacite_raciale_bonus_spiritisme">Spiritisme :</label>
						<select name="capacite_raciale_bonus_spiritisme" id="capacite_raciale_bonus_spiritisme">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $capaciteRaciale->bonus_spiritisme == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="capacite_raciale_bonus_vigueur">Vigueur :</label>
						<select name="capacite_raciale_bonus_vigueur" id="capacite_raciale_bonus_vigueur">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $capaciteRaciale->bonus_vigueur == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="capacite_raciale_bonus_volonte">Volonté :</label>
						<select name="capacite_raciale_bonus_volonte" id="capacite_raciale_bonus_volonte">
<?php
	for( $i = 0; $i <= 5; $i++ ){
?>
							<option value="<?php echo $i; ?>"<?php if( $capaciteRaciale->bonus_volonte == $i ) echo "selected='selected'"; ?>><?php echo $i; ?></option>
<?php
	}
?>
						</select>
					</div>
				</div>
				<div>
					<h3>Capacités bonus</h3>
					<ul>
<?php
	foreach( $capaciteRaciale->list_bonus_capacite as $id => $capacite ){
?>
						<li>
							<?php echo utf8_encode( $capacite->nom ); ?>
							<button type="submit" name="delete_capacite" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette capacité de cette capacité raciale ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<h3>Connaissances bonus</h3>
					<ul>
<?php
	foreach( $capaciteRaciale->list_bonus_connaissance as $id => $connaissance ){
?>
						<li>
							<?php echo utf8_encode( $connaissance->nom ); ?>
							<button type="submit" name="delete_connaissance" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette connaissance de cette capacité raciale ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<h3>Voies bonus</h3>
					<ul>
<?php
	foreach( $capaciteRaciale->list_bonus_voie as $id => $voie ){
?>
						<li>
							<?php echo utf8_encode( $voie->nom ); ?>
							<button type="submit" name="delete_voie" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette voie de cette capacité raciale ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<h3>Choix de capacité</h3>
					<ul>
<?php
	foreach( $capaciteRaciale->list_choix_capacite as $id => $capacite ){
?>
						<li>
							<?php echo utf8_encode( $capacite->nom ); ?>
							<button type="submit" name="delete_choix_capacite" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer ce choix de capacité de cette capacité raciale ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<h3>Choix de connaissance</h3>
					<ul>
<?php
	foreach( $capaciteRaciale->list_choix_connaissance as $id => $connaissance ){
?>
						<li>
							<?php echo utf8_encode( $connaissance->nom ); ?>
							<button type="submit" name="delete_choix_connaissance" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer ce choix de connaissance de cette capacité raciale ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<h3>Choix de pouvoir</h3>
					<ul>
<?php
	foreach( $capaciteRaciale->list_choix_pouvoir as $id => $pouvoir ){
?>
						<li>
							<?php echo utf8_encode( $pouvoir->nom ); ?>
							<button type="submit" name="delete_choix_pouvoir" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer ce choix de pouvoir de cette capacité raciale ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="save_capacite_raciale" value="Enregistrer" />
				</div>
			</form>
			<hr />
			<div>
				<h3>Ajouter une capacité</h3>
				<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
					<div>
						<label for="capacite">Capacité :</label>
						<select name="capacite" id="capacite">
<?php
	foreach( $list_capacites as $id => $nom ){
		if( !array_key_exists( $id, $capaciteRaciale->list_bonus_capacite ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="add_capacite" value="Ajouter" />
				</form>
			</div>
			<div>
				<h3>Ajouter une connaissance</h3>
				<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
					<div>
						<label for="connaissance">Connaissance :</label>
						<select name="connaissance" id="connaissance">
<?php
	foreach( $list_connaissances as $id => $nom ){
		if( !array_key_exists( $id, $capaciteRaciale->list_bonus_connaissance ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="add_connaissance" value="Ajouter" />
				</form>
			</div>
			<div>
				<h3>Ajouter une voie</h3>
				<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
					<div>
						<label for="voie">Voie :</label>
						<select name="voie" id="voie">
<?php
	foreach( $list_voies as $id => $nom ){
		if( !array_key_exists( $id, $capaciteRaciale->list_bonus_voie ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="add_voie" value="Ajouter" />
				</form>
			</div>
			<hr />
			<div>
				<h3>Ajouter un choix de capacité</h3>
				<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
					<div>
						<label for="choix_capacite">Capacité :</label>
						<select name="choix_capacite" id="choix_capacite">
<?php
	foreach( $list_choix_capacites as $id => $nom ){
		if( !array_key_exists( $id, $capaciteRaciale->list_choix_capacite ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="add_choix_capacite" value="Ajouter" />
				</form>
			</div>
			<div>
				<h3>Ajouter un choix de connaissance</h3>
				<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
					<div>
						<label for="choix_connaissance">Connaissance :</label>
						<select name="choix_connaissance" id="choix_connaissance">
<?php
	foreach( $list_choix_connaissances as $id => $nom ){
		if( !array_key_exists( $id, $capaciteRaciale->list_choix_connaissance ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="add_choix_connaissance" value="Ajouter" />
				</form>
			</div>
			<div>
				<h3>Ajouter un choix de pouvoir</h3>
				<form method="post" action="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">
					<div>
						<label for="choix_pouvoir">Connaissance :</label>
						<select name="choix_pouvoir" id="choix_pouvoir">
<?php
	foreach( $list_choix_pouvoirs as $id => $nom ){
		if( !array_key_exists( $id, $capaciteRaciale->list_choix_pouvoir ) ){
?>
							<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="capacite_raciale_id" value="<?php echo $capaciteRaciale->id; ?>" />
					<input type="submit" name="add_choix_pouvoir" value="Ajouter" />
				</form>
			</div>
		</div>