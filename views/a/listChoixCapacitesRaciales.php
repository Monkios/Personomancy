		<h2>Liste des choix de capacités raciales</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_choix_capacites_raciales ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de capacité raciale trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $list_choix_capacites_raciales as $choix_capacites_raciales ){
?>
			<tr>
				<td><?php echo $choix_capacites_raciales->nom; ?></td>
				<td><?php echo ( $choix_capacites_raciales->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateChoixCapaciteRaciale&i=<?php echo $choix_capacites_raciales->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listChoixCapacitesRaciales">
			<h3>Ajouter un choix de capacité raciale</h3>
			<div>
				<label for="choix_capacite_raciale_nom">Nom :</label>
				<input type="text" name="choix_capacite_raciale_nom" id="choix_capacite_raciale_nom" />
			</div>
			<input type="submit" name="add_choix_capacite_raciale" value="Ajouter" />
		</form>