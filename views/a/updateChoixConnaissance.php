		<h2>Modification d'un choix de connaissance</h2>
		<div>
			<form method="post" action="?s=admin&a=updateChoixConnaissance&i=<?php echo $choixConnaissance->id; ?>">
				<div>
					<label for="choix_connaissance_nom">Nom :</label>
					<input type="text" name="choix_connaissance_nom" id="choix_connaissance_nom" value="<?php echo utf8_encode( $choixConnaissance->nom ); ?>" />
				</div>
				<div>
					<label for="choix_connaissance_active">Est activée</label>
					<input type="checkbox" name="choix_connaissance_active" id="choix_connaissance_active"<?php if( $choixConnaissance->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Connaissances associées</h3>
					<ul>
<?php
	foreach( $connaissances as $id => $connaissance ){
?>
						<li>
							<?php echo utf8_encode( $connaissance->nom ); ?>
							<button type="submit" name="delete_connaissance" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette connaissance de ce choix de connaissances ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="choix_connaissance_id" value="<?php echo $choixConnaissance->id; ?>" />
					<input type="submit" name="save_choix_connaissance" value="Enregistrer" />
				</div>
			</form>
			<div>
				<h3>Ajouter une connaissance</h3>
				<form method="post" action="?s=admin&a=updateChoixConnaissance&i=<?php echo $choixConnaissance->id; ?>">
				<div>
					<label for="connaissance">Connaissance :</label>
					<select name="connaissance" id="connaissance">
<?php
	foreach( $list_connaissances as $id => $nom ){
		if( !array_key_exists( $id, $connaissances ) ){
?>
						<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="choix_connaissance_id" value="<?php echo $choixConnaissance->id; ?>" />
					<input type="submit" name="add_connaissance" value="Ajouter" />
				</form>
			</div>
		</div>