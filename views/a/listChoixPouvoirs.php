		<h2>Liste des choix de pouvoirs</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $choixPouvoirs ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun choix de pouvoir trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $choixPouvoirs as $choixPouvoir ){
?>
			<tr>
				<td><?php echo utf8_encode( $choixPouvoir->nom ); ?></td>
				<td><?php echo ( $choixPouvoir->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateChoixPouvoir&i=<?php echo $choixPouvoir->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listChoixPouvoirs">
			<h3>Ajouter un choix de pouvoir</h3>
			<div>
				<label for="choix_pouvoir_nom">Nom :</label>
				<input type="text" name="choix_pouvoir_nom" id="choix_pouvoir_nom" />
			</div>
			<input type="submit" name="add_choix_pouvoir" value="Ajouter" />
		</form>