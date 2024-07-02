		<h2>Liste des voies</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Description</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_voies ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune voie trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $list_voies as $voie ){
?>
			<tr>
				<td><?php echo $voie->nom; ?></td>
				<td><?php echo $voie->description; ?></td>
				<td><?php echo ( $voie->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=super&a=updateVoie&i=<?php echo $voie->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=super&a=listVoies">
			<h3>Ajouter une voie</h3>
			<div>
				<label for="voie_nom">Nom :</label>
				<input type="text" name="voie_nom" id="voie_nom" />
			</div>
			<input type="submit" name="add_voie" value="Ajouter" />
		</form>