		<h2>Modification d'une connaissance</h2>
		<div>
			<form method="post" action="?s=admin&a=updateConnaissance&i=<?php echo $connaissance->id; ?>">
				<div>
					<label for="connaissance_nom">Nom :</label>
					<input type="text" name="connaissance_nom" id="connaissance_nom" value="<?php echo $connaissance->nom; ?>" />
				</div>
				<div>
					<label for="connaissance_description">Description :</label>
					<input type="text" name="connaissance_description" id="connaissance_description" value="<?php echo $connaissance->description; ?>" />
				</div>
				<div>
					<label for="connaissance_cout">Coût :</label>
					<input type="number" min="1" max="4" name="connaissance_cout" id="connaissance_cout" value="<?php echo $connaissance->cout; ?>" />
				</div>
				<div>
					<label for="connaissance_type">Type :</label>
					<input type="text" id="connaissance_type" readonly="readonly" value="<?php echo $connaissance->GetConnaissanceType(); ?>" />
				</div>
				<div>
					<label for="connaissance_active">Est activée</label>
					<input type="checkbox" name="connaissance_active" id="connaissance_active"<?php if( $connaissance->active == 1 ) echo "checked='checked'"; ?> />
				</div>
				<div>
					<h3>Prérequis</h3>
					<div>
						<label for="connaissance_prereq_capacite">Capacité légendaire</label>
						<select id="connaissance_prereq_capacite" name="connaissance_prereq_capacite">
							<option value="">--- n.a. ---</option>
<?php
	foreach( $list_capacites as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_capacite ){ echo " selected='selected'"; } ?>><?php echo $nom; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_prereq_voie_primaire">Voie obligatoire</label>
						<select id="connaissance_prereq_voie_primaire" name="connaissance_prereq_voie_primaire">
<?php
	foreach( $list_voies as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_voie_primaire ){ echo " selected='selected'"; } ?>><?php echo $nom; ?></option>
<?php
	}
?>
						</select>
					</div>
					<div>
						<label for="connaissance_prereq_voie_secondaire">Voie synergique</label>
						<select id="connaissance_prereq_voie_secondaire" name="connaissance_prereq_voie_secondaire">
							<option value="">--- n.a. ---</option>
<?php
	foreach( $list_voies as $id => $nom ){
?>
							<option value="<?php echo $id; ?>"<?php if( $id == $connaissance->prereq_voie_secondaire ){ echo " selected='selected'"; } ?>><?php echo $nom; ?></option>
<?php
	}
?>
						</select>
					</div>
				</div>
				<div>
					<input type="hidden" name="connaissance_id" value="<?php echo $connaissance->id; ?>" />
					<input type="submit" name="save_connaissance" value="Enregistrer" />
				</div>
			</form>
		</div>