		<h2>Liste des capacités raciales</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Description</th>
				<th>Race</th>
				<th>Coût</th>
				<th>Bonus</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_capacites_raciales ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune capacité raciale trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $list_capacites_raciales as $capacite_raciale ){
			$bonus = "";
			if( $capacite_raciale->choix_capacite_bonus_id != 0 ){ $bonus .= " + " . $list_choix_capacites[ $capacite_raciale->choix_capacite_bonus_id ]; }
			if( $capacite_raciale->choix_capacite_raciale_bonus_id != 0 ){ $bonus .= " + " . $list_choix_capacites_raciales[ $capacite_raciale->choix_capacite_raciale_bonus_id ]; }
			if( $capacite_raciale->choix_connaissance_bonus_id != 0 ){ $bonus .= " + " . $list_choix_connaissances[ $capacite_raciale->choix_connaissance_bonus_id ]; }
			if( $capacite_raciale->choix_voie_bonus_id != 0 ){ $bonus .= " + " . $list_choix_voies[ $capacite_raciale->choix_voie_bonus_id ]; }
?>
			<tr>
				<td><?php echo $capacite_raciale->nom; ?></td>
				<td><?php echo $capacite_raciale->description; ?></td>
				<td><?php echo $list_races[ $capacite_raciale->race_id ]; ?></td>
				<td><?php echo $capacite_raciale->cout; ?></td>
				<td><?php echo $bonus; ?></td>
				<td><?php echo ( $capacite_raciale->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capacite_raciale->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listCapacitesRaciales">
			<h3>Ajouter une capacité raciale</h3>
			<div>
				<label for="capacite_raciale_nom">Nom :</label>
				<input type="text" name="capacite_raciale_nom" id="capacite_raciale_nom" />
			</div>
			<div>
				<label for="capacite_raciale_race">Race :</label>
				<select name="capacite_raciale_race" id="capacite_raciale_race">
					<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_races as $id => $race ){
?>
					<option value="<?php echo $id; ?>"><?php echo $race; ?></option>
<?php
	}
?>
				</select>
			</div>
			<input type="submit" name="add_capacite_raciale" value="Ajouter" />
		</form>