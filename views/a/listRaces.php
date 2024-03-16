		<h2>Liste des races</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $races ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune race trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $races as $race ){
?>
			<tr>
				<td><?php echo $race->nom; ?></td>
				<td><?php echo ( $race->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateRace&i=<?php echo $race->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listRaces">
			<h3>Ajouter une race</h3>
			<div>
				<label for="race_nom">Nom :</label>
				<input type="text" name="race_nom" id="race_nom" />
			</div>
			<input type="submit" name="add_race" value="Ajouter" />
		</form>