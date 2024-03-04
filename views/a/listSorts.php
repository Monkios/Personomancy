		<h2>Liste des sorts</h2>
		<table>
			<tr>
				<th>Nom</th>
				<th>Sphère</th>
				<th>Cercle</th>
				<th>Active</th>
				<th></th>
			</tr>
<?php
	if( count( $sorts ) == 0 ){
?>
			<tr>
				<td colspan="6">Aucun sort trouvé.</td>
			</tr>
<?php
	} else {
		foreach( $sorts as $sort ){
?>
			<tr>
				<td><?php echo utf8_encode( $sort->nom ); ?></td>
				<td><?php echo utf8_encode( $list_spheres[ $sort->sphere_id ] ); ?></td>
				<td><?php echo utf8_encode( $sort->cercle ); ?></td>
				<td><?php echo ( $sort->active ? "Oui" : "Non" ); ?></td>
				<td><a href="?s=admin&a=updateSort&i=<?php echo $sort->id; ?>">Modifier</a></td>
			</tr>
<?php
		}
	}
?>
		</table>
		<form method="post" action="?s=admin&a=listSorts">
			<h3>Ajouter un Sort</h3>
			<div>
				<label for="sort_nom">Nom :</label>
				<input type="text" name="sort_nom" id="sort_nom" />
			</div>
			<div>
				<label for="sort_sphere">Sphère :</label>
				<select name="sort_sphere" id="sort_sphere">
<?php
	foreach( $list_spheres as $id => $nom ){
?>
					<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
	}
?>
				</select>
			</div>
			<div>
				<label for="sort_cercle">Cercle :</label>
				<select name="sort_cercle" id="sort_cercle">
<?php
	for( $cercle = 1; $cercle <= 5; $cercle++ ){
?>
					<option value="<?php echo $cercle; ?>"><?php echo $cercle; ?></option>
<?php
	}
?>
				</select>
			</div>
			<input type="submit" name="add_sort" value="Ajouter" />
		</form>