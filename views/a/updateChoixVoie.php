		<h2>Modification d'un choix de voie</h2>
		<a href="?s=admin&a=listChoixVoies">Retourner à la liste</a>
		<div>
			<form method="post" action="?s=admin&a=updateChoixVoie&i=<?php echo $choixVoie->id; ?>">
				<div>
					<label for="choix_voie_nom">Nom :</label>
					<input type="text" name="choix_voie_nom" id="choix_voie_nom" value="<?php echo $choixVoie->nom; ?>" />
				</div>
				<div>
					<label for="choix_voie_active">Est activée</label>
					<input type="checkbox" name="choix_voie_active" id="choix_voie_active"<?php if( $choixVoie->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Voies associées</h3>
					<ul>
<?php
	foreach( $voies as $id => $voie ){
?>
						<li>
							<?php echo $voie->nom; ?>
							<button type="submit" name="delete_voie" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette voie de ce choix de voies ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="choix_voie_id" value="<?php echo $choixVoie->id; ?>" />
					<input type="submit" name="save_choix_voie" value="Enregistrer" />
				</div>
			</form>
			<div>
				<h3>Ajouter une voie</h3>
				<form method="post" action="?s=admin&a=updateChoixVoie&i=<?php echo $choixVoie->id; ?>">
				<div>
					<label for="voie">Voie :</label>
					<select name="voie" id="voie">
						<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_voies as $id => $nom ){
		if( !array_key_exists( $id, $voies ) ){
?>
						<option value="<?php echo $id; ?>"><?php echo $nom; ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="choix_voie_id" value="<?php echo $choixVoie->id; ?>" />
					<input type="submit" name="add_voie" value="Ajouter" />
				</form>
			</div>
		</div>