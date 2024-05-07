		<h2>Liste des connaissances</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Voie</th>
				<th>Description</th>
				<th>Type</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_connaissances ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune connaissance trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $list_connaissances as $connaissance ){
?>
			<tr>
				<td><?php echo $connaissance->nom; ?></td>
				<td><?php echo $list_voies[ $connaissance->prereq_voie_primaire ]; ?></td>
				<td><?php echo $connaissance->description; ?></td>
				<td><?php echo $connaissance->GetConnaissanceType(); ?></td>
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
			<div>
				<label for="connaissance_voie">Voie :</label>
				<select name="connaissance_voie" id="connaissance_voie">
					<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_voies as $id => $voie ){
?>
					<option value="<?php echo $id; ?>"><?php echo $voie; ?></option>
<?php
	}
?>
				</select>
			</div>
			<input type="submit" name="add_connaissance" value="Ajouter" />
		</form>