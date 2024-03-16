		<h2>Liste des croyances</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Description</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_croyances ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune croyance trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $list_croyances as $croyance ){
?>
			<tr>
				<td><?php echo $croyance->nom; ?></td>
				<td><?php echo $croyance->description; ?></td>
				<td><?php echo ( $croyance->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateCroyance&i=<?php echo $croyance->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listCroyances">
			<h3>Ajouter une croyance</h3>
			<div>
				<label for="croyance_nom">Nom :</label>
				<input type="text" name="croyance_nom" id="croyance_nom" />
			</div>
			<input type="submit" name="add_croyance" value="Ajouter" />
		</form>