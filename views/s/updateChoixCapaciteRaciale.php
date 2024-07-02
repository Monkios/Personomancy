		<h2>Modification d'un choix de capacité raciale</h2>
		<a href="?s=super&a=listChoixCapacitesRaciales">Retourner à la liste</a>
		<div>
			<form method="post" action="?s=super&a=updateChoixCapaciteRaciale&i=<?php echo $choixCapaciteRaciale->id; ?>">
				<div>
					<label for="choix_capacite_raciale_nom">Nom :</label>
					<input type="text" name="choix_capacite_raciale_nom" id="choix_capacite_raciale_nom" value="<?php echo $choixCapaciteRaciale->nom; ?>" />
				</div>
				<div>
					<label for="choix_capacite_raciale_active">Est activée</label>
					<input type="checkbox" name="choix_capacite_raciale_active" id="choix_capacite_raciale_active"<?php if( $choixCapaciteRaciale->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>CapacitesRaciales associées</h3>
					<ul>
<?php
	foreach( $capacites_raciales as $id => $capacite_raciale ){
?>
						<li>
							<?php echo $capacite_raciale->nom; ?>
							<button type="submit" name="delete_capacite_raciale" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette capacité raciale bonus de cette liste ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="choix_capacite_raciale_id" value="<?php echo $choixCapaciteRaciale->id; ?>" />
					<input type="submit" name="save_choix_capacite_raciale" value="Enregistrer" />
				</div>
			</form>
			<div>
				<h3>Ajouter une capacité raciale</h3>
				<form method="post" action="?s=super&a=updateChoixCapaciteRaciale&i=<?php echo $choixCapaciteRaciale->id; ?>">
				<div>
					<label for="capacite_raciale">Capacité raciale :</label>
					<select name="capacite_raciale" id="capacite_raciale">
						<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_capacites_raciales as $id => $nom ){
		if( !array_key_exists( $id, $capacites_raciales ) ){
?>
						<option value="<?php echo $id; ?>"><?php echo $nom; ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="choix_capacite_raciale_id" value="<?php echo $choixCapaciteRaciale->id; ?>" />
					<input type="submit" name="add_capacite_raciale" value="Ajouter" />
				</form>
			</div>
		</div>