		<h2>Liste des choix de voies</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_choix_voies ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de voie trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $list_choix_voies as $choix_voies ){
?>
			<tr>
				<td><?php echo $choix_voies->nom; ?></td>
				<td><?php echo ( $choix_voies->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateChoixVoie&i=<?php echo $choix_voies->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listChoixVoies">
			<h3>Ajouter un choix de voie</h3>
			<div>
				<label for="choix_voie_nom">Nom :</label>
				<input type="text" name="choix_voie_nom" id="choix_voie_nom" />
			</div>
			<input type="submit" name="add_choix_voie" value="Ajouter" />
		</form>