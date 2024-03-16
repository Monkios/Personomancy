		<h2>Liste des cités-États</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Description</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $list_cites_etats ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucune cité-État trouvée.</td>
			</tr>
<?php
	} else {
		foreach( $list_cites_etats as $cite_etat ){
?>
			<tr>
				<td><?php echo $cite_etat->nom; ?></td>
				<td><?php echo $cite_etat->description; ?></td>
				<td><?php echo ( $cite_etat->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateCiteEtat&i=<?php echo $cite_etat->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listCitesEtats">
			<h3>Ajouter une cité-État</h3>
			<div>
				<label for="cite_etat_nom">Nom :</label>
				<input type="text" name="cite_etat_nom" id="cite_etat_nom" />
			</div>
			<input type="submit" name="add_cite_etat" value="Ajouter" />
		</form>