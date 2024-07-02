		<h2>Liste des choix de capacités</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_choix_capacites ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de capacité trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $list_choix_capacites as $choix_capacites ){
?>
			<tr>
				<td><?php echo $choix_capacites->nom; ?></td>
				<td><?php echo ( $choix_capacites->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=super&a=updateChoixCapacite&i=<?php echo $choix_capacites->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=super&a=listChoixCapacites">
			<h3>Ajouter un choix de capacité</h3>
			<div>
				<label for="choix_capacite_nom">Nom :</label>
				<input type="text" name="choix_capacite_nom" id="choix_capacite_nom" />
			</div>
			<input type="submit" name="add_choix_capacite" value="Ajouter" />
		</form>