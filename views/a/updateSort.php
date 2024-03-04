		<h2>Modification d'un sort</h2>
		<div>
			<form method="post" action="?s=admin&a=updateSort&i=<?php echo $sort->id; ?>">
				<div>
					<label for="sort_nom">Nom :</label>
					<input type="text" name="sort_nom" id="sort_nom" value="<?php echo utf8_encode( $sort->nom ); ?>" />
				</div>
				<div>
					<label for="sort_active">Est activée</label>
					<input type="checkbox" name="sort_active" id="sort_active"<?php if( $sort->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<label for="sort_sphere">Sphère :</label>
					<select name="sort_sphere" id="sort_sphere">
<?php
	foreach( $list_spheres as $id => $nom ){
?>
						<option value="<?php echo $id; ?>"<?php if( $id == $sort->sphere_id ){ echo " selected='selected'"; } ?>><?php echo utf8_encode( $nom ); ?></option>
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
						<option value="<?php echo $cercle; ?>"<?php if( $cercle == $sort->cercle ){ echo " selected='selected'"; } ?>><?php echo $cercle; ?></option>
<?php
	}
?>
					</select>
				</div>
				<div>
					<input type="hidden" name="sort_id" value="<?php echo $sort->id; ?>" />
					<input type="submit" name="save_sort" value="Enregistrer" />
				</div>
			</form>
		</div>