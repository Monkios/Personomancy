		<h2>Liste des choix de capacités</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $choixCapacites ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de capacité trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $choixCapacites as $choixCapacite ){
?>
			<tr>
				<td><?php echo $choixCapacite->nom; ?></td>
				<td><?php echo ( $choixCapacite->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateChoixCapacite&i=<?php echo $choixCapacite->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listChoixCapacites">
			<h3>Ajouter un choix de capacité</h3>
			<div>
				<label for="choix_capacite_nom">Nom :</label>
				<input type="text" name="choix_capacite_nom" id="choix_capacite_nom" />
			</div>
			<input type="submit" name="add_choix_capacite" value="Ajouter" />
		</form>