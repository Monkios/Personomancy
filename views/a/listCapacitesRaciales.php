		<h2>Liste des capacités raciales</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $capacitesRaciales ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune capacité raciale trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $capacitesRaciales as $capaciteRaciale ){
?>
			<tr>
				<td><?php echo utf8_encode( $capaciteRaciale->nom ); ?></td>
				<td><?php echo ( $capaciteRaciale->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateCapaciteRaciale&i=<?php echo $capaciteRaciale->id; ?>">Modifier</a></td>
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
			<input type="submit" name="add_capacite_raciale" value="Ajouter" />
		</form>