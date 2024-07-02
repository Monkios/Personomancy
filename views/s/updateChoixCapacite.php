		<h2>Modification d'un choix de capacité</h2>
		<a href="?s=super&a=listChoixCapacites">Retourner à la liste</a>
		<div>
			<form method="post" action="?s=super&a=updateChoixCapacite&i=<?php echo $choixCapacite->id; ?>">
				<div>
					<label for="choix_capacite_nom">Nom :</label>
					<input type="text" name="choix_capacite_nom" id="choix_capacite_nom" value="<?php echo $choixCapacite->nom; ?>" />
				</div>
				<div>
					<label for="choix_capacite_active">Est activée</label>
					<input type="checkbox" name="choix_capacite_active" id="choix_capacite_active"<?php if( $choixCapacite->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Capacités associées</h3>
					<ul>
<?php
	foreach( $capacites as $id => $capacite ){
?>
						<li>
							<?php echo $capacite->nom; ?>
							<button type="submit" name="delete_capacite" value="<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment retirer cette capacité de ce choix de capacités ?');">X</button>
						</li>
<?php
	}
?>
					</ul>
				</div>
				<div>
					<input type="hidden" name="choix_capacite_id" value="<?php echo $choixCapacite->id; ?>" />
					<input type="submit" name="save_choix_capacite" value="Enregistrer" />
				</div>
			</form>
			<div>
				<h3>Ajouter une capacité</h3>
				<form method="post" action="?s=super&a=updateChoixCapacite&i=<?php echo $choixCapacite->id; ?>">
				<div>
					<label for="capacite">Capacité :</label>
					<select name="capacite" id="capacite">
						<option value="">Veuillez sélectionner un élément...</option>
<?php
	foreach( $list_capacites as $id => $nom ){
		if( !array_key_exists( $id, $capacites ) ){
?>
						<option value="<?php echo $id; ?>"><?php echo $nom; ?></option>
<?php
		}
	}
?>
						</select>
					</div>
					<input type="hidden" name="choix_capacite_id" value="<?php echo $choixCapacite->id; ?>" />
					<input type="submit" name="add_capacite" value="Ajouter" />
				</form>
			</div>
		</div>