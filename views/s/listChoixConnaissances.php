		<h2>Liste des choix de connaissances</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_choix_connaissances ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de connaissance trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $list_choix_connaissances as $choix_connaissances ){
?>
			<tr>
				<td><?php echo $choix_connaissances->nom; ?></td>
				<td><?php echo ( $choix_connaissances->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=super&a=updateChoixConnaissance&i=<?php echo $choix_connaissances->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=super&a=listChoixConnaissances">
			<h3>Ajouter un choix de connaissance</h3>
			<div>
				<label for="choix_connaissance_nom">Nom :</label>
				<input type="text" name="choix_connaissance_nom" id="choix_connaissance_nom" />
			</div>
			<input type="submit" name="add_choix_connaissance" value="Ajouter" />
		</form>