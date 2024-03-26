		<h2>Liste des capacités</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Voie</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_capacites ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune capacité trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $list_capacites as $capacite ){
?>
			<tr>
				<td><?php echo $capacite->nom; ?></td>
				<td><?php echo $list_voies[ $capacite->voie_id ][ "nom" ]; ?></td>
				<td><?php echo ( $capacite->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateCapacite&i=<?php echo $capacite->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listCapacites">
			<h3>Ajouter une capacité</h3>
			<div>
				<label for="capacite_nom">Nom :</label>
				<input type="text" name="capacite_nom" id="capacite_nom" />
			</div>
			<div>
				<label for="capacite_voie">Voie :</label>
				<select name="capacite_voie" id="capacite_voie">
<?php
	foreach( $list_voies as $id => $voie ){
?>
					<option value="<?php echo $id; ?>"><?php echo $voie[ "nom" ]; ?></option>
<?php
	}
?>
				</select>
			</div>
			<input type="submit" name="add_capacite" value="Ajouter" />
		</form>