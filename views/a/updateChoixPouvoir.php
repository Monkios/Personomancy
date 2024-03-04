		<h2>Modification d'un choix de pouvoir</h2>
		<div>
			<form method="post" action="?s=admin&a=updateChoixPouvoir&i=<?php echo $choixPouvoir->id; ?>">
				<div>
					<label for="choix_pouvoir_nom">Nom :</label>
					<input type="text" name="choix_pouvoir_nom" id="choix_pouvoir_nom" value="<?php echo utf8_encode( $choixPouvoir->nom ); ?>" />
				</div>
				<div>
					<label for="choix_pouvoir_active">Est activée</label>
					<input type="checkbox" name="choix_pouvoir_active" id="choix_pouvoir_active"<?php if( $choixPouvoir->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Pouvoirs associées</h3>
					<ul>
<?php
	foreach( $pouvoirs as $id => $pouvoir ){
?>
						<li>
							<?php echo utf8_encode( $pouvoir->nom ); ?>
							<button type="submit" name="delete_pouvoir" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette pouvoir de ce choix de pouvoirs ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="choix_pouvoir_id" value="<?php echo $choixPouvoir->id; ?>" />
					<input type="submit" name="save_choix_pouvoir" value="Enregistrer" />
				</div>
			</form>
			<div>
				<h3>Ajouter une pouvoir</h3>
				<form method="post" action="?s=admin&a=updateChoixPouvoir&i=<?php echo $choixPouvoir->id; ?>">
				<div>
					<label for="pouvoir">Pouvoir :</label>
					<select name="pouvoir" id="pouvoir">
<?php
	foreach( $list_pouvoirs as $id => $nom ){
		if( !array_key_exists( $id, $pouvoirs ) ){
?>
						<option value="<?php echo $id; ?>"><?php echo utf8_encode( $nom ); ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="choix_pouvoir_id" value="<?php echo $choixPouvoir->id; ?>" />
					<input type="submit" name="add_pouvoir" value="Ajouter" />
				</form>
			</div>
		</div>