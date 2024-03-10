		<h2>Liste des connaissances</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $connaissances ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune connaissance trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $connaissances as $connaissance ){
?>
			<tr>
				<td><?php echo $connaissance->nom; ?></td>
				<td><?php echo ( $connaissance->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateConnaissance&i=<?php echo $connaissance->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listConnaissances">
			<h3>Ajouter une connaissance</h3>
			<div>
				<label for="connaissance_nom">Nom :</label>
				<input type="text" name="connaissance_nom" id="connaissance_nom" />
			</div>
			<input type="submit" name="add_connaissance" value="Ajouter" />
		</form>