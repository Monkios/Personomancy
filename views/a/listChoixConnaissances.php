		<h2>Liste des choix de connaissances</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $choixConnaissances ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de connaissance trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $choixConnaissances as $choixConnaissance ){
?>
			<tr>
				<td><?php echo utf8_encode( $choixConnaissance->nom ); ?></td>
				<td><?php echo ( $choixConnaissance->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateChoixConnaissance&i=<?php echo $choixConnaissance->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listChoixConnaissances">
			<h3>Ajouter un choix de connaissance</h3>
			<div>
				<label for="choix_connaissance_nom">Nom :</label>
				<input type="text" name="choix_connaissance_nom" id="choix_connaissance_nom" />
			</div>
			<input type="submit" name="add_choix_connaissance" value="Ajouter" />
		</form>